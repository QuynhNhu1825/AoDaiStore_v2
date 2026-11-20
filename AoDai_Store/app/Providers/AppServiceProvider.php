<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (parse_url(config('app.url'), PHP_URL_SCHEME) === 'https') {
            URL::forceScheme('https');
        }

        try {
            View::share(
                'categories',
                Category::where('IsDeleted', 0)
                    ->orderBy('MaDanhMuc', 'asc')
                    ->get()
            );
        } catch (\Exception $e) {
            View::share('categories', collect());
        }
    }
}
