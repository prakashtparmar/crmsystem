<div class="p-6">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4">

        <table id="ordersTable" class="w-full text-xs min-w-max border-collapse">
            <!-- colgroup will be generated automatically -->

            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                <tr>
                    @foreach (['', 'ID', 'UUID', 'Order Code', 'Customer', 'Email', 'Phone', 'Order Date', 'Created At', 'Updated At', 'Expected', 'Sub Total', 'Tax', 'Shipping', 'Discount', 'Grand Total', 'Status', 'Payment', 'Method', 'Transaction', 'Paid At', 'Completed', 'Customer ID', 'User ID', 'Billing Address', 'Shipping Address', 'Meta', 'Created By', 'Updated By', 'Deleted At', 'Confirmed At', 'Cancelled At', 'Invoice #', 'Invoice Status', 'Shipment Status', 'Carrier', 'Tracking', 'Shipped At', 'Delivered At', 'Total Paid', 'Balance', 'Action'] as $h)
                        <th class="px-3 py-2 whitespace-nowrap text-left">
                            @if ($loop->first)
                                <input type="checkbox" id="selectAll">
                            @else
                                {{ $h }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($orders as $order)
                    @php
                        $paid = $order->total_paid ?? 0;
                        $balance = $order->grand_total - $paid;
                    @endphp

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 align-top">
                        <td class="px-3 py-2 text-center">
                            <input type="checkbox" class="row-checkbox" value="{{ $order->id }}">
                        </td>

                        <td class="px-3 py-2 text-gray-500">{{ $order->id }}</td>
                        <td class="px-3 py-2 text-gray-500 break-all">{{ $order->uuid }}</td>

                        <td class="px-3 py-2">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">
                                {{ $order->order_code }}
                            </a>
                        </td>

                        <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                            {{ $order->customer_name }}
                        </td>

                        <td class="px-3 py-2 break-all">{{ $order->customer_email ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->customer_phone ?? '—' }}</td>

                        <td class="px-3 py-2 text-gray-500">{{ $order->order_date?->format('d M Y') ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->created_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->updated_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->expected_delivery_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td class="px-3 py-2">{{ number_format($order->sub_total, 2) }}</td>
                        <td class="px-3 py-2">{{ number_format($order->tax_amount, 2) }}</td>
                        <td class="px-3 py-2">{{ number_format($order->shipping_amount, 2) }}</td>
                        <td class="px-3 py-2">{{ number_format($order->discount_amount, 2) }}</td>
                        <td class="px-3 py-2 font-semibold">{{ number_format($order->grand_total, 2) }}</td>

                        <td class="px-3 py-2">
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
                        <td class="px-3 py-2 break-all">{{ $order->transaction_id ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->paid_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->completed_at?->format('d M Y H:i') ?? '—' }}</td>

                        <td class="px-3 py-2 text-gray-500">{{ $order->customer?->customer_code ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->creator?->id ?? '—' }}</td>

                        <td class="px-3 py-2 max-w-xs break-words whitespace-pre-line">{!! nl2br(e($order->billing_address ?? '—')) !!}</td>
                        <td class="px-3 py-2 max-w-xs break-words whitespace-pre-line">{!! nl2br(e($order->shipping_address ?? '—')) !!}</td>

                        <td class="px-3 py-2 break-all text-gray-500">
                            {{ $order->meta ? json_encode($order->meta) : '—' }}</td>

                        <td class="px-3 py-2 text-gray-500">{{ $order->creator?->name ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->updated_by ?? '—' }}</td>

                        <td class="px-3 py-2 text-gray-500">{{ $order->deleted_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->confirmed_at?->format('d M Y H:i') ?? '—' }}
                        </td>
                        <td class="px-3 py-2 text-gray-500">{{ $order->cancelled_at?->format('d M Y H:i') ?? '—' }}
                        </td>

                        <td class="px-3 py-2">{{ $order->invoice?->invoice_number ?? '—' }}</td>
                        <td class="px-3 py-2 capitalize">{{ $order->invoice?->status ?? '—' }}</td>

                        <td class="px-3 py-2 capitalize">{{ $order->shipments->first()?->status ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->shipments->first()?->carrier ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->shipments->first()?->tracking_number ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $order->shipments->first()?->shipped_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-3 py-2">{{ $order->shipments->first()?->delivered_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td class="px-3 py-2">{{ number_format($paid, 2) }}</td>
                        <td class="px-3 py-2 {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($balance, 2) }}
                        </td>

                        <td class="px-3 py-2 text-right relative overflow-visible">
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
                                    class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/10 z-[100]">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                    <a href="{{ route('orders.edit', $order) }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
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
