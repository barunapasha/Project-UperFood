<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>UperFood - Universitas Pertamina</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                        <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" style="height:7rem">
                    </a>
                </div>

                <a href="{{ route('cart.index') }}" class="relative">
                    <div class="bg-white p-2 rounded-full shadow hover:shadow-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ App\Helpers\CartHelper::getCartItemsCount() }}
                        </span>
                    </div>
                </a>

                <div class="flex-1 mx-8">
                    <div class="relative">
                        <input type="text"
                            placeholder="Cari makanan atau warung"
                            class="w-full px-4 py-2 pl-10 bg-gray-100 rounded-full">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 bg-white rounded-full px-4 py-1.5 border">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>Universitas Pertamina</span>
                    </div>

                    <button class="bg-purple-500 text-white rounded-full w-10 h-10">
                        <a href="{{ route('profile') }}" class="bg-purple-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 mt-4">
        <p class="text-sm text-gray-600">
            Main Page / Warung Universitas Pertamina
        </p>
    </div>

    <div class="container mx-auto px-4 mt-8 opacity-0 transform translate-y-4" id="featuredCategories">
        <div class="grid grid-cols-4 gap-6">
            <!-- Warung Baru -->
            <a href="{{ route('category.new') }}" class="featured-card group">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Warung Baru</h3>
                            <p class="text-blue-100 text-sm">Temukan warung-warung baru di sekitarmu</p>
                        </div>
                        <div class="text-white transform transition-transform duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-block bg-blue-400 bg-opacity-30 text-white px-3 py-1 rounded-full text-sm">
                            5 Warung Baru
                        </span>
                    </div>
                </div>
            </a>

            <!-- Terfavorit -->
            <a href="{{ route('category.favorite') }}" class="featured-card group">
                <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Terfavorit</h3>
                            <p class="text-pink-100 text-sm">Warung dengan rating terbaik</p>
                        </div>
                        <div class="text-white transform transition-transform duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-block bg-pink-400 bg-opacity-30 text-white px-3 py-1 rounded-full text-sm">
                            Rating 4.5+
                        </span>
                    </div>
                </div>
            </a>

            <!-- Lokal -->
            <a href="{{ route('category.local') }}" class="featured-card group">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Lokal</h3>
                            <p class="text-green-100 text-sm">Nikmati masakan khas lokal</p>
                        </div>
                        <div class="text-white transform transition-transform duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-block bg-green-400 bg-opacity-30 text-white px-3 py-1 rounded-full text-sm">
                            Masakan Nusantara
                        </span>
                    </div>
                </div>
            </a>

            <!-- Terlaris -->
            <a href="{{ route('category.bestseller') }}" class="featured-card group">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Terlaris</h3>
                            <p class="text-purple-100 text-sm">Menu favorit mahasiswa</p>
                        </div>
                        <div class="text-white transform transition-transform duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-block bg-purple-400 bg-opacity-30 text-white px-3 py-1 rounded-full text-sm">
                            Best Seller
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 mt-8 opacity-0 transform translate-y-4" id="kantinAtas">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Warung Kantin Atas</h2>
            <a href="{{ route('kantin-atas') }}" class="text-purple-500 hover:text-purple-700 transition duration-300">
                Tampilkan semua warung
            </a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @foreach($warungKantinAtas as $warung)
            <a href="{{ route('warung.detail', ['slug' => $warung['id']]) }}" class="warung-card block">
                <div class="bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($warung['image']) }}"
                            alt="{{ $warung['name'] }}"
                            class="w-full h-48 object-cover transition duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $warung['name'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $warung['description'] }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1">{{ $warung['rating'] }}</span>
                            </div>
                            <div class="flex items-center ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1">{{ $warung['distance'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="container mx-auto px-4 mt-8 opacity-0 transform translate-y-4" id="kantinBawah">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Warung Kantin Bawah</h2>
            <a href="{{ route('kantin-bawah') }}" class="text-purple-500 hover:text-purple-700 transition duration-300">
                Tampilkan semua warung
            </a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @foreach($warungKantinBawah as $warung)
            <a href="{{ route('warung.detail', ['slug' => $warung['id']]) }}" class="warung-card block">
                <div class="bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($warung['image']) }}"
                            alt="{{ $warung['name'] }}"
                            class="w-full h-48 object-cover transition duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $warung['name'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $warung['description'] }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1">{{ $warung['rating'] }}</span>
                            </div>
                            <div class="flex items-center ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1">{{ $warung['distance'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Footer yang diperbarui -->
    <footer class="bg-purple-500 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-3 gap-12">
                <!-- Kolom 1: Informasi Alamat -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6">Universitas Pertamina</h3>
                    <div class="flex items-start space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="leading-relaxed">
                            Jl. Teuku Nyak Arief, Simprug,<br>
                            Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                            Daerah Khusus Ibukota Jakarta
                        </p>
                    </div>
                </div>

                <!-- Kolom 2: Logo -->
                <div class="flex flex-col items-center justify-center">
                    <img src="{{ asset('images/logo-uperfood-white.png') }}"
                        alt="UperFood"
                        class="mb-4 transform hover:scale-105 transition-transform duration-300"
                        style="height:9rem">
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-purple-200 transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 3: Informasi Tambahan -->
                <div class="text-right">
                    <h3 class="text-xl font-bold mb-6">Makanan Lezat dan Enak di<br>
                        Lingkungan Universitas Pertamina</h3>
                    <div class="mt-4 space-y-2">
                        <p class="text-purple-200">Jam Operasional:</p>
                        <p>Senin - Jumat: 08:00 - 17:00</p>
                        <p>Sabtu: 08:00 - 15:00</p>
                    </div>
                    <p class="mt-8">© UperFood 2024</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const featuredSection = document.getElementById('featuredCategories');
        if (featuredSection) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '50px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.transition = 'all 0.5s ease-out';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';

                        // Animate cards
                        const cards = entry.target.querySelectorAll('.featured-card');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, index * 100);
                        });
                    }
                });
            }, observerOptions);

            // Initialize cards with starting styles
            const cards = featuredSection.querySelectorAll('.featured-card');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.3s ease-out';
            });

            observer.observe(featuredSection);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial page fade in
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in';
                document.body.style.opacity = '1';
            }, 0);

            // Observer setup for sections
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '50px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';

                        // Animate cards within the section
                        const cards = entry.target.querySelectorAll('.warung-card');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, index * 100);
                        });
                    }
                });
            }, observerOptions);

            // Initialize all cards with starting styles
            document.querySelectorAll('.warung-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.3s ease-out';

                // Add consistent hover effects
                card.addEventListener('mouseenter', function() {
                    this.querySelector('img').style.transform = 'scale(1.1)';
                    this.querySelector('.bg-white').style.transform = 'translateY(-8px)';
                    this.querySelector('.bg-white').style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
                });

                card.addEventListener('mouseleave', function() {
                    this.querySelector('img').style.transform = 'scale(1)';
                    this.querySelector('.bg-white').style.transform = 'translateY(0)';
                    this.querySelector('.bg-white').style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                });
            });

            // Observe sections
            document.querySelectorAll('#kantinAtas, #kantinBawah').forEach(section => {
                observer.observe(section);
            });

            // Add required styles
            const style = document.createElement('style');
            style.textContent = `
        .warung-card {
            transition: all 0.3s ease-out;
        }
        .warung-card img {
            transition: transform 0.3s ease-out;
        }
        .warung-card .bg-white {
            transition: all 0.3s ease-out;
        }
    `;
            document.head.appendChild(style);
        });

        // Add this JavaScript to the existing script section in home.blade.php

        // Create a debounce function to limit API calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Add search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[placeholder="Cari makanan atau warung"]');
            const searchResults = document.createElement('div');
            searchResults.className = 'absolute w-full mt-2 bg-white rounded-lg shadow-lg z-50 hidden';
            searchInput.parentNode.appendChild(searchResults);

            const performSearch = debounce(async (query) => {
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`/search?query=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (!response.ok) throw new Error(data.error);

                    // Clear previous results
                    searchResults.innerHTML = '';

                    // Display results
                    if (data.warungs.length === 0 && data.menuItems.length === 0) {
                        searchResults.innerHTML = `
                    <div class="p-4 text-gray-500 text-center">
                        Tidak ada hasil ditemukan
                    </div>
                `;
                    } else {
                        // Warung results
                        if (data.warungs.length > 0) {
                            searchResults.innerHTML += `
                        <div class="p-2 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-600 px-2">Warung</h3>
                        </div>
                    `;
                            data.warungs.forEach(warung => {
                                searchResults.innerHTML += `
                            <a href="/warung/${warung.id}" class="block p-3 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <img src="${warung.image}" class="w-12 h-12 object-cover rounded" alt="${warung.name}">
                                    <div class="ml-3">
                                        <div class="font-semibold">${warung.name}</div>
                                        <div class="text-sm text-gray-600">${warung.location}</div>
                                    </div>
                                </div>
                            </a>
                        `;
                            });
                        }

                        // Menu items results
                        if (data.menuItems.length > 0) {
                            searchResults.innerHTML += `
                        <div class="p-2 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-600 px-2">Menu</h3>
                        </div>
                    `;
                            data.menuItems.forEach(item => {
                                searchResults.innerHTML += `
                            <a href="/warung/${item.warung_slug}" class="block p-3 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold">${item.name}</div>
                                        <div class="text-sm text-gray-600">${item.warung_name}</div>
                                    </div>
                                    <div class="text-purple-600 font-semibold">
                                        Rp ${new Intl.NumberFormat('id-ID').format(item.price)}
                                    </div>
                                </div>
                            </a>
                        `;
                            });
                        }
                    }

                    searchResults.classList.remove('hidden');
                } catch (error) {
                    console.error('Search error:', error);
                    searchResults.innerHTML = `
                <div class="p-4 text-red-500 text-center">
                    Terjadi kesalahan saat mencari
                </div>
            `;
                }
            }, 300);

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.trim();
                performSearch(query);
            });

            // Close search results when clicking outside
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>