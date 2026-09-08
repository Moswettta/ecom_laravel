<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::with(['category', 'supplier'])->active();

        if ($search = trim((string) $request->get('search'))) {
            $q->where(function ($w) use ($search) {
                $w->where('product_name', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%")
                  ->orWhere('barcode', 'ilike', "%{$search}%");
            });
        }
        if ($dept = $request->get('department')) {
            if (in_array($dept, ['Shoes', 'Clothing'], true)) {
                $q->where('department', $dept);
            }
        }

        $products = $q->orderByDesc('id')->paginate(20)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $edit = null;
        if ($request->filled('edit')) {
            $edit = Product::with('sizes')->find($request->integer('edit'));
        }

        return view('owner.products', compact('products', 'categories', 'suppliers', 'edit'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'product_name' => 'required|string|max:150',
            'sku' => 'required|string|max:80',
            'barcode' => 'nullable|string|max:80',
            'category_id' => 'nullable|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'department' => 'required|in:Shoes,Clothing',
            'brand' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:40',
            'size' => 'nullable|string|max:30',
            'color' => 'nullable|string|max:60',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'size_stock' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,webp|max:3072',
        ]);

        $id = (int) ($data['id'] ?? 0);
        $barcode = $data['barcode'] ?: null;

        // Unique SKU / barcode
        $skuExists = Product::where('sku', $data['sku'])->when($id, fn ($q) => $q->where('id', '!=', $id))->exists();
        if ($skuExists) {
            return back()->withErrors(['sku' => 'SKU already exists.'])->withInput();
        }
        if ($barcode) {
            $bcExists = Product::where('barcode', $barcode)->when($id, fn ($q) => $q->where('id', '!=', $id))->exists();
            if ($bcExists) {
                return back()->withErrors(['barcode' => 'Barcode already exists.'])->withInput();
            }
        }

        $sizeStock = [];
        if (!empty($data['size_stock'])) {
            foreach (preg_split('/[,\n]+/', $data['size_stock']) as $entry) {
                $parts = array_map('trim', explode(':', $entry, 2));
                if (count($parts) !== 2 || $parts[0] === '' || !ctype_digit($parts[1])) {
                    return back()->withErrors(['size_stock' => 'Size stock format: Size:Qty e.g. 38:2,39:4'])->withInput();
                }
                $sizeStock[$parts[0]] = (int) $parts[1];
            }
            $data['quantity'] = array_sum($sizeStock);
        }

        $imageName = null;
        if ($request->hasFile('product_image')) {
            $imageName = $request->file('product_image')->store('products', 'public');
            $imageName = basename($imageName);
        }

        try {
            DB::transaction(function () use ($data, $id, $barcode, $sizeStock, $imageName, $request) {
                $payload = [
                    'product_name' => $data['product_name'],
                    'sku' => $data['sku'],
                    'barcode' => $barcode,
                    'category_id' => $data['category_id'] ?: null,
                    'supplier_id' => $data['supplier_id'] ?: null,
                    'department' => $data['department'],
                    'brand' => $data['brand'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'size' => $data['size'] ?? null,
                    'color' => $data['color'] ?? null,
                    'buying_price' => $data['buying_price'],
                    'selling_price' => $data['selling_price'],
                    'quantity' => $data['quantity'],
                    'reorder_level' => $data['reorder_level'],
                    'description' => $data['description'] ?? null,
                    'status' => $data['status'] ?? 'active',
                ];
                if ($imageName) {
                    $payload['product_image'] = $imageName;
                }

                if ($id) {
                    $product = Product::findOrFail($id);
                    $oldQty = $product->quantity;
                    $product->update($payload);
                    if ($sizeStock) {
                        $product->sizes()->delete();
                        foreach ($sizeStock as $label => $qty) {
                            $product->sizes()->create(['size_label' => $label, 'quantity' => $qty]);
                        }
                    }
                    if ($data['quantity'] !== $oldQty) {
                        DB::table('inventory_transactions')->insert([
                            'product_id' => $product->id,
                            'type' => $data['quantity'] > $oldQty ? 'addition' : 'reduction',
                            'quantity' => abs($data['quantity'] - $oldQty),
                            'reference' => 'PRODUCT_EDIT',
                            'notes' => 'Stock correction',
                            'user_id' => $request->user()->id,
                            'created_at' => now(),
                        ]);
                    }
                } else {
                    $product = Product::create($payload);
                    foreach ($sizeStock as $label => $qty) {
                        $product->sizes()->create(['size_label' => $label, 'quantity' => $qty]);
                    }
                    if ($data['quantity'] > 0) {
                        DB::table('inventory_transactions')->insert([
                            'product_id' => $product->id,
                            'type' => 'addition',
                            'quantity' => $data['quantity'],
                            'reference' => 'OPENING',
                            'notes' => 'Opening stock',
                            'user_id' => $request->user()->id,
                            'created_at' => now(),
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('owner.products.index')->with('success', $id ? 'Product updated.' : 'Product added successfully.');
    }

    public function destroy(Product $product)
    {
        $product->update(['status' => 'inactive']);
        return redirect()->route('owner.products.index')->with('success', 'Product deactivated.');
    }
}
