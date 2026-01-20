<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <span class="mx-2">›</span>
        <a href="{{ route('customers.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Customers') }}</a>
        <span class="mx-2">›</span>
        <span>{{ __('Create') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Create Customer') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Add a new customer to your system') }}</p>
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

                <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Basic + Classification -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <section class="space-y-3">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Basic
                                Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-forms.input label="First Name" name="first_name" value="{{ old('first_name') }}"
                                    required />
                                <x-forms.input label="Last Name" name="last_name" value="{{ old('last_name') }}" />
                                <x-forms.input label="Display Name" name="display_name"
                                    value="{{ old('display_name') }}" />
                                <x-forms.input label="Mobile" name="mobile"
                                    value="{{ old('mobile', request('mobile')) }}" required />
                                <x-forms.input label="Email" name="email" type="email"
                                    value="{{ old('email') }}" />
                            </div>
                        </section>

                        <section class="space-y-3">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Classification
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Type</label>
                                    <select name="type"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        @foreach (['farmer', 'buyer', 'vendor', 'dealer'] as $t)
                                            <option value="{{ $t }}" @selected(old('type', 'farmer') == $t)>
                                                {{ ucfirst($t) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Category</label>
                                    <select name="category"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="individual" @selected(old('category', 'individual') === 'individual')>Individual</option>
                                        <option value="business" @selected(old('category') === 'business')>Business</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                    </div>



                    <!-- Address -->
                    <section class="space-y-1.5">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Address</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                            <!-- Row 1: Long fields -->
                            <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                                <x-forms.input label="Address Line 1" name="address_line1" />
                            </div>

                            <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                                <x-forms.input label="Address Line 2" name="address_line2" />
                            </div>

                            <!-- Row 2 -->
                            <div class="col-span-1">
                                <x-forms.input label="Village" name="village" />
                            </div>

                            <div class="col-span-1">
                                <x-forms.input label="Taluka" name="taluka" />
                            </div>

                            <div class="col-span-1">
                                <x-forms.input label="District" name="district" />
                            </div>

                            <div class="col-span-1">
                                <x-forms.input label="State" name="state" />
                            </div>

                            <!-- Row 3 -->
                            <div class="col-span-1">
                                <x-forms.input label="Country" name="country" value="{{ old('country', 'India') }}" />
                            </div>

                            <div class="col-span-1">
                                <x-forms.input label="Pincode" name="pincode" />
                            </div>
                        </div>
                    </section>


                    <!-- Agriculture + Finance -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Agriculture -->
    <section class="space-y-2">
        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Agriculture</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="w-full">
                <x-forms.input label="Land Area" name="land_area" />
            </div>

            <div class="flex flex-col w-full">
                <label class="block text-sm font-medium mb-1">Land Unit</label>
                <select name="land_unit"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    @foreach (['acre','hectare','bigha'] as $u)
                        <option value="{{ $u }}" @selected(old('land_unit','acre') == $u)>
                            {{ ucfirst($u) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full">
                <x-forms.input label="Primary Crops" name="primary_crops" />
            </div>

            <div class="w-full">
                <x-forms.input label="Secondary Crops" name="secondary_crops" />
            </div>

            <div class="flex flex-col w-full">
                <label class="block text-sm font-medium mb-1">Irrigation Type</label>
                <select name="irrigation_type"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    @foreach (['rainfed','canal','drip','sprinkler','borewell'] as $i)
                        <option value="{{ $i }}" @selected(old('irrigation_type') == $i)>
                            {{ ucfirst($i) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <!-- Financial -->
    <section class="space-y-2">
        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Financial</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="w-full">
                <x-forms.input label="Credit Limit" name="credit_limit" value="{{ old('credit_limit',0) }}" />
            </div>

            <div class="w-full">
                <x-forms.input label="Outstanding Balance" name="outstanding_balance" value="{{ old('outstanding_balance',0) }}" />
            </div>

            <div class="w-full">
                <x-forms.input label="Credit Valid Till" name="credit_valid_till" type="date" />
            </div>
        </div>
    </section>
</div>



                    <!-- Status -->
                    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <textarea name="internal_notes" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Internal notes...">{{ old('internal_notes') }}</textarea>

                        <div class="space-y-2 pt-1">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" class="rounded"
                                    @checked(old('is_active', true))>
                                <span class="text-sm">Active</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_blacklisted" value="1" class="rounded"
                                    @checked(old('is_blacklisted'))>
                                <span class="text-sm">Blacklisted</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="kyc_completed" value="1" class="rounded"
                                    @checked(old('kyc_completed'))>
                                <span class="text-sm">KYC Completed</span>
                            </label>
                        </div>
                    </section>

                    <!-- Optional Business Details -->
<section class="space-y-2">
    <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
        Optional Business Details
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <div class="w-full">
            <x-forms.input label="Shop Name" name="company_name" value="{{ old('company_name') }}" />
        </div>

        <div class="w-full">
            <x-forms.input label="GST Number" name="gst_number" value="{{ old('gst_number') }}" />
        </div>

        <div class="w-full">
            <x-forms.input label="PAN Number" name="pan_number" value="{{ old('pan_number') }}" />
        </div>
    </div>
</section>


<!-- Optional Location -->
<section class="space-y-2">
    <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
        Optional Location
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="w-full">
            <x-forms.input label="Latitude" name="latitude" />
        </div>

        <div class="w-full">
            <x-forms.input label="Longitude" name="longitude" />
        </div>
    </div>
</section>



                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('customers.index') }}"
                            class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Save Customer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
