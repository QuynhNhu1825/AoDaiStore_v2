<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // Kiểm tra đăng nhập
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Kiểm tra quyền Admin
        if (auth()->user()->Role !== 'Admin') {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}