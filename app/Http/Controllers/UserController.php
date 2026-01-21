<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showTrashed = $request->boolean('trash');

        $users = $showTrashed
            ? User::onlyTrashed()->latest()->get()
            : User::latest()->get();

        return view('users.index', compact('users', 'showTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|min:8|confirmed',

            'status'   => 'nullable|in:active,inactive,blocked',
            'dob'      => 'nullable|date',
            'gender'   => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|max:2048',

            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'] ?? null,
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'status'   => $data['status'] ?? 'active',
            'dob'      => $data['dob'] ?? null,
            'gender'   => $data['gender'] ?? null,
            'avatar'   => $data['avatar'] ?? null,
            'password_changed_at' => now(),
        ]);

        if (!empty($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:8|confirmed',

            'status'   => 'nullable|in:active,inactive,blocked',
            'dob'      => 'nullable|date',
            'gender'   => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|max:2048',

            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name     = $data['name'];
        $user->username = $data['username'] ?? null;
        $user->email    = $data['email'];
        $user->phone    = $data['phone'] ?? null;
        $user->status   = $data['status'] ?? $user->status;
        $user->dob      = $data['dob'] ?? null;
        $user->gender   = $data['gender'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->password_changed_at = now();
        }

        if (isset($data['avatar'])) {
            $user->avatar = $data['avatar'];
        }

        $user->save();

        if (array_key_exists('roles', $data)) {
            $roles = empty($data['roles'])
                ? []
                : Role::whereIn('id', $data['roles'])->pluck('name')->toArray();

            $user->syncRoles($roles);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Restore a single user.
     */
    public function restore(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'User restored successfully.');
    }

    /**
     * Handle bulk actions from Users list.
     */
    public function bulkAction(Request $request)
{
    $request->validate([
        'action' => 'required|in:activate,deactivate,block,delete,restore',
        'ids'    => 'required|array',
    ]);

    // RESTORE (must validate against trashed records)
    if ($request->action === 'restore') {
        $request->validate([
            'ids.*' => 'required|integer',
        ]);

        User::onlyTrashed()->whereIn('id', $request->ids)->restore();

        return back()->with('success', 'Selected users restored.');
    }

    // NORMAL ACTIONS (active users)
    $request->validate([
        'ids.*' => 'exists:users,id',
    ]);

    $users = User::whereIn('id', $request->ids)->get();

    switch ($request->action) {
        case 'activate':
            $users->each->update(['status' => 'active']);
            break;

        case 'deactivate':
            $users->each->update(['status' => 'inactive']);
            break;

        case 'block':
            $users->each->update(['status' => 'blocked']);
            break;

        case 'delete':
            foreach ($users as $user) {
                if (auth()->id() === $user->id) continue;

                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $user->delete();
            }
            break;
    }

    return back()->with('success', 'Bulk action completed successfully.');
}

}
