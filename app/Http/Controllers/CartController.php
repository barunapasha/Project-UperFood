<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartId = Session::get('cart_id');
        $carts = [];

        if ($cartId) {
            $carts = Cart::with(['items.menuItem.menuCategory.warung', 'warung'])
                ->where('id', $cartId)
                ->get();
        } elseif (Auth::check()) {
            $carts = Cart::with(['items.menuItem.menuCategory.warung', 'warung'])
                ->where('user_id', Auth::id())
                ->get();
        }

        return view('cart.index', compact('carts'));
    }

    public function addItem(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'menu_item_id' => 'required|exists:menu_items,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $menuItem = MenuItem::with('menuCategory.warung')->findOrFail($request->menu_item_id);
            $warungId = $menuItem->menuCategory->warung_id;

            // Get cart from session or create new one
            $cartId = Session::get('cart_id');
            $cart = null;

            if ($cartId) {
                $cart = Cart::find($cartId);
            }

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id() ?? null,
                    'warung_id' => $warungId,
                    'total_amount' => 0,
                    'session_id' => Session::getId()
                ]);
                Session::put('cart_id', $cart->id);
            }

            // Check if item exists in cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('menu_item_id', $menuItem->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $request->quantity,
                    'price' => $menuItem->price,
                    'subtotal' => $menuItem->price * $request->quantity
                ]);
            }

            // Update cart total
            $cart->total_amount = $cart->items()->sum('subtotal');
            $cart->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang',
                'redirect_url' => route('cart.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateItem(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $cartItem = CartItem::findOrFail($id);
            
            if ($request->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->quantity = $request->quantity;
                $cartItem->subtotal = $cartItem->price * $request->quantity;
                $cartItem->save();
            }

            $cart = $cartItem->cart;
            $cart->total_amount = $cart->items()->sum('subtotal');
            $cart->save();

            if ($cart->items()->count() === 0) {
                Session::forget('cart_id');
                $cart->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeItem($id)
    {
        try {
            DB::beginTransaction();

            $cartItem = CartItem::findOrFail($id);
            $cart = $cartItem->cart;

            $cartItem->delete();

            $cart->total_amount = $cart->items()->sum('subtotal');
            $cart->save();

            if ($cart->items()->count() === 0) {
                Session::forget('cart_id');
                $cart->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}