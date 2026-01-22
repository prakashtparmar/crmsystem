{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-forms.input
    label="Category Name"
    name="name"
    type="text"
    :value="old('name', $category->name ?? '')"
    required
/>

<x-forms.input
    label="Slug"
    name="slug"
    type="text"
    :value="old('slug', $category->slug ?? '')"
/>

<div>
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea
        name="description"
        rows="3"
        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
    >{{ old('description', $category->description ?? '') }}</textarea>
</div>

<input type="hidden" name="is_active" value="0">
<div class="flex items-center gap-2">
    <input
        type="checkbox"
        name="is_active"
        value="1"
        class="rounded border-gray-300 dark:border-gray-700"
        @checked(old('is_active', $category->is_active ?? true))
    >
    <label class="text-sm">Active</label>
</div>
