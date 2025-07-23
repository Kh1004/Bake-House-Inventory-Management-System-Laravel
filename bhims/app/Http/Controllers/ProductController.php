<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'recipe'])
            ->latest()
            ->paginate(15);
            
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $recipes = Recipe::pluck('name', 'id');
        
        return view('products.create', compact('categories', 'recipes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'recipe_id' => 'nullable|exists:recipes,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $product = Product::create($validated);
            
            // If product has a recipe, update ingredient stock
            if (!empty($validated['recipe_id'])) {
                $recipe = Recipe::with('ingredients')->findOrFail($validated['recipe_id']);
                
                foreach ($recipe->ingredients as $ingredient) {
                    $quantityUsed = $ingredient->pivot->quantity;
                    
                    // Check if there's enough stock
                    if ($ingredient->current_stock < $quantityUsed) {
                        throw new \Exception("Not enough stock for ingredient: " . $ingredient->name . ". Available: " . $ingredient->current_stock);
                    }
                    
                    // Update ingredient stock
                    $ingredient->decrement('current_stock', $quantityUsed);
                    
                    // Record stock movement
                    $ingredient->stockMovements()->create([
                        'quantity' => -$quantityUsed,
                        'movement_type' => 'product_production',
                        'notes' => 'Used in product: ' . $validated['name'],
                        'user_id' => auth()->id(),
                        'reference_type' => Product::class,
                        'reference_id' => $product->id,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('products.show', $product)
                ->with('success', 'Product created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'recipe', 'stockMovements' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::pluck('name', 'id');
        $recipes = Recipe::pluck('name', 'id');
        
        return view('products.edit', compact('product', 'categories', 'recipes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'recipe_id' => 'nullable|exists:recipes,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $product->update($validated);
            
            DB::commit();
            
            return redirect()->route('products.show', $product)
                ->with('success', 'Product updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            
            // Check if product has any sales before deleting
            if ($product->sales()->exists()) {
                return back()->with('error', 'Cannot delete product with existing sales records');
            }
            
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
