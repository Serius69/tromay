<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Lead de email capturado en la vitrina pública para alertas de tasas.
 *
 * Solo tiene `created_at` (no `updated_at`): un lead es un evento de captura,
 * no una entidad que se edite.
 */
class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    public const UPDATED_AT = null; // la tabla no tiene columna updated_at

    protected $fillable = [
        'email',
        'currency',
        'source',
        'ip',
    ];
}
