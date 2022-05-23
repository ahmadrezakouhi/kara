<?php

namespace App\Providers;

use App\Models\TaskTitle;
use App\Personnel;
use App\Policies\PersonnelPolicy;
use App\Policies\UserPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectUserPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TaskTitlePolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        Personnel::class => PersonnelPolicy::class,
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        ProjectUser::class => ProjectUserPolicy::class,
        Task::class => TaskPolicy::class,
        TaskTitle::class => TaskTitlePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // check for superuser
        Gate::define('isManager' , function($user){
            return $user->role == 'manager';
        });

         // check for user
         Gate::define('isAdmin' , function($user){
            return $user->role == 'admin';
        });

        // check for user
        Gate::define('isUser' , function($user){
            return $user->role == 'user';
        });

        // Gate::define('update-pesonnel', function ($user, $personnel) {
        //     return $user->id == $personnel->user_id;
        // });
        //
    }
}
