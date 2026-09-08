@extends('layouts.app')
@section('title', 'Shop')
@section('content')
<section style="background:linear-gradient(135deg,#0f172a,#1e3a5f);color:#fff;border-radius:16px;padding:32px;margin-bottom:24px">
    <h1 style="margin:0 0 8px">Everything you want.<br>Prices you'll love.</h1>
    <p style="opacity:.85">Discover shoes, clothing and exclusive deals.</p>
    <a class="btn" href="{{ route('shop.products') }}">Shop now →</a>
</section>

<div style="display:flex;justify-content:space-between;align-items:center">
    <h2>⚡ Flash Deals</h2>
    <a href="{{ route('shop.products') }}">View all →</a>
</div>
<div class="market-grid" style="margin-bottom:24px">
    @foreach($deals as $p)
    <a class="product-card" href="{{ route('shop.product', $p) }}">
        @if($p->product_image)
            <img src="{{ asset('storage/products/'.$p->product_image) }}" alt="">
        @else
            <div class="noimg"></div>
        @endif
        <h3>{{ $p->product_name }}</h3>
        <div class="price">KES {{ number_format($p->selling_price, 2) }}</div>
    </a>
    @endforeach
</div>

<div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Recommended for you</h2>
    <a href="{{ route('shop.products') }}">See more →</a>
</div>
<div class="market-grid">
    @forelse($featured as $p)
    <a class="product-card" href="{{ route('shop.product', $p) }}">
        @if($p->product_image)
            <img src="{{ asset('storage/products/'.$p->product_image) }}" alt="">
        @else
            <div class="noimg"></div>
        @endif
        <h3>{{ $p->product_name }}</h3>
        <p class="muted" style="margin:0 12px;font-size:13px">{{ $p->brand ?: $p->department }}</p>
        <div class="price">KES {{ number_format($p->selling_price, 2) }}</div>
    </a>
    @empty
    <p>No products available yet.</p>
    @endforelse
</div>
@endsection
