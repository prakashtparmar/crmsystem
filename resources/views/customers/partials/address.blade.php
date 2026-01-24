<section
    x-data="{ open: true }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Address Details
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Residential or business location information
            </p>
        </div>

        <div class="flex gap-2">
            <button
                type="button"
                onclick="clearAddressFields()"
                class="text-xs px-3 py-1 rounded-md border border-red-300 text-red-600 hover:bg-red-50
                       dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 transition"
            >
                Clear
            </button>

            <button
                type="button"
                @click="open = !open"
                class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100
                       dark:text-gray-300 dark:hover:bg-gray-700 transition flex items-center gap-1"
            >
                <span x-text="open ? 'Hide' : 'Show'"></span>
                <svg
                    class="w-3 h-3 transition-transform"
                    :class="{ 'rotate-180': !open }"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Fields Wrapper -->
    <div
        x-show="open"
        x-transition
        class="grid grid-cols-1 md:grid-cols-2 gap-5"
    >
        <!-- Address Line 1 -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Address Line 1
            </label>
            <input id="address_line1" name="address_line1" autocomplete="off"
                value="{{ old('address_line1') }}" placeholder="House no, street, area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- Address Line 2 -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Address Line 2
            </label>
            <input id="address_line2" name="address_line2" autocomplete="off"
                value="{{ old('address_line2') }}" placeholder="Landmark, building, floor (optional)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- Pincode -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Pincode</label>
            <input
                id="pincode"
                name="pincode"
                autocomplete="off"
                value="{{ old('pincode') }}"
                inputmode="numeric"
                maxlength="6"
                pattern="\d{6}"
                placeholder="Postal code"
                oninput="this.value = this.value.replace(/\D/g,'').slice(0,6);"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Post Office -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Post Office (B.O)
            </label>

            <div class="relative">
                <input id="post_office" name="post_office" autocomplete="off"
                    value="{{ old('post_office') }}" placeholder="Enter Postal BO"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 pr-9" />

                <span
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                >
                    ▾
                </span>
            </div>
        </div>

        <!-- Village -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Village</label>
            <input id="village" name="village" autocomplete="off" value="{{ old('village') }}"
                placeholder="Village / Area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- Taluka -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Taluka</label>
            <input id="taluka" name="taluka" autocomplete="off" value="{{ old('taluka') }}"
                placeholder="Taluka"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- District -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">District</label>
            <input id="district" name="district" autocomplete="off" value="{{ old('district') }}"
                placeholder="District"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- State -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">State</label>
            <input id="state" name="state" autocomplete="off" value="{{ old('state') }}"
                placeholder="State"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>

        <!-- Country -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Country</label>
            <input id="country" name="country" autocomplete="off" value="{{ old('country', 'India') }}"
                placeholder="Country"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2" />
        </div>
    </div>
</section>
