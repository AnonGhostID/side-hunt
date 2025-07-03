<?php

namespace App\Services;

use App\Models\Payout;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Payout\PayoutApi;

class TarikSaldoService
{
    protected $payoutApi;

    public function __construct()
    {
        // Use the same Xendit key as TopUpController
        Configuration::setXenditKey('xnd_development_7Qgujm27QHHqpc15olW28d1yBzncI1f1KLHSGNMwGeRug2K6doSB426KYqvgEa');
        $this->payoutApi = new PayoutApi();
    }

    /**
     * Create disbursement through Xendit
     */
    public function createDisbursement(Payout $payout): array
    {
        try {
            // For now, let's simulate a successful disbursement for testing
            // In production, you would use the actual Xendit Payout API
            
            // Simulate API call delay
            usleep(500000); // 0.5 second delay
            
            // Generate a fake disbursement ID for testing
            $disbursementId = 'disb_' . time() . '_' . rand(1000, 9999);
            
            Log::info('Xendit disbursement simulated (test mode)', [
                'payout_id' => $payout->id,
                'simulated_disbursement_id' => $disbursementId,
                'amount' => $payout->amount,
                'bank_code' => $payout->bank_code,
                'account_number' => $payout->account_number
            ]);

            return [
                'success' => true,
                'disbursement_id' => $disbursementId,
                'status' => 'completed'
            ];

            // TODO: Uncomment this when ready for production
            /*
            $createPayoutRequest = new \Xendit\Payout\CreatePayoutRequest([
                'reference_id' => $payout->xendit_reference_id,
                'channel_code' => $this->getBankChannelCode($payout->bank_code),
                'channel_properties' => [
                    'account_holder_name' => $payout->account_name,
                    'account_number' => $payout->account_number,
                ],
                'amount' => $payout->amount,
                'description' => 'Penarikan Saldo - SideHunt',
                'currency' => 'IDR'
            ]);

            $result = $this->payoutApi->createPayout($createPayoutRequest);

            Log::info('Xendit disbursement created', [
                'payout_id' => $payout->id,
                'xendit_response' => $result
            ]);

            return [
                'success' => true,
                'disbursement_id' => $result['id'],
                'status' => $result['status']
            ];
            */

        } catch (\Exception $e) {
            Log::error('Xendit disbursement failed', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $this->getErrorMessage($e->getMessage())
            ];
        }
    }

    /**
     * Get bank channel code for Xendit
     */
    private function getBankChannelCode(string $bankCode): string
    {
        $bankMapping = [
            'BCA' => 'ID_BCA',
            'BNI' => 'ID_BNI',
            'BRI' => 'ID_BRI',
            'MANDIRI' => 'ID_MANDIRI',
            'CIMB' => 'ID_CIMB',
            'DANAMON' => 'ID_DANAMON',
            'PERMATA' => 'ID_PERMATA',
            'MAYBANK' => 'ID_MAYBANK',
            'PANIN' => 'ID_PANIN',
            'BSI' => 'ID_BSI',
            'MUAMALAT' => 'ID_MUAMALAT',
        ];

        return $bankMapping[$bankCode] ?? 'ID_BCA'; // Default to BCA if not found
    }

    /**
     * Get user-friendly error message
     */
    private function getErrorMessage(string $error): string
    {
        if (str_contains($error, 'INVALID_ACCOUNT_NUMBER')) {
            return 'Nomor rekening tidak valid';
        }
        
        if (str_contains($error, 'INSUFFICIENT_BALANCE')) {
            return 'Saldo tidak mencukupi di sistem pembayaran';
        }
        
        if (str_contains($error, 'DUPLICATE_REFERENCE_ID')) {
            return 'Transaksi duplikat terdeteksi';
        }

        if (str_contains($error, 'INVALID_CHANNEL')) {
            return 'Bank tujuan tidak didukung';
        }

        return 'Terjadi kesalahan pada sistem pembayaran. Silakan coba lagi nanti.';
    }

    /**
     * Get supported banks list
     */
    public static function getSupportedBanks(): array
    {
        return [
            'BCA' => 'Bank Central Asia (BCA)',
            'BNI' => 'Bank Negara Indonesia (BNI)',
            'BRI' => 'Bank Rakyat Indonesia (BRI)',
            'MANDIRI' => 'Bank Mandiri',
            'CIMB' => 'CIMB Niaga',
            'DANAMON' => 'Bank Danamon',
            'PERMATA' => 'Bank Permata',
            'MAYBANK' => 'Maybank Indonesia',
            'PANIN' => 'Panin Bank',
            'BSI' => 'Bank Syariah Indonesia (BSI)',
            'MUAMALAT' => 'Bank Muamalat',
        ];
    }
}
