<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transaction";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'author',
        'description',
        'date_publication',
        'url',
        'photo_id',
        'status'
    ];

    protected function name(): Attribute {
        return new Attribute (
            get: fn($value) => ucwords($value),
            set: fn($value) => strtolower($value)
        );
    }

    public function photo()
    {
    return $this->belongsTo(Photo::class, 'photo_id');
    }

}
