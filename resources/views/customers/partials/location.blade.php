<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Location Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Geographic and regional classification
            </p>
        </div>

        <button type="button"
            data-toggle="locationSection"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Show
        </button>
    </div>

    <!-- Fields Wrapper (Hidden by default) -->
    <div id="locationSection" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Region -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Region
            </label>
            <input
                name="region"
                value="{{ old('region') }}"
                placeholder="Region / Zone"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Area -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Area
            </label>
            <input
                name="area"
                value="{{ old('area') }}"
                placeholder="Operational area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Route -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Route
            </label>
            <input
                name="route"
                value="{{ old('route') }}"
                placeholder="Delivery route"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Beat -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Beat
            </label>
            <input
                name="beat"
                value="{{ old('beat') }}"
                placeholder="Sales beat"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Territory -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Territory
            </label>
            <input
                name="territory"
                value="{{ old('territory') }}"
                placeholder="Territory"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Zone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Zone
            </label>
            <input
                name="zone"
                value="{{ old('zone') }}"
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
            <input
                name="sales_person"
                value="{{ old('sales_person') }}"
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
            <input
                name="warehouse"
                value="{{ old('warehouse') }}"
                placeholder="Warehouse / Hub"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>
    </div>
</section>
