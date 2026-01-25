<section
    x-data="{ open: true, editing: {{ isset($customer) ? 'false' : 'true' }} }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                References & Contacts
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Referral source and secondary contact details
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

        <!-- Referred By -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Referred By
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('referred_by', $customer->referred_by ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="referred_by"
                value="{{ old('referred_by', $customer->referred_by ?? '') }}"
                placeholder="Person / Dealer / Campaign"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Reference Type -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Reference Type
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('reference_type', $customer->reference_type ?? '') ?: '-' }}
            </p>

            <select
                x-show="editing"
                name="reference_type"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="dealer" @selected(old('reference_type', $customer->reference_type ?? '')=='dealer')>Dealer</option>
                <option value="customer" @selected(old('reference_type', $customer->reference_type ?? '')=='customer')>Customer</option>
                <option value="employee" @selected(old('reference_type', $customer->reference_type ?? '')=='employee')>Employee</option>
                <option value="campaign" @selected(old('reference_type', $customer->reference_type ?? '')=='campaign')>Campaign</option>
            </select>
        </div>

        <!-- Reference Name -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Reference Name
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('reference_name', $customer->reference_name ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="reference_name"
                value="{{ old('reference_name', $customer->reference_name ?? '') }}"
                placeholder="Reference person name"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Reference Phone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Reference Phone
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('reference_phone', $customer->reference_phone ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="reference_phone"
                value="{{ old('reference_phone', $customer->reference_phone ?? '') }}"
                placeholder="Contact number"
                inputmode="numeric"
                maxlength="10"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Secondary Contact Name -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Secondary Contact Name
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('secondary_contact_name', $customer->secondary_contact_name ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="secondary_contact_name"
                value="{{ old('secondary_contact_name', $customer->secondary_contact_name ?? '') }}"
                placeholder="Alternate contact person"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Secondary Contact Phone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Secondary Contact Phone
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('secondary_contact_phone', $customer->secondary_contact_phone ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="secondary_contact_phone"
                value="{{ old('secondary_contact_phone', $customer->secondary_contact_phone ?? '') }}"
                placeholder="Alternate phone number"
                inputmode="numeric"
                maxlength="10"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Notes -->
        <div class="space-y-1 md:col-span-3">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Reference Notes
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line">
                {{ old('reference_notes', $customer->reference_notes ?? '') ?: '-' }}
            </p>

            <textarea
                x-show="editing"
                name="reference_notes"
                rows="3"
                placeholder="Any additional context about the reference..."
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            >{{ old('reference_notes', $customer->reference_notes ?? '') }}</textarea>
        </div>
    </div>
</section>
