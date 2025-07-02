<?php

namespace App\Http\View\Composers;

use App\Models\Alert;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AlertComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $unreadAlerts = Alert::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
            
            $view->with('activeAlertsCount', $unreadAlerts);
        } else {
            $view->with('activeAlertsCount', 0);
        }
    }
}
