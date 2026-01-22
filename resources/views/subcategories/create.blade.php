<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('subcategories.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Sub Categories') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Sub Category') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Add a new sub category') }}
        </p>
    </div>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('subcategories.create')
                <form action="{{ route('subcategories.store') }}" method="POST" class="max-w-2xl space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select name="category_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                            required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-forms.input
                        label="Sub Category Name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                    />

                    <x-forms.input
                        label="Slug"
                        name="slug"
                        type="text"
                        value="{{ old('slug') }}"
                    />

                    <input type="hidden" name="is_active" value="0">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 dark:border-gray-700"
                            @checked(old('is_active', true))>
                        <label class="text-sm">Active</label>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <x-button type="primary">{{ __('Save Sub Category') }}</x-button>
                        <a href="{{ route('subcategories.index') }}"
                           class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            @else
                <div class="p-4 rounded bg-yellow-100 text-yellow-800">
                    You do not have permission to create sub categories.
                </div>
            @endcan
        </div>
    </div>
</x-layouts.app>
