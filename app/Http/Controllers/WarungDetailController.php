<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\Request;

class WarungDetailController extends Controller
{
    public function show($id)
    {
        $warung = Warung::with(['menuCategories.menuItems'])
            ->where('slug', $id)
            ->firstOrFail();

        $warungData = [
            'id' => $warung->slug,
            'name' => $warung->name,
            'description' => $warung->description,
            'rating' => $warung->rating,
            'distance' => $warung->distance,
            'image' => $warung->image,
            'open_hours' => $warung->open_hours,
            'location' => $warung->location,
            'menus' => $warung->menuCategories->map(function($category) {
                return [
                    'category' => $category->name,
                    'items' => $category->menuItems->map(function($item) {
                        return [
                            'name' => $item->name,
                            'description' => $item->description,
                            'price' => $item->price,
                            'image' => $item->image,
                            'is_available' => $item->is_available
                        ];
                    })->toArray()
                ];
            })->toArray()
        ];

        return view('warung.detail', compact('warungData'));
    }
}