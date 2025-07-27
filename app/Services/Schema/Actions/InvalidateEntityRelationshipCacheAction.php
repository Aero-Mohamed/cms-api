<?php

namespace App\Services\Schema\Actions;

use App\Models\Entity;
use App\Models\EntityRelationship;
use App\Services\Schema\Support\EntityFormCacheKeyGenerator;
use Illuminate\Support\Facades\Cache;

class InvalidateEntityRelationshipCacheAction
{
    public function __construct(
        protected InvalidateEntityCacheAction $invalidateEntityCacheAction
    ) {
    }

    /**
     * @param EntityRelationship $relationship
     * @return void
     */
    public function handler(EntityRelationship $relationship): void
    {
        /** @var Entity $entityTo */
        $entityTo = $relationship->toEntity()->first();
        /** @var Entity $entityFrom */
        $entityFrom = $relationship->fromEntity()->first();

        $this->invalidateEntityCacheAction->handler($entityTo);
        $this->invalidateEntityCacheAction->handler($entityFrom);
    }
}
