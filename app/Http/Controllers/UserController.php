<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load all users for DataTables (client-side)
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|min:8|confirmed',

            'status'   => 'nullable|in:active,inactive,blocked',
            'dob'      => 'nullable|date',
            'gender'   => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),

            'status'   => $data['status'] ?? 'active',
            'dob'      => $data['dob'] ?? null,
            'gender'   => $data['gender'] ?? null,
            'avatar'   => $data['avatar'] ?? null,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:8|confirmed',

            'status'   => 'nullable|in:active,inactive,blocked',
            'dob'      => 'nullable|date',
            'gender'   => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name   = $data['name'];
        $user->email  = $data['email'];
        $user->phone  = $data['phone'] ?? null;
        $user->status = $data['status'] ?? $user->status;
        $user->dob    = $data['dob'] ?? null;
        $user->gender = $data['gender'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['avatar'])) {
            $user->avatar = $data['avatar'];
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
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
     * Handle bulk actions from Users list.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,block,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'exists:users,id',
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
                    if (auth()->id() === $user->id) {
                        continue; // prevent self delete in bulk
                    }

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
