<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $auth_key = config('auth.custom_token_auth', 'nEtmArxiFu6iyw6oZdf89sEIVJfgcRiZ');
        if($auth_key == "") $auth_key = 'nEtmArxiFu6iyw6oZdf89sEIVJfgcRiZ';

        if ($request->hasHeader(config('auth.key_custom_token_auth'))) {
            if ($request->header(config('auth.key_custom_token_auth')) == $auth_key) {
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
