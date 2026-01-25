<section
    x-data="{ open: true, editing: {{ isset($customer) ? 'false' : 'true' }} }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <!-- Header -->
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
                x-cloak
                x-show="editing"
                onclick="clearAddressFields()"
                class="text-xs px-3 py-1 rounded-md border border-red-300 text-red-600 hover:bg-red-50
                       dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 transition"
            >
                Clear
            </button>

            <button
                type="button"
                x-cloak
                x-show="!editing"
                @click="editing = true"
                class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                Edit
            </button>

            <button
                type="submit"
                x-cloak
                x-show="editing"
                class="text-xs px-3 py-1 rounded-md bg-blue-600 text-white"
            >
                Save
            </button>

            <button
                type="button"
                x-cloak
                x-show="editing"
                @click="editing = false"
                class="text-xs px-3 py-1 rounded-md bg-indigo-600 text-white border"
            >
                Cancel
            </button>

            <button
                type="button"
                @click="open = !open"
                class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                <span x-text="open ? 'Hide' : 'Show'"></span>
            </button>
        </div>
    </div>

    <!-- Fields Wrapper -->
    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Address Line 1 -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Address Line 1</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('address_line1', $customer->address_line1 ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="address_line1" name="address_line1"
                value="{{ old('address_line1', $customer->address_line1 ?? '') }}"
                placeholder="House no, street, area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Address Line 2 -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Address Line 2</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('address_line2', $customer->address_line2 ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="address_line2" name="address_line2"
                value="{{ old('address_line2', $customer->address_line2 ?? '') }}"
                placeholder="Landmark, building, floor (optional)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Pincode -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Pincode</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('pincode', $customer->pincode ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="pincode" name="pincode"
                value="{{ old('pincode', $customer->pincode ?? '') }}"
                inputmode="numeric" maxlength="6"
                oninput="this.value = this.value.replace(/\D/g,'').slice(0,6);"
                placeholder="Postal code"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Post Office -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Post Office (B.O)</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('post_office', $customer->post_office ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="post_office" name="post_office"
                value="{{ old('post_office', $customer->post_office ?? '') }}"
                placeholder="Enter Postal BO"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Village -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Village</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('village', $customer->village ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="village" name="village"
                value="{{ old('village', $customer->village ?? '') }}"
                placeholder="Village / Area"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Taluka -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Taluka</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('taluka', $customer->taluka ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="taluka" name="taluka"
                value="{{ old('taluka', $customer->taluka ?? '') }}"
                placeholder="Taluka"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- District -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">District</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('district', $customer->district ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="district" name="district"
                value="{{ old('district', $customer->district ?? '') }}"
                placeholder="District"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- State -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">State</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('state', $customer->state ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="state" name="state"
                value="{{ old('state', $customer->state ?? '') }}"
                placeholder="State"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>

        <!-- Country -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Country</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('country', $customer->country ?? 'India') ?: '-' }}
            </p>

            <input
                x-show="editing"
                id="country" name="country"
                value="{{ old('country', $customer->country ?? 'India') }}"
                placeholder="Country"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2"
            />
        </div>
    </div>
</section>
