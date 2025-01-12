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
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'float'
    ];
}
