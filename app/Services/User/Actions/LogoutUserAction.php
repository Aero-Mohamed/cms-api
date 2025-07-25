<?php

namespace App\Services\User\Actions;

use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Contracts\OAuthenticatable;

class LogoutUserAction
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @param Authenticatable $user
     * @return void
     */
    public function handler(Authenticatable $user): void
    {
        if ($user instanceof OAuthenticatable) {
            $this->userRepository->deleteTokens($user);
        }
    }
}
