<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this['id'],
            'name'          => $this['name'],
            'slug'          => $this['slug'],
            'data_type'     => $this['data_type'],
            'is_required'   => $this['is_required'],
            'is_unique'     => $this['is_unique'],
            'default_value' => $this['default_value'],
            'created_at'    => $this['created_at'],
            'updated_at'    => $this['updated_at'],
            ...$this->additional,
        ];
    }
}
