<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Financial Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Credit, billing, and payment preferences
            </p>
        </div>

        <button type="button"
            data-toggle="financialSection"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Show
        </button>
    </div>

    <!-- Fields Wrapper (Hidden by default) -->
    <div id="financialSection" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Credit Limit -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Credit Limit
            </label>
            <input
                type="number"
                name="credit_limit"
                min="0"
                step="1"
                value="{{ old('credit_limit', 0) }}"
                placeholder="0"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Outstanding Balance -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Opening Outstanding
            </label>
            <input
                type="number"
                name="outstanding_balance"
                min="0"
                step="1"
                value="{{ old('outstanding_balance', 0) }}"
                placeholder="0"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Credit Valid Till -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Credit Valid Till
            </label>
            <input
                type="date"
                name="credit_valid_till"
                value="{{ old('credit_valid_till') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Payment Terms -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Payment Terms (Days)
            </label>
            <input
                type="number"
                name="payment_terms"
                min="0"
                step="1"
                value="{{ old('payment_terms') }}"
                placeholder="e.g. 30"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Preferred Payment Mode -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Preferred Payment Mode
            </label>
            <select
                name="preferred_payment_mode"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="cash" @selected(old('preferred_payment_mode')=='cash')>Cash</option>
                <option value="upi" @selected(old('preferred_payment_mode')=='upi')>UPI</option>
                <option value="bank" @selected(old('preferred_payment_mode')=='bank')>Bank Transfer</option>
                <option value="cheque" @selected(old('preferred_payment_mode')=='cheque')>Cheque</option>
                <option value="credit" @selected(old('preferred_payment_mode')=='credit')>Credit</option>
            </select>
        </div>

        <!-- Bank Name -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Bank Name
            </label>
            <input
                name="bank_name"
                value="{{ old('bank_name') }}"
                placeholder="Bank name"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Account Number -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Account Number
            </label>
            <input
                name="account_number"
                value="{{ old('account_number') }}"
                placeholder="Account number"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- IFSC -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                IFSC Code
            </label>
            <input
                name="ifsc_code"
                value="{{ old('ifsc_code') }}"
                placeholder="IFSC"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Billing Cycle -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Billing Cycle
            </label>
            <select
                name="billing_cycle"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="weekly" @selected(old('billing_cycle')=='weekly')>Weekly</option>
                <option value="fortnightly" @selected(old('billing_cycle')=='fortnightly')>Fortnightly</option>
                <option value="monthly" @selected(old('billing_cycle')=='monthly')>Monthly</option>
            </select>
        </div>
    </div>
</section>
