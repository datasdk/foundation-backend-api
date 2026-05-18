<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Orion\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends OrionBaseController
{
    protected $model = User::class;

    protected $request = UserRequest::class;

    protected $sortableBy = [
        'id',
        'first_name',
        'last_name',
        'email',
        'created_at',
        'updated_at',
    ];

    protected $filterableBy = [
        'id',
        'email',
        'type',
        'active',
    ];

    protected $searchableBy = [
        'first_name',
        'last_name',
        'email',
    ];

    public function store(Request $request)
    {
        $data = $request->validate($this->userRequestRules('store'));

        $password = $data['password'] ?? null;
        unset($data['password'], $data['password_confirmation']);

        $user = new User($data);

        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->save();

        return response()->json($user->fresh(), 201);
    }

    public function show(Request $request, ...$args)
    {
        $user = User::findOrFail($args[0]);

        return response()->json(['data' => $user]);
    }

    public function update(Request $request, ...$args)
    {
        $user = User::findOrFail($args[0]);
        $data = $request->validate($this->userRequestRules('update'));

        if (array_key_exists('password', $data)) {
            $password = $data['password'];
            unset($data['password'], $data['password_confirmation']);

            if ($password) {
                $user->password = Hash::make($password);
            }
        }

        $user->fill($data)->save();

        return response()->json(['data' => $user->fresh()]);
    }

    public function destroy(Request $request, ...$args)
    {
        $user = User::findOrFail($args[0]);

        if (User::count() <= 1) {
            return response()->json(['message' => "You can't delete the last user"], 400);
        }

        $user->delete();

        return response()->noContent();
    }

    private function userRequestRules(string $action): array
    {
        $request = app(UserRequest::class);

        return match ($action) {
            'store' => array_merge($request->commonRules(), $request->storeRules()),
            'update' => array_merge($request->commonRules(), $request->updateRules()),
            default => $request->commonRules(),
        };
    }
}
