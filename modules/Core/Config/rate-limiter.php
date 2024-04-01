<?php

return [
    /**
     * Determine the rate-limiter should be applied on client's IP to reject any access; otherwise, rate-limiter
     * will perform on client's token.
     */
    'reject-ip' => env('RATE_LIMITER_REJECT_IP', false),

    'attempts' => env('RATE_LIMITER_ATTEMPTS', 100),
    'decay' => env('RATE_LIMITER_DECAY_MINUTES', 60),

    /**
     * Determine whether the rate-limiter should be applied per endpoints or the whole system.
     */
    'per-endpoint' => env('RATE_LIMITER_PER_ENDPOINT', false)
];
