<?php

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;


// ─── List ────────────────────────────────────────────────────────────────────
Route::get('/', function (Request $request) {
    $categories = Product::whereNotNull('category')
        ->distinct()
        ->pluck('category');

    $cat_query    = $request->query('category');
    $search_query = $request->query('search');

    if ($search_query) {
        $products = Product::whereLike('name', "%{$search_query}%")->paginate(10);
    } elseif ($cat_query && $cat_query !== 'All Categories') {
        $products = Product::where('category', $cat_query)->paginate(10);
    } else {
        $products = Product::paginate(10);
    }

    return view('productManagement', [
        'products'   => $products,
        'categories' => $categories,
    ]);
});


// ─── Create ───────────────────────────────────────────────────────────────────
Route::post('/products', function (Request $request) {
    $validated = $request->validate([
        'name'       => 'required|string|max:255|unique:products,name',
        'category'   => 'required|string|max:255',
        'quantity'   => 'required|integer|min:0',
        'buy_price'  => 'required|numeric|min:0',
        'sell_price' => 'required|numeric|min:0',
    ], [
        'name.required'       => 'ကုန်ပစ္စည်းအမည် ထည့်သွင်းရန် လိုအပ်သည်။',
        'name.unique'         => 'ဤကုန်ပစ္စည်းအမည် ရှိပြီးဖြစ်သည်။',
        'name.max'            => 'ကုန်ပစ္စည်းအမည် အလွန်ရှည်နေသည်။',
        'category.required'   => 'အမျိုးအစား ရွေးချယ်ရန် လိုအပ်သည်။',
        'quantity.required'   => 'အရေအတွက် ထည့်သွင်းရန် လိုအပ်သည်။',
        'quantity.integer'    => 'အရေအတွက် ကိန်းဂဏန်း ဖြစ်ရမည်။',
        'quantity.min'        => 'အရေအတွက် သုညထက် မနည်းရ။',
        'buy_price.required'  => 'ဝယ်ဈေး ထည့်သွင်းရန် လိုအပ်သည်။',
        'buy_price.numeric'   => 'ဝယ်ဈေး ဂဏန်း ဖြစ်ရမည်။',
        'buy_price.min'       => 'ဝယ်ဈေး သုညထက် မနည်းရ။',
        'sell_price.required' => 'ရောင်းဈေး ထည့်သွင်းရန် လိုအပ်သည်။',
        'sell_price.numeric'  => 'ရောင်းဈေး ဂဏန်း ဖြစ်ရမည်။',
        'sell_price.min'      => 'ရောင်းဈေး သုညထက် မနည်းရ။',
    ]);

    Product::create($validated);

    return redirect('/')->with('success', 'ကုန်ပစ္စည်း အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
});


// ─── Export CSV ───────────────────────────────────────────────────────────────
Route::get('/products/export', function () {
    $products = Product::orderBy('name')->get();

    $filename = 'products_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control'       => 'no-store, no-cache',
        'Pragma'              => 'no-cache',
    ];

    $callback = function () use ($products) {
        $out = fopen('php://output', 'w');
        // UTF-8 BOM so Excel opens it correctly
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['name', 'category', 'quantity', 'buy_price', 'sell_price']);
        foreach ($products as $p) {
            fputcsv($out, [
                $p->name,
                $p->category ?? '',
                $p->quantity,
                $p->buy_price,
                $p->sell_price,
            ]);
        }
        fclose($out);
    };

    return response()->stream($callback, 200, $headers);
});


// ─── Update ───────────────────────────────────────────────────────────────────
Route::put('/products/{id}', function (Request $request, int $id) {
    $product = Product::findOrFail($id);

    $validated = $request->validate([
        'name'       => 'required|string|max:255|unique:products,name,' . $id,
        'category'   => 'required|string|max:255',
        'quantity'   => 'required|integer|min:0',
        'buy_price'  => 'required|numeric|min:0',
        'sell_price' => 'required|numeric|min:0',
    ], [
        'name.required'       => 'ကုန်ပစ္စည်းအမည် ထည့်သွင်းရန် လိုအပ်သည်။',
        'name.unique'         => 'ဤကုန်ပစ္စည်းအမည် ရှိပြီးဖြစ်သည်။',
        'name.max'            => 'ကုန်ပစ္စည်းအမည် အလွန်ရှည်နေသည်။',
        'category.required'   => 'အမျိုးအစား ရွေးချယ်ရန် လိုအပ်သည်။',
        'quantity.required'   => 'အရေအတွက် ထည့်သွင်းရန် လိုအပ်သည်။',
        'quantity.integer'    => 'အရေအတွက် ကိန်းဂဏန်း ဖြစ်ရမည်။',
        'quantity.min'        => 'အရေအတွက် သုညထက် မနည်းရ။',
        'buy_price.required'  => 'ဝယ်ဈေး ထည့်သွင်းရန် လိုအပ်သည်။',
        'buy_price.numeric'   => 'ဝယ်ဈေး ဂဏန်း ဖြစ်ရမည်။',
        'buy_price.min'       => 'ဝယ်ဈေး သုညထက် မနည်းရ။',
        'sell_price.required' => 'ရောင်းဈေး ထည့်သွင်းရန် လိုအပ်သည်။',
        'sell_price.numeric'  => 'ရောင်းဈေး ဂဏန်း ဖြစ်ရမည်။',
        'sell_price.min'      => 'ရောင်းဈေး သုညထက် မနည်းရ။',
    ]);

    $product->update($validated);

    return redirect('/')->with('success', 'ကုန်ပစ္စည်း အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
});


// ─── Delete Single ────────────────────────────────────────────────────────────
Route::delete('/products/{id}', function (int $id) {
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect('/')->with('success', 'ကုန်ပစ္စည်း အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
});


// ─── Clear All ────────────────────────────────────────────────────────────────
Route::delete('/products/delete', function () {
    Artisan::call('migrate:fresh');
    return redirect('/')->with('success', 'ကုန်ပစ္စည်းအားလုံး ဖျက်ပြီးပါပြီ။');
});


// ─── Excel Import ─────────────────────────────────────────────────────────────
Route::post('/products/import', function (Request $request) {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ], [
        'file.required' => 'ဖိုင် ရွေးချယ်ရန် လိုအပ်သည်။',
        'file.mimes'    => 'xlsx, xls သို့မဟုတ် csv ဖိုင်သာ ဖြစ်ရမည်။',
    ]);

    Excel::import(new ProductsImport, $request->file('file'));

    return redirect('/')->with('success', 'ကုန်ပစ္စည်းများ အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။');
});
