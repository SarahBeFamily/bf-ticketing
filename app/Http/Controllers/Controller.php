<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Aggiungo le notifiche non lette a tutte le view
        $this->middleware(function ($request, $next) {
            $unread_nots = Notification::where('user_id', auth()->id())->whereNull('read_at')->get();

            view()->share('unread_nots', $unread_nots);

            return $next($request);
        });
    }
}
