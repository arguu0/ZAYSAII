<?php

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', function (Request $request) {
    $categories = Product::whereNotNull('category')
        ->distinct()
        ->pluck('category');

    $cat_query = $request->query('category');
    $search_query = $request->query('search');
    
    if ($search_query) $products = Product::whereLike('name', "%{$search_query}%")->paginate(10);

    elseif ($cat_query && $cat_query !== "All Categories") $products = Product::where('category', $cat_query)->paginate(10);
    else $products = Product::paginate(10);

    return view('productManagement', ['products' => $products, 'categories' => $categories]);
});

Route::delete('/products/delete', function() {
    Artisan::call('migrate:fresh');
    return redirect('/')->with('success', 'All products data have been cleared!');
});

Route::post('/products/import', function(Request $request) {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new ProductsImport, $request->file('file'));

    return redirect('/')->with('success', 'Products imported successfully!');
});