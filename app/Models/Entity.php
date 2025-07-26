<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Entity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
    ];

    /**
     * Get the attributes associated with the entity.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_entity')
            ->using(AttributeEntity::class);
    }

    /**
     * Get the user who created the entity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
