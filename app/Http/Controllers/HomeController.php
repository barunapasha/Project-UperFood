<?php

namespace App\Http\Controllers;

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

        return view('home', compact('categories', 'warungKantinAtas', 'warungKantinBawah'));
    }
}