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
        Schema::table('product_metadata', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('product_id');
            $table->string('model_code')->nullable()->after('brand');
            $table->string('chipset')->nullable()->after('model_code');
            $table->string('battery_specs')->nullable()->after('chipset');
            $table->string('carton_specs')->nullable()->after('battery_specs');
            $table->json('specs')->nullable()->after('carton_specs');
            $table->json('colors')->nullable()->after('specs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_metadata', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model_code', 'chipset', 'battery_specs', 'carton_specs', 'specs', 'colors']);
        });
    }
};

