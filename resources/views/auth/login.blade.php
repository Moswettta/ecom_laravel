@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="card" style="max-width:420px;margin:40px auto">
    <h1>Login</h1>
    <p class="muted">Owner / Cashier / Customer</p>
    <form method="post" action="{{ route('login') }}">
        @csrf
        <p><label>Username</label><input name="username" value="{{ old('username') }}" required autofocus></p>
        <p><label>Password</label><input type="password" name="password" required></p>
        <p><label><input type="checkbox" name="remember" value="1"> Remember me</label></p>
        <button class="btn" type="submit">Sign in</button>
    </form>
    <p class="muted" style="margin-top:16px;font-size:13px">Default: <b>admin / admin</b> (Owner)</p>
</div>
@endsection
