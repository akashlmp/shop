<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Delivery Areas for serviceability & quick commerce
        Schema::create('delivery_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('postcode')->index();
            $table->string('city');
            $table->string('state')->nullable();
            $table->unsignedInteger('delivery_time_minutes')->default(20);
            $table->unsignedBigInteger('minimum_order_amount')->default(500); // 5.00 EUR in cents
            $table->unsignedBigInteger('shipping_fee')->default(199); // 1.99 EUR in cents
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Product Metadata / Shopping Modes (Retail, Bulk Buy, Quick Shop)
        Schema::create('product_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique();
            $table->foreign('product_id')->references('id')->on('lunar_products')->cascadeOnDelete();
            $table->string('unit_of_measure')->default('piece'); // piece, kg, litre, pack, carton, box
            $table->boolean('is_retail')->default(true);
            $table->boolean('is_bulk')->default(false);
            $table->unsignedInteger('bulk_min_quantity')->default(5);
            $table->string('bulk_package_type')->nullable(); // e.g. "Wholesale Pack of 10", "Box of 24"
            $table->boolean('is_quick_commerce')->default(true);
            $table->unsignedInteger('quick_delivery_minutes')->default(15);
            $table->string('badge_text')->nullable(); // e.g. "Bestseller", "Organic", "Bulk Saver"
            $table->text('origin_details')->nullable(); // Sourcing, farmer/mill details for grocery feel
            $table->timestamps();
        });

        // 3. Delivery Area Restrictions (optional per-product override)
        Schema::create('delivery_area_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_area_id')->constrained('delivery_areas')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('lunar_products')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['delivery_area_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_area_product');
        Schema::dropIfExists('product_metadata');
        Schema::dropIfExists('delivery_areas');
    }
};

