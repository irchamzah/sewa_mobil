<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'license_plate',
        'daily_rent',
        'photos',
        'availability',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
