<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\Request;

class WarungController extends Controller
{
    public function kantinAtas()
    {
        try {
            $warungKantinAtas = Warung::where('location', 'like', '%Kantin Atas%')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($warung) {
                    return [
                        'id' => $warung->id,
                        'slug' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $this->getWarungImage($warung->name)
                    ];
                });

            return view('kantin-atas', compact('warungKantinAtas'));

        } catch (\Exception $e) {
            // Fallback data jika terjadi error
            $warungKantinAtas = [
                [
                    'id' => 1,
                    'slug' => 'nasi-padang',
                    'name' => 'Nasi Padang',
                    'description' => 'Rendang, Ayam Bakar, Sayur Nangka',
                    'rating' => 4.7,
                    'distance' => '0.5 km',
                    'image' => 'images/nasi-padang.jpg'
                ],
                [
                    'id' => 2,
                    'slug' => 'ayam-suir',
                    'name' => 'Ayam Suir',
                    'description' => 'Ayam Suir, Es Teh',
                    'rating' => 5.0,
                    'distance' => '0.45 km',
                    'image' => 'images/ayam-suir.jpg'
                ],
                [
                    'id' => 3,
                    'slug' => 'warung-indomie',
                    'name' => 'Warung Indomie',
                    'description' => 'Indomie Goreng, Indomie Kuah',
                    'rating' => 4.3,
                    'distance' => '0.6 km',
                    'image' => 'images/warung-indomie.jpg'
                ]
            ];

            return view('kantin-atas', compact('warungKantinAtas'))
                ->with('error', 'Terjadi kesalahan saat memuat data warung.');
        }
    }

    public function kantinBawah()
    {
        try {
            $warungKantinBawah = Warung::where('location', 'like', '%Kantin Bawah%')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($warung) {
                    return [
                        'id' => $warung->id,
                        'slug' => $warung->slug,
                        'name' => $warung->name,
                        'description' => $warung->description,
                        'rating' => number_format($warung->rating, 1),
                        'distance' => $warung->distance ?? '0 km',
                        'image' => $this->getWarungImage($warung->name)
                    ];
                });

            return view('kantin-bawah', compact('warungKantinBawah'));

        } catch (\Exception $e) {
            // Fallback data jika terjadi error
            $warungKantinBawah = [
                [
                    'id' => 4,
                    'slug' => 'hokkian',
                    'name' => 'Hokkian',
                    'description' => 'Jus, Siomay, Risol',
                    'rating' => 4.7,
                    'distance' => '0.5 km',
                    'image' => 'images/hokkian.jpg'
                ],
                [
                    'id' => 5,
                    'slug' => 'katsu',
                    'name' => 'Katsu',
                    'description' => 'Chicken Katsu, Steak',
                    'rating' => 5.0,
                    'distance' => '0.45 km',
                    'image' => 'images/katsu.jpg'
                ],
                [
                    'id' => 6,
                    'slug' => 'soto',
                    'name' => 'Soto',
                    'description' => 'Soto Ayam, Es Jeruk',
                    'rating' => 4.3,
                    'distance' => '0.6 km',
                    'image' => 'images/soto.jpg'
                ]
            ];

            return view('kantin-bawah', compact('warungKantinBawah'))
                ->with('error', 'Terjadi kesalahan saat memuat data warung.');
        }
    }

    private function getWarungImage($warungName)
    {
        return match($warungName) {
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
}