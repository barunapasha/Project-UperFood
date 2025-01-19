<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($order)
    {
        try {
            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ),
                'customer_details' => array(
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                ),
                'enable_payments' => array('gopay','bank_transfer'),
                'vtweb' => array()
            );

            $snapToken = Snap::getSnapToken($params);

            return [
                'success' => true,
                'snap_token' => $snapToken
            ];

        } catch (Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}