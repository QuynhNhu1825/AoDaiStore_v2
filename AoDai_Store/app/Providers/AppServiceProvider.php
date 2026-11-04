<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\InfoWeb;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            view()->share('categories', \App\Models\Category::where('TrangThai', 1)->get());
        } catch (\Exception $e) {
            // Khi chưa kết nối DB thì bỏ qua
            view()->share('categories', collect());
        }
    }

}
