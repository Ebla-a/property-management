<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class PropertyImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['property_id','path','is_main','alt',];

    protected $casts = ['is_main' => 'boolean',];

    public function property(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
}
