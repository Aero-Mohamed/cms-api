<?php

namespace App\Services\User\Actions;

use App\Dtos\User\LoginUserData;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param LoginUserData $data
     * @return User
     * @throws AuthenticationException
     */
    public function handle(LoginUserData $data): User
    {
        $credentials = $data->only('email', 'password')->toArray();

        $userProvider = Auth::getProvider();
        /** @var User|null $user */
        $user = $userProvider->retrieveByCredentials($credentials);

        if (!$user || !$userProvider->validateCredentials($user, $credentials)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $this->userRepository->deleteTokens($user);
        return $user;
    }
}
