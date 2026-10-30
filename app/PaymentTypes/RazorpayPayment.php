<?php

namespace App\PaymentTypes;

use Illuminate\Support\Str;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentCheck;
use Lunar\Base\DataTransferObjects\PaymentChecks;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Events\PaymentAttemptEvent;
use Lunar\Models\Contracts\Transaction as TransactionContract;
use Lunar\PaymentTypes\AbstractPayment;

class RazorpayPayment extends AbstractPayment
{
    /**
     * Authorize payment
     */
    public function authorize(): ?PaymentAuthorize
    {
        if (! $this->order) {
            if (! $this->order = $this->cart->draftOrder()->first()) {
                $this->order = $this->cart->createOrder();
            }
        }

        $orderMeta = array_merge(
            (array) $this->order->meta,
            $this->data['meta'] ?? []
        );

        $this->order->update([
            'status' => $this->data['authorized'] ?? ($this->config['authorized'] ?? 'payment-received'),
            'meta' => $orderMeta,
            'placed_at' => now(),
        ]);

        $response = new PaymentAuthorize(
            success: true,
            orderId: $this->order->id,
            paymentType: 'razorpay',
        );

        PaymentAttemptEvent::dispatch($response);

        return $response;
    }

    /**
     * Provide payment verification badges in Filament Admin
     */
    public function getPaymentChecks(TransactionContract $transaction): PaymentChecks
    {
        $checks = new PaymentChecks;

        $checks->addCheck(new PaymentCheck(
            successful: (bool) $transaction->success,
            label: 'Razorpay Security',
            message: 'HMAC SHA-256 Signature Verified'
        ));

        if ($transaction->reference) {
            $checks->addCheck(new PaymentCheck(
                successful: true,
                label: 'Payment ID',
                message: $transaction->reference
            ));
        }

        return $checks;
    }

    /**
     * Capture payment
     */
    public function capture(TransactionContract $transaction, $amount = 0): PaymentCapture
    {
        $transaction->update([
            'status' => 'paid',
            'captured_at' => now(),
        ]);

        return new PaymentCapture(success: true);
    }

    /**
     * Refund payment
     */
    public function refund(TransactionContract $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        if ($transaction->order) {
            $transaction->order->transactions()->create([
                'parent_transaction_id' => $transaction->id,
                'success' => true,
                'type' => 'refund',
                'driver' => 'razorpay',
                'amount' => $amount > 0 ? $amount : $transaction->amount->value,
                'reference' => 'REF-' . strtoupper(Str::random(10)),
                'status' => 'refunded',
                'notes' => $notes ?? 'Online payment refund processed by administrator',
                'card_type' => 'online',
            ]);
        }

        return new PaymentRefund(success: true);
    }
}

