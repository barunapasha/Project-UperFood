<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Menampilkan warung-warung baru
     */
    public function new()
    {
        try {
            $warungs = Warung::where('created_at', '>=', Carbon::now()->subDays(30))
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($warung) {
                    return $this->formatWarungData($warung);
                });

            return view('second-home', [
                'warungs' => $warungs,
                'title' => 'Warung Baru',
                'description' => 'Daftar warung yang baru bergabung dalam 30 hari terakhir'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat warung baru.');
        }
    }

    /**
     * Menampilkan warung-warung dengan rating tertinggi
     */
    public function favorite()
    {
        try {
            $warungs = Warung::where('rating', '>=', 4.5)
                ->orderBy('rating', 'desc')
                ->get()
                ->map(function($warung) {
                    return $this->formatWarungData($warung);
                });

            return view('second-home', [
                'warungs' => $warungs,
                'title' => 'Warung Terfavorit',
                'description' => 'Warung-warung dengan rating terbaik dari pengguna'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat warung favorit.');
        }
    }

    /**
     * Menampilkan warung-warung dengan masakan lokal
     */
    public function local()
    {
        try {
            $warungs = Warung::where('name', 'like', '%Nasi%')
                ->orWhere('name', 'like', '%Soto%')
                ->orWhere('name', 'like', '%Sate%')
                ->orWhere('name', 'like', '%Bakso%')
                ->orWhere('name', 'like', '%Gado%')
                ->orderBy('rating', 'desc')
                ->get()
                ->map(function($warung) {
                    return $this->formatWarungData($warung);
                });

            return view('second-home', [
                'warungs' => $warungs,
                'title' => 'Warung Lokal',
                'description' => 'Nikmati berbagai masakan khas Nusantara'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat warung lokal.');
        }
    }

    /**
     * Menampilkan warung-warung terlaris
     */
    public function bestseller()
    {
        try {
            $warungs = Warung::where('rating', '>=', 4.0)
                ->orderBy('rating', 'desc')
                ->take(10)
                ->get()
                ->map(function($warung) {
                    return $this->formatWarungData($warung);
                });

            return view('second-home', [
                'warungs' => $warungs,
                'title' => 'Warung Terlaris',
                'description' => 'Warung-warung dengan penjualan terbanyak'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat warung terlaris.');
        }
    }

    /**
     * Format data warung untuk view
     */
    private function formatWarungData($warung)
    {
        // Set gambar default berdasarkan nama warung
        $image = match($warung->name) {
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
            default => $warung->image ?? 'images/default-warung.jpg'
        };

        return [
            'id' => $warung->slug,
            'name' => $warung->name,
            'description' => $warung->description,
            'rating' => number_format($warung->rating, 1),
            'distance' => $warung->distance ?? '0 km',
            'image' => $image,
            'location' => $warung->location
        ];
    }
}