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
        <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ __('Product Details') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('View complete information about this product') }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}"
                class="px-3 py-2 rounded-lg border text-sm text-gray-700 dark:text-gray-300
                      hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Edit') }}
            </a>

            <a href="{{ route('products.index') }}"
                class="px-3 py-2 rounded-lg border text-sm text-gray-700 dark:text-gray-300
                      hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="max-w-3xl">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">

                <div class="p-6 space-y-6 text-sm">

                    {{-- Images --}}
                    <div>
                        <div class="text-xs text-gray-500 mb-2">Images</div>

                        @if ($product->images->count())
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                @foreach ($product->images as $img)
                                    <div class="border rounded-md overflow-hidden w-24 h-24">
                                        <img src="{{ asset('storage/' . $img->path) }}" alt="Product Image"
                                            class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-400 text-xs">No images uploaded.</div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Name</div>
                            <div class="font-medium text-gray-800 dark:text-gray-100">
                                {{ $product->name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">SKU</div>
                            <div>{{ $product->sku ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Category</div>
                            <div>{{ $product->category?->name ?? '—' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Brand</div>
                            <div>{{ $product->brand?->name ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Price</div>
                            <div class="font-semibold text-blue-600">
                                {{ $product->price }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">GST</div>
                            <div>{{ $product->gst_percent }}%</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Status</div>
                            <div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Short Description</div>
                        <div class="mt-1 text-gray-700 dark:text-gray-300">
                            {{ $product->short_description ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Description</div>
                        <div class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $product->description ?? '—' }}
                        </div>
                    </div>

                </div>

                <div
                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 rounded-b-xl flex justify-end gap-2">
                    <a href="{{ route('products.edit', $product) }}"
                        class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300
                              hover:bg-gray-100 dark:hover:bg-gray-700">
                        {{ __('Edit') }}
                    </a>

                    <a href="{{ route('products.index') }}"
                        class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300
                              hover:bg-gray-100 dark:hover:bg-gray-700">
                        {{ __('Back to List') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
