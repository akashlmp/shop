<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingExperienceController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Products, Categories, Search
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// 3. Shopping Experiences: Retail & Bulk Wholesale
Route::get('/retail', [ShoppingExperienceController::class, 'retail'])->name('experience.retail');
Route::get('/bulk-buy', [ShoppingExperienceController::class, 'bulkBuy'])->name('experience.bulk');

// 4. Cart Operations
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon');
    Route::post('/coupon/remove', [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

// 5. Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::post('/razorpay/verify', [CheckoutController::class, 'verifyRazorpay'])->name('razorpay.verify');
    Route::get('/confirmation/{reference}', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

// 6. Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 7. Customer Account
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{reference}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'saveAddress'])->name('addresses.store');
});

// 8. Static Content Pages
Route::get('/contact', [StaticPageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [StaticPageController::class, 'contactSubmit'])->name('page.contact.submit');
Route::get('/about', [StaticPageController::class, 'about'])->name('page.about');
Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('page.privacy');
Route::get('/terms', [StaticPageController::class, 'terms'])->name('page.terms');

// 9. Payment Webhooks
Route::post('/webhooks/razorpay', [WebhookController::class, 'razorpay'])->name('webhook.razorpay')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
