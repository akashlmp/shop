@extends('layouts.app')

@section('title', 'Privacy & Data Protection - A&C Electronics')
@section('meta_description', 'Privacy and data protection policy for A&C Electronics Ltd. How trade and retail data is securely processed.')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-slate-950">Privacy &amp; Data Protection Policy</h1>
        <p class="text-xs text-slate-500 mt-1">Last updated: {{ date('F Y') }}. How A&amp;C Electronics Ltd protects trade and consumer information.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs space-y-6 text-xs sm:text-sm text-slate-600 leading-relaxed font-light">
        
        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">1. Information We Collect</h2>
            <p>
                When you place a retail hardware order, register a B2B wholesale trade account, or submit master carton delivery instructions, we collect necessary commercial details such as your company or personal name, delivery address, corporate registration / VAT details (where applicable), contact telephone, and email.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">2. Commercial Payment &amp; Escrow Security</h2>
            <p>
                All digital transactions are encrypted via industry-standard, PCI-DSS Level 1 compliant payment gateways (including Visa, Mastercard, AMEX, Razorpay, and bank wire transfers). We never store raw debit/credit card credentials on our servers.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">3. Trade Inquiries &amp; Quotations</h2>
            <p>
                Custom master carton wholesale quotations, pricing tiers, and OEM volume agreements remain strictly confidential between A&amp;C Electronics and the registered commercial account holder.
            </p>
        </section>

        <section class="space-y-2">
            <h2 class="font-display text-base font-bold text-slate-900">4. Contacting Our Data Controller</h2>
            <p>
                For GDPR compliance inquiries, data subject access requests, or account deletion, please contact our data officer directly at <strong class="text-slate-900">trade@ac-electronics.co.uk</strong>.
            </p>
        </section>

    </div>

</div>
@endsection
