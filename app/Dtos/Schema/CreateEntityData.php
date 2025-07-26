<?php

namespace App\Dtos\Schema;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateEntityData extends Data
{
    /**
     * @param string $name
     * @param string|null $slug
     * @param string|null $description
     */
    public function __construct(
        #[Required, Max(255)]
        public string $name,
        #[Max(255), Unique('entities', 'slug')]
        public ?string $slug = null,
        public ?string $description = null,
    ) {
        // Generate slug from name if not provided
        if (is_null($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }
}
