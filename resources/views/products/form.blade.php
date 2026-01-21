<div class="grid grid-cols-1 gap-5 max-w-xl">

    <x-forms.input
        label="Product Name"
        name="name"
        type="text"
        :value="old('name', $product->name ?? '')"
        required
    />
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Enter the public-facing name of the product.
    </p>

    <x-forms.input
        label="Slug"
        name="slug"
        type="text"
        :value="old('slug', $product->slug ?? '')"
    />
    <p class="text-xs text-gray-500 dark:text-gray-400">
        URL-friendly version of the name. Leave empty to auto-generate.
    </p>

    <x-forms.input
        label="Product SKU (Enterprise)"
        name="sku"
        type="text"
        :value="old('sku', $product->sku ?? '')"
    />
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Internal or enterprise reference code for this product.
    </p>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Category</label>
        <select name="category_id" class="form-select relative z-20" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}"
                    @selected(old('category_id', $product->category_id ?? '') == $c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Primary classification for this product.
        </p>
    </div>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Sub Category</label>
        <select name="subcategory_id" class="form-select relative z-20">
            <option value="">-- Select Sub Category --</option>
            @foreach($subcategories as $s)
                <option value="{{ $s->id }}"
                    @selected(old('subcategory_id', $product->subcategory_id ?? '') == $s->id)>
                    {{ $s->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Brand</label>
        <select name="brand_id" class="form-select relative z-20">
            <option value="">-- Select Brand --</option>
            @foreach($brands as $b)
                <option value="{{ $b->id }}"
                    @selected(old('brand_id', $product->brand_id ?? '') == $b->id)>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Unit</label>
        <select name="unit_id" class="form-select relative z-20" required>
            <option value="">-- Select Unit --</option>
            @foreach($units as $u)
                <option value="{{ $u->id }}"
                    @selected(old('unit_id', $product->unit_id ?? '') == $u->id)>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Measurement unit used for orders and stock.
        </p>
    </div>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Crop</label>
        <select name="crop_id" class="form-select relative z-20">
            <option value="">-- Select Crop --</option>
            @foreach($crops as $c)
                <option value="{{ $c->id }}"
                    @selected(old('crop_id', $product->crop_id ?? '') == $c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col relative z-10">
        <label class="block text-sm font-medium mb-1">Season</label>
        <select name="season_id" class="form-select relative z-20">
            <option value="">-- Select Season --</option>
            @foreach($seasons as $s)
                <option value="{{ $s->id }}"
                    @selected(old('season_id', $product->season_id ?? '') == $s->id)>
                    {{ $s->name }}
                </option>
            @endforeach
        </select>
    </div>

    <x-forms.input label="Base Price" name="price" type="number" step="0.01"
        :value="old('price', $product->price ?? 0)" required />
    <p class="text-xs text-gray-500 dark:text-gray-400">Selling price used in orders.</p>

    <x-forms.input label="Cost Price" name="cost_price" type="number" step="0.01"
        :value="old('cost_price', $product->cost_price ?? '')" />
    <p class="text-xs text-gray-500 dark:text-gray-400">Optional internal cost for margin reporting.</p>

    <x-forms.input label="HSN Code" name="hsn_code" type="text"
        :value="old('hsn_code', $product->hsn_code ?? '')" />

    <x-forms.input label="GST %" name="gst_percent" type="number" step="0.01"
        :value="old('gst_percent', $product->gst_percent ?? 0)" required />

    <x-forms.input label="Minimum Order Quantity" name="min_order_qty" type="number"
        :value="old('min_order_qty', $product->min_order_qty ?? 1)" required />

    <x-forms.input label="Maximum Order Quantity" name="max_order_qty" type="number"
        :value="old('max_order_qty', $product->max_order_qty ?? '')" />

    <x-forms.input label="Shelf Life (Days)" name="shelf_life_days" type="number"
        :value="old('shelf_life_days', $product->shelf_life_days ?? '')" />

    <div class="flex flex-col">
        <label class="block text-sm font-medium mb-1">Short Description</label>
        <textarea name="short_description" class="form-textarea" rows="2">{{ old('short_description', $product->short_description ?? '') }}</textarea>
    </div>

    <div class="flex flex-col">
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" class="form-textarea" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <x-forms.input
        label="Tags (comma separated)"
        name="tags"
        type="text"
        :value="old('tags', isset($product) ? $product->tags->pluck('tag')->implode(', ') : '')"
        placeholder="Best Seller, Govt Approved"
    />

    <div class="flex flex-col">
        <label class="block text-sm font-medium mb-1">Product Images</label>
        <input type="file" name="images[]" multiple class="form-input">
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Upload one or more images. First image becomes primary.
        </p>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <input type="hidden" name="is_organic" value="0">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_organic" value="1"
                @checked(old('is_organic', $product->is_organic ?? false))>
            Organic Product
        </label>

        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1"
                @checked(old('is_active', $product->is_active ?? true))>
            Active
        </label>
    </div>

</div>
