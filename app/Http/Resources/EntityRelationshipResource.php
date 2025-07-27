<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityRelationshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this['id'],
            'type'         => $this['type'],
            'name'         => $this['name'],
            'inverse_name' => $this['inverse_name'],
            'from_entity'  => new EntityResource($this->whenLoaded('fromEntity')),
            'to_entity'    => new EntityResource($this->whenLoaded('toEntity')),
            'from_entity_id' => $this['from_entity_id'],
            'to_entity_id'   => $this['to_entity_id'],
            ...$this->additional,
        ];
    }
}
