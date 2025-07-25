<?php

namespace App\Repositories\User;

use App\Dtos\User\CreateOperatorData;
use App\Enums\SystemRoleEnum;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use GuzzleHttp\Promise\Create;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Contracts\OAuthenticatable;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user
     *
     * @param CreateOperatorData $data
     * @param SystemRoleEnum $role
     * @return User
     */
    public function create(CreateOperatorData $data, SystemRoleEnum $role): User
    {
        $user = User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        $user->assignRole($role->value);

        return $user;
    }

    /**
     * Find the user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::query()->find($id);
    }

    /**
     * Delete user tokens
     *
     * @param User $user
     * @return void
     */
    public function deleteTokens(OAuthenticatable $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Get the currently authenticated user
     *
     * @return ?Authenticatable
     */
    public function getCurrentUser(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getOperators(): LengthAwarePaginator
    {
        return User::role(SystemRoleEnum::OPERATOR->value)
            ->latest()
            ->paginate();
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
