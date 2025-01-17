<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - UperFood</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm mb-8">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="hover:opacity-80 transition">
                    <img src="{{ asset('images/logo-uperfood-blue.png') }}" alt="UperFood" class="h-16">
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="col-span-2">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">Ringkasan Pesanan</h2>
                    @foreach($carts as $cart)
                        <div class="mb-6 pb-6 border-b">
                            <h3 class="font-semibold text-lg mb-4">{{ $cart->warung->name }}</h3>
                            @foreach($cart->items as $item)
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        <p class="font-medium">{{ $item->menuItem->name }}</p>
                                        <p class="text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                            <div class="mt-4 pt-4 border-t">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-medium">Rp {{ number_format($cart->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-2xl font-bold mb-4">Total Pembayaran</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span>Total Pesanan</span>
                            <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-purple-500 text-white mt-16 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>© UperFood 2024</p>
            </div>
        </div>
    </footer>
</body>
</html>