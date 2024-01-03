<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Types\RoleType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $users = User::with('roles')->search('name', $search)
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $users;
    }

    public function storeUser($name, $email, $roleName)
    {
        try {
            DB::beginTransaction();

            $password = Str::random(10);

            $u = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'remember_token' => Str::random(10),
            ])->assignRole($roleName);

            DB::commit();

            return [$password, $u];

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateUser(User $user, $name, $email, $roleName)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'name' => $name,
                'email' => $email,
            ]);

            $user->removeRole($user->roles[0]);
            $user->assignRole($roleName);

            DB::commit();

        }  catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyUser(User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
