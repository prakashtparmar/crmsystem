<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Orders') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Orders') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all customer orders') }}
            </p>
        </div>

        <a href="{{ route('orders.create') }}">
            <x-button type="primary">
                + {{ __('New Order') }}
            </x-button>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-200">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-6">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
            <table id="ordersTable" class="w-full text-xs border-collapse">

                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-center w-10">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">UUID</th>
                        <th class="px-3 py-2">Order Code</th>
                        <th class="px-3 py-2">Customer</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Phone</th>
                        <th class="px-3 py-2">Order Date</th>
                        <th class="px-3 py-2">Created At</th>
                        <th class="px-3 py-2">Updated At</th>
                        <th class="px-3 py-2">Expected</th>
                        <th class="px-3 py-2">Sub Total</th>
                        <th class="px-3 py-2">Tax</th>
                        <th class="px-3 py-2">Shipping</th>
                        <th class="px-3 py-2">Discount</th>
                        <th class="px-3 py-2">Grand Total</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Payment</th>
                        <th class="px-3 py-2">Method</th>
                        <th class="px-3 py-2">Transaction</th>
                        <th class="px-3 py-2">Paid At</th>
                        <th class="px-3 py-2">Completed</th>
                        <th class="px-3 py-2">Customer ID</th>
                        <th class="px-3 py-2">User ID</th>
                        <th class="px-3 py-2">Billing Address</th>
                        <th class="px-3 py-2">Shipping Address</th>
                        <th class="px-3 py-2">Meta</th>
                        <th class="px-3 py-2">Created By</th>
                        <th class="px-3 py-2">Updated By</th>
                        <th class="px-3 py-2">Deleted At</th>
                        <th class="px-3 py-2 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">
                                <input type="checkbox" class="row-checkbox" value="{{ $order->id }}">
                            </td>

                            <td class="px-3 py-2 text-gray-500">{{ $order->id }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $order->uuid }}</td>

                            <!-- Order Code as Link -->
                            <td class="px-3 py-2 text-gray-500">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">
                                    {{ $order->order_code }}
                                </a>
                            </td>

                            <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                {{ $order->customer_name }}
                            </td>

                            <td class="px-3 py-2">{{ $order->customer_email ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $order->customer_phone ?? '—' }}</td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->order_date?->format('d M Y') ?? '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->created_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->updated_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->expected_delivery_at?->format('d M Y') ?? '—' }}
                            </td>

                            <td class="px-3 py-2">{{ number_format($order->sub_total, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($order->tax_amount, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($order->shipping_amount, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($order->discount_amount, 2) }}</td>
                            <td class="px-3 py-2 font-semibold">{{ number_format($order->grand_total, 2) }}</td>

                            <td class="px-3 py-2 capitalize">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                        {{ $order->status === 'delivered'
                            ? 'bg-green-100 text-green-700'
                            : ($order->status === 'cancelled'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="px-3 py-2 capitalize">{{ $order->payment_status }}</td>
                            <td class="px-3 py-2">{{ $order->payment_method ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $order->transaction_id ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $order->paid_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $order->completed_at?->format('d M Y H:i') ?? '—' }}</td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->customer?->customer_code ?? '—' }}
                            </td>


                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->creator?->id ?? '—' }}
                            </td>


                            <td class="px-3 py-2 max-w-xs whitespace-pre-line text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($order->billing_address ?? '—')) !!}
                            </td>

                            <td class="px-3 py-2 max-w-xs whitespace-pre-line text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($order->shipping_address ?? '—')) !!}
                            </td>


                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->meta ? json_encode($order->meta) : '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->creator?->name ?? '—' }}
                            </td>


                            <td class="px-3 py-2 text-gray-500">{{ $order->updated_by ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-500">
                                {{ $order->deleted_at?->format('d M Y H:i') ?? '—' }}
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
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            View
                                        </a>
                                        <a href="{{ route('orders.edit', $order) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                            onsubmit="return confirm('Delete this order?');">
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
                $('#ordersTable').DataTable({
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
        </script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

        <style>
            /* Compact modern table */
            #ordersTable th,
            #ordersTable td {
                padding: 6px 8px !important;
                white-space: nowrap;
            }

            #ordersTable thead th {
                position: sticky;
                top: 0;
                z-index: 10;
                background: #f9fafb;
            }

            .dark #ordersTable thead th {
                background: #374151;
            }

            #ordersTable tbody tr:hover {
                background-color: rgba(59, 130, 246, 0.05);
            }

            /* DataTable controls */
            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border-radius: 0.375rem;
                padding: 4px 8px;
                border: 1px solid #e5e7eb;
            }

            .dataTables_wrapper .dt-buttons .dt-button {
                background: #2563eb;
                color: #fff;
                border-radius: 6px;
                padding: 6px 12px;
                border: none;
                margin-right: 6px;
            }

            .dataTables_wrapper .dt-buttons .dt-button:hover {
                background: #1d4ed8;
            }
        </style>
    @endpush
</x-layouts.app>
