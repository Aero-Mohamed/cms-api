<?php

namespace App\Services\User\Actions;

use App\Models\User;
use Laravel\Passport\PersonalAccessTokenResult;

class GenerateTokenAction
{
    /**
     * @param User $user
     * @return PersonalAccessTokenResult
     */
    public function handle(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('API Token');
    }
}
