@extends('layouts.app')

@section('title', 'Contact UK Trade Desk - A&C Electronics')
@section('meta_description', 'Contact the A&C Electronics UK trade desk. Inquire about master carton wholesale, OEM hardware distribution, container logistics, and specifications.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <div class="text-center max-w-xl mx-auto space-y-2">
        <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold uppercase tracking-wider px-3 py-1">
            UK Trade Desk &bull; Global Operations
        </span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-950">Contact A&amp;C Electronics</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-light">
            Connecting global brands to UK markets. Have questions regarding master carton allocations, container consignments, OEM specifications, or trade terms? Contact our London team.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Contact Form -->
        <div class="lg:col-span-7 rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 shadow-2xs space-y-5">
            <h2 class="font-display text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">
                Submit Trade or Retail Inquiry
            </h2>

            <form action="{{ route('page.contact.submit') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Your Name / Trade Representative *</label>
                        <input type="text" name="name" required class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Corporate or Personal Email *</label>
                        <input type="email" name="email" required class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Inquiry Subject *</label>
                    <select name="subject" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                        <option value="Master Carton Wholesale">B2B Master Carton Wholesale (5-50+ Units)</option>
                        <option value="Container Freight & Pallet">Container Freight &amp; Pallet Logistics</option>
                        <option value="OEM Specifications & Compliance">Lenovo &amp; AOC OEM Technical Specs &amp; CE/RoHS</option>
                        <option value="Order Tracking">Consignment Dispatch &amp; Tracking</option>
                        <option value="General Trade Inquiry">General Trade Inquiry</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Your Message / Order Requirement *</label>
                    <textarea name="message" rows="5" required placeholder="Specify hardware models, estimated volume, delivery requirements, or technical questions..." class="w-full rounded-2xl border border-slate-300 py-2.5 px-4 text-xs text-slate-900 focus:border-blue-600 focus:outline-none"></textarea>
                </div>

                <button type="submit" class="rounded-full bg-blue-600 hover:bg-blue-700 py-3 px-8 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95">
                    Send Inquiry &rarr;
                </button>
            </form>
        </div>

        <!-- Right: Studio Details -->
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-2xs space-y-5 text-xs">
                <h3 class="font-display text-base font-bold text-slate-900 pb-2 border-b border-slate-100">
                    London Trade Operations Desk
                </h3>

                <div class="space-y-4 text-slate-600">
                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">Direct Trade Telephone:</span>
                        <a href="tel:+442079460991" class="text-blue-600 font-bold hover:underline">+44 20 7946 0991</a>
                        <p class="text-[11px] text-slate-400 mt-0.5">Monday &ndash; Friday: 08:30 &ndash; 17:30 GMT</p>
                    </div>

                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">Corporate &amp; Trade Email:</span>
                        <a href="mailto:trade@ac-electronics.co.uk" class="text-blue-600 font-bold hover:underline">trade@ac-electronics.co.uk</a>
                        <p class="text-[11px] text-slate-400 mt-0.5">Response within 2 business hours for wholesale inquiries.</p>
                    </div>

                    <div>
                        <span class="font-bold text-slate-900 block mb-0.5">London Logistics Gateway:</span>
                        <p class="text-slate-800">A&amp;C Electronics Ltd<br>London Gateway Logistics Park<br>United Kingdom</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-slate-900 text-white border border-slate-800 p-6 space-y-2 text-xs shadow-md">
                <span class="text-blue-400 font-bold text-sm block">Global Trade. Trusted Partnerships.</span>
                <p class="text-slate-300 font-light leading-relaxed">
                    Looking for factory contract manufacturing or customized corporate branding on electronics? Our trade specialists manage custom production batches with full CE, RoHS, and FCC compliance certificates.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
