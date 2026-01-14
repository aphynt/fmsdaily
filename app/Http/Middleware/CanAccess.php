<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CanAccess
{
    public function handle($request, Closure $next)
    {
        $routeName = $request->route()?->getName();

        if (!$routeName) {
            abort(403, 'Route tidak memiliki nama.');
        }

        if (!canAccess($routeName)) {
            return redirect()->route('dashboard.index')->with('info', 'Anda tidak diizinkan untuk mengakses halaman ini');
        }

        return $next($request);
    }
}
