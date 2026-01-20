<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Core totals
        $totalUsers   = User::count();
        $totalOrders  = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('grand_total');

        // Visitors (until you implement tracking)
        $totalVisitors = 0;

        // This week stats
        $thisWeek = [
            'users'   => User::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'orders'  => Order::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'revenue' => Order::where('status', 'delivered')
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->sum('grand_total'),
        ];

        // Last week stats
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

        return view('dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'totalVisitors',
            'thisWeek',
            'lastWeek'
        ));
    }
}
