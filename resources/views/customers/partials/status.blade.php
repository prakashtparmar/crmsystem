<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Status & Notes
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Account state and internal remarks
            </p>
        </div>

        <button
            type="button"
            onclick="document.getElementById('statusNotesSection').classList.toggle('hidden'); this.textContent = this.textContent === 'Show' ? 'Hide' : 'Show';"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Show
        </button>
    </div>

    <!-- Fields Wrapper (Hidden by default) -->
    <div id="statusNotesSection" class="hidden grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Internal Notes -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Internal Notes
            </label>
            <textarea
                name="internal_notes"
                rows="3"
                placeholder="Internal notes..."
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            >{{ old('internal_notes') }}</textarea>
        </div>

        <!-- Flags -->
        <div class="space-y-3 md:col-span-2">
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <input type="checkbox" name="is_active" value="1" class="rounded"
                       @checked(old('is_active', true))>
                <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>

            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <input type="checkbox" name="is_blacklisted" value="1" class="rounded"
                       @checked(old('is_blacklisted'))>
                <span class="text-sm text-gray-700 dark:text-gray-300">Blacklisted</span>
            </label>

            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <input type="checkbox" name="kyc_completed" value="1" class="rounded"
                       @checked(old('kyc_completed'))>
                <span class="text-sm text-gray-700 dark:text-gray-300">KYC Completed</span>
            </label>
        </div>
    </div>
</section>
