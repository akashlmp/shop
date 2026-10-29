<?php

namespace App\Modifiers;

use App\Models\DeliveryArea;
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

        // 1. Standard Delivery (€2.99, free for orders above €35.00)
        $standardCost = $subTotal >= 3500 ? 0 : 299;
        $standardOption = new ShippingOption(
            name: 'Standard Home Delivery',
            description: $subTotal >= 3500
                ? 'FREE Delivery (Qualifies for Free Standard Delivery on orders €35+)'
                : 'Delivered in 1-2 business days safely to your door.',
            identifier: 'standard-delivery',
            price: new Price($standardCost, $currency, 1),
            taxClass: $taxClass,
            collect: false
        );
        ShippingManifest::addOption($standardOption);

        // 2. Express Quick-Commerce Delivery (if postcode is in delivery area)
        $postcode = $cart->shippingAddress?->postcode;
        $matchedArea = null;
        if ($postcode) {
            $matchedArea = DeliveryArea::findByPostcode($postcode);
        }

        if ($matchedArea) {
            $quickCost = (int) $matchedArea->shipping_fee;
            $quickOption = new ShippingOption(
                name: "⚡ Express Quick Delivery ({$matchedArea->delivery_time_minutes} Mins)",
                description: "Hyperlocal rapid dispatch from {$matchedArea->name}. Fresh and chilled pack.",
                identifier: 'express-delivery',
                price: new Price($quickCost, $currency, 1),
                taxClass: $taxClass,
                collect: false
            );
            ShippingManifest::addOption($quickOption);
        } else {
            // General Express option
            $expressOption = new ShippingOption(
                name: '⚡ Express Delivery (Within 2 Hours)',
                description: 'Fast priority packing and temperature-controlled dispatch.',
                identifier: 'express-delivery',
                price: new Price(399, $currency, 1),
                taxClass: $taxClass,
                collect: false
            );
            ShippingManifest::addOption($expressOption);
        }

        // 3. Wholesale Freight Dispatch (for bulk orders)
        $hasBulkItems = $cart->lines->some(function ($line) {
            return $line->quantity >= 5 || (isset($line->meta['is_bulk']) && $line->meta['is_bulk']);
        });

        if ($hasBulkItems || $subTotal >= 5000) {
            $freightCost = $subTotal >= 10000 ? 0 : 499;
            $bulkOption = new ShippingOption(
                name: 'Heavy Freight & Wholesale Dispatch',
                description: $subTotal >= 10000
                    ? 'FREE Bulk Commercial Freight (Orders €100+)'
                    : 'Palletized careful shipping for heavy cartons, sacks & wholesale quantities.',
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
