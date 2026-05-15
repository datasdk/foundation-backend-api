<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Crm\Services\UserService;
use Modules\Email\Services\InviteService;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function invite(Request $request, UserService $users, InviteService $invites)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $role = Role::where('name', $data['role'])
            ->where('guard_name', config('auth.defaults.guard'))
            ->firstOrFail();

        $user = $users->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'role' => $role->id,
            'invite' => false,
            'send_activation' => false,
        ]);

        $user->assignRole($role);
        $invites->send($user);

        return response()->json($user->load('roles'), 201);
    }

    public function destroy(Request $request, User $admin)
    {
        if (!$admin->isModerator()) {
            return response()->json(['message' => 'User is not an admin'], 422);
        }

        $remainingAdmins = User::role(['admin', 'editor', 'analyzer'])->whereKeyNot($admin->getKey())->count();

        if ($remainingAdmins === 0) {
            return response()->json(['message' => "You can't delete the last admin"], 400);
        }

        $admin->delete();

        return response()->noContent();
    }
}
