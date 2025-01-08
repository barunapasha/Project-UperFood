<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function dashboard()
   {
       $warungs = Warung::all();
       return view('admin.dashboard', compact('warungs'));
   }

   public function warungMenu($id)
   {
       $warung = Warung::with(['menuCategories.menuItems'])->findOrFail($id);
       return view('admin.warung-menu', compact('warung'));
   }

   public function createMenuItem(Request $request, $warungId, $categoryId)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255', 
           'description' => 'required|string',
           'price' => 'required|numeric',
           'is_available' => 'boolean',
           'image' => 'required|string'
       ]);

       $menuCategory = MenuCategory::findOrFail($categoryId);
       
       if($menuCategory->warung_id != $warungId) {
           return back()->with('error', 'Category does not belong to this warung');
       }

       $menuItem = MenuItem::create([
           'menu_category_id' => $categoryId,
           'name' => $validated['name'],
           'description' => $validated['description'], 
           'price' => $validated['price'],
           'is_available' => $validated['is_available'] ?? true,
           'image' => $validated['image']
       ]);

       return back()->with('success', 'Menu item created successfully');
   }

   public function updateMenuItem(Request $request, $id)
   {
       $menuItem = MenuItem::findOrFail($id);
       
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'price' => 'required|numeric', 
           'is_available' => 'boolean',
           'image' => 'required|string'
       ]);

       $menuItem->update($validated);

       return back()->with('success', 'Menu item updated successfully');
   }

   public function deleteMenuItem($id)
   {
       $menuItem = MenuItem::findOrFail($id);
       $menuItem->delete();

       return back()->with('success', 'Menu item deleted successfully');
   }

   public function createMenuCategory(Request $request, $warungId)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255'
       ]);

       $warung = Warung::findOrFail($warungId);

       $menuCategory = MenuCategory::create([
           'warung_id' => $warungId,
           'name' => $validated['name']
       ]);

       return back()->with('success', 'Menu category created successfully');
   }

   public function updateMenuCategory(Request $request, $id)
   {
       $menuCategory = MenuCategory::findOrFail($id);

       $validated = $request->validate([
           'name' => 'required|string|max:255'
       ]);

       $menuCategory->update($validated);

       return back()->with('success', 'Menu category updated successfully');
   }

   public function deleteMenuCategory($id)
   {
       $menuCategory = MenuCategory::findOrFail($id);
       
       $menuCategory->menuItems()->delete();
       
       $menuCategory->delete();

       return back()->with('success', 'Menu category and all its items deleted successfully');
   }

   public function createWarung(Request $request)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string',
           'image' => 'required|string',
           'open_hours' => 'required|string', 
           'distance' => 'required|string',
           'rating' => 'required|numeric|between:0,5',
           'slug' => 'required|string|unique:warungs'
       ]);

       $warung = Warung::create($validated);

       return back()->with('success', 'Warung created successfully');
   }

   public function updateWarung(Request $request, $id)
   {
       $warung = Warung::findOrFail($id);

       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'location' => 'required|string',
           'image' => 'required|string',
           'open_hours' => 'required|string',
           'distance' => 'required|string', 
           'rating' => 'required|numeric|between:0,5',
           'slug' => 'required|string|unique:warungs,slug,' . $id
       ]);

       $warung->update($validated);

       return back()->with('success', 'Warung updated successfully');
   }

   public function deleteWarung($id)
   {
       $warung = Warung::findOrFail($id);
       
       foreach($warung->menuCategories as $category) {
           $category->menuItems()->delete();
           $category->delete();
       }
       
       $warung->delete();

       return back()->with('success', 'Warung and all its menu data deleted successfully');
   }
}