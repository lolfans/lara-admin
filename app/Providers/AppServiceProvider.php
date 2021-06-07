<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //注册service接口及其实现类
        $this->app->bind('App\Service\Contract\ConfigGroupServiceInterface', "App\Service\Logic\ConfigGroupService");
        $this->app->bind('App\Service\Contract\LoginLogServiceInterface', "App\Service\Logic\LoginLogService");
        $this->app->bind('App\Service\Contract\OperaLogServiceInterface', "App\Service\Logic\OperaLogService");
        $this->app->bind('App\Service\Contract\PermissionServiceInterface', "App\Service\Logic\PermissionService");
        $this->app->bind('App\Service\Contract\RoleServiceInterface', "App\Service\Logic\RoleService");
        $this->app->bind('App\Service\Contract\UserServiceInterface', "App\Service\Logic\UserService");
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //左侧菜单
        view()->composer('admin.layout',function($view){
            $menus = \App\Models\Permission::with(['childs'])->where('parent_id',0)->orderBy('sort','desc')->get();
            $view->with('menus',$menus);
        });
    }
}
