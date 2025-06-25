<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the recipes.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        
        $recipes = Recipe::with('ingredients')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'active', function($query) {
                return $query->where('is_active', true);
            })
            ->when($status === 'inactive', function($query) {
                return $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        if ($request->ajax() || $request->wantsJson()) {
            $view = view('recipes.partials.recipe_rows', [
                'recipes' => $recipes,
                'status' => $status,
                'search' => $search
            ])->render();
            
            $pagination = $recipes->withQueryString()->links()->toHtml();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'pagination' => $pagination,
                'count' => $recipes->total(),
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage()
            ]);
        }
            
        return view('recipes.index', compact('recipes', 'status', 'search'));
    }

    /**
     * Show the form for creating a new recipe.
     */
    public function create()
    {
        $ingredients = Ingredient::where('is_active', true)->get(['id', 'name', 'unit_of_measure']);
        return view('recipes.create', compact('ingredients'));
    }

    /**
     * Store a newly created recipe in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:recipes',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'serving_size' => 'required|numeric|min:0.1',
            'selling_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit_of_measure' => 'required|string|max:50',
            'ingredients.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create the recipe
            $recipe = Recipe::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'instructions' => $validated['instructions'],
                'serving_size' => $validated['serving_size'],
                'selling_price' => $validated['selling_price'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Attach ingredients with pivot data
            $ingredientsData = [];
            $totalCost = 0;

            foreach ($validated['ingredients'] as $ingredientData) {
                $ingredient = Ingredient::findOrFail($ingredientData['id']);
                
                $ingredientsData[$ingredientData['id']] = [
                    'quantity' => $ingredientData['quantity'],
                    'unit_of_measure' => $ingredientData['unit_of_measure'],
                    'notes' => $ingredientData['notes'] ?? null,
                ];

                // Calculate cost for this ingredient
                $totalCost += $ingredient->cost_per_unit * $ingredientData['quantity'];
            }

            $recipe->ingredients()->attach($ingredientsData);

            // Update recipe cost per serving
            $recipe->update([
                'cost_per_serving' => $totalCost / max(1, $validated['serving_size'])
            ]);

            DB::commit();

            return redirect()
                ->route('recipes.index')
                ->with('success', 'Recipe created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create recipe: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified recipe.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load('ingredients');
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified recipe.
     */
    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');
        $ingredients = Ingredient::where('is_active', true)->get(['id', 'name', 'unit_of_measure']);
        return view('recipes.edit', compact('recipe', 'ingredients'));
    }

    /**
     * Update the specified recipe in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recipes')->ignore($recipe->id),
            ],
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'serving_size' => 'required|numeric|min:0.1',
            'selling_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit_of_measure' => 'required|string|max:50',
            'ingredients.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update the recipe
            $recipe->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'instructions' => $validated['instructions'],
                'serving_size' => $validated['serving_size'],
                'selling_price' => $validated['selling_price'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Sync ingredients with pivot data
            $ingredientsData = [];
            $totalCost = 0;

            foreach ($validated['ingredients'] as $ingredientData) {
                $ingredient = Ingredient::findOrFail($ingredientData['id']);
                
                $ingredientsData[$ingredientData['id']] = [
                    'quantity' => $ingredientData['quantity'],
                    'unit_of_measure' => $ingredientData['unit_of_measure'],
                    'notes' => $ingredientData['notes'] ?? null,
                ];

                // Calculate cost for this ingredient
                $totalCost += $ingredient->cost_per_unit * $ingredientData['quantity'];
            }


            $recipe->ingredients()->sync($ingredientsData);

            // Update recipe cost per serving
            $recipe->update([
                'cost_per_serving' => $totalCost / max(1, $validated['serving_size'])
            ]);

            DB::commit();

            return redirect()
                ->route('recipes.index')
                ->with('success', 'Recipe updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update recipe: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified recipe from storage.
     */
    public function destroy(Recipe $recipe)
    {
        try {
            $recipe->delete();
            return redirect()
                ->route('recipes.index')
                ->with('success', 'Recipe deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete recipe: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of a recipe.
     */
    public function toggleStatus(Recipe $recipe)
    {
        try {
            $recipe->update(['is_active' => !$recipe->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Recipe status updated successfully',
                'is_active' => $recipe->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update recipe status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
