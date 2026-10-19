<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of users and their roles with statistics.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $selectedRole = $request->query('role');

        $query = User::with('roles');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($selectedRole)) {
            $query->whereHas('roles', function ($q) use ($selectedRole) {
                $q->where('name', $selectedRole);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        // Statistics
        $totalUsers = User::count();
        $totalSuperAdmins = User::role('super-admin')->count();
        $totalAdmins = User::role('admin')->count();
        $totalMembers = User::role('member')->count();

        return view('admin.roles.index', compact(
            'users',
            'roles',
            'search',
            'selectedRole',
            'totalUsers',
            'totalSuperAdmins',
            'totalAdmins',
            'totalMembers'
        ));
    }

    /**
     * Create a new user with an assigned role.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', Rule::in(['super-admin', 'admin', 'member'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.roles.index')
            ->with('success', "Pengguna {$user->name} berhasil ditambahkan dengan role {$request->role}!");
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::in(['super-admin', 'admin', 'member'])],
        ]);

        // Safety check: Prevent the currently logged in super-admin from demoting themselves
        if ($user->id === Auth::id() && $request->role !== 'super-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri dari Super-Admin!');
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role untuk pengguna {$user->name} berhasil diubah menjadi {$request->role}!");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Pengguna {$userName} berhasil dihapus!");
    }
}
