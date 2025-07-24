<?php

namespace App\Http\Handlers;

use App\Http\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueOAuthServerException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class CommonApiExceptionHandler
{
    use ApiResponse;

    public function handler(Exceptions $exceptions): void
    {

        // Handle OAuthServerException (e.g., when Invalid Token fails)
        $exceptions->renderable(function (InvalidAuthTokenException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle OAuthServerException (e.g., when OAuth authentication fails)
        $exceptions->renderable(function (OAuthServerException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle LeagueOAuthServerException (e.g., when OAuth authentication fails)
        $exceptions->renderable(function (LeagueOAuthServerException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle UnauthorizedHttpException (e.g., when a user is not authorized)
        $exceptions->renderable(function (UnauthorizedHttpException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle AuthenticationException (e.g., when user credentials are invalid)
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle ModelNotFoundException (e.g., when a model is not found)
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        });

        // Handle AuthenticationException (e.g., when a user is not authenticated)
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNAUTHORIZED);
        });

        // Handle AuthorizationException (e.g., when a user lacks the required permissions)
        $exceptions->renderable(function (AuthorizationException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_FORBIDDEN);
        });

        // Handle ValidationException (e.g., when form validation fails)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
        });

        // Handle NotFoundHttpException (e.g., when a route is not found)
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return $this->error($e->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        });

        // Handle generic HttpException
        $exceptions->renderable(function (HttpException $e, Request $request) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        });

        // Catch-all
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if (app()->isProduction()) {
                return $this->error(
                    message: 'Internal Server Error',
                    statusCode: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
                );
            }

            throw $e;
        });
    }
}
