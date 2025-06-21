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
    public function index(Request $request)
    {
        $query = Ingredient::with('category');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $ingredients = $query->latest()->paginate(10);
        $categories = \App\Models\Category::all();

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->has('ajax')) {
            $view = view('ingredients.partials.table', compact('ingredients'))->render();
            $pagination = $ingredients->appends($request->except('page'))->links()->toHtml();
            
            return response()->json([
                'html' => $view,
                'pagination' => $pagination
            ]);
        }

        return view('ingredients.index', compact('ingredients', 'categories'));
    }
    
    /**
     * Display a listing of low stock ingredients.
     */
    public function lowStock(Request $request)
    {
        $query = Ingredient::with('category')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('minimum_stock', '>', 0);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $ingredients = $query->latest()->paginate(10);
        $categories = \App\Models\Category::all();
        
        // Set a flag to indicate this is the low stock view
        $isLowStockPage = true;

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->has('ajax')) {
            $view = view('ingredients.partials.table', compact('ingredients', 'isLowStockPage'))->render();
            $pagination = $ingredients->appends($request->except('page'))->links()->toHtml();
            
            return response()->json([
                'html' => $view,
                'pagination' => $pagination
            ]);
        }

        return view('ingredients.index', compact('ingredients', 'categories', 'isLowStockPage'));
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
        try {
            // Debug the request data
            \Log::info('Form submission data:', $request->all());
            
            // Manually validate the request
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'unit_of_measure' => 'required|string|max:50',
                'current_stock' => 'required|numeric|min:0',
                'minimum_stock' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            DB::beginTransaction();

            // Create the ingredient first
            $ingredient = Ingredient::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'unit_of_measure' => $request->unit_of_measure,
                'current_stock' => $request->current_stock,
                'minimum_stock' => $request->minimum_stock,
                'unit_price' => $request->unit_price,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);


            // Record initial stock movement if there's any stock
            if ($ingredient->current_stock > 0) {
                $movement = new StockMovement([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredient->current_stock,
                    'movement_type' => 'in',
                    'notes' => 'Initial stock',
                    'user_id' => Auth::id(),
                ]);
                $movement->save();
            }

            DB::commit();

            return redirect()->route('ingredients.index')
                ->with('success', 'Ingredient created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating ingredient: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error creating ingredient: ' . $e->getMessage())
                ->withInput();
        }
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
}
