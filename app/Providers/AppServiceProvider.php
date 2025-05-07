<?php

namespace App\Providers;

use App\Repositories\Auth\UserRepository;
use App\Repositories\Auth\UserRepositoryInterface;
use App\Repositories\RefreshToken\RefreshTokenRepository;
use App\Repositories\RefreshToken\RefreshTokenRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\RefreshToken\RefreshTokenService;
use App\Services\RefreshToken\RefreshTokenServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // repositories
        $this->app->bind(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // services
        $this->app->bind(AuthServiceInterface::class,AuthService::class);
        $this->app->bind(RefreshTokenServiceInterface::class,RefreshTokenService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
