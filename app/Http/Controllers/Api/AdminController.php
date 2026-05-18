<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    private const ADMIN_ROLES = ['admin', 'editor', 'analyzer'];

    public function index()
    {
        $this->authorizeAdminManagement();

        return User::role(self::ADMIN_ROLES)
            ->with('roles')
            ->orderBy('first_name')
            ->get();
    }

    public function invite(Request $request)
    {
        $this->authorizeAdminManagement();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'exists:roles,name'],
        ]);

        $password = Str::random(16);

        $user = User::create([
            'uid' => (string) Str::uuid(),
            'username' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'type' => 'admin',
        ]);

        $user->password = Hash::make($password);
        $user->save();
        $user->syncRoles($data['roles']);

        return response()->json([
            'admin' => $user->load('roles'),
            'temporary_password' => $password,
        ], 201);
    }

    public function update(Request $request, User $admin)
    {
        $this->authorizeAdminManagement();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'exists:roles,name'],
        ]);

        if ($admin->hasRole('admin') && !in_array('admin', $data['roles'], true) && User::role('admin')->count() <= 1) {
            return response()->json(['message' => 'Du kan ikke fjerne admin-rollen fra den sidste admin.'], 400);
        }

        $admin->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ])->save();

        $admin->syncRoles($data['roles']);

        return response()->json($admin->load('roles'));
    }

    public function destroy(User $admin)
    {
        $this->authorizeAdminManagement();

        if ($admin->hasRole('admin') && User::role('admin')->count() <= 1) {
            return response()->json(['message' => 'Du kan ikke slette den sidste admin.'], 400);
        }

        $admin->delete();

        return response()->noContent();
    }

    public function roles()
    {
        $this->authorizeAdminManagement();

        return Role::query()
            ->whereIn('name', self::ADMIN_ROLES)
            ->orderByRaw("FIELD(name, 'admin', 'editor', 'analyzer')")
            ->get();
    }

    private function authorizeAdminManagement(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }
}
