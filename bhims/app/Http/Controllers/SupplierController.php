<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Active/Inactive filter
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $suppliers = $query->latest()->paginate(10)->withQueryString();
        
        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->has('ajax')) {
            $view = view('suppliers.partials.table', compact('suppliers'))->render();
            $pagination = $suppliers->appends($request->except('page', 'ajax'))->links()->toHtml();
            
            return response()->json([
                'html' => $view,
                'pagination' => $pagination
            ]);
        }
        
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:suppliers',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:suppliers,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'tax_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $supplier = Supplier::create($validated);

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating supplier: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('purchaseOrders');
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
                'contact_person' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'tax_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $supplier->update($validated);

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating supplier: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has any purchase orders
            if ($supplier->purchaseOrders()->exists()) {
                return redirect()->back()
                    ->with('warning', 'Cannot delete supplier with associated purchase orders.');
            }
            
            $supplier->delete();
            
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }
}
