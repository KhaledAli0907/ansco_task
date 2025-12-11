<?php

namespace App\Providers;

use App\Services\Implementations\AuthService;
use App\Services\Interfaces\AuthInterface;
use App\Services\Interfaces\UserInterface;
use App\Services\Interfaces\SubscribtionInterface;
use App\Services\Implementations\UserService;
use App\Services\Interfaces\PaymentGatewayInterface;
use App\Services\Implementations\SubscribtionService;
use App\Services\Implementations\Payments\PaymobPaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthService::class);
        $this->app->bind(UserInterface::class, UserService::class);
        $this->app->bind(SubscribtionInterface::class, SubscribtionService::class);
        $this->app->bind(PaymentGatewayInterface::class, PaymobPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
