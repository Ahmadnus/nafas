<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Generates 100 products spread across the existing categories so the
 * scroll-reveal animation can be tested against long, tall grids — the
 * condition that exposed the "only the first row is visible" bug.
 *
 *   php artisan db:seed --class=StressTestSeeder
 *
 * Everything it creates is slugged `stress-*` so it can be wiped cleanly:
 *   php artisan db:seed --class=StressTestSeeder -- --fresh   (see below)
 */
class StressTestSeeder extends Seeder
{
    private const TOTAL = 100;

    /** Flavour words combined into product names, EN => AR. */
    private const FLAVOURS = [
        'Mango' => 'مانجو',
        'Strawberry' => 'فراولة',
        'Banana' => 'موز',
        'Blueberry' => 'توت أزرق',
        'Pineapple' => 'أناناس',
        'Kiwi' => 'كيوي',
        'Peach' => 'خوخ',
        'Watermelon' => 'بطيخ',
        'Pomegranate' => 'رمان',
        'Passion Fruit' => 'فاكهة العاطفة',
        'Coconut' => 'جوز الهند',
        'Avocado' => 'أفوكادو',
        'Date' => 'تمر',
        'Fig' => 'تين',
        'Guava' => 'جوافة',
        'Lychee' => 'ليتشي',
        'Papaya' => 'بابايا',
        'Raspberry' => 'توت العليق',
        'Blackberry' => 'توت أسود',
        'Apricot' => 'مشمش',
    ];

    /** Per-category noun, EN => AR. */
    private const KINDS = [
        'smoothies' => ['Smoothie', 'سموذي'],
        'milkshakes' => ['Milkshake', 'ميلك شيك'],
        'fruit-bowls' => ['Bowl', 'طبق'],
        'desserts' => ['Waffle', 'وافل'],
        'juices' => ['Juice', 'عصير'],
    ];

    public function run(): void
    {
        $categories = Category::whereIn('slug', array_keys(self::KINDS))->get();

        if ($categories->isEmpty()) {
            $this->command?->error('No categories found. Run `php artisan db:seed` first.');

            return;
        }

        $flavours = array_keys(self::FLAVOURS);
        $badgePool = [[], [], ['Fresh'], ['Best Seller'], ['Offer'], ['Best Seller', 'Fresh']];
        $created = 0;

        // Round-robin across categories so every grid is tall enough to push
        // most of its cards below the fold.
        for ($i = 0; $i < self::TOTAL; $i++) {
            $category = $categories[$i % $categories->count()];
            [$kind, $kindAr] = self::KINDS[$category->slug];

            $flavour = $flavours[intdiv($i, $categories->count()) % count($flavours)];
            $flavourAr = self::FLAVOURS[$flavour];

            $n = $i + 1;
            $title = "{$flavour} {$kind} #{$n}";
            $titleAr = "{$kindAr} {$flavourAr} رقم {$n}";

            Product::updateOrCreate(
                ['slug' => 'stress-' . Str::slug($title)],
                [
                    'category_id' => $category->id,
                    'title' => $title,
                    'title_ar' => $titleAr,
                    'description' => "Test item {$n} — {$flavour} {$kind} used to stress the scroll-reveal animation across a long grid.",
                    'description_ar' => "عنصر تجريبي رقم {$n} — {$kindAr} {$flavourAr} لاختبار حركة الظهور عند التمرير.",
                    'price' => 300 + ($i % 12) * 45,
                    'badges' => $badgePool[$i % count($badgePool)],
                    // A few sold-out cards verify the overlay still renders.
                    'is_available' => $i % 17 !== 0,
                    'sort_order' => 100 + $i,
                ]
            );

            $created++;
        }

        $this->command?->info("Seeded {$created} stress-test products across {$categories->count()} categories.");
        $this->command?->info('Remove them with: php artisan tinker --execute="App\Models\Product::where(\'slug\',\'like\',\'stress-%\')->delete();"');
    }
}
