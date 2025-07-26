<?php

namespace App\Services\Schema;

use App\Enums\DataTypeEnum;
use App\Enums\RelationshipTypeEnum;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use App\Services\Schema\Contracts\EntityFormSchemaServiceInterface;
use App\Services\Schema\Support\EntityFieldGenerator;
use App\Services\Schema\Support\EntityRelationshipFieldGenerator;
use App\Services\Schema\Support\EntityValidationRuleGenerator;
use Illuminate\Validation\Rule;

class EntityFormSchemaService implements EntityFormSchemaServiceInterface
{
    /**
     * @param EntityFieldGenerator $fieldGenerator
     * @param EntityValidationRuleGenerator $validationRuleGenerator
     * @param EntityRelationshipFieldGenerator $relationshipFieldGenerator
     */
    public function __construct(
        protected EntityFieldGenerator             $fieldGenerator,
        protected EntityValidationRuleGenerator    $validationRuleGenerator,
        protected EntityRelationshipFieldGenerator $relationshipFieldGenerator,
    ) {
    }

    /**
     * Generate form schema for a given entity
     *
     * @param Entity $entity
     * @param string $requestType The type of request ('POST' for creation, 'PUT' or 'PATCH' for update)
     * @param int|null $recordId The ID of the record being updated (for PUT/PATCH requests)
     * @return array
     */
    public function generateFormSchema(Entity $entity, string $requestType = 'POST', ?int $recordId = null): array
    {
        return [
            'fields' => $this->fieldGenerator->generate($entity),
            'validation_rules' => $this->validationRuleGenerator->generate($entity, $requestType, $recordId),
            'relationship_fields' => $this->relationshipFieldGenerator->generate($entity),
        ];
    }

}
