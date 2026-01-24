<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                Agriculture Information
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Farming and crop-related details
            </p>
        </div>

        <button
            type="button"
            onclick="document.getElementById('agricultureSection').classList.toggle('hidden'); this.textContent = this.textContent === 'Hide' ? 'Show' : 'Hide';"
            class="text-xs px-3 py-1 rounded-md border text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            Hide
        </button>
    </div>

    <!-- Fields Wrapper -->
    <div id="agricultureSection" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Land Area -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Land Area
            </label>
            <div class="flex gap-2">
                <input
                    type="text"
                    name="land_area"
                    value="{{ old('land_area', '0') }}"
                    placeholder="Area"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    oninput="this.value = this.value.replace(/\D/g, '')"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                />
                <select
                    name="land_unit"
                    class="w-28 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    <option value="acre" @selected(old('land_unit','acre')=='acre')>Acre</option>
                    <option value="hectare" @selected(old('land_unit')=='hectare')>Hectare</option>
                    <option value="bigha" @selected(old('land_unit')=='bigha')>Bigha</option>
                </select>
            </div>
        </div>

        <!-- Irrigation Type -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Irrigation Type
            </label>
            <select
                name="irrigation_type"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="canal" @selected(old('irrigation_type')=='canal')>Canal</option>
                <option value="borewell" @selected(old('irrigation_type')=='borewell')>Borewell</option>
                <option value="rainfed" @selected(old('irrigation_type')=='rainfed')>Rainfed</option>
                <option value="drip" @selected(old('irrigation_type')=='drip')>Drip</option>
            </select>
        </div>

        @php
            $crops = ['Wheat','Rice','Cotton','Maize','Bajra','Jowar','Sugarcane','Groundnut','Soybean','Onion','Potato','Tomato'];
            $oldPrimary = old('primary_crops', []);
            $oldSecondary = old('secondary_crops', []);
        @endphp

        <!-- Primary Crops -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-2">Primary Crops</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                @foreach($crops as $crop)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox"
                               name="primary_crops[]"
                               value="{{ $crop }}"
                               @checked(in_array($crop, $oldPrimary))
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>{{ $crop }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Secondary Crops -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-2">Secondary Crops</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                @foreach($crops as $crop)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox"
                               name="secondary_crops[]"
                               value="{{ $crop }}"
                               @checked(in_array($crop, $oldSecondary))
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>{{ $crop }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Soil Type -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Soil Type
            </label>
            <input
                name="soil_type"
                value="{{ old('soil_type') }}"
                placeholder="Black, Red, Sandy..."
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
            />
        </div>

        <!-- Farming Method -->
        <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">
                Farming Method
            </label>
            <select
                name="farming_method"
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                <option value="">Select</option>
                <option value="organic" @selected(old('farming_method')=='organic')>Organic</option>
                <option value="conventional" @selected(old('farming_method','conventional')=='conventional')>Conventional</option>
                <option value="mixed" @selected(old('farming_method')=='mixed')>Mixed</option>
            </select>
        </div>

    </div>
</section>
