<?php

namespace App\Services\Schema\Contracts;

use App\Models\Entity;

interface EntityFormSchemaServiceInterface
{
    /**
     * Generate form schema for a given entity
     *
     * @param Entity $entity
     * @param string $requestType The type of request ('POST' for create, 'PUT' or 'PATCH' for update)
     * @param int|null $recordId The ID of the record being updated (for PUT/PATCH requests)
     * @return array
     */
    public function generateFormSchema(Entity $entity, string $requestType = 'POST', ?int $recordId = null): array;
}
