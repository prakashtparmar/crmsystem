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
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit User') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update user details') }}</p>
    </div>

    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">

                        <form action="{{ route('users.update', $user) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="max-w-2xl space-y-4">
                            @csrf
                            @method('PUT')

                            <x-forms.input
                                label="Full Name"
                                name="name"
                                type="text"
                                value="{{ old('name', $user->name) }}"
                                required
                            />

                            <x-forms.input
                                label="Email Address"
                                name="email"
                                type="email"
                                value="{{ old('email', $user->email) }}"
                                required
                            />

                            <x-forms.input
                                label="Phone"
                                name="phone"
                                type="text"
                                value="{{ old('phone', $user->phone) }}"
                            />

                            <div>
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select name="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                                    <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                                    <option value="blocked" @selected(old('status', $user->status) === 'blocked')>Blocked</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select name="gender"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">-- Select --</option>
                                    <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                                    <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                                </select>
                            </div>

                            <x-forms.input
                                label="Date of Birth"
                                name="dob"
                                type="date"
                                value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}"
                            />

                         <div>
    <label class="block text-sm font-medium mb-1">Avatar</label>

    @if($user->avatar)
        <div class="mb-3 flex items-center gap-3">
            <img
                src="{{ asset('storage/' . $user->avatar) }}"
                alt="Avatar"
                class="h-16 w-16 rounded-full object-cover border border-gray-300 dark:border-gray-600"
            >
            <span class="text-xs text-gray-500">Current Avatar</span>
        </div>
    @endif

    <input
        type="file"
        name="avatar"
        accept="image/*"
        class="custom-file-input w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"
    >

    <p class="mt-1 text-xs text-gray-500">JPG, PNG up to 2MB</p>
</div>


                            <x-forms.input
                                label="New Password (optional)"
                                name="password"
                                type="password"
                            />

                            <x-forms.input
                                label="Confirm New Password"
                                name="password_confirmation"
                                type="password"
                            />

                            <div class="flex items-center gap-3 pt-4">
                                <x-button type="primary">
                                    {{ __('Update User') }}
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
