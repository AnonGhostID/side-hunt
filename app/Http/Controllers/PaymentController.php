<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            $payment->user_id = $request->user_id ?? null;
            $payment->status = $result->getStatus();
            $payment->checkout_link = $result['invoice_url'];
            $payment->external_id = $createInvoiceRequest['external_id'];
            $payment->amount = $request->amount;
            $payment->description = $request->description;
            $payment->save();

            return response()->json($payment);
        }

        public function notification(Request $request)
        {
            $external_id = $request->external_id;
            
            if (!$external_id) {
                return response()->json(['error' => 'External ID is required'], 400);
            }

            try {
                $result = $this->apiInstance->getInvoices(null, $external_id);

                // Get payment data
                $payment = Payment::where('external_id', $external_id)->first();
                
                if (!$payment) {
                    return response()->json(['error' => 'Payment not found'], 404);
                }

                if ($payment->status == 'settled' || $payment->status == 'paid') {
                    return response()->json(['message' => 'Payment already processed']);
                }

                if (!empty($result)) {
                    $xenditStatus = strtolower($result[0]['status']);
                    
                    // Update payment status
                    $payment->update(['status' => $xenditStatus]);
                    
                    // If payment is successful and it's a top-up, update user wallet
                    if (($xenditStatus === 'paid' || $xenditStatus === 'settled') && $payment->user_id) {
                        $user = $payment->user;
                        if ($user) {
                            $user->increment('dompet', $payment->amount);
                        }
                    }
                    
                    return response()->json(['message' => 'Payment status updated successfully']);
                }

                return response()->json(['error' => 'No payment data found'], 404);
                
            } catch (\Exception $e) {
                Log::error('Payment notification error: ' . $e->getMessage());
                return response()->json(['error' => 'Internal server error'], 500);
            }
        }
}
