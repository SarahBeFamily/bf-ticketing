<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = SystemSetting::all();
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // foreach ($request->all() as $key => $value) {
        //     SystemSetting::set($key, $value);
        // }
        if ($request->has('site_name')) {
            SystemSetting::set('site_name', $request->site_name);
        }

        if ($request->has('division')) {
            SystemSetting::storeFromTextarea('division', $request->division);
        }

        if ($request->has('project_status')) {
            SystemSetting::storeFromTextarea('project_status', $request->project_status);
        }

        if ($request->has('ticket_type')) {
            SystemSetting::storeFromTextarea('ticket_type', $request->ticket_type);
        }

        return redirect()->route('settings.index')->with('success', 'Impostazioni aggiornate con successo.');
    }
}
