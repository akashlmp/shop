<?php

namespace App\Http\Controllers;

use App\Models\ProductMetadata;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Product;

class ShoppingExperienceController extends Controller
{
    /**
     * Experience 1: Retail Shopping
     */
    public function retail(Request $request)
    {
        $collections = Collection::with('urls')->get();

        $query = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published')
            ->whereHas('metadata', function ($q) {
                $q->where('is_retail', true);
            });

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('collections.urls', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->paginate(12)->withQueryString();

        return view('storefront.experiences.retail', compact('products', 'collections'));
    }

    /**
     * Experience 2: Bulk Buy / Wholesale
     */
    public function bulkBuy(Request $request)
    {
        $collections = Collection::with('urls')->get();

        $query = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published')
            ->whereHas('metadata', function ($q) {
                $q->where('is_bulk', true);
            });

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('collections.urls', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->paginate(12)->withQueryString();

        return view('storefront.experiences.bulk_buy', compact('products', 'collections'));
    }
}

