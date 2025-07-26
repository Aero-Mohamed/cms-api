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
    ) {
    }

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
            $isSource = $relationship->getAttribute('from_entity_id') === $entity->getKey();
            /** @var Entity $relatedEntity */
            $relatedEntity = $isSource
                ? $relationship->toEntity()->first()
                : $relationship->fromEntity()->first();

            $fieldName = $isSource
                ? $relationship->getAttribute('name')
                : $relationship->getAttribute('inverse_name');

            $field = [
                'name' => $fieldName,
                'label' => ucfirst(str_replace('_', ' ', $fieldName)),
                'related_entity' => $relatedEntity->getAttribute('slug'),
                'relationship_type' => $relationship->getAttribute('type')->value,
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
