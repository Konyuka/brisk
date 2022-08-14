<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;

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

    }

    public function registerClient(Request $request)
    {
       
        $client = new Client();
        $client->added_by = $request->added_by;
        $client->client_name = $request->client_name;
        $client->client_email = $request->client_email;
        $client->client_contact = $request->client_contact;
        $client->client_kra = $request->client_kra;
        $client->client_address = $request->client_address;
        $client->client_balance = $request->product_description;
        $client->save();
        return redirect()->back();

    }

    public function registerSale(Request $request)
    {
        return dd($request);
        $sale = new Sale();
        $sale->added_by = $request->added_by;
        $sale->client_id = $request->client_id;
        $sale->product_id = $request->product_id;
        $sale->product_quantity = $request->product_quantity;
        $sale->sale_amount = $request->sale_amount;
        $sale->payment_status = $request->payment_status;
        $sale->invoice_number = $request->invoice_number;
        $sale->save();

        $products = Product::latest()->get();
        $clients = Client::latest()->get();
        return Inertia::render('Inventory', [
            'products' => $products,
            'clients' => $clients
        ]);

        // $this->inventory();
        // return;
        // return redirect()->back();
    }
    
    public function inventory()
    {
        $products = Product::latest()->get();
        $clients = Client::latest()->get();
        return Inertia::render('Inventory', [
            'products' => $products,
            'clients' => $clients
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
