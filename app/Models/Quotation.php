<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    protected $fillable = [
        'client_id',
        'cash_id',
        'type',
        'amount',
        'rate',
        'total',
        'valid_until',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount'      => 'float',
        'rate'        => 'float',
        'total'       => 'float',
        'valid_until' => 'date',
        'status'      => 'integer',
    ];

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /** Cotizaciones vigentes: activas y no vencidas. */
    public function scopeValid($query)
    {
        return $query->where('status', 1)
                     ->where(function ($q) {
                         $q->whereNull('valid_until')
                           ->orWhereDate('valid_until', '>=', today());
                     });
    }

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function cash()
    {
        return $this->belongsTo(Cash::class, 'cash_id');
    }
}
