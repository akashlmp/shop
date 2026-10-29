<?php

namespace App\Services;

use App\Models\DeliveryArea;
use App\Models\ProductMetadata;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\Channel;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\Discount;
use Lunar\Models\Order;
use Lunar\Models\ProductVariant;

class CartManager
{
    /**
     * Get or initialize the active cart session
     */
    public function getCart(bool $calculate = true): Cart
    {
        $cart = CartSession::current(estimateShipping: true, calculate: false);

        if (! $cart) {
            $channel = Channel::whereDefault(true)->first() ?? Channel::first();
            $currency = Currency::whereDefault(true)->first() ?? Currency::first();

            $cart = Cart::create([
                'channel_id' => $channel->id,
                'currency_id' => $currency->id,
                'user_id' => Auth::id(),
            ]);

            CartSession::use($cart);
        } elseif (Auth::check() && ! $cart->user_id) {
            $cart->update(['user_id' => Auth::id()]);
        }

        if ($calculate) {
            $cart->calculate(force: true);
        }

        return $cart;
    }

    /**
     * Add product variant to cart with stock and bulk validation
     */
    public function add(ProductVariant $variant, int $quantity = 1, array $meta = []): Cart
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be at least 1.');
        }

        // Validate stock
        if ($variant->purchasable === 'in_stock' && $variant->stock < $quantity) {
            throw new Exception("Only {$variant->stock} units are currently available in stock.");
        }

        // Validate bulk minimum quantity if bulk mode
        $metadata = ProductMetadata::where('product_id', $variant->product_id)->first();
        if (($meta['is_bulk'] ?? false) && $metadata && $metadata->bulk_min_quantity > 1) {
            if ($quantity < $metadata->bulk_min_quantity) {
                throw new Exception("Wholesale bulk purchase requires a minimum order of {$metadata->bulk_min_quantity} units.");
            }
        }

        $cart = $this->getCart(calculate: false);

        // Check if item already in cart and if total quantity exceeds stock
        $existingLine = $cart->lines->firstWhere('purchasable_id', $variant->id);
        if ($existingLine && $variant->purchasable === 'in_stock') {
            $newTotalQty = $existingLine->quantity + $quantity;
            if ($newTotalQty > $variant->stock) {
                throw new Exception("Cannot add {$quantity} more. Total in cart ({$newTotalQty}) would exceed available stock of {$variant->stock}.");
            }
        }

        $cart->add(
            purchasable: $variant,
            quantity: $quantity,
            meta: $meta,
            refresh: true
        );

        return $cart->calculate();
    }

    /**
     * Update cart line quantity with validation
     */
    public function update(int $cartLineId, int $quantity): Cart
    {
        $cart = $this->getCart(calculate: false);
        $line = $cart->lines->firstWhere('id', $cartLineId);

        if (! $line) {
            throw new Exception('Cart item not found.');
        }

        if ($quantity <= 0) {
            return $this->remove($cartLineId);
        }

        $variant = $line->purchasable;
        if ($variant && $variant->purchasable === 'in_stock' && $quantity > $variant->stock) {
            throw new Exception("Only {$variant->stock} units are currently available.");
        }

        $cart->updateLine($cartLineId, $quantity, refresh: true);

        return $cart->calculate();
    }

    /**
     * Remove item from cart
     */
    public function remove(int $cartLineId): Cart
    {
        $cart = $this->getCart(calculate: false);
        $cart->remove($cartLineId, refresh: true);

        return $cart->calculate();
    }

    /**
     * Clear all items
     */
    public function clear(): Cart
    {
        $cart = $this->getCart(calculate: false);
        $cart->clear();

        return $cart->calculate();
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(string $code): array
    {
        $cleanCode = strtoupper(trim($code));
        if (empty($cleanCode)) {
            return ['success' => false, 'message' => 'Please enter a coupon code.'];
        }

        $discount = Discount::where('coupon', $cleanCode)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (! $discount) {
            return ['success' => false, 'message' => "Coupon '{$cleanCode}' is invalid or expired."];
        }

        $cart = $this->getCart(calculate: false);
        $cart->coupon_code = $cleanCode;
        $cart->save();
        \Lunar\Facades\Discounts::resetDiscounts();
        $cart->calculate(force: true);

        // Check if any discount was applied
        if (! $cart->discountTotal || $cart->discountTotal->value <= 0) {
            $cart->coupon_code = null;
            $cart->save();
            \Lunar\Facades\Discounts::resetDiscounts();
            $cart->calculate(force: true);

            return ['success' => false, 'message' => "Coupon '{$cleanCode}' conditions were not met for your cart."];
        }

        return ['success' => true, 'message' => "Coupon '{$cleanCode}' applied successfully!"];
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(): Cart
    {
        $cart = $this->getCart(calculate: false);
        $cart->coupon_code = null;
        $cart->save();
        \Lunar\Facades\Discounts::resetDiscounts();

        return $cart->calculate(force: true);
    }

    /**
     * Save shipping and billing address to the cart
     */
    public function setAddresses(array $shippingData, ?array $billingData = null): Cart
    {
        $cart = $this->getCart(calculate: false);

        $countryId = $shippingData['country_id'] ?? null;
        if (! $countryId) {
            $country = Country::where('iso2', 'GB')->orWhere('iso2', 'IN')->orWhere('iso2', 'US')->first() ?? Country::first();
            $countryId = $country?->id;
        }

        $shippingAddressData = [
            'country_id' => $countryId,
            'first_name' => $shippingData['first_name'] ?? '',
            'last_name' => $shippingData['last_name'] ?? '',
            'company_name' => $shippingData['company_name'] ?? null,
            'line_one' => $shippingData['line_one'] ?? '',
            'line_two' => $shippingData['line_two'] ?? null,
            'city' => $shippingData['city'] ?? '',
            'state' => $shippingData['state'] ?? '',
            'postcode' => $shippingData['postcode'] ?? '',
            'contact_email' => $shippingData['contact_email'] ?? '',
            'contact_phone' => $shippingData['contact_phone'] ?? '',
            'delivery_instructions' => $shippingData['delivery_instructions'] ?? null,
        ];

        $cart->setShippingAddress($shippingAddressData);

        $billingAddressData = $billingData ? [
            'country_id' => $billingData['country_id'] ?? $countryId,
            'first_name' => $billingData['first_name'] ?? $shippingAddressData['first_name'],
            'last_name' => $billingData['last_name'] ?? $shippingAddressData['last_name'],
            'company_name' => $billingData['company_name'] ?? null,
            'line_one' => $billingData['line_one'] ?? $shippingAddressData['line_one'],
            'line_two' => $billingData['line_two'] ?? null,
            'city' => $billingData['city'] ?? $shippingAddressData['city'],
            'state' => $billingData['state'] ?? $shippingAddressData['state'],
            'postcode' => $billingData['postcode'] ?? $shippingAddressData['postcode'],
            'contact_email' => $billingData['contact_email'] ?? $shippingAddressData['contact_email'],
            'contact_phone' => $billingData['contact_phone'] ?? $shippingAddressData['contact_phone'],
        ] : $shippingAddressData;

        $cart->setBillingAddress($billingAddressData);

        return $cart->calculate(force: true);
    }

    /**
     * Set shipping method option on cart
     */
    public function setShippingOption(string $identifier): Cart
    {
        $cart = $this->getCart(calculate: true);
        $shippingOption = ShippingManifest::getOption($cart, $identifier);

        if (! $shippingOption) {
            // Pick default available
            $shippingOption = ShippingManifest::getOptions($cart)->first();
        }

        if ($shippingOption) {
            $cart->setShippingOption($shippingOption);
        }

        return $cart->calculate(force: true);
    }

    /**
     * Create order from active cart safely within database transaction
     */
    public function createOrder(): Order
    {
        $cart = $this->getCart(calculate: true);

        if (! $cart->lines()->count()) {
            throw new Exception('Your cart is empty.');
        }

        // Validate stock before placing order
        foreach ($cart->lines as $line) {
            $variant = $line->purchasable;
            if ($variant && $variant->purchasable === 'in_stock' && $variant->stock < $line->quantity) {
                throw new Exception("Product '{$line->description}' only has {$variant->stock} units left in stock.");
            }
        }

        // Check if a draft order already exists for this exact cart fingerprint
        $existingOrder = $cart->currentDraftOrder();
        if ($existingOrder) {
            return $existingOrder;
        }

        return DB::transaction(function () use ($cart) {
            $order = $cart->createOrder();

            // Deduct inventory
            foreach ($cart->lines as $line) {
                $variant = $line->purchasable;
                if ($variant && $variant->purchasable === 'in_stock') {
                    $variant->decrement('stock', $line->quantity);
                }
            }

            return $order;
        });
    }

    /**
     * Return structured summary for checkout / UI
     */
    public function getSummary(): array
    {
        $cart = $this->getCart(calculate: true);

        $currency = $cart->currency;
        $currencyCode = $currency?->code ?? 'EUR';
        $currencySymbol = match ($currencyCode) {
            'EUR' => '€',
            'USD' => '$',
            'INR' => '₹',
            'GBP' => '£',
            default => $currencyCode . ' ',
        };

        $lines = $cart->lines->map(function ($line) use ($currencySymbol) {
            $variant = $line->purchasable;
            $product = $variant?->product;
            $metadata = $product ? ProductMetadata::where('product_id', $product->id)->first() : null;

            return [
                'id' => $line->id,
                'purchasable_id' => $line->purchasable_id,
                'product_id' => $product?->id,
                'name' => $line->description ?? $product?->translateAttribute('name') ?? 'Item',
                'sku' => $variant?->sku,
                'quantity' => $line->quantity,
                'unit_price' => $line->unitPrice?->value ?? 0,
                'unit_price_formatted' => $currencySymbol . number_format(($line->unitPrice?->value ?? 0) / 100, 2),
                'sub_total' => $line->subTotal?->value ?? 0,
                'sub_total_formatted' => $currencySymbol . number_format(($line->subTotal?->value ?? 0) / 100, 2),
                'total' => $line->total?->value ?? 0,
                'total_formatted' => $currencySymbol . number_format(($line->total?->value ?? 0) / 100, 2),
                'image_url' => $metadata?->image_url ?? $product?->primary_image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80',
                'unit_of_measure' => $metadata?->unit_of_measure ?? 'piece',
                'is_bulk' => $line->meta['is_bulk'] ?? false,
                'stock' => $variant?->stock ?? 0,
            ];
        });

        $subTotal = $cart->subTotal?->value ?? 0;
        $discountTotal = $cart->discountTotal?->value ?? 0;
        $shippingTotal = $cart->shippingTotal?->value ?? 0;
        $taxTotal = $cart->taxTotal?->value ?? 0;
        $total = $cart->total?->value ?? 0;

        return [
            'cart_id' => $cart->id,
            'total_items' => $cart->lines->sum('quantity'),
            'lines' => $lines,
            'sub_total' => $subTotal,
            'sub_total_formatted' => $currencySymbol . number_format($subTotal / 100, 2),
            'discount_total' => $discountTotal,
            'discount_total_formatted' => $currencySymbol . number_format($discountTotal / 100, 2),
            'shipping_total' => $shippingTotal,
            'shipping_total_formatted' => $currencySymbol . number_format($shippingTotal / 100, 2),
            'tax_total' => $taxTotal,
            'tax_total_formatted' => $currencySymbol . number_format($taxTotal / 100, 2),
            'total' => $total,
            'total_formatted' => $currencySymbol . number_format($total / 100, 2),
            'coupon_code' => $cart->coupon_code,
            'currency_code' => $currencyCode,
            'currency_symbol' => $currencySymbol,
            'shipping_option' => $cart->shippingAddress?->shipping_option,
        ];
    }
}
