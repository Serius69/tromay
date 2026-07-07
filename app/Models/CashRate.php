<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRate extends Model
{
    protected $table    = 'cash_rates';
    protected $fillable = ['cash_id', 'buy', 'sell', 'oficial'];
    protected $casts    = [
        'buy'    => 'float',
        'sell'   => 'float',
        'oficial'=> 'float',
    ];

    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }
}
