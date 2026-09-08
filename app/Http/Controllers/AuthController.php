<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectForRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $data['username'])->with('role')->first();

        if (!$user || $user->status !== 'active' || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectForRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectForRole(User $user)
    {
        return match ($user->role?->name) {
            'Owner' => redirect()->route('owner.dashboard'),
            'Cashier' => redirect()->route('owner.dashboard'),
            default => redirect()->route('shop.index'),
        };
    }
}
