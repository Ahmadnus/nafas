<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'title', 'title_ar', 'slug', 'description', 'description_ar', 'image',
        'price', 'compare_price', 'badges', 'is_available', 'sort_order', 'clicks_count',
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'badges' => 'array',
        'is_available' => 'boolean',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . $this->image) : asset('images/products/' . $this->categoryFallbackImage()));
    }

    protected function categoryFallbackImage(): string
    {
        return match ($this->category_id) {
            1 => 'smoothie.svg',
            2 => 'milkshake.svg',
            3 => 'bowl.svg',
            4 => 'waffle.svg',
            5 => 'juice.svg',
            default => 'smoothie.svg',
        };
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function optionGroups(): HasMany
    {
        return $this->hasMany(ProductOptionGroup::class)->orderBy('sort_order');
    }
}
