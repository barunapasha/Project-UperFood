<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $carts = Cache::remember("user_carts_" . $userId, now()->addMinutes(5), function () use ($userId) {
            return Cart::with(['items.menuItem', 'warung'])
                ->where('user_id', $userId)
                ->get();
        });

        return view('cart.index', compact('carts'));
    }

    public function addItem(Request $request)
    {
        $menuItem = MenuItem::findOrFail($request->menu_item_id);

        // Cek apakah sudah ada cart untuk warung ini
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
            'warung_id' => $menuItem->menuCategory->warung_id,
        ]);

        // Cek apakah item sudah ada di cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('menu_item_id', $menuItem->id)
            ->first();

        if ($cartItem) {
            // Update quantity jika item sudah ada
            $cartItem->quantity += $request->quantity;
            $cartItem->subtotal = $cartItem->quantity * $menuItem->price;
            $cartItem->save();
        } else {
            // Buat item baru jika belum ada
            CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $request->quantity,
                'price' => $menuItem->price,
                'subtotal' => $menuItem->price * $request->quantity
            ]);
        }

        // Update total cart
        $cart->total_amount = $cart->items->sum('subtotal');
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang'
        ]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->quantity = $request->quantity;
        $cartItem->subtotal = $cartItem->price * $request->quantity;
        $cartItem->save();

        // Update total cart
        $cart = $cartItem->cart;
        $cart->total_amount = $cart->items->sum('subtotal');
        $cart->save();

        if ($request->quantity <= 0) {
            $cartItem->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate'
        ]);
    }

    public function removeItem($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;

        $cartItem->delete();

        // Update total cart
        $cart->total_amount = $cart->items->sum('subtotal');
        $cart->save();

        // Hapus cart jika tidak ada item
        if ($cart->items->isEmpty()) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $carts = Cart::with(['items.menuItem', 'warung'])
                ->where('user_id', $userId)
                ->get();

            // Clear cart data
            foreach ($carts as $cart) {
                $cart->items()->delete();
                $cart->delete();
                Cache::forget($cart->getCacheKey());
            }

            // Clear other related cache
            Cache::forget("cart_count_" . $userId);
            Cache::forget("user_carts_" . $userId);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat checkout: ' . $e->getMessage()
            ], 500);
        }
    }
}
