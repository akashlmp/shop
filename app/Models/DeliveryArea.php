<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lunar\Models\Product;

class DeliveryArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'postcode',
        'city',
        'state',
        'delivery_time_minutes',
        'minimum_order_amount',
        'shipping_fee',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delivery_time_minutes' => 'integer',
        'minimum_order_amount' => 'integer',
        'shipping_fee' => 'integer',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'delivery_area_product', 'delivery_area_id', 'product_id');
    }

    /**
     * Check if a given postcode is serviceable
     */
    public static function findByPostcode(string $postcode): ?self
    {
        $clean = strtoupper(trim(str_replace(' ', '', $postcode)));

        return self::where('is_active', true)
            ->get()
            ->first(function ($area) use ($clean) {
                $areaClean = strtoupper(trim(str_replace(' ', '', $area->postcode)));
                return $clean === $areaClean || str_starts_with($clean, $areaClean);
            });
    }
}
