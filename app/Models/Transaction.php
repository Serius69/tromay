<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;
use App\Models\Cash;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'cash1_id',
        'cash2_id',
        'amount1',
        'amount2',
        'date',
        'type',
        'status'
    ];

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
