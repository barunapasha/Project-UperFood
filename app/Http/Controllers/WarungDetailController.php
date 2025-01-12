<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarungDetailController extends Controller
{
    public function show($id)
    {
        try {
            // Ambil data warung dengan relasi menu categories dan menu items
            $warung = Warung::with(['menuCategories.menuItems' => function($query) {
                $query->where('is_available', true)
                      ->orderBy('name', 'asc');
            }])
            ->where('slug', $id)
            ->firstOrFail();

            // Format data untuk view
            $warungData = [
                'id' => $warung->slug,
                'name' => $warung->name,
                'description' => $warung->description,
                'rating' => number_format($warung->rating, 1),
                'distance' => $warung->distance,
                'image' => $warung->image ?? 'images/default-warung.jpg',
                'open_hours' => $warung->open_hours ?? '08:00 - 17:00',
                'location' => $warung->location,
                'menus' => $warung->menuCategories->map(function($category) {
                    return [
                        'category' => $category->name,
                        'items' => $category->menuItems->map(function($item) {
                            return [
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'image' => $item->image ?? 'images/default-menu.jpg',
                                'is_available' => $item->is_available
                            ];
                        })->toArray()
                    ];
                })->filter(function($category) {
                    // Filter kategori yang memiliki item menu
                    return count($category['items']) > 0;
                })->values()->toArray()
            ];

            // Log untuk debugging
            \Log::info('Warung data:', ['data' => $warungData]);

            return view('warung.detail', compact('warungData'));

        } catch (\Exception $e) {
            \Log::error('Error in warung detail:', ['error' => $e->getMessage()]);
            return redirect()->route('home')
                           ->with('error', 'Warung tidak ditemukan atau terjadi kesalahan.');
        }
    }
}