<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordRelationship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'relationship_id',
        'from_record_id',
        'to_record_id',
    ];

    /**
     * Get the entity relationship that defines this record relationship.
     */
    public function relationship(): BelongsTo
    {
        return $this->belongsTo(EntityRelationship::class, 'relationship_id');
    }

    /**
     * Get the source record of this relationship.
     */
    public function fromRecord(): BelongsTo
    {
        return $this->belongsTo(Record::class, 'from_record_id');
    }

    /**
     * Get the target record of this relationship.
     */
    public function toRecord(): BelongsTo
    {
        return $this->belongsTo(Record::class, 'to_record_id');
    }
}
