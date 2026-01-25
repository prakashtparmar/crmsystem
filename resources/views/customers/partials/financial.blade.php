<section
    x-data="{ open: true, editing: {{ isset($customer) ? 'false' : 'true' }} }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Financial Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Credit, billing, and payment preferences
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

        <!-- Credit Limit -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Credit Limit</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('credit_limit', $customer->credit_limit ?? 0) }}
            </p>

            <input
                x-show="editing"
                type="number"
                name="credit_limit"
                min="0"
                step="1"
                value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Opening Outstanding -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Opening Outstanding</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('outstanding_balance', $customer->outstanding_balance ?? 0) }}
            </p>

            <input
                x-show="editing"
                type="number"
                name="outstanding_balance"
                min="0"
                step="1"
                value="{{ old('outstanding_balance', $customer->outstanding_balance ?? 0) }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Credit Valid Till -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Credit Valid Till</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('credit_valid_till', $customer->credit_valid_till ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                type="date"
                name="credit_valid_till"
                value="{{ old('credit_valid_till', $customer->credit_valid_till ?? '') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Payment Terms -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Payment Terms (Days)</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('payment_terms', $customer->payment_terms ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                type="number"
                name="payment_terms"
                min="0"
                step="1"
                value="{{ old('payment_terms', $customer->payment_terms ?? '') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Preferred Payment Mode -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Preferred Payment Mode</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('preferred_payment_mode', $customer->preferred_payment_mode ?? '') ?: '-' }}
            </p>

            <select
                x-show="editing"
                name="preferred_payment_mode"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="cash" @selected(old('preferred_payment_mode', $customer->preferred_payment_mode ?? '')=='cash')>Cash</option>
                <option value="upi" @selected(old('preferred_payment_mode', $customer->preferred_payment_mode ?? '')=='upi')>UPI</option>
                <option value="bank" @selected(old('preferred_payment_mode', $customer->preferred_payment_mode ?? '')=='bank')>Bank Transfer</option>
                <option value="cheque" @selected(old('preferred_payment_mode', $customer->preferred_payment_mode ?? '')=='cheque')>Cheque</option>
                <option value="credit" @selected(old('preferred_payment_mode', $customer->preferred_payment_mode ?? '')=='credit')>Credit</option>
            </select>
        </div>

        <!-- Bank Name -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Bank Name</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('bank_name', $customer->bank_name ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="bank_name"
                value="{{ old('bank_name', $customer->bank_name ?? '') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Account Number -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Account Number</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('account_number', $customer->account_number ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="account_number"
                value="{{ old('account_number', $customer->account_number ?? '') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- IFSC -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">IFSC Code</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('ifsc_code', $customer->ifsc_code ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="ifsc_code"
                value="{{ old('ifsc_code', $customer->ifsc_code ?? '') }}"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Billing Cycle -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Billing Cycle</label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('billing_cycle', $customer->billing_cycle ?? '') ?: '-' }}
            </p>

            <select
                x-show="editing"
                name="billing_cycle"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="weekly" @selected(old('billing_cycle', $customer->billing_cycle ?? '')=='weekly')>Weekly</option>
                <option value="fortnightly" @selected(old('billing_cycle', $customer->billing_cycle ?? '')=='fortnightly')>Fortnightly</option>
                <option value="monthly" @selected(old('billing_cycle', $customer->billing_cycle ?? '')=='monthly')>Monthly</option>
            </select>
        </div>
    </div>
</section>
