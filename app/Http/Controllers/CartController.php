<?php

namespace App\Http\Controllers;

use App\Models\DeliveryArea;
use App\Services\CartManager;
use Exception;
use Illuminate\Http\Request;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    public function __construct(
        protected CartManager $cartManager
    ) {}

    /**
     * Display cart page
     */
    public function index()
    {
        $summary = $this->cartManager->getSummary();
        $deliveryAreas = DeliveryArea::where('is_active', true)->get();

        return view('storefront.cart.index', compact('summary', 'deliveryAreas'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:lunar_product_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'is_bulk' => 'nullable|boolean',
        ]);

        $variant = ProductVariant::findOrFail($request->input('variant_id'));
        $quantity = (int) $request->input('quantity', 1);
        $isBulk = (bool) $request->input('is_bulk', false);

        try {
            $this->cartManager->add($variant, $quantity, ['is_bulk' => $isBulk]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'summary' => $this->cartManager->getSummary(),
                ]);
            }

            return back()->with('success', 'Item added to cart successfully!');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'line_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $lineId = (int) $request->input('line_id');
            $quantity = (int) $request->input('quantity');

            $this->cartManager->update($lineId, $quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated.',
                    'summary' => $this->cartManager->getSummary(),
                ]);
            }

            return back()->with('success', 'Cart updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'line_id' => 'required|integer',
        ]);

        try {
            $this->cartManager->remove((int) $request->input('line_id'));

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'summary' => $this->cartManager->getSummary(),
                ]);
            }

            return back()->with('success', 'Item removed from cart.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clear all cart items
     */
    public function clear(Request $request)
    {
        $this->cartManager->clear();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
                'summary' => $this->cartManager->getSummary(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $result = $this->cartManager->applyCoupon($request->input('coupon_code'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'summary' => $this->cartManager->getSummary(),
            ], $result['success'] ? 200 : 422);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(Request $request)
    {
        $this->cartManager->removeCoupon();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'summary' => $this->cartManager->getSummary(),
            ]);
        }

        return back()->with('success', 'Coupon removed.');
    }

    /**
     * Check delivery serviceability by postcode
     */
    public function checkServiceability(Request $request)
    {
        $request->validate([
            'postcode' => 'required|string|max:20',
        ]);

        $postcode = $request->input('postcode');
        $area = DeliveryArea::findByPostcode($postcode);

        if ($area) {
            session(['quick_postcode' => $area->postcode]);

            return response()->json([
                'serviceable' => true,
                'area_name' => $area->name,
                'delivery_time' => $area->delivery_time_minutes,
                'shipping_fee' => number_format($area->shipping_fee / 100, 2),
                'message' => "Great news! Express delivery is available in {$area->name} in ~{$area->delivery_time_minutes} minutes.",
            ]);
        }

        return response()->json([
            'serviceable' => false,
            'message' => "We do not offer 15-minute quick delivery to '{$postcode}' yet, but Standard delivery (1-2 days) is available!",
        ]);
    }
}
