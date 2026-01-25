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
        ------------------------------------------------------------ */
        if (request('range')) {
            match (request('range')) {
                'today' => $orderQuery->whereDate('created_at', today()),

                'yesterday' => $orderQuery->whereDate('created_at', today()->subDay()),

                'week' => $orderQuery->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
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
        }

        /* ------------------------------------------------------------
           Core Totals
        ------------------------------------------------------------ */
        $totalUsers  = User::count();
        $totalOrders = (clone $orderQuery)->count();

        // Revenue: exclude draft & cancelled
        $totalRevenue = (clone $orderQuery)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('grand_total');

        $totalVisitors = 0;

        /* ------------------------------------------------------------
           This Week Stats (always real current week)
        ------------------------------------------------------------ */
        $thisWeek = [
            'users' => User::where('created_at', '>=', now()->startOfWeek())->count(),

            'orders' => Order::where('created_at', '>=', now()->startOfWeek())->count(),

            'revenue' => Order::whereNotIn('status', ['cancelled'])
                ->where('created_at', '>=', now()->startOfWeek())
                ->sum('grand_total'),
        ];

        /* ------------------------------------------------------------
           Last Week Stats
        ------------------------------------------------------------ */
        $lastWeek = [
            'users' => User::whereBetween('created_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ])->count(),

            'orders' => Order::whereBetween('created_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ])->count(),

            'revenue' => Order::whereNotIn('status', ['draft', 'cancelled'])
                ->whereBetween('created_at', [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek(),
                ])
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
            ->get()
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
