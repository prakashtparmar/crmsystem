<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <div class="mb-5 flex items-center text-xs text-gray-500 dark:text-gray-400">
            <a href="{{ route('dashboard') }}"
               class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
            <span class="mx-2">›</span>
            <a href="{{ route('products.index') }}"
               class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Products') }}</a>
            <span class="mx-2">›</span>
            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Create') }}</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Create Product') }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Add a new product to the catalog') }}
            </p>
        </div>

        <div class="max-w-3xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                @if ($errors->any())
                    <div class="m-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    <!-- Product Details -->
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Product Information
                        </h2>

                        <div class="grid grid-cols-1 gap-5 max-w-xl">

                            {{-- All your product form fields --}}
                            @include('products.form')

                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 rounded-b-xl">
                        <a href="{{ route('products.index') }}"
                           class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300
                                  hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>

                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Save Product') }}
                        </x-button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-layouts.app>
