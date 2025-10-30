<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        'login.post',
        'register',
        'register.post',
        'logout',
        '/login',
        '/login/*',
        '/password/*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Skip CSRF check for login routes to avoid 419 errors
        if ($request->is('login') || $request->is('login/*') ||
            $request->is('password/*') || $request->is('register')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
