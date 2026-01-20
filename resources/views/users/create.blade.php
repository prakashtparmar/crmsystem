<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('users.index') }}"
           class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Users') }}</a>
        <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create User') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Add a new user to the system') }}
        </p>
    </div>

    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('users.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="max-w-2xl space-y-4">
                            @csrf

                            <x-forms.input
                                label="Full Name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                            />

                            <x-forms.input
                                label="Email Address"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                            />

                            <x-forms.input
                                label="Phone"
                                name="phone"
                                type="text"
                                value="{{ old('phone') }}"
                            />

                            <div>
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select name="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="active" @selected(old('status') === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                                    <option value="blocked" @selected(old('status') === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select name="gender"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">-- Select --</option>
                                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                                    <option value="other" @selected(old('gender') === 'other')>Other</option>
                                </select>
                            </div>

                            <x-forms.input
                                label="Date of Birth"
                                name="dob"
                                type="date"
                                value="{{ old('dob') }}"
                            />

                            <div>
                                <label class="block text-sm font-medium mb-1">Avatar</label>
                                <input type="file"
                                       name="avatar"
                                       accept="image/*"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG up to 2MB</p>
                            </div>

                            <x-forms.input
                                label="Password"
                                name="password"
                                type="password"
                                required
                            />

                            <x-forms.input
                                label="Confirm Password"
                                name="password_confirmation"
                                type="password"
                                required
                            />

                            <div class="flex items-center gap-3 pt-4">
                                <x-button type="primary">
                                    {{ __('Save User') }}
                                </x-button>

                                <a href="{{ route('users.index') }}"
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
</x-layouts.app>
