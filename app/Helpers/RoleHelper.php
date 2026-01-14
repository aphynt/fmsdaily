<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('canAccess')) {
    function canAccess(string $routeName): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->roleRel) return false;

        $routes = $user->roleRel->allowed_routes ?? [];

        if (in_array('*', $routes)) {
            return true;
        }

        return in_array($routeName, $routes);
    }
}
