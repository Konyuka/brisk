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
use Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;



class DashboardController extends Controller
{
    public function uploadProduct(Request $request)
    {
        Product::truncate();
        Excel::import(new ProductsImport, $request->file('file')->store('temp'));
        // return Excel::download(new ProductsExport, 'products-collection.xlsx');
        return back();
    }
    public function downloadProduct()
    {
        return Excel::download(new ProductsExport, 'products-collection.xlsx');
        return back();
    }
    
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

    public function updateProduct(Request $request)
    {

        $request->validate([
            'product_name' => 'required',
            'product_quantity' => 'required',
            'sales_price' => 'required',
            'wholesale_price' => 'required',
            'finished_products' => 'required'
        ]);

        Product::where('id', $request->product_id)
        ->update([
            'product_name' => $request->product_name,
            'product_quantity' => $request->product_quantity,
            'product_code' => $request->product_code,
            'bar_code' => $request->bar_code,
            'sales_price' => $request->sales_price,
            'wholesale_price' => $request->wholesale_price,
            'finished_products' => $request->finished_products,
            'in_delivery' => $request->in_delivery,
            'finished_products' => $request->finished_products,
            'spoiled_products' => $request->spoiled_products,
            'missing_products' => $request->missing_products,
            'added_by' => $request->added_by,
            'tax_exempt' => $request->tax_exempt,
        ]);

        // $product = new Product();
        // $product->product_name = $request->product_name;
        // $product->product_quantity = $request->product_quantity;
        // $product->product_code = $request->product_code;
        // $product->bar_code = $request->bar_code;
        // $product->sales_price = $request->sales_price;
        // $product->wholesale_price = $request->wholesale_price;
        // $product->finished_products = $request->finished_products;
        // $product->in_delivery = $request->in_delivery;
        // $product->spoiled_products = $request->spoiled_products;
        // $product->missing_products = $request->missing_products;
        // $product->added_by = $request->added_by;
        // $product->tax_exempt = $request->tax_exempt;
        // $product->save();
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
        $saledetailsArray = json_decode($request->getContent());

        if (is_array($saledetailsArray)) {
            // return dd('Cash');
            $firstproduct = array_values($saledetailsArray)[0];
            $agent = User::where(['id' => $firstproduct->agentID])->first();
            $trip = Trip::where(['id' => $agent->trip_batch])->first();



            if (count((array)$firstproduct->client)) {
                $client = Client::where(['id' => $firstproduct->client->id])->first();
            }

            if (count((array)$firstproduct->client)) {
                $client_id = $client->id;
            } else {
                $client_id = null;
            }


            $string = \json_encode($saledetailsArray);

            $createdSale =  Sale::create([
                'added_by' => $agent->id,
                'trip_batch' => $trip->id,
                'client_id' => $client_id,
                'products' => $string,
                'sale_amount' => $firstproduct->overallTotal,
                'payment_method' => $firstproduct->mpesaPayment,
                'invoice_number' => $firstproduct->invoice_number,
                'mpesa_ref' => 0,
            ]);

            foreach ($saledetailsArray as $key => $value) {
                $product = Product::where('id', $value->selectedproductID)->first();
                $tripFigures = json_decode($product->trip_batch);
                $tripBatch = $agent->trip_batch;
                $tripObjectDetails = null;

                $newTripFigures = [];
                foreach ($tripFigures as $figure) {
                    if ($figure->batchNumber == $tripBatch) {
                        $initialFigure = $figure->itemsSold;
                        $figure->itemsSold = $value->productQuantity + $initialFigure;
                        // $figure->numberItems = $figure->numberItems - $value->productQuantity;
                    }
                }
                Product::where('id', $value->selectedproductID)
                    ->update([
                        'trip_batch' => $tripFigures
                    ]);
            }

            return redirect()->back()->with('success', 'Sale Registered Successfully');
        } else {

            $phone = $request->phone;
            $amount = $request->amount;
            $account = $request->account;

            $mpesa = new Mpesa();
            $BusinessShortCode = env('MPESA_STK_SHORTCODE');
            // $LipaNaMpesaPasskey=$this->lipaNaMpesaPassword();
            $LipaNaMpesaPasskey = env('MPESA_PASSKEY');
            $TransactionType = "CustomerPayBillOnline";
            $Amount = $amount;
            $PartyA = $phone;
            // $PartyA='254722326662';
            $PartyB = env('MPESA_STK_SHORTCODE');
            // $PhoneNumber='254722326662';
            $PhoneNumber = $phone;
            $CallBackURL = env('MPESA_TEST_URL') . '/api/stkpush';
            // $CallBackURL="https://tenderske.herokuapp.com/api/stkpush";
            $AccountReference = $account;
            $TransactionDesc = "Brisk POS Account";
            $Remarks = "Brisk International";

            $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
            $stkPushSimulation = json_decode($stkPushSimulation);
            $result_code = $stkPushSimulation->ResponseCode ?? null;
            // return dd($result_code);
            if (isset($result_code) and $result_code == "0") {
                $trans_id = $stkPushSimulation->MerchantRequestID;
                // dump($trans_id);
            }
            return redirect()->back();
        }

        // return;        
        
    }
    
