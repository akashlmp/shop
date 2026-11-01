@extends('layouts.app')

@section('title', 'Complete Payment - Order #' . $order->reference . ' - A&C Electronics')
@section('meta_description', 'Complete secure online payment for your A&C Electronics hardware order.')

@section('content')
<div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8 py-14 space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs text-center space-y-6">
        
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-700">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
            </svg>
        </div>

        <div>
            <span class="rounded-md bg-slate-100 text-slate-800 text-[10px] font-bold uppercase tracking-wider px-3 py-1">
                Secure Trade Gateway
            </span>
            <h1 class="font-display text-2xl font-bold text-slate-900 mt-2">
                Order Payment #{{ $order->reference }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Amount Payable: <strong class="font-display text-blue-600 text-lg">£{{ number_format($order->total->value / 100, 2) }}</strong>
            </p>
        </div>

        <!-- Verification Form -->
        <form id="razorpay-form" action="{{ route('checkout.razorpay.verify') }}" method="POST">
            @csrf
            <input type="hidden" name="order_reference" value="{{ $order->reference }}">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $razorpayData['order_id'] }}">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="">

            <div class="space-y-3 pt-2">
                <button type="button" onclick="simulateSandboxPayment()" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Authorize &amp; Complete Payment</span>
                </button>

                <a href="{{ route('checkout.index') }}" class="block text-xs font-medium text-slate-400 hover:text-slate-800 transition pt-1">
                    Cancel &amp; Return to Checkout
                </a>
            </div>
        </form>

    </div>

</div>

@push('scripts')
<script>
    function simulateSandboxPayment() {
        const paymentId = 'pay_sbx_' + Math.random().toString(36).substring(2, 12);
        document.getElementById('razorpay_payment_id').value = paymentId;
        document.getElementById('razorpay_signature').value = 'sandbox_sig_' + Math.random().toString(36).substring(2, 14);
        document.getElementById('razorpay-form').submit();
    }
</script>
@endpush
@endsection
