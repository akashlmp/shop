<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Models\Product;

class ProductMetadata extends Model
{
    use HasFactory;

    protected $table = 'product_metadata';

    protected $fillable = [
        'product_id',
        'brand',
        'model_code',
        'chipset',
        'battery_specs',
        'carton_specs',
        'specs',
        'colors',
        'unit_of_measure',
        'is_retail',
        'is_bulk',
        'bulk_min_quantity',
        'bulk_package_type',
        'is_quick_commerce',
        'quick_delivery_minutes',
        'badge_text',
        'origin_details',
        'image_url',
    ];

    protected $casts = [
        'is_retail' => 'boolean',
        'is_bulk' => 'boolean',
        'is_quick_commerce' => 'boolean',
        'bulk_min_quantity' => 'integer',
        'quick_delivery_minutes' => 'integer',
        'specs' => 'array',
        'colors' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