    public function stock()
    {
        $products = Product::latest()->get();
        $clients = Client::latest()->get();
        $invoice = Sale::latest()->get()->first();
        $userId = auth()->user()->id;
        $sales = Sale::where('added_by', $userId)->latest()->get();

        // return dd($sales);

        $activeTrips =  Trip::where('trip_complete', 0)->select('user_ids')->get();
        
        $agentsArrayPush = [];
        foreach($activeTrips as $key=>$value){
            $agentsArray =  json_decode($value->user_ids);
            foreach($agentsArray as $actual){
                array_push($agentsArrayPush, $actual);
            }
        }

        $adminUsers =  User::where('admin', 1)->select('id')->get();
        $admindecoded = json_decode($adminUsers);
        $managerUsers =  User::where('admin', 2)->select('id')->get();
        $managersdecoded = json_decode($managerUsers);
        foreach ($managersdecoded as $key => $value) {
            array_push($agentsArrayPush, $value->id);
        }
        foreach ($admindecoded as $key => $value) {
            array_push($agentsArrayPush, $value->id);
        }
        // return dd($agentsArrayPush);
        // array_push($agentsArrayPush, json_decode($elevatedUsers));
        

        if($invoice != null){
            $invoiceID = $invoice->id;
        }else {
            $invoiceID = 1;
        }
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
        // return dd($productArray);
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
            $leadName = User::where('id', $value->lead)->get()->first();
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

        $tripBatch = $createdTrip->id;
        foreach ($productArray as $key => $value) {
             $remainingStock = $value->remainingProducts - $value->productQuantity;
             $productQuantity = $value->productQuantity;
             $productTripBatch = Product::where('id', $value->selectedproductID)->select('trip_batch')->first();
             $batchArray = [];
             $productTripBatchDecoded = json_decode($productTripBatch->trip_batch);

             
             if(is_null($productTripBatchDecoded)){
                $batchObject = new \stdClass();
                $batchObject->batchNumber = $tripBatch;
                $batchObject->numberItems = (int)$productQuantity;
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
                $batchObject->numberItems = (int)$productQuantity; 
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
        // return dd($batchArray);
        $agentsArray = [];
        foreach($agentArray as $key=>$value){
             User::where('id', $value->selectedAgentID)
             ->update([
                 'trip_batch' => $tripBatch
             ]);

            $idsArray = []; 
            array_push($agentsArray, $value->selectedAgentID);

        }
        $leadID = Trip::where('id', $createdTrip->id)->select('team_lead')->first();
        $decodedID = json_decode($leadID->team_lead);
        array_push($agentsArray, $decodedID);
        User::where('id', $decodedID)
            ->update([
                'trip_batch' => $tripBatch
            ]);

        // return dd($decodedID);

        Trip::where('id', $createdTrip->id)
        ->update([
            'user_ids' => $agentsArray,
        ]);

        $productIDsArray = [];
        foreach($productArray as $key=>$value){
           array_push($productIDsArray, $value->selectedproductID);  
        }
        Trip::where('id', $createdTrip->id)
        ->update([
            'products_ids' => $productIDsArray,
        ]);

        if($tripBatch == 1){
            Trip::where('id', $createdTrip->id)
            ->update([
                'id' => 1,
            ]);
        }
        
        return redirect()->back()->with('success', 'Trip Registered Successfully');

    }
   
    public function finishTrip(Request $request)
    {
        
        $tripdetailsArray = json_decode($request->getContent());

        // return dd($tripdetailsArray)


        foreach($tripdetailsArray as $key=>$value){
            $trip = Trip::where('id', $value->currentBatch)->first();
            $tripAgents = json_decode($trip->user_ids);

            if($value->itemsMissing == true){
                Trip::where('id', $tripdetailsArray[0]->currentBatch)
                 ->update([
                     'products_missing' => true,
                 ]);
            }


            foreach($tripAgents as $key=>$value){
                User::where('id', $value)
                ->update([
                    'trip_batch' => 0,
                ]);
            }
        }

        foreach($tripdetailsArray as $key=>$value){
            $finishedProduct = Product::where('id', $value->productID)->select("finished_products")->first();
            $inDelivery = Product::where('id', $value->productID)->select("in_delivery")->first();
            $spoiledProducts = Product::where('id', $value->productID)->select("spoiled_products")->first();
            $missingProducts = Product::where('id', $value->productID)->select("missing_products")->first();
            $tripBatch = Product::where('id', $value->productID)->select("trip_batch")->first();
            $tripBatchDecoded = json_decode($tripBatch->trip_batch);
            return dd($tripBatchDecoded);
            $newTripBatch = [];
            $recordedTripBatch = [];

            foreach($tripBatchDecoded as $key=>$value2){
                if($value2->batchNumber != $tripdetailsArray[0]->currentBatch) {
                    array_push($newTripBatch, $value2);
                }
                if($value2->batchNumber == $tripdetailsArray[0]->currentBatch) {
                    array_push($recordedTripBatch, $value2);
                }
            }

            
            $newFinished = intval(json_decode($finishedProduct->finished_products)) + intval($value->restocked);
            $newSpolied = intval(json_decode($finishedProduct->spoiled_products)) + intval($value->spoiledProducts);
            $newMissing = intval(json_decode($finishedProduct->missing_products)) + intval($value->missingProducts);
            
            // return dd($newFinished);

            Product::where('id', $value->productID)
             ->update([
                 'finished_products' => $newFinished,
                 'in_delivery' => 0,
                 'spoiled_products' => $newSpolied,
                 'missing_products' => $newMissing,
                 'trip_batch' => $newTripBatch
             ]);

            }
            

            Trip::where('id', $tripdetailsArray[0]->currentBatch)
                ->update([
                    'products_summary' => $tripdetailsArray,
                    'trip_complete' => true,
                    'products_missing' => true,
                ]);

             return redirect()->back()->with('success', 'Trip Finished Successfully');

        
    }

    public function destroy($id)
    {
        //
    }
}
