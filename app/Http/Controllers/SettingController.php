<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'hourly_rate' => Setting::get('hourly_rate', 50),
            'tax_rate' => Setting::get('tax_rate', 0),
            'company_name' => Setting::get('company_name', config('app.name')),
            'company_email' => Setting::get('company_email', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_address' => Setting::get('company_address', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',
        ]);

        Setting::set('hourly_rate', $validated['hourly_rate'], 'number');
        Setting::set('tax_rate', $validated['tax_rate'], 'number');
        Setting::set('company_name', $validated['company_name']);
        Setting::set('company_email', $validated['company_email'] ?? '');
        Setting::set('company_phone', $validated['company_phone'] ?? '');
        Setting::set('company_address', $validated['company_address'] ?? '');

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
