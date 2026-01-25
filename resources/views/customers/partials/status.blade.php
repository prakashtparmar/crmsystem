<section
    x-data="{ open: true, editing: {{ isset($customer) ? 'false' : 'true' }} }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Follow-ups & Notes
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Account state and internal remarks
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
    <div x-show="open" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Internal Notes -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Internal Notes
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line">
                {{ old('internal_notes', $customer->internal_notes ?? '') ?: '-' }}
            </p>

            <textarea
                x-show="editing"
                name="internal_notes"
                rows="3"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            >{{ old('internal_notes', $customer->internal_notes ?? '') }}</textarea>
        </div>

        <!-- Flags -->
        <div class="space-y-3 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Status Flags
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('is_active', $customer->is_active ?? true) ? 'Active' : 'Inactive' }},
                {{ old('is_blacklisted', $customer->is_blacklisted ?? false) ? 'Blacklisted' : 'Not Blacklisted' }},
                {{ old('kyc_completed', $customer->kyc_completed ?? false) ? 'KYC Completed' : 'KYC Pending' }}
            </p>

            <div x-show="editing" class="space-y-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="rounded"
                           @checked(old('is_active', $customer->is_active ?? true))>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" name="is_blacklisted" value="1" class="rounded"
                           @checked(old('is_blacklisted', $customer->is_blacklisted ?? false))>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Blacklisted</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" name="kyc_completed" value="1" class="rounded"
                           @checked(old('kyc_completed', $customer->kyc_completed ?? false))>
                    <span class="text-sm text-gray-700 dark:text-gray-300">KYC Completed</span>
                </label>
            </div>
        </div>
    </div>
</section>
