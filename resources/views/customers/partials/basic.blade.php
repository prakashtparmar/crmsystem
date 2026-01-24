<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
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

        <button
            type="button"
            data-toggle="basicSection"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
        >
            Hide
        </button>
    </div>

    <!-- Fields Wrapper -->
    <div id="basicSection" class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Name Row (First / Middle / Last in one line) -->
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- First Name -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input
                    name="first_name"
                    value="{{ old('first_name') }}"
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
                <input
                    name="middle_name"
                    value="{{ old('middle_name') }}"
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
                <input
                    name="last_name"
                    value="{{ old('last_name') }}"
                    required
                    placeholder="Last name"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                />
            </div>
        </div>

        <!-- Display Name (Read Only) -->
        <div class="md:col-span-2 space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Display Name
            </label>
            <input
                name="display_name"
                value="{{ old('display_name') }}"
                readonly
                class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm
                       px-3 py-2 cursor-not-allowed text-gray-600 dark:text-gray-300"
            />
            <p class="text-[11px] text-gray-400">
                Auto-generated from First, Middle &amp; Last Name.
            </p>
        </div>

        <!-- Mobile -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Mobile <span class="text-red-500">*</span>
            </label>
            <input
                name="mobile"
                value="{{ old('mobile', request('mobile')) }}"
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
            <input
                name="phone_number_2"
                value="{{ old('phone_number_2') }}"
                placeholder="Alternate phone number"
                maxlength="10"
                inputmode="numeric"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Relative Phone -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Relative Phone
            </label>
            <input
                name="relative_phone"
                value="{{ old('relative_phone') }}"
                placeholder="Emergency / relative contact"
                maxlength="10"
                inputmode="numeric"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').slice(0, 10)"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>
    </div>
</section>
