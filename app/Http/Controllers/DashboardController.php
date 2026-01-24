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
        $user = auth()->user();

        // Base order query using permissions (not roles)
        $orderQuery = Order::query()
            ->when(
                $user->can('orders.view_own') && ! $user->can('orders.view_all'),
                fn ($q) => $q->where('created_by', $user->id)
            );

        // Core totals
        $totalUsers  = User::count(); // global
        $totalOrders = (clone $orderQuery)->count();

        // Revenue: include all except cancelled & draft
        $totalRevenue = (clone $orderQuery)
            ->whereIn('status', ['draft','confirmed', 'processing', 'shipped', 'delivered'])
            ->sum('grand_total');

        // Visitors (until you implement tracking)
        $totalVisitors = 0;

        // This week stats
        $thisWeek = [
            'users' => User::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),

            'orders' => (clone $orderQuery)
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->count(),

            'revenue' => (clone $orderQuery)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->sum('grand_total'),
        ];

        // Last week stats
        $lastWeek = [
            'users' => User::whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek(),
            ])->count(),

            'orders' => (clone $orderQuery)
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek(),
                ])->count(),

            'revenue' => (clone $orderQuery)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek(),
                ])
                ->sum('grand_total'),
        ];

        /* -----------------------------------------------------------------
           Additional fields for enhanced dashboard
        ----------------------------------------------------------------- */

        $totalCustomers = Customer::count();
        $totalProducts  = Product::count();

        // Low stock based on product_stocks
        $lowStockCount = DB::table('product_stocks')
            ->selectRaw('product_id, SUM(quantity - reserved_qty) as available_qty')
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity - reserved_qty) <= ?', [10])
            ->count();

        // Campaigns
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
