<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class TopUpController extends Controller
{
    var $apiInstance = null;

    public function __construct()
    {
        $this->middleware('auth');
        Configuration::setXenditKey('xnd_development_7Qgujm27QHHqpc15olW28d1yBzncI1f1KLHSGNMwGeRug2K6doSB426KYqvgEa');
        $this->apiInstance = new InvoiceApi();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'nullable|numeric|min:20000',
            // 'custom_amount' => 'nullable|string',
            'custom_amount_raw' => 'nullable|numeric|min:20000'
        ]);

        $user = Auth::user();
        
        // Get amount from the appropriate field
        $amount = !empty($request->nominal) ? $request->nominal : (!empty($request->custom_amount_raw) ? $request->custom_amount_raw : null);

        if (!$amount || $amount < 20000) {
            return back()->with('error', 'Minimum top up adalah Rp 20.000');
        }

        if ($amount > 10000000) {
            return back()->with('error', 'Maximum top up adalah Rp 10.000.000');
        }

        try {
            $external_id = 'topup_' . Str::random(32);
            
            // Set expiry date to 24 hours from now
            $expiryDate = now()->addHours(24)->toISOString();
            
            $createInvoiceRequest = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id' => $external_id,
                'description' => "Top Up Saldo - Rp " . number_format($amount, 0, ',', '.') . " - " . $user->nama,
                'amount' => $amount,
                'payer_email' => $user->email,
                'expiry_date' => $expiryDate,
                'success_redirect_url' => route('manajemen.topup.success', ['external_id' => $external_id]),
                'failure_redirect_url' => route('manajemen.topup.failed', ['external_id' => $external_id]),
            ]);
            
            $result = $this->apiInstance->createInvoice($createInvoiceRequest);
            
            // Save to database
            $payment = Payment::create([
                'user_id' => $user->id,
                'status' => $result->getStatus(),
                'checkout_link' => $result['invoice_url'],
                'external_id' => $external_id,
                'amount' => $amount,
                'description' => "Top Up Saldo - Rp " . number_format($amount, 0, ',', '.') . " - " . $user->nama,
            ]);

            return redirect()->route('manajemen.topup.waiting', ['external_id' => $external_id]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function waiting($external_id)
    {
        $payment = Payment::where('external_id', $external_id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return view('manajemen.keuangan.topup-waiting', compact('payment'));
    }

    public function checkStatus(Request $request)
    {
        $external_id = $request->external_id;
        $payment = Payment::where('external_id', $external_id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        try {
            $result = $this->apiInstance->getInvoices(null, $external_id);
            
            if (!empty($result)) {
                $xenditStatus = strtolower($result[0]['status']);
                
                // Use database transaction with row locking to prevent race conditions
                return DB::transaction(function () use ($payment, $xenditStatus, $result) {
                    // Lock the payment row for update to prevent concurrent processing
                    $lockedPayment = Payment::where('id', $payment->id)->lockForUpdate()->first();
                    $previousStatus = $lockedPayment->status;
                    
                    // Extract payment method if available
                    $paymentMethod = null;
                    
                    // Check various possible fields for payment method information
                    if (isset($result[0]['payment_method'])) {
                        $paymentMethod = $result[0]['payment_method'];
                    } elseif (isset($result[0]['payment_channel'])) {
                        $paymentMethod = $result[0]['payment_channel'];
                    } elseif (isset($result[0]['payment_destination'])) {
                        $paymentMethod = $result[0]['payment_destination'];
                    } elseif (isset($result[0]['bank_code'])) {
                        $paymentMethod = $result[0]['bank_code'];
                    } elseif (isset($result[0]['payment_details']['payment_method'])) {
                        $paymentMethod = $result[0]['payment_details']['payment_method'];
                    }
                    
                    // Update payment status and method first
                    $updateData = ['status' => $xenditStatus];
                    if ($paymentMethod) {
                        $updateData['method'] = $paymentMethod;
                    }
                    
                    $lockedPayment->update($updateData);
                    
                    // If payment is successful and status changed from unpaid to paid, update user wallet
                    if (($xenditStatus === 'paid' || $xenditStatus === 'settled') && 
                        ($previousStatus !== 'paid' && $previousStatus !== 'settled')) {
                        $user = $lockedPayment->user;
                        $user->increment('dompet', $lockedPayment->amount);
                        
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Pembayaran berhasil! Saldo Anda telah ditambahkan.',
                            'new_balance' => $user->fresh()->dompet
                        ]);
                    }
                    
                    return response()->json([
                        'status' => $xenditStatus,
                        'message' => $this->getStatusMessage($xenditStatus)
                    ]);
                });
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengecek status pembayaran: ' . $e->getMessage()
            ], 500);
        }
        
        return response()->json([
            'status' => $payment->status,
            'message' => $this->getStatusMessage($payment->status)
        ]);
    }

    public function success($external_id)
    {
        $payment = Payment::where('external_id', $external_id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        // Check and update payment status
        $this->updatePaymentStatus($payment);

        return view('manajemen.keuangan.topup-success', compact('payment'));
    }

    public function failed($external_id)
    {
        $payment = Payment::where('external_id', $external_id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return view('manajemen.keuangan.topup-failed', compact('payment'));
    }

    public function cancel($external_id)
    {
        $payment = Payment::where('external_id', $external_id)
                         ->where('user_id', Auth::id())
                         ->where('status', 'pending')
                         ->firstOrFail();

        $payment->update(['status' => 'cancelled']);

        return redirect()->route('manajemen.topUp')->with('success', 'Pembayaran telah dibatalkan.');
    }

    private function updatePaymentStatus(Payment $payment)
    {
        try {
            $result = $this->apiInstance->getInvoices(null, $payment->external_id);
            
            if (!empty($result)) {
                $xenditStatus = strtolower($result[0]['status']);
                $previousStatus = $payment->status;
                
                // Log the full Xendit response to see available fields
                Log::info('Xendit Invoice Response', [
                    'external_id' => $payment->external_id,
                    'response' => $result[0]
                ]);
                
                // Extract payment method if available
                $paymentMethod = null;
                
                // Check various possible fields for payment method information
                if (isset($result[0]['payment_method'])) {
                    $paymentMethod = $result[0]['payment_method'];
                } elseif (isset($result[0]['payment_channel'])) {
                    $paymentMethod = $result[0]['payment_channel'];
                } elseif (isset($result[0]['payment_destination'])) {
                    $paymentMethod = $result[0]['payment_destination'];
                } elseif (isset($result[0]['bank_code'])) {
                    $paymentMethod = $result[0]['bank_code'];
                } elseif (isset($result[0]['payment_details']['payment_method'])) {
                    $paymentMethod = $result[0]['payment_details']['payment_method'];
                }
                
                // Update payment status and method
                $updateData = ['status' => $xenditStatus];
                if ($paymentMethod) {
                    $updateData['method'] = $paymentMethod;
                }
                
                $payment->update($updateData);
                
                // Only increment wallet if status changed from unpaid to paid
                if (($xenditStatus === 'paid' || $xenditStatus === 'settled') && 
                    ($previousStatus !== 'paid' && $previousStatus !== 'settled')) {
                    $user = $payment->user;
                    $user->increment('dompet', $payment->amount);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't throw
            Log::error('Failed to update payment status: ' . $e->getMessage());
        }
    }

    private function getStatusMessage($status)
    {
        return match($status) {
            'pending' => 'Menunggu pembayaran...',
            'paid', 'settled' => 'Pembayaran berhasil!',
            'expired' => 'Pembayaran telah kedaluwarsa',
            'cancelled' => 'Pembayaran dibatalkan',
            default => 'Status tidak dikenal'
        };
    }

    public function cleanupExpiredPayments()
    {
        $expiredPayments = Payment::where('status', 'pending')
                                ->where('created_at', '<', now()->subHours(24))
                                ->get();

        foreach ($expiredPayments as $payment) {
            $payment->update(['status' => 'expired']);
        }

        return response()->json(['message' => 'Expired payments cleaned up']);
    }

}
