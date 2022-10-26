<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Safaricom\Mpesa\Mpesa;
use Inertia\Inertia;



class MpesaResponseController extends Controller
{
    public function validation(Request $request)
    {
        Log::info('Validation endpoint hit');
        Log::info($request->all());

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }

    public function confirmation(Request $request)
    {
        Log::info('Confirmation endpoint hit');
        Log::info($request->all());

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }

    public function transactionStatusResponse(Request $request)
    {
        Log::info('transactionStatusResponse  endpoint hit');
        Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }

    public function stkPush(Request $request)
    {
        // return dd($request->all());
        Log::info('STK Push endpoint hit');
        Log::info($request->all());

        $mpesa = new Mpesa();
        $callBackData = $mpesa->getDataFromCallback();
        $object = json_decode($callBackData);
        $result_code = $object->Body->stkCallback->ResultCode;
        $trans_id = $object->Body->stkCallback->MerchantRequestID;
        $result_description = $object->Body->stkCallback->ResultDesc;

        // Log::info($object);
        // Log::info($result_code);

        if ($result_code == 0) {
            // $payment = Payments::where(['trans_id' => $trans_id])->first();
            // if ($payment) {


            //     $payment->completed = true;
            //     $payment->waiting = false;
            //     $payment->save();
            //     $post = $payment->info;

            // }
            // return dump('payment complete');
        } else if ($result_code == 1032) {
            // return dump('payment cancelled');
            // $payment = Payments::where(['trans_id' => $trans_id])->first();
            // $payment->completed = false;
            // $payment->waiting = false;
            // $payment->save();
            // $post = $payment->info;
        }


        // return dd($request);
        // return redirect()->route('success');
        // return Inertia::render('Success', ['Status' => 'Cancelled' ]);

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }
}
