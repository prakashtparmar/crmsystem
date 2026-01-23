<x-layouts.app>

    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Dashboard</a>
        <span class="mx-2">›</span>
        <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Orders</a>
        <span class="mx-2">›</span>
        <span>View</span>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-100 border border-red-200 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @php
        $steps = ['draft', 'confirmed', 'processing', 'shipped', 'delivered'];
        $currentIndex = array_search($order->status, $steps);
        $shipment = $order->shipments->last();
    @endphp

    <!-- Sticky Header -->
    <div class="mb-6 bg-white dark:bg-gray-800 border rounded-xl p-4 sticky top-2 z-20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ $order->order_code }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $order->customer_name }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize
                    {{ $order->status === 'delivered'
                        ? 'bg-green-100 text-green-700'
                        : ($order->status === 'cancelled'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-blue-100 text-blue-700') }}">
                    {{ $order->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- KPI Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 border rounded-xl p-4">
            <div class="text-xs text-gray-400">Grand Total</div>
            <div class="text-lg font-bold">₹{{ number_format($order->grand_total, 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 border rounded-xl p-4">
            <div class="text-xs text-gray-400">Paid</div>
            <div class="text-lg font-bold">₹{{ number_format($order->total_paid ?? 0, 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 border rounded-xl p-4">
            <div class="text-xs text-gray-400">Balance</div>
            <div class="text-lg font-bold">
                ₹{{ number_format($order->balance ?? $order->grand_total, 2) }}
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 border rounded-xl p-4">
            <div class="text-xs text-gray-400">Payment Status</div>
            <div class="text-lg font-bold capitalize">{{ $order->payment_status }}</div>
        </div>
    </div>

    <!-- Lifecycle -->
    <div class="mb-8 bg-white dark:bg-gray-800 border rounded-xl p-4">
        <div class="flex items-center justify-between">
            @foreach ($steps as $i => $step)
                <div class="flex items-center w-full">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $order->status === 'cancelled'
                                ? 'bg-red-500 text-white'
                                : ($i <= $currentIndex
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-200 text-gray-500') }}">
                            {{ $i + 1 }}
                        </div>
                        <div
                            class="mt-1 text-[11px] uppercase tracking-wide
                            {{ $i <= $currentIndex ? 'text-blue-600' : 'text-gray-400' }}">
                            {{ ucfirst($step) }}
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="flex-1 h-px mx-2 {{ $i < $currentIndex ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        <!-- MAIN -->
        <div class="xl:col-span-3 space-y-6">

            <!-- Order Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-4 text-[12px]">
                <h3 class="text-xs font-semibold uppercase tracking-wide mb-3">Order Details</h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-2">
                    <div><span class="block text-gray-400">Order Date</span>{{ $order->order_date?->format('d M Y') }}
                    </div>
                    <div><span class="block text-gray-400">Expected
                            Delivery</span>{{ $order->expected_delivery_at?->format('d M Y') ?? '—' }}</div>
                    <div><span class="block text-gray-400">Payment Status</span>{{ ucfirst($order->payment_status) }}
                    </div>
                    <div><span class="block text-gray-400">Payment Method</span>{{ $order->payment_method ?? '—' }}
                    </div>
                    <div><span class="block text-gray-400">Transaction ID</span><span
                            class="font-mono text-xs">{{ $order->transaction_id ?? '—' }}</span></div>
                    <div><span class="block text-gray-400">Paid
                            At</span>{{ $order->paid_at?->format('d M Y H:i') ?? '—' }}</div>
                    <div><span class="block text-gray-400">Completed
                            At</span>{{ $order->completed_at?->format('d M Y H:i') ?? '—' }}</div>
                    <div><span class="block text-gray-400">Created</span>{{ $order->created_at?->format('d M Y H:i') }}
                    </div>

                    <div class="col-span-2 sm:col-span-3 lg:col-span-4 pt-2">
                        <span class="block text-gray-400">Billing Address</span>
                        <div class="leading-snug">{{ $order->billing_address ?? '—' }}</div>
                    </div>

                    <div class="col-span-2 sm:col-span-3 lg:col-span-4 pt-2">
                        <span class="block text-gray-400">Shipping Address</span>
                        <div class="leading-snug">{{ $order->shipping_address ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                <h3 class="text-sm font-semibold uppercase tracking-wide mb-4">Items</h3>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Price</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item->product_name }}</td>
                                    <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right text-lg font-bold">
                    Grand Total: ₹{{ number_format($order->grand_total, 2) }}
                </div>
            </div>

            @if ($shipment)
                <!-- Shipment Details (unchanged logic, styled) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-semibold uppercase tracking-wide">Shipment Details</h3>
                        <button id="editShipmentBtn"
                            class="text-[11px] px-2 py-0.5 rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                            Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2 text-[12px]">
                        <div><span class="block text-gray-400">Shipment ID</span>#{{ $shipment->id }}</div>
                        <div><span class="block text-gray-400">Order ID</span>#{{ $shipment->order_id }}</div>
                        <div>
                            <span class="block text-gray-400">Status</span>
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                                {{ $shipment->status === 'delivered'
                                    ? 'bg-green-100 text-green-700'
                                    : ($shipment->status === 'shipped'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </div>
                        <div><span class="block text-gray-400">Carrier</span>{{ $shipment->carrier ?? '—' }}</div>
                        <div><span class="block text-gray-400">Tracking No.</span><span
                                class="font-mono">{{ $shipment->tracking_number ?? '—' }}</span></div>
                        <div><span class="block text-gray-400">Shipped
                                At</span>{{ $shipment->shipped_at?->format('d M Y') ?? '—' }}</div>
                        <div><span class="block text-gray-400">Delivered
                                At</span>{{ $shipment->delivered_at?->format('d M Y') ?? '—' }}</div>
                        <div><span
                                class="block text-gray-400">Created</span>{{ $shipment->created_at?->format('d M Y H:i') ?? '—' }}
                        </div>
                        <div><span class="block text-gray-400">Last
                                Updated</span>{{ $shipment->updated_at?->format('d M Y H:i') ?? '—' }}</div>

                        <div class="col-span-2 sm:col-span-3 pt-1">
                            <span class="block text-gray-400">Address Snapshot</span>
                            <div class="leading-snug">{{ $shipment->address_snapshot ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT CONTROL RAIL -->
        <div class="space-y-6 xl:sticky xl:top-24 h-fit">
            @php
                $flow = [
                    'draft' => ['confirmed', 'cancelled'],
                    'confirmed' => ['processing', 'cancelled'],
                    'processing' => ['shipped', 'cancelled'],
                    'shipped' => ['delivered'],
                    'delivered' => [],
                    'cancelled' => [],
                ];

                $allowed = $flow[$order->status] ?? [];
                $canShip = (bool) $order->invoice;
            @endphp

            <!-- CONTROL RAIL -->

<!-- Bulk Payments -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
    <h3 class="text-[11px] font-semibold uppercase tracking-wider mb-2 text-gray-600 dark:text-gray-300">
        Bulk Payments
    </h3>

    <p class="text-[11px] text-gray-500 mb-3">
        Upload an Excel/CSV file to apply payments to multiple orders using Order ID.
    </p>

    <a href="{{ route('orders.payments.bulk.form') }}"
        class="block w-full text-center px-3 py-2 text-xs rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
        Upload Excel Sheet
    </a>
</div>

            <!-- Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider mb-3 text-gray-600 dark:text-gray-300">
                    Order Status
                </h3>

                @if (count($allowed))
                    <form id="statusForm" action="{{ route('orders.status.update', $order) }}" method="POST"
                        class="space-y-3">
                        @csrf
                        @method('PUT')

                        <select id="statusSelect" name="status"
                            class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            @foreach ($allowed as $s)
                                @if ($s === 'shipped' && !$canShip)
                                    <option value="{{ $s }}" disabled>
                                        {{ ucfirst($s) }} (Generate invoice first)
                                    </option>
                                @else
                                    <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                                @endif
                            @endforeach
                        </select>

                        @if ($order->status === 'processing' && !$canShip)
                            <p class="text-[11px] text-red-500">
                                Generate invoice before you can ship this order.
                            </p>
                        @endif

                        <x-button type="primary" class="w-full text-sm">Update Status</x-button>
                    </form>

                    {{-- Hidden form used when "Shipped" is selected --}}
                    <form id="shipStatusForm" action="{{ route('orders.status.update', $order) }}" method="POST"
                        class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="shipped">
                    </form>
                @else
                    <div class="text-xs text-gray-500 text-center py-3">
                        No further status changes allowed.
                    </div>
                @endif
            </div>

            <!-- Invoice -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider mb-3 text-gray-600 dark:text-gray-300">
                    Invoice
                </h3>

                @if ($order->status === 'cancelled')
                    <div class="text-xs text-red-500">
                        This order is cancelled. Invoice cannot be generated.
                    </div>
                @elseif ($order->invoice)
                    <div class="space-y-2 text-xs mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Invoice No</span>
                            <span class="font-medium">{{ $order->invoice->invoice_number }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Amount</span>
                            <span class="font-medium">₹{{ number_format($order->invoice->amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-400">Generated</span>
                            <span class="font-medium">
                                {{ $order->invoice->created_at?->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('orders.invoice.download', $order) }}"
                            class="px-3 py-2 text-xs text-center rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                            Download
                        </a>

                        <a href="{{ route('orders.invoice.print', $order) }}" target="_blank"
                            class="px-3 py-2 text-xs text-center rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                            Print
                        </a>
                    </div>
                @else
                    <form action="{{ route('orders.invoice.store', $order) }}" method="POST">
                        @csrf
                        <x-button type="primary" class="w-full text-sm">Generate Invoice</x-button>
                    </form>
                @endif
            </div>

            <!-- Payments -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider mb-2 text-gray-600 dark:text-gray-300">
                    Payments
                </h3>

                <div class="text-[11px] text-gray-500 mb-3">
                    Total: ₹{{ number_format($order->grand_total, 2) }} <br>
                    Paid: ₹{{ number_format($order->total_paid ?? 0, 2) }} <br>
                    Balance: ₹{{ number_format($order->balance ?? $order->grand_total, 2) }}
                </div>

                @if ($order->payments->count())
                    <div class="mb-4 overflow-x-auto border rounded-lg">
                        <table class="min-w-full text-[11px]">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-2 py-1 text-left">Date</th>
                                    <th class="px-2 py-1 text-left">Method</th>
                                    <th class="px-2 py-1 text-right">Amount</th>
                                    <th class="px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($order->payments as $p)
                                    <tr>
                                        <td class="px-2 py-1">{{ $p->paid_at->format('d M Y') }}</td>
                                        <td class="px-2 py-1 capitalize">{{ $p->method }}</td>
                                        <td class="px-2 py-1 text-right">₹{{ number_format($p->amount, 2) }}</td>
                                        <td class="px-2 py-1 text-right">
                                            ₹{{ number_format($p->balance_after, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($order->payment_status !== 'paid')
                    <form action="{{ route('orders.payments.store', $order) }}" method="POST"
                        class="grid grid-cols-1 gap-2">
                        @csrf

                        <select name="method"
                            class="rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Card</option>
                            <option value="cheque">Cheque</option>
                        </select>

                        <div>
                            <input type="text" name="amount" id="amount" placeholder="Amount"
                                class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                oninput="validateAmount(this)">

                            <p id="amountError" class="text-[11px] text-red-600 mt-1 hidden">
                                Only whole numbers are allowed.
                            </p>
                        </div>

                        <input type="date" name="paid_at" value="{{ now()->format('Y-m-d') }}"
                            class="rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">

                        <input name="reference" placeholder="Reference (optional)"
                            class="rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">

                        <x-button type="primary" class="w-full text-sm">Add Payment</x-button>
                    </form>
                @else
                    <div class="text-xs text-green-600 text-center mt-2">
                        This order is fully paid.
                    </div>
                @endif
            </div>

            <script>
                function validateAmount(el) {
                    const error = document.getElementById('amountError');

                    if (!/^\d*$/.test(el.value)) {
                        el.value = el.value.replace(/\D/g, '');
                        error.classList.remove('hidden');
                    } else {
                        error.classList.add('hidden');
                    }
                }
            </script>

            <!-- Shipment Modal -->
            <div id="shipmentModal"
                class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200">
                            Shipment Details
                        </h3>
                        <button type="button" id="closeShipment"
                            class="text-xs px-2 py-1 rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                            ✕
                        </button>
                    </div>

                    <form id="shipmentForm" action="{{ route('orders.shipments.store', $order) }}" method="POST"
                        class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-[11px] text-gray-500 mb-1">Carrier</label>
                            <input name="carrier" placeholder="e.g. BlueDart, FedEx"
                                value="{{ $shipment->carrier ?? '' }}"
                                class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        </div>

                        <div>
                            <label class="block text-[11px] text-gray-500 mb-1">Tracking Number</label>
                            <input name="tracking_number" placeholder="AWB / Consignment No."
                                value="{{ $shipment->tracking_number ?? '' }}"
                                class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        </div>

                        <div>
                            <label class="block text-[11px] text-gray-500 mb-1">Shipped Date</label>
                            <input type="date" name="shipped_at"
                                value="{{ $shipment?->shipped_at?->format('Y-m-d') ?? now()->format('Y-m-d') }}"
                                class="w-full rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        </div>

                        <div class="flex justify-end gap-2 pt-3">
                            <button type="button" id="closeShipmentAlt"
                                class="px-4 py-2 text-sm rounded-md border hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <x-button type="primary" class="text-sm px-4">Save & Ship</x-button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                const statusForm = document.getElementById('statusForm');
                const statusSelect = document.getElementById('statusSelect');
                const modal = document.getElementById('shipmentModal');
                const closeBtn = document.getElementById('closeShipment');
                const closeBtnAlt = document.getElementById('closeShipmentAlt');
                const editBtn = document.getElementById('editShipmentBtn');
                const shipmentForm = document.getElementById('shipmentForm');

                // Intercept status change
                if (statusForm && statusSelect) {
                    statusForm.addEventListener('submit', function(e) {
                        const nextStatus = statusSelect.value;

                        // When "shipped" is selected → open Shipment modal
                        if (nextStatus === 'shipped') {
                            e.preventDefault();
                            modal.classList.remove('hidden');
                            return;
                        }

                        // When "cancelled" is selected → ask for confirmation
                        if (nextStatus === 'cancelled') {
                            const ok = confirm(
                                'Are you sure you want to cancel this order?\n\n' +
                                'This action will release reserved stock and cannot be undone.'
                            );

                            if (!ok) {
                                e.preventDefault();
                                return;
                            }
                        }
                    });
                }

                // Open shipment modal when clicking "Edit"
                if (editBtn) {
                    editBtn.addEventListener('click', () => modal.classList.remove('hidden'));
                }

                // Close shipment modal
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
                }
                if (closeBtnAlt) {
                    closeBtnAlt.addEventListener('click', () => modal.classList.add('hidden'));
                }

                // After saving shipment, actually update order status to "shipped"
                if (shipmentForm) {
                    shipmentForm.addEventListener('submit', function() {
                        setTimeout(() => {
                            const shipStatusForm = document.getElementById('shipStatusForm');
                            if (shipStatusForm) {
                                shipStatusForm.submit();
                            }
                        }, 50);
                    });
                }
            </script>


</x-layouts.app>
