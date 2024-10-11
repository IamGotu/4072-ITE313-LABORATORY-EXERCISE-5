<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view)
    {
        // Fetch notifications for the authenticated user
        $notifications = Auth::check() ? Auth::user()->notifications : [];

        // Pass the notifications variable to the view
        $view->with('notifications', $notifications);
    }
}