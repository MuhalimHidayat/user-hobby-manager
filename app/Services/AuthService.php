<?php 
namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Handle user registration.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data)
    {
        $user = User::create($data);
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle user login.
     *
     * @param array $credentials
     * @return string|null
     */
    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return null;
        }

        return $token;
    }
}