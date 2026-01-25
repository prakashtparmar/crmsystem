<section
    x-data="{ open: {{ isset($customer) ? 'false' : 'true' }}, editing: {{ isset($customer) ? 'false' : 'true' }} }"

    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Location Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Geographic and regional classification
            </p>
        </div>

        <div class="flex gap-2">
            <button
                type="button"
                x-cloak
                x-show="!editing"
                @click="editing = true"
                class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                Edit
            </button>

            <button
                type="submit"
                x-cloak
                x-show="editing"
                class="text-xs px-3 py-1 rounded-md bg-blue-600 text-white
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm">
                Save
            </button>

            <button
                type="button"
                x-cloak
                x-show="editing"
                @click="editing = false"
                class="text-xs px-3 py-1 rounded-md bg-indigo-600 text-white border
                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 shadow-sm">
                Cancel
            </button>

            <button
                type="button"
                @click="open = !open"
                class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                <span x-text="open ? 'Hide' : 'Show'"></span>
            </button>
        </div>
    </div>

    <!-- Fields Wrapper -->
    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Region -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Region</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('region', $customer->region ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="region"
                value="{{ old('region', $customer->region ?? '') }}"
                placeholder="Region / Zone"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Area -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Area</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('area', $customer->area ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="area"
                value="{{ old('area', $customer->area ?? '') }}"
                placeholder="Operational area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Route -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Route</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('route', $customer->route ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="route"
                value="{{ old('route', $customer->route ?? '') }}"
                placeholder="Delivery route"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Beat -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Beat</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('beat', $customer->beat ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="beat"
                value="{{ old('beat', $customer->beat ?? '') }}"
                placeholder="Sales beat"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Territory -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Territory</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('territory', $customer->territory ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="territory"
                value="{{ old('territory', $customer->territory ?? '') }}"
                placeholder="Territory"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Zone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Zone</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('zone', $customer->zone ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="zone"
                value="{{ old('zone', $customer->zone ?? '') }}"
                placeholder="Zone"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Sales Person -->
        <div class="space-y-1 lg:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Sales Representative
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('sales_person', $customer->sales_person ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="sales_person"
                value="{{ old('sales_person', $customer->sales_person ?? '') }}"
                placeholder="Assigned sales person"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Warehouse -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Nearest Warehouse
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('warehouse', $customer->warehouse ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="warehouse"
                value="{{ old('warehouse', $customer->warehouse ?? '') }}"
                placeholder="Warehouse / Hub"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>
    </div>
</section>
