<?php

namespace App\Providers;

use App\Services\Schema\Contracts\EntityFormSchemaServiceInterface;
use App\Services\Schema\EntityFormSchemaService;
use App\Services\User\Contracts\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->serviceBinding();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
        Model::preventLazyLoading(!$this->app->isProduction());
    }

    /**
     * @return void
     */
    private function serviceBinding(): void
    {
        $bindings = [
            UserServiceInterface::class => UserService::class,
            EntityFormSchemaServiceInterface::class => EntityFormSchemaService::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
