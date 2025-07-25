<?php

namespace App\Http\Controllers\Operator;

use App\Dtos\User\CreateOperatorData;
use App\Enums\SystemRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Services\User\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Operators
 */
class OperatorController extends Controller
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserServiceInterface $userService
    ){
        $this->middleware('role:'.SystemRoleEnum::ADMIN->value);
    }

    /**
     * Get all Operators
     *
     * Returns a list of users with the "operator" role.
     * @authenticated
     *
     * @return JsonResponse
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Success",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *     },
     *     {
     *       "id": 2,
     *       "name": "Jane Smith",
     *       "email": "jane@example.com",
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        $operators = $this->userRepository->getOperators();

        return $this->success(
            data: UserResource::collection($operators)
        );
    }

    /**
     * Create an Operator
     *
     * This endpoint allows admin to create new operators by providing their name, email, and password. Returns the created user resource.
     * @authenticated
     *
     * @param CreateOperatorData $data
     * @return JsonResponse
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Must be unique. Example: john@example.com
     * @bodyParam password string required The password for the user. Must be at least 8 characters and confirmed. Example: secret123
     * @bodyParam password_confirmation string required Must match the password. Example: secret123
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z",
     *   },
     *   "errors": null
     * }
     *
     * @response 422 {
     *   "success": false,
     *   "status_code": 422,
     *   "message": "The given data was invalid.",
     *   "data": null,
     *   "errors": {
     *     "email": [
     *       "The email has already been taken."
     *     ]
     *   }
     * }
     */
    public function store(CreateOperatorData $data): JsonResponse
    {
        $user = $this->userService->create($data, SystemRoleEnum::OPERATOR);

        return $this->success(
            data: UserResource::make($user)
        );
    }

    /**
     * Show operator
     *
     * This endpoint allows admin to get a specific operator by id.
     * @authenticated
     *
     * @param User $operator
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z",
     *   },
     *   "errors": null
     * }
     */
    public function show(User $operator): JsonResponse
    {
        return $this->success(
            data: UserResource::make($operator)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Delete operator
     *
     * This endpoint allows admin to delete a specific operator by id.
     * @authenticated
     *
     * @param User $operator
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z",
     *   },
     *   "errors": null
     * }
     */
    public function destroy(User $operator): JsonResponse
    {
        $this->userService->delete($operator);
        return $this->success();
    }
}
