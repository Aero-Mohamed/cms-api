<?php

namespace App\Repositories\User\Contracts;

use App\Dtos\User\CreateOperatorData;
use App\Enums\SystemRoleEnum;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Passport\Contracts\OAuthenticatable;

interface UserRepositoryInterface
{
    /**
     * Create a new user
     *
     * @param CreateOperatorData $data
     * @param SystemRoleEnum $role
     * @return User
     */
    public function create(CreateOperatorData $data, SystemRoleEnum $role): User;

    /**
     * Find the user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Delete user tokens
     *
     * @param OAuthenticatable $user
     * @return void
     */
    public function deleteTokens(OAuthenticatable $user): void;

    /**
     * Get the currently authenticated user
     *
     * @return ?Authenticatable
     */
    public function getCurrentUser(): ?Authenticatable;

    /**
     * Get Users with role Operator
     * @return LengthAwarePaginator
     */
    public function getOperators(): LengthAwarePaginator;

    /**
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void;
}
