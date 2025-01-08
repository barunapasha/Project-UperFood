<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $warungs = Warung::all();
            return view('admin.dashboard', compact('warungs'));
        } catch (\Exception $e) {
            Log::error('Error in dashboard: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data warung.');
        }
    }

    public function storeWarung(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:299',
                'location' => 'required|string',
                'image' => 'nullable|string'
            ]);

            Warung::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'image' => $validated['image'] ?? 'images/default-warung.jpg', 
                'rating' => 0,
                'distance' => '0 km',
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Warung berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in storeWarung: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan warung.');
        }
    }

    public function warungMenu($id)
    {
        try {
            $warung = Warung::with(['menuCategories.menuItems'])->findOrFail($id);
            return view('admin.warung-menu', compact('warung'));
        } catch (\Exception $e) {
            Log::error('Error in warungMenu: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data menu.');
        }
    }

    public function createMenuItem(Request $request, $warungId, $categoryId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'is_available' => 'sometimes|boolean',
                'image' => 'required|string'
            ]);

            $menuCategory = MenuCategory::where('warung_id', $warungId)
                ->where('id', $categoryId)
                ->firstOrFail();

            $menuItem = new MenuItem($validated);
            $menuItem->menu_category_id = $categoryId;
            $menuItem->is_available = $request->has('is_available');
            $menuItem->save();

            return redirect()->back()->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in createMenuItem: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan menu.');
        }
    }

    public function updateMenuItem(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'is_available' => 'sometimes|boolean',
                'image' => 'required|string'
            ]);

            $menuItem = MenuItem::findOrFail($id);
            $menuItem->fill($validated);
            $menuItem->is_available = $request->has('is_available');
            $menuItem->save();

            return redirect()->back()->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in updateMenuItem: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui menu.');
        }
    }

    public function deleteMenuItem($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);
            $menuItem->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Menu berhasil dihapus']);
            }
            return redirect()->back()->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in deleteMenuItem: ' . $e->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan saat menghapus menu.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus menu.');
        }
    }

    public function createMenuCategory(Request $request, $warungId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            MenuCategory::create([
                'warung_id' => $warungId,
                'name' => $validated['name']
            ]);

            return redirect()->back()->with('success', 'Kategori menu berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error in createMenuCategory: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat kategori menu.');
        }
    }

    public function updateMenuCategory(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $menuCategory = MenuCategory::findOrFail($id);
            $menuCategory->update($validated);

            return redirect()->back()->with('success', 'Kategori menu berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in updateMenuCategory: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui kategori menu.');
        }
    }

    public function deleteMenuCategory($id)
    {
        try {
            DB::beginTransaction();

            $menuCategory = MenuCategory::findOrFail($id);
            $menuCategory->menuItems()->delete();
            $menuCategory->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Kategori menu berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in deleteMenuCategory: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus kategori menu.');
        }
    }
}
