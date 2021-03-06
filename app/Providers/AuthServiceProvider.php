<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Permission;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Post' => 'App\Policies\PostPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // $gate->define('who-see', function(User $user, Post $post){
        //     return $user->id == $post->user_id;
        // });

        $permissions = Permission::with('roles')->get();

        foreach($permissions as $permission) {
            $gate->define($permission->name, function(User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

    }
}
