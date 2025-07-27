<?php

namespace App\Services\User\Contracts;

use App\Dtos\User\CreateOperatorData;
use App\Dtos\User\LoginUserData;
use App\Enums\SystemRoleEnum;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\PersonalAccessTokenResult;

interface UserServiceInterface
{
    /**
     * @param CreateOperatorData $data
     * @param SystemRoleEnum $role
     * @return User
     */
    public function create(CreateOperatorData $data, SystemRoleEnum $role): User;

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

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
