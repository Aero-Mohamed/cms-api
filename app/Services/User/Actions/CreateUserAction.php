<?php

namespace App\Services\User\Actions;

use App\Dtos\User\CreateUserData;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;

class CreateUserAction
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param CreateUserData $data
     * @return User
     */
    public function handle(CreateUserData $data): User
    {
        return $this->userRepository->create($data);
    }
}
