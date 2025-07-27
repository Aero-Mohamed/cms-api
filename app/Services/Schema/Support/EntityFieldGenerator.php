<?php

namespace App\Services\Schema\Support;

use App\Enums\DataTypeEnum;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class EntityFieldGenerator
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository
    ) {
    }

    /**
     * Generate fields for a given entity based on its attributes
     *
     * @param Entity $entity
     * @return array
     */
    public function generate(Entity $entity): array
    {
        return Cache::rememberForever(EntityFormCacheKeyGenerator::fields($entity), function () use ($entity) {
            $fields = [];
            $attributes = $this->attributeRepository->getAttributesForEntity($entity);

            foreach ($attributes as $attribute) {
                $field = [
                    'name' => $attribute->getAttribute('slug'),
                    'label' => $attribute->getAttribute('name'),
                    'type' => $this->mapDataTypeToFieldType($attribute->getAttribute('data_type')),
                    'required' => $attribute->getAttribute('is_required'),
                    'default_value' => $attribute->getAttribute('default_value'),
                ];

                $fields[] = $field;
            }

            return $fields;
        });
    }

    /**
     * Map data type to field type
     *
     * @param DataTypeEnum $dataType
     * @return string
     */
    protected function mapDataTypeToFieldType(DataTypeEnum $dataType): string
    {
        return match ($dataType) {
            DataTypeEnum::STRING => 'text',
            DataTypeEnum::INTEGER, DataTypeEnum::FLOAT => 'number',
            DataTypeEnum::DATE => 'date',
            DataTypeEnum::BOOLEAN => 'checkbox',
        };
    }
}
