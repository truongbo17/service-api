<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CheckAuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
        if(empty(env('AUTH_TOKEN_API')) || empty(config('auth.custom_token_auth'))){
            return response([
                'status_code' => 401,
                'message' => "Not config Auth-Token key in server",
                'data' => [],
                'error' => true
            ], 401);
        }

        if(empty(env('ENDPOINT_TIKTOK_SIGNATURE')) || empty(config('tiktok.signature_service'))){
            return response([
                'status_code' => 401,
                'message' => "Not config Tiktok Signature Endpoint in server",
                'data' => [],
                'error' => true
            ], 401);
        }

        if ($request->hasHeader(config('auth.key_custom_token_auth'))) {
            if ($request->header(config('auth.key_custom_token_auth')) == config('auth.custom_token_auth')) {
                return $next($request);
            }
        }
        return response([
            'status_code' => 401,
            'message' => "Authentication Failed",
            'data' => [],
            'error' => true
        ], 401);
    }
}
