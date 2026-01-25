<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Campaign;
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

        /* ------------------------------------------------------------
           Apply Date Filters from Dashboard UI
           Default = today
        ------------------------------------------------------------ */
        $range = request('range', 'today');

        match ($range) {
            'today' => $orderQuery->whereDate('created_at', now()->toDateString()),

            'yesterday' => $orderQuery->whereDate(
                'created_at',
                now()->copy()->subDay()->toDateString()
            ),

            'week' => $orderQuery->whereBetween('created_at', [
                now()->startOfWeek(),
                now(),
            ]),

            'month' => $orderQuery
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),

            'year' => $orderQuery->whereYear('created_at', now()->year),

            'custom' => $orderQuery
                ->when(request('from'), fn ($q) =>
                    $q->whereDate('created_at', '>=', request('from'))
                )
                ->when(request('to'), fn ($q) =>
                    $q->whereDate('created_at', '<=', request('to'))
                ),

            default => null,
        };

        /* ------------------------------------------------------------
           Core Totals
        ------------------------------------------------------------ */
        $totalUsers  = User::count();
        $totalOrders = (clone $orderQuery)->count();

        // Revenue: include all statuses except cancelled
        $totalRevenue = (clone $orderQuery)
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');

        $totalVisitors = 0;

        /* ------------------------------------------------------------
           This Week Stats (permission aware)
        ------------------------------------------------------------ */
        $weekQuery = Order::query()
            ->when(
                $user->can('orders.view_own') && ! $user->can('orders.view_all'),
                fn ($q) => $q->where('created_by', $user->id)
            )
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now(),
            ]);

        $thisWeek = [
            'users' => User::where('created_at', '>=', now()->startOfWeek())->count(),

            'orders' => (clone $weekQuery)->count(),

            'revenue' => (clone $weekQuery)
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total'),
        ];

        /* ------------------------------------------------------------
           Last Week Stats
        ------------------------------------------------------------ */
        $lastWeekQuery = Order::query()
            ->when(
                $user->can('orders.view_own') && ! $user->can('orders.view_all'),
                fn ($q) => $q->where('created_by', $user->id)
            )
            ->whereBetween('created_at', [
                now()->copy()->subWeek()->startOfWeek(),
                now()->copy()->subWeek()->endOfWeek(),
            ]);

        $lastWeek = [
            'users' => User::whereBetween('created_at', [
                now()->copy()->subWeek()->startOfWeek(),
                now()->copy()->subWeek()->endOfWeek(),
            ])->count(),

            'orders' => (clone $lastWeekQuery)->count(),

            'revenue' => (clone $lastWeekQuery)
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total'),
        ];

        /* ------------------------------------------------------------
           Additional Dashboard Metrics
        ------------------------------------------------------------ */
        $totalCustomers = Customer::count();
        $totalProducts  = Product::count();

        $lowStockCount = DB::table('product_stocks')
            ->selectRaw('product_id, SUM(quantity - reserved_qty) as available_qty')
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity - reserved_qty) <= ?', [10])
            ->count();

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
