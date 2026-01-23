<x-layouts.app>

    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Dashboard</a>
        <span class="mx-2">›</span>
        <a href="{{ route('order-returns.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
            Order Returns
        </a>
        <span class="mx-2">›</span>
        <span>{{ $return->return_number }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Return {{ $return->return_number }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Review and manage this return request.
        </p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- MAIN -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Return Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-4 text-sm">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <span class="block text-gray-400 text-xs">Return No</span>
                        <span class="font-semibold">{{ $return->return_number }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Order</span>
                        <span class="font-semibold">#{{ $return->order_id }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Status</span>
                        <span class="font-semibold capitalize">{{ $return->status }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 text-xs">Refund</span>
                        <span class="font-semibold">₹{{ number_format($return->refund_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Returned Items -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                <h3 class="text-sm font-semibold uppercase tracking-wide mb-4">Returned Items</h3>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($return->items as $item)
                                <tr>
                                    <td class="px-4 py-2">
                                        {{ $item->orderItem->product_name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ $item->qty }}</td>
                                    <td class="px-4 py-2 text-right">
                                        ₹{{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reason -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-4 text-sm">
                <h3 class="text-xs font-semibold uppercase tracking-wide mb-2">Customer Reason</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ $return->reason ?? '—' }}
                </p>
            </div>
        </div>

        <!-- ACTION PANEL -->
        <div class="space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider mb-3 text-gray-600 dark:text-gray-300">
                    Actions
                </h3>

                @if ($return->status === 'pending')
                    <form action="{{ route('order-returns.update', $return) }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="approved">

                        <x-button type="primary" class="w-full text-sm">
                            Approve Return
                        </x-button>
                    </form>

                    <form action="{{ route('order-returns.update', $return) }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="rejected">

                        <button
                            class="w-full px-3 py-2 text-sm rounded-md border border-red-300 text-red-600 hover:bg-red-50">
                            Reject Return
                        </button>
                    </form>
                @elseif ($return->status === 'approved')
                    <form action="{{ route('order-returns.update', $return) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="refunded">

                        <x-button type="primary" class="w-full text-sm">
                            Mark as Refunded
                        </x-button>
                    </form>
                @else
                    <div class="text-xs text-gray-500 text-center">
                        No further actions available.
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.app>
