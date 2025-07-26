<?php

namespace App\Models;

use App\Enums\RelationshipTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityRelationship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'name',
        'inverse_name',
        'from_entity_id',
        'to_entity_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => RelationshipTypeEnum::class,
    ];

    /**
     * Get the source entity of the relationship.
     */
    public function fromEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'from_entity_id');
    }

    /**
     * Get the target entity of the relationship.
     */
    public function toEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'to_entity_id');
    }
}
