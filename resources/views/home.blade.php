<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- Featured Categories Section -->
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

    <!-- Update bagian Warung Kantin Atas -->
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

    <!-- Update bagian Warung Kantin Bawah dengan struktur yang sama -->
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
    </script>
</body>

</html>