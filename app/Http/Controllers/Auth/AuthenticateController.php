<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\User\LoginUserData;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 *
 */
class AuthenticateController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        protected UserService $userService
    ){}


    /**
     * Log in a user
     *
     * This endpoint authenticates a user using their email and password and returns user details along with an access token.
     * @unauthenticated
     *
     * @param LoginUserData $data
     * @return JsonResponse
     * @throws AuthenticationException
     *
     * @bodyParam email string required The user's email address. Example: john@example.com
     * @bodyParam password string required The user's password. Example: secret123
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
     * @response 401 {
     *   "success": false,
     *   "status_code": 401,
     *   "message": "Invalid credentials.",
     *   "data": null,
     *   "errors": []
     * }
     */
    public function login(LoginUserData $data): JsonResponse
    {
        $user = $this->userService->attemptLogin($data);
        $token = $this->userService->authenticate($user);

        return $this->success(
            data: UserResource::make($user)->additional([
                'access_token' => $token->accessToken,
                'expires_in' => $token->expiresIn,
            ]),
        );
    }

    /**
     * Log out the authenticated user
     *
     * This endpoint logs out the currently authenticated user by invalidating their access token.
     * @authenticated
     *
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": null,
     *   "errors": null
     * }
     *
     * @response 401 {
     *   "success": false,
     *   "status_code": 401,
     *   "message": "Unauthenticated.",
     *   "data": null,
     *   "errors": []
     * }
     */
    public function logout(): JsonResponse
    {
        $this->userService->logout();
        return $this->success();
    }
}
