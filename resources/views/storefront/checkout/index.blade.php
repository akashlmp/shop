@extends('layouts.app')

@section('title', 'Secure Checkout - A&C Electronics')
@section('meta_description', 'Complete your order with A&C Electronics. Verified OEM hardware, tracked UK shipping, and secure payment.')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="border-b border-slate-200 pb-4">
        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-950">Secure Trade &amp; Retail Checkout</h1>
        <p class="text-xs text-slate-500 mt-1">Please provide consignee shipping address and select your preferred payment method</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Form: Contact, Address, Shipping, Payment -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Customer Contact Details -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px]">1</span>
                        <span>Consignee / Contact Information</span>
                    </h2>

                    @php
                        $user = Auth::user();
                        $savedCustomer = $user?->latestCustomer();
                        $nameParts = explode(' ', $user?->name ?? '', 2);
                        $defaultFirst = old('first_name', $savedCustomer?->first_name ?? $nameParts[0] ?? '');
                        $defaultLast = old('last_name', $savedCustomer?->last_name ?? $nameParts[1] ?? '');
                        $defaultEmail = old('contact_email', $user?->email ?? '');
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" required value="{{ $defaultFirst }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('first_name') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" required value="{{ $defaultLast }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('last_name') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address (for tracking &amp; invoices) *</label>
                            <input type="email" name="contact_email" required value="{{ $defaultEmail }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('contact_email') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contact Phone (for courier dispatch) *</label>
                            <input type="tel" name="contact_phone" required value="{{ old('contact_phone', '+44 7700 900077') }}" placeholder="+44 or international..." class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('contact_phone') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery Address -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px]">2</span>
                        <span>Delivery Destination</span>
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Street Address Line 1 *</label>
                        <input type="text" name="line_one" required value="{{ old('line_one', '10 Downing Street') }}" placeholder="Building, unit or street name" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                        @error('line_one') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Warehouse Bay / Suite / Floor (optional)</label>
                        <input type="text" name="line_two" value="{{ old('line_two') }}" placeholder="Suite / Warehouse Bay / Department" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">City / Town *</label>
                            <input type="text" name="city" required value="{{ old('city', 'London') }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('city') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">County / State / Region *</label>
                            <input type="text" name="state" required value="{{ old('state', 'Greater London') }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('state') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Postcode / ZIP *</label>
                            <input type="text" name="postcode" required value="{{ old('postcode', 'SW1A 1AA') }}" class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @error('postcode') <span class="text-[11px] font-semibold text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Country / Territory *</label>
                        <select name="country_id" required class="w-full rounded-full border border-slate-300 py-2.5 px-4 text-xs font-medium text-slate-900 focus:border-blue-600 focus:outline-none">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ ($defaultCountry && $country->id === $defaultCountry->id) ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->iso2 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Special Consignment / Logistics Instructions (optional)</label>
                        <textarea name="delivery_instructions" rows="2" placeholder="e.g. Forklift unloading available at gate 2, or leave at reception desk" class="w-full rounded-2xl border border-slate-300 py-2.5 px-4 text-xs text-slate-900 focus:border-blue-600 focus:outline-none"></textarea>
                    </div>
                </div>

                <!-- 3. Shipping Options -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px]">3</span>
                        <span>Dispatch &amp; Freight Service</span>
                    </h2>

                    <div class="space-y-3">
                        @foreach ($shippingOptions as $index => $opt)
                            @php
                                $optPriceVal = $opt->price->value;
                                $isFree = $optPriceVal === 0;
                            @endphp
                            <label class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 hover:border-blue-400 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_option" value="{{ $opt->getIdentifier() }}" {{ $index === 0 ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ $opt->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $opt->description }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-xs font-bold text-slate-900">
                                    {{ $isFree ? 'FREE' : '£' . number_format($optPriceVal / 100, 2) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Payment Method -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px]">4</span>
                        <span>Payment Method</span>
                    </h2>

                    <div class="space-y-3" x-data="{ paymentType: 'cod' }">
                        
                        <!-- Cash / Wire on Delivery -->
                        <label class="flex items-start justify-between rounded-2xl border-2 p-4 cursor-pointer transition" :class="paymentType === 'cod' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 bg-white'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentType" class="mt-0.5 h-4 w-4 text-blue-600 focus:ring-blue-600">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">💵 Cash / Bank Wire on Delivery</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Pay upon tracked delivery or bank wire verification.</div>
                                </div>
                            </div>
                            <span class="rounded-full bg-slate-200 text-slate-800 text-[10px] font-bold px-2 py-0.5">Zero Fee</span>
                        </label>

                        <!-- Razorpay Online Payment -->
                        <label class="flex items-start justify-between rounded-2xl border-2 p-4 cursor-pointer transition" :class="paymentType === 'razorpay' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 bg-white'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="razorpay" x-model="paymentType" class="mt-0.5 h-4 w-4 text-blue-600 focus:ring-blue-600">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">💳 Online Card / Razorpay (Visa, Mastercard, AMEX, Apple Pay)</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Secure, 256-bit SSL encrypted checkout with instant dispatch reservation.</div>
                                </div>
                            </div>
                            <span class="rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold px-2 py-0.5">Instant</span>
                        </label>

                    </div>
                </div>

            </div>

            <!-- Right Summary Sticky Column -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xs space-y-4">
                    <h3 class="font-display text-base font-bold text-slate-900 pb-3 border-b border-slate-100">
                        Hardware Items ({{ $summary['total_items'] }})
                    </h3>

                    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1">
                        @foreach ($summary['lines'] as $line)
                            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $line['image_url'] }}" alt="{{ $line['name'] }}" class="h-12 w-12 rounded-xl object-cover bg-slate-50 border border-slate-200">
                                    <div>
                                        <div class="font-bold text-slate-900 line-clamp-1">{{ $line['name'] }}</div>
                                        <div class="text-[11px] text-slate-500">Qty: {{ $line['quantity'] }} &bull; {{ $line['unit_price_formatted'] }}</div>
                                    </div>
                                </div>
                                <div class="font-display font-bold text-slate-900 shrink-0">
                                    {{ $line['sub_total_formatted'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-100 pt-3 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900">{{ $summary['sub_total_formatted'] }}</span>
                        </div>
                        @if ($summary['discount_total'] > 0)
                            <div class="flex justify-between text-blue-700 font-semibold">
                                <span>Discount ({{ $summary['coupon_code'] }})</span>
                                <span>-{{ $summary['discount_total_formatted'] }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping</span>
                            <span class="text-emerald-600 font-bold">{{ $summary['shipping_total'] > 0 ? $summary['shipping_total_formatted'] : 'FREE' }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400 text-[11px]">
                            <span>Warranty Registration</span>
                            <span class="text-blue-600 font-semibold">INCLUDED</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                            <span class="font-display text-base font-bold text-slate-900">Total Payable</span>
                            <span class="font-display text-2xl font-black text-blue-600">{{ $summary['total_formatted'] }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-white shadow transition active:scale-95 flex items-center justify-center gap-2">
                        <span>Confirm &amp; Place Order</span>
                        <span>&rarr;</span>
                    </button>

                    <p class="text-[11px] text-center text-slate-400">
                        🔒 Safe &amp; Encrypted 256-bit SSL Checkout &bull; A&amp;C Electronics Ltd
                    </p>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection
