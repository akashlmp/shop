@extends('layouts.app')

@section('title', 'Privacy Policy - Lunar Store')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Privacy Policy</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. How we protect and process customer data.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-sm space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">1. Information We Collect</h2>
            <p>
                To fulfill orders, deliver groceries, and manage customer accounts, we collect necessary personal details including your name, delivery address, phone number, email address, and order transaction history.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">2. Payment Security</h2>
            <p>
                We do not store full credit or debit card numbers on our servers. All digital payments are handled by certified payment gateways (such as Razorpay) complying with PCI-DSS Tier 1 requirements. Signatures and webhook events are validated cryptographically on our server.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">3. Cookies and Session Management</h2>
            <p>
                We use secure, HTTP-only session cookies to manage shopping carts, active logins, and temporary checkout state. We do not sell personal browsing behavior or customer lists to third-party advertisers.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">4. Your Rights</h2>
            <p>
                You may review, update, or delete your saved delivery addresses and account details at any time via your Customer Dashboard or by contacting customer support.
            </p>
        </section>

    </div>

</div>
@endsection

