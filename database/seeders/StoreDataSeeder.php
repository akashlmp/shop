<?php

namespace Database\Seeders;

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
        // 0. Ensure Countries and States are imported
        if (Country::count() === 0) {
            $this->command?->info('Importing Lunar address data (countries & states)...');
            \Illuminate\Support\Facades\Artisan::call('lunar:import:address-data');
        }

        // 0.1 Ensure Staff admin exists
        if (\Lunar\Admin\Models\Staff::count() === 0) {
            \Lunar\Admin\Models\Staff::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@acelectronics.com',
                'password' => \Illuminate\Support\Facades\Hash::make('AdminPassword123!'),
                'admin' => true,
            ]);
        }

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

        // 4. Customer Groups
        $retailGroup = CustomerGroup::firstOrCreate(
            ['handle' => 'retail'],
            [
                'name' => 'Retail Customer',
                'default' => true,
            ]
        );

        $wholesaleGroup = CustomerGroup::firstOrCreate(
            ['handle' => 'wholesale'],
            [
                'name' => 'B2B Wholesale / Distributor',
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
            ['name' => 'Consumer Electronics & Hardware'],
        );
        $productType->mappedAttributes()->syncWithoutDetaching([$productNameAttr->id, $productDescAttr->id]);

        // 9. 7 Electronics Categories / Collections
        $categoriesData = [
            [
                'name' => 'TWS Earbuds & True Wireless',
                'slug' => 'tws-earbuds',
                'description' => 'Compact Bluetooth 5.3 and 5.4 wireless earbuds featuring low latency gaming, Jerry and Bluetrum chipsets, and ergonomic charging cases.',
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Active Noise Cancelling (ANC)',
                'slug' => 'anc-audio',
                'description' => 'Flagship Hybrid ANC in-ear and over-ear headphones with up to -40dB depth, ambient transparency modes, and Realtek DSP audio engines.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Open-Ear (OWS) & Sports Audio',
                'slug' => 'open-ear-sports',
                'description' => 'Non-intrusive directional open-ear air conduction earphones and titanium earclip headsets designed for comfort, situational awareness, and sport.',
                'image' => 'https://images.unsplash.com/photo-1577174881658-0f30ed549adc?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Over-Ear & Gaming Headsets',
                'slug' => 'gaming-over-ear',
                'description' => 'Immersive 50mm neodymium driver headsets with RGB lighting, 7.1 virtual surround sound, noise-isolating boom mics, and up to 50h battery life.',
                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Smartwatches & Fitness Wearables',
                'slug' => 'smartwatches-wearables',
                'description' => 'AMOLED touch display smartwatches, zinc-alloy Bluetooth calling wearables, IP68 health monitors, and 24/7 screenless biometric bands.',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Computer Mice & Peripherals',
                'slug' => 'computer-mice',
                'description' => 'High-precision 2.4GHz wireless and dual-mode Bluetooth 5.2 optical mice with silent switches, ergonomic grip, and adjustable DPI.',
                'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=700&q=80',
            ],
            [
                'name' => 'Smart Scales & Diagnostic Tools',
                'slug' => 'smart-scales',
                'description' => 'High-precision ITO glass body composition scales with Fitdays app synchronization, 8-electrode segmental analysis, and VA color displays.',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=700&q=80',
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

        // 10. Electronics Product Catalog (Derived from Lenovo & AOC PDF Catalogs)
        $productsData = [
            // ================= Lenovo TWS & Audio Series =================
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA110',
                'name' => 'Lenovo EA110 True Wireless In-Ear Earbuds',
                'slug' => 'lenovo-ea110-tws-earbuds',
                'category' => 'tws-earbuds',
                'description' => 'Lenovo EA110 delivers crisp high-fidelity acoustic performance powered by a Jerry 6983D Bluetooth 5.3 chipset and 13mm composite diaphragm. Features touch control, fast Type-C charging, and up to 20 hours combined playback.',
                'sku' => 'LEN-EA110',
                'stock' => 1500,
                'price' => 799, // €7.99 Retail
                'compare_price' => 1299,
                'bulk_price_tier1' => 599, // 5+ units
                'bulk_price_tier2' => 450, // 20+ units (half carton)
                'bulk_price_tier3' => 370, // 100+ units (full carton ~29 RMB base)
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983D (BT 5.3)',
                'battery_specs' => '30mAh Earbud / 250mAh Case',
                'carton_specs' => '100 pcs/ctn | 48×32×26 cm | GW: 7.8 kg',
                'specs' => [
                    'Bluetooth Version' => '5.3',
                    'Horn Size' => '13mm Composite Diaphragm',
                    'Charging Port' => 'Type-C USB',
                    'Playtime' => '4.5 Hours (Single Charge)',
                    'Transmission Distance' => '10m',
                ],
                'colors' => ['Black', 'White'],
                'badge_text' => 'Lenovo Genuine Audio',
                'image_url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA166',
                'name' => 'Lenovo EA166 Semi-In-Ear Wireless Earbuds',
                'slug' => 'lenovo-ea166-semi-in-ear-earbuds',
                'category' => 'tws-earbuds',
                'description' => 'Ultra-lightweight semi-in-ear design tailored for all-day comfortable listening. Equipped with Bluetooth 5.3, instantaneous hall-sensor auto pairing, and clean vocal clarity.',
                'sku' => 'LEN-EA166',
                'stock' => 2000,
                'price' => 699,
                'compare_price' => 1099,
                'bulk_price_tier1' => 549,
                'bulk_price_tier2' => 420,
                'bulk_price_tier3' => 330, // 26 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983D (BT 5.3)',
                'battery_specs' => '25mAh Earbud / 200mAh Case',
                'carton_specs' => '100 pcs/ctn | 46×30×24 cm | GW: 6.9 kg',
                'specs' => [
                    'Bluetooth Version' => '5.3',
                    'Weight per Earbud' => '3.2g Featherweight',
                    'Charging Time' => '1.2 Hours',
                    'Frequency Range' => '20Hz - 20kHz',
                ],
                'colors' => ['Black', 'White', 'Purple'],
                'badge_text' => 'Featherweight Comfort',
                'image_url' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA175',
                'name' => 'Lenovo EA175 Compact TWS Earphones',
                'slug' => 'lenovo-ea175-compact-tws-earphones',
                'category' => 'tws-earbuds',
                'description' => 'Budget-friendly performance audio. Jerry 6983D2 core with low power consumption, intelligent noise isolation earmuffs, and deep bass resonance.',
                'sku' => 'LEN-EA175',
                'stock' => 1200,
                'price' => 599,
                'compare_price' => 999,
                'bulk_price_tier1' => 479,
                'bulk_price_tier2' => 370,
                'bulk_price_tier3' => 280, // 22 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983D2 (BT 5.3)',
                'battery_specs' => '25mAh Earbud / 250mAh Case',
                'carton_specs' => '100 pcs/ctn | 45×31×25 cm | GW: 7.1 kg',
                'specs' => [
                    'Bluetooth Version' => '5.3',
                    'Driver Unit' => '10mm Dynamic',
                    'Standby Time' => '180 Hours',
                ],
                'colors' => ['Black', 'White'],
                'badge_text' => 'High Value Pick',
                'image_url' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA186',
                'name' => 'Lenovo EA186 Pocket Wireless Stereo Earbuds',
                'slug' => 'lenovo-ea186-pocket-wireless-earbuds',
                'category' => 'tws-earbuds',
                'description' => 'Pencil-case compact profile with frosted anti-scratch exterior. Smooth silicone finish, sensitive touch sensors, and reliable connectivity.',
                'sku' => 'LEN-EA186',
                'stock' => 2500,
                'price' => 599,
                'compare_price' => 899,
                'bulk_price_tier1' => 450,
                'bulk_price_tier2' => 350,
                'bulk_price_tier3' => 270, // 21 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983 (BT 5.3)',
                'battery_specs' => '25mAh Earbud / 200mAh Case',
                'carton_specs' => '100 pcs/ctn | 44×30×22 cm | GW: 6.5 kg',
                'specs' => [
                    'Bluetooth Version' => '5.3',
                    'Microphone' => 'Omnidirectional Built-In',
                    'Water Resistance' => 'IPX4 Splashproof',
                ],
                'colors' => ['Black', 'White', 'Pink'],
                'badge_text' => 'Bestseller Model',
                'image_url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA189',
                'name' => 'Lenovo EA189 Cyber Transparent Mecha TWS',
                'slug' => 'lenovo-ea189-cyber-transparent-tws',
                'category' => 'tws-earbuds',
                'description' => 'Striking futuristic transparent housing revealing internal precision circuitry. Bluetrum Bluetooth 5.3 high-speed processor, breathing ambient lighting, and rich punchy bass.',
                'sku' => 'LEN-EA189',
                'stock' => 950,
                'price' => 999,
                'compare_price' => 1599,
                'bulk_price_tier1' => 799,
                'bulk_price_tier2' => 600,
                'bulk_price_tier3' => 450, // 35 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Bluetrum High-Spec (BT 5.3)',
                'battery_specs' => '30mAh Earbud / 300mAh Case',
                'carton_specs' => '100 pcs/ctn | 47×33×26 cm | GW: 8.2 kg',
                'specs' => [
                    'Design' => 'Clear Cyberpunk Shell',
                    'Lighting' => 'Ambient Cyan LED',
                    'Latency' => '55ms Ultra-Low Latency',
                ],
                'colors' => ['Cyber Black', 'Frost White', 'Solar Orange'],
                'badge_text' => 'Cyber Edition',
                'image_url' => 'https://images.unsplash.com/photo-1629367494173-c78a56567877?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA230P',
                'name' => 'Lenovo EA230P Pro Dual-Mic ENC Wireless Earbuds',
                'slug' => 'lenovo-ea230p-pro-enc-earbuds',
                'category' => 'tws-earbuds',
                'description' => 'Upgraded Pro version featuring Jerry 6973D4 with Dual-Microphone Environmental Noise Cancellation (ENC) for crystal clear mobile calls even in busy transit.',
                'sku' => 'LEN-EA230P',
                'stock' => 1800,
                'price' => 749,
                'compare_price' => 1249,
                'bulk_price_tier1' => 579,
                'bulk_price_tier2' => 450,
                'bulk_price_tier3' => 350, // 27 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6973D4 (BT 5.4)',
                'battery_specs' => '30mAh Earbud / 250mAh Case',
                'carton_specs' => '100 pcs/ctn | 48×31×25 cm | GW: 7.6 kg',
                'specs' => [
                    'Noise Cancellation' => 'Dual-Mic AI ENC Call Noise Reduction',
                    'Bluetooth Version' => '5.4 Low Energy',
                    'Drivers' => '13mm Beryllium-Coated Horn',
                ],
                'colors' => ['Titanium Black', 'Pearl White', 'Sand Beige'],
                'badge_text' => 'ENC Clear Calling',
                'image_url' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'TA410',
                'name' => 'Lenovo TA410 OWS Earclip Directional Audio Earbuds',
                'slug' => 'lenovo-ta410-ows-earclip-earbuds',
                'category' => 'open-ear-sports',
                'description' => 'Next-generation Open-Ear Wearable Stereo (OWS). Clips securely to the ear pinna without blocking the ear canal. Delivers private directional acoustic beamforming with zero sound leakage.',
                'sku' => 'LEN-TA410',
                'stock' => 850,
                'price' => 1299,
                'compare_price' => 1899,
                'bulk_price_tier1' => 999,
                'bulk_price_tier2' => 780,
                'bulk_price_tier3' => 590, // 46 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 60 Pcs',
                'chipset' => 'Bluetrum 5656C (BT 5.4)',
                'battery_specs' => '35mAh Earbud / 300mAh Case',
                'carton_specs' => '60 pcs/ctn | 46×34×26 cm | GW: 6.2 kg',
                'specs' => [
                    'Acoustic Architecture' => 'Directional Sound Field Induction',
                    'Earclip Material' => 'Aviation Silicone Memory Bridge',
                    'Ear Canal Health' => 'Zero-Pressure Non-Intrusive',
                    'Continuous Play' => '6 Hours per charge',
                ],
                'colors' => ['Midnight Black', 'Ivory White', 'Natural Skin'],
                'badge_text' => 'OWS Earclip Innovation',
                'image_url' => 'https://images.unsplash.com/photo-1577174881658-0f30ed549adc?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EA420',
                'name' => 'Lenovo EA420 Open-Ear Sports Clip Earphones',
                'slug' => 'lenovo-ea420-open-ear-sports-earphones',
                'category' => 'open-ear-sports',
                'description' => 'Engineered for runners, cyclists, and fitness enthusiasts. Flexible TPU memory frame withstands rigorous movement while keeping the auditory canal free to hear ambient road traffic.',
                'sku' => 'LEN-EA420',
                'stock' => 920,
                'price' => 999,
                'compare_price' => 1499,
                'bulk_price_tier1' => 799,
                'bulk_price_tier2' => 620,
                'bulk_price_tier3' => 460, // 36 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 80 Pcs',
                'chipset' => 'Jerry 6983D (BT 5.4)',
                'battery_specs' => '30mAh Earbud / 250mAh Case',
                'carton_specs' => '80 pcs/ctn | 45×32×24 cm | GW: 6.8 kg',
                'specs' => [
                    'Water Resistance' => 'IPX5 Sweat & Rainproof',
                    'Driver' => '12mm Composite Driver',
                    'Clip Type' => 'C-Bridge Comfort Fit',
                ],
                'colors' => ['Black', 'White', 'Lilac'],
                'badge_text' => 'Sports Ergonomics',
                'image_url' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EH140',
                'name' => 'Lenovo EH140 Foldable Wireless Headband Headphones',
                'slug' => 'lenovo-eh140-foldable-wireless-headphones',
                'category' => 'gaming-over-ear',
                'description' => 'Compact on-ear foldable headphones with plush memory foam cushions. 40mm tuned drivers deliver broad frequency response. Perfect for commuting, study, and daily audiobooks.',
                'sku' => 'LEN-EH140',
                'stock' => 600,
                'price' => 999,
                'compare_price' => 1699,
                'bulk_price_tier1' => 799,
                'bulk_price_tier2' => 620,
                'bulk_price_tier3' => 460, // 36 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 40 Pcs',
                'chipset' => 'Jerry 7006 (BT 5.3)',
                'battery_specs' => '300mAh Polymer Lithium (20H Playtime)',
                'carton_specs' => '40 pcs/ctn | 58×42×38 cm | GW: 11.2 kg',
                'specs' => [
                    'Driver Diameter' => '40mm',
                    'Structure' => 'Multi-Angle Foldable Swivel',
                    'Playback Modes' => 'Bluetooth 5.3 + 3.5mm Aux Line-In',
                ],
                'colors' => ['Matte Black', 'Cream Beige', 'Khaki'],
                'badge_text' => 'Foldable Travel Headset',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'Lenovo',
                'model_code' => 'EH150',
                'name' => 'Lenovo EH150 Hi-Fi Studio Over-Ear Headphones',
                'slug' => 'lenovo-eh150-hi-fi-studio-headphones',
                'category' => 'gaming-over-ear',
                'description' => 'Flagship Lenovo studio headphones with 40mm bio-acoustic diaphragm drivers and soundproof protein leather over-ear cups. Features 30 hours battery endurance and zero-latency wired AUX bypass.',
                'sku' => 'LEN-EH150',
                'stock' => 550,
                'price' => 1699,
                'compare_price' => 2499,
                'bulk_price_tier1' => 1350,
                'bulk_price_tier2' => 1050,
                'bulk_price_tier3' => 780, // 61 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 30 Pcs',
                'chipset' => 'Jerry High-Res (BT 5.3)',
                'battery_specs' => '400mAh High-Density (30H Playback)',
                'carton_specs' => '30 pcs/ctn | 62×44×40 cm | GW: 12.5 kg',
                'specs' => [
                    'Sound Cavity' => 'Enclosed Acoustic Chamber',
                    'Impedance' => '32 Ohms',
                    'Frequency Response' => '15Hz - 24,000Hz',
                    'Audio Jack' => 'Gold-Plated 3.5mm',
                ],
                'colors' => ['Space Grey', 'Silver White', 'Onyx Black'],
                'badge_text' => 'Hi-Fi Acoustic Studio',
                'image_url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=600&q=80',
            ],

            // ================= AOC Audio & ANC Series =================
            [
                'brand' => 'AOC',
                'model_code' => 'ACT1611',
                'name' => 'AOC ACT1611 Compact Wireless Earbuds',
                'slug' => 'aoc-act1611-compact-wireless-earbuds',
                'category' => 'tws-earbuds',
                'description' => 'AOC signature acoustic tuning in an ultra-compact package. Equipped with Jerry 6983D2, instant pairing, and deep low-frequency dynamic punch.',
                'sku' => 'AOC-ACT1611',
                'stock' => 2200,
                'price' => 649,
                'compare_price' => 999,
                'bulk_price_tier1' => 499,
                'bulk_price_tier2' => 380,
                'bulk_price_tier3' => 290, // 23 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983D2 (BT 5.3)',
                'battery_specs' => '30mAh Earbud / 250mAh Case',
                'carton_specs' => '100 pcs/ctn | 46×31×24 cm | GW: 7.0 kg',
                'specs' => [
                    'Bluetooth Version' => '5.3',
                    'Driver' => '10mm Dynamic',
                    'Playtime' => '4 Hours Single / 18 Hours Total',
                ],
                'colors' => ['Pure Black', 'Pearl White'],
                'badge_text' => 'AOC Audio',
                'image_url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACT1631',
                'name' => 'AOC ACT1631 Dual LED Power Display TWS Earbuds',
                'slug' => 'aoc-act1631-led-power-display-tws',
                'category' => 'tws-earbuds',
                'description' => 'Features an integrated high-contrast digital LED battery percentage readout for the charging base and left/right independent earbud charge bars.',
                'sku' => 'AOC-ACT1631',
                'stock' => 1800,
                'price' => 749,
                'compare_price' => 1199,
                'bulk_price_tier1' => 569,
                'bulk_price_tier2' => 430,
                'bulk_price_tier3' => 330, // 26 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983D2 (BT 5.3)',
                'battery_specs' => '30mAh Earbud / 300mAh Case with LED',
                'carton_specs' => '100 pcs/ctn | 47×32×25 cm | GW: 7.7 kg',
                'specs' => [
                    'Display' => 'Real-Time Precision LED Power Gauge',
                    'Charging Connector' => 'Type-C Reversible',
                    'Speakers' => '13mm Copper Ring Driver',
                ],
                'colors' => ['Piano Black', 'Glacier White', 'Navy Blue'],
                'badge_text' => 'LED Battery Gauge',
                'image_url' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACT2615',
                'name' => 'AOC ACT2615 Active Noise Cancelling (ANC) TWS',
                'slug' => 'aoc-act2615-anc-tws-earbuds',
                'category' => 'anc-audio',
                'description' => 'Feed-forward Active Noise Cancellation achieving -30dB ambient noise attenuation. Eliminates train rumble and AC hum. Bluetrum 5636F processor ensures swift mode switching.',
                'sku' => 'AOC-ACT2615',
                'stock' => 1100,
                'price' => 1499,
                'compare_price' => 2299,
                'bulk_price_tier1' => 1150,
                'bulk_price_tier2' => 890,
                'bulk_price_tier3' => 660, // 52 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Bluetrum 5636F ANC Core',
                'battery_specs' => '35mAh Earbud / 350mAh Case',
                'carton_specs' => '100 pcs/ctn | 49×33×27 cm | GW: 8.4 kg',
                'specs' => [
                    'ANC Depth' => '-30dB Feedforward ANC',
                    'Modes' => 'ANC On / Transparency / Music Mode',
                    'Driver' => '10mm Polymer Diaphragm',
                ],
                'colors' => ['Slate Grey', 'Pearl White'],
                'badge_text' => 'Hybrid ANC -30dB',
                'image_url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACT3651',
                'name' => 'AOC ACT3651 360° Spatial Audio Hybrid ANC Earbuds',
                'slug' => 'aoc-act3651-spatial-audio-anc-earbuds',
                'category' => 'anc-audio',
                'description' => 'Audiophile grade TWS engineered with a flagship Realtek 8773BFE DSP processor. Integrates 360-degree head-tracking spatial audio simulation and -35dB deep active cancellation.',
                'sku' => 'AOC-ACT3651',
                'stock' => 650,
                'price' => 2299,
                'compare_price' => 3499,
                'bulk_price_tier1' => 1799,
                'bulk_price_tier2' => 1350,
                'bulk_price_tier3' => 1000, // 78 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 60 Pcs',
                'chipset' => 'Realtek 8773BFE Spatial DSP',
                'battery_specs' => '40mAh Earbud / 400mAh Case',
                'carton_specs' => '60 pcs/ctn | 48×34×26 cm | GW: 7.2 kg',
                'specs' => [
                    'Audio Engine' => 'Realtek 360° Spatial Virtualizer',
                    'Noise Cancellation' => '-35dB Hybrid ANC with Dual-Mic ENC',
                    'Wireless Standard' => 'Bluetooth 5.4 Low-Latency HD',
                ],
                'colors' => ['Graphite Black', 'Ceramic White'],
                'badge_text' => 'Spatial Audio Engine',
                'image_url' => 'https://images.unsplash.com/photo-1629367494173-c78a56567877?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACT4615',
                'name' => 'AOC ACT4615 Pro Hybrid ANC + Quad-Mic AI ENC TWS',
                'slug' => 'aoc-act4615-pro-anc-quad-mic-tws',
                'category' => 'anc-audio',
                'description' => 'Flagship true wireless earbuds with -38dB active noise cancellation, quad-microphone AI beamforming algorithm, and Qi wireless charging compatible case.',
                'sku' => 'AOC-ACT4615',
                'stock' => 450,
                'price' => 2499,
                'compare_price' => 3999,
                'bulk_price_tier1' => 1950,
                'bulk_price_tier2' => 1450,
                'bulk_price_tier3' => 1050, // 82 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 60 Pcs',
                'chipset' => 'Jerry 7003D4 Flagship ANC',
                'battery_specs' => '40mAh Earbud / 450mAh Wireless Case',
                'carton_specs' => '60 pcs/ctn | 49×35×27 cm | GW: 7.9 kg',
                'specs' => [
                    'ANC Attenuation' => '-38dB Deep Cancellation',
                    'Microphones' => '4-Mic Array with Wind-Noise Reduction',
                    'Wireless Charging' => 'Supported (Qi Compatible)',
                ],
                'colors' => ['Midnight Navy', 'Silver Mist', 'Obsidian Black'],
                'badge_text' => 'Pro ANC -38dB',
                'image_url' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACD2611',
                'name' => 'AOC ACD2611 Open-Ear OWS Sport Headphones',
                'slug' => 'aoc-acd2611-open-ear-ows-headphones',
                'category' => 'open-ear-sports',
                'description' => '16.2mm large dynamic acoustic driver delivering rich natural sound without entering the ear canal. Liquid silicone wrapped titanium wire guarantees pain-free wear.',
                'sku' => 'AOC-ACD2611',
                'stock' => 800,
                'price' => 1399,
                'compare_price' => 2199,
                'bulk_price_tier1' => 1099,
                'bulk_price_tier2' => 830,
                'bulk_price_tier3' => 615, // 48 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 60 Pcs',
                'chipset' => 'Bluetrum 5656C (BT 5.3)',
                'battery_specs' => '50mAh Earbud / 400mAh Case',
                'carton_specs' => '60 pcs/ctn | 47×34×26 cm | GW: 7.1 kg',
                'specs' => [
                    'Acoustic Driver' => '16.2mm Bio-Cellulose Horn',
                    'Water Resistance' => 'IPX5 Rain & Sweat Resistance',
                    'Battery Life' => '8 Hours Playback per charge',
                ],
                'colors' => ['Sport Black', 'Chalk White', 'Neon Orange'],
                'badge_text' => '16.2mm Big Bass OWS',
                'image_url' => 'https://images.unsplash.com/photo-1577174881658-0f30ed549adc?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACN4611',
                'name' => 'AOC ACN4611 Magnetic Neckband Sports Earphones',
                'slug' => 'aoc-acn4611-magnetic-neckband-earphones',
                'category' => 'open-ear-sports',
                'description' => 'Ergonomic neckband headset with magnetic snap earbuds that turn on/off automatically. 150mAh battery provides 18 hours continuous music playback.',
                'sku' => 'AOC-ACN4611',
                'stock' => 1400,
                'price' => 899,
                'compare_price' => 1399,
                'bulk_price_tier1' => 699,
                'bulk_price_tier2' => 540,
                'bulk_price_tier3' => 410, // 32 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'Jerry 6983 (BT 5.3)',
                'battery_specs' => '150mAh Fast-Charging (18H Playtime)',
                'carton_specs' => '100 pcs/ctn | 52×36×30 cm | GW: 9.8 kg',
                'specs' => [
                    'Feature' => 'Hall Magnetic Smart Power Switch',
                    'Neckband' => 'Shape-Memory Nickel-Titanium Alloy',
                    'Microphone' => 'HD Silicon Mic with CVC 8.0',
                ],
                'colors' => ['Stealth Black', 'Racing Red', 'Deep Blue'],
                'badge_text' => '18-Hour Neckband',
                'image_url' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=600&q=80',
            ],

            // ================= Over-Ear, ANC & Gaming Series =================
            [
                'brand' => 'AOC',
                'model_code' => 'ACG2615',
                'name' => 'AOC ACG2615 RGB Virtual 7.1 Gaming Headset',
                'slug' => 'aoc-acg2615-rgb-gaming-headset',
                'category' => 'gaming-over-ear',
                'description' => 'Immersive 7.1 surround sound gaming headset with 50mm neodymium magnet drivers, dynamic RGB flowing lighting rings, and a 360-degree flexible noise-cancelling boom mic.',
                'sku' => 'AOC-ACG2615',
                'stock' => 700,
                'price' => 1999,
                'compare_price' => 2999,
                'bulk_price_tier1' => 1550,
                'bulk_price_tier2' => 1190,
                'bulk_price_tier3' => 870, // 68 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 20 Pcs',
                'chipset' => 'C-Media Virtual 7.1 Sound Engine',
                'battery_specs' => 'USB + 3.5mm Dual Interface',
                'carton_specs' => '20 pcs/ctn | 56×46×42 cm | GW: 11.5 kg',
                'specs' => [
                    'Drivers' => '50mm Neodymium Acoustic Horns',
                    'Surround Sound' => 'Virtual 7.1 Channel Spatial Audio',
                    'Lighting' => '16.8M Color RGB Chroma Flow',
                    'Earpads' => 'Thick Breathable Protein Leather',
                ],
                'colors' => ['Gamer Black', 'Cyber White'],
                'badge_text' => 'Virtual 7.1 Surround',
                'image_url' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACH4615BK/00',
                'name' => 'AOC ACH4615BK CNC Aluminum Luxury Hybrid ANC Headphone',
                'slug' => 'aoc-ach4615bk-cnc-aluminum-anc-headphone',
                'category' => 'gaming-over-ear',
                'description' => 'Ultra-premium over-ear headphone precision CNC-machined from aircraft aluminum. Delivers -40dB deep hybrid noise cancellation, Hi-Res Audio certification, and 45 hours battery life.',
                'sku' => 'AOC-ACH4615BK',
                'stock' => 280,
                'price' => 4999,
                'compare_price' => 7999,
                'bulk_price_tier1' => 3899,
                'bulk_price_tier2' => 2790,
                'bulk_price_tier3' => 1990, // 155 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 3,
                'bulk_package_type' => 'Master Carton of 20 Pcs',
                'chipset' => 'Bestechnic BES2500YP Hybrid ANC',
                'battery_specs' => '500mAh (45H ANC Playback / 60H Normal)',
                'carton_specs' => '20 pcs/ctn | 60×48×44 cm | GW: 14.8 kg',
                'specs' => [
                    'Noise Cancellation' => '-40dB Dual-Feed Hybrid ANC',
                    'Chassis' => 'Sandblasted Aircraft-Grade Aluminum Alloy',
                    'Certifications' => 'Hi-Res Audio Gold Certified',
                    'Bluetooth Codecs' => 'LDAC, AAC, SBC, Bluetooth 5.4',
                ],
                'colors' => ['Anodized Space Gray', 'Brushed Silver', 'Obsidian Black'],
                'badge_text' => 'Flagship CNC -40dB',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'ACH4615',
                'name' => 'AOC ACH4615 50-Hour Endurance Wireless Headphone',
                'slug' => 'aoc-ach4615-50h-endurance-wireless-headphone',
                'category' => 'gaming-over-ear',
                'description' => 'Marathon 50-hour battery life with 40mm tuned composite titanium drivers, memory foam ear cushions, and Bluetooth 5.4 multipoint device connectivity.',
                'sku' => 'AOC-ACH4615',
                'stock' => 420,
                'price' => 3499,
                'compare_price' => 4999,
                'bulk_price_tier1' => 2699,
                'bulk_price_tier2' => 1990,
                'bulk_price_tier3' => 1510, // 118 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 24 Pcs',
                'chipset' => 'Bluetrum High-End BT 5.4',
                'battery_specs' => '600mAh High-Capacity (50H Playtime)',
                'carton_specs' => '24 pcs/ctn | 58×46×40 cm | GW: 12.0 kg',
                'specs' => [
                    'Battery Endurance' => '50 Continuous Hours on single charge',
                    'Multipoint' => 'Simultaneous 2-Device Bluetooth Switch',
                    'Quick Charge' => '10 Min charge gives 5 Hours playback',
                ],
                'colors' => ['Midnight Black', 'Ivory Cream'],
                'badge_text' => '50H Mega Battery',
                'image_url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=600&q=80',
            ],

            // ================= Computer Mice & Peripherals =================
            [
                'brand' => 'AOC',
                'model_code' => 'MS135',
                'name' => 'AOC MS135 Wired Office Ergonomic Optical Mouse',
                'slug' => 'aoc-ms135-wired-optical-mouse',
                'category' => 'computer-mice',
                'description' => 'Precision 1200 DPI optical tracking mouse with silent tactile micro-switches rated for 5 million clicks. Plug-and-play USB cable for reliable office workstations.',
                'sku' => 'AOC-MS135',
                'stock' => 3500,
                'price' => 499,
                'compare_price' => 799,
                'bulk_price_tier1' => 349,
                'bulk_price_tier2' => 240,
                'bulk_price_tier3' => 150, // 12 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'AOC Optical Sensor (1200 DPI)',
                'battery_specs' => 'USB 2.0 Bus-Powered (1.5m Cable)',
                'carton_specs' => '100 pcs/ctn | 45×32×26 cm | GW: 8.5 kg',
                'specs' => [
                    'Resolution' => '1200 DPI Optical Tracking',
                    'Button Lifespan' => '5,000,000 Clicks',
                    'Compatibility' => 'Windows / macOS / Linux / ChromeOS',
                ],
                'colors' => ['Matte Black', 'Business Grey'],
                'badge_text' => 'Office Essential',
                'image_url' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'MS225',
                'name' => 'AOC MS225 2.4G Silent Wireless Office Mouse',
                'slug' => 'aoc-ms225-2-4g-silent-wireless-mouse',
                'category' => 'computer-mice',
                'description' => 'Whisper-quiet silent switches eliminating over 90% of click noise. Features 3-speed adjustable DPI (1000/1200/1600), compact USB nano receiver, and 12-month battery life.',
                'sku' => 'AOC-MS225',
                'stock' => 2800,
                'price' => 699,
                'compare_price' => 1199,
                'bulk_price_tier1' => 520,
                'bulk_price_tier2' => 370,
                'bulk_price_tier3' => 230, // 18 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 10,
                'bulk_package_type' => 'Master Carton of 100 Pcs',
                'chipset' => 'PixArt 2.4GHz Low-Power Optical Engine',
                'battery_specs' => '1× AA Battery Powered (Smart Sleep)',
                'carton_specs' => '100 pcs/ctn | 46×34×28 cm | GW: 8.9 kg',
                'specs' => [
                    'Connectivity' => '2.4GHz Nano USB (10m Range)',
                    'DPI Settings' => '1000 / 1200 / 1600 DPI Adjustable',
                    'Acoustics' => '90% Noise Muted Silent Switches',
                ],
                'colors' => ['Obsidian Black', 'Chalk White', 'Ice Mint'],
                'badge_text' => '90% Silent Click',
                'image_url' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'MS235',
                'name' => 'AOC MS235 Dual-Mode Bluetooth 5.2 + 2.4G Rechargeable Mouse',
                'slug' => 'aoc-ms235-dual-mode-rechargeable-mouse',
                'category' => 'computer-mice',
                'description' => 'Versatile dual-mode connectivity allows seamless one-button toggling between laptops and desktop PCs via Bluetooth 5.2 or 2.4G wireless. Built-in 500mAh Type-C rechargeable lithium battery.',
                'sku' => 'AOC-MS235',
                'stock' => 1600,
                'price' => 999,
                'compare_price' => 1699,
                'bulk_price_tier1' => 780,
                'bulk_price_tier2' => 550,
                'bulk_price_tier3' => 360, // 28 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 80 Pcs',
                'chipset' => 'Nordic Dual-Mode BLE 5.2 + 2.4GHz',
                'battery_specs' => '500mAh Rechargeable Lithium (Type-C)',
                'carton_specs' => '80 pcs/ctn | 48×36×30 cm | GW: 9.6 kg',
                'specs' => [
                    'Dual-Mode' => 'Bluetooth 5.2 + 2.4GHz Wireless',
                    'Rechargeable' => 'Type-C Fast Charging Port',
                    'Sensors' => 'Works on Wood, Paper & Glass Desks',
                ],
                'colors' => ['Space Grey', 'Pearl Silver', 'Rose Gold'],
                'badge_text' => 'Bluetooth + 2.4G Dual',
                'image_url' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=600&q=80',
            ],

            // ================= Smartwatches & Wearables =================
            [
                'brand' => 'AOC',
                'model_code' => 'Y3',
                'name' => 'AOC Y3 1.85" HD Smart Fitness Watch',
                'slug' => 'aoc-y3-hd-smart-fitness-watch',
                'category' => 'smartwatches-wearables',
                'description' => 'Bright 1.85" edge-to-edge curved touch screen with 100+ athletic sport modes, 24/7 dynamic heart rate tracking, blood oxygen saturation (SpO2), and IP68 waterproof rating.',
                'sku' => 'AOC-Y3',
                'stock' => 850,
                'price' => 1899,
                'compare_price' => 2999,
                'bulk_price_tier1' => 1450,
                'bulk_price_tier2' => 1050,
                'bulk_price_tier3' => 740, // 58 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 50 Pcs',
                'chipset' => 'Realtek 8763E Health Core',
                'battery_specs' => '260mAh Lithium (7 Days Normal / 15 Days Standby)',
                'carton_specs' => '50 pcs/ctn | 46×36×28 cm | GW: 8.2 kg',
                'specs' => [
                    'Screen' => '1.85" TFT HD Display (240×286)',
                    'Water Resistance' => 'IP68 Swim-Proof',
                    'Sensors' => 'PPG Biometric Sensor, 3-Axis Accelerometer',
                ],
                'colors' => ['Midnight Black', 'Starlight Silver', 'Rose Gold'],
                'badge_text' => '1.85" HD Screen',
                'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'T6',
                'name' => 'AOC T6 Bluetooth Calling Smartwatch with Zinc Alloy Bezel',
                'slug' => 'aoc-t6-bluetooth-calling-smartwatch',
                'category' => 'smartwatches-wearables',
                'description' => '1.96" IPS borderless screen framed in high-strength zinc alloy. Make and receive crystal-clear phone calls straight from your wrist with the built-in speaker and microphone.',
                'sku' => 'AOC-T6',
                'stock' => 750,
                'price' => 2499,
                'compare_price' => 3999,
                'bulk_price_tier1' => 1950,
                'bulk_price_tier2' => 1390,
                'bulk_price_tier3' => 960, // 75 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 50 Pcs',
                'chipset' => 'Jerry 7012F Dual-Mode Calling',
                'battery_specs' => '300mAh Polymer (5-7 Days Working Time)',
                'carton_specs' => '50 pcs/ctn | 48×38×30 cm | GW: 9.4 kg',
                'specs' => [
                    'Screen' => '1.96" Borderless IPS (320×386)',
                    'Calling' => 'High-Fidelity Bluetooth Speakerphone',
                    'Housing' => 'Aviation Zinc Alloy + IML Back Cover',
                ],
                'colors' => ['Obsidian Black', 'Titanium Grey', 'Desert Gold'],
                'badge_text' => 'Bluetooth Calling',
                'image_url' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'T7',
                'name' => 'AOC T7 Rugged Tactical AMOLED Smartwatch',
                'slug' => 'aoc-t7-rugged-tactical-amoled-smartwatch',
                'category' => 'smartwatches-wearables',
                'description' => 'Military-tested rugged outdoor tactical watch featuring a 1.43" vibrant AMOLED always-on display, anti-scratch Corning glass, compass, altimeter, and 400mAh expedition battery.',
                'sku' => 'AOC-T7',
                'stock' => 450,
                'price' => 3499,
                'compare_price' => 5499,
                'bulk_price_tier1' => 2750,
                'bulk_price_tier2' => 1990,
                'bulk_price_tier3' => 1410, // 110 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 3,
                'bulk_package_type' => 'Master Carton of 40 Pcs',
                'chipset' => 'Realtek 8763EWE Ultra-Low Power',
                'battery_specs' => '400mAh Pure Cobalt (15 Days Outdoor Life)',
                'carton_specs' => '40 pcs/ctn | 50×40×32 cm | GW: 10.8 kg',
                'specs' => [
                    'Display' => '1.43" AMOLED (466×466 Pixels, 600 Nits)',
                    'Durability' => 'MIL-STD-810H Drop, Dust & Freeze Resistant',
                    'Features' => 'Compass, Barometer, Blood Oxygen, 5ATM',
                ],
                'colors' => ['Tactical Black', 'Army Green', 'Desert Sand'],
                'badge_text' => '1.43" AMOLED Tactical',
                'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'Pulse-Band',
                'name' => 'AOC Pulse Band Screenless 24/7 Biometric Health Tracker',
                'slug' => 'aoc-pulse-band-screenless-biometric-tracker',
                'category' => 'smartwatches-wearables',
                'description' => 'Zero-distraction screenless wristband weighing only 18g. Dedicated solely to medical-grade 24/7 continuous heart rate variability (HRV), sleep stage architecture, and recovery strain scoring.',
                'sku' => 'AOC-PULSE',
                'stock' => 1100,
                'price' => 1499,
                'compare_price' => 2299,
                'bulk_price_tier1' => 1150,
                'bulk_price_tier2' => 820,
                'bulk_price_tier3' => 570, // 45 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 60 Pcs',
                'chipset' => 'Nordic nRF52840 Low-Power BLE 5.3',
                'battery_specs' => '120mAh (14 Days Continuous Wear)',
                'carton_specs' => '60 pcs/ctn | 44×32×24 cm | GW: 5.6 kg',
                'specs' => [
                    'Weight' => '18g Featherweight Fabric Strap',
                    'Metrics' => 'HRV, Sleep Stages, Recovery Score, Skin Temp',
                    'Design' => 'Screenless Water-Tight Encapsulation',
                ],
                'colors' => ['Onyx Fabric', 'Glacier Grey', 'Deep Teal'],
                'badge_text' => '14-Day HRV Tracker',
                'image_url' => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?auto=format&fit=crop&w=600&q=80',
            ],

            // ================= Smart Scales & Diagnostic Tools =================
            [
                'brand' => 'AOC',
                'model_code' => 'WS2611',
                'name' => 'AOC WS2611 Tempered Glass Digital Precision Bathroom Scale',
                'slug' => 'aoc-ws2611-digital-precision-scale',
                'category' => 'smart-scales',
                'description' => 'Sleek 6mm explosion-proof tempered glass surface with hidden LED display. High-precision mountain G-sensors measure weight down to 50g graduation up to 180kg.',
                'sku' => 'AOC-WS2611',
                'stock' => 1600,
                'price' => 999,
                'compare_price' => 1599,
                'bulk_price_tier1' => 780,
                'bulk_price_tier2' => 540,
                'bulk_price_tier3' => 360, // 28 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 10 Pcs',
                'chipset' => 'AOC 24-bit High-Precision ADC Sensor',
                'battery_specs' => '2× AAA Batteries (Auto Step-On On/Off)',
                'carton_specs' => '10 pcs/ctn | 34×32×30 cm | GW: 13.5 kg',
                'specs' => [
                    'Capacity' => '180 kg (400 lbs)',
                    'Graduation' => '50g / 0.1 lb Precision',
                    'Glass' => '6mm Automotive Grade Tempered Glass',
                ],
                'colors' => ['Glacier White', 'Piano Black'],
                'badge_text' => '50g Precision Scale',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'WS2801',
                'name' => 'AOC WS2801 Bluetooth Smart Body Fat Scale (Fitdays App)',
                'slug' => 'aoc-ws2801-bluetooth-smart-body-fat-scale',
                'category' => 'smart-scales',
                'description' => 'Bioelectrical Impedance Analysis (BIA) scale featuring 4 stainless steel electrode pads. Automatically synchronizes 14 body composition metrics (BMI, Body Fat %, Muscle Mass, Visceral Fat) to the Fitdays and Apple Health apps.',
                'sku' => 'AOC-WS2801',
                'stock' => 1200,
                'price' => 1399,
                'compare_price' => 2299,
                'bulk_price_tier1' => 1050,
                'bulk_price_tier2' => 720,
                'bulk_price_tier3' => 490, // 38 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 10 Pcs',
                'chipset' => 'BIA Dual-Frequency Sensor + BLE 5.0',
                'battery_specs' => '2× AAA Batteries / 6 Months Endurance',
                'carton_specs' => '10 pcs/ctn | 35×33×31 cm | GW: 14.0 kg',
                'specs' => [
                    'App Compatibility' => 'Fitdays / Apple Health / Google Fit',
                    'Metrics' => '14 Core Body Metrics (BMI, BMR, Fat %, Protein)',
                    'Electrode Design' => '4× 304 Stainless Steel Surface Pads',
                ],
                'colors' => ['Pearl White', 'Obsidian Black'],
                'badge_text' => 'Fitdays App Sync',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'brand' => 'AOC',
                'model_code' => 'FG2408WB-B',
                'name' => 'AOC FG2408WB-B VA Color Screen Smart Diagnostic Scale',
                'slug' => 'aoc-fg2408wb-b-va-color-screen-smart-scale',
                'category' => 'smart-scales',
                'description' => 'Equipped with a brilliant 3.5" VA full-color screen that directly renders body fat percentage, hydration, and weight trends directly on the scale without opening your phone. Full-surface ITO conductive coating.',
                'sku' => 'AOC-FG2408WB',
                'stock' => 600,
                'price' => 2299,
                'compare_price' => 3499,
                'bulk_price_tier1' => 1790,
                'bulk_price_tier2' => 1250,
                'bulk_price_tier3' => 870, // 68 RMB base
                'unit_of_measure' => 'piece',
                'is_retail' => true,
                'is_bulk' => true,
                'bulk_min_quantity' => 5,
                'bulk_package_type' => 'Master Carton of 8 Pcs',
                'chipset' => 'Advanced BIA + Color VA Graphic Controller',
                'battery_specs' => 'Type-C USB Rechargeable 500mAh Lithium',
                'carton_specs' => '8 pcs/ctn | 38×36×32 cm | GW: 13.2 kg',
                'specs' => [
                    'Screen' => '3.5" VA Full-Color Graphic Display',
                    'Sensors' => 'Full Surface ITO Conductive Coating',
                    'Rechargeable' => 'Type-C Fast Charge (Lasts 1 Year on charge)',
                ],
                'colors' => ['Cosmic White', 'Deep Space Black'],
                'badge_text' => 'VA Color Display',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        // 11. Seed Products, Variants, Prices & Metadata
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

                // Base Retail Price (min_quantity 1)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['price'],
                    'compare_price' => $pData['compare_price'],
                    'min_quantity' => 1,
                ]);

                // Tier 1 Wholesale Price (min_quantity 5)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['bulk_price_tier1'],
                    'compare_price' => null,
                    'min_quantity' => 5,
                ]);

                // Tier 2 Wholesale Price (min_quantity 20)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['bulk_price_tier2'],
                    'compare_price' => null,
                    'min_quantity' => 20,
                ]);

                // Tier 3 Full Master Carton Wholesale Price (min_quantity 50)
                Price::create([
                    'priceable_type' => ProductVariant::morphName(),
                    'priceable_id' => $variant->id,
                    'currency_id' => $eur->id,
                    'customer_group_id' => null,
                    'price' => $pData['bulk_price_tier3'],
                    'compare_price' => null,
                    'min_quantity' => 50,
                ]);

                // USD Currency Price
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
                    'brand' => $pData['brand'],
                    'model_code' => $pData['model_code'],
                    'chipset' => $pData['chipset'],
                    'battery_specs' => $pData['battery_specs'],
                    'carton_specs' => $pData['carton_specs'],
                    'specs' => $pData['specs'],
                    'colors' => $pData['colors'],
                    'unit_of_measure' => $pData['unit_of_measure'],
                    'is_retail' => $pData['is_retail'],
                    'is_bulk' => $pData['is_bulk'],
                    'bulk_min_quantity' => $pData['bulk_min_quantity'],
                    'bulk_package_type' => $pData['bulk_package_type'],
                    'is_quick_commerce' => false,
                    'quick_delivery_minutes' => 0,
                    'badge_text' => $pData['badge_text'],
                    'origin_details' => "Official {$pData['brand']} Electronics Global Distribution. Standard Master Carton Export Packing.",
                    'image_url' => $pData['image_url'],
                ]
            );
        }

        // 12. Trade Coupons
        $discounts = [
            [
                'name' => '10% New Trader Welcome',
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
                'name' => 'Bulk B2B Trader €50 Off',
                'handle' => 'b2b50',
                'coupon' => 'B2B50',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'data' => [
                    'fixed_value' => true,
                    'fixed_values' => [
                        'EUR' => 5000,
                        'USD' => 5400,
                    ],
                ],
            ],
            [
                'name' => 'Master Carton 5% Off',
                'handle' => 'carton5',
                'coupon' => 'CARTON5',
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
