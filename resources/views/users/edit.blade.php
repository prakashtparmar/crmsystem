<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Dashboard</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Users</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">Edit</span>
    </div>

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit User</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Update user details</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 space-y-6">

            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-100 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Avatar -->
                    <div class="lg:col-span-1 flex flex-col items-center gap-3">
                        <div class="w-32 h-32 rounded-full overflow-hidden border dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                            <img id="avatarPreview"
                                 src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar-placeholder.png') }}"
                                 class="w-full h-full object-cover {{ $user->avatar ? '' : 'hidden' }}">
                            <span id="avatarPlaceholder"
                                  class="text-xs text-gray-400 {{ $user->avatar ? 'hidden' : '' }}">
                                No Image
                            </span>
                        </div>

                        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Avatar</label>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*"
                               class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>

                    <!-- Fields -->
                    <div class="lg:col-span-3 space-y-4">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-forms.input label="Username" name="username" type="text" value="{{ old('username', $user->username) }}" />
                            <x-forms.input label="Email Address" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                            <x-forms.input label="Phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" />
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-forms.input label="Full Name" name="name" type="text" value="{{ old('name', $user->name) }}" required />

                            <div>
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                                    <option value="blocked" @selected(old('status', $user->status) === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select name="gender" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">-- Select --</option>
                                    <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                                    <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-forms.input label="Date of Birth" name="dob" type="date" value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}" />
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t dark:border-gray-700">
                    <x-forms.input label="New Password (optional)" name="password" type="password" />
                    <x-forms.input label="Confirm New Password" name="password_confirmation" type="password" />
                </div>

                <!-- Roles -->
                <div class="pt-4 border-t dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Roles</h2>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" id="selectAllRoles" class="rounded text-indigo-600">
                            <span>Select All</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-2 px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                       class="rounded text-indigo-600"
                                       @checked(in_array($role->id, old('roles', $userRoles ?? [])))>
                                <span class="text-xs capitalize">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <x-button type="primary">Update User</x-button>
                    <a href="{{ route('users.index') }}"
                       class="px-4 py-2 border rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('selectAllRoles')?.addEventListener('change', function () {
            document.querySelectorAll('input[name="roles[]"]').forEach(cb => cb.checked = this.checked);
        });

        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');

        input?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    </script>
    @endpush
</x-layouts.app>
