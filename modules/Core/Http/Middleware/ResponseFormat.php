<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * This middleware makes sure all the requests are handled in JSON format by Laravel, therefore the responses will
 * be returned in JSON format.
 */
class ResponseFormat
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
        if (config('core.full_json_response')) {
            $request->headers->set('Accept', 'application/json');
            $request->headers->set('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
