<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Welcome to the dashboard') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <!-- Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalUsers) }}
                    </p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        {{ __('This week') }}: {{ $thisWeek['users'] }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full"></div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenue') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalRevenue, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        {{ __('This week') }}: {{ number_format($thisWeek['revenue'], 2) }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full"></div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Orders') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalOrders) }}
                    </p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        {{ __('This week') }}: {{ $thisWeek['orders'] }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full"></div>
            </div>
        </div>

        <!-- Visitors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Visitors') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                        {{ number_format($totalVisitors) }}
                    </p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        {{ __('No data') }}
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full"></div>
            </div>
        </div>

    </div>

</x-layouts.app>
