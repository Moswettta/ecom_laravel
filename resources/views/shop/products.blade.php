@extends('layouts.app')
@section('title', 'Products')
@section('content')
<h1>Shop Products</h1>
<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
    <a class="pill" href="{{ route('shop.products') }}">All</a>
    @foreach($categories as $c)
        <a class="pill" href="{{ route('shop.products', ['category' => $c->id]) }}">{{ $c->name }}</a>
    @endforeach
</div>
<div class="market-grid">
    @forelse($products as $p)
    <a class="product-card" href="{{ route('shop.product', $p) }}">
        @if($p->product_image)
            <img src="{{ asset('storage/products/'.$p->product_image) }}" alt="">
        @else
            <div class="noimg"></div>
        @endif
        <h3>{{ $p->product_name }}</h3>
        <div class="price">KES {{ number_format($p->selling_price, 2) }}</div>
    </a>
    @empty
    <p>No products are currently available.</p>
    @endforelse
</div>
{{ $products->links() }}
@endsection
