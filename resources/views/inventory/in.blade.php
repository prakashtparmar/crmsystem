<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('inventory.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Inventory') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Stock In / Adjust') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Stock In / Adjust') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Add new stock or adjust existing stock for a product') }}
        </p>
    </div>

    <div class="p-6">
        <div class="max-w-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('inventory.in.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Product</label>
                    <select name="product_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2"
                            required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Warehouse</label>
                    <select name="warehouse_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2"
                            required>
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" @selected(old('warehouse_id') == $w->id)>
                                {{ $w->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Quantity</label>
                    <input type="number"
                           step="0.01"
                           name="qty"
                           value="{{ old('qty') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2"
                           placeholder="Enter quantity"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2">
                        <option value="in" @selected(old('type', 'in') === 'in')>Stock In</option>
                        <option value="adjust" @selected(old('type') === 'adjust')>Adjustment</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Remarks</label>
                    <textarea name="remarks"
                              class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 p-2"
                              rows="3"
                              placeholder="Optional notes">{{ old('remarks') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                        Submit
                    </button>

                    <a href="{{ route('inventory.index') }}"
                       class="px-4 py-2 border rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
