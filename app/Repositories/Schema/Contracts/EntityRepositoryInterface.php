<?php

namespace App\Repositories\Schema\Contracts;

use App\Dtos\Schema\CreateEntityData;
use App\Dtos\Schema\CreateEntityRelationshipData;
use App\Dtos\Schema\UpdateEntityData;
use App\Models\Entity;
use App\Models\EntityRelationship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntityRepositoryInterface
{
    /**
     * Get all entities with pagination
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find entity by ID
     *
     * @param int $id
     * @return Entity|null
     */
    public function findById(int $id): ?Entity;

    /**
     * Find entity by slug
     *
     * @param string $slug
     * @return Entity|null
     */
    public function findBySlug(string $slug): ?Entity;

    /**
     * Create a new entity
     *
     * @param CreateEntityData $data
     * @return Entity
     */
    public function create(CreateEntityData $data): Entity;

    /**
     * Update an existing entity
     *
     * @param Entity $entity
     * @param UpdateEntityData $data
     * @return Entity
     */
    public function update(Entity $entity, UpdateEntityData $data): Entity;

    /**
     * Delete an entity
     *
     * @param Entity $entity
     * @return bool
     */
    public function delete(Entity $entity): bool;

    /**
     * Create a new entity relationship
     *
     * @param CreateEntityRelationshipData $data
     * @return EntityRelationship
     */
    public function createRelationship(CreateEntityRelationshipData $data): EntityRelationship;

    /**
     * Delete an entity relationship
     *
     * @param EntityRelationship $relationship
     * @return bool
     */
    public function deleteRelationship(EntityRelationship $relationship): bool;

    /**
     * Get relationships for a specific entity
     *
     * @param Entity $entity
     * @return Collection<EntityRelationship>
     */
    public function getEntityRelationships(Entity $entity): Collection;
}
