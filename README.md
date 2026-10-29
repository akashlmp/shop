# LunarStore - Complete E-Commerce Platform (Laravel 11 + Lunar PHP)

A modern, high-performance e-commerce platform built with **Laravel 11**, **Lunar PHP (1.x)**, **MySQL 8+**, **Tailwind CSS**, and **Livewire 3**. 

Designed specifically for modern grocery and general merchandise with **three unified shopping experiences**, tiered wholesale volume pricing, 15-minute hyperlocal quick delivery, resilient multi-gateway checkout (Cash on Delivery + Razorpay with server-side HMAC-SHA256 signature verification), and a comprehensive administration dashboard.

---

## 🌟 Key Features

### 1. Three Unified Shopping Experiences
- **Retail Shopping (`/retail`)**:
  - Full product browsing with category filtering, instant keyword search, and price sorting.
  - Interactive variant selector, stock level badge, GI-tag / farm-direct badges.
  - Server-side stock availability checks and real-time cart synchronization.
- **Bulk Buy Wholesale (`/bulk-buy`)**:
  - Enforced minimum order quantities (e.g. 5 or 10 units per SKU).
  - Multi-tier volume discounts (automatic price breaks calculated by Lunar when threshold is met).
  - Master carton packaging details, unit weights, and wholesale commercial freight.
- **Quick Shop 15-Min Hyperlocal (`/quick-shop`)**:
  - Live postcode serviceability checker (e.g., South Mumbai `400001`, Central Delhi `110001`, Bengaluru `560038`, London Central `SW1A 1AA`).
  - Rapid `+` / `-` product ordering interface with sticky cart drawer.
  - Hyperlocal delivery hub routing with dynamic 10–20 minute delivery estimates.

### 2. Lunar-Powered Commerce Engine
- **Unified Product Catalog**: Products, Variants, Multi-Currency Prices (EUR, USD, INR), and Tax Zones powered directly by Lunar core models.
- **Tiered Volume Pricing**: Leverages Lunar's native `min_quantity` price matrix on `lunar_prices`.
- **Dynamic Shipping Pipeline**: Custom `ShippingModifier` registered into Lunar's `ShippingManifest` pipeline providing:
  - Standard Delivery (€2.99, or FREE on orders over €35.00).
  - ⚡ Hyperlocal Express 15-min delivery for verified serviceable postcodes.
  - Wholesale Commercial Freight for heavy bulk deliveries.
- **Promotions & Coupons**: Integrated with Lunar's `AmountOff` discount pipeline (supports percentage and fixed amount codes, e.g. `WELCOME10`, `BULK15`, `SAVE5`).
- **Atomic Order Creation**: Orders created within database transactions with automatic stock decrements and idempotent draft-order deduplication.

### 3. Payment Processing & Security
- **Cash on Delivery (COD)**: Doorstep collection transaction with instant order confirmation.
- **Razorpay Online Payments**:
  - Server-side cryptographic HMAC-SHA256 signature verification.
  - Webhook listener with secret verification (`/webhook/razorpay`).
  - Zero trust: browser redirects cannot fake order payment status.
  - Idempotent transaction recording preventing duplicate charge attempts.
  - Interactive testing sandbox mode with auto-signed testing tokens.

### 4. Admin Portal (Lunar Filament)
- Secured admin dashboard at `/lunar` powered by Lunar Admin & Filament.
- Manage products, variants, tiered prices, images, stock levels, orders, customers, and collections.
- Accessible only to authorized `Staff` accounts (`admin@example.com` / `password`).

---

## 🏗️ Technology Stack

| Component | Technology |
|---|---|
| **Framework** | Laravel 11.x |
| **Commerce Engine** | Lunar PHP 1.0 (`lunarphp/lunar`, `lunarphp/core`) |
| **Admin Panel** | Lunar Admin / Filament 3.x |
| **Database** | MySQL 8.0+ (InnoDB) |
| **Frontend** | Laravel Blade, Livewire 3, Tailwind CSS (via Vite) |
| **Payments** | Cash on Delivery & Razorpay (HMAC-SHA256) |
| **Testing** | PHPUnit (27 automated unit & feature tests) |
| **Default Currency** | EUR (€) |
| **Default Channel** | Web Storefront |

---

## 🚀 Getting Started

### 1. Requirements
- PHP 8.2 or higher (with `pdo_mysql`, `bcmath`, `intl`, `mbstring`, `gd` or `imagick`, `fileinfo`)
- MySQL 8.0+
- Composer 2.x
- Node.js 18+ and npm

### 2. Installation & Setup

1. **Clone or navigate into the project directory**:
   ```powershell
   cd c:\Users\akash\OneDrive\Desktop\projects\shop
   ```

