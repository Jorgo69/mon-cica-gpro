<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterUserAction
{
    /**
     * Execute the action to register a new user.
     * 
     * @param array $data Validated user data
     * @return User
     */
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'organization_id' => null, // Explicitement nul au départ pour le flux onboarding
        ]);

        event(new Registered($user));

        return $user;
    }
}
