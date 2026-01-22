<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Welcome to the dashboard') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <!-- Users -->
        @can('users.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalUsers) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">This week: {{ $thisWeek['users'] }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Customers -->
        @can('customers.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Customers</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalCustomers) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Orders -->
        @can('orders.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Orders</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalOrders) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">This week: {{ $thisWeek['orders'] }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Revenue -->
        @can('reports-sales.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalRevenue, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        This week: {{ number_format($thisWeek['revenue'], 2) }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Products -->
        @can('products.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalProducts) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Catalog size</p>
                </div>
                <div class="bg-teal-100 dark:bg-teal-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Inventory Alerts -->
        @can('inventory.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">
                        {{ number_format($lowStockCount) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Needs attention</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Campaigns -->
        @can('campaigns.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Campaigns</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($activeCampaigns) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Running now</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

        <!-- Visitors -->
        @can('reports-customers.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Visitors</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalVisitors) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">No data</p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full"></div>
            </div>
        </div>
        @endcan

    </div>

</x-layouts.app>
