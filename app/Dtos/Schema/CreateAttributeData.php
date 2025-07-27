<?php

namespace App\Dtos\Schema;

use App\Enums\DataTypeEnum;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateAttributeData extends Data
{
    /**
     * @param string $name
     * @param string|null $slug
     * @param DataTypeEnum $data_type
     * @param bool $is_required
     * @param bool $is_unique
     * @param string|null $default_value
     */
    public function __construct(
        #[Required, Max(255)]
        public string $name,
        #[Required, Enum(DataTypeEnum::class)]
        public DataTypeEnum $data_type,
        public bool $is_required = false,
        public bool $is_unique = false,
        #[Max(255), Unique('attributes', 'slug')]
        public ?string $slug = null,
        public ?string $default_value = null,
    ) {
        // Generate slug from name if not provided
        if (is_null($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }
}
