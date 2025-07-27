<?php

namespace App\Http\Resources;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class GenericModelResource extends JsonResource
{
    private static array $extra = [];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Collection $entityValues */
        $entityValues = self::$extra['entityValues'];
        /** @var Collection $entityAttributes */
        $entityAttributes = self::$extra['entityAttributes'];
        $recordValues = $entityValues->get($this['id'], collect())->keyBy('attribute_id');

        $recordData = [];

        /** @var Attribute $attr */
        foreach ($entityAttributes as $attr) {
            $value = $recordValues->get($attr->getKey());
            $recordData[$attr->getAttribute('slug')] = $value->value ?? null;
        }


        return [
            'id'            => $this['id'],
            ...$recordData,
            'created_at'    => $this['created_at'],
            'updated_at'    => $this['updated_at'],
        ];
    }

    /**
     * @param $resource
     * @param array $extra
     * @return AnonymousResourceCollection
     */
    public static function collectionWithData($resource, array $extra = []): AnonymousResourceCollection
    {
        self::$extra = $extra;
        return parent::collection($resource);
    }
}
