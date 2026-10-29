@extends('layouts.app')

@section('title', 'Terms of Service - Lunar Store')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Terms of Service</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. Legal and operational guidelines governing purchases.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">1. Acceptance of Terms</h2>
            <p>
                By accessing this storefront, placing orders, or using our retail, bulk buy, or quick shop services, you agree to comply with and be bound by these Terms of Service.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">2. Pricing, Inventory and Accuracy</h2>
            <p>
                All prices are represented in minor monetary units and displayed in EUR (€) by default. Prices submitted by clients or browsers are never trusted; totals, bulk volume tier discounts, taxes, and shipping fees are calculated and verified exclusively on the server.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">3. Quick-Commerce &amp; Delivery SLAs</h2>
            <p>
                Estimated 15-minute quick delivery times are target operational windows dependent on local road traffic, weather conditions, and service area boundary verification by postal code.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">4. Wholesale &amp; Bulk Orders</h2>
            <p>
                Wholesale bulk products require strict minimum purchase quantities as configured in the catalog. Volume discounts apply only when the verified cart line meets or exceeds the specified tier quantity.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">5. Refunds and Doorstep Cancellations</h2>
            <p>
                Cash on Delivery (COD) orders can be verified upon delivery. In the event of damaged or defective items, refunds are processed through the original payment method or credited to your account.
            </p>
        </section>

    </div>

</div>
@endsection
