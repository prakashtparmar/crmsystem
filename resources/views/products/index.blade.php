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
                        <th class="px-3 py-2">Name</th>
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">
                                <input type="checkbox" class="row-checkbox" value="{{ $product->id }}">
                            </td>

                            <td class="px-3 py-2 text-gray-500">{{ $product->id }}</td>

                            <!-- Product Name as Quick View -->
                            <td class="px-3 py-2 font-medium">
                                <button type="button"
                                    class="view-product text-left text-blue-600 hover:underline dark:text-blue-400"
                                    data-name="{{ $product->name }}" data-sku="{{ $product->sku }}"
                                    data-hsn="{{ $product->hsn_code }}" data-price="{{ $product->price }}"
                                    data-cost="{{ $product->cost_price }}" data-gst="{{ $product->gst_percent }}"
                                    data-organic="{{ $product->is_organic ? 'Yes' : 'No' }}"
                                    data-active="{{ $product->is_active ? 'Active' : 'Inactive' }}"
                                    data-min="{{ $product->min_order_qty }}" data-max="{{ $product->max_order_qty }}"
                                    data-shelf="{{ $product->shelf_life_days }}"
                                    data-short="{{ $product->short_description }}"
                                    data-desc="{{ $product->description }}">
                                    {{ $product->name }}
                                </button>

                            </td>

                            <td class="px-3 py-2">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $product->brand?->name ?? '—' }}</td>
<td class="px-3 py-2">
    <span class="px-2 py-1 text-xs rounded-full
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
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
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
                                            data-price="{{ $product->price }}" data-gst="{{ $product->gst_percent }}"
                                            data-desc="{{ $product->description }}">
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#productsTable').DataTable({
                    dom: 'lBfrtip',
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 50, 100],
                        [5, 10, 50, 100]
                    ],
                    autoWidth: false,
                    scrollX: true,
                    responsive: false,
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Export Excel'
                    }],
                });

                $('#selectAll').on('change', function() {
                    $('.row-checkbox').prop('checked', this.checked);
                });
            });

            // Quick View Modal Logic (Enhanced)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('view-product')) {
                    const d = e.target.dataset;

                    pm_name.textContent = d.name || '—';
                    pm_sku.textContent = d.sku || '—';
                    pm_hsn.textContent = d.hsn || '—';
                    pm_price.textContent = d.price || '—';
                    pm_cost.textContent = d.cost || '—';
                    pm_tax.textContent = d.gst || '—';
                    pm_organic.textContent = d.organic || '—';
                    pm_active.textContent = d.active || '—';
                    pm_min.textContent = d.min || '—';
                    pm_max.textContent = d.max || '—';
                    pm_shelf.textContent = d.shelf ? d.shelf + ' days' : '—';
                    pm_short.textContent = d.short || '—';
                    pm_desc.textContent = d.desc || '—';

                    productModal.classList.remove('hidden');
                    productModal.classList.add('flex');

                    requestAnimationFrame(() => {
                        productModalCard.classList.remove('scale-95', 'opacity-0');
                        productModalCard.classList.add('scale-100', 'opacity-100');
                    });
                }

                if (
                    e.target.id === 'productModal' ||
                    e.target.id === 'closeProductModal' ||
                    e.target.id === 'closeProductModalBtn'
                ) {
                    productModalCard.classList.add('scale-95', 'opacity-0');

                    setTimeout(() => {
                        productModal.classList.add('hidden');
                        productModal.classList.remove('flex');
                    }, 150);
                }
            });

            // ESC key support
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    productModalCard.classList.add('scale-95', 'opacity-0');

                    setTimeout(() => {
                        productModal.classList.add('hidden');
                        productModal.classList.remove('flex');
                    }, 150);
                }
            });
        </script>
    @endpush


    <!-- Product Quick View Modal -->

    <div id="productModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm px-3">
        <div id="productModalCard"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md
               h-[80vh] flex flex-col
               transform transition-all duration-200 scale-95 opacity-0">

            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    Product Details
                </h3>

                <button id="closeProductModal"
                    class="p-1 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-3 space-y-2 text-xs">

                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                    <div class="text-[10px] text-gray-500">Name</div>
                    <div id="pm_name" class="font-medium text-gray-800 dark:text-gray-100"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">SKU</div>
                        <div id="pm_sku"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">HSN</div>
                        <div id="pm_hsn"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Price</div>
                        <div id="pm_price" class="font-semibold text-blue-600"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Cost</div>
                        <div id="pm_cost"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">GST</div>
                        <div id="pm_tax"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Organic</div>
                        <div id="pm_organic"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Status</div>
                        <div id="pm_active"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Min</div>
                        <div id="pm_min"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Max</div>
                        <div id="pm_max"></div>
                    </div>

                    <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                        <div class="text-[10px] text-gray-500">Shelf</div>
                        <div id="pm_shelf"></div>
                    </div>
                </div>

                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                    <div class="text-[10px] text-gray-500">Short</div>
                    <div id="pm_short"></div>
                </div>

                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-900">
                    <div class="text-[10px] text-gray-500">Description</div>
                    <div id="pm_desc"></div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button id="closeProductModalBtn"
                    class="px-3 py-1.5 rounded-md border text-xs text-gray-700 dark:text-gray-300
                       hover:bg-gray-100 dark:hover:bg-gray-700">
                    Close
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
