<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('crops.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Crops') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold">{{ __('Edit Crop') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update crop details') }}</p>
    </div>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border p-6">
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crops.update', $crop) }}" method="POST" class="max-w-2xl space-y-4">
                @csrf
                @method('PUT')

                <x-forms.input label="Crop Name" name="name" value="{{ old('name', $crop->name) }}" required />
                <x-forms.input label="Slug" name="slug" value="{{ old('slug', $crop->slug) }}" />

                <input type="hidden" name="is_active" value="0">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $crop->is_active))>
                    <label class="text-sm">Active</label>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <x-button type="primary">{{ __('Update Crop') }}</x-button>
                    <a href="{{ route('crops.index') }}" class="px-4 py-2 border rounded-md">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
