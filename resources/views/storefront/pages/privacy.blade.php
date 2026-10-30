@extends('layouts.app')

@section('title', 'Privacy Policy - Nexora Global')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Privacy Policy</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. How Nexora Global protects and processes client data.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">1. Information We Collect</h2>
            <p>
                To process consignments, fulfill electronic hardware orders, generate export invoices, and manage client accounts, we collect essential details including your name, company name, destination shipping address, phone number, email address, and order transaction history.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">2. Payment &amp; Escrow Security</h2>
            <p>
                Nexora Global never stores unencrypted payment credentials on our servers. All digital payments are processed through PCI-DSS Tier 1 certified gateways (including Razorpay) using server-side HMAC SHA256 cryptographic verification and SSL encryption.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">3. Cookies and Session Management</h2>
            <p>
                We use secure, HTTP-only session cookies to manage shopping carts, active logins, and temporary checkout state. We do not sell client data or procurement history to third parties.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">4. Your Rights</h2>
            <p>
                You may review, update, or delete your saved shipping addresses and account details at any time via your Client Account Portal or by contacting trade support.
            </p>
        </section>

    </div>

</div>
@endsection
