<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <span class="mx-2">›</span>
        <a href="{{ route('users.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Users') }}</a>
        <span class="mx-2">›</span>
        <span>{{ __('Create') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Create User') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Add a new user to the system') }}</p>
    </div>

    <div class="max-w-6xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-medium text-gray-800 dark:text-gray-100">
                    {{ __('User Details') }}
                </h2>
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

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Profile -->
                    <section class="space-y-3">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            Profile Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Avatar -->
                            <div class="md:col-span-1 flex flex-col items-center gap-2">
                                <div class="w-16 h-16 rounded-full overflow-hidden border dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                    <img id="avatarPreview"
                                         src="{{ asset('images/avatar-placeholder.png') }}"
                                         class="w-full h-full object-cover hidden">
                                    <span id="avatarPlaceholder" class="text-xs text-gray-400">No Image</span>
                                </div>

                                <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                       class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>

                            <!-- Fields -->
                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <x-forms.input label="Username" name="username" value="{{ old('username') }}" />
                                <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" required />
                                <x-forms.input label="Phone" name="phone" value="{{ old('phone') }}" />

                                <x-forms.input label="Full Name" name="name" value="{{ old('name') }}" required />

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Status</label>
                                    <select name="status"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="active" @selected(old('status','active') === 'active')>Active</option>
                                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                                        <option value="blocked" @selected(old('status') === 'blocked')>Blocked</option>
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium mb-1">Gender</label>
                                    <select name="gender"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                        <option value="">-- Select --</option>
                                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                                        <option value="other" @selected(old('gender') === 'other')>Other</option>
                                    </select>
                                </div>

                                <x-forms.input label="Date of Birth" name="dob" type="date" value="{{ old('dob') }}" />
                            </div>
                        </div>
                    </section>

                    <!-- Security -->
                    <section class="space-y-3">
                        <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Security</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-w-2xl">
                            <x-forms.input label="Password" name="password" type="password" required />
                            <x-forms.input label="Confirm Password" name="password_confirmation" type="password" required />
                        </div>
                    </section>

                    <!-- Roles -->
                    <section class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                                Roles & Permissions
                            </h3>

                            <label class="flex items-center gap-2 text-xs cursor-pointer">
                                <input type="checkbox" id="selectAllRoles" class="rounded">
                                <span>Select All</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2">
                            @foreach ($roles as $role)
                                <label class="flex items-center gap-2 px-2 py-1.5 rounded-md border border-gray-200 dark:border-gray-700">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                           @checked(in_array($role->id, old('roles', [])))>
                                    <span class="text-xs capitalize">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('users.index') }}"
                           class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300">
                            {{ __('Cancel') }}
                        </a>
                        <x-button type="primary" class="px-4 py-1.5 text-sm">
                            {{ __('Save User') }}
                        </x-button>
                    </div>
                </form>
            </div>
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
