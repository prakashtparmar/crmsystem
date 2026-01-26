<x-layouts.app>

    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>

        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <a href="{{ route('customers.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Customers') }}</a>

        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
    </div>

    {{-- Flash Messages --}}
    @include('customers.partials.alerts')

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $customer->display_name ?? $customer->first_name . ' ' . $customer->last_name }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Customer Overview & Quick Order
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('customers.edit', $customer) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm
                      hover:bg-blue-700 shadow-sm">
                Edit Customer
            </a>

            <a href="{{ route('customers.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg border text-sm
                      text-gray-700 dark:text-gray-300
                      hover:bg-gray-50 dark:hover:bg-gray-700">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <!-- LEFT SIDE -->
        <div class="xl:col-span-3 space-y-6">

            {{-- Customer Information --}}
            {{-- @include('customers.partials.show.system') --}}

            @include('customers.partials.basic')
                 {{--   @include('customers.partials.address') --}}
               {{--     @include('customers.partials.agriculture') --}}
               {{--     @include('customers.partials.reference')  --}}
                {{--    @include('customers.partials.financial') --}}

            {{-- Quick Order --}}
            @include('customers.partials.show.quick-order')

            {{-- Products --}}
            @include('customers.partials.show.products')

            {{-- Orders --}}
            @include('customers.partials.show.orders')

        </div>
    </div>

    {{-- Product Modal --}}
    @include('customers.partials.show.product-modal')

    {{-- Styles --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    @endpush

    {{-- Scripts --}}
    @push('scripts')
        @include('customers.partials.show.scripts')
        @include('customers.partials.scripts')
    @endpush

</x-layouts.app>
