@extends('layouts.app')
@section('title', 'Products')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center">
    <div><h1>Products</h1><p class="muted">Manage shoes and clothing, prices, and stock.</p></div>
    <a class="btn" href="#new">+ Add Product</a>
</div>

<div class="card">
    <form method="get" style="display:flex;gap:8px;flex-wrap:wrap">
        <input name="search" value="{{ request('search') }}" placeholder="Search name / SKU / barcode" style="flex:1;min-width:180px">
        <select name="department" style="max-width:180px">
            <option value="">All departments</option>
            <option value="Shoes" @selected(request('department')==='Shoes')>Shoes</option>
            <option value="Clothing" @selected(request('department')==='Clothing')>Clothing</option>
        </select>
        <button class="btn secondary" type="submit">Search</button>
    </form>
</div>

<div class="card">
    <div style="overflow-x:auto">
    <table>
        <tr>
            <th>Name</th><th>Dept</th><th>SKU</th><th>Category</th><th>Supplier</th>
            <th>Buy</th><th>Sell</th><th>Qty</th><th>Action</th>
        </tr>
        @forelse($products as $p)
        <tr>
            <td><b>{{ $p->product_name }}</b><br><span class="muted">{{ $p->color }}</span></td>
            <td><span class="pill">{{ $p->department }}</span></td>
            <td>{{ $p->sku }}</td>
            <td>{{ $p->category?->name }}</td>
            <td>{{ $p->supplier?->name ?? '—' }}</td>
            <td>KES {{ number_format($p->buying_price, 2) }}</td>
            <td>KES {{ number_format($p->selling_price, 2) }}</td>
            <td class="{{ $p->isLowStock() ? 'low' : '' }}">{{ $p->quantity }}</td>
            <td>
                <a class="btn sm secondary" href="{{ route('owner.products.index', ['edit' => $p->id]) }}#new">Edit</a>
                <form method="post" action="{{ route('owner.products.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Deactivate product?')">
                    @csrf @method('DELETE')
                    <button class="btn sm danger" type="submit">Deactivate</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="9">No products found.</td></tr>
        @endforelse
    </table>
    </div>
    {{ $products->links() }}
</div>

<div class="card" id="new">
    <h2>{{ $edit ? 'Edit' : 'Add' }} Product</h2>
    <form method="post" action="{{ route('owner.products.store') }}" enctype="multipart/form-data" class="formgrid">
        @csrf
        <input type="hidden" name="id" value="{{ $edit->id ?? 0 }}">
        <p>
            <label>Department</label>
            <select name="department" required>
                <option value="Shoes" @selected(($edit->department ?? 'Shoes')==='Shoes')>Shoes</option>
                <option value="Clothing" @selected(($edit->department ?? '')==='Clothing')>Clothing</option>
            </select>
        </p>
        <p><label>Product name</label><input name="product_name" value="{{ old('product_name', $edit->product_name ?? '') }}" required maxlength="150"></p>
        <p><label>SKU</label><input name="sku" value="{{ old('sku', $edit->sku ?? '') }}" required maxlength="80"></p>
        <p><label>Barcode</label><input name="barcode" value="{{ old('barcode', $edit->barcode ?? '') }}"></p>
        <p>
            <label>Category</label>
            <select name="category_id">
                <option value="">-- Select --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $edit->category_id ?? '')==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Supplier</label>
            <select name="supplier_id">
                <option value="">-- Select --</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" @selected(old('supplier_id', $edit->supplier_id ?? '')==$s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </p>
        <p><label>Brand</label><input name="brand" value="{{ old('brand', $edit->brand ?? '') }}"></p>
        <p><label>Gender</label><input name="gender" value="{{ old('gender', $edit->gender ?? '') }}" placeholder="Men / Women / Unisex"></p>
        <p><label>Size</label><input name="size" value="{{ old('size', $edit->size ?? '') }}"></p>
        <p><label>Color</label><input name="color" value="{{ old('color', $edit->color ?? '') }}"></p>
        <p><label>Buying price</label><input type="number" step="0.01" min="0" name="buying_price" value="{{ old('buying_price', $edit->buying_price ?? 0) }}" required></p>
        <p><label>Selling price</label><input type="number" step="0.01" min="0.01" name="selling_price" value="{{ old('selling_price', $edit->selling_price ?? 0) }}" required></p>
        <p><label>Quantity</label><input type="number" min="0" name="quantity" value="{{ old('quantity', $edit->quantity ?? 0) }}" required></p>
        <p><label>Reorder level</label><input type="number" min="0" name="reorder_level" value="{{ old('reorder_level', $edit->reorder_level ?? 5) }}" required></p>
        <p style="grid-column:1/-1">
            <label>Size stock (optional)</label>
            <input name="size_stock" value="{{ old('size_stock', $edit ? $edit->sizes->map(fn($s)=>$s->size_label.':'.$s->quantity)->implode(', ') : '') }}" placeholder="38:2, 39:4, 40:3">
        </p>
        <p style="grid-column:1/-1">
            <label>Product image</label>
            <input type="file" name="product_image" accept="image/jpeg,image/png,image/webp">
        </p>
        <p>
            <label>Status</label>
            <select name="status">
                <option value="active" @selected(($edit->status ?? 'active')==='active')>Active</option>
                <option value="inactive" @selected(($edit->status ?? '')==='inactive')>Inactive</option>
            </select>
        </p>
        <p style="grid-column:1/-1"><label>Description</label><textarea name="description" rows="3">{{ old('description', $edit->description ?? '') }}</textarea></p>
        <p style="grid-column:1/-1"><button class="btn" type="submit">Save product</button></p>
    </form>
</div>
@endsection
