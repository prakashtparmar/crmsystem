<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Status & Compliance
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Account state, verification, and risk controls
            </p>
        </div>

        <button
            type="button"
            onclick="document.getElementById('statusComplianceSection').classList.toggle('hidden'); this.textContent = this.textContent === 'Show' ? 'Hide' : 'Show';"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Show
        </button>
    </div>

    <!-- Fields Wrapper (Hidden by default) -->
    <div id="statusComplianceSection" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Active Status -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Account Status
            </label>
            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_active" value="1"
                           @checked(old('is_active', 1) == 1)
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_active" value="0"
                           @checked(old('is_active') === '0')
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Inactive</span>
                </label>
            </div>
        </div>

        <!-- Blacklist -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Blacklist
            </label>
            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_blacklisted" value="0"
                           @checked(old('is_blacklisted', 0) == 0)
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">No</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_blacklisted" value="1"
                           @checked(old('is_blacklisted') == 1)
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span>
                </label>
            </div>
        </div>

        <!-- KYC Status -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                KYC Status
            </label>
            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="kyc_completed" value="1"
                           @checked(old('kyc_completed') == 1)
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Verified</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="kyc_completed" value="0"
                           @checked(old('kyc_completed', 0) == 0)
                           class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Pending</span>
                </label>
            </div>
        </div>

        <!-- Aadhaar Last 4 -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Aadhaar (Last 4 Digits)
            </label>
            <input
                name="aadhaar_last4"
                maxlength="4"
                value="{{ old('aadhaar_last4') }}"
                placeholder="XXXX"
                inputmode="numeric"
                oninput="this.value=this.value.replace(/\D/g,'').slice(0,4)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- KYC Verified At -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                KYC Verified Date
            </label>
            <input
                type="date"
                name="kyc_verified_at"
                value="{{ old('kyc_verified_at') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Internal Notes -->
        <div class="space-y-1 md:col-span-3">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Internal Notes
            </label>
            <textarea
                name="internal_notes"
                rows="4"
                placeholder="Internal remarks, risk notes, special handling instructions..."
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            >{{ old('internal_notes') }}</textarea>
        </div>
    </div>
</section>
