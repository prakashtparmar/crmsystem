<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <div class="mb-5 flex items-center text-xs text-gray-500 dark:text-gray-400">
            <a href="{{ route('dashboard') }}"
               class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
            <span class="mx-2">›</span>
            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Warehouses') }}</span>
        </div>

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Warehouses') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Manage inventory locations') }}</p>
            </div>

            <a href="{{ route('warehouses.create') }}">
                <x-button type="primary" class="px-4 py-1.5 text-sm">
                    + {{ __('New Warehouse') }}
                </x-button>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-6xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                        Warehouse List
                    </h2>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table id="warehousesTable" class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Code</th>
                                <th class="px-3 py-2 text-left">Name</th>
                                <th class="px-3 py-2 text-left">Address</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($warehouses as $w)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-3 py-2 text-gray-500">{{ $w->id }}</td>

                                    <td class="px-3 py-2 font-mono text-gray-700 dark:text-gray-200">
                                        {{ $w->code }}
                                    </td>

                                    <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                        {{ $w->name }}
                                    </td>

                                    <td class="px-3 py-2 text-gray-500">
                                        {{ $w->address ?? '—' }}
                                    </td>

                                    <td class="px-3 py-2">
                                        <span
                                            class="px-2 py-0.5 text-[11px] rounded-full
                                            {{ $w->is_active
                                                ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                                                : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' }}">
                                            {{ $w->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-2 text-right space-x-3 text-xs">
                                        <a href="{{ route('warehouses.edit', $w) }}"
                                           class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('warehouses.toggle', $w) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $w->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('warehouses.destroy', $w) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this warehouse?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-400 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <style>
            .dataTables_wrapper {
                font-size: 12px;
            }

            .dataTables_filter input,
            .dataTables_length select {
                border-radius: 0.375rem;
                border: 1px solid #e5e7eb;
                padding: 2px 6px;
            }

            .dark .dataTables_filter input,
            .dark .dataTables_length select {
                background: #111827;
                border-color: #374151;
                color: #e5e7eb;
            }

            .dataTables_paginate .paginate_button {
                padding: 2px 6px !important;
                margin: 0 1px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#warehousesTable').DataTable({
                    pageLength: 10,
                    scrollX: true,
                });
            });
        </script>
    @endpush
</x-layouts.app>
