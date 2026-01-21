<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('products.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Products') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Product') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Update product details') }}
        </p>
    </div>

    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">

                        <form action="{{ route('products.update', $product) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="max-w-2xl space-y-6">

                            @csrf
                            @method('PUT')

                            {{-- Product Form Fields --}}
                            @include('products.form')

                            <!-- Actions -->
                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('products.index') }}"
                                   class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300
                                          hover:bg-gray-100 dark:hover:bg-gray-700">
                                    {{ __('Cancel') }}
                                </a>

                                <x-button type="primary" class="px-4 py-1.5 text-sm">
                                    {{ __('Update Product') }}
                                </x-button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
