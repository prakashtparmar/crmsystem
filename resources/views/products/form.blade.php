@csrf

<x-forms.input
    label="Product Name"
    name="name"
    type="text"
    :value="old('name', $product->name ?? '')"
    required
/>

<x-forms.input
    label="Slug"
    name="slug"
    type="text"
    :value="old('slug', $product->slug ?? '')"
/>

<div>
    <label class="block text-sm font-medium mb-1">Category</label>
    <select name="category_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
        required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id ?? '') == $c->id)>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Sub Category</label>
    <select name="subcategory_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
        <option value="">-- Select Sub Category --</option>
        @foreach($subcategories as $s)
            <option value="{{ $s->id }}" @selected(old('subcategory_id', $product->subcategory_id ?? '') == $s->id)>
                {{ $s->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Brand</label>
    <select name="brand_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
        <option value="">-- Select Brand --</option>
        @foreach($brands as $b)
            <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id ?? '') == $b->id)>
                {{ $b->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Unit</label>
    <select name="unit_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
        required>
        <option value="">-- Select Unit --</option>
        @foreach($units as $u)
            <option value="{{ $u->id }}" @selected(old('unit_id', $product->unit_id ?? '') == $u->id)>
                {{ $u->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Crop</label>
    <select name="crop_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
        <option value="">-- Select Crop --</option>
        @foreach($crops as $c)
            <option value="{{ $c->id }}" @selected(old('crop_id', $product->crop_id ?? '') == $c->id)>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Season</label>
    <select name="season_id"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
        <option value="">-- Select Season --</option>
        @foreach($seasons as $s)
            <option value="{{ $s->id }}" @selected(old('season_id', $product->season_id ?? '') == $s->id)>
                {{ $s->name }}
            </option>
        @endforeach
    </select>
</div>

<x-forms.input
    label="HSN Code"
    name="hsn_code"
    type="text"
    :value="old('hsn_code', $product->hsn_code ?? '')"
/>

<x-forms.input
    label="GST %"
    name="gst_percent"
    type="number"
    step="0.01"
    :value="old('gst_percent', $product->gst_percent ?? 0)"
    required
/>

<x-forms.input
    label="Minimum Order Quantity"
    name="min_order_qty"
    type="number"
    :value="old('min_order_qty', $product->min_order_qty ?? 1)"
    required
/>

<x-forms.input
    label="Maximum Order Quantity"
    name="max_order_qty"
    type="number"
    :value="old('max_order_qty', $product->max_order_qty ?? '')"
/>

<x-forms.input
    label="Shelf Life (Days)"
    name="shelf_life_days"
    type="number"
    :value="old('shelf_life_days', $product->shelf_life_days ?? '')"
/>

<div>
    <label class="block text-sm font-medium mb-1">Short Description</label>
    <textarea name="short_description"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
        rows="2">{{ old('short_description', $product->short_description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea name="description"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
        rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<input type="hidden" name="is_organic" value="0">
<div class="flex items-center gap-2">
    <input type="checkbox"
           name="is_organic"
           value="1"
           class="rounded border-gray-300 dark:border-gray-700"
           @checked(old('is_organic', $product->is_organic ?? false))>
    <label class="text-sm">Organic Product</label>
</div>

<input type="hidden" name="is_active" value="0">
<div class="flex items-center gap-2">
    <input type="checkbox"
           name="is_active"
           value="1"
           class="rounded border-gray-300 dark:border-gray-700"
           @checked(old('is_active', $product->is_active ?? true))>
    <label class="text-sm">Active</label>
</div>

<div class="flex items-center gap-3 pt-4">
    <x-button type="primary">
        {{ __('Save Product') }}
    </x-button>

    <a href="{{ route('products.index') }}"
       class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
        {{ __('Cancel') }}
    </a>
</div>
