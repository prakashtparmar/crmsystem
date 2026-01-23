<x-layouts.app>

    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Dashboard</a>
        <span class="mx-2">›</span>
        <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Orders</a>
        <span class="mx-2">›</span>
        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
            {{ $order->order_code }}
        </a>
        <span class="mx-2">›</span>
        <span>Create Return</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Create Order Return
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Select items and quantities to return for this order.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-100 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('order-returns.store') }}" method="POST" class="space-y-6" onsubmit="return validateReturnForm();">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <!-- Order Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-4 text-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="block text-gray-400 text-xs">Order</span>
                    <span class="font-semibold">{{ $order->order_code }}</span>
                </div>
                <div>
                    <span class="block text-gray-400 text-xs">Customer</span>
                    <span class="font-semibold">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="block text-gray-400 text-xs">Order Date</span>
                    <span>{{ $order->order_date?->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-gray-400 text-xs">Grand Total</span>
                    <span class="font-semibold">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wide mb-4">Select Items to Return</h3>

            <div class="overflow-x-auto rounded-lg border">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-right">Ordered Qty</th>
                            <th class="px-4 py-2 text-right">Price</th>
                            <th class="px-4 py-2 text-right">Return Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->product_name }}</td>
                                <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-right">₹{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-2 text-right">
                                    <input
                                        type="number"
                                        name="items[{{ $item->id }}]"
                                        min="0"
                                        max="{{ $item->quantity }}"
                                        value="0"
                                        class="w-24 rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-right return-qty"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="mt-2 text-[11px] text-gray-500">
                Enter only the quantity you want to return. Leave 0 for items not being returned.
            </p>
        </div>

        <!-- Reason -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
            <h3 class="text-sm font-semibold uppercase tracking-wide mb-3">Return Reason</h3>

            <textarea
                name="reason"
                rows="4"
                placeholder="Describe why the customer is returning these items..."
                class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900"
            >{{ old('reason') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('orders.show', $order) }}"
                class="px-4 py-2 text-sm rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                Cancel
            </a>

            <x-button type="primary" class="text-sm px-6">
                Create Return
            </x-button>
        </div>
    </form>

    <script>
        function validateReturnForm() {
            const inputs = document.querySelectorAll('.return-qty');
            let hasQty = false;

            inputs.forEach(i => {
                if (parseInt(i.value || 0) > 0) {
                    hasQty = true;
                }
            });

            if (!hasQty) {
                alert('Please enter at least one item quantity to return.');
                return false;
            }

            return true;
        }
    </script>

</x-layouts.app>
