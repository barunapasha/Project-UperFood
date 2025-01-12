<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()  // Ganti dari dashboard() ke index()
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
            ]);

            $warung = Warung::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'image' => 'images/default-warung.jpg',
                'rating' => 0,
                'distance' => '0 km',
                'slug' => Str::slug($validated['name']),
                'open_hours' => '08:00 - 17:00'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Warung berhasil ditambahkan',
                'data' => $warung
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in storeWarung: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan warung: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateWarung(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:299',
                'location' => 'required|string',
            ]);

            $warung = Warung::findOrFail($id);
            $warung->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'slug' => Str::slug($validated['name'])
            ]);

            return redirect()->back()->with('success', 'Warung berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in updateWarung: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui warung.');
        }
    }

    public function deleteWarung($id)
    {
        try {
            DB::beginTransaction();
            $warung = Warung::findOrFail($id);

            // Delete related menu items and categories first
            foreach ($warung->menuCategories as $category) {
                $category->menuItems()->delete();
            }
            $warung->menuCategories()->delete();
            $warung->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Warung berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in deleteWarung: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus warung.');
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
                'is_available' => 'required|in:0,1'  // Ubah validasi ini
            ]);

            $menuCategory = MenuCategory::where('warung_id', $warungId)
                ->where('id', $categoryId)
                ->firstOrFail();

            $menuItem = new MenuItem([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_available' => (bool)$validated['is_available']  // Konversi ke boolean
            ]);

            $menuItem->menu_category_id = $categoryId;
            $menuItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors()['is_available'] ?? []),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan menu: ' . $e->getMessage()
            ], 500);
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
            ]);

            $menuItem = MenuItem::findOrFail($id);
            $menuItem->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_available' => $request->has('is_available')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui menu: ' . $e->getMessage()
            ], 500);
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

            $category = MenuCategory::create([
                'warung_id' => $warungId,
                'name' => $validated['name']
            ]);

            // Selalu return JSON
            return response()->json([
                'success' => true,
                'message' => 'Kategori menu berhasil dibuat',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kategori menu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMenuCategory(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $category = MenuCategory::findOrFail($id);
            $category->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori menu berhasil diperbarui',
                    'data' => $category
                ]);
            }

            return redirect()->back()->with('success', 'Kategori menu berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in updateMenuCategory: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui kategori menu: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat memperbarui kategori menu.');
        }
    }

    public function deleteMenuCategory($id)
    {
        try {
            DB::beginTransaction();

            $category = MenuCategory::findOrFail($id);
            $category->menuItems()->delete();
            $category->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori menu berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in deleteMenuCategory: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori menu: ' . $e->getMessage()
            ], 500);
        }
    }
}
