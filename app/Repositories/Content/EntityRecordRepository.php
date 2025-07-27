<?php

namespace App\Repositories\Content;

use App\Enums\RelationshipTypeEnum;
use App\Models\Entity;
use App\Models\EntityRelationship;
use App\Models\EntityValue;
use App\Models\Record;
use App\Models\RecordRelationship;
use App\Repositories\Content\Contracts\EntityRecordRepositoryInterface;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use App\Services\Schema\Support\EntityRelationshipFieldGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntityRecordRepository implements EntityRecordRepositoryInterface
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param EntityRepositoryInterface $entityRepository
     * @param EntityRelationshipFieldGenerator $relationshipFieldGenerator
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository,
        protected EntityRepositoryInterface $entityRepository,
        protected EntityRelationshipFieldGenerator $relationshipFieldGenerator,
    ) {
    }

    /**
     * Get a paginated list of records for the given entity with their attributes
     *
     * @param Entity $entity The entity to get records from
     * @param int $perPage Number of records per page
     * @param int $page Current page number
     * @return LengthAwarePaginator Paginated records
     */
    public function index(Entity $entity, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        // Start building the query for records
        $recordsQuery = Record::query()
            ->where('entity_id', $entity->getKey())
            ->orderBy('created_at', 'desc');

        return $recordsQuery->paginate($perPage, ['*'], 'page', $page);
    }


    /**
     * @param Entity $entity
     * @param LengthAwarePaginator $records
     * @return Collection
     */
    public function recordsValues(Entity $entity, LengthAwarePaginator $records): Collection
    {
        $recordIds = $records->pluck('id')->toArray();
        return EntityValue::query()
            ->where('entity_id', $entity->getKey())
            ->whereIn('record_id', $recordIds)
            ->get()
            ->groupBy('record_id');
    }

    /**
     * Create a new record for the given entity with the provided data
     *
     * @param Entity $entity The entity to create a record for
     * @param array $data The data for the record's attributes
     * @return Record The created record
     */
    public function create(Entity $entity, array $data): Record
    {
        return DB::transaction(function () use ($entity, $data) {
            // Create the record
            $record = Record::query()->create([
                'entity_id' => $entity->getKey(),
                'created_by' => Auth::id(),
            ]);

            // Get all attributes for this entity
            $attributes = $this->attributeRepository->getAttributesForEntity($entity);
            $attributesBySlug = $attributes->keyBy('slug');

            // Get all relationship fields for this entity
            $relationshipFields = $this->relationshipFieldGenerator->generate($entity);
            $relationshipFieldsByName = collect($relationshipFields)->keyBy('name');

            // Create entity values for each attribute in the data
            foreach ($data as $key => $value) {
                if ($relationshipFieldsByName->has($key)) {
                    continue;
                }

                if (!$attributesBySlug->has($key)) {
                    continue;
                }

                $attribute = $attributesBySlug->get($key);

                EntityValue::query()->create([
                    'entity_id' => $entity->getKey(),
                    'record_id' => $record->getKey(),
                    'attribute_id' => $attribute->getKey(),
                    'value' => $value,
                ]);
            }

            // Handle relationships
            $relationships = $this->entityRepository->getEntityRelationships($entity);

            foreach ($relationships as $relationship) {
                $isSource = $relationship->from_entity_id === $entity->id;
                $fieldName = $isSource ? $relationship->name : $relationship->inverse_name;

                if (!isset($data[$fieldName])) {
                    continue;
                }

                $relatedRecordIds = is_array($data[$fieldName])
                    ? $data[$fieldName]
                    : [$data[$fieldName]];

                foreach ($relatedRecordIds as $relatedRecordId) {
                    // Create the record relationship
                    RecordRelationship::query()->create([
                        'relationship_id' => $relationship->id,
                        'from_record_id' => $isSource ? $record->id : $relatedRecordId,
                        'to_record_id' => $isSource ? $relatedRecordId : $record->id,
                    ]);
                }
            }

            return $record;
        });
    }

    /**
     * Get a record with specific attributes and all relations with their attributes
     *
     * @param Entity $entity The entity to get the record from
     * @param int $recordId The ID of the record to retrieve
     * @return array The record data with attributes and relations
     */
    public function findById(Entity $entity, int $recordId): array
    {
        // Find the record
        $record = Record::query()->where('id', $recordId)
            ->where('entity_id', $entity->getKey())
            ->firstOrFail();

        // Get all attributes for this entity
        $entityAttributes = $this->attributeRepository->getAttributesForEntity($entity);

        // Get entity values for the record
        $query = EntityValue::query()
            ->where('record_id', $record->getKey())
            ->where('entity_id', $entity->getKey());

        $entityValues = $query->get()->keyBy('attribute_id');

        $result = [
            'id' => $record->id,
        ];

        // Add attribute values
        foreach ($entityAttributes as $attr) {
            $value = $entityValues->get($attr->getKey());
            $result[$attr->slug] = $value->value ?? null;
        }

        $incomingRelationships = RecordRelationship::query()
            ->where('to_record_id', $record->id)
            ->with(['relationship', 'fromRecord'])
            ->get();

        foreach ($incomingRelationships as $rel) {
            /** @var EntityRelationship $relationship */
            $relationship = $rel->relationship;
            /** @var Record|null $relatedRecord */
            $relatedRecord = $rel->fromRecord;
            /** @var Entity|null $relatedEntity */
            $relatedEntity = $this->entityRepository->findById($relationship->getAttribute('from_entity_id'));

            if (!$relatedEntity || !$relatedRecord) {
                continue;
            }

            // Get related record's attributes
            $relatedValues = EntityValue::query()
                ->where('record_id', $relatedRecord->getKey())
                ->where('entity_id', $relatedEntity->getKey())
                ->get()->keyBy('attribute_id');

            $relatedAttributes = $this->attributeRepository->getAttributesForEntity($relatedEntity);

            $relatedData = [
                'id' => $relatedRecord->getKey(),
            ];

            foreach ($relatedAttributes as $attr) {
                $value = $relatedValues->get($attr->getKey());
                $relatedData[$attr->getAttribute('slug')] = $value->value ?? null;
            }

            $relatedData = [...$relatedData, ...$relatedRecord->only('created_at', 'updated_at')];

            if (!isset($result[$relationship->getAttribute('inverse_name')])) {
                $result[$relationship->getAttribute('inverse_name')] = [];
            }

            if ($relationship->type == RelationshipTypeEnum::MANY_TO_MANY) {
                $result[$relationship->getAttribute('inverse_name')][] = $relatedData;
            } else {
                $result[$relationship->getAttribute('inverse_name')] = $relatedData;
            }
        }


        return [
            ...$result,
            ...$record->only('created_at', 'updated_at')
        ];
    }
}
