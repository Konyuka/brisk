<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Client;

class DashboardController extends Controller
{
    
    public function addProduct(Request $request)
    {
        // return dd($request);
        // return dd(json_decode($request));
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_quantity = $request->product_quantity;
        $product->stock_quantity = $request->stock_quantity;
        $product->production_price = $request->production_price;
        $product->sales_price = $request->sales_price;
        $product->product_description = $request->product_description;
        $product->added_by = $request->added_by;
        $product->save();
        return redirect()->back();

        // return Inertia::render('Inventory');
    }
    
    
    public function inventory()
    {
        $products = Product::latest()->get();
        return Inertia::render('Inventory', [
            'products' => $products
        ]);
    }

    public function addClient()
    {
        $clients = Client::latest()->get();
        return Inertia::render('AddClient', [
            'clients' => $clients
        ]);
    }

    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }
   
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
