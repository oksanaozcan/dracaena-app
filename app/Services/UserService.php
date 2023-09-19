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

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function storeUser($name, $email)
    {
        try {
            DB::beginTransaction();

            $password = Str::random(10);

            $u = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ])->assignRole(RoleType::ASSISTANT);

            DB::commit();

            return [$password, $u];

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateUser(User $user, $name, $email)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'name' => $name,
                'email' => $email,
            ]);

            DB::commit();

        }  catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyUser($id)
    {
        try {
            DB::beginTransaction();

            $deletingUser = User::find($id);

            $deletingUser->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
