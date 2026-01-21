<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <div class="mb-5 flex items-center text-xs text-gray-500 dark:text-gray-400">
            <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
            <span class="mx-2">›</span>
            <a href="{{ route('users.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Users') }}</a>
            <span class="mx-2">›</span>
            <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Create') }}</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Create User') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Add a new user to the system') }}</p>
        </div>

        <div class="max-w-6xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                @if ($errors->any())
                    <div class="m-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
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
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Profile Information
                        </h2>

                        <div class="grid grid-cols-1 gap-4">

                            <!-- Avatar -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden border dark:border-gray-700 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                    <img id="avatarPreview" src="{{ asset('images/avatar-placeholder.png') }}"
                                         class="w-full h-full object-cover hidden">
                                    <span id="avatarPlaceholder" class="text-[10px] text-gray-400">No Image</span>
                                </div>

                                <div class="flex-1">
                                    <label class="block text-xs font-medium mb-1">Avatar</label>
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                           class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                </div>
                            </div>

                            <x-forms.input label="Username" name="username" value="{{ old('username') }}" />
                            <x-forms.input label="Email Address" name="email" type="email" value="{{ old('email') }}" required />
                            <x-forms.input label="Phone" name="phone" value="{{ old('phone') }}" />
                            <x-forms.input label="Full Name" name="name" value="{{ old('name') }}" required />

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="active" @selected(old('status','active') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                                    <option value="blocked" @selected(old('status') === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select name="gender" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">-- Select --</option>
                                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                                    <option value="other" @selected(old('gender') === 'other')>Other</option>
                                </select>
                            </div>

                            <x-forms.input label="Date of Birth" name="dob" type="date" value="{{ old('dob') }}" />
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300 mb-4">
                            Security
                        </h2>

                        <div class="grid grid-cols-1 gap-4 max-w-3xl">
                            <x-forms.input label="Password" name="password" type="password" required />
                            <x-forms.input label="Confirm Password" name="password_confirmation" type="password" required />
                        </div>
                    </div>

      <!-- Roles -->
<div class="p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
            Roles & Permissions
        </h2>

        <label class="flex items-center gap-2 text-xs cursor-pointer text-gray-600 dark:text-gray-300">
            <input type="checkbox" id="selectAllRoles" class="rounded">
            <span>Select All</span>
        </label>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($roles as $role)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-900/40 hover:shadow-sm transition">
                <label class="flex items-center gap-2 mb-2 cursor-pointer">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                           @checked(in_array($role->id, old('roles', [])))>
                    <span class="text-sm font-semibold capitalize text-gray-800 dark:text-gray-100">
                        {{ $role->name }}
                    </span>
                </label>

                @if ($role->permissions->count())
                    <div class="flex flex-wrap gap-1 pl-5">
                        @foreach ($role->permissions as $perm)
                            <span class="px-2 py-0.5 text-[10px] rounded-full bg-indigo-100 text-indigo-700
                                         dark:bg-indigo-900/40 dark:text-indigo-300">
                                {{ $perm->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="pl-5 text-[10px] text-gray-400">
                        No permissions attached
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>


                    <!-- Actions -->
                    <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 rounded-b-xl">
                        <a href="{{ route('users.index') }}"
                           class="px-3 py-1.5 rounded-md border text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
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
