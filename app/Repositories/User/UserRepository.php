<?php

namespace App\Repositories\User;

use App\Models\User\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    /**
     * @param array $data
     * @return User
     */
    public function create($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param User $user
     * @param string $password
     * @return User
     * @throws \Exception
     */
    public function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(generateRememberToken());
        $user->save();

        return $user;
    }
}
