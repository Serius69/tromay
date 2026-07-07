<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = "clients";

    protected $fillable = [
        'ci',
        'name',
        'lastname',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
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

    protected function lastname(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSearchByCi($query, string $term)
    {
        return $query->where('ci', 'like', "%{$term}%")
                     ->orWhere('name', 'like', "%{$term}%")
                     ->orWhere('lastname', 'like', "%{$term}%");
    }

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'client_id');
    }
}
