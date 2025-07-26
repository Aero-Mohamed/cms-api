<?php

namespace App\Repositories\Schema\Contracts;

use App\Dtos\Schema\CreateAttributeData;
use App\Dtos\Schema\UpdateAttributeData;
use App\Models\Attribute;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AttributeRepositoryInterface
{
    /**
     * Get all attributes with pagination
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find attribute by ID
     *
     * @param int $id
     * @return Attribute|null
     */
    public function findById(int $id): ?Attribute;

    /**
     * Find attribute by slug
     *
     * @param string $slug
     * @return Attribute|null
     */
    public function findBySlug(string $slug): ?Attribute;

    /**
     * Create a new attribute
     *
     * @param CreateAttributeData $data
     * @return Attribute
     */
    public function create(CreateAttributeData $data): Attribute;

    /**
     * Update an existing attribute
     *
     * @param Attribute $attribute
     * @param UpdateAttributeData $data
     * @return Attribute
     */
    public function update(Attribute $attribute, UpdateAttributeData $data): Attribute;

    /**
     * Delete an attribute
     *
     * @param Attribute $attribute
     * @return bool
     */
    public function delete(Attribute $attribute): bool;

    /**
     * Attach an attribute to an entity
     *
     * @param Attribute $attribute
     * @param Entity $entity
     * @return bool
     */
    public function attachToEntity(Attribute $attribute, Entity $entity): bool;

    /**
     * Detach an attribute from an entity
     *
     * @param Attribute $attribute
     * @param Entity $entity
     * @return bool
     */
    public function detachFromEntity(Attribute $attribute, Entity $entity): bool;

    /**
     * Get all attributes for an entity
     *
     * @param Entity $entity
     * @return Collection
     */
    public function getAttributesForEntity(Entity $entity): Collection;
}
