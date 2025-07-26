<?php

namespace App\Dtos\Schema;

use App\Enums\RelationshipTypeEnum;
use App\Models\Entity;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\DataProperty;

class CreateEntityRelationshipData extends Data
{
    /**
     * @param RelationshipTypeEnum $type
     * @param int $from_entity_id
     * @param int $to_entity_id
     * @param string|null $name
     * @param string|null $inverse_name
     */
    public function __construct(
        #[Required, Enum(RelationshipTypeEnum::class)]
        public RelationshipTypeEnum $type,

        #[Required, Exists('entities', 'id')]
        public int $from_entity_id,

        #[Required, Exists('entities', 'id')]
        public int $to_entity_id,

        #[Max(100)]
        public ?string $name = null,

        #[Max(100)]
        public ?string $inverse_name = null,
    ) {
        // Generate names if not provided
        $this->generateNames();
    }

    /**
     * Generate relationship names based on entity slugs and relationship type
     *
     * @return void
     */
    protected function generateNames(): void
    {
        // Only generate names if they are not provided
        $fromEntity = Entity::query()->find($this->from_entity_id);
        $toEntity = Entity::query()->find($this->to_entity_id);

        if (! $fromEntity || ! $toEntity) {
            return;
        }

        $fromSlug = Str::singular($fromEntity->slug);
        $toSlug = Str::singular($toEntity->slug);

        switch ($this->type) {
            case RelationshipTypeEnum::ONE_TO_ONE:
                if ($this->name === null) {
                    $this->name = "{$fromSlug}_{$toSlug}";
                }
                if ($this->inverse_name === null) {
                    $this->inverse_name = "{$toSlug}_{$fromSlug}";
                }
                break;

            case RelationshipTypeEnum::ONE_TO_MANY:
                if ($this->name === null) {
                    $this->name = "{$fromSlug}_" . Str::plural($toSlug);
                }
                if ($this->inverse_name === null) {
                    $this->inverse_name = "{$toSlug}_{$fromSlug}";
                }
                break;

            case RelationshipTypeEnum::MANY_TO_MANY:
                if ($this->name === null) {
                    $this->name = "{$fromSlug}_" . Str::plural($toSlug);
                }
                if ($this->inverse_name === null) {
                    $this->inverse_name = "{$toSlug}_" . Str::plural($fromSlug);
                }
                break;
        }
    }

}
