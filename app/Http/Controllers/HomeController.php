<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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
            // Ambil 3 warung terbaru dari kantin atas
            $warungKantinAtas = Warung::where('location', 'like', '%Kantin Atas%')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function($warung) {
                    $image = match($warung->name) {
                        'Nasi Padang' => 'images/nasi-padang.jpg',
                        'Ayam Suir' => 'images/ayam-suir.jpg',
                        'Warung Indomie' => 'images/indomie.jpg',
                        'Bakso Malang' => 'images/bakso.jpg',
                        'Sate Madura' => 'images/sate.jpg',
                        'Gado-gado' => 'images/gado-gado.jpg',
                        default => $warung->image
                    };

                    return [
                        'id' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $image
                    ];
                })
                ->toArray();

            // Ambil 3 warung terbaru dari kantin bawah
            $warungKantinBawah = Warung::where('location', 'like', '%Kantin Bawah%')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function($warung) {
                    $image = match($warung->name) {
                        'Hokkian' => 'images/hokkian.jpg',
                        'Katsu' => 'images/katsu.jpg',
                        'Soto' => 'images/soto.jpg',
                        'Warung Korean Food' => 'images/korean.jpg',
                        'Japanese Corner' => 'images/japanese.jpg',
                        'Chinese Food' => 'images/chinese.jpg',
                        default => $warung->image
                    };

                    return [
                        'id' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $image
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
            $warungKantinAtas = [
                [
                    'id' => 'nasi-padang',
                    'name' => 'Nasi Padang',
                    'description' => 'Rendang, Ayam Bakar, Sayur Nangka',
                    'rating' => 4.7,
                    'distance' => '0.5 km',
                    'image' => 'images/nasi-padang.jpg'
                ],
                [
                    'id' => 'ayam-suir',
                    'name' => 'Ayam Suir',
                    'description' => 'Ayam Suir, Es Teh',
                    'rating' => 5.0,
                    'distance' => '0.45 km',
                    'image' => 'images/ayam-suir.jpg'
                ],
                [
                    'id' => 'warung-indomie',
                    'name' => 'Warung Indomie',
                    'description' => 'Indomie Goreng, Indomie Kuah',
                    'rating' => 4.3,
                    'distance' => '0.6 km',
                    'image' => 'images/indomie.jpg'
                ]
            ];

            $warungKantinBawah = [
                [
                    'id' => 'hokkian',
                    'name' => 'Hokkian',
                    'description' => 'Jus, Siomay, Risol',
                    'rating' => 4.7,
                    'distance' => '0.5 km',
                    'image' => 'images/hokkian.jpg'
                ],
                [
                    'id' => 'katsu',
                    'name' => 'Katsu',
                    'description' => 'Chicken Katsu, Steak',
                    'rating' => 5.0,
                    'distance' => '0.45 km',
                    'image' => 'images/katsu.jpg'
                ],
                [
                    'id' => 'soto',
                    'name' => 'Soto',
                    'description' => 'Soto Ayam, Es Jeruk',
                    'rating' => 4.3,
                    'distance' => '0.6 km',
                    'image' => 'images/soto.jpg'
                ]
            ];

            return view('home', compact('categories', 'warungKantinAtas', 'warungKantinBawah'))
                ->with('error', 'Terjadi kesalahan saat memuat data warung.');
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}