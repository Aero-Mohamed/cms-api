<?php

namespace App\Services\Schema\Support;

use App\Enums\DataTypeEnum;
use App\Enums\RelationshipTypeEnum;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use Illuminate\Validation\Rule;

class EntityValidationRuleGenerator
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param EntityRepositoryInterface $entityRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository,
        protected EntityRepositoryInterface $entityRepository,
    ) {
    }

    /**
     * Generate validation rules for a given entity based on its attributes and relationships
     *
     * @param Entity $entity
     * @param string $requestType The type of request ('POST' for creation, 'PUT' or 'PATCH' for update)
     * @param int|null $recordId The ID of the record being updated (for PUT/PATCH requests)
     * @return array
     */
    public function generate(Entity $entity, string $requestType = 'POST', ?int $recordId = null): array
    {
        $rules = [];
        $isUpdate = in_array(strtoupper($requestType), ['PUT', 'PATCH']);

        // Generate rules for attributes
        $attributes = $this->attributeRepository->getAttributesForEntity($entity);

        foreach ($attributes as $attribute) {
            $fieldRules = [];

            // Add the required rule if the attribute is required
            // For PUT/PATCH requests, fields are optional unless it's a PUT request
            if ($attribute->getAttribute('is_required') && (!$isUpdate || strtoupper($requestType) === 'PUT')) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Add type-specific validation rules
            switch ($attribute->getAttribute('data_type')) {
                case DataTypeEnum::STRING:
                    $fieldRules[] = 'string';
                    break;
                case DataTypeEnum::INTEGER:
                    $fieldRules[] = 'integer';
                    break;
                case DataTypeEnum::FLOAT:
                    $fieldRules[] = 'numeric';
                    break;
                case DataTypeEnum::DATE:
                    $fieldRules[] = 'date';
                    break;
                case DataTypeEnum::BOOLEAN:
                    $fieldRules[] = 'boolean';
                    break;
            }

            if ($attribute->getAttribute('is_unique')) {
                $uniqueRule = Rule::unique('entity_values', 'value')
                    ->where('entity_id', $entity->getKey())
                    ->where('attribute_id', $attribute->getKey());

                if ($isUpdate && $recordId) {
                    $uniqueRule->whereNot('record_id', $recordId);
                }

                $fieldRules[] = $uniqueRule;
            }

            $rules[$attribute->slug] = $fieldRules;
        }

        // Generate rules for relationships
        $relationships = $this->entityRepository->getEntityRelationships($entity);

        foreach ($relationships as $relationship) {
            $isSource = $relationship->from_entity_id === $entity->id;
            $fieldName = $isSource ? $relationship->name : $relationship->inverse_name;
            $relationshipRules = [];

            // Get the related entity ID
            $relatedEntityId = $isSource ? $relationship->to_entity_id : $relationship->from_entity_id;

            // For PUT/PATCH requests, all relationship fields are optional
            // For POST requests, we might want to require some relationships based on business rules
            $relationshipRules[] = 'nullable';

            // Determine validation rules based on a relationship type
            switch ($relationship->type) {
                case RelationshipTypeEnum::ONE_TO_ONE:
                    // For one-to-one, we expect a single ID
                    $relationshipRules[] = 'integer';
                    $relationshipRules[] = "exists:records,id,entity_id,$relatedEntityId";
                    break;

                case RelationshipTypeEnum::ONE_TO_MANY:
                    if ($isSource) {
                        // For one-to-many (source), we expect an array of IDs
                        $relationshipRules[] = 'array';
                        $relationshipRules[] = 'min:0';
                        $rules[$fieldName . '.*'] = "integer|exists:records,id,entity_id,$relatedEntityId";
                    } else {
                        // For one-to-many (target), we expect a single ID
                        $relationshipRules[] = 'integer';
                        $relationshipRules[] = "exists:records,id,entity_id,$relatedEntityId";
                    }
                    break;

                case RelationshipTypeEnum::MANY_TO_MANY:
                    // For many-to-many, we expect an array of IDs
                    $relationshipRules[] = 'array';
                    $relationshipRules[] = 'min:0';
                    $rules[$fieldName . '.*'] = "integer|exists:records,id,entity_id,$relatedEntityId";
                    break;
            }

            $rules[$fieldName] = $relationshipRules;
        }

        return $rules;
    }
}
