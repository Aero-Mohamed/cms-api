<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Record extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'entity_id',
        'created_by',
    ];

    /**
     * Get the entity that this record belongs to.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the user who created this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the outgoing relationships where this record is the source.
     */
    public function outgoingRelationships(): HasMany
    {
        return $this->hasMany(RecordRelationship::class, 'from_record_id');
    }

    /**
     * Get the incoming relationships where this record is the target.
     */
    public function incomingRelationships(): HasMany
    {
        return $this->hasMany(RecordRelationship::class, 'to_record_id');
    }
}
