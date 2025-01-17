<!-- resources/views/cart/index.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - UperFood</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <main class="container mx-auto px-4 py-8">
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

        @forelse($carts as $cart)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">{{ $cart->warung->name }}</h2>

            <div class="divide-y">
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

            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total</span>
                    <span>Rp {{ number_format($cart->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-lg shadow">
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
        <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t p-4">
            <div class="container mx-auto max-w-7xl px-4">
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
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-purple-500 text-white py-12">
        <div class="container mx-auto grid grid-cols-3 gap-8 px-4">
            <div>
                <h3 class="font-bold mb-4">Universitas Pertamina</h3>
                <p>Jl. Teuku Nyak Arief, Simprug,<br>
                    Kec. Kby. Lama, Kota Jakarta Selatan,<br>
                    Daerah Khusus Ibukota Jakarta</p>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-uperfood-white.png') }}" alt="UperFood" style="height: 7rem;">
            </div>
            <div class="text-right">
                <h3 class="font-bold mb-4">Makanan Lezat dan Enak di<br>
                    Lingkungan Universitas Pertamina</h3>
                <p>© UperFood 2024</p>
            </div>
        </div>
    </footer>

    <script>
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