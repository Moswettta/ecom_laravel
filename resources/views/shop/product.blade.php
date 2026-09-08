@extends('layouts.app')
@section('title', $product->product_name)
@section('content')
<div class="card" style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
    <div>
        @if($product->product_image)
            <img src="{{ asset('storage/products/'.$product->product_image) }}" alt="" style="width:100%;border-radius:12px">
        @else
            <div class="noimg" style="height:280px;border-radius:12px"></div>
        @endif
    </div>
    <div>
        <span class="pill">{{ $product->department }}</span>
        <h1>{{ $product->product_name }}</h1>
        <p class="muted">{{ $product->brand }} · {{ $product->category?->name }}</p>
        <p style="font-size:28px;font-weight:700;color:#15803d">KES {{ number_format($product->selling_price, 2) }}</p>
        <p>Stock: <b>{{ $product->quantity }}</b></p>
        @if($product->sizes->count())
            <p>Sizes:
                @foreach($product->sizes as $s)
                    <span class="pill">{{ $s->size_label }} ({{ $s->quantity }})</span>
                @endforeach
            </p>
        @endif
        <p>{{ $product->description }}</p>
        <a class="btn secondary" href="{{ route('shop.products') }}">← Back to shop</a>
    </div>
</div>
@endsection
