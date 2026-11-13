<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => Setting::getValue('store_name', 'Asyart'),
            'contact_email' => Setting::getValue('contact_email', ''),
            'phone' => Setting::getValue('phone', ''),
            'instagram' => Setting::getValue('instagram', ''),
            'address' => Setting::getValue('address', ''),

            'standard_shipping_rate' => Setting::getValue('standard_shipping_rate', 15000),
            'standard_shipping_rate' => Setting::getValue('standard_shipping_rate', 0),
            'standard_shipping_active' => Setting::getValue('standard_shipping_active', '0'),

            'express_shipping_rate' => Setting::getValue('express_shipping_rate', 35000),
            'express_shipping_active' => Setting::getValue('express_shipping_active', '1'),
            'free_shipping_minimum' => Setting::getValue('free_shipping_minimum', 100000),
            'free_shipping_minimum' => Setting::getValue('free_shipping_minimum', 200000),
            'free_shipping_active' => Setting::getValue('free_shipping_active', '1'),

            'qris_expiry' => Setting::getValue('qris_expiry', 15),
            'payment_notification_email' => Setting::getValue('payment_notification_email', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],

            'standard_shipping_rate' => ['required', 'numeric', 'min:0'],
            'express_shipping_rate' => ['required', 'numeric', 'min:0'],
            'standard_shipping_active' => ['nullable', 'boolean'],
            'express_shipping_active' => ['nullable', 'boolean'],

            'free_shipping_minimum' => ['required', 'numeric', 'min:0'],
            'free_shipping_active' => ['nullable', 'boolean'],

            'qris_expiry' => ['required', 'in:15,30,60'],
            'payment_notification_email' => ['nullable', 'email', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        // Simpan toggle yang tidak dicentang sebagai 0
        foreach (
            [
                'standard_shipping_active',
                'express_shipping_active',
                'free_shipping_active',
            ] as $toggle
        ) {
            Setting::setValue($toggle, $request->boolean($toggle) ? '1' : '0');
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings berhasil disimpan.');
    }
}
