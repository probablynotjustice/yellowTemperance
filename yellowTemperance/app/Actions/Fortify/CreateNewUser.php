<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\Role;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),

            'roles' => ['required', 'array'],
            'roles.*' => ['in:customer,vendor,admin'],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        Wallet::create([
            'user_id' => $user->id,
        ]);

        $roleIds = Role::whereIn('name', $input['roles'])
            ->pluck('id');

        $user->roles()->attach($roleIds);
        return $user;
    }
}
