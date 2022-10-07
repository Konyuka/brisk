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

        $request->validate([
                'product_name' => 'required',
                'product_quantity' => 'required',
                'sales_price' => 'required',
                'wholesale_price' => 'required',
                'finished_products' => 'required'
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_quantity = $request->product_quantity;
        $product->product_code = $request->product_code;
        $product->bar_code = $request->bar_code;
        $product->sales_price = $request->sales_price;
        $product->wholesale_price = $request->wholesale_price;
        $product->finished_products = $request->finished_products;
        $product->in_delivery = $request->in_delivery;
        $product->spoiled_products = $request->spoiled_products;
        $product->missing_products = $request->missing_products;
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
        return dd($request);


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

        $activeTrips =  Trip::where('trip_complete', 0)->select('user_ids')->get();
        
        $agentsArrayPush = [];
        foreach($activeTrips as $key=>$value){
            $agentsArray =  json_decode($value->user_ids);
            foreach($agentsArray as $actual){
                array_push($agentsArrayPush, $actual);
            }
        }
        

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
            'activeAgents' => $agentsArrayPush,
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
        $teamLead = User::where(['admin'=>3])->latest()->get();
        $trip = Trip::latest()->get()->first();  
        $trips = Trip::latest()->get(); 
        
        $activeTrips =  Trip::where('trip_complete', 0)->select('user_ids')->get();
        $decodedActiveTrips = json_decode($activeTrips);

        // return dd($activeTrips);

        $agentsArrayPush = [];
        foreach($activeTrips as $key=>$value){
            $agentsArray =  json_decode($value->user_ids);
            foreach($agentsArray as $actual){
                array_push($agentsArrayPush, $actual);
            }
        }

        $teamLeadsID =  Trip::select('team_lead')->get();
        $activeTeamLeads =  json_decode($teamLeadsID, true);

        // return dump($activeTeamLeads);
        $leadsArrayPush = [];
        foreach($activeTeamLeads as $value){
            $teamLeadValue =  $value["team_lead"];
            array_push($leadsArrayPush, $teamLeadValue);
        }        
        
        if($trip != null){
            $tripBatch = $trip->id + 1;
        }else {
            $tripBatch = 1;
        }


        return Inertia::render('Delivery', [
            'products' => $products,
            'salesAgents' => $salesAgents,
            'teamLead' => $teamLead,
            'tripBatch' => $tripBatch,
            'trips' => $trips,
            'activeAgents' => $agentsArrayPush,
            'activeTeamLeads' => $leadsArrayPush,
        ]);

    }

    public function processDelivery(Request $request)
    
    {
        $productArray = json_decode($request->getContent());
        $agentArray = json_decode($request->getContent());
        $detailsArray = json_decode($request->getContent());

        
        foreach($productArray as $elementKey => $element) {
            foreach($element as $valueKey => $value) {
                if($valueKey == 'selectedType' && $value == 'agent'){
                    //delete this particular object from the $array
                    unset($productArray[$elementKey]);
                }
                if($valueKey == 'selectedType' && $value == 'details'){
                    //delete this particular object from the $array
                    unset($productArray[$elementKey]);
                } 
            }
        }
        foreach($agentArray as $elementKey => $element) {
            foreach($element as $valueKey => $value) {
                if($valueKey == 'selectedType' && $value == 'product'){
                    //delete this particular object from the $array
                    unset($agentArray[$elementKey]);
                }
                if($valueKey == 'selectedType' && $value == 'details'){
                    //delete this particular object from the $array
                    unset($agentArray[$elementKey]);
                } 
            }
        }
        foreach($detailsArray as $elementKey => $element) {
            foreach($element as $valueKey => $value) {
                if($valueKey == 'selectedType' && $value == 'product'){
                    //delete this particular object from the $array
                    unset($detailsArray[$elementKey]);
                } 
                if($valueKey == 'selectedType' && $value == 'agent'){
                    //delete this particular object from the $array
                    unset($detailsArray[$elementKey]);
                } 
            }
        }
        foreach($detailsArray as $key=>$value){
             $tripBatch = $value->tripBatch;
        }
        foreach($detailsArray as $key=>$value){
             $leadName = User::where('id', $value->lead)->get()->first();
            //  return dd($leadName->name);
            // $agentsArray = [];
            // foreach($agentArray as $key=>$value){
            //     User::where('id', $value->selectedAgentID)
            //     ->update([
            //         'trip_batch' => $tripBatch
            //     ]);

            //     $idsArray = []; 
            //     array_push($agentsArray, $value->selectedAgentID);

            // }

            $createdTrip =  Trip::create([
                'added_by' => $value->added_by,
                'number_users' => $value->agentsNumber,
                // 'user_ids' => $agentsArray,
                'number_products' => $value->loadedProducts,
                'number_brands' => $value->brandsNumber,
                'products_sold' => 0,
                'products_returned' => 0,
                'products_spoiled' => 0,
                'products_missing' => 0,
                'trip_location' => $value->location,
                'team_lead' => $value->lead,
                'lead_name' => $leadName->name,
                'vehicle_number' => $value->truckPlate,
                'driver_name' => $value->driver,
            ]);
        }

        // return dd($createdTrip);
        foreach($productArray as $key=>$value){
            // return dd($value);
             $remainingStock = $value->remainingProducts - $value->productQuantity;
             $productQuantity = $value->productQuantity;
             $productTripBatch = Product::where('id', $value->selectedproductID)->select('trip_batch')->first();
             $batchArray = [];
             $productTripBatchDecoded = json_decode($productTripBatch->trip_batch);

             
             if(is_null($productTripBatchDecoded)){
                $batchObject = new \stdClass();
                $batchObject->batchNumber = $tripBatch;
                $batchObject->numberItems = $productQuantity;
                $batchObject->itemsSold = 0;

                 array_push($batchArray, $batchObject);
                }else{
                     foreach ($productTripBatchDecoded as $item) {
                         $batchObject = new \stdClass();
                         $batchObject->batchNumber = $item->batchNumber;
                         $batchObject->numberItems = $item->numberItems; 
                         $batchObject->itemsSold = $item->itemsSold; 

                         array_push($batchArray, $batchObject);
                     }
                     $batchObject = new \stdClass();
                     $batchObject->batchNumber = $tripBatch;
                     $batchObject->numberItems = $productQuantity; 
                     $batchObject->itemsSold = 0;
                     array_push($batchArray, $batchObject);  
                    }

             Product::where('id', $value->selectedproductID)
             ->update([
                 'trip_batch' => $batchArray,
                 'in_delivery' => $value->productQuantity,
                 'finished_products' => $remainingStock
             ]);
        }
        $agentsArray = [];
        foreach($agentArray as $key=>$value){
             User::where('id', $value->selectedAgentID)
             ->update([
                 'trip_batch' => $tripBatch
             ]);

            $idsArray = []; 
            array_push($agentsArray, $value->selectedAgentID);

        }

        Trip::where('id', $createdTrip->id)
        ->update([
            'user_ids' => $agentsArray,
        ]);

        if($tripBatch == 1){
            Trip::where('id', $createdTrip->id)
            ->update([
                'id' => 1,
            ]);
        }
        // Trip::where('id', $tripBatch)
        // ->update([
        //     'user_ids' => $agentsArray,
        // ]);


        // return dd($productArray, $agentArray, $detailsArray);
        // return dd($productArray);

        
        return redirect()->back()->with('success', 'Delivery Registered Successfully');

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
