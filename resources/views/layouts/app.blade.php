<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ShopZone') — ShopZone Kenya</title>
    <style>
        :root { --bg:#0f172a; --card:#1e293b; --accent:#22c55e; --text:#e2e8f0; --muted:#94a3b8; --danger:#ef4444; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: system-ui, sans-serif; background: #f1f5f9; color: #0f172a; }
        a { color: inherit; text-decoration: none; }
        .topbar { background: var(--bg); color: var(--text); padding: 12px 24px; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color: var(--text); margin-left: 16px; opacity: .9; }
        .topbar a:hover { color: var(--accent); }
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-bottom: 16px; }
        .btn { display:inline-block; background: var(--accent); color:#052e16; border:0; padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor:pointer; }
        .btn.secondary { background: #e2e8f0; color: #0f172a; }
        .btn.danger { background: var(--danger); color:#fff; }
        .btn.sm { padding: 6px 10px; font-size: 13px; }
        table { width:100%; border-collapse: collapse; }
        th, td { text-align:left; padding: 10px 8px; border-bottom: 1px solid #e2e8f0; }
        .formgrid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .formgrid p { margin:0; }
        label { display:block; font-size:13px; color:#64748b; margin-bottom:4px; }
        input, select, textarea { width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:8px; }
        .alert { padding:12px 16px; border-radius:8px; margin-bottom:16px; }
        .alert.success { background:#dcfce7; color:#14532d; }
        .alert.error { background:#fee2e2; color:#7f1d1d; }
        .pill { background:#e2e8f0; padding:2px 8px; border-radius:999px; font-size:12px; }
        .muted { color:#64748b; }
        .stats { display:grid; grid-template-columns: repeat(4,1fr); gap:12px; }
        .stat { background:#fff; border-radius:12px; padding:16px; }
        .stat b { display:block; font-size:24px; }
        .market-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(180px,1fr)); gap:16px; }
        .product-card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.08); }
        .product-card img, .product-card .noimg { width:100%; height:160px; object-fit:cover; background:#e2e8f0; display:block; }
        .product-card h3 { margin:8px 12px 4px; font-size:15px; }
        .product-card .price { margin:0 12px 12px; font-weight:700; color:#15803d; }
        .low { color: var(--danger); font-weight:700; }
        @media (max-width:700px){ .formgrid,.stats{grid-template-columns:1fr;} }
    </style>
</head>
<body>
<nav class="topbar">
    <div><strong>ShopZone</strong> <span class="muted" style="color:#94a3b8">Kenya</span></div>
    <div>
        <a href="{{ route('shop.index') }}">Shop</a>
        <a href="{{ route('shop.products') }}">Products</a>
        @auth
            @if(auth()->user()->isOwner() || auth()->user()->isCashier())
                <a href="{{ route('owner.dashboard') }}">Dashboard</a>
                <a href="{{ route('owner.products.index') }}">Manage products</a>
            @endif
            <form action="{{ route('logout') }}" method="post" style="display:inline">@csrf
                <button class="btn sm secondary" type="submit">Logout ({{ auth()->user()->username }})</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</nav>
<div class="container">
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert error">
            <ul style="margin:0;padding-left:18px">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
</body>
</html>
