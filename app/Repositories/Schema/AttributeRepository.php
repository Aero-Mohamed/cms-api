<?php

namespace App\Repositories\Schema;

use App\Dtos\Schema\CreateAttributeData;
use App\Dtos\Schema\UpdateAttributeData;
use App\Models\Attribute;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Services\Schema\Support\EntityFormCacheKeyGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AttributeRepository implements AttributeRepositoryInterface
{
    /**
     * Get all attributes with pagination
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Attribute::query()->latest()->paginate();
    }

    /**
     * Find attribute by ID
     *
     * @param int $id
     * @return Attribute|null
     */
    public function findById(int $id): ?Attribute
    {
        return Attribute::query()->find($id);
    }

    /**
     * Find attribute by slug
     *
     * @param string $slug
     * @return Attribute|null
     */
    public function findBySlug(string $slug): ?Attribute
    {
        return Attribute::query()->where('slug', $slug)->first();
    }

    /**
     * Create a new attribute
     *
     * @param CreateAttributeData $data
     * @return Attribute
     */
    public function create(CreateAttributeData $data): Attribute
    {
        $data = $data->toArray();
        $data['created_by'] = Auth::id();
        return Attribute::query()->create($data);
    }

    /**
     * Update an existing attribute
     *
     * @param Attribute $attribute
     * @param UpdateAttributeData $data
     * @return Attribute
     */
    public function update(Attribute $attribute, UpdateAttributeData $data): Attribute
    {
        $data = array_filter($data->toArray(), fn($i) => !is_null($i));
        $attribute->update($data);
        return $attribute;
    }

    /**
     * Delete an attribute
     *
     * @param Attribute $attribute
     * @return bool
     */
    public function delete(Attribute $attribute): bool
    {
        return $attribute->delete();
    }

    /**
     * Attach an attribute to an entity
     *
     * @param Attribute $attribute
     * @param Entity $entity
     * @return bool
     */
    public function attachToEntity(Attribute $attribute, Entity $entity): bool
    {
        if (!$entity->attributes()->where('attribute_id', $attribute->getKey())->exists()) {
            $entity->attributes()->attach($attribute);
            return true;
        }
        return false;
    }

    /**
     * Detach an attribute from an entity
     *
     * @param Attribute $attribute
     * @param Entity $entity
     * @return bool
     */
    public function detachFromEntity(Attribute $attribute, Entity $entity): bool
    {
        return $entity->attributes()->detach($attribute) > 0;
    }

    /**
     * Get all attributes for an entity
     *
     * @param Entity $entity
     * @return Collection
     */
    public function getAttributesForEntity(Entity $entity): Collection
    {
        $key = EntityFormCacheKeyGenerator::attributes($entity);
        return Cache::rememberForever($key, function () use ($entity) {
            return $entity->attributes()->get();
        });
    }
}
