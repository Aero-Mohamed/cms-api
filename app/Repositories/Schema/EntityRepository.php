<?php

namespace App\Repositories\Schema;

use App\Dtos\Schema\CreateEntityData;
use App\Dtos\Schema\UpdateEntityData;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class EntityRepository implements EntityRepositoryInterface
{
    /**
     * Get all entities with pagination
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Entity::query()->latest()->paginate();
    }

    /**
     * Find entity by ID
     *
     * @param int $id
     * @return Entity|null
     */
    public function findById(int $id): ?Entity
    {
        return Entity::query()->find($id);
    }

    /**
     * Find entity by slug
     *
     * @param string $slug
     * @return Entity|null
     */
    public function findBySlug(string $slug): ?Entity
    {
        return Entity::query()->where('slug', $slug)->first();
    }

    /**
     * Create a new entity
     *
     * @param CreateEntityData $data
     * @return Entity
     */
    public function create(CreateEntityData $data): Entity
    {
        $data = $data->toArray();
        $data['created_by'] = Auth::id();
        return Entity::query()->create($data);
    }

    /**
     * Update an existing entity
     *
     * @param Entity $entity
     * @param UpdateEntityData $data
     * @return Entity
     */
    public function update(Entity $entity, UpdateEntityData $data): Entity
    {
        $data = array_filter($data->toArray(), fn($i) => !is_null($i));
        $entity->update($data);
        return $entity;
    }

    /**
     * Delete an entity
     *
     * @param Entity $entity
     * @return bool
     */
    public function delete(Entity $entity): bool
    {
        return $entity->delete();
    }
}
