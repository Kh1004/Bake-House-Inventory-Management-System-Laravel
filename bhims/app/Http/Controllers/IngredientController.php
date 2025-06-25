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
        $query = Ingredient::with(['category', 'supplier']);

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
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();
        
        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')
                ->with('warning', 'Please create at least one category before adding ingredients.');
        }
        
        if ($suppliers->isEmpty()) {
            return redirect()->route('suppliers.create')
                ->with('warning', 'Please create at least one supplier before adding ingredients.');
        }
        
        return view('ingredients.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(Request $request)
    {
        \Log::info('=== Starting ingredient store process ===');
        \Log::info('Request data:', $request->all());
        
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'unit_of_measure' => 'required|string|max:50',
                'current_stock' => 'required|numeric|min:0',
                'minimum_stock' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0.01',
                'is_active' => 'sometimes|boolean',
            ]);
            
            \Log::info('Validation passed', $validated);
            
            DB::beginTransaction();
            
            // Create the ingredient
            $ingredient = new Ingredient([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'supplier_id' => $validated['supplier_id'] ?? null,
                'unit_of_measure' => $validated['unit_of_measure'],
                'current_stock' => $validated['current_stock'],
                'minimum_stock' => $validated['minimum_stock'],
                'unit_price' => $validated['unit_price'],
                'is_active' => $request->boolean('is_active', false) ? 1 : 0,
            ]);
            
            $ingredient->save();
            \Log::info('Ingredient created', ['id' => $ingredient->id]);
            
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
                \Log::info('Stock movement recorded', ['movement_id' => $movement->id]);
            }
            
            DB::commit();
            \Log::info('=== Transaction committed successfully ===');
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ingredient created successfully',
                    'redirect' => route('ingredients.index')
                ]);
            }
            
            return redirect()->route('ingredients.index')
                ->with('success', 'Ingredient created successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error creating ingredient', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed',
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating ingredient', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating ingredient: ' . $e->getMessage(),
                ], 500);
            }
            
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
        $ingredient->load(['supplier', 'category']);
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
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();
        
        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')
                ->with('warning', 'Please create at least one category before editing ingredients.');
        }
        
        if ($suppliers->isEmpty()) {
            return redirect()->route('suppliers.create')
                ->with('warning', 'Please create at least one supplier before editing ingredients.');
        }
        
        return view('ingredients.edit', compact('ingredient', 'categories', 'suppliers'));
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
            'supplier_id' => 'nullable|exists:suppliers,id',
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
