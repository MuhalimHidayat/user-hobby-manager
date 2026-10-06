<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);
            $user->syncHobbies($data['hobbies'] ?? []);

            return $user;
        });
    }

    /**
     * Update an existing user.
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\User
     */
    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);
            $user->syncHobbies($data['hobbies'] ?? []);

            return $user;
        });
    }
}