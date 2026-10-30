<?php

namespace App\Modifiers;

use Closure;
use Lunar\Base\ShippingModifier as BaseShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class ShippingModifier extends BaseShippingModifier
{
    public function handle(Cart $cart, Closure $next)
    {
        $currency = $cart->currency ?? Currency::getDefault();
        if (! $currency) {
            $currency = Currency::where('default', true)->first() ?? Currency::first();
        }

        $taxClass = TaxClass::getDefault() ?? TaxClass::first();
        if (! $taxClass) {
            return $next($cart);
        }

        // Subtotal in minor units (cents)
        $subTotal = $cart->subTotal?->value ?? 0;
        if (! $subTotal && $cart->lines()->count()) {
            $subTotal = $cart->lines->sum(function ($line) {
                return $line->subTotal?->value ?? ($line->unitPrice?->value ?? 0) * $line->quantity;
            });
        }

        // 1. Standard Tracked Parcel Delivery (€4.99, free for orders above €50.00)
        $standardCost = $subTotal >= 5000 ? 0 : 499;
        $standardOption = new ShippingOption(
            name: 'Standard Tracked Courier',
            description: $subTotal >= 5000
                ? 'FREE Delivery (Qualifies for Free Tracked Delivery on orders €50+)'
                : 'Delivered in 2-4 business days with full online tracking.',
            identifier: 'standard-delivery',
            price: new Price($standardCost, $currency, 1),
            taxClass: $taxClass,
            collect: false
        );
        ShippingManifest::addOption($standardOption);

        // 2. Priority Air Express Delivery (1-2 business days)
        $expressOption = new ShippingOption(
            name: 'Priority Air Express (1-2 Days)',
            description: 'Fast priority air dispatch with transit insurance and signature upon delivery.',
            identifier: 'express-delivery',
            price: new Price(999, $currency, 1),
            taxClass: $taxClass,
            collect: false
        );
        ShippingManifest::addOption($expressOption);

        // 3. B2B Commercial Freight & Master Carton Dispatch (for bulk orders)
        $hasBulkItems = $cart->lines->some(function ($line) {
            return $line->quantity >= 5 || (isset($line->meta['is_bulk']) && $line->meta['is_bulk']);
        });

        if ($hasBulkItems || $subTotal >= 10000) {
            $freightCost = $subTotal >= 25000 ? 0 : 1999;
            $bulkOption = new ShippingOption(
                name: 'B2B Cargo & Pallet Freight',
                description: $subTotal >= 25000
                    ? 'FREE B2B Freight (Orders over €250+)'
                    : 'Reinforced palletized dispatch for carton quantities, commercial invoices & export packing.',
                identifier: 'bulk-freight',
                price: new Price($freightCost, $currency, 1),
                taxClass: $taxClass,
                collect: false
            );
            ShippingManifest::addOption($bulkOption);
        }

        return $next($cart);
    }
}
