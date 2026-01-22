<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Products') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Products') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all products') }}
            </p>
        </div>

        <a href="{{ route('products.create') }}">
            <x-button type="primary">
                + {{ __('Add Product') }}
            </x-button>
        </a>
    </div>

    <div class="p-6">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
            <table id="productsTable" class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-center w-10">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Product</th>
                        <th class="px-3 py-2">Category</th>
                        <th class="px-3 py-2">Brand</th>
                        <th class="px-3 py-2">Stock</th>
                        <th class="px-3 py-2">GST</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Created</th>
                        <th class="px-3 py-2 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($products as $product)
                        @php
                            $image = optional($product->images->first())->path
                                ? asset('storage/' . $product->images->first()->path)
                                : asset('products/agriimage.jpg');
                        @endphp


                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">
                                <input type="checkbox" class="row-checkbox" name="ids[]" value="{{ $product->id }}">
                            </td>

                            <td class="px-3 py-2 text-gray-500">{{ $product->id }}</td>

                            <!-- Product Column -->
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-3">
                                    <!-- Main clickable product block -->
                                    <button type="button"
                                        class="view-product flex items-center gap-3 text-left hover:underline"
                                        data-name="{{ $product->name }}" data-sku="{{ $product->sku }}"
                                        data-hsn="{{ $product->hsn_code }}" data-price="{{ $product->price }}"
                                        data-cost="{{ $product->cost_price }}" data-gst="{{ $product->gst_percent }}"
                                        data-organic="{{ $product->is_organic ? 'Yes' : 'No' }}"
                                        data-active="{{ $product->is_active ? 'Active' : 'Inactive' }}"
                                        data-min="{{ $product->min_order_qty }}"
                                        data-max="{{ $product->max_order_qty }}"
                                        data-shelf="{{ $product->shelf_life_days }}"
                                        data-short="{{ $product->short_description }}"
                                        data-desc="{{ $product->description }}" data-image="{{ $image }}"
                                        data-category="{{ $product->category?->name ?? '—' }}"
                                        data-subcategory="{{ $product->subcategory?->name ?? '—' }}"
                                        data-brand="{{ $product->brand?->name ?? '—' }}"
                                        data-unit="{{ $product->unit?->name ?? '—' }}"
                                        data-crop="{{ $product->crop?->name ?? '—' }}"
                                        data-season="{{ $product->season?->name ?? '—' }}">
                                        <div class="flex gap-1">
                                            @if ($product->images->count())
                                                @foreach ($product->images->take(3) as $img)
                                                    <div
                                                        class="w-8 h-8 rounded-md overflow-hidden border bg-gray-100 flex-shrink-0">
                                                        <img src="{{ asset('storage/' . $img->path) }}"
                                                            alt="{{ $product->name }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                @endforeach
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-md overflow-hidden border bg-gray-100 flex-shrink-0">
                                                    <img src="{{ asset('products/agriimage.jpg') }}" alt="Default"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @endif

                                            @if ($product->images->count() > 3)
                                                <div
                                                    class="w-8 h-8 rounded-md border bg-gray-200 text-[10px] flex items-center justify-center text-gray-600">
                                                    +{{ $product->images->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="font-medium text-blue-600 dark:text-blue-400">
                                                {{ $product->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $product->sku ?? '—' }}
                                            </span>
                                        </div>
                                    </button>


                                    <!-- Quick View Icon -->
                                    <button type="button"
                                        class="view-product p-1.5 rounded-md border border-gray-200 dark:border-gray-600
                                               text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Quick View" data-name="{{ $product->name }}"
                                        data-sku="{{ $product->sku }}" data-hsn="{{ $product->hsn_code }}"
                                        data-price="{{ $product->price }}" data-cost="{{ $product->cost_price }}"
                                        data-gst="{{ $product->gst_percent }}"
                                        data-organic="{{ $product->is_organic ? 'Yes' : 'No' }}"
                                        data-active="{{ $product->is_active ? 'Active' : 'Inactive' }}"
                                        data-min="{{ $product->min_order_qty }}"
                                        data-max="{{ $product->max_order_qty }}"
                                        data-shelf="{{ $product->shelf_life_days }}"
                                        data-short="{{ $product->short_description }}"
                                        data-desc="{{ $product->description }}" data-image="{{ $image }}">
                                        👁
                                    </button>
                                </div>
                            </td>

                            <td class="px-3 py-2">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $product->brand?->name ?? '—' }}</td>

                            <td class="px-3 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    {{ $product->available_qty > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->available_qty }}
                                </span>
                            </td>

                            <td class="px-3 py-2">{{ $product->gst_percent }}%</td>

                            <td class="px-3 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $product->created_at->format('d M Y') }}
                            </td>

                            <td class="px-3 py-2 text-right relative">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6-2a2 2 0 100 4 2 2 0 000-4zm4-2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>

                                    <div x-cloak x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/10 z-50">
                                        <button type="button"
                                            class="view-product w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                            data-name="{{ $product->name }}" data-sku="{{ $product->sku }}"
                                            data-price="{{ $product->price }}"
                                            data-gst="{{ $product->gst_percent }}"
                                            data-desc="{{ $product->description }}"
                                            data-image="{{ $image }}">
                                            Quick View
                                        </button>

                                        <a href="{{ route('products.show', $product) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            View
                                        </a>

                                        <a href="{{ route('products.edit', $product) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        @include('products.partials.datatable-script')
        @include('products.partials.quick-view-script')
    @endpush

    {{-- Quick View Modal --}}
    @include('products.partials.quick-view-modal')
</x-layouts.app>
