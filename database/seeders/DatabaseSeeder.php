<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@frutta.test'],
            ['name' => 'Admin', 'password' => 'password']
        );

        Setting::set('restaurant_name', 'Nafas - Juice & Patisserie');
        Setting::set('restaurant_name_ar', 'نَفَس - جوس وباتيسيري');
        Setting::set('whatsapp_number', '923001234567');
        Setting::set('currency_symbol', 'ل.س');
        Setting::set('is_open', '1');
        Setting::set('tagline', 'Fresh Juice & Fine Patisserie');
        Setting::set('tagline_ar', 'عصائر طازجة وحلويات فاخرة');

        $categories = [
            ['name' => 'Fresh Smoothies', 'name_ar' => 'سموذي طازج', 'slug' => 'smoothies', 'icon' => 'cup-soda'],
            ['name' => 'Milkshakes', 'name_ar' => 'ميلك شيك', 'slug' => 'milkshakes', 'icon' => 'milk'],
            ['name' => 'Fruit Bowls & Salads', 'name_ar' => 'أطباق وسلطات الفواكه', 'slug' => 'fruit-bowls', 'icon' => 'apple'],
            ['name' => 'Waffles & Desserts', 'name_ar' => 'وافل وحلويات', 'slug' => 'desserts', 'icon' => 'ice-cream-cone'],
            ['name' => 'Juices', 'name_ar' => 'عصائر', 'slug' => 'juices', 'icon' => 'citrus'],
        ];

        $catModels = [];
        foreach ($categories as $i => $c) {
            $catModels[$c['slug']] = Category::updateOrCreate(
                ['slug' => $c['slug']],
                [...$c, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $products = [
            [
                'cat' => 'smoothies', 'title' => 'Mango Smoothie', 'title_ar' => 'سموذي المانجو', 'price' => 550,
                'description' => 'Rich Chaunsa mango blended with creamy yogurt and a hint of honey.',
                'description_ar' => 'مانجو تشاونسا غنية ممزوجة بالزبادي الكريمي ولمسة من العسل.',
                'badges' => ['Best Seller', 'Fresh'],
                'groups' => [
                    ['name' => 'Size', 'name_ar' => 'الحجم', 'type' => 'single', 'required' => true, 'options' => [
                        ['label' => 'Regular (12oz)', 'label_ar' => 'عادي (12 أونصة)', 'delta' => 0, 'default' => true],
                        ['label' => 'Large (16oz)', 'label_ar' => 'كبير (16 أونصة)', 'delta' => 150],
                    ]],
                    ['name' => 'Sweetness Level', 'name_ar' => 'مستوى التحلية', 'type' => 'single', 'required' => false, 'options' => [
                        ['label' => 'Less Sweet', 'label_ar' => 'أقل حلاوة', 'delta' => 0],
                        ['label' => 'Regular', 'label_ar' => 'عادي', 'delta' => 0, 'default' => true],
                        ['label' => 'Extra Sweet', 'label_ar' => 'حلو زيادة', 'delta' => 0],
                    ]],
                    ['name' => 'Add-ons', 'name_ar' => 'إضافات', 'type' => 'multiple', 'required' => false, 'options' => [
                        ['label' => 'Chia Seeds', 'label_ar' => 'بذور الشيا', 'delta' => 80],
                        ['label' => 'Extra Mango Chunks', 'label_ar' => 'قطع مانجو إضافية', 'delta' => 100],
                    ]],
                ],
            ],
            [
                'cat' => 'smoothies', 'title' => 'Mixed Berry Blast', 'title_ar' => 'خليط التوت المنعش', 'price' => 600,
                'description' => 'Strawberries, blueberries & raspberries whipped into a vibrant smoothie.',
                'description_ar' => 'فراولة وتوت أزرق وتوت العليق مخفوقة في سموذي حيوي.',
                'badges' => ['Fresh'],
                'groups' => [
                    ['name' => 'Size', 'name_ar' => 'الحجم', 'type' => 'single', 'required' => true, 'options' => [
                        ['label' => 'Regular (12oz)', 'label_ar' => 'عادي (12 أونصة)', 'delta' => 0, 'default' => true],
                        ['label' => 'Large (16oz)', 'label_ar' => 'كبير (16 أونصة)', 'delta' => 150],
                    ]],
                ],
            ],
            [
                'cat' => 'smoothies', 'title' => 'Green Detox Smoothie', 'title_ar' => 'سموذي ديتوكس أخضر', 'price' => 620,
                'description' => 'Spinach, green apple, banana & mint — a refreshing daily detox.',
                'description_ar' => 'سبانخ وتفاح أخضر وموز ونعناع — ديتوكس يومي منعش.',
                'badges' => [],
                'groups' => [],
            ],
            [
                'cat' => 'milkshakes', 'title' => 'Nutella Milkshake', 'title_ar' => 'ميلك شيك نوتيلا', 'price' => 700,
                'description' => 'Thick creamy milkshake swirled with real Nutella and choco crumbs.',
                'description_ar' => 'ميلك شيك سميك وكريمي ممزوج بالنوتيلا الحقيقية وفتات الشوكولاتة.',
                'badges' => ['Best Seller'],
                'groups' => [
                    ['name' => 'Toppings', 'name_ar' => 'إضافات علوية', 'type' => 'multiple', 'required' => false, 'options' => [
                        ['label' => 'Whipped Cream', 'label_ar' => 'كريمة مخفوقة', 'delta' => 60],
                        ['label' => 'Choco Drizzle', 'label_ar' => 'صوص شوكولاتة', 'delta' => 50],
                        ['label' => 'Crushed Oreo', 'label_ar' => 'أوريو مطحون', 'delta' => 80],
                    ]],
                ],
            ],
            [
                'cat' => 'milkshakes', 'title' => 'Oreo Milkshake', 'title_ar' => 'ميلك شيك أوريو', 'price' => 650,
                'description' => 'Classic Oreo cookies blended into velvety vanilla milkshake.',
                'description_ar' => 'بسكويت أوريو الكلاسيكي ممزوج بميلك شيك الفانيليا المخملي.',
                'badges' => ['Fresh'],
                'groups' => [],
            ],
            [
                'cat' => 'milkshakes', 'title' => 'Classic Vanilla Milkshake', 'title_ar' => 'ميلك شيك فانيليا كلاسيكي', 'price' => 500,
                'description' => 'Smooth and simple — the perfect vanilla bean milkshake.',
                'description_ar' => 'ناعم وبسيط — ميلك شيك حبوب الفانيليا المثالي.',
                'badges' => [],
                'groups' => [],
            ],
            [
                'cat' => 'fruit-bowls', 'title' => 'Fruit Salad Bowl', 'title_ar' => 'طبق سلطة الفواكه', 'price' => 480,
                'description' => 'Seasonal fresh fruits tossed with honey-yogurt dressing.',
                'description_ar' => 'فواكه موسمية طازجة مع صلصة العسل والزبادي.',
                'badges' => ['Fresh'],
                'groups' => [
                    ['name' => 'Add Toppings', 'name_ar' => 'إضافة مكونات علوية', 'type' => 'multiple', 'required' => false, 'options' => [
                        ['label' => 'Honey Drizzle', 'label_ar' => 'رذاذ العسل', 'delta' => 40],
                        ['label' => 'Granola', 'label_ar' => 'جرانولا', 'delta' => 70],
                        ['label' => 'Chia Seeds', 'label_ar' => 'بذور الشيا', 'delta' => 80],
                    ]],
                ],
            ],
            [
                'cat' => 'fruit-bowls', 'title' => 'Dragon Fruit Bowl', 'title_ar' => 'طبق فاكهة التنين', 'price' => 550,
                'description' => 'Exotic dragon fruit chunks with kiwi and pomegranate.',
                'description_ar' => 'قطع فاكهة التنين الغريبة مع الكيوي والرمان.',
                'badges' => ['Offer'],
                'groups' => [],
            ],
            [
                'cat' => 'desserts', 'title' => 'Nutella Waffle', 'title_ar' => 'وافل نوتيلا', 'price' => 750,
                'description' => 'Crispy golden waffle drizzled with Nutella, banana slices & powdered sugar.',
                'description_ar' => 'وافل ذهبي مقرمش مغطى بالنوتيلا وشرائح الموز والسكر البودرة.',
                'badges' => ['Best Seller', 'Offer'],
                'groups' => [
                    ['name' => 'Extra Drizzle', 'name_ar' => 'رذاذ إضافي', 'type' => 'multiple', 'required' => false, 'options' => [
                        ['label' => 'Extra Nutella', 'label_ar' => 'نوتيلا إضافية', 'delta' => 100],
                        ['label' => 'Caramel Drizzle', 'label_ar' => 'صوص كراميل', 'delta' => 80],
                        ['label' => 'Vanilla Ice Cream Scoop', 'label_ar' => 'كرة آيس كريم فانيليا', 'delta' => 150],
                    ]],
                ],
            ],
            [
                'cat' => 'desserts', 'title' => 'Belgian Choco Waffle', 'title_ar' => 'وافل الشوكولاتة البلجيكية', 'price' => 780,
                'description' => 'Waffle topped with melted Belgian chocolate and hazelnuts.',
                'description_ar' => 'وافل مغطى بالشوكولاتة البلجيكية الذائبة والبندق.',
                'badges' => [],
                'groups' => [],
            ],
            [
                'cat' => 'juices', 'title' => 'Fresh Orange Juice', 'title_ar' => 'عصير برتقال طازج', 'price' => 400,
                'description' => 'Cold-pressed, no added sugar, 100% fresh oranges.',
                'description_ar' => 'معصور على البارد، بدون سكر مضاف، 100% برتقال طازج.',
                'badges' => ['Fresh'],
                'groups' => [
                    ['name' => 'Size', 'name_ar' => 'الحجم', 'type' => 'single', 'required' => true, 'options' => [
                        ['label' => 'Regular (12oz)', 'label_ar' => 'عادي (12 أونصة)', 'delta' => 0, 'default' => true],
                        ['label' => 'Large (16oz)', 'label_ar' => 'كبير (16 أونصة)', 'delta' => 120],
                    ]],
                ],
            ],
            [
                'cat' => 'juices', 'title' => 'Watermelon Cooler', 'title_ar' => 'عصير البطيخ المنعش', 'price' => 380,
                'description' => 'Chilled watermelon juice with a splash of mint and lime.',
                'description_ar' => 'عصير بطيخ مثلج مع لمسة من النعناع والليمون.',
                'badges' => [],
                'groups' => [],
            ],
        ];

        foreach ($products as $i => $p) {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'category_id' => $catModels[$p['cat']]->id,
                    'title' => $p['title'],
                    'title_ar' => $p['title_ar'],
                    'description' => $p['description'],
                    'description_ar' => $p['description_ar'],
                    'price' => $p['price'],
                    'badges' => $p['badges'],
                    'is_available' => true,
                    'sort_order' => $i,
                ]
            );

            $product->optionGroups()->delete();

            foreach ($p['groups'] as $gi => $g) {
                $group = $product->optionGroups()->create([
                    'name' => $g['name'],
                    'name_ar' => $g['name_ar'],
                    'type' => $g['type'],
                    'is_required' => $g['required'],
                    'sort_order' => $gi,
                ]);

                foreach ($g['options'] as $oi => $o) {
                    $group->options()->create([
                        'label' => $o['label'],
                        'label_ar' => $o['label_ar'],
                        'price_delta' => $o['delta'],
                        'is_default' => $o['default'] ?? false,
                        'sort_order' => $oi,
                    ]);
                }
            }
        }

        $waffle = Product::where('slug', 'nutella-waffle')->first();
        $dragonFruit = Product::where('slug', 'dragon-fruit-bowl')->first();

        Offer::updateOrCreate(
            ['title' => 'Nutella Waffle Deal'],
            [
                'product_id' => $waffle?->id,
                'title_ar' => 'عرض وافل نوتيلا',
                'subtitle' => 'Buy 1 Waffle + Get a Free Regular Smoothie',
                'subtitle_ar' => 'اشترِ وافل واحصل على سموذي عادي مجانًا',
                'discount_price' => 699,
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        Offer::updateOrCreate(
            ['title' => 'Dragon Fruit Bowl Special'],
            [
                'product_id' => $dragonFruit?->id,
                'title_ar' => 'عرض طبق فاكهة التنين',
                'subtitle' => 'Weekend Special — 15% Off',
                'subtitle_ar' => 'عرض نهاية الأسبوع — خصم 15%',
                'discount_price' => 470,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
