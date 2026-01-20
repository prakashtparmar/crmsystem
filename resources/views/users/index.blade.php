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
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Users') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Manage all system users') }}
            </p>
        </div>

        <a href="{{ route('users.create') }}">
            <x-button type="primary">
                + {{ __('Add User') }}
            </x-button>
        </a>
    </div>

    <div class="p-6">
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
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Phone</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Email Verified</th>
                        <th class="px-3 py-2">Last Login</th>
                        <th class="px-3 py-2">Created</th>
                        <th class="px-3 py-2 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">
                                <input type="checkbox" class="row-checkbox" value="{{ $user->id }}">
                            </td>

                            <td class="px-3 py-2 text-gray-500">{{ $user->id }}</td>

                            <td class="px-3 py-2">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                        class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                @endif
                            </td>

                            <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-100">
                                {{ $user->name }}
                            </td>

                            <td class="px-3 py-2">{{ $user->email }}</td>
                            <td class="px-3 py-2">{{ $user->phone ?? '—' }}</td>

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
                                @if ($user->email_verified_at)
                                    <span class="text-green-600">Yes</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $user->last_login_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="px-3 py-2 text-right relative">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6-2a2 2 0 100 4 2 2 0 000-4zm4-2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>

                                    <div x-cloak x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/10 z-50">

                                        <a href="{{ route('users.edit', $user) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                $('#usersTable').DataTable({
                    dom: 'lBfrtip',
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 50, 100],
                        [5, 10, 50, 100]
                    ],
                    autoWidth: false,
                    scrollX: true,
                    responsive: false,
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Export Excel'
                    }],
                });

                $('#selectAll').on('change', function() {
                    $('.row-checkbox').prop('checked', this.checked);
                });
            });
        </script>
    @endpush
</x-layouts.app>
