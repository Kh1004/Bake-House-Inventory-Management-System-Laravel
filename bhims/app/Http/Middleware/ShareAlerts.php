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
            try {
                // Run the low stock check on every request
                \Artisan::call('inventory:check-low-stock');
                
                // Get fresh count of unread alerts for the current user
                $activeAlertsCount = Alert::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                // Get unread alerts for the current user with fresh data
                $unreadAlerts = Alert::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->take(10) // Limit to 10 most recent alerts for the dropdown
                    ->get();
                
                // Share with all views
                view()->share('alerts', $unreadAlerts);
                view()->share('activeAlertsCount', $activeAlertsCount);
                
                // Also store in session for JavaScript access if needed
                session(['activeAlertsCount' => $activeAlertsCount]);
                
                // Log for debugging
                \Log::debug('Updated alerts count', [
                    'user_id' => Auth::id(),
                    'count' => $activeAlertsCount,
                    'alerts' => $unreadAlerts->pluck('id')
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Failed to update alerts: ' . $e->getMessage());
                
                // Fallback to session or default values
                $activeAlertsCount = session('activeAlertsCount', 0);
                view()->share('alerts', collect());
                view()->share('activeAlertsCount', $activeAlertsCount);
            }
        } else {
            view()->share('alerts', collect());
            view()->share('activeAlertsCount', 0);
            session(['activeAlertsCount' => 0]);
        }

        return $next($request);
    }
}
