<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->get('api') != config('app.api_key')) {
            return response()->setStatusCode(403)->json('Invalid API key');
        }

        return $next($request);
    }
}
