<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
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
        $prescriptions = Prescription::query()
                        ->when(!auth()->user()->hasRole('Super Admin'), function ($query) {
                            return $query->where(['user_id' => auth()->id()]);
                        })
                        ->when(auth()->user()->hasRole('Super Admin'), function ($query){
                            return $query;
                        })->limit(10)->get();

        //logs
        $logs = \App\Models\RepoLog::orderBy('id', 'desc')->limit(10)->get();


        return view('themes.backend.default.home', compact('prescriptions', 'logs'));
    }

    public function notifyReaded(Request $request)
    {
        if ( $request->notifyId ) {
            $user = auth()->user();
            $user->unreadNotifications->where('id', $request->notifyId)->markAsRead();
        }
    }
}
