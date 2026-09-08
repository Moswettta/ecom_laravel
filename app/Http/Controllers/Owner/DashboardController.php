<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::active()->count(),
            'low_stock' => Product::active()->whereColumn('quantity', '<=', 'reorder_level')->count(),
            'users' => User::where('status', 'active')->count(),
            'sales_today' => DB::table('sales')->whereDate('created_at', today())->count(),
        ];
        $recent = Product::active()->latest()->take(5)->get();
        return view('owner.dashboard', compact('stats', 'recent'));
    }
}
