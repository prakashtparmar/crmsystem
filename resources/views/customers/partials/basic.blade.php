<section
    x-data="{ open: true, editing: {{ isset($customer) ? 'false' : 'true' }} }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
>
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Basic Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Core identity and contact details
            </p>
        </div>

        <div class="flex gap-2">
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
                class="text-xs px-3 py-1 rounded-md bg-blue-600 text-white
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm"
            >
                Save
            </button>

            <button
                type="button"
                x-cloak
                x-show="editing"
                @click="editing = false"
                class="text-xs px-3 py-1 rounded-md bg-indigo-600 text-white border
                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 shadow-sm"
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

        <!-- Name Row -->
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-5">

            <!-- First Name -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                    First Name <span class="text-red-500">*</span>
                </label>

                <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                    {{ old('first_name', $customer->first_name ?? '') ?: '-' }}
                </p>

                <input
                    x-show="editing"
                    name="first_name"
                    value="{{ old('first_name', $customer->first_name ?? '') }}"
                    required
                    placeholder="First name"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                />
            </div>

            <!-- Middle Name -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                    Middle Name <span class="text-red-500">*</span>
                </label>

                <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                    {{ old('middle_name', $customer->middle_name ?? '') ?: '-' }}
                </p>

                <input
                    x-show="editing"
                    name="middle_name"
                    value="{{ old('middle_name', $customer->middle_name ?? '') }}"
                    required
                    placeholder="Middle name"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                />
            </div>

            <!-- Last Name -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                    Last Name <span class="text-red-500">*</span>
                </label>

                <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                    {{ old('last_name', $customer->last_name ?? '') ?: '-' }}
                </p>

                <input
                    x-show="editing"
                    name="last_name"
                    value="{{ old('last_name', $customer->last_name ?? '') }}"
                    required
                    placeholder="Last name"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                />
            </div>
        </div>

        <!-- Display Name -->
        <div class="md:col-span-2 space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Display Name
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('display_name', $customer->display_name ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="display_name"
                value="{{ old('display_name', $customer->display_name ?? '') }}"
                readonly
                class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm
                       px-3 py-2 cursor-not-allowed text-gray-600 dark:text-gray-300"
            />
        </div>

        <!-- Mobile -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Mobile <span class="text-red-500">*</span>
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('mobile', $customer->mobile ?? request('mobile')) ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="mobile"
                value="{{ old('mobile', $customer->mobile ?? request('mobile')) }}"
                required
                maxlength="10"
                inputmode="numeric"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                placeholder="Primary mobile number"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Phone Number 2 -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Phone Number 2
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('phone_number_2', $customer->phone_number_2 ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="phone_number_2"
                value="{{ old('phone_number_2', $customer->phone_number_2 ?? '') }}"
                maxlength="10"
                inputmode="numeric"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                placeholder="Alternate phone number"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Relative Phone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Relative Phone
            </label>

            <p x-show="!editing" class="text-sm text-gray-800 dark:text-gray-200">
                {{ old('relative_phone', $customer->relative_phone ?? '') ?: '-' }}
            </p>

            <input
                x-show="editing"
                name="relative_phone"
                value="{{ old('relative_phone', $customer->relative_phone ?? '') }}"
                maxlength="10"
                inputmode="numeric"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                placeholder="Emergency / relative contact"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>
    </div>
</section>
