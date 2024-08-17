<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('themes.backend.default.home');
    }

    public function notifyReaded(Request $request)
    {
        if ( $request->notifyId ) {
            $user = auth()->user();
            $user->unreadNotifications->where('id', $request->notifyId)->markAsRead();
        }
    }
}
