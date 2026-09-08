<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSize extends Model
{
    protected $fillable = ['product_id', 'size_label', 'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
