@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
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
                            class="text-gray-500 hover:text-gray-700">-</button>
                        <span>{{ $item->quantity }}</span>
                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                            class="text-gray-500 hover:text-gray-700">+</button>
                    </div>

                    <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>

                    <button onclick="removeItem({{ $item->id }})"
                        class="text-red-500 hover:text-red-700">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang Kosong</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai belanja untuk menambahkan item ke keranjang.</p>
    </div>
    @endforelse
</div>

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
            });
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
                });
        }
    }
</script>
@endsection