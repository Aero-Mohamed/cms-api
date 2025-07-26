<?php

namespace App\Services\Schema\Support;

use App\Enums\DataTypeEnum;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;

class EntityFieldGenerator
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository
    ) {}

    /**
     * Generate fields for a given entity based on its attributes
     *
     * @param Entity $entity
     * @return array
     */
    public function generate(Entity $entity): array
    {
        $fields = [];
        $attributes = $this->attributeRepository->getAttributesForEntity($entity);

        foreach ($attributes as $attribute) {
            $field = [
                'name' => $attribute->slug,
                'label' => $attribute->name,
                'type' => $this->mapDataTypeToFieldType($attribute->data_type),
                'required' => $attribute->is_required,
                'default_value' => $attribute->default_value,
            ];

            $fields[] = $field;
        }

        return $fields;
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
