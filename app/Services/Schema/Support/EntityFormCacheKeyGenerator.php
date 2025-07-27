<?php

namespace App\Services\Schema\Support;

use App\Models\Entity;

class EntityFormCacheKeyGenerator
{
    /**
     * @param Entity $entity
     * @return string
     */
    public static function baseKey(Entity $entity): string
    {
        return "entity_form_{$entity->getKey()}";
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public static function fields(Entity $entity): string
    {
        return self::baseKey($entity) . '_fields';
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public static function validationRules(Entity $entity): string
    {
        return self::baseKey($entity) . '_validation_rules';
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public static function relationships(Entity $entity): string
    {
        return self::baseKey($entity) . '_relationships';
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public static function attributes(Entity $entity): string
    {
        return self::baseKey($entity) . '_attributes';
    }

    /**
     * @param Entity $entity
     * @return array
     */
    public static function allKeys(Entity $entity): array
    {
        return [
            self::fields($entity),
            self::validationRules($entity),
            self::relationships($entity),
            self::attributes($entity),
        ];
    }
}
