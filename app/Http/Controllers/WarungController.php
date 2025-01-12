<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarungController extends Controller
{
    public function kantinAtas()
    {
        $warungKantinAtas = [
            [
                'name' => 'Nasi Padang',
                'description' => 'Rendang, Ayam Bakar, Sayur Nangka',
                'rating' => 4.7,
                'distance' => '0.5 km',
                'image' => 'images/nasi-padang.jpg'
            ],
            [
                'name' => 'Ayam Suir',
                'description' => 'Ayam Suir, Es Teh',
                'rating' => 5.0,
                'distance' => '0.45 km',
                'image' => 'images/ayam-suir.jpg'
            ],
            [
                'name' => 'Warung Indomie',
                'description' => 'Indomie Goreng, Indomie Kuah',
                'rating' => 4.3,
                'distance' => '0.6 km',
                'image' => 'images/warung-indomie.jpg'
            ],
            // Tambahan warung untuk contoh
            [
                'name' => 'Bakso Malang',
                'description' => 'Bakso, Mie Ayam, Pangsit',
                'rating' => 4.8,
                'distance' => '0.3 km',
                'image' => 'images/bakso.jpg'
            ],
            [
                'name' => 'Sate Madura',
                'description' => 'Sate Ayam, Sate Kambing',
                'rating' => 4.6,
                'distance' => '0.4 km',
                'image' => 'images/sate.jpg'
            ],
            [
                'name' => 'Gado-gado',
                'description' => 'Gado-gado, Ketoprak, Lontong',
                'rating' => 4.5,
                'distance' => '0.55 km',
                'image' => 'images/gado-gado.jpg'
            ]
        ];

        return view('kantin-atas', compact('warungKantinAtas'));
    }

    public function kantinBawah()
    {
        $warungKantinBawah = [
            [
                'name' => 'Hokkian',
                'description' => 'Jus, Siomay, Risol',
                'rating' => 4.7,
                'distance' => '0.5 km',
                'image' => 'images/hokkian.jpg'
            ],
            [
                'name' => 'Katsu',
                'description' => 'Chicken Katsu, Steak',
                'rating' => 5.0,
                'distance' => '0.45 km',
                'image' => 'images/katsu.jpg'
            ],
            [
                'name' => 'Soto',
                'description' => 'Soto Ayam, Es Jeruk',
                'rating' => 4.3,
                'distance' => '0.6 km',
                'image' => 'images/soto.jpg'
            ],
            // Tambahan warung untuk contoh
            [
                'name' => 'Warung Korean Food',
                'description' => 'Tteokbokki, Kimchi, Ramyeon',
                'rating' => 4.9,
                'distance' => '0.35 km',
                'image' => 'images/korean.jpg'
            ],
            [
                'name' => 'Japanese Corner',
                'description' => 'Sushi, Ramen, Tempura',
                'rating' => 4.8,
                'distance' => '0.4 km',
                'image' => 'images/japanese.jpg'
            ],
            [
                'name' => 'Chinese Food',
                'description' => 'Capcay, Fuyunghai, Kwetiau',
                'rating' => 4.6,
                'distance' => '0.5 km',
                'image' => 'images/chinese.jpg'
            ]
        ];

        return view('kantin-bawah', compact('warungKantinBawah'));
    }
}