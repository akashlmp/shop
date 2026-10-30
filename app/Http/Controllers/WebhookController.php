<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function razorpay(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Razorpay-Signature');

        $result = $this->paymentService->handleWebhook($payload, $signature);

        if ($result) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ignored or invalid signature'], 400);
    }
}

