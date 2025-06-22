<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('ingredients');
        
        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sort by name or ingredients count
        if ($request->has('sort')) {
            if ($request->sort === 'ingredients') {
                $direction = $request->direction ?? 'desc';
                $query->orderBy('ingredients_count', $direction);
            } elseif ($request->sort === 'name') {
                $direction = str_starts_with($request->sort, '!') ? 'desc' : 'asc';
                $query->orderBy('name', $direction);
            }
        } else {
            $query->latest();
        }
        
        $categories = $query->paginate(10)->withQueryString();
        
        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->has('ajax')) {
            $view = view('categories.partials.table', compact('categories'))->render();
            $pagination = $categories->appends($request->except('page', 'ajax'))->links()->toHtml();
            
            return response()->json([
                'html' => $view,
                'pagination' => $pagination
            ]);
        }
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'description' => 'nullable|string',
            ]);

            Category::create($validated);

            return redirect()->route('categories.index')
                ->with('success', 'Category created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->ignore($category->id),
                ],
                'description' => 'nullable|string',
            ]);


            $category->update($validated);

            return redirect()->route('categories.index')
                ->with('success', 'Category updated successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Prevent deletion if category has ingredients
            if ($category->ingredients()->count() > 0) {
                return redirect()->route('categories.index')
                    ->with('error', 'Cannot delete category with associated ingredients. Please remove or reassign the ingredients first.');
            }

            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}