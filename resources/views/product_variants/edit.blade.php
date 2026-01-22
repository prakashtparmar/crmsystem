<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <div class="mb-5 flex items-center text-xs text-gray-500 dark:text-gray-400">
            <a href="{{ route('dashboard') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
            <span class="mx-2">›</span>
            <a href="{{ route('product-variants.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Product Variants') }}</a>
            <span class="mx-2">›</span>
            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Edit') }}</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Edit Variant') }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Update product variant details') }}
            </p>
        </div>

        <div class="max-w-5xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                @if ($errors->any())
                    <div class="m-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('product-variants.update', $variant) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Info -->
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Basic Information
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-3xl">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Product</label>
                                <select name="product_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            @selected(old('product_id', $variant->product_id) == $product->id)>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <x-forms.input label="SKU" name="sku"
                                value="{{ old('sku', $variant->sku) }}" required />

                            <x-forms.input label="Weight" name="weight" type="number" step="0.01"
                                value="{{ old('weight', $variant->weight) }}" />

                            <x-forms.input label="Price" name="price" type="number" step="0.01"
                                value="{{ old('price', $variant->price) }}" required />

                            <x-forms.input label="Sale Price" name="sale_price" type="number" step="0.01"
                                value="{{ old('sale_price', $variant->sale_price) }}" />
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Settings
                        </h2>

                        <div class="flex flex-col gap-3 max-w-md">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_default" value="1"
                                    @checked(old('is_default', $variant->is_default))>
                                <span>Set as Default Variant</span>
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_active" value="1"
                                    @checked(old('is_active', $variant->is_active))>
                                <span>Active</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 rounded-b-xl">
                        <a href="{{ route('product-variants.index') }}"
                            class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Update Variant') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
