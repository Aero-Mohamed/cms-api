<?php

namespace App\Dtos\Schema;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AttributeEntityData extends Data
{
    /**
     * @param int $attribute_id
     * @param int $entity_id
     */
    public function __construct(
        #[Required, Exists('attributes', 'id')]
        public int $attribute_id,
        #[Required, Exists('entities', 'id')]
        public int $entity_id,
    ) {
    }
}
