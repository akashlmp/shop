<?php

namespace App\Http\Controllers;

use App\Models\DeliveryArea;
use App\Services\CartManager;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Country;
use Lunar\Models\Order;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartManager $cartManager,
        protected PaymentService $paymentService
    ) {}

    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = $this->cartManager->getCart(calculate: true);

        if (! $cart->lines()->count()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add products before checking out.');
        }

        $summary = $this->cartManager->getSummary();
        $countries = Country::orderBy('name')->get();
        $shippingOptions = ShippingManifest::getOptions($cart);

        $defaultCountry = Country::where('iso2', 'GB')
            ->orWhere('iso2', 'IN')
            ->orWhere('iso2', 'US')
            ->first() ?? $countries->first();

        $deliveryAreas = DeliveryArea::where('is_active', true)->get();

        return view('storefront.checkout.index', compact(
            'cart',
            'summary',
            'countries',
            'defaultCountry',
            'shippingOptions',
            'deliveryAreas'
        ));
    }

    /**
     * Process checkout form: Save addresses, select shipping, create Order
     */
    public function process(Request $request)
    {
        $cart = $this->cartManager->getCart(calculate: true);

        if (! $cart->lines()->count()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'contact_email' => 'required|email|max:150',
            'contact_phone' => 'required|string|max:30',
            'line_one' => 'required|string|max:255',
            'line_two' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country_id' => 'required|exists:lunar_countries,id',
            'shipping_option' => 'required|string',
            'payment_method' => 'required|in:cod,razorpay',
            'delivery_instructions' => 'nullable|string|max:500',
        ]);

        try {
            // 1. Save Addresses to Cart
            $this->cartManager->setAddresses($validated);

            // 2. Set Shipping Option
            $this->cartManager->setShippingOption($validated['shipping_option']);

            // 3. Create Lunar Order
            $order = $this->cartManager->createOrder();

            // Link user if logged in
            if (Auth::check() && ! $order->user_id) {
                $order->update(['user_id' => Auth::id()]);
            }

            // 4. Handle Payment Method
            if ($validated['payment_method'] === 'cod') {
                $this->paymentService->processCodPayment($order);

                return redirect()->route('checkout.confirmation', ['reference' => $order->reference])
                    ->with('success', 'Order placed successfully with Cash on Delivery!');
            }

            if ($validated['payment_method'] === 'razorpay') {
                $razorpayData = $this->paymentService->createRazorpayOrder($order);

                return view('storefront.checkout.razorpay_pay', compact('order', 'razorpayData'));
            }

            return redirect()->route('checkout.confirmation', ['reference' => $order->reference]);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Verify Razorpay Payment from frontend modal
     */
    public function verifyRazorpay(Request $request)
    {
        $validated = $request->validate([
            'order_reference' => 'required|exists:lunar_orders,reference',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $order = Order::where('reference', $validated['order_reference'])->firstOrFail();

        try {
            $this->paymentService->verifyRazorpayPayment(
                $order,
                $validated['razorpay_order_id'],
                $validated['razorpay_payment_id'],
                $validated['razorpay_signature']
            );

            return redirect()->route('checkout.confirmation', ['reference' => $order->reference])
                ->with('success', 'Payment verified and order confirmed successfully!');
        } catch (Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Order Confirmation Page
     */
    public function confirmation(string $reference)
    {
        $order = Order::with([
            'lines.purchasable',
            'shippingAddress',
            'billingAddress',
            'transactions',
        ])->where('reference', $reference)->firstOrFail();

        // Customer security: if order belongs to a user and user is logged in, verify ownership
        if ($order->user_id && Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        return view('storefront.checkout.confirmation', compact('order'));
    }
}

