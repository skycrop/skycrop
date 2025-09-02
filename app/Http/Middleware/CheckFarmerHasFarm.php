<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFarmerHasFarm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $farmer = Auth::guard('farmer')->user();

        // If user is not logged in or not a farmer, allow the request
        if (!$farmer) {
            return $next($request);
        }

        // Check if farmer has any farms
        if ($farmer->farms()->count() === 0) {
            // Exclude the farm creation route itself to avoid redirect loop
            if (!$request->routeIs('user.farms.create') && !$request->routeIs('user.farms.store')) {
                return redirect()->route('user.farms.create')
                    ->with('error', 'Please add your farm details before proceeding.');
            }
        }

        return $next($request);
    }
}
