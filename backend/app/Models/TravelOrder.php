<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
