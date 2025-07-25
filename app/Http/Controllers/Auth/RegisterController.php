<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\User\CreateUserData;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\User\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 *
 * APIs for managing Users Authentication
 */
class RegisterController extends Controller
{
    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(
        protected UserServiceInterface $userService,
    ){}


    /**
     * Register a new user
     *
     * This endpoint allows new users to register by providing their name, email, and password. Returns the created user resource along with an access token.
     * @unauthenticated
     *
     * @param CreateUserData $data
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
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...",
     *     "expires_in": 3600
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
    public function __invoke(CreateUserData $data): JsonResponse
    {
        $user = $this->userService->register($data);
        $token = $this->userService->authenticate($user);

        return $this->success(
            data: UserResource::make($user)->additional([
                'access_token' => $token->accessToken,
                'expires_in' => $token->expiresIn,
            ]),
        );
    }
}
