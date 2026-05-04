<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IoMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $menuService = new MenuService();
        $current_route = $request->route()->getName();
        if ($request->method() === 'GET') {
            $menus = $menuService->list_menu($user->akses ?? 'Super Admin');
            view()->share('menus', $menus);

            $current_route_params = $request->query();
            view()->share($menuService::current_menu($menus, $current_route, env('APP_NAME'), $current_route_params));
        }

        return $next($request);
    }
}
