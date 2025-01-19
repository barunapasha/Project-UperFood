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

        if (!$cartId) {
            return view('cart.index', [
                'carts' => collect(),
                'isEmpty' => true
            ]);
        }

        try {
            // Get cart items dengan relasi yang dibutuhkan
            $cartItems = CartItem::with(['menuItem.menuCategory.warung'])
                ->where('cart_id', $cartId)
                ->get();

            if ($cartItems->isEmpty()) {
                Session::forget('cart_id');
                return view('cart.index', [
                    'carts' => collect(),
                    'isEmpty' => true
                ]);
            }

            // Group items berdasarkan warung_id secara unik
            $groupedCarts = collect();

            $cartItems->each(function ($item) use (&$groupedCarts) {
                $warungId = $item->menuItem->menuCategory->warung_id;
                $warung = $item->menuItem->menuCategory->warung;

                if (!$groupedCarts->has($warungId)) {
                    $groupedCarts->put($warungId, [
                        'warung' => $warung,
                        'items' => collect(),
                        'total_amount' => 0
                    ]);
                }

                $groupData = $groupedCarts->get($warungId);
                $groupData['items']->push($item);
                $groupData['total_amount'] += $item->subtotal;
                $groupedCarts->put($warungId, $groupData);
            });

            // Transform ke format yang dibutuhkan view
            $carts = $groupedCarts->map(function ($group) {
                return (object)[
                    'warung' => $group['warung'],
                    'items' => $group['items'],
                    'total_amount' => $group['total_amount']
                ];
            })->values();

            return view('cart.index', [
                'carts' => $carts,
                'isEmpty' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart Error: ' . $e->getMessage());
            return view('cart.index', [
                'carts' => collect(),
                'isEmpty' => true
            ])->with('error', 'Terjadi kesalahan saat memuat keranjang');
        }
    }

    public function getItems()
    {
        try {
            $cartId = Session::get('cart_id');
            $items = [];

            if ($cartId) {
                $cart = Cart::with(['items.menuItem'])->find($cartId);
                if ($cart) {
                    $items = $cart->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->menuItem->name,
                            'price' => $item->price,
                            'quantity' => $item->quantity
                        ];
                    });
                }
            }

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting cart items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data keranjang'
            ], 500);
        }
    }

    public function getCartCount()
    {
        try {
            $cartId = Session::get('cart_id');
            $count = 0;

            if ($cartId) {
                $count = CartItem::where('cart_id', $cartId)->sum('quantity');
            } elseif (Auth::check()) {
                $count = Cart::where('user_id', Auth::id())
                    ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                    ->sum('cart_items.quantity');
            }

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting cart count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil jumlah item keranjang'
            ], 500);
        }
    }

    public function addItem(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'menu_item_id' => 'required|exists:menu_items,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // Ambil menu item dan warung_id
            $menuItem = MenuItem::select('menu_items.*', 'menu_categories.warung_id')
                ->join('menu_categories', 'menu_items.menu_category_id', '=', 'menu_categories.id')
                ->where('menu_items.id', $request->menu_item_id)
                ->firstOrFail();

            $warungId = $menuItem->warung_id;

            $cartId = Session::get('cart_id');
            $cart = null;

            if ($cartId) {
                $cart = Cart::find($cartId);
            }

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'warung_id' => $warungId,
                    'total_amount' => 0,
                    'session_id' => Session::getId()
                ]);
                Session::put('cart_id', $cart->id);
            }

            // Check if item already exists in cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('menu_item_id', $menuItem->id)
                ->first();

            if ($existingItem) {
                // Update quantity jika item sudah ada
                $existingItem->quantity = $request->quantity;
                $existingItem->subtotal = $existingItem->price * $request->quantity;
                $existingItem->save();
            } else {
                // Create new cart item jika belum ada
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
                'cartCount' => $cart->items()->sum('quantity')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cart Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
