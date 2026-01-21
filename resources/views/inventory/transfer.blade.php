    <x-layouts.app>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <div class="mb-5 flex items-center text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
                <span class="mx-2">›</span>
                <a href="{{ route('inventory.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Inventory') }}</a>
                <span class="mx-2">›</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Transfer Stock') }}</span>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Transfer Stock') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Move stock from one warehouse to another') }}
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

                    <form method="POST" action="{{ route('inventory.transfer.store') }}" class="space-y-6">
                        @csrf

                        <!-- Transfer Details -->
                        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                                Transfer Information
                            </h2>

                            <div class="grid grid-cols-1 gap-5 max-w-xl">

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Product</label>
                                    <select id="productSelect" name="product_id"
                                            class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                                bg-white dark:bg-gray-900 px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Select the product whose stock you want to move.
                                    </p>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">From Warehouse</label>
                                    <select id="fromWarehouseSelect" name="from_warehouse"
                                            class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                                bg-white dark:bg-gray-900 px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <option value="">-- Select Source --</option>
                                        @foreach($warehouses as $w)
                                            <option value="{{ $w->id }}" @selected(old('from_warehouse') == $w->id)>
                                                {{ $w->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Choose the warehouse where the stock currently exists.
                                    </p>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">To Warehouse</label>
                                    <select name="to_warehouse"
                                            class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                                bg-white dark:bg-gray-900 px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <option value="">-- Select Destination --</option>
                                        @foreach($warehouses as $w)
                                            <option value="{{ $w->id }}" @selected(old('to_warehouse') == $w->id)>
                                                {{ $w->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Select the destination warehouse.
                                    </p>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Quantity</label>
                                    <input id="qtyInput" type="number"
                                        step="0.01"
                                        name="qty"
                                        value="{{ old('qty') }}"
                                        class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                                bg-gray-100 dark:bg-gray-800 px-3 py-2
                                                focus:outline-none focus:ring-2 focus:ring-blue-500
                                                disabled:opacity-60 disabled:cursor-not-allowed"
                                        placeholder="Enter quantity"
                                        disabled
                                        required>

                                    <p id="availableText" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Select product and source warehouse to see available quantity.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 rounded-b-xl">
                            <a href="{{ route('inventory.index') }}"
                            class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                            <x-button type="primary" class="px-4 py-1.5 text-sm">
                                {{ __('Transfer') }}
                            </x-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                const product = document.getElementById('productSelect');
                const fromWh = document.getElementById('fromWarehouseSelect');
                const qty = document.getElementById('qtyInput');
                const info = document.getElementById('availableText');

                async function loadAvailable() {
                    const p = product.value;
                    const w = fromWh.value;

                    if (!p || !w) {
                        qty.value = '';
                        qty.disabled = true;
                        qty.removeAttribute('max');
                        info.textContent = 'Select product and source warehouse to see available quantity.';
                        return;
                    }

                    try {
                        const res = await fetch(`{{ url('/inventory/available') }}?product_id=${p}&warehouse_id=${w}`);
                        const data = await res.json();

                        const available = parseFloat(data.available || 0);

                        qty.disabled = false;
                        qty.max = available;
                        qty.value = '';

                        info.innerHTML = available > 0
                            ? `Available in this warehouse: <strong>${available}</strong>`
                            : `<span class="text-red-600">No stock available in this warehouse.</span>`;

                        if (available <= 0) {
                            qty.disabled = true;
                        }
                    } catch (e) {
                        info.textContent = 'Unable to fetch available quantity.';
                    }
                }

                product.addEventListener('change', loadAvailable);
                fromWh.addEventListener('change', loadAvailable);

                qty.addEventListener('input', function () {
                    const max = parseFloat(this.max || 0);
                    if (max && parseFloat(this.value) > max) {
                        this.value = max;
                    }
                });
            </script>
        @endpush
    </x-layouts.app>
