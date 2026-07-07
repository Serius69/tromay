<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    protected $table = "cashes";

    protected $fillable = [
        'name',
        'buy',
        'sell',
        'oficial',
        'path',
        'status',
    ];

    protected $casts = [
        'buy'    => 'float',
        'sell'   => 'float',
        'oficial'=> 'float',
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

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeAllowed($query, array $currencies)
    {
        return $query->whereIn('name', $currencies);
    }

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function transactions1()
    {
        return $this->hasMany(Transaction::class, 'cash1_id');
    }

    public function transactions2()
    {
        return $this->hasMany(Transaction::class, 'cash2_id');
    }

    public function rateHistory()
    {
        return $this->hasMany(CashRate::class);
    }
}
