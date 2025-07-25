<?php

namespace App\Services\User;

use App\Dtos\User\CreateOperatorData;
use App\Dtos\User\LoginUserData;
use App\Enums\SystemRoleEnum;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Services\User\Actions\CreateUserAction;
use App\Services\User\Actions\GenerateTokenAction;
use App\Services\User\Actions\LoginUserAction;
use App\Services\User\Actions\LogoutUserAction;
use App\Services\User\Contracts\UserServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\PersonalAccessTokenResult;

class UserService implements UserServiceInterface
{
    /**
     * @param GenerateTokenAction $generateToken
     * @param LoginUserAction $loginUser
     * @param LogoutUserAction $logoutUser
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected GenerateTokenAction $generateToken,
        protected LoginUserAction $loginUser,
        protected LogoutUserAction $logoutUser,
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @param CreateOperatorData $data
     * @param SystemRoleEnum $role
     * @return User
     */
    public function create(CreateOperatorData $data, SystemRoleEnum $role): User
    {
        $user = $this->userRepository->create($data, $role);
        event(new Registered($user));

        return $user;
    }

    /**
     * @param LoginUserData $data
     * @return User
     * @throws AuthenticationException
     */
    public function attemptLogin(LoginUserData $data): User
    {
        return $this->loginUser->handle($data);
    }

    /**
     * @param User $user
     * @return PersonalAccessTokenResult
     */
    public function authenticate(User $user): PersonalAccessTokenResult
    {
        return $this->generateToken->handle($user);
    }

    /**
     * @return ?Authenticatable
     */
    public function getAuthenticatedUser(): ?Authenticatable
    {
        return $this->userRepository->getCurrentUser();
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if ($user = $this->getAuthenticatedUser()) {
            $this->logoutUser->handler($user);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->logoutUser->handler($user);
        $this->userRepository->deleteUser($user);
    }
}
