<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\MidtransService;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartId = Session::get('cart_id');

        if (!$cartId) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $cart = Cart::with(['items.menuItem', 'warung'])
            ->where('id', $cartId)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            Session::forget('cart_id');
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $carts = collect([$cart]);
        $total = $cart->total_amount;

        return view('checkout.index', compact('carts', 'total'));
    }

    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get cart from session
            $cartId = Session::get('cart_id');
            $cart = Cart::with(['items.menuItem', 'warung'])
                ->where('id', $cartId)
                ->firstOrFail();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'warung_id' => $cart->warung_id,
                'total_amount' => $cart->total_amount,
                'status' => 'pending',
                'order_number' => 'ORD-' . time() . '-' . Auth::id()
            ]);

            // Copy items from cart to order
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal
                ]);
            }

            // Get Snap Token from Midtrans
            $result = $this->midtransService->createTransaction($order);

            if (!$result['success']) {
                throw new \Exception('Gagal membuat transaksi');
            }

            // Save payment token
            $order->update(['payment_token' => $result['snap_token']]);

            // Clear cart after successful order creation
            $cart->items()->delete();
            $cart->delete();
            Session::forget('cart_id');

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
