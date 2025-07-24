<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        // Mark alerts as read when viewing the page
        Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Update the active alerts count in the session
        $activeAlertsCount = Alert::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
            
        session(['activeAlertsCount' => $activeAlertsCount]);
            
        return view('alerts.index', compact('alerts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $alert = Alert::where('user_id', Auth::id())->findOrFail($id);
        $alert->delete();

        $remainingAlerts = Alert::where('user_id', Auth::id())->where('is_read', false)->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'remainingAlerts' => $remainingAlerts,
                'message' => 'Alert deleted successfully!'
            ]);
        }

        return redirect()->back()
            ->with('status', 'Alert deleted successfully!');
    }
}
