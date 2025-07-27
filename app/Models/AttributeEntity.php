<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeEntity extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attribute_entity';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attribute_id',
        'entity_id',
    ];
}
