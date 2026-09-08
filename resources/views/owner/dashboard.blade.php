@extends('layouts.app')
@section('title', 'Owner Dashboard')
@section('content')
<h1>Owner Dashboard</h1>
<div class="stats">
    <div class="stat"><span class="muted">Products</span><b>{{ $stats['products'] }}</b></div>
    <div class="stat"><span class="muted">Low stock</span><b>{{ $stats['low_stock'] }}</b></div>
    <div class="stat"><span class="muted">Users</span><b>{{ $stats['users'] }}</b></div>
    <div class="stat"><span class="muted">Sales today</span><b>{{ $stats['sales_today'] }}</b></div>
</div>
<div class="card" style="margin-top:16px">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0">Recent products</h2>
        <a class="btn" href="{{ route('owner.products.index') }}">Manage products</a>
    </div>
    <table>
        <tr><th>Name</th><th>SKU</th><th>Price</th><th>Qty</th></tr>
        @foreach($recent as $p)
        <tr>
            <td>{{ $p->product_name }}</td>
            <td>{{ $p->sku }}</td>
            <td>KES {{ number_format($p->selling_price, 2) }}</td>
            <td class="{{ $p->isLowStock() ? 'low' : '' }}">{{ $p->quantity }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
