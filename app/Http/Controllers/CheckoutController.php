<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // Get cart from session or user ID
        $cartId = Session::get('cart_id');
        $carts = [];

        if ($cartId) {
            $carts = Cart::with(['items.menuItem', 'warung'])
                ->where('id', $cartId)
                ->get();
        } elseif (Auth::check()) {
            $carts = Cart::with(['items.menuItem', 'warung'])
                ->where('user_id', Auth::id())
                ->get();
        }

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $total = $carts->sum('total_amount');

        return view('checkout.index', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get cart 
            $cartId = Session::get('cart_id');
            $carts = [];

            if ($cartId) {
                $carts = Cart::with(['items.menuItem', 'warung'])
                    ->where('id', $cartId)
                    ->get();
            } elseif (Auth::check()) {
                $carts = Cart::with(['items.menuItem', 'warung'])
                    ->where('user_id', Auth::id())
                    ->get();
            }

            if ($carts->isEmpty()) {
                throw new \Exception('Keranjang kosong');
            }

            foreach($carts as $cart) {
                // Create new order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'warung_id' => $cart->warung_id,
                    'total_amount' => $cart->total_amount,
                    'status' => 'pending',
                    'order_number' => 'ORD-' . time() . '-' . ($cart->user_id ?? 'GUEST')
                ]);

                // Copy items from cart to order
                foreach($cart->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $item->menu_item_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal
                    ]);
                }

                // Delete cart and items
                $cart->items()->delete();
                $cart->delete();
            }

            // Clear cart session
            Session::forget('cart_id');

            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }
}