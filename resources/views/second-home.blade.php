<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UperFood - {{ $title }}</title>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Universitas Pertamina</span>
                    </div>
                    <button class="bg-purple-500 text-white rounded-full w-10 h-10">
                        <a href="{{ route('profile') }}"
                            class="bg-purple-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <p class="text-sm text-gray-600">
                Main Page / {{ $title }}
            </p>
        </div>

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-gray-600">{{ $description }}</p>
        </div>

        <div class="container mx-auto px-4 py-4">
            <a href="{{ route('home') }}"
                class="inline-flex items-center text-purple-600 hover:text-purple-700 transition-colors duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200"
                    viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <!-- Warung Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-0 transform translate-y-4" id="warungGrid">
            @forelse($warungs as $warung)
            <div class="warung-card">
                <a href="{{ route('warung.detail', $warung['id']) }}"
                    class="block bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($warung['image']) }}" alt="{{ $warung['name'] }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold">{{ $warung['name'] }}</h3>
                            <span
                                class="flex items-center bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400 mr-1"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ $warung['rating'] }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4">{{ $warung['description'] }}</p>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-1"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">{{ $warung['distance'] }}</span>
                            </div>

                            <button
                                class="inline-flex items-center px-3 py-1 border border-purple-500 text-purple-500 rounded-full hover:bg-purple-500 hover:text-white transition-colors duration-300">
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada warung</h3>
                <p class="mt-1 text-gray-500">Belum ada warung yang tersedia dalam kategori ini.</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 mt-4 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition-colors duration-300">
                    Kembali ke Beranda
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-500 text-white mt-12 py-8">
        <div class="container mx-auto px-4 grid grid-cols-3 gap-8">
            <div>
                <h3 class="font-bold mb-4">Universitas Pertamina</h3>
                <p>Jl. Teuku Nyak Arief, Simprug,<br>
                    Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                    Daerah Khusus Ibukota Jakarta</p>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" style="height: 9rem;">
            </div>
            <div class="text-right">
                <h3 class="font-bold mb-4">Makanan Lezat dan Enak di<br>
                    Lingkungan Universitas Pertamina</h3>
                <p>© UperFood 2024</p>
            </div>
        </div>
    </footer>

    <script>
        // ============ SEARCH FUNCTIONALITY ============
        const searchInput = document.querySelector('input[placeholder="Cari makanan atau warung"]');
        const searchResults = document.createElement('div');
        searchResults.className = 'absolute w-full mt-2 bg-white rounded-lg shadow-lg z-50 hidden overflow-y-auto max-h-[80vh]';
        searchResults.style.top = '100%';
        searchInput.parentNode.appendChild(searchResults);

        let debounceTimer;
        let currentSearchRequest = null;

        // Search input handler
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(debounceTimer);

            if (currentSearchRequest) {
                currentSearchRequest.abort();
            }

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
                searchInput.classList.remove('ring-2', 'ring-purple-500');
                return;
            }

            searchInput.classList.add('ring-2', 'ring-purple-500');

            debounceTimer = setTimeout(async () => {
                try {
                    const controller = new AbortController();
                    currentSearchRequest = controller;

                    searchResults.classList.remove('hidden');
                    searchResults.innerHTML = '<div class="p-4 text-center"><div class="animate-spin inline-block w-6 h-6 border-2 border-purple-500 border-t-transparent rounded-full"></div></div>';

                    const response = await fetch(`/search?query=${encodeURIComponent(query)}`, {
                        signal: controller.signal
                    });
                    const data = await response.json();

                    if (!response.ok) throw new Error(data.error);

                    searchResults.innerHTML = '';

                    if (data.warungs.length === 0 && data.menuItems.length === 0) {
                        searchResults.innerHTML = `
                    <div class="p-4 text-gray-500 text-center">
                        Tidak ada hasil untuk "${query}"
                    </div>
                `;
                    } else {
                        if (data.warungs.length > 0) {
                            searchResults.innerHTML += `
                        <div class="p-2 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-600 px-2">Warung</h3>
                        </div>
                    `;

                            data.warungs.forEach(warung => {
                                searchResults.innerHTML += `
                            <a href="/warung/${warung.id}" 
                               class="block p-4 hover:bg-gray-50 transition-all duration-300">
                                <div class="flex items-center">
                                    <img src="${warung.image}" 
                                         class="w-12 h-12 object-cover rounded" 
                                         alt="${warung.name}">
                                    <div class="ml-3">
                                        <div class="font-semibold">${warung.name}</div>
                                        <div class="text-sm text-gray-600">${warung.location}</div>
                                    </div>
                                </div>
                            </a>
                        `;
                            });
                        }

                        if (data.menuItems.length > 0) {
                            searchResults.innerHTML += `
                        <div class="p-2 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-600 px-2">Menu</h3>
                        </div>
                    `;

                            data.menuItems.forEach(item => {
                                searchResults.innerHTML += `
                            <a href="/warung/${item.warung_slug}" 
                               class="block p-4 hover:bg-gray-50 transition-all duration-300">
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

                    searchResults.style.display = 'block';
                } catch (error) {
                    if (error.name === 'AbortError') return;

                    console.error('Search error:', error);
                    searchResults.innerHTML = `
                <div class="p-4 text-red-500 text-center">
                    Terjadi kesalahan saat mencari
                </div>
            `;
                }
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
                searchInput.classList.remove('ring-2', 'ring-purple-500');
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
                searchInput.classList.remove('ring-2', 'ring-purple-500');
                searchInput.blur();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('warungGrid');
            const cards = document.querySelectorAll('.warung-card');

            // Animate grid on load
            setTimeout(() => {
                grid.style.transition = 'all 0.5s ease-out';
                grid.style.opacity = '1';
                grid.style.transform = 'translateY(0)';
            }, 100);

            // Stagger card animations
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });

            // Hover animations
            cards.forEach(card => {
                const image = card.querySelector('img');
                const container = card.querySelector('a');

                card.addEventListener('mouseenter', () => {
                    image.style.transform = 'scale(1.05)';
                    container.style.transform = 'translateY(-4px)';
                });

                card.addEventListener('mouseleave', () => {
                    image.style.transform = 'scale(1)';
                    container.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>