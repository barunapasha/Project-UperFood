<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available'
    ];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
}