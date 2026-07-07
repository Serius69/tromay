<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Latest extends Model
{
    use HasFactory;

    protected $table = "latests";

    protected $fillable = [
        'name',
        'category',
        'author',
        'description',
        'date_publication',
        'url',
        'path',
        'status',
    ];

    protected $casts = [
        'date_publication' => 'date',
        'status'           => 'integer',
    ];

    // ----------------------------------------------------------------
    // Accessors / Mutators
    // ----------------------------------------------------------------

    protected function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }
}
