<div class="mb-6 flex items-center text-sm">
    <a href="{{ route('dashboard') }}"
       class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>

    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>

    <a href="{{ route('customers.index') }}"
       class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Customers') }}</a>

    <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7" />
    </svg>

    <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
</div>
