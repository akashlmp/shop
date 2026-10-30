<?php

namespace Database\Seeders;

use App\Models\DeliveryArea;
use App\Models\ProductMetadata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Lunar\DiscountTypes\AmountOff;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Channel;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Discount;
use Lunar\Models\Language;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxZone;
use Lunar\Models\Url;

class StoreDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Channel
        $channel = Channel::firstOrCreate(
            ['handle' => 'webstore'],
            [
                'name' => 'Webstore',
                'default' => true,
                'url' => config('app.url', 'http://127.0.0.1:8000'),
            ]
        );

        // 2. Language
        $language = Language::firstOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'default' => true,
            ]
        );

        // 3. Currencies (EUR default as requested in prompt)
        $eur = Currency::firstOrCreate(
            ['code' => 'EUR'],
            [
                'name' => 'Euro',
                'exchange_rate' => 1.0,
                'decimal_places' => 2,
                'default' => true,
                'enabled' => true,
            ]
        );
        Currency::where('code', '!=', 'EUR')->update(['default' => false]);
        $eur->update(['default' => true, 'enabled' => true]);

        $usd = Currency::firstOrCreate(
            ['code' => 'USD'],
            [
                'name' => 'US Dollar',
                'exchange_rate' => 1.08,
                'decimal_places' => 2,
                'default' => false,
                'enabled' => true,
            ]
        );

        $inr = Currency::firstOrCreate(
            ['code' => 'INR'],
            [
                'name' => 'Indian Rupee',
                'exchange_rate' => 90.50,
                'decimal_places' => 2,
                'default' => false,
                'enabled' => true,
            ]
        );

        // 4. Customer Groups
        $retailGroup = CustomerGroup::firstOrCreate(
            ['handle' => 'retail'],
            [
                'name' => 'Retail',
                'default' => true,
            ]
        );

        $wholesaleGroup = CustomerGroup::firstOrCreate(
            ['handle' => 'wholesale'],
            [
                'name' => 'Wholesale / Bulk',
                'default' => false,
            ]
        );

        // 5. Collection Group
        $collectionGroup = CollectionGroup::firstOrCreate(
            ['handle' => 'main'],
            [
                'name' => 'Main Navigation',
            ]
        );

        // 6. Tax Class & Tax Zone
        $taxClass = TaxClass::firstOrCreate(
            ['name' => 'Standard Tax'],
            [
                'default' => true,
            ]
        );

        $taxZone = TaxZone::firstOrCreate(
            ['name' => 'Default Tax Zone'],
            [
                'zone_type' => 'country',
                'price_display' => 'tax_inclusive',
                'default' => true,
                'active' => true,
            ]
        );

        // 7. Attributes
        $productAttrGroup = AttributeGroup::firstOrCreate(
            ['handle' => 'details', 'attributable_type' => Product::morphName()],
            [
                'name' => collect(['en' => 'Product Details']),
                'position' => 1,
            ]
        );

        $collectionAttrGroup = AttributeGroup::firstOrCreate(
            ['handle' => 'collection_details', 'attributable_type' => Collection::morphName()],
            [
                'name' => collect(['en' => 'Collection Details']),
                'position' => 1,
            ]
        );

        $productNameAttr = Attribute::firstOrCreate(
            ['handle' => 'name', 'attribute_type' => 'product'],
            [
                'attribute_group_id' => $productAttrGroup->id,
                'position' => 1,
                'name' => ['en' => 'Name'],
                'section' => 'main',
                'type' => TranslatedText::class,
                'required' => true,
                'configuration' => ['richtext' => false],
                'system' => true,
            ]
        );

        $productDescAttr = Attribute::firstOrCreate(
            ['handle' => 'description', 'attribute_type' => 'product'],
            [
                'attribute_group_id' => $productAttrGroup->id,
                'position' => 2,
                'name' => ['en' => 'Description'],
                'section' => 'main',
                'type' => TranslatedText::class,
                'required' => false,
                'configuration' => ['richtext' => true],
                'system' => false,
            ]
        );

        $collNameAttr = Attribute::firstOrCreate(
            ['handle' => 'name', 'attribute_type' => 'collection'],
            [
                'attribute_group_id' => $collectionAttrGroup->id,
                'position' => 1,
                'name' => ['en' => 'Name'],
                'section' => 'main',
                'type' => TranslatedText::class,
                'required' => true,
                'configuration' => ['richtext' => false],
                'system' => true,
            ]
        );

        $collDescAttr = Attribute::firstOrCreate(
            ['handle' => 'description', 'attribute_type' => 'collection'],
            [
                'attribute_group_id' => $collectionAttrGroup->id,
                'position' => 2,
                'name' => ['en' => 'Description'],
                'section' => 'main',
                'type' => TranslatedText::class,
                'required' => false,
                'configuration' => ['richtext' => true],
                'system' => false,
            ]
        );

        // 8. Product Type
        $productType = ProductType::firstOrCreate(
            ['name' => 'Grocery & General Merchandise'],
        );
        $productType->mappedAttributes()->syncWithoutDetaching([$productNameAttr->id, $productDescAttr->id]);

        // 9. Delivery Areas (Serviceable quick commerce zones)
        $deliveryAreas = [
            [
                'name' => 'South Mumbai Express (Colaba, Fort & Marine Lines)',
                'postcode' => '400001',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'delivery_time_minutes' => 15,
                'minimum_order_amount' => 500,
                'shipping_fee' => 149,
                'is_active' => true,
            ],
            [
                'name' => 'Central Delhi Hub (Connaught Place & Mandi House)',
                'postcode' => '110001',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'delivery_time_minutes' => 18,
                'minimum_order_amount' => 500,
                'shipping_fee' => 149,
                'is_active' => true,
            ],
            [
                'name' => 'Bengaluru Tech Corridor (Indiranagar & Koramangala)',
                'postcode' => '560038',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'delivery_time_minutes' => 12,
                'minimum_order_amount' => 400,
                'shipping_fee' => 99,
                'is_active' => true,
            ],
            [
                'name' => 'London Central Quick Hub (Westminster, Soho & West End)',
                'postcode' => 'SW1A 1AA',
                'city' => 'London',
                'state' => 'Greater London',
                'delivery_time_minutes' => 20,
                'minimum_order_amount' => 1000,
                'shipping_fee' => 249,
                'is_active' => true,
            ],
            [
                'name' => 'London City & Shoreditch',
                'postcode' => 'EC1A 1BB',
                'city' => 'London',
                'state' => 'Greater London',
                'delivery_time_minutes' => 20,
                'minimum_order_amount' => 1000,
                'shipping_fee' => 249,
                'is_active' => true,
            ],
            [
                'name' => 'Paris 1er Arrondissement Express',
                'postcode' => '75001',
                'city' => 'Paris',
                'state' => 'Île-de-France',
                'delivery_time_minutes' => 25,
                'minimum_order_amount' => 1200,
                'shipping_fee' => 299,
                'is_active' => true,
            ],
            [
                'name' => 'Berlin Mitte Hub',
                'postcode' => '10115',
                'city' => 'Berlin',
                'state' => 'Berlin',
                'delivery_time_minutes' => 20,
                'minimum_order_amount' => 1000,
                'shipping_fee' => 249,
                'is_active' => true,
            ],
        ];

        $createdAreas = [];
        foreach ($deliveryAreas as $areaData) {
            $createdAreas[] = DeliveryArea::updateOrCreate(
                ['postcode' => $areaData['postcode']],
                $areaData
            );
        }

        // 10. Categories / Collections
        $categoriesData = [
            [
                'name' => 'Fresh Fruits & Vegetables',
                'slug' => 'fresh-produce',
                'description' => 'Farm-fresh daily harvest vegetables, fresh greens, organic herbs, and seasonal orchard fruits direct from growers.',
                'image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Dairy, Eggs & Bakery',
                'slug' => 'dairy-bakery',
                'description' => 'Pure cow milk, traditional hand-churned ghee, fresh cottage cheese (paneer), free-range farm eggs, and artisanal breads.',
                'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Pantry Staples, Rice & Dal',
                'slug' => 'pantry-staples',
                'description' => 'Aged aromatic Royal Basmati rice, unpolished organic pulses, whole wheat chakki atta, and essential kitchen staples in retail & bulk packs.',
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Spices, Masalas & Oils',
                'slug' => 'spices-oils',
                'description' => 'Authentic whole aromatic spices, pure stone-ground spice powders, cold-pressed mustard & sesame oils, and premium blended masalas.',
                'image' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Beverages, Chai & Coffee',
                'slug' => 'beverages',
                'description' => 'Assam & Darjeeling loose-leaf tea, roasted plantation filter coffees, natural tender coconut water, and traditional summer drinks.',
                'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Snacks, Namkeen & Sweets',
                'slug' => 'snacks-sweets',
                'description' => 'Crispy savory namkeens, authentic roasted dry-fruit snacks, traditional festive sweets, and wholesome tea-time biscuits.',
                'image' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $collections = [];
        foreach ($categoriesData as $cat) {
            $collection = Collection::whereHas('urls', function ($q) use ($cat) {
                $q->where('slug', $cat['slug']);
            })->first();

            if (! $collection) {
                $collection = Collection::create([
                    'collection_group_id' => $collectionGroup->id,
                    'attribute_data' => collect([
                        'name' => new TranslatedText(collect(['en' => $cat['name']])),
                        'description' => new TranslatedText(collect(['en' => $cat['description']])),
                    ]),
                ]);

                Url::create([
                    'language_id' => $language->id,
                    'element_type' => Collection::morphName(),
                    'element_id' => $collection->id,
                    'slug' => $cat['slug'],
                    'default' => true,
                ]);

                $collection->channels()->syncWithoutDetaching([$channel->id]);
                $collection->customerGroups()->syncWithoutDetaching([$retailGroup->id, $wholesaleGroup->id]);
            }

            $collections[$cat['slug']] = $collection;
        }

        // 11. 24 Products Catalog with Retail, Bulk Tiers, Quick Shop
        $productsData = [
            // Category: Fresh Fruits & Vegetables
            [
                'name' => 'Ratnagiri Alphonso Mangoes (Box of 6)',
                'slug' => 'ratnagiri-alphonso-mangoes-box-6',
                'category' => 'fresh-produce',
                'description' => 'Directly sourced from solar-nurtured coastal orchards of Ratnagiri. Sweet, fiberless, rich golden saffron pulp with unforgettable aroma. Naturally ripened with zero chemical carbide.',
                'sku' => 'FRT-MANGO-001',
                'stock' => 120,
                'price' => 1499, // 14.99 EUR
                'compare_price' => 1899,
                'bulk_price_tier1' => 1299, // 5+ units
                'bulk_price_tier2' => 1149, // 10+ units
                'unit_of_measure' => 'box',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Crate of 5 Boxes (30 Mangoes)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'GI Tagged & Farm Direct',
                'origin_details' => 'Sourced from Devgad & Ratnagiri Coastal Farms, Maharashtra.',
                'image_url' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Crisp Royal Gala Apples (1kg Pack)',
                'slug' => 'crisp-royal-gala-apples-1kg',
                'category' => 'fresh-produce',
                'description' => 'Crispy, sweet, and juicy hand-sorted apples with radiant red blush. Wax-free and washed with filtered water, ready for immediate snacking or fresh morning juice.',
                'sku' => 'FRT-APPLE-002',
                'stock' => 250,
                'price' => 299, // 2.99 EUR
                'compare_price' => 379,
                'bulk_price_tier1' => 249,
                'bulk_price_tier2' => 219,
                'unit_of_measure' => 'kg',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Wholesale Carton of 10kg',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Bestseller Produce',
                'origin_details' => 'Handpicked from mountain orchards in Kinnaur, Himachal.',
                'image_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Organic Baby Spinach / Palak (500g Bunch)',
                'slug' => 'organic-baby-spinach-palak-500g',
                'category' => 'fresh-produce',
                'description' => 'Tender, nutrient-dense hydroponically cultivated spinach leaves. Pre-trimmed, crisp, free from mud residue, packed in breathable biodegradable bags.',
                'sku' => 'VEG-SPIN-003',
                'stock' => 90,
                'price' => 179, // 1.79 EUR
                'compare_price' => 220,
                'bulk_price_tier1' => 149,
                'bulk_price_tier2' => 129,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => false,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Bundle of 5 Packs',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => '10-Min Express Harvest',
                'origin_details' => 'Harvested 3 hours ago from Local Peri-Urban Greenhouse.',
                'image_url' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Farm Fresh Vine Country Tomatoes (1kg)',
                'slug' => 'vine-ripened-country-tomatoes-1kg',
                'category' => 'fresh-produce',
                'description' => 'Naturally tangy, firm, and juicy country tomatoes with thick red skin. Ideal for Indian curries, rich Italian marinara sauces, and fresh salads.',
                'sku' => 'VEG-TOM-004',
                'stock' => 300,
                'price' => 189, // 1.89 EUR
                'compare_price' => 240,
                'bulk_price_tier1' => 159,
                'bulk_price_tier2' => 139,
                'unit_of_measure' => 'kg',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Farmers Wooden Crate (15kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Daily Kitchen Essential',
                'origin_details' => 'Sourced directly from Nashik Valley farmers collective.',
                'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Organic Nasik Red Onions (5kg Net Bag)',
                'slug' => 'organic-nasik-red-onions-5kg',
                'category' => 'fresh-produce',
                'description' => 'Pungent, dry-cured red onions known for long kitchen shelf life and high cooking flavor profile. Delivered in breathable mesh bags.',
                'sku' => 'VEG-ONION-005',
                'stock' => 180,
                'price' => 549, // 5.49 EUR
                'compare_price' => 699,
                'bulk_price_tier1' => 479,
                'bulk_price_tier2' => 419,
                'unit_of_measure' => 'bag',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Wholesale Sack of 30kg (6x5kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 20,
                'badge_text' => 'Bulk Savings Choice',
                'origin_details' => 'Graded and sun-cured at Lasalgaon Mandi, Maharashtra.',
                'image_url' => 'https://images.unsplash.com/photo-1508747703725-719777637510?auto=format&fit=crop&w=600&q=80',
            ],

            // Category: Dairy, Eggs & Bakery
            [
                'name' => 'Amul Pure Cow Ghee (1 Litre Tin)',
                'slug' => 'amul-pure-cow-ghee-1-litre',
                'category' => 'dairy-bakery',
                'description' => 'Golden granular clarified cow butter made with age-old standards. Rich aromatic nutty fragrance, high smoke point, ideal for religious offerings and everyday cooking.',
                'sku' => 'DRY-GHEE-006',
                'stock' => 140,
                'price' => 1199, // 11.99 EUR
                'compare_price' => 1350,
                'bulk_price_tier1' => 1049,
                'bulk_price_tier2' => 979,
                'unit_of_measure' => 'litre',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Carton of 6 Litres',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Heritage Kitchen Choice',
                'origin_details' => 'Gujarat Co-operative Milk Marketing Federation (GCMMF), Anand.',
                'image_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Fresh Malai Paneer Block (400g Vacuum Pack)',
                'slug' => 'fresh-malai-paneer-block-400g',
                'category' => 'dairy-bakery',
                'description' => 'Ultra-soft, melt-in-mouth cottage cheese crafted from rich buffalo and cow milk. High in natural protein, holds shape during grilling or gravy simmer.',
                'sku' => 'DRY-PAN-007',
                'stock' => 85,
                'price' => 389, // 3.89 EUR
                'compare_price' => 450,
                'bulk_price_tier1' => 329,
                'bulk_price_tier2' => 299,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Case of 10 Vacuum Packs (4kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => 'Chilled 15-Min Delivery',
                'origin_details' => 'Daily small-batch fresh dairy batch.',
                'image_url' => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Country Free-Range Brown Eggs (Pack of 12)',
                'slug' => 'country-free-range-brown-eggs-12',
                'category' => 'dairy-bakery',
                'description' => 'Certified organic grain-fed hen eggs with deep golden yolk. Packed in shockproof recycled pulp cartons to ensure zero transit damage.',
                'sku' => 'DRY-EGG-008',
                'stock' => 160,
                'price' => 349, // 3.49 EUR
                'compare_price' => 420,
                'bulk_price_tier1' => 299,
                'bulk_price_tier2' => 269,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 8,
                'bulk_package_type' => 'Crate of 8 Cartons (96 Eggs)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'High Protein Certified',
                'origin_details' => 'Humane certified pasture-raised poultry farm.',
                'image_url' => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Artisanal Whole Wheat Sourdough Bread (500g)',
                'slug' => 'artisanal-whole-wheat-sourdough-500g',
                'category' => 'dairy-bakery',
                'description' => 'Slow 36-hour wild yeast cold fermentation. Crunchy caramelized crust and airy, soft interior with mild tang. Naturally preserved with olive oil.',
                'sku' => 'BAK-BRD-009',
                'stock' => 70,
                'price' => 289, // 2.89 EUR
                'compare_price' => 340,
                'bulk_price_tier1' => 239,
                'bulk_price_tier2' => 209,
                'unit_of_measure' => 'loaf',
                'is_retail' => true,
                'is_bulk' => false,
                'bulk_min_quantity' => 4,
                'bulk_package_type' => 'Pack of 4 Loaves',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Baked Fresh Every 4 Hours',
                'origin_details' => 'Handcrafted by our master baker community partner.',
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
            ],

            // Category: Pantry Staples, Rice & Dal
            [
                'name' => 'India Gate Classic Royal Aged Basmati Rice (5kg Bag)',
                'slug' => 'india-gate-classic-aged-basmati-rice-5kg',
                'category' => 'pantry-staples',
                'description' => 'Aged for two years in Himalayan climate warehouses. Extra-long pearl grains that elongate to double length upon cooking without stickiness. Ideal for Biryanis and fragrant Pilafs.',
                'sku' => 'GRN-RICE-010',
                'stock' => 220,
                'price' => 1649, // 16.49 EUR
                'compare_price' => 1999,
                'bulk_price_tier1' => 1449,
                'bulk_price_tier2' => 1299,
                'unit_of_measure' => 'bag',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 4,
                'bulk_package_type' => 'Master Sack of 20kg (4x5kg Bags)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 25,
                'badge_text' => '2-Year Aged Super Grain',
                'origin_details' => 'Grown in snow-fed Tarai plains of Punjab and Haryana.',
                'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Aashirvaad Shudh Chakki Whole Wheat Atta (10kg Bag)',
                'slug' => 'aashirvaad-shudh-chakki-whole-wheat-atta-10kg',
                'category' => 'pantry-staples',
                'description' => 'Made from golden heavy grains of Madhya Pradesh Sehore wheat. 100% whole grain stone-ground chakki flour retaining natural dietary fiber and germ nutrition for pillow-soft rotis.',
                'sku' => 'GRN-ATTA-011',
                'stock' => 190,
                'price' => 1299, // 12.99 EUR
                'compare_price' => 1499,
                'bulk_price_tier1' => 1149,
                'bulk_price_tier2' => 1029,
                'unit_of_measure' => 'bag',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Pallet Bundle of 5x10kg (50kg)',
                'is_quick_commerce' => false,
                'quick_delivery_minutes' => 45,
                'badge_text' => 'Heavy Weight Wholesale Deal',
                'origin_details' => 'Sourced from Sehore Sharbati wheat belt, MP.',
                'image_url' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Organic Unpolished Toor / Arhar Dal (1kg Pouch)',
                'slug' => 'organic-unpolished-toor-arhar-dal-1kg',
                'category' => 'pantry-staples',
                'description' => 'No oil polish, no artificial coloring or water tampering. Rich natural golden lentils that cook quickly into creamy, fragrant everyday Indian comforting dal.',
                'sku' => 'PUL-TOOR-012',
                'stock' => 280,
                'price' => 329, // 3.29 EUR
                'compare_price' => 399,
                'bulk_price_tier1' => 279,
                'bulk_price_tier2' => 249,
                'unit_of_measure' => 'kg',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Wholesale Carton of 10 Pouches',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Unpolished Nutrition',
                'origin_details' => 'Produced by certified organic farmer cooperative in Latur.',
                'image_url' => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Organic Moong Dal Yellow Split (1kg Pouch)',
                'slug' => 'organic-moong-dal-yellow-split-1kg',
                'category' => 'pantry-staples',
                'description' => 'Light, easy to digest, high-protein split yellow mung beans. Perfect for wholesome khichdi, detox soups, and traditional Moong Dal halwa.',
                'sku' => 'PUL-MOONG-013',
                'stock' => 195,
                'price' => 319, // 3.19 EUR
                'compare_price' => 379,
                'bulk_price_tier1' => 269,
                'bulk_price_tier2' => 239,
                'unit_of_measure' => 'kg',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 8,
                'bulk_package_type' => 'Carton of 8 Pouches (8kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Easy Digest Superfood',
                'origin_details' => 'Rajasthan rainfed pulses cluster.',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
            ],

            // Category: Spices, Masalas & Oils
            [
                'name' => 'Cold-Pressed Mustard Oil Kachi Ghani (1 Litre Bottle)',
                'slug' => 'cold-pressed-mustard-oil-kachi-ghani-1l',
                'category' => 'spices-oils',
                'description' => 'Extracted through traditional wooden kohlu cold press mechanism. Pungent natural aroma, high smoke point, ideal for pickling, Bengali fish curries, and winter cooking.',
                'sku' => 'OIL-MUST-014',
                'stock' => 150,
                'price' => 429, // 4.29 EUR
                'compare_price' => 520,
                'bulk_price_tier1' => 369,
                'bulk_price_tier2' => 329,
                'unit_of_measure' => 'bottle',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Box of 6 Bottles (6 Litres)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Wood-Pressed Pungency',
                'origin_details' => 'First-press yellow mustard seeds from Bharatpur, Rajasthan.',
                'image_url' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Fortune Sunlite Refined Sunflower Oil (5 Litre Can)',
                'slug' => 'fortune-sunlite-sunflower-oil-5l',
                'category' => 'spices-oils',
                'description' => 'Light, clear, and fortified with Vitamin A and D. Non-greasy frying profile with zero cholesterol. Ergonomic handle can for smooth daily kitchen pouring.',
                'sku' => 'OIL-SUN-015',
                'stock' => 130,
                'price' => 1349, // 13.49 EUR
                'compare_price' => 1599,
                'bulk_price_tier1' => 1199,
                'bulk_price_tier2' => 1099,
                'unit_of_measure' => 'can',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 4,
                'bulk_package_type' => 'Crate of 4 Cans (20 Litres)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 20,
                'badge_text' => 'Family Pack Economy',
                'origin_details' => 'Refined and quality-sealed by Adani Wilmar facilities.',
                'image_url' => 'https://images.unsplash.com/photo-1620706857370-e1b9770e8bb1?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Catch Stemless Kashmiri Red Chilli Powder (200g)',
                'slug' => 'catch-kashmiri-red-chilli-powder-200g',
                'category' => 'spices-oils',
                'description' => 'Imparts vibrant bright crimson red hue to gravies and tandoori grills without excessive burning heat. Low-temperature ground to preserve natural capsanthin pigments.',
                'sku' => 'SPC-CHIL-016',
                'stock' => 210,
                'price' => 269, // 2.69 EUR
                'compare_price' => 310,
                'bulk_price_tier1' => 219,
                'bulk_price_tier2' => 189,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 12,
                'bulk_package_type' => 'Retail Box of 12 Packs (2.4kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => 'Vibrant Natural Color',
                'origin_details' => 'Handpicked Kashmiri red chillies.',
                'image_url' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Everest Royal Garam Masala Blend (100g Box)',
                'slug' => 'everest-royal-garam-masala-blend-100g',
                'category' => 'spices-oils',
                'description' => 'Exquisite blend of 13 whole aromatic spices: green cardamom, black mace, cloves, star anise, nutmeg, and cinnamon. Adds instant royal restaurant fragrance to curries.',
                'sku' => 'SPC-MAS-017',
                'stock' => 340,
                'price' => 189, // 1.89 EUR
                'compare_price' => 230,
                'bulk_price_tier1' => 159,
                'bulk_price_tier2' => 139,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 12,
                'bulk_package_type' => 'Wholesale Carton of 24 Boxes',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => 'Secret Chef Blend',
                'origin_details' => 'Blended under ISO certified hygienic facilities.',
                'image_url' => 'https://images.unsplash.com/photo-1532336414038-cf19250c5757?auto=format&fit=crop&w=600&q=80',
            ],

            // Category: Beverages, Chai & Coffee
            [
                'name' => 'Wagh Bakri Premium CTC Loose Tea (1kg Pack)',
                'slug' => 'wagh-bakri-premium-ctc-tea-1kg',
                'category' => 'beverages',
                'description' => 'Specially blended strong CTC tea granules from upper Assam gardens. Yields deep malty liquor, robust color, and invigorating aroma with fresh milk and crushed ginger.',
                'sku' => 'BEV-TEA-018',
                'stock' => 175,
                'price' => 699, // 6.99 EUR
                'compare_price' => 850,
                'bulk_price_tier1' => 599,
                'bulk_price_tier2' => 529,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Master Carton of 6 Packs (6kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'True Indian Kadak Chai',
                'origin_details' => 'Assam & Dooars tea gardens.',
                'image_url' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Bru Gold 100% Pure Instant Coffee (100g Glass Jar)',
                'slug' => 'bru-gold-pure-instant-coffee-100g',
                'category' => 'beverages',
                'description' => 'Agglomerated freeze-dried granules made from fine blend of South Indian Arabica and Robusta beans. Deep roasted aroma with thick crema layer.',
                'sku' => 'BEV-COF-019',
                'stock' => 140,
                'price' => 449, // 4.49 EUR
                'compare_price' => 520,
                'bulk_price_tier1' => 389,
                'bulk_price_tier2' => 349,
                'unit_of_measure' => 'jar',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Display Tray of 10 Glass Jars',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Rich Roasted Aromatics',
                'origin_details' => 'Plantations of Coorg and Chikmagalur, Karnataka.',
                'image_url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Pure Tender Coconut Water (Pack of 6 x 200ml)',
                'slug' => 'pure-tender-coconut-water-pack-6',
                'category' => 'beverages',
                'description' => '100% natural, isotonic hydration with zero added sugar or preservatives. Packed under bio-aseptic technology to preserve natural electrolytes and potassium freshness.',
                'sku' => 'BEV-COC-020',
                'stock' => 200,
                'price' => 499, // 4.99 EUR
                'compare_price' => 600,
                'bulk_price_tier1' => 419,
                'bulk_price_tier2' => 379,
                'unit_of_measure' => 'pack',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Wholesale Carton of 5 Packs (30 Units)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => 'Pure Natural Electrolytes',
                'origin_details' => 'Harvested from green coconut groves of Pollachi, Tamil Nadu.',
                'image_url' => 'https://images.unsplash.com/photo-1544253106-258385e05a30?auto=format&fit=crop&w=600&q=80',
            ],

            // Category: Snacks, Namkeen & Sweets
            [
                'name' => 'Haldirams Nagpur Aloo Bhujia Namkeen (400g Pouch)',
                'slug' => 'haldirams-nagpur-aloo-bhujia-400g',
                'category' => 'snacks-sweets',
                'description' => 'The quintessential Indian tea-time crunch. Crispy, spicy potato and moth bean gram noodles seasoned with aromatic mint, ginger, and signature spice dust.',
                'sku' => 'SNK-BHUJ-021',
                'stock' => 280,
                'price' => 249, // 2.49 EUR
                'compare_price' => 299,
                'bulk_price_tier1' => 199,
                'bulk_price_tier2' => 169,
                'unit_of_measure' => 'pouch',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 12,
                'bulk_package_type' => 'Carton of 12 Pouches (4.8kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 10,
                'badge_text' => 'Most Popular Munchie',
                'origin_details' => 'Haldiram Foods International, Nagpur.',
                'image_url' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Parle-G Gold Glucose Biscuits (Wholesale Box of 24)',
                'slug' => 'parle-g-gold-glucose-biscuits-box-24',
                'category' => 'snacks-sweets',
                'description' => 'India\'s beloved nostalgic chai dunking biscuit, enriched with wholesome wheat, milk, and glucose energy. Thicker, crunchier Gold edition.',
                'sku' => 'SNK-PARLE-022',
                'stock' => 160,
                'price' => 599, // 5.99 EUR
                'compare_price' => 750,
                'bulk_price_tier1' => 499,
                'bulk_price_tier2' => 439,
                'unit_of_measure' => 'box',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Master Shipper of 6 Boxes (144 packets)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'The National Tea Dunker',
                'origin_details' => 'Parle Products, Vile Parle, Mumbai.',
                'image_url' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Bikaji Royal Bikaneri Gulab Jamun (1kg Tin)',
                'slug' => 'bikaji-royal-gulab-jamun-1kg',
                'category' => 'snacks-sweets',
                'description' => 'Melt-in-the-mouth soft cottage cheese and milk khoya spheres dipped in fragrant saffron, cardamom, and rose water sugar syrup. Ideal for celebrations.',
                'sku' => 'SWT-GULAB-023',
                'stock' => 110,
                'price' => 649, // 6.49 EUR
                'compare_price' => 799,
                'bulk_price_tier1' => 549,
                'bulk_price_tier2' => 489,
                'unit_of_measure' => 'tin',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Gift Shipper of 6 Tins (6kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Festive Dessert Favorite',
                'origin_details' => 'Bikaji Foods, Bikaner, Rajasthan.',
                'image_url' => 'https://images.unsplash.com/photo-1541832676-9b763b0239ab?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Roasted Salted California Almonds (500g Zip Pouch)',
                'slug' => 'roasted-salted-california-almonds-500g',
                'category' => 'snacks-sweets',
                'description' => 'Nonpareil jumbo supreme almonds dry roasted with rock salt. Super crunchy, high in natural Vitamin E, magnesium, and healthy monosaturated fats.',
                'sku' => 'DRY-ALM-024',
                'stock' => 140,
                'price' => 789, // 7.89 EUR
                'compare_price' => 950,
                'bulk_price_tier1' => 689,
                'bulk_price_tier2' => 599,
                'unit_of_measure' => 'pouch',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 6,
                'bulk_package_type' => 'Carton of 6 Zip Pouches (3kg)',
                'is_quick_commerce' => true,
                'quick_delivery_minutes' => 15,
                'badge_text' => 'Heart Healthy Snack',
                'origin_details' => 'Imported and nitrogen vacuum packed for maximum crunch.',
                'image_url' => 'https://images.unsplash.com/photo-1508061252445-5350f37def0e?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::whereHas('urls', function ($q) use ($pData) {
                $q->where('slug', $pData['slug']);
            })->first();

            if (! $product) {
                $product = Product::create([
                    'product_type_id' => $productType->id,
                    'status' => 'published',
                    'attribute_data' => collect([
                        'name' => new TranslatedText(collect(['en' => $pData['name']])),
                        'description' => new TranslatedText(collect(['en' => $pData['description']])),
                    ]),
                ]);

                // Create Product URL
                Url::create([
                    'language_id' => $language->id,
                    'element_type' => Product::morphName(),
                    'element_id' => $product->id,
                    'slug' => $pData['slug'],
                    'default' => true,
                ]);

                // Attach Channel & Customer Groups
                $product->channels()->syncWithoutDetaching([$channel->id]);
                $product->customerGroups()->syncWithoutDetaching([$retailGroup->id, $wholesaleGroup->id]);

                // Attach to Collection
                if (isset($collections[$pData['category']])) {
                    $product->collections()->syncWithoutDetaching([
                        $collections[$pData['category']]->id => ['position' => 1],
                    ]);
                }

                // Create Primary Variant
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'tax_class_id' => $taxClass->id,
                    'sku' => $pData['sku'],
                    'unit_quantity' => 1,
                    'min_quantity' => 1,
                    'quantity_increment' => 1,
                    'stock' => $pData['stock'],
                    'backorder' => 0,
                    'purchasable' => 'in_stock',
                    'shippable' => true,
                ]);

                // Base Retail Price
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['price'],
                    'compare_price' => $pData['compare_price'],
                    'min_quantity' => 1,
                ]);

                // Bulk Tier 1 Price (min_quantity 5)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['bulk_price_tier1'],
                    'compare_price' => null,
                    'min_quantity' => 5,
                ]);

                // Bulk Tier 2 Price (min_quantity 10)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['bulk_price_tier2'],
                    'compare_price' => null,
                    'min_quantity' => 10,
                ]);

                // USD Price
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $usd->id,
                    'customer_group_id' => null,
                    'price' => (int) round($pData['price'] * 1.08),
                    'compare_price' => (int) round($pData['compare_price'] * 1.08),
                    'min_quantity' => 1,
                ]);
            }

            // Create or update Product Metadata
            ProductMetadata::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'unit_of_measure' => $pData['unit_of_measure'],
                    'is_retail' => $pData['is_retail'],
                    'is_bulk' => $pData['is_bulk'],
                    'bulk_min_quantity' => $pData['bulk_min_quantity'],
                    'bulk_package_type' => $pData['bulk_package_type'],
                    'is_quick_commerce' => $pData['is_quick_commerce'],
                    'quick_delivery_minutes' => $pData['quick_delivery_minutes'],
                    'badge_text' => $pData['badge_text'],
                    'origin_details' => $pData['origin_details'],
                    'image_url' => $pData['image_url'],
                ]
            );
        }

        // 12. Sample Discount Coupons
        $discounts = [
            [
                'name' => '10% Welcome Discount',
                'handle' => 'welcome10',
                'coupon' => 'WELCOME10',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'data' => [
                    'fixed_value' => false,
                    'percentage' => 10,
                ],
            ],
            [
                'name' => 'Bulk Saver €15 Off',
                'handle' => 'bulk15',
                'coupon' => 'BULK15',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'data' => [
                    'fixed_value' => true,
                    'fixed_values' => [
                        'EUR' => 1500,
                        'USD' => 1600,
                        'INR' => 135000,
                    ],
                ],
            ],
            [
                'name' => 'Super Saver 5% Off',
                'handle' => 'save5',
                'coupon' => 'SAVE5',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'data' => [
                    'fixed_value' => false,
                    'percentage' => 5,
                ],
            ],
        ];

        foreach ($discounts as $dData) {
            $discount = Discount::firstOrCreate(
                ['handle' => $dData['handle']],
                [
                    'name' => $dData['name'],
                    'coupon' => $dData['coupon'],
                    'type' => AmountOff::class,
                    'starts_at' => $dData['starts_at'],
                    'ends_at' => $dData['ends_at'],
                    'uses' => 0,
                    'priority' => 1,
                    'stop' => false,
                    'data' => $dData['data'],
                ]
            );

            $discount->channels()->syncWithoutDetaching([$channel->id]);
            $discount->customerGroups()->syncWithoutDetaching([$retailGroup->id, $wholesaleGroup->id]);
        }
    }
}

