<?php

namespace App\Services\User\Contracts;

use App\Dtos\User\CreateUserData;
use App\Dtos\User\LoginUserData;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\PersonalAccessTokenResult;

interface UserServiceInterface
{
    /**
     * @param CreateUserData $data
     * @return User
     */
    public function register(CreateUserData $data): User;

    /**
     * @param LoginUserData $data
     * @return User
     */
    public function attemptLogin(LoginUserData $data): User;

    /**
     * @param User $user
     * @return PersonalAccessTokenResult
     */
    public function authenticate(User $user): PersonalAccessTokenResult;

    /**
     * @return ?Authenticatable
     */
    public function getAuthenticatedUser(): ?Authenticatable;

    /**
     * @return void
     */
    public function logout(): void;
}
