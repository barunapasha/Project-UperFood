<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $warungData['name'] }} - UperFood</title>
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
                        <input type="text" placeholder="Cari makanan atau warung"
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
                        <span>{{ $warungData['location'] }}</span>
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

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 mb-6 transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Warung Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8 opacity-0 transform translate-y-4" id="warungHeader">
            <div class="relative h-64">
                <img src="{{ asset($warungData['image']) }}"
                    alt="{{ $warungData['name'] }}"
                    class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                    <h1 class="text-3xl font-bold text-white">{{ $warungData['name'] }}</h1>
                    <p class="text-white opacity-90 mt-2">{{ $warungData['description'] }}</p>
                </div>
            </div>
            <div class="p-6 flex items-center justify-between border-b">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 font-semibold">{{ $warungData['rating'] }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">{{ $warungData['distance'] }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">{{ $warungData['open_hours'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Categories -->
        @forelse($warungData['menus'] as $menu)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6 opacity-0 transform translate-y-4 menu-category">
            <h2 class="text-2xl font-bold mb-4">{{ $menu['category'] }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($menu['items'] as $item)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="font-semibold text-lg mb-2">{{ $item['name'] }}</h3>
                            @if($item['is_available'])
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Tersedia</span>
                            @else
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Tidak Tersedia</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm mb-2">{{ $item['description'] }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-purple-600 font-bold">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </span>
                            @if($item['is_available'])
                            <button onclick="addToCart('{{ $item['name'] }}', {{ $item['price'] }})"
                                class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 transition duration-300 transform hover:scale-105">
                                + Tambah
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada menu</h3>
            <p class="mt-1 text-sm text-gray-500">Menu untuk warung ini belum tersedia.</p>
        </div>
        @endforelse
    </main>

    <!-- Cart Modal -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="fixed right-0 top-0 h-full w-96 bg-white shadow-lg transform transition-transform duration-300" id="cartPanel">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Keranjang</h2>
                    <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="cartItems" class="space-y-4"></div>
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span id="cartSubtotal">Rp 0</span>
                    </div>
                    <button onclick="checkout()"
                        class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600 transition duration-300">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-500 text-white py-8 mt-12">
        <div class="container mx-auto px-4 grid grid-cols-3 gap-8">
            <div>
                <h3 class="font-bold mb-4">Universitas Pertamina</h3>
                <p>Jl. Teuku Nyak Arief, Simprug,<br>
                    Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                    Daerah Khusus Ibukota Jakarta</p>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" class="h-24">
            </div>
            <div class="text-right">
                <h3 class="font-bold mb-4">Makanan Lezat dan Enak di<br>
                    Lingkungan Universitas Pertamina</h3>
                <p>© UperFood 2024</p>
            </div>
        </div>
    </footer>

    <script>
        function addToCart(menuItemId, quantity = 1) {
            fetch('/cart/items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        menu_item_id: menuItemId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item berhasil ditambahkan ke keranjang!');
                        updateCartCount(); // Fungsi untuk update jumlah item di keranjang (opsional)
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan item ke keranjang');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Fade in untuk header warung
            setTimeout(() => {
                document.getElementById('warungHeader').style.opacity = '1';
                document.getElementById('warungHeader').style.transform = 'translateY(0)';
            }, 300);

            // Fade in untuk kategori menu
            const menuCategories = document.querySelectorAll('.menu-category');
            menuCategories.forEach((category, index) => {
                setTimeout(() => {
                    category.style.opacity = '1';
                    category.style.transform = 'translateY(0)';
                }, 500 + (index * 200));
            });
        });

        // Cart functionality
        let cart = [];

        function addToCart(name, price) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    name,
                    price,
                    quantity: 1
                });
            }
            updateCartUI();
            toggleCart(true);
        }

        function updateCartUI() {
            const cartItems = document.getElementById('cartItems');
            const subtotal = document.getElementById('cartSubtotal');

            cartItems.innerHTML = cart.map(item => `
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold">${item.name}</h4>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateQuantity('${item.name}', ${item.quantity - 1})" class="text-gray-500">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="updateQuantity('${item.name}', ${item.quantity + 1})" class="text-gray-500">+</button>
                        </div>
                    </div>
                    <span>Rp ${formatNumber(item.price * item.quantity)}</span>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            subtotal.textContent = `Rp ${formatNumber(total)}`;
        }

        function updateQuantity(name, newQuantity) {
            if (newQuantity < 1) {
                cart = cart.filter(item => item.name !== name);
            } else {
                const item = cart.find(item => item.name === name);
                if (item) {
                    item.quantity = newQuantity;
                }
            }
            updateCartUI();
        }

        function toggleCart(show = null) {
            const modal = document.getElementById('cartModal');
            const panel = document.getElementById('cartPanel');

            if (show === null) {
                show = modal.classList.contains('hidden');
            }

            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    panel.style.transform = 'translateX(0)';
                }, 10);
            } else {
                panel.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        function checkout() {
            alert('Fitur checkout akan segera hadir!');
        }

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>

    <style>
        .menu-category {
            transition: all 0.5s ease-out;
        }

        #cartPanel {
            transform: translateX(100%);
        }

        .menu-item:hover img {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
</body>

</html>