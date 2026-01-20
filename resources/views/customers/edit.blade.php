<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <span class="mx-2">›</span>
        <a href="{{ route('customers.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Customers') }}</a>
        <span class="mx-2">›</span>
        <span>{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Edit Customer') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Update customer information') }}</p>
    </div>

    <div class="max-w-6xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-medium text-gray-800 dark:text-gray-100">{{ __('Customer Details') }}</h2>
            </div>

            <div class="p-4">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic + Classification -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <section class="space-y-3">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-forms.input label="First Name" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required />
                                <x-forms.input label="Last Name" name="last_name" value="{{ old('last_name', $customer->last_name) }}" />
                                <x-forms.input label="Display Name" name="display_name" value="{{ old('display_name', $customer->display_name) }}" />
                                <x-forms.input label="Mobile" name="mobile" value="{{ old('mobile', $customer->mobile) }}" required />
                                <x-forms.input label="Email" name="email" type="email" value="{{ old('email', $customer->email) }}" />
                            </div>
                        </section>

                        <section class="space-y-3">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Classification</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Type</label>
                                    <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        @foreach (['farmer','buyer','vendor','dealer'] as $t)
                                            <option value="{{ $t }}" @selected(old('type', $customer->type) == $t)>{{ ucfirst($t) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Category</label>
                                    <select name="category" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="individual" @selected(old('category', $customer->category) === 'individual')>Individual</option>
                                        <option value="business" @selected(old('category', $customer->category) === 'business')>Business</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Address -->
                    <section class="space-y-1.5">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Address</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                            <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                                <x-forms.input label="Address Line 1" name="address_line1" value="{{ old('address_line1', $customer->address_line1) }}" />
                            </div>

                            <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                                <x-forms.input label="Address Line 2" name="address_line2" value="{{ old('address_line2', $customer->address_line2) }}" />
                            </div>

                            <x-forms.input label="Village" name="village" value="{{ old('village', $customer->village) }}" />
                            <x-forms.input label="Taluka" name="taluka" value="{{ old('taluka', $customer->taluka) }}" />
                            <x-forms.input label="District" name="district" value="{{ old('district', $customer->district) }}" />
                            <x-forms.input label="State" name="state" value="{{ old('state', $customer->state) }}" />
                            <x-forms.input label="Country" name="country" value="{{ old('country', $customer->country) }}" />
                            <x-forms.input label="Pincode" name="pincode" value="{{ old('pincode', $customer->pincode) }}" />
                        </div>
                    </section>

                    <!-- Agriculture + Finance -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <section class="space-y-2">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Agriculture</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <x-forms.input label="Land Area" name="land_area" value="{{ old('land_area', $customer->land_area) }}" />

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Land Unit</label>
                                    <select name="land_unit" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        @foreach (['acre','hectare','bigha'] as $u)
                                            <option value="{{ $u }}" @selected(old('land_unit', $customer->land_unit) == $u)>{{ ucfirst($u) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-forms.input label="Primary Crops" name="primary_crops" value="{{ old('primary_crops', $customer->primary_crops) }}" />
                                <x-forms.input label="Secondary Crops" name="secondary_crops" value="{{ old('secondary_crops', $customer->secondary_crops) }}" />

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Irrigation Type</label>
                                    <select name="irrigation_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        @foreach (['rainfed','canal','drip','sprinkler','borewell'] as $i)
                                            <option value="{{ $i }}" @selected(old('irrigation_type', $customer->irrigation_type) == $i)>{{ ucfirst($i) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="space-y-2">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Financial</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-forms.input label="Credit Limit" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" />
                                <x-forms.input label="Outstanding Balance" name="outstanding_balance" value="{{ old('outstanding_balance', $customer->outstanding_balance) }}" />
                                <x-forms.input label="Credit Valid Till" name="credit_valid_till" type="date" value="{{ old('credit_valid_till', $customer->credit_valid_till) }}" />
                            </div>
                        </section>
                    </div>

                    <!-- Status -->
                    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <textarea name="internal_notes" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">{{ old('internal_notes', $customer->internal_notes) }}</textarea>

                        <div class="space-y-2 pt-1">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" class="rounded" @checked(old('is_active', $customer->is_active))>
                                <span class="text-sm">Active</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_blacklisted" value="1" class="rounded" @checked(old('is_blacklisted', $customer->is_blacklisted))>
                                <span class="text-sm">Blacklisted</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="kyc_completed" value="1" class="rounded" @checked(old('kyc_completed', $customer->kyc_completed))>
                                <span class="text-sm">KYC Completed</span>
                            </label>
                        </div>
                    </section>

                    <!-- Optional Business Details -->
                    <section class="space-y-2">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Optional Business Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <x-forms.input label="Shop Name" name="company_name" value="{{ old('company_name', $customer->company_name) }}" />
                            <x-forms.input label="GST Number" name="gst_number" value="{{ old('gst_number', $customer->gst_number) }}" />
                            <x-forms.input label="PAN Number" name="pan_number" value="{{ old('pan_number', $customer->pan_number) }}" />
                        </div>
                    </section>

                    <!-- Optional Location -->
                    <section class="space-y-2">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Optional Location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <x-forms.input label="Latitude" name="latitude" value="{{ old('latitude', $customer->latitude) }}" />
                            <x-forms.input label="Longitude" name="longitude" value="{{ old('longitude', $customer->longitude) }}" />
                        </div>
                    </section>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('customers.index') }}" class="px-3 py-1.5 rounded-md border text-sm">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Update Customer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
