<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define("manage-users", function($user){
            // TODO : logika untuk mengizinkan manage users
            return in_array("ADMIN", json_decode($user->roles));
        });

        Gate::define("manage-categories", function($user){
            // TODO : logika untuk mengizinkan manage categories
            return count(array_intersect("ADMIN", "STAFF"), json_decode($user->roles));
        });

        Gate::define("manage-books", function($user){
            // TODO : logika untuk mengizinkan manage books
            return count(array_intersect("ADMIN", "STAFF"), json_decode($user->roles));
        });
        
        Gate::define("manage-orders", function($user){
            // TODO : logika untuk mengizinkan manage orders
            return count(array_intersect("ADMIN", "STAFF"), json_decode($user->roles));
        });
    }
}
