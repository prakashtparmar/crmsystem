<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Roles') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Roles') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage system roles') }}
            </p>
        </div>

        <a href="{{ route('roles.create') }}">
            <x-button type="primary">
                + {{ __('Add Role') }}
            </x-button>
        </a>
    </div>

    <div class="p-6">
        <!-- BULK FORM ADDED -->
        <form id="bulkForm" action="{{ route('roles.bulkAction') }}" method="POST">
            @csrf

            <!-- Bulk Actions (same style as Users page) -->
            <div class="mb-3 flex items-center gap-2">
                <select name="action"
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm">
                    <option value="">Bulk Action</option>
                    <option value="delete">Delete</option>
                </select>

                <button type="submit"
                    onclick="return confirmBulkAction();"
                    class="px-3 py-1.5 rounded-md
                           border border-gray-300
                           bg-white text-gray-800 text-sm
                           hover:bg-gray-100
                           dark:bg-gray-900 dark:text-gray-100
                           dark:border-gray-700 dark:hover:bg-gray-800">
                    Apply
                </button>
            </div>

            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 overflow-visible">
                <table id="rolesTable" class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2 text-center w-10">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Guard</th>
                            <th class="px-3 py-2">Permissions</th>
                            <th class="px-3 py-2">Created</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 align-top">
                                <td class="px-3 py-2 text-center">
                                    <!-- name added for bulk submit -->
                                    <input type="checkbox" class="row-checkbox" name="ids[]" value="{{ $role->id }}">
                                </td>

                                <td class="px-3 py-2 text-gray-500">{{ $role->id }}</td>

                                <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                    {{ $role->name }}
                                </td>

                                <td class="px-3 py-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                        {{ $role->guard_name }}
                                    </span>
                                </td>

                                <td class="px-3 py-2">
                                    @php
                                        $grouped = $role->permissions->groupBy(function ($permission) {
                                            return explode('.', $permission->name)[0];
                                        });
                                    @endphp

                                    <div class="max-w-[360px] max-h-24 overflow-y-auto overflow-x-hidden pr-1 space-y-1">
                                        @forelse($grouped as $group => $items)
                                            <div class="flex items-start gap-1 text-xs">
                                                <span
                                                    class="font-semibold text-gray-600 dark:text-gray-300 capitalize min-w-[70px]">
                                                    {{ $group }}:
                                                </span>

                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($items as $permission)
                                                        @php
                                                            $action = explode('.', $permission->name)[1] ?? $permission->name;
                                                        @endphp
                                                        <span
                                                            class="px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                            {{ $action }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-gray-400 text-xs">No permissions</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $role->created_at->format('d M Y') }}
                                </td>

                                <td class="px-3 py-2 text-right relative">
                                    <!-- keep your existing action menu -->
                                    ...
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#rolesTable').DataTable({
                    pageLength: 10,
                    scrollX: true,
                });

                $('#selectAll').on('change', function() {
                    $('.row-checkbox').prop('checked', this.checked);
                });
            });

            function confirmBulkAction() {
                const action = document.querySelector('select[name="action"]').value;
                const checked = document.querySelectorAll('.row-checkbox:checked').length;

                if (!action) {
                    alert('Please select a bulk action.');
                    return false;
                }

                if (checked === 0) {
                    alert('Please select at least one role.');
                    return false;
                }

                if (action === 'delete') {
                    return confirm('Are you sure you want to delete selected roles?');
                }

                return true;
            }
        </script>
    @endpush
</x-layouts.app>
