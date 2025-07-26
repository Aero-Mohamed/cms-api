<?php

namespace App\Dtos\Schema;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UpdateEntityData extends Data
{
    /**
     * @param string|null $name
     * @param string|null $slug
     * @param string|null $description
     */
    public function __construct(
        #[Sometimes, Max(255)]
        public ?string $name = null,
        #[Sometimes, Max(255)]
        #[Unique('entities', 'slug', ignore: new RouteParameterReference('entity', 'id'))]
        public ?string $slug = null,
        #[Nullable]
        public ?string $description = null,
    ) {
    }
}
