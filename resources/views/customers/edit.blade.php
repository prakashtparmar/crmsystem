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

        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit Customer') }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Update customer information') }}
        </p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('customers.show', $customer) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-blue-600 text-white text-sm font-medium
                  hover:bg-blue-700 shadow-md">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Create Order') }}
        </a>
    </div>
</div>


    <div class="max-w-7xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    {{ __('Customer Details') }}
                </h2>
            </div>

            <div class="p-6 space-y-6">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')



                    @include('customers.partials.basic', ['customer' => $customer])
                    @include('customers.partials.address', ['customer' => $customer])
                    @include('customers.partials.agriculture', ['customer' => $customer])
                    @include('customers.partials.reference', ['customer' => $customer])
                    @include('customers.partials.financial', ['customer' => $customer])
                    @include('customers.partials.status', ['customer' => $customer])
                    @include('customers.partials.classification', ['customer' => $customer])
                    @include('customers.partials.location', ['customer' => $customer])
                    @include('customers.partials.business', ['customer' => $customer])

                    <!-- Bottom Actions (optional, keep or remove) -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('customers.index') }}"
                           class="px-4 py-2 rounded-xl border text-sm text-gray-700 dark:text-gray-300
                                  hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>

                        <x-button type="primary" name="final_submit" value="1"
                                  class="px-6 py-2 text-sm shadow-md">
                            {{ __('Update Customer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('customers.partials.scripts')
</x-layouts.app>
