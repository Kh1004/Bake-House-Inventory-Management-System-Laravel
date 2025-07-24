<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alert;
use Symfony\Component\HttpFoundation\Response;

class ShareAlerts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Get unread alerts for the current user
            $unreadAlerts = Alert::where('user_id', Auth::id())
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Share with all views
            view()->share('alerts', $unreadAlerts);
            view()->share('activeAlertsCount', $unreadAlerts->count());
        } else {
            view()->share('alerts', collect());
            view()->share('activeAlertsCount', 0);
        }

        return $next($request);
    }
}
