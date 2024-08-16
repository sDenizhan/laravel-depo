<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class EmailSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = Setting::getGroup('email');
        return view('settings.email-settings.index', compact('settings'));
    }

    public function store(StoreEmailSettingRequest $request)
    {
        $validated = $request->validated();
        Setting::setGroup('email', $validated);
        return redirect()->route('admin.email-settings.index')->with('success', 'Email settings updated successfully');
    }
}
