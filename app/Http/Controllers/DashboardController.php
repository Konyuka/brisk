<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Client;
use App\Models\User;
use App\Models\Sale;
use App\Models\Trip;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    
    public function addProduct(Request $request)
    {
        // return dd($request);
        // return dd(json_decode($request));
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_quantity = $request->product_quantity;
        $product->product_code = $request->product_code;
        $product->bar_code = $request->bar_code;
        $product->sales_price = $request->sales_price;
        $product->finished_products = $request->finished_products;
        $product->in_delivery = $request->in_delivery;
        $product->spoiled_products = $request->spoiled_products;
        $product->added_by = $request->added_by;
        $product->tax_exempt = $request->tax_exempt;
        $product->save();
        return redirect()->back();

    }

    public function registerClient(Request $request)
    {
        // return dd($request);
       
        $client = new Client();
        $client->added_by = $request->added_by;
        $client->client_name = $request->client_name;
        $client->client_email = $request->client_email;
        $client->client_contact = $request->client_contact;
        $client->client_kra = $request->client_kra;
        $client->client_address = $request->client_address;
        $client->client_balance = 0;
        $client->tax_exempt = $request->tax_exempt;
        $client->save();
        // return;
        return redirect()->back()->with('success', 'Client Created Successfully');

    }

    public function registerUser(Request $request)
    {
       
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->admin = $request->permission;
        $user->save();
        // return;
        return redirect()->back()->with('success', 'User Created Successfully');

    }

    public function registerSale(Request $request)
    {
        $productID = $request->product_id;
        $productToUpdate = Product::where('id', $productID)->first();

        $sale = new Sale();
        $sale->added_by = $request->added_by;
        $sale->client_id = $request->client_id;
        $sale->product_id = $request->product_id;
        $sale->product_quantity = $request->product_quantity;
        $sale->sale_amount = $request->sale_amount;
        $sale->payment_status = $request->payment_status;
        $sale->payment_method = 'mpesa';
        $sale->invoice_number = $request->invoice_number;
        $sale->save();

        $product = Product::where(['id'=>$productID])->first();
        $client = Client::where(['id'=>$request->client_id])->first();

        // return dd($product, $client);

        return Inertia::render('Order', [
            'product' => $product,
            'client' => $client,
            'sale' => $sale,

        ]);

        // $this->inventory();
        // return;
        // return redirect()->back();
    }

    public function finishSale(Request $request)
    {
        $sale = Sale::where(['id'=>$request->sale_id])->first();
        $product = Product::where(['id'=>$request->product_id])->first();

        $itemsToDeduct = $sale->product_quantity;
        $initialStock = $product->stock_quantity;
        $finalStock = $initialStock - $itemsToDeduct; 

        // return dd($finalStock);

        $product->update(
            [
                'stock_quantity' => $finalStock
            ]
        );;
        $sale->update(
            [
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method
            ]
        );;

        // return redirect()->back()->with('message', 'Sale success!');
        // return redirect()->back();

        $products = Product::latest()->get();
        $clients = Client::latest()->get();

        return Inertia::render('Stock', [
            'products' => $products,
            'clients' => $clients,
            'message' => 'Sale Made',
        ]);

        // return Inertia::render('Order', [
        //     // 'clients' => $clients
        // ]);
    }
    
    public function stock()
    {
        $products = Product::latest()->get();
        $clients = Client::latest()->get();
        $invoice = Sale::latest()->get()->first();

        if($invoice != null){
            $invoiceID = $invoice->id;
        }else {
            $invoiceID = 1;
        }
        // return dd($invoice);
        $sales = Sale::latest()->get();
        return Inertia::render('Stock', [
            'products' => $products,
            'clients' => $clients,
            'invoiceLog' => $invoiceID,
            'sales' => $sales,
        ]);
    }

    public function usersClients()
    {
        $clients = Client::latest()->get();
        $users = User::latest()->get();
        return Inertia::render('Accounts', [
            'clients' => $clients,
            'users' => $users
        ]);
    }

    public function processOrder()
    {

        return Inertia::render('Order', [
            // 'clients' => $clients
        ]);
    }

    public function displayReceipt()
    {
        // $clients = Client::latest()->get();
        return Inertia::render('Receipt', [
            // 'clients' => $clients
        ]);
    }

    public function invoiceNumber()
    {
        $invoice = Sale::latest()->get()->first();
        // return Inertia::render('Stock', [
        //     'invoiceLog' => $invoice->id,
        // ]);
        // return redirect()->back()->with($invoice->id);
        return $invoice->id;
    }

    
    public function inventory()
    {
        $products = Product::latest()->get();
        
        return Inertia::render('Inventory', [
            'products' => $products,
        ]);
    }

    public function delivery()
    
    {
        $products = Product::latest()->get();
        $salesAgents = User::where(['admin'=>4])->latest()->get();
        $trip = Trip::latest()->get()->first();  
        $trips = Trip::latest()->get();  

        // return dd($salesAgents);
        
        if($trip != null){
            $tripBatch = $trip->id;
        }else {
            $tripBatch = 1;
        }

        return Inertia::render('Delivery', [
            'products' => $products,
            'salesAgents' => $salesAgents,
            'tripBatch' => $tripBatch,
            'trips' => $trips,
        ]);

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
