<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public const STATUS_ANULADA    = 0;
    public const STATUS_VIGENTE    = 1;
    public const STATUS_CONVERTIDA = 2;

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
        return $query->where('status', self::STATUS_VIGENTE);
    }

    /** Cotizaciones vigentes: activas y no vencidas. */
    public function scopeValid($query)
    {
        return $query->where('status', self::STATUS_VIGENTE)
                     ->where(function ($q) {
                         $q->whereNull('valid_until')
                           ->orWhereDate('valid_until', '>=', today());
                     });
    }

    /**
     * Motivo por el que NO puede convertirse en transacción, o null si es
     * convertible. Fuente única de la regla: la UI (botón) y el servicio
     * (guardas) deben decidir siempre con este método.
     */
    public function convertibilityError(): ?string
    {
        return match (true) {
            $this->status === self::STATUS_CONVERTIDA
                => 'Esta cotización ya fue convertida en transacción.',
            $this->status !== self::STATUS_VIGENTE
                => 'Solo se pueden convertir cotizaciones vigentes.',
            $this->valid_until !== null && $this->valid_until->copy()->endOfDay()->isPast()
                => 'La cotización está vencida.',
            $this->client_id === null
                => 'Asigne un cliente a la cotización antes de convertirla.',
            default => null,
        };
    }

    /** ¿Puede convertirse en transacción? (vigente, no vencida y con cliente) */
    public function isConvertible(): bool
    {
        return $this->convertibilityError() === null;
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

    /** Transacción generada al convertir esta cotización (si aplica). */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