2. **Configure Environment Variables**:
   Copy `.env.example` to `.env` (or verify existing `.env`):
   ```env
   APP_NAME="Lunar Grocery & Merchandise"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lunar_shop
   DB_USERNAME=root
   DB_PASSWORD=

   # Razorpay Sandbox Credentials
   RAZORPAY_KEY=rzp_test_lunar_shop_key
   RAZORPAY_SECRET=lunar_shop_test_secret
   RAZORPAY_SANDBOX=true
   ```

3. **Install Dependencies**:
   ```powershell
   composer install
   npm install
   npm run build
   ```

4. **Run Migrations & Seed Data**:
   ```powershell
   php artisan migrate
   php artisan lunar:import:address-data
   php artisan db:seed
   ```

5. **Start Local Development Server**:
   ```powershell
   php artisan serve
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## 🔑 Default Credentials

### Administrator Portal
- **URL**: [http://127.0.0.1:8000/lunar/login](http://127.0.0.1:8000/lunar/login)
- **Email**: `admin@example.com`
- **Password**: `password`

### Sample Customer Account
- **URL**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Email**: `customer@example.com`
- **Password**: `password`

### Active Promotional Coupons
- `WELCOME10`: 10% off any order.
- `BULK15`: €15.00 off bulk orders.
- `SAVE5`: 5% discount across the store.

### Serviceable Quick-Commerce Postcodes
- `400001` (South Mumbai Express Hub - 15 mins)
- `110001` (Central Delhi Express Hub - 18 mins)
- `560038` (Bengaluru Tech Corridor - 12 mins)
- `SW1A 1AA` (London Central Hub - 20 mins)

---

## 🧪 Automated Testing

The project includes an automated test suite verifying all critical business logic and storefront workflows.

### Running the Test Suite
```powershell
php artisan test
```

### Coverage Highlights (27 Tests, 87 Assertions)
- **Catalog & Discovery**: Home page, full catalog pagination, search query filtering, empty search handling, category collection filtering.
- **Product Details & Pricing**: Dynamic variant and multi-tier volume pricing table.
- **Shopping Cart**: Adding items, line quantity increment/decrement, line removal, and total recalculation.
- **Inventory & Stock Availability**: Server-side stock validation preventing excess purchases and out-of-stock checkouts.
- **Wholesale Bulk Buy**: Wholesale minimum order validation (e.g. 5+ items) and tiered price break calculations.
- **Promotions Engine**: Validating coupon applications (`WELCOME10`) and rejecting invalid codes.
- **Hyperlocal Serviceability**: Postcode lookups returning distance-based serviceability and shipping estimates.
- **Shipping Calculator**: Standard shipping vs Free Shipping threshold (€35+).
- **Checkout & Order Creation**: Mandatory address validation, atomic order creation, inventory decrements, and draft-order idempotency deduplication.
- **Payment Verification**: Cash on Delivery and Razorpay cryptographic HMAC-SHA256 signature verification & tampering rejection.
- **Security & Authorization**: Customer registration, authenticated order history access, and staff-only admin route protection.

---

## 📁 Key File Map

```
app/
├── Http/Controllers/
│   ├── HomeController.php               # Home storefront view
│   ├── ProductController.php            # Products catalog, search, categories, product details
│   ├── ShoppingExperienceController.php # Retail, Bulk Buy, and Quick Shop controllers
│   ├── CartController.php               # Cart add, update, remove, clear, coupon, serviceability
│   ├── CheckoutController.php           # Address capture, order creation, payment routing
│   ├── AuthController.php               # Customer login, register, logout
│   ├── AccountController.php            # Customer profile, orders, order details, addresses
│   ├── StaticPageController.php         # About, Contact, Privacy, Terms
│   └── WebhookController.php            # Razorpay webhook listener
├── Modifiers/
│   └── ShippingModifier.php             # Custom shipping rates registered into Lunar pipeline
├── Models/
│   ├── DeliveryArea.php                 # Hyperlocal quick commerce delivery hubs
│   ├── ProductMetadata.php              # Retail, bulk, quick flags, packages, origins
│   └── User.php                         # Extends LunarUser for customer account management
└── Services/
    ├── CartManager.php                  # End-to-end cart session & transaction service
    └── PaymentService.php               # COD & Razorpay HMAC-SHA256 signature verification
resources/views/
├── layouts/app.blade.php                # Master storefront layout with postcode pill & experience tabs
├── components/                          # Reusable product card components (retail, bulk, quick)
└── storefront/                          # Blade templates for all 18 store pages
tests/Feature/
└── StorefrontEcommerceTest.php          # 25 comprehensive feature tests
```

---

## 🛡️ License
Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
