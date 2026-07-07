<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TurnSession extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'opened_at',
        'closed_at',
        'opening_notes',
        'closing_notes',
        'initial_balances',
        'closing_balances',
        'transaction_count',
    ];

    protected $casts = [
        'opened_at'        => 'datetime',
        'closed_at'        => 'datetime',
        'initial_balances' => 'array',
        'closing_balances' => 'array',
        'transaction_count'=> 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // ── Relations ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public static function currentForUser(int $userId): ?self
    {
        return static::where('user_id', $userId)->open()->latest('opened_at')->first();
    }

    public static function anyOpen(): ?self
    {
        return static::open()->latest('opened_at')->first();
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function duration(): ?string
    {
        $end = $this->closed_at ?? now();
        $diff = $this->opened_at->diffForHumans($end, true);
        return $diff;
    }
}
