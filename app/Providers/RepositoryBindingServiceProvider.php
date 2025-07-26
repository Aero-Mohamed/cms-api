<?php

namespace App\Providers;

use App\Repositories\Schema\AttributeRepository;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use App\Repositories\Schema\EntityRepository;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryBindingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $bindings = [
            UserRepositoryInterface::class => UserRepository::class,
            EntityRepositoryInterface::class => EntityRepository::class,
            AttributeRepositoryInterface::class => AttributeRepository::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
