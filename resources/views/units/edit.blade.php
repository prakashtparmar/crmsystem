<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('units.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Units') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Unit') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update unit details') }}</p>
    </div>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('units.update', $unit) }}" method="POST" class="max-w-2xl space-y-4">
                    @csrf
                    @method('PUT')

                    <x-forms.input
                        label="Unit Name"
                        name="name"
                        type="text"
                        value="{{ old('name', $unit->name) }}"
                        required
                    />

                    <x-forms.input
                        label="Symbol (optional)"
                        name="symbol"
                        type="text"
                        value="{{ old('symbol', $unit->symbol) }}"
                        placeholder="kg, ltr, pcs"
                    />

                    <div class="flex items-center gap-3 pt-4">
                        <x-button type="primary">
                            {{ __('Update Unit') }}
                        </x-button>

                        <a href="{{ route('units.index') }}"
                           class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layouts.app>
