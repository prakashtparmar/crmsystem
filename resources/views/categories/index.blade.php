<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Categories') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Categories') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all product categories') }}
            </p>
        </div>

        @can('categories.create')
            <a href="{{ route('categories.create') }}">
                <x-button type="primary">
                    + {{ __('Add Category') }}
                </x-button>
            </a>
        @endcan
    </div>

    <div class="p-6">
        <form id="bulkForm" action="{{ route('categories.bulkAction') }}" method="POST">
            @csrf

            @can('categories.delete')
                <div class="mb-3 flex items-center gap-2">
                    <select name="action" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm">
                        <option value="">Bulk Action</option>
                        <option value="delete">Delete</option>
                    </select>

                    <button type="submit" onclick="return confirmBulkAction();"
                        class="px-3 py-1.5 rounded-md border border-gray-300 bg-white text-gray-800 text-sm
                               hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-100
                               dark:border-gray-700 dark:hover:bg-gray-800">
                        Apply
                    </button>
                </div>
            @endcan

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
                <table id="categoriesTable" class="w-full text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            @can('categories.delete')
                                <th class="px-3 py-2 text-center w-10">
                                    <input type="checkbox" id="selectAll">
                                </th>
                            @endcan
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Slug</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Created</th>
                            @canany(['categories.edit','categories.delete'])
                                <th class="px-3 py-2 text-right">Action</th>
                            @endcanany
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                @can('categories.delete')
                                    <td class="px-3 py-2 text-center">
                                        <input type="checkbox" class="row-checkbox" name="ids[]"
                                               value="{{ $category->id }}">
                                    </td>
                                @endcan

                                <td class="px-3 py-2 text-gray-500">{{ $category->id }}</td>

                                <td class="px-3 py-2 font-medium text-blue-600 dark:text-blue-400">
                                    {{ $category->name }}
                                </td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $category->slug }}
                                </td>

                                <td class="px-3 py-2">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $category->created_at->format('d M Y') }}
                                </td>

                                @canany(['categories.edit','categories.delete'])
                                    <td class="px-3 py-2 text-right relative">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button type="button" @click="open = !open"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6-2a2 2 0 100 4 2 2 0 000-4zm4-2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </button>

                                            <div x-cloak x-show="open" @click.away="open = false" x-transition
                                                class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/10 z-50">
                                                @can('categories.edit')
                                                    <a href="{{ route('categories.edit', $category) }}"
                                                       class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('categories.delete')
                                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                          onsubmit="return confirm('Delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const table = $('#categoriesTable').DataTable({
                    dom: 'lBfrtip',
                    pageLength: 10,
                    lengthMenu: [[5,10,50,100],[5,10,50,100]],
                    autoWidth: false,
                    scrollX: true,
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Export Excel'
                    }],
                });

                const selectAll = document.getElementById('selectAll');

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        table.rows({ page: 'current' }).nodes().to$()
                             .find('.row-checkbox').prop('checked', this.checked);
                    });

                    $('#categoriesTable').on('change', '.row-checkbox', function() {
                        const all = table.rows({ page: 'current' }).nodes().to$().find('.row-checkbox').length;
                        const checked = table.rows({ page: 'current' }).nodes().to$().find('.row-checkbox:checked').length;
                        selectAll.checked = (all === checked);
                    });
                }
            });

            function confirmBulkAction() {
                const action = document.querySelector('select[name="action"]').value;
                const checked = document.querySelectorAll('.row-checkbox:checked').length;

                if (!action) {
                    alert('Please select a bulk action.');
                    return false;
                }

                if (checked === 0) {
                    alert('Please select at least one category.');
                    return false;
                }

                if (action === 'delete') {
                    return confirm('Are you sure you want to delete selected categories?');
                }

                return true;
            }
        </script>
    @endpush
</x-layouts.app>
