<?php

namespace App\Http\View\Composers;

use App\Models\Alert;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AlertComposer
{
    public function compose(View $view)
    {
        try {
            if (Auth::check()) {
                // Get unread alerts for the current user
                $unreadAlerts = Alert::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with('activeAlertsCount', $unreadAlerts);
                
                // For debugging - log the active alerts count
                \Log::info('Active alerts for user ' . Auth::id() . ': ' . $unreadAlerts);
            } else {
                $view->with('activeAlertsCount', 0);
            }
        } catch (\Exception $e) {
            // Log any errors
            \Log::error('Error in AlertComposer: ' . $e->getMessage());
            $view->with('activeAlertsCount', 0);
        }
    }
}
