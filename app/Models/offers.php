<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class offers extends Model
{
    protected $fillable = [
        'name',
        'category',
        'brand',
        'sku',
        'price',
        'discount_percentage',
        'quantity',
        'images',
        'description',
        'specifications',
        'status',
        'featured',
        'views',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'featured' => 'boolean',
        'price' => 'integer',
        'quantity' => 'integer',
        'discount_percentage' => 'integer',
        'views' => 'integer',
    ];

    /**
     * Get the category relationship
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'category', 'name');
    }

    /**
     * Get cart items for this product
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'offer_id');
    }

    /**
     * Get wishlist items for this product
     */
    public function wishlistItems()
    {
        return $this->hasMany(wishes::class, 'offer_id');
    }

    /**
     * Get order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'offer_id');
    }

    /**
     * Get the first image URL
     */
    public function getFirstImageAttribute()
    {
        $images = is_string($this->images) ? json_decode($this->images, true) : $this->images;
        if ($images && is_array($images) && count($images) > 0) {
            return 'storage/offer_img/product' . $this->id . '/' . $images[0];
        }
        return 'img/default-product.jpg'; // fallback image
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute()
    {
        $images = is_string($this->images) ? json_decode($this->images, true) : $this->images;
        if ($images && is_array($images)) {
            return array_map(function($img) {
                return 'storage/offer_img/product' . $this->id . '/' . $img;
            }, $images);
        }
        return ['img/default-product.jpg'];
    }

    /**
     * Get discounted price
     */
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return $this->price;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->quantity > 0 && $this->status !== 'out_of_stock';
    }

    /**
     * Increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Scope for active products
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured(Builder $query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope for in-stock products
     */
    public function scopeInStock(Builder $query)
    {
        return $query->where('quantity', '>', 0)->where('status', '!=', 'out_of_stock');
    }

    /**
     * Scope for search
     */
    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory(Builder $query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope for filtering by price range
     */
    public function scopeByPriceRange(Builder $query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }
}
