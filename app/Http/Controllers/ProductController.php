<?php

namespace App\Http\Controllers;

use App\Models\ProductMetadata;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Product;
use Lunar\Models\Url;

class ProductController extends Controller
{
    /**
     * All products listing with search, filtering and sorting
     */
    public function index(Request $request)
    {
        $query = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published');

        // Category Filter
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('collections.urls', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Search Keyword
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('attribute_data->name->value->en', 'LIKE', "%{$search}%")
                    ->orWhere('attribute_data->description->value->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('variants', function ($vq) use ($search) {
                        $vq->where('sku', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Price Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price-asc' || $sort === 'price-desc') {
            $direction = $sort === 'price-asc' ? 'asc' : 'desc';
            $query->select('lunar_products.*')
                ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                ->join('lunar_prices', function ($join) {
                    $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                        ->where('lunar_prices.priceable_type', '=', 'product_variant')
                        ->where('lunar_prices.min_quantity', '=', 1);
                })
                ->orderBy('lunar_prices.price', $direction);
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $collections = Collection::with('urls')->get();

        return view('storefront.products.index', compact('products', 'collections'));
    }

    /**
     * Product details page
     */
    public function show(string $slug)
    {
        $url = Url::where('element_type', Product::morphName())
            ->where('slug', $slug)
            ->firstOrFail();

        $product = Product::with([
            'variants.prices',
            'collections.urls',
            'urls',
        ])->findOrFail($url->element_id);

        $metadata = ProductMetadata::where('product_id', $product->id)->first();
        $variant = $product->variants->first();
        $basePrice = $variant?->prices->where('min_quantity', 1)->first();
        $priceTiers = $variant?->prices->where('min_quantity', '>', 1)->sortBy('min_quantity');

        // Related Products in same collection
        $relatedProducts = collect();
        if ($primaryCollection = $product->collections->first()) {
            $relatedProducts = Product::with(['variants.prices', 'urls'])
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->whereHas('collections', function ($q) use ($primaryCollection) {
                    $q->where('lunar_collections.id', $primaryCollection->id);
                })
                ->take(4)
                ->get();
        }

        return view('storefront.products.show', compact(
            'product',
            'metadata',
            'variant',
            'basePrice',
            'priceTiers',
            'relatedProducts'
        ));
    }

    /**
     * Category / Collection listing page
     */
    public function category(string $slug, Request $request)
    {
        $url = Url::where('element_type', Collection::morphName())
            ->where('slug', $slug)
            ->firstOrFail();

        $collection = Collection::with('urls')->findOrFail($url->element_id);

        $query = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published')
            ->whereHas('collections', function ($q) use ($collection) {
                $q->where('lunar_collections.id', $collection->id);
            });

        // Price Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price-asc') {
            $query->select('lunar_products.*')
                ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                ->join('lunar_prices', function ($join) {
                    $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                        ->where('lunar_prices.priceable_type', '=', 'product_variant')
                        ->where('lunar_prices.min_quantity', '=', 1);
                })
                ->orderBy('lunar_prices.price', 'asc');
        } elseif ($sort === 'price-desc') {
            $query->select('lunar_products.*')
                ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                ->join('lunar_prices', function ($join) {
                    $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                        ->where('lunar_prices.priceable_type', '=', 'product_variant')
                        ->where('lunar_prices.min_quantity', '=', 1);
                })
                ->orderBy('lunar_prices.price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $collections = Collection::with('urls')->get();

        return view('storefront.products.category', compact('collection', 'products', 'collections'));
    }

    /**
     * Search results page
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        $query = Product::with(['variants.prices', 'urls', 'collections'])
            ->where('status', 'published');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('attribute_data->name->value->en', 'LIKE', "%{$search}%")
                    ->orWhere('attribute_data->description->value->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('variants', function ($vq) use ($search) {
                        $vq->where('sku', 'LIKE', "%{$search}%");
                    });
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $collections = Collection::with('urls')->get();

        return view('storefront.products.search', compact('products', 'search', 'collections'));
    }
}

