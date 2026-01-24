<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('roles.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Roles') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Role') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Update role details and permissions') }}
        </p>
    </div>

    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">

                        <form action="{{ route('roles.update', $role) }}"
                              method="POST"
                              class="max-w-5xl space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Role Name -->
                            <x-forms.input
                                label="Role Name"
                                name="name"
                                type="text"
                                value="{{ old('name', $role->name) }}"
                                required
                            />

                            <!-- Permissions -->
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                                    <label class="text-sm font-medium">
                                        {{ __('Permissions') }}
                                    </label>

                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                        <!-- Search -->
                                        <input
                                            type="text"
                                            id="permissionSearch"
                                            placeholder="Search permission..."
                                            class="w-full sm:w-64 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-1.5 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >

                                        <!-- Global Select -->
                                        <label class="flex items-center gap-2 text-xs cursor-pointer whitespace-nowrap">
                                            <input type="checkbox"
                                                   id="selectAllPermissions"
                                                   class="rounded text-indigo-600">
                                            <span class="text-gray-700 dark:text-gray-300">
                                                Select All
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                @php
                                    $groupedPermissions = $permissions
                                        ->sortBy('name')
                                        ->groupBy(function ($permission) {
                                            return explode('.', $permission->name)[0];
                                        });

                                    $actionOrder = ['view', 'create', 'edit', 'delete'];
                                @endphp

                                <div class="space-y-4" id="permissionContainer">
                                    @foreach($groupedPermissions as $group => $items)
                                        @php
                                            $sortedItems = $items->sortBy(function ($perm) use ($actionOrder) {
                                                $action = explode('.', $perm->name)[1] ?? '';
                                                return array_search($action, $actionOrder);
                                            });
                                        @endphp

                                        <div class="permission-group border border-gray-200 dark:border-gray-700 rounded-lg p-3"
                                             data-group-wrapper>
                                            <div class="mb-2 flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 capitalize">
                                                    {{ str_replace('_', ' ', $group) }}
                                                </h3>

                                                <button type="button"
                                                        class="text-xs text-indigo-600 hover:underline"
                                                        onclick="toggleGroup('{{ $group }}')">
                                                    Select All
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2"
                                                 data-group="{{ $group }}">
                                                @foreach($sortedItems as $permission)
                                                    @php
                                                        $action = explode('.', $permission->name)[1] ?? $permission->name;
                                                    @endphp
                                                    <label
                                                        class="permission-item flex items-center gap-2 px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40"
                                                        data-permission="{{ strtolower($permission->name) }}">
                                                        <input
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            class="rounded text-indigo-600"
                                                            @checked(in_array($permission->id, $rolePermissions ?? []))
                                                        >
                                                        <span class="text-xs text-gray-700 dark:text-gray-200 capitalize">
                                                            {{ $action }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 pt-4">
                                <x-button type="primary">
                                    {{ __('Update Role') }}
                                </x-button>

                                <a href="{{ route('roles.index') }}"
                                   class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleGroup(group) {
                const container = document.querySelector(`[data-group="${group}"]`);
                if (!container) return;

                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                checkboxes.forEach(cb => cb.checked = !allChecked);
                updateGlobalCheckbox();
            }

            function updateGlobalCheckbox() {
                const all = document.querySelectorAll('input[name="permissions[]"]');
                const checked = document.querySelectorAll('input[name="permissions[]"]:checked');

                const global = document.getElementById('selectAllPermissions');
                if (!global) return;

                global.checked = all.length === checked.length;
                global.indeterminate = checked.length > 0 && checked.length < all.length;
            }

            function filterPermissions(term) {
                term = term.toLowerCase();

                document.querySelectorAll('.permission-group').forEach(group => {
                    let anyVisible = false;

                    group.querySelectorAll('.permission-item').forEach(item => {
                        const name = item.dataset.permission;
                        const match = name.includes(term);
                        item.style.display = match ? '' : 'none';
                        if (match) anyVisible = true;
                    });

                    group.style.display = anyVisible ? '' : 'none';
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const global = document.getElementById('selectAllPermissions');
                const search = document.getElementById('permissionSearch');

                if (global) {
                    global.addEventListener('change', function () {
                        const all = document.querySelectorAll('input[name="permissions[]"]');
                        all.forEach(cb => cb.checked = this.checked);
                    });
                }

                document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
                    cb.addEventListener('change', updateGlobalCheckbox);
                });

                if (search) {
                    search.addEventListener('input', function () {
                        filterPermissions(this.value);
                    });
                }

                updateGlobalCheckbox();
            });
        </script>
    @endpush
</x-layouts.app>
