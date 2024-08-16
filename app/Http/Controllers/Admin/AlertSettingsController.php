<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlertSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class AlertSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = Setting::getGroup('alert');
        return view('settings.alert-settings.index', compact('settings'));
    }

    public function store(StoreAlertSettingRequest $request)
    {
        $validated = $request->validated();
        Setting::clearCache();

        Setting::setGroup('alert', $validated);
        return redirect()->route('admin.alert-settings.index')->with('success', 'Alert settings updated successfully');
    }
}
