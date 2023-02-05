<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 使用 bootstrap 的分页样式
        Paginator::useBootstrap();

        View::share('menu', $this->getMenuItems());
    }

    /**
     * 获取菜单
     *
     * @return Collection
     */
    private function getMenuItems(): Collection
    {
        return Cache::remember('view-share:menu', now()->addMinutes(30), function () {
            return Menu::query()
                ->with('children')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
        });
    }
}
