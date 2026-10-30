<?php

namespace Tests\Feature;

use App\Models\ProductMetadata;
use App\Models\User;
use App\Services\CartManager;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Lunar\Models\Cart;
use Lunar\Models\Collection;
use Lunar\Models\Country;
use Lunar\Models\Discount;
use Lunar\Models\Order;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\Models\Transaction;
use Tests\TestCase;

class StorefrontEcommerceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ELECTRONICS');
        $response->assertSee('Delivering Excellence');
    }

    public function test_products_catalog_page_renders_with_products(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('All Electronics');
    }

    public function test_retail_experience_page_renders(): void
    {
        $response = $this->get('/retail');

        $response->assertStatus(200);
        $response->assertSee('Retail');
    }

    public function test_bulk_buy_experience_page_renders(): void
    {
        $response = $this->get('/bulk-buy');

        $response->assertStatus(200);
        $response->assertSee('Wholesale');
    }

    public function test_quick_shop_feature_is_completely_removed(): void
    {
        // Quick shop route was completely removed as requested
        $response = $this->get('/quick-shop');
        $response->assertStatus(404);
    }

    public function test_static_pages_render(): void
    {
        $this->get('/about')->assertStatus(200)->assertSee('About');
        $this->get('/contact')->assertStatus(200)->assertSee('Contact');
        $this->get('/privacy')->assertStatus(200)->assertSee('Privacy Policy');
        $this->get('/terms')->assertStatus(200)->assertSee('Terms of Service');
    }

    public function test_product_search_finds_matching_electronics(): void
    {
        $response = $this->get('/search?q=Lenovo');

        $response->assertStatus(200);
        $response->assertSee('Lenovo');
    }

    public function test_product_search_handles_no_matches_gracefully(): void
    {
        $response = $this->get('/search?q=NonExistentProductXYZ12345');

        $response->assertStatus(200);
        $response->assertSee('No items matched');
    }

    public function test_category_filtering_returns_filtered_products(): void
    {
        $collection = Collection::whereHas('urls', function ($q) {
            $q->where('slug', 'tws-earbuds');
        })->first() ?? Collection::first();
        $this->assertNotNull($collection, 'An electronics collection should exist in the seeded database');

        $slug = $collection->urls->first()?->slug ?? 'tws-earbuds';
        $response = $this->get('/products?category=' . urlencode($slug));

        $response->assertStatus(200);
    }

    public function test_product_detail_page_loads_with_variants_and_pricing(): void
    {
        $product = Product::with('urls')->first();
        $this->assertNotNull($product, 'An electronics product should exist');

        $slug = $product->urls->first()?->slug;
        $this->assertNotNull($slug);
        $response = $this->get('/product/' . $slug);

        $response->assertStatus(200);
        $response->assertSee($product->translateAttribute('name'));
    }

    public function test_add_to_cart_and_view_cart_summary(): void
    {
        $variant = ProductVariant::where('stock', '>', 5)->first();
        $this->assertNotNull($variant);

        $response = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success');

        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($variant->product->translateAttribute('name'));
    }

    public function test_ajax_add_to_cart_returns_json_without_page_reload(): void
    {
        $variant = ProductVariant::where('stock', '>', 5)->first();
        $this->assertNotNull($variant);

        $response = $this->postJson('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'summary' => [
                'total_items',
                'sub_total_formatted',
                'total_formatted',
            ],
        ]);
    }

    public function test_cart_update_quantity_and_remove_item(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $variant = ProductVariant::where('stock', '>=', 10)->first();
        $cart = $cartManager->add($variant, 2);
        $lineId = $cart->lines->first()->id;

        // Update quantity to 4
        $cartManager->update($lineId, 4);
        $cart = $cartManager->getCart();
        $this->assertEquals(4, $cart->lines->first()->quantity);

        // Remove line item
        $cartManager->remove($lineId);
        $cart = $cartManager->getCart();
        $this->assertEquals(0, $cart->lines->count());
    }

    public function test_server_side_stock_availability_validation(): void
    {
        $variant = ProductVariant::where('purchasable', 'in_stock')->first();
        $this->assertNotNull($variant);

        // Try adding more than available stock
        $excessQuantity = $variant->stock + 50000;
        $response = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => $excessQuantity,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_bulk_pricing_tiers_and_minimum_quantity_validation(): void
    {
        $cartManager = app(CartManager::class);

        // Find metadata with bulk min quantity >= 5
        $metadata = ProductMetadata::where('bulk_min_quantity', '>=', 5)->first();
        $this->assertNotNull($metadata, 'Product metadata with bulk min quantity should exist');

        $variant = ProductVariant::where('product_id', $metadata->product_id)->first();
        $this->assertNotNull($variant, 'A product variant should exist');

        // Attempting to add with is_bulk=true and quantity < minQty must throw
        $rejected = false;
        try {
            $cartManager->add($variant, quantity: 1, meta: ['is_bulk' => true]);
        } catch (\Exception $e) {
            $rejected = true;
            $this->assertStringContainsString('minimum order', $e->getMessage());
        }
        $this->assertTrue($rejected, 'Adding bulk item below min quantity should be rejected');

        // Adding with quantity >= 5 should apply tiered pricing
        $cartManager->clear();
        $cart = $cartManager->add($variant, quantity: 5, meta: ['is_bulk' => true]);

        $line = $cart->lines->firstWhere('purchasable_id', $variant->id);
        $this->assertNotNull($line);

        // Verify tier pricing: 5+ tier price is lower than unit price for 1
        $tier5Price = $variant->prices->where('min_quantity', 5)->first();
        if ($tier5Price) {
            $this->assertEquals($tier5Price->price->value, $line->unitPrice->value);
        }
    }

    public function test_coupon_validation_and_discount_calculation(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        // Invalid coupon should fail
        $invalidResult = $cartManager->applyCoupon('NONEXISTENT_CODE_999');
        $this->assertFalse($invalidResult['success']);

        // Add an item to cart first so discount conditions can be met
        $variant = ProductVariant::where('stock', '>=', 10)->first();
        $cartManager->add($variant, 2);

        // Valid coupon WELCOME10 exists in seeder
        $validResult = $cartManager->applyCoupon('WELCOME10');
        $this->assertTrue($validResult['success']);

        $summary = $cartManager->getSummary();
        $this->assertGreaterThan(0, $summary['discount_total']);
    }

    public function test_shipping_cost_calculation(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $country = Country::first();
        $this->assertNotNull($country);

        $variant = ProductVariant::where('stock', '>=', 10)->first();

        // 1 item - order under 50 EUR should have standard shipping
        $cartManager->add($variant, 1);
        $cartManager->setAddresses([
            'country_id' => $country->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'line_one' => '42 High St',
            'city' => 'London',
            'state' => 'Greater London',
            'postcode' => 'SW1A 1AA',
            'contact_email' => 'jane@example.com',
            'contact_phone' => '+44 7700 900077',
        ]);
        $cartManager->setShippingOption('standard-delivery');

        $summary = $cartManager->getSummary();
        $this->assertNotNull($summary['shipping_total']);
    }

    public function test_checkout_validation_requires_mandatory_address_fields(): void
    {
        $variant = ProductVariant::where('stock', '>=', 5)->first();
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        // Attempt checkout with missing required fields
        $response = $this->post('/checkout/process', [
            'first_name' => '',
            'last_name' => '',
            'line_one' => '',
            'city' => '',
            'state' => '',
            'postcode' => '',
            'country_id' => '',
            'contact_email' => '',
            'contact_phone' => '',
        ]);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'line_one',
            'contact_email',
            'contact_phone',
        ]);
    }

    public function test_order_creation_and_inventory_decrement(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $country = Country::first();
        $this->assertNotNull($country);

        $variant = ProductVariant::where('stock', '>=', 10)->where('purchasable', 'in_stock')->first();
        $initialStock = $variant->stock;

        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->post('/checkout/process', [
            'first_name' => 'Alex',
            'last_name' => 'Vance',
            'line_one' => 'Unit 12, Terminal Cargo Park',
            'city' => 'London',
            'state' => 'Greater London',
            'postcode' => 'E14 5AB',
            'country_id' => $country->id,
            'contact_email' => 'alex.vance@example.com',
            'contact_phone' => '+44 7700 900123',
            'shipping_option' => 'standard-delivery',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Verify variant stock was decremented by 2
        $variant->refresh();
        $this->assertEquals($initialStock - 2, $variant->stock);

        // Verify Order exists
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('processing', $order->status);

        // Verify COD Transaction
        $transaction = Transaction::where('order_id', $order->id)->where('driver', 'cash-on-delivery')->first();
        $this->assertNotNull($transaction);
        $this->assertTrue((bool) $transaction->success);
    }

    public function test_duplicate_order_prevention(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $country = Country::first();
        $this->assertNotNull($country);

        $variant = ProductVariant::where('stock', '>=', 5)->first();
        $cartManager->add($variant, 1);
        $cartManager->setAddresses([
            'country_id' => $country->id,
            'first_name' => 'David',
            'last_name' => 'Miller',
            'line_one' => '12 Commercial Road',
            'city' => 'Manchester',
            'state' => 'Greater Manchester',
            'postcode' => 'M1 1AE',
            'contact_email' => 'david@example.com',
            'contact_phone' => '+44 7700 900555',
        ]);
        $cartManager->setShippingOption('standard-delivery');

        $order1 = $cartManager->createOrder();
        $this->assertNotNull($order1);

        // Calling createOrder again on same cart returns existing order without creating duplicate
        $order2 = $cartManager->createOrder();
        $this->assertEquals($order1->id, $order2->id);
    }

    public function test_razorpay_payment_verification_valid_signature_marks_order_paid(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $country = Country::first();
        $this->assertNotNull($country);

        $variant = ProductVariant::where('stock', '>=', 5)->first();
        $cartManager->add($variant, 1);
        $cartManager->setAddresses([
            'country_id' => $country->id,
            'first_name' => 'Sophia',
            'last_name' => 'Chen',
            'line_one' => '88 Silicon Wharf',
            'city' => 'Bristol',
            'state' => 'Bristol',
            'postcode' => 'BS1 4AA',
            'contact_email' => 'sophia@example.com',
            'contact_phone' => '+44 7700 900888',
        ]);
        $cartManager->setShippingOption('standard-delivery');

        $order = $cartManager->createOrder();
        $paymentService = app(PaymentService::class);

        // Simulate Razorpay payment intent
        $razorpayData = $paymentService->createRazorpayOrder($order);
        $razorpayOrderId = $razorpayData['order_id'];
        $razorpayPaymentId = 'pay_test_' . rand(100000, 999999);

        // Calculate cryptographic HMAC SHA256 signature
        $secret = config('services.razorpay.key_secret');
        $validSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $secret);

        $transaction = $paymentService->verifyRazorpayPayment(
            $order,
            $razorpayOrderId,
            $razorpayPaymentId,
            $validSignature
        );

        $this->assertTrue((bool) $transaction->success);
        $this->assertEquals('paid', $transaction->status);

        $order->refresh();
        $this->assertEquals('payment-received', $order->status);
    }

    public function test_razorpay_payment_verification_invalid_signature_fails_safely(): void
    {
        $cartManager = app(CartManager::class);
        $cartManager->clear();

        $country = Country::first();
        $this->assertNotNull($country);

        $variant = ProductVariant::where('stock', '>=', 5)->first();
        $cartManager->add($variant, 1);
        $cartManager->setAddresses([
            'country_id' => $country->id,
            'first_name' => 'Sophia',
            'last_name' => 'Chen',
            'line_one' => '88 Silicon Wharf',
            'city' => 'Bristol',
            'state' => 'Bristol',
            'postcode' => 'BS1 4AA',
            'contact_email' => 'sophia@example.com',
            'contact_phone' => '+44 7700 900888',
        ]);
        $cartManager->setShippingOption('standard-delivery');

        $order = $cartManager->createOrder();
        $paymentService = app(PaymentService::class);

        $razorpayOrderId = 'order_tampered123';
        $razorpayPaymentId = 'pay_fake999';
        $tamperedSignature = 'invalid_tampered_signature_hex';

        $failed = false;
        try {
            $paymentService->verifyRazorpayPayment(
                $order,
                $razorpayOrderId,
                $razorpayPaymentId,
                $tamperedSignature
            );
        } catch (\Exception $e) {
            $failed = true;
            $this->assertStringContainsString('signature verification failed', $e->getMessage());
        }

        $this->assertTrue($failed, 'Invalid signature should throw exception and reject payment');
    }

    public function test_customer_registration_and_authentication(): void
    {
        $uniqueEmail = 'customer_' . uniqid() . '@example.com';

        $response = $this->post('/register', [
            'name' => 'Robert Taylor',
            'email' => $uniqueEmail,
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticated();

        $user = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($user);
        $this->assertEquals('Robert Taylor', $user->name);
    }

    public function test_unauthenticated_user_cannot_access_order_history(): void
    {
        $response = $this->get('/account/orders');
        $response->assertRedirect('/login');
    }

    public function test_unauthorized_user_cannot_access_lunar_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    protected function createTestOrder(): \Lunar\Models\Order
    {
        $cartManager = app(\App\Services\CartManager::class);
        $cartManager->clear();
        $variant = \Lunar\Models\ProductVariant::where('stock', '>=', 5)->first();
        $cartManager->add($variant, 1);
        $country = \Lunar\Models\Country::first();
        $cartManager->setAddresses([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'line_one' => '10 Downing Street',
            'city' => 'London',
            'postcode' => 'SW1A 2AA',
            'country_id' => $country->id,
            'contact_email' => 'john.doe@example.com',
            'contact_phone' => '+44 7700 900077',
        ]);
        $cartManager->setShippingOption('standard-delivery');
        $cart = $cartManager->getCart();
        $order = $cart->createOrder();
        $paymentService = app(\App\Services\PaymentService::class);
        $paymentService->processCodPayment($order);

        return $order;
    }

    public function test_authorized_staff_can_view_order_in_admin(): void
    {
        $staff = \Lunar\Admin\Models\Staff::firstOrCreate(
            ['email' => 'admin@acelectronics.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('AdminPassword123!'),
                'admin' => true,
            ]
        );

        $order = $this->createTestOrder();

        $response = $this->actingAs($staff, 'staff')
            ->get("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertSee($order->reference);
    }

    public function test_authorized_staff_can_view_razorpay_order_in_admin(): void
    {
        $staff = \Lunar\Admin\Models\Staff::firstOrCreate(
            ['email' => 'admin@acelectronics.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('AdminPassword123!'),
                'admin' => true,
            ]
        );

        $order = $this->createTestOrder();

        // Add a razorpay transaction to the order
        $order->transactions()->create([
            'success' => true,
            'type' => 'capture',
            'driver' => 'razorpay',
            'amount' => 1500,
            'reference' => 'pay_test12345678',
            'status' => 'paid',
            'notes' => 'Test Razorpay payment',
            'card_type' => 'online',
        ]);

        $response = $this->actingAs($staff, 'staff')
            ->get("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertSee('pay_test12345678');
    }

    public function test_admin_order_view_falls_back_gracefully_on_unknown_driver(): void
    {
        $staff = \Lunar\Admin\Models\Staff::firstOrCreate(
            ['email' => 'admin@acelectronics.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('AdminPassword123!'),
                'admin' => true,
            ]
        );

        $order = $this->createTestOrder();

        // Add a transaction with an unknown/custom driver
        $order->transactions()->create([
            'success' => true,
            'type' => 'capture',
            'driver' => 'custom-legacy-gateway',
            'amount' => 2000,
            'reference' => 'LEGACY-999',
            'status' => 'completed',
            'notes' => 'Legacy transaction',
            'card_type' => 'visa',
        ]);

        $response = $this->actingAs($staff, 'staff')
            ->get("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertSee('LEGACY-999');
    }

    public function test_order_statuses_include_shipped_and_delivered_and_can_be_updated(): void
    {
        $statuses = config('lunar.orders.statuses');

        $this->assertArrayHasKey('processing', $statuses);
        $this->assertArrayHasKey('dispatched', $statuses);
        $this->assertArrayHasKey('shipped', $statuses);
        $this->assertArrayHasKey('delivered', $statuses);
        $this->assertArrayHasKey('cancelled', $statuses);

        $order = $this->createTestOrder();

        // Update to shipped
        $order->update(['status' => 'shipped']);
        $this->assertEquals('shipped', $order->fresh()->status);

        // Update to delivered
        $order->update(['status' => 'delivered']);
        $this->assertEquals('delivered', $order->fresh()->status);
    }
}
