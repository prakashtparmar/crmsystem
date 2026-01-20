<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Seasons') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Seasons') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all seasons') }}
            </p>
        </div>

        <a href="{{ route('seasons.create') }}">
            <x-button type="primary">
                + {{ __('Add Season') }}
            </x-button>
        </a>
    </div>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4">
            <table id="seasonsTable" class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-3 py-2">ID</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Created</th>
                        <th class="px-3 py-2 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($seasons as $season)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-gray-500">{{ $season->id }}</td>
                            <td class="px-3 py-2 font-medium">{{ $season->name }}</td>
                            <td class="px-3 py-2 text-gray-500">
                                {{ $season->created_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-3 py-2 text-right">
                                <a href="{{ route('seasons.edit', $season) }}"
                                   class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('seasons.destroy', $season) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this season?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
