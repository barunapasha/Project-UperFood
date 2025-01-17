<!-- resources/views/cart/index.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - UperFood</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        .slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stagger-animate>* {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .stagger-animate>*:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stagger-animate>*:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stagger-animate>*:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stagger-animate>*:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stagger-animate>*:nth-child(5) {
            animation-delay: 0.5s;
        }

        .stagger-animate>*:nth-child(6) {
            animation-delay: 0.6s;
        }

        .stagger-animate>*:nth-child(7) {
            animation-delay: 0.7s;
        }

        .stagger-animate>*:nth-child(8) {
            animation-delay: 0.8s;
        }

        .stagger-animate>*:nth-child(9) {
            animation-delay: 0.9s;
        }

        .stagger-animate>*:nth-child(10) {
            animation-delay: 1s;
        }
    </style>
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

                @if(session('success'))
                <div class="container mx-auto px-4 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="container mx-auto px-4 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                </div>
                @endif

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

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 mb-16">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        <div class="fade-in">
            @forelse($carts as $cart)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 hover-scale">
                <h2 class="text-xl font-semibold mb-4 fade-in">{{ $cart->warung->name }}</h2>

                <div class="divide-y stagger-animate">
                    @foreach($cart->items as $item)
                    <div class="py-4 flex justify-between items-center">
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $item->menuItem->name }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center">
                                    -
                                </button>
                                <span class="w-8 text-center">{{ $item->quantity }}</span>
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                    class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center">
                                    +
                                </button>
                            </div>

                            <span class="font-medium w-32 text-right">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>

                            <button onclick="removeItem({{ $item->id }})"
                                class="text-red-500 hover:text-red-700 ml-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t slide-in-right">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span>Rp {{ number_format($cart->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-lg shadow fade-in">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang Kosong</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai belanja untuk menambahkan item ke keranjang.</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 mt-4 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition-colors duration-300">
                    Mulai Belanja
                </a>
            </div>
            @endforelse

            @if($carts->isNotEmpty())
            <div class="mt-8 bg-white shadow-lg rounded-lg border p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-600">Total Pembayaran:</span>
                        <span class="text-xl font-bold ml-2">
                            Rp {{ number_format($carts->sum('total_amount'), 0, ',', '.') }}
                        </span>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                        class="bg-purple-500 text-white px-8 py-3 rounded-lg hover:bg-purple-600 transition-colors">
                        Checkout
                    </a>
                </div>
            </div>
            @endif
    </main>

    <!-- Footer yang diperbarui -->
    <footer class="bg-purple-500 text-white py-12">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Add initial opacity of 0 to prevent flash
            document.body.style.opacity = '0';

            // Fade in the entire page
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease';
                document.body.style.opacity = '1';
            }, 0);
        });

        function updateQuantity(itemId, newQuantity) {
            fetch(`/cart/items/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function removeItem(itemId) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                fetch(`/cart/items/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Add margin bottom to main content if cart is not empty
        if (document.querySelector('.fixed.bottom-0')) {
            document.querySelector('main').style.marginBottom = '100px';
        }
    </script>
</body>

</html>