<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'sku', 'barcode', 'category_id', 'supplier_id',
        'department', 'brand', 'gender', 'size', 'color',
        'buying_price', 'selling_price', 'quantity', 'reorder_level',
        'product_image', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'buying_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'quantity' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }
}
