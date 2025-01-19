<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private function getWarungImage($warungName)
    {
        return match ($warungName) {
            'Nasi Padang' => 'images/nasi-padang.jpg',
            'Ayam Suir' => 'images/ayam-suir.jpg',
            'Warung Indomie' => 'images/warung-indomie.jpg',
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

    public function index()
    {
        $categories = [
            [
                'name' => 'Baru',
                'image' => 'images/baru.jpg'
            ],
            [
                'name' => 'Terfavorit',
                'image' => 'images/terfavorit.jpg'
            ],
            [
                'name' => 'Terlaris',
                'image' => 'images/terlaris.jpg'
            ],
            [
                'name' => 'Lokal',
                'image' => 'images/lokal.jpg'
            ]
        ];

        try {
            $warungKantinAtas = Warung::where('location', 'like', '%Kantin Atas%')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($warung) {
                    return [
                        'id' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $this->getWarungImage($warung->name)
                    ];
                })
                ->toArray();

            $warungKantinBawah = Warung::where('location', 'like', '%Kantin Bawah%')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($warung) {
                    return [
                        'id' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $this->getWarungImage($warung->name)
                    ];
                })
                ->toArray();

            return view('home', compact('categories', 'warungKantinAtas', 'warungKantinBawah'));
        } catch (\Exception $e) {
            \Log::error('Error fetching warung data:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Data fallback jika terjadi error
            $warungKantinAtas = [];
            $warungKantinBawah = [];

            return view('home', compact('categories', 'warungKantinAtas', 'warungKantinBawah'))
                ->with('error', 'Terjadi kesalahan saat memuat data warung.');
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([
                'warungs' => [],
                'menuItems' => []
            ]);
        }

        try {
            // Search in warungs
            $warungs = Warung::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->get()
                ->map(function ($warung) {
                    return [
                        'id' => $warung->slug,
                        'name' => $warung->name,
                        'location' => $warung->location,
                        'description' => $warung->description,
                        'type' => 'warung',
                        'image' => $this->getWarungImage($warung->name),
                        'rating' => number_format($warung->rating, 1)
                    ];
                });

            // Search in warung with menus through menu categories
            $warungsWithMenus = Warung::whereHas('menuCategories', function ($query) use ($request) {
                $query->whereHas('menuItems', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->query . '%')
                        ->orWhere('description', 'like', '%' . $request->query . '%');
                });
            })
                ->with(['menuCategories.menuItems' => function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->query . '%')
                        ->orWhere('description', 'like', '%' . $request->query . '%');
                }])
                ->get();

            $menuItems = collect();
            foreach ($warungsWithMenus as $warung) {
                foreach ($warung->menuCategories as $category) {
                    foreach ($category->menuItems as $item) {
                        $menuItems->push([
                            'id' => $item->id,
                            'name' => $item->name,
                            'description' => $item->description,
                            'type' => 'menu',
                            'price' => $item->price,
                            'warung_name' => $warung->name,
                            'warung_slug' => $warung->slug
                        ]);
                    }
                }
            }

            return response()->json([
                'warungs' => $warungs,
                'menuItems' => $menuItems
            ]);
        } catch (\Exception $e) {
            \Log::error('Search Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mencari'
            ], 500);
        }
    }
}
