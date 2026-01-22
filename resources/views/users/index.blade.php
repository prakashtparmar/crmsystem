<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Users') }}</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ $showTrashed ? __('Deleted Users') : __('Users') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ $showTrashed ? __('View and restore deleted users') : __('Manage all system users') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('users.index', ['trash' => $showTrashed ? 0 : 1]) }}"
                class="px-3 py-2 rounded-md border text-sm">
                {{ $showTrashed ? 'View Active' : 'View Deleted' }}
            </a>

            @unless ($showTrashed)
                <a href="{{ route('users.create') }}">
                    <x-button type="primary">
                        + {{ __('Add User') }}
                    </x-button>
                </a>
            @endunless
        </div>
    </div>

    <div class="p-6">
        <form id="bulkForm" action="{{ route('users.bulkAction') }}" method="POST">
            @csrf

            <!-- Bulk Actions -->
            <!-- Bulk Actions -->
            <div class="mb-3 flex items-center gap-2">
                <select name="action" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm">
                    <option value="">Bulk Action</option>

                    @if ($showTrashed)
                        <option value="restore">Restore</option>
                    @else
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="block">Block</option>
                        <option value="delete">Delete</option>
                    @endif
                </select>

                <button type="submit" onclick="return confirmBulkAction();"
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
                <table id="usersTable" class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2 text-center w-10">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Avatar</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Username</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2">Phone</th>
                            <th class="px-3 py-2">Roles</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2">Phone</th>
                            <th class="px-3 py-2">Locked</th>
                            <th class="px-3 py-2">Gender</th>
                            <th class="px-3 py-2">DOB</th>
                            <th class="px-3 py-2">Last Login</th>
                            <th class="px-3 py-2">Created</th>
                            <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center">
                                    <input type="checkbox" class="row-checkbox" name="ids[]"
                                        value="{{ $user->id }}">
                                </td>

                                <td class="px-3 py-2 text-gray-500">{{ $user->id }}</td>

                                <td class="px-3 py-2">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                            class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-semibold text-white">
                                            {{ $user->initials() }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                    {{ $user->name }}
                                </td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $user->username ?? '—' }}
                                </td>

                                <td class="px-3 py-2">{{ $user->email }}</td>
                                <td class="px-3 py-2">{{ $user->phone ?? '—' }}</td>

                                <td class="px-3 py-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($user->roles as $role)
                                            <span class="px-2 py-0.5 text-xs rounded bg-indigo-100 text-indigo-700">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-3 py-2">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                {{ $user->status === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : ($user->status === 'blocked'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>

                                <td class="px-3 py-2">
                                    {!! $user->email_verified_at
                                        ? '<span class="text-green-600">Yes</span>'
                                        : '<span class="text-red-600">No</span>' !!}
                                </td>

                                <td class="px-3 py-2">
                                    {!! $user->phone_verified_at
                                        ? '<span class="text-green-600">Yes</span>'
                                        : '<span class="text-red-600">No</span>' !!}
                                </td>

                                <td class="px-3 py-2">
                                    @if ($user->locked_until && now()->lessThan($user->locked_until))
                                        <span class="text-red-600">Locked</span>
                                    @else
                                        <span class="text-green-600">No</span>
                                    @endif
                                </td>

                                <td class="px-3 py-2">{{ $user->gender ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $user->dob?->format('d M Y') ?? '—' }}</td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $user->last_login_at?->format('d M Y H:i') ?? '—' }}
                                </td>

                                <td class="px-3 py-2 text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <td class="px-3 py-2 text-right">
                                    @if ($showTrashed)
                                        <button type="submit" formaction="{{ route('users.restore', $user->id) }}"
                                            formmethod="POST" class="text-green-600 hover:underline"
                                            onclick="return confirm('Restore this user?')">
                                            Restore
                                        </button>
                                    @else
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="text-indigo-600 hover:underline">Edit</a>
                                    @endif
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
                $('#usersTable').DataTable({
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
                    alert('Please select at least one user.');
                    return false;
                }

                if (action === 'delete') {
                    return confirm('Are you sure you want to delete selected users?');
                }

                if (action === 'restore') {
                    return confirm('Restore selected users?');
                }

                return true;
            }
        </script>
    @endpush
</x-layouts.app>
