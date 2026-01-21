<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <span class="mx-2">›</span>
        <a href="{{ route('users.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Users') }}</a>
        <span class="mx-2">›</span>
        <span>{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Edit User') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Update user details') }}</p>
    </div>

    <div class="max-w-6xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-medium text-gray-800 dark:text-gray-100">{{ __('User Details') }}</h2>
            </div>

            <div class="p-4">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
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
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <!-- Avatar -->
                        <div class="lg:col-span-1 flex flex-col items-center gap-3">
                            <div
                                class="w-28 h-28 rounded-full overflow-hidden border dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
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
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <x-forms.input label="Username" name="username"
                                    value="{{ old('username', $user->username) }}" />
                                <x-forms.input label="Email Address" name="email" type="email"
                                    value="{{ old('email', $user->email) }}" required />
                                <x-forms.input label="Phone" name="phone"
                                    value="{{ old('phone', $user->phone) }}" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <x-forms.input label="Full Name" name="name"
                                    value="{{ old('name', $user->name) }}" required />

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Status</label>
                                    <select name="status"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                                        <option value="blocked" @selected(old('status', $user->status) === 'blocked')>Blocked</option>
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Gender</label>
                                    <select name="gender"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="">-- Select --</option>
                                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <x-forms.input label="Date of Birth" name="dob" type="date"
                                    value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Security -->
                    <section class="space-y-2 pt-4 border-t dark:border-gray-700">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Security</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <x-forms.input label="New Password (optional)" name="password" type="password" />
                            <x-forms.input label="Confirm New Password" name="password_confirmation" type="password" />
                        </div>
                    </section>

                    <!-- Roles -->
                    <section class="space-y-2 pt-4 border-t dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Roles</h3>
                            <label class="flex items-center gap-2 text-xs cursor-pointer">
                                <input type="checkbox" id="selectAllRoles" class="rounded text-indigo-600">
                                <span>Select All</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2">
                            @foreach ($roles as $role)
                                <label
                                    class="flex items-center gap-2 px-2 py-1 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="rounded text-indigo-600"
                                        @checked(in_array($role->id, old('roles', $userRoles ?? [])))>
                                    <span class="text-xs capitalize">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('users.index') }}"
                            class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Update User') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('selectAllRoles')?.addEventListener('change', function() {
                document.querySelectorAll('input[name="roles[]"]').forEach(cb => cb.checked = this.checked);
            });

            const input = document.getElementById('avatarInput');
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');

            input?.addEventListener('change', function() {
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
