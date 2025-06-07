<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    var $apiInstance = null;

    public function __construct()
    {
        Configuration::setXenditKey('xnd_development_7Qgujm27QHHqpc15olW28d1yBzncI1f1KLHSGNMwGeRug2K6doSB426KYqvgEa');
        $this->apiInstance = new InvoiceApi();
    }
        public function store(Request $request)
        {
            $createInvoiceRequest = new \Xendit\Invoice\CreateInvoiceRequest(
                [
                    'external_id' => Str::random(32),
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'payer_email' => $request->input('email'),
                ]);
                
            $result = $this->apiInstance->createInvoice($createInvoiceRequest);
            
            //saveDB
            $payment = new Payment();
            $payment->status = $result->getStatus();
            $payment->checkout_link = $result['invoice_url'];
            $payment->external_id = $createInvoiceRequest['external_id'];
            $payment->save();

            return response()->json($payment);
        }

        public function notification(Request $request)
        {
            $result = $this->apiInstance->getInvoices(null, $request->external_id);

            // Get data
            $payment = Payment::where('external_id', $request->external_id)->firstOrFail();

            if ($payment->status == 'settled') {
                return response()->json('Payment anda telah di proses');
            }

            // Update status
            $payment->status = strtolower($result[0]['status']);
            $payment->save();

            return response()->json('Success');
        }
}
