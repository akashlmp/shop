@extends('layouts.app')

@section('title', 'Terms of Trade & Delivery - A&C Electronics')
@section('meta_description', 'Terms of trade, warranty, delivery, and wholesale consignment policies for A&C Electronics Ltd.')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-slate-950">Terms of Trade &amp; Delivery</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. Guidelines governing retail sales, wholesale master cartons, and logistics.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed font-light">
        
        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">1. Certified Electronic Hardware</h2>
            <p>
                Every electronic unit distributed by A&amp;C Electronics is directly sourced from licensed OEM manufacturers for Lenovo and AOC. All products are tested to meet CE, RoHS, and UKCA safety directives and are supplied with factory specifications.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">2. Master Carton Wholesale Allocations</h2>
            <p>
                Wholesale volume discounts apply on orders of 5+ units and full 50-unit master cartons. B2B wholesale consignments are packaged in double-wall export corrugated boxes and dispatched with commercial packing slips and VAT invoices.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">3. 12-Month UK Replacement Warranty</h2>
            <p>
                All electronic devices feature a standard 12-month UK replacement warranty against manufacturing defects. In the unlikely event of hardware failure, our London RMA hub processes replacements or repairs upon return verification.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">4. Dispatch, Courier &amp; Pallet Freight</h2>
            <p>
                Retail and small carton orders ship via tracked UK express couriers within 24 to 48 hours. Pallet and container consignments are scheduled via our logistics desk with live tracking and bill of lading documentation.
            </p>
        </section>

    </div>

</div>
@endsection
