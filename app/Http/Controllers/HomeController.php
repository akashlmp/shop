<?php

namespace App\Http\Controllers;

use App\Models\DeliveryArea;
use App\Models\ProductMetadata;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $collections = Collection::with(['urls'])->get();

        // 8 Featured Retail Products
        $featuredProducts = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published')
            ->take(8)
            ->get();

        // Quick Commerce Products
        $quickShopProducts = Product::with(['variants.prices', 'urls'])
            ->whereHas('metadata', function ($q) {
                $q->where('is_quick_commerce', true);
            })
            ->where('status', 'published')
            ->take(6)
            ->get();

        // Bulk Buy Deals
        $bulkBuyProducts = Product::with(['variants.prices', 'urls'])
            ->whereHas('metadata', function ($q) {
                $q->where('is_bulk', true);
            })
            ->where('status', 'published')
            ->take(6)
            ->get();

        $deliveryAreas = DeliveryArea::where('is_active', true)->take(6)->get();

        return view('storefront.home', compact(
            'collections',
            'featuredProducts',
            'quickShopProducts',
            'bulkBuyProducts',
            'deliveryAreas'
        ));
    }
}

