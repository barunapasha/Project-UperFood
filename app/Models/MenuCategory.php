<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $fillable = [
        'warung_id',
        'name'
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}