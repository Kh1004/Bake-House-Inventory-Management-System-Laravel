<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DemandPredictionController extends Controller
{
    /**
     * Show the demand prediction dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('demand-prediction.show', compact('products'));
    }
}
