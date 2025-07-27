<?php

namespace App\Services\Schema\Actions;

use App\Models\Entity;
use App\Services\Schema\Support\EntityFormCacheKeyGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class InvalidateEntityCacheAction
{
    /**
     * @param Entity|Collection $entities
     * @return void
     */
    public function handler(Entity|Collection $entities): void
    {
        if ($entities instanceof Entity) {
            $entities = collect([$entities]);
        }

        /** @var Entity $entity */
        foreach ($entities as $entity) {
            foreach (EntityFormCacheKeyGenerator::allKeys($entity) as $key) {
                Cache::forget($key);
            }
        }
    }
}
