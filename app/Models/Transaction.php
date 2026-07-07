<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";

    protected $fillable = [
        'user_id',
        'client_id',
        'cash1_id',
        'cash2_id',
        'amount1',
        'amount2',
        'date',
        'type',
        'status',
    ];

    protected $casts = [
        'amount1' => 'float',
        'amount2' => 'float',
        'type'    => 'integer',
        'status'  => 'integer',
        'date'    => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeByType($query, int $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function cash1()
    {
        return $this->belongsTo(Cash::class, 'cash1_id');
    }

    public function cash2()
    {
        return $this->belongsTo(Cash::class, 'cash2_id');
    }
}
