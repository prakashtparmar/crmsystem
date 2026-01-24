<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Business Details
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Company and commercial information
            </p>
        </div>

        <button type="button"
            data-toggle="businessSection"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Show
        </button>
    </div>

    <!-- Fields Wrapper (Hidden by default) -->
    <div id="businessSection" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Business Type -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Business Type
            </label>
            <select
                name="business_type"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select type</option>
                <option value="retailer" @selected(old('business_type')=='retailer')>Retailer</option>
                <option value="wholesaler" @selected(old('business_type')=='wholesaler')>Wholesaler</option>
                <option value="distributor" @selected(old('business_type')=='distributor')>Distributor</option>
                <option value="farmer" @selected(old('business_type')=='farmer')>Farmer</option>
            </select>
        </div>

        <!-- Company Name -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Company / Shop Name
            </label>
            <input
                name="company_name"
                value="{{ old('company_name') }}"
                placeholder="Registered business name"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- GST Number -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                GST Number
            </label>
            <input
                name="gst_number"
                value="{{ old('gst_number') }}"
                placeholder="GSTIN"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- PAN Number -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                PAN Number
            </label>
            <input
                name="pan_number"
                value="{{ old('pan_number') }}"
                placeholder="PAN"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Registration Number -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Registration No.
            </label>
            <input
                name="registration_number"
                value="{{ old('registration_number') }}"
                placeholder="Business registration"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Website -->
        <div class="space-y-1 md:col-span-2">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Website
            </label>
            <input
                name="website"
                value="{{ old('website') }}"
                placeholder="https://example.com"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Established Year -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Established Year
            </label>
            <input
                name="established_year"
                value="{{ old('established_year') }}"
                placeholder="e.g. 2015"
                inputmode="numeric"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Annual Turnover -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Annual Turnover
            </label>
            <input
                name="annual_turnover"
                value="{{ old('annual_turnover') }}"
                placeholder="Approx. yearly revenue"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>
    </div>
</section>
