<?php

namespace Corp\Providers;

use Corp\Article;
use Corp\Menu;
use Corp\Permissions;
use Corp\Policies\ArticlePolicy;
use Corp\Policies\MenusPolicy;
use Corp\Policies\PermissionPolicy;
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
        Article::class =>ArticlePolicy::class,
        Permissions::class =>PermissionPolicy::class,
        Menu::class => MenusPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerViewPolicies();


    }

    public function registerViewPolicies()
    {
        Gate::define('VIEW_ADMIN', function ($user) {
            return $user->canDo(['VIEW_ADMIN'], true);
        });
        Gate::define('ADD_ARTICLES', function ($user) {
        return $user->canDo(['ADD_ARTICLES'], true);
        });
        Gate::define('VIEW_ARTICLES', function ($user) {
        return $user->canDo(['VIEW_ARTICLES'], true);
        });
        Gate::define('UPDATE_ARTICLES', function ($user) {
            return $user->canDo(['UPDATE_ARTICLES'], true);
        });
        Gate::define('DELETE_ARTICLES', function ($user) {
            return $user->canDo(['DELETE_ARTICLES'], true);
        });
//        or $user->id == $article->user_id
        Gate::define('ADMIN_USERS', function ($user) {
            return $user->canDo(['ADMIN_USERS'], true);
        });
        Gate::define('VIEW_ADMIN_ARTICLES', function ($user) {
            return $user->canDo(['VIEW_ADMIN_ARTICLES'], true);
        });
        Gate::define('DELETE_USERS', function ($user) {
            return $user->hasRole(['Admin'], true);
        });
        Gate::define('EDIT_USERS', function ($user) {
            return $user->hasRole(['Admin'], true);
        });
    }
}
