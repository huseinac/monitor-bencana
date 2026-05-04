<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExcelTemplateAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validHash = md5('machinegunkelly');

        $user = $request->getUser();
        $pass = $request->getPassword();

        if ($user !== 'aingExcel' && md5($pass) !== $validHash) {
            return response('Unauthorized', 401)
                ->header('WWW-Authenticate', 'Basic realm="API"');
        }

        return $next($request);
    }
}
