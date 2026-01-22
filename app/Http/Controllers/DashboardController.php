<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Core totals (existing logic - unchanged)
        $totalUsers   = User::count();
        $totalOrders  = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('grand_total');

        // Visitors (until you implement tracking)
        $totalVisitors = 0;

        // This week stats (existing logic - unchanged)
        $thisWeek = [
            'users'   => User::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'orders'  => Order::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'revenue' => Order::where('status', 'delivered')
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->sum('grand_total'),
        ];

        // Last week stats (existing logic - unchanged)
        $lastWeek = [
            'users'   => User::whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek(),
            ])->count(),

            'orders'  => Order::whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek(),
            ])->count(),

            'revenue' => Order::where('status', 'delivered')
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek(),
                ])->sum('grand_total'),
        ];

        /* -----------------------------------------------------------------
           Additional fields for enhanced dashboard
        ----------------------------------------------------------------- */

        $totalCustomers = Customer::count();
        $totalProducts  = Product::count();

        // Low stock based on product_stocks (same logic used elsewhere)
        $lowStockCount = DB::table('product_stocks')
            ->selectRaw('product_id, SUM(quantity - reserved_qty) as available_qty')
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity - reserved_qty) <= ?', [10])
            ->count();

        // FIXED: campaigns table has no `is_active` column
        $activeCampaigns = Campaign::count();

        return view('dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'totalVisitors',
            'thisWeek',
            'lastWeek',
            'totalCustomers',
            'totalProducts',
            'lowStockCount',
            'activeCampaigns'
        ));
    }
}
