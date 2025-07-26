<?php

namespace App\Services\Schema\Support;

use App\Enums\RelationshipTypeEnum;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;

class EntityRelationshipFieldGenerator
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param EntityRepositoryInterface $entityRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository,
        protected EntityRepositoryInterface $entityRepository,
    ) {}

    /**
     * Generate relationship fields for a given entity
     *
     * @param Entity $entity
     * @return array
     */
    public function generate(Entity $entity): array
    {
        $relationshipFields = [];
        $relationships = $this->entityRepository->getEntityRelationships($entity);

        foreach ($relationships as $relationship) {
            $isSource = $relationship->from_entity_id === $entity->id;
            $relatedEntity = $isSource
                ? $relationship->toEntity
                : $relationship->fromEntity;

            $fieldName = $isSource
                ? $relationship->name
                : $relationship->inverse_name;

            $field = [
                'name' => $fieldName,
                'label' => ucfirst(str_replace('_', ' ', $fieldName)),
                'related_entity' => $relatedEntity->slug,
                'relationship_type' => $relationship->type->value,
                'is_source' => $isSource,
            ];

            // Determine a field type based on a relationship type
            switch ($relationship->type) {
                case RelationshipTypeEnum::ONE_TO_ONE:
                    $field['type'] = 'select';
                    $field['multiple'] = false;
                    break;
                case RelationshipTypeEnum::ONE_TO_MANY:
                    if ($isSource) {
                        $field['type'] = 'select';
                        $field['multiple'] = true;
                    } else {
                        $field['type'] = 'select';
                        $field['multiple'] = false;
                    }
                    break;
                case RelationshipTypeEnum::MANY_TO_MANY:
                    $field['type'] = 'select';
                    $field['multiple'] = true;
                    break;
            }

            $relationshipFields[] = $field;
        }

        return $relationshipFields;
    }

}
