<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(config('logging.requests')) {
            Log::debug('Request', [
                'status code' => http_response_code(),
                'http method' => $request->method(),
                'url' => $request->fullUrl(),
                'params' => $request->all(),
                'user ID' => auth()->check() ? $request->user()->id : 'guest',
            ]);
        }

        return $response;
    }
}
