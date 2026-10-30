<?php

namespace App\Http\Controllers;

use App\Models\ProductMetadata;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $collections = Collection::with(['urls'])->get();

        // 8 Featured Electronic Products
        $featuredProducts = Product::with(['variants.prices', 'urls', 'collections', 'metadata'])
            ->where('status', 'published')
            ->take(8)
            ->get();

        // TWS & Wireless Audio
        $twsProducts = Product::with(['variants.prices', 'urls', 'metadata'])
            ->whereHas('collections.urls', function ($q) {
                $q->where('slug', 'tws-earbuds');
            })
            ->where('status', 'published')
            ->take(6)
            ->get();

        // Over-Ear & Gaming / ANC
        $gamingProducts = Product::with(['variants.prices', 'urls', 'metadata'])
            ->whereHas('collections.urls', function ($q) {
                $q->whereIn('slug', ['gaming-over-ear', 'anc-audio']);
            })
            ->where('status', 'published')
            ->take(6)
            ->get();

        // Bulk Wholesale B2B Deals
        $bulkBuyProducts = Product::with(['variants.prices', 'urls', 'metadata'])
            ->whereHas('metadata', function ($q) {
                $q->where('is_bulk', true);
            })
            ->where('status', 'published')
            ->take(6)
            ->get();

        return view('storefront.home', compact(
            'collections',
            'featuredProducts',
            'twsProducts',
            'gamingProducts',
            'bulkBuyProducts'
        ));
    }
}
