<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarungDetailController extends Controller
{
    public function show($slug)
    {
        try {
            // Load warung dengan eager loading untuk menu categories dan items
            $warung = Warung::with(['menuCategories.menuItems' => function ($query) {
                $query->where('is_available', true); // Hanya ambil menu yang tersedia
            }])
                ->where('slug', $slug)
                ->firstOrFail();

            // Log untuk debugging
            \Log::info('Menu categories loaded:', [
                'warung' => $warung->name,
                'categories_count' => $warung->menuCategories->count(),
            ]);

            // Format data warung
            $warungData = [
                'id' => $warung->id,
                'name' => $warung->name,
                'description' => $warung->description,
                'rating' => number_format($warung->rating, 1),
                'distance' => $warung->distance ?? '0 km',
                'image' => $this->getWarungImage($warung->name),
                'open_hours' => $warung->open_hours ?? '08:00 - 17:00',
                'location' => $warung->location,
                'menus' => []
            ];

            // Format menu categories dan items
            foreach ($warung->menuCategories as $category) {
                $menuItems = $category->menuItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'price' => $item->price,
                        'is_available' => $item->is_available,
                        'image' => $this->getMenuImage($item->name)
                    ];
                })->toArray();

                if (!empty($menuItems)) {
                    $warungData['menus'][] = [
                        'category' => $category->name,
                        'items' => $menuItems
                    ];
                }
            }

            \Log::info('Warung data formatted:', [
                'categories_count' => count($warungData['menus']),
                'has_items' => collect($warungData['menus'])->pluck('items')->flatten()->count()
            ]);

            return view('warung.detail', compact('warungData'));
        } catch (\Exception $e) {
            \Log::error('Error in warung detail:', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('home')
                ->with('error', 'Warung tidak ditemukan atau terjadi kesalahan.');
        }
    }

    private function getWarungImage($warungName)
    {
        return match ($warungName) {
            'Nasi Padang' => 'images/nasi-padang.jpg',
            'Ayam Suir' => 'images/ayam-suir.jpg',
            'Warung Indomie' => 'images/warung-indomie.jpg',
            'Warung Mang Ujang' => 'images/default-warung.jpg',
            'Bakso Malang' => 'images/bakso.jpg',
            'Sate Madura' => 'images/sate.jpg',
            'Gado-gado' => 'images/gado-gado.jpg',
            'Hokkian' => 'images/hokkian.jpg',
            'Katsu' => 'images/katsu.jpg',
            'Soto' => 'images/soto.jpg',
            'Warung Korean Food' => 'images/korean.jpg',
            'Japanese Corner' => 'images/japanese.jpg',
            'Chinese Food' => 'images/chinese.jpg',
            default => 'images/default-warung.jpg'
        };
    }

    private function getMenuImage($menuName)
    {
        $filename = strtolower(str_replace(' ', '-', $menuName));
        $defaultPath = 'images/menu/default-menu.jpg';
        $imagePath = "images/menu/{$filename}.jpg";

        return file_exists(public_path($imagePath)) ? $imagePath : $defaultPath;
    }
}
