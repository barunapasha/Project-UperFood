<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'image',
        'rating',
        'distance',
        'slug',
        'open_hours'
    ];

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }
}