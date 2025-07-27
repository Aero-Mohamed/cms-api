<?php

namespace App\Dtos\Schema;

use App\Enums\DataTypeEnum;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UpdateAttributeData extends Data
{
    /**
     * @param string|null $name
     * @param string|null $slug
     * @param DataTypeEnum|null $data_type
     * @param bool|null $is_required
     * @param bool|null $is_unique
     * @param string|null $default_value
     */
    public function __construct(
        #[Sometimes, Max(255)]
        public ?string $name = null,
        #[Sometimes, Max(255)]
        #[Unique('attributes', 'slug', ignore: new RouteParameterReference('attribute', 'id'))]
        public ?string $slug = null,
        #[Sometimes, Enum(DataTypeEnum::class)]
        public ?DataTypeEnum $data_type = null,
        public ?bool $is_required = null,
        public ?bool $is_unique = null,
        public ?string $default_value = null,
    ) {
    }
}
