<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Inventory') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Inventory Overview') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Track product stock across warehouses') }}
            </p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-2">
    <a href="{{ route('inventory.in.create') }}"
       class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700">
        + Stock In / Adjust
    </a>

    <a href="{{ route('inventory.transfer.create') }}"
       class="px-4 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700">
        ⇄ Transfer Stock
    </a>
</div>

    </div>

    <div class="p-6">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
            <table id="inventoryTable" class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Product</th>
                        <th class="px-3 py-2">SKU</th>
                        <th class="px-3 py-2">Warehouse</th>
                        <th class="px-3 py-2 text-right">Physical Qty</th>
                        <th class="px-3 py-2 text-right">Reserved</th>
                        <th class="px-3 py-2 text-right">Available</th>
                        <th class="px-3 py-2">Updated At</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($stocks as $stock)
                        @php
                            $available = $stock->quantity - $stock->reserved_qty;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-gray-500">{{ $stock->id }}</td>

                            <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                {{ $stock->product->name }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $stock->product->sku ?? '—' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $stock->warehouse->name }}
                            </td>

                            <td class="px-3 py-2 text-right">
                                {{ $stock->quantity }}
                            </td>

                            <td class="px-3 py-2 text-right">
                                {{ $stock->reserved_qty }}
                            </td>

                            <td class="px-3 py-2 text-right font-semibold">
                                <span
                                    class="px-2 py-1 rounded-full text-xs
                                    {{ $available <= 0
                                        ? 'bg-red-100 text-red-700'
                                        : ($available < 10
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-green-100 text-green-700') }}">
                                    {{ $available }}
                                </span>
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $stock->updated_at?->format('d M Y H:i') ?? '—' }}
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
                $('#inventoryTable').DataTable({
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
            });
        </script>
    @endpush
</x-layouts.app>
