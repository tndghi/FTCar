<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_category_id', 'name', 'slug', 'brand', 'model', 'year',
        'price', 'description', 'thumbnail', 'has_3d_model',
        'model_3d_path', 'status', 'stock',
    ];

    protected $casts = [
        'has_3d_model' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CarCategory::class, 'car_category_id');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(CarPart::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}