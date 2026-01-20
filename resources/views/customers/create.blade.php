<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
            {{ __('Dashboard') }}
        </a>
        <svg class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('customers.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
            {{ __('Customers') }}
        </a>
        <svg class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span>{{ __('Create') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Create Customer') }}
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Add a new customer to your system') }}
        </p>
    </div>

    <div class="max-w-6xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-100">
                    {{ __('Customer Details') }}
                </h2>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customers.store') }}" method="POST" class="space-y-10">
                    @csrf

                    <!-- Basic Info -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <x-forms.input label="First Name" name="first_name" value="{{ old('first_name') }}" required />
                            <x-forms.input label="Last Name" name="last_name" value="{{ old('last_name') }}" />
                            <x-forms.input label="Display Name" name="display_name" value="{{ old('display_name') }}" />

                            <x-forms.input
                                label="Mobile"
                                name="mobile"
                                value="{{ old('mobile', request('mobile')) }}"
                                required
                            />

                            <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" />
                        </div>
                    </div>

                    <!-- Classification -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Classification
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-medium mb-1">Type</label>
                                <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    @foreach(['farmer','buyer','vendor','dealer'] as $t)
                                        <option value="{{ $t }}" @selected(old('type')==$t)>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Category</label>
                                <select name="category" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="individual" @selected(old('category') === 'individual')>Individual</option>
                                    <option value="business" @selected(old('category') === 'business')>Business</option>
                                </select>
                            </div>

                            <x-forms.input label="Company Name" name="company_name" value="{{ old('company_name') }}" />
                            <x-forms.input label="GST Number" name="gst_number" value="{{ old('gst_number') }}" />
                            <x-forms.input label="PAN Number" name="pan_number" value="{{ old('pan_number') }}" />
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Address
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <x-forms.input label="Address Line 1" name="address_line1" />
                            <x-forms.input label="Address Line 2" name="address_line2" />
                            <x-forms.input label="Village" name="village" />

                            <x-forms.input label="Taluka" name="taluka" />
                            <x-forms.input label="District" name="district" />
                            <x-forms.input label="State" name="state" />

                            <x-forms.input label="Country" name="country" value="{{ old('country','India') }}" />
                            <x-forms.input label="Pincode" name="pincode" />
                            <x-forms.input label="Latitude" name="latitude" />
                            <x-forms.input label="Longitude" name="longitude" />
                        </div>
                    </div>

                    <!-- Agriculture Profile -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Agriculture Profile
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                            <x-forms.input label="Land Area" name="land_area" />
                            <x-forms.input label="Land Unit" name="land_unit" value="acre" />
                            <x-forms.input label="Primary Crops (JSON/Text)" name="primary_crops" />
                            <x-forms.input label="Secondary Crops (JSON/Text)" name="secondary_crops" />
                            <x-forms.input label="Irrigation Type" name="irrigation_type" />
                        </div>
                    </div>

                    <!-- Financial -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Financial / Credit
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <x-forms.input label="Credit Limit" name="credit_limit" value="0" />
                            <x-forms.input label="Outstanding Balance" name="outstanding_balance" value="0" />
                            <x-forms.input label="Credit Valid Till" name="credit_valid_till" type="date" />
                        </div>
                    </div>

                    <!-- KYC -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            KYC & Compliance
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <x-forms.input label="Aadhaar Last 4" name="aadhaar_last4" />
                            <div class="flex items-center gap-2 mt-6">
                                <input type="checkbox" name="kyc_completed" value="1" class="rounded" @checked(old('kyc_completed'))>
                                <label class="text-sm">KYC Completed</label>
                            </div>
                        </div>
                    </div>

                    <!-- Notes & Status -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                            Status & Notes
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <textarea name="internal_notes" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Internal notes...">{{ old('internal_notes') }}</textarea>

                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" class="rounded" @checked(old('is_active', true))>
                                    <span class="text-sm">Active</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_blacklisted" value="1" class="rounded" @checked(old('is_blacklisted'))>
                                    <span class="text-sm">Blacklisted</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('customers.index') }}"
                           class="px-4 py-2 rounded-md border text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary">
                            {{ __('Save Customer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
