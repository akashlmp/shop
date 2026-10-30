<?php

namespace App\Providers;

use App\Models\DeliveryArea;
use App\Models\ProductMetadata;
use App\Modifiers\ShippingModifier;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Base\ShippingModifiers;
use Lunar\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(function (\Filament\Panel $panel) {
            return $panel->path('admin');
        })->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom shipping modifier
        app(ShippingModifiers::class)->add(ShippingModifier::class);

        Product::macro('metadata', function () {
            return $this->hasOne(ProductMetadata::class, 'product_id');
        });

        Product::macro('deliveryAreas', function () {
            return $this->belongsToMany(DeliveryArea::class, 'delivery_area_product', 'product_id', 'delivery_area_id');
        });

        Product::macro('getPrimaryImageUrlAttribute', function () {
            if ($this->relationLoaded('metadata') && $this->metadata?->image_url) {
                return $this->metadata->image_url;
            }
            if ($metadata = ProductMetadata::where('product_id', $this->id)->first()) {
                if ($metadata->image_url) {
                    return $metadata->image_url;
                }
            }
            return 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80';
        });
    }
}
