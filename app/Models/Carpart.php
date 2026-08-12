<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'object_name', 'label', 'description', 'image', 'extra_price',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}