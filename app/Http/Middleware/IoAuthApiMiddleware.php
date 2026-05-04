<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IoAuthApiMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $jwtService = new JwtService();
        $token = $request->bearerToken();
        if ($token == '') return response()->json(['success' => false, 'message' => 'Token is invalid.'], 401);
        $payload = $jwtService->base64UrlDecode(explode('.', $token)[1]);
        $payload = json_decode($payload, true);
        $app_key = $payload['app_key'];
        $data = $jwtService->verifyJwt($token, $app_key);
        if ($data === false) return response()->json(['success' => false, 'message' => 'Token is invalid.'], 401);
        
        $with = $request->input('with') ?? '';
        if ($with !== '') $request->merge(['with' => explode(',', $with)]);

        return $next($request);
    }
}
