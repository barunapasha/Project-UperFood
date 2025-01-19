<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function notification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        
        try {
            $notification = new Notification();
            
            $order = Order::where('order_number', $notification->order_id)->firstOrFail();
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if($fraud == 'challenge') {
                        $order->setStatusPending();
                    } else {
                        $order->setStatusSuccess();
                    }
                }
            }
            else if ($transaction == 'settlement') {
                $order->setStatusSuccess();
            }
            else if($transaction == 'pending') {
                $order->setStatusPending();
            }
            else if ($transaction == 'deny') {
                $order->setStatusFailed();
            }
            else if ($transaction == 'expire') {
                $order->setStatusExpired();
            }
            else if ($transaction == 'cancel') {
                $order->setStatusFailed();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Payment Notification Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function complete(Request $request)
    {
        return redirect()->route('home')
            ->with('success', 'Pembayaran berhasil!');
    }

    public function failed(Request $request)
    {
        return redirect()->route('home')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('home')
            ->with('warning', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
    }
}