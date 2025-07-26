<?php

namespace App\Models;

use App\Enums\DataTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
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
        'data_type',
        'is_required',
        'is_unique',
        'default_value',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'data_type' => DataTypeEnum::class,
    ];

    /**
     * Get the entities associated with the attribute.
     */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'attribute_entity')
            ->using(AttributeEntity::class);
    }

    /**
     * Get the user who created the attribute.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
