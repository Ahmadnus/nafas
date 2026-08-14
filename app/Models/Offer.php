<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'product_id', 'title', 'title_ar', 'subtitle', 'subtitle_ar', 'image', 'discount_price',
        'starts_at', 'ends_at', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'discount_price' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function imageUrl(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        if ($this->product) {
            return $this->product->image_url;
        }

        return asset('images/products/juice.svg');
    }

    public function hasRealImage(): bool
    {
        return (bool) ($this->image || $this->product?->image);
    }
}
