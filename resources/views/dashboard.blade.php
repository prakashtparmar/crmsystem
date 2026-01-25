<x-layouts.app>

    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Welcome back, here’s what’s happening today') }}
        </p>
    </div>

    <!-- Filter Bar -->
    <form method="GET" class="mb-6">
    <div class="flex flex-wrap items-end gap-2
                bg-white dark:bg-gray-800
                border-2 border-gray-200 dark:border-gray-700
                rounded-xl p-3 shadow-sm">

        <select name="range"
            class="rounded-lg text-sm
                   border-2 border-gray-300 dark:border-gray-600
                   dark:bg-gray-900 px-3 py-2
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

            <option value="today" @selected(request('range', 'today') === 'today')>Today</option>
            <option value="yesterday" @selected(request('range') === 'yesterday')>Yesterday</option>
            <option value="week" @selected(request('range') === 'week')>This Week</option>
            <option value="month" @selected(request('range') === 'month')>This Month</option>
            <option value="year" @selected(request('range') === 'year')>This Year</option>
        </select>

        <button
            class="px-5 py-2 rounded-lg text-sm font-medium
                   bg-indigo-600 text-white
                   border border-indigo-600
                   hover:bg-indigo-700 hover:border-indigo-700
                   focus:ring-2 focus:ring-indigo-500
                   transition">
            Apply
        </button>

        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 rounded-lg text-sm
                  border-2 border-gray-300 dark:border-gray-600
                  text-gray-700 dark:text-gray-200
                  hover:bg-gray-100 dark:hover:bg-gray-700
                  transition">
            Reset
        </a>
    </div>
</form>


    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        @can('users.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Users</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($totalUsers) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">This period: {{ $thisWeek['users'] ?? 0 }}</p>
        </div>
        @endcan

        @can('customers.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Customers</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($totalCustomers) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">All time</p>
        </div>
        @endcan

        @can('orders.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-purple-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Orders</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($totalOrders) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">This period: {{ $thisWeek['orders'] ?? 0 }}</p>
        </div>
        @endcan

        @can('reports-sales.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-green-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Revenue</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                ₹{{ number_format($totalRevenue, 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                This period: ₹{{ number_format($thisWeek['revenue'] ?? 0, 2) }}
            </p>
        </div>
        @endcan

        @can('products.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-teal-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Products</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($totalProducts) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Catalog size</p>
        </div>
        @endcan

        @can('inventory.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-red-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Low Stock</p>
            <p class="text-3xl font-bold mt-2 text-red-600">
                {{ number_format($lowStockCount) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Needs attention</p>
        </div>
        @endcan

        @can('campaigns.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-yellow-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Active Campaigns</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($activeCampaigns) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Running now</p>
        </div>
        @endcan

        @can('reports-customers.view')
        <div class="relative bg-white dark:bg-gray-800 rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-orange-500 rounded-t-xl"></div>
            <p class="text-xs uppercase tracking-wide text-gray-500">Visitors</p>
            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-100">
                {{ number_format($totalVisitors) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">No data</p>
        </div>
        @endcan

    </div>

</x-layouts.app>
