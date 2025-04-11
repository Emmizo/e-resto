<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Configure Passport
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Load keys from storage
        Passport::loadKeysFrom(storage_path());

        // Configure token scopes
        Passport::tokensCan([
            'user' => 'Access user data',
            'restaurant' => 'Access restaurant data',
            'admin' => 'Access admin features'
        ]);

        // Set default scope
        Passport::setDefaultScope([
            'user'
        ]);
    }
}
