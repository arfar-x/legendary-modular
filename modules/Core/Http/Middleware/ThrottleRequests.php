<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as LaravelThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests extends LaravelThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param int|string $maxAttempts
     * @param float|int $decayMinutes
     * @param string $prefix
     * @return Response
     *
     * @throws ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = ''): Response
    {
        $maxAttempts = config('core.rate-limiter.attempts');
        $decayMinutes = config('core.rate-limiter.decay');

        // TODO: Improvement, unify 'Too Many Attempts' responses with customized ones.
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
