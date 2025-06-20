<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     */
    public function index()
    {
        $ingredients = Ingredient::with('category')
            ->latest()
            ->paginate(10);

        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create()
    {
        $categories = Category::all();
        return view('ingredients.create', compact('categories'));
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_of_measure' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $ingredient = Ingredient::create($validated);

        // Record initial stock movement
        if ($ingredient->current_stock > 0) {
            StockMovement::create([
                'ingredient_id' => $ingredient->id,
                'quantity' => $ingredient->current_stock,
                'movement_type' => 'initial',
                'notes' => 'Initial stock',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient created successfully.');
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Ingredient $ingredient)
    {
        $stockMovements = $ingredient->stockMovements()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('ingredients.show', compact('ingredient', 'stockMovements'));
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient)
    {
        $categories = Category::all();
        return view('ingredients.edit', compact('ingredient', 'categories'));
    }

    /**
     * Update the specified ingredient in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_of_measure' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully.');
    }

    /**
     * Remove the specified ingredient from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        // Prevent deletion if there are stock movements
        if ($ingredient->stockMovements()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete ingredient with stock movement history.');
        }

        $ingredient->delete();

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient deleted successfully.');
    }

    /**
     * Show the form for adjusting stock.
     */
    public function showAdjustStock(Ingredient $ingredient)
    {
        return view('ingredients.adjust-stock', compact('ingredient'));
    }

    /**
     * Adjust the stock level.
     */
    public function adjustStock(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,remove,set',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'required|string|max:1000',
        ]);

        return DB::transaction(function () use ($ingredient, $validated) {
            $oldStock = $ingredient->current_stock;
            
            switch ($validated['adjustment_type']) {
                case 'add':
                    $newStock = $oldStock + $validated['quantity'];
                    $movementType = 'addition';
                    break;
                case 'remove':
                    if ($validated['quantity'] > $oldStock) {
                        return back()->with('error', 'Insufficient stock.');
                    }
                    $newStock = $oldStock - $validated['quantity'];
                    $movementType = 'removal';
                    break;
                case 'set':
                    $newStock = $validated['quantity'];
                    $movementType = 'adjustment';
                    break;
            }

            // Update stock
            $ingredient->update(['current_stock' => $newStock]);

            // Record stock movement
            StockMovement::create([
                'ingredient_id' => $ingredient->id,
                'quantity' => $validated['quantity'],
                'movement_type' => $movementType,
                'notes' => $validated['notes'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('ingredients.show', $ingredient)
                ->with('success', 'Stock adjusted successfully.');
        });
    }

    /**
     * Get low stock ingredients.
     */
    public function lowStock()
    {
        $ingredients = Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('current_stock')
            ->paginate(10);

        return view('ingredients.low-stock', compact('ingredients'));
    }
}
