<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Activity;

Route::get('/test-activity', function () {
    $user = User::first();
    
    if (!$user) {
        return 'No users found in the database.';
    }
    
    // Log some test activities
    activity()
        ->causedBy($user)
        ->log('User performed a test action');
        
    activity()
        ->causedBy($user)
        ->withProperties(['key' => 'value'])
        ->log('User performed an action with properties');
    
    activity()
        ->causedBy($user)
        ->performedOn($user)
        ->withProperties(['old' => ['name' => 'Old Name'], 'attributes' => ['name' => 'New Name']])
        ->log('User updated their profile');
    
    return 'Test activities logged successfully!';
});
