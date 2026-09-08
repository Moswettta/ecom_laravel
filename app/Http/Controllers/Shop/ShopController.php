<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $featured = Product::active()->inStock()->latest()->take(12)->get();
        $deals = Product::active()->inStock()->orderBy('selling_price')->take(6)->get();
        return view('shop.index', compact('featured', 'deals'));
    }

    public function products(Request $request)
    {
        $q = Product::with('category')->active()->inStock();
        if ($cat = $request->integer('category')) {
            $q->where('category_id', $cat);
        }
        $products = $q->latest()->paginate(24)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        return view('shop.products', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }
        $product->load('sizes', 'category');
        return view('shop.product', compact('product'));
    }
}
