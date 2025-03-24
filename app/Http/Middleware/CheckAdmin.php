<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check() || !Auth::user()->hasRole('admin'))
        {
           Auth::logout();
           return redirect()->route('admin.login')->with('status', 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
