<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'image',
        'open_hours',
        'distance',
        'rating'
    ];

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }
}