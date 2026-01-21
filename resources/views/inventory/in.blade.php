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
            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Stock In / Adjust') }}</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Stock In / Adjust') }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Add new stock or adjust existing stock for a product') }}
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

                <form method="POST" action="{{ route('inventory.in.store') }}" class="space-y-6">
                    @csrf

                    <!-- Stock Details -->
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Stock Information
                        </h2>

                        <div class="grid grid-cols-1 gap-5 max-w-xl">

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Product</label>
                                <select name="product_id"
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
                                    Choose the product for which stock is being added or adjusted.
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Warehouse</label>
                                <select name="warehouse_id"
                                        class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                               bg-white dark:bg-gray-900 px-3 py-2
                                               focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <option value="">-- Select Warehouse --</option>
                                    @foreach($warehouses as $w)
                                        <option value="{{ $w->id }}" @selected(old('warehouse_id') == $w->id)>
                                            {{ $w->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Select the warehouse where this stock change will apply.
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Quantity</label>
                                <input type="number"
                                       step="0.01"
                                       name="qty"
                                       value="{{ old('qty') }}"
                                       class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                              bg-white dark:bg-gray-900 px-3 py-2
                                              focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Enter quantity"
                                       required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Enter the amount to add or adjust for this product.
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Type</label>
                                <select name="type"
                                        class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                               bg-white dark:bg-gray-900 px-3 py-2
                                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="in" @selected(old('type', 'in') === 'in')>Stock In</option>
                                    <option value="adjust" @selected(old('type') === 'adjust')>Adjustment</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    “Stock In” adds new quantity. “Adjustment” corrects existing stock.
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Remarks</label>
                                <textarea name="remarks"
                                          rows="3"
                                          class="w-full rounded-md border border-gray-300 dark:border-gray-700
                                                 bg-white dark:bg-gray-900 px-3 py-2
                                                 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Optional notes">{{ old('remarks') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Optional notes such as damage, audit correction, or supplier reference.
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
                            {{ __('Submit') }}
                        </x-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
