<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings', ['settings' => Setting::allSettings()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:120',
            'tagline' => 'nullable|string|max:160',
            'head_office' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:160',
            'phone' => 'nullable|string|max:60',
            'address' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:200',
            'hero_text' => 'nullable|string|max:500',
            'about_title' => 'nullable|string|max:200',
            'about_text' => 'nullable|string|max:2000',
            'footer_text' => 'nullable|string|max:500',
            'service1_title' => 'nullable|string|max:120',
            'service1_text' => 'nullable|string|max:500',
            'service2_title' => 'nullable|string|max:120',
            'service2_text' => 'nullable|string|max:500',
            'service3_title' => 'nullable|string|max:120',
            'service3_text' => 'nullable|string|max:500',
            'stat1_num' => 'nullable|string|max:20',
            'stat1_label' => 'nullable|string|max:80',
            'stat2_num' => 'nullable|string|max:20',
            'stat2_label' => 'nullable|string|max:80',
            'stat3_num' => 'nullable|string|max:20',
            'stat3_label' => 'nullable|string|max:80',
            'authorized_signature_name' => 'nullable|string|max:160',
            'status_options' => 'nullable|string|max:2000',
            'charge_options' => 'nullable|string|max:2000',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:'.implode(',', Setting::PAYMENT_METHODS),
            'logo' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
            'about_image' => 'nullable|image|max:4096',
        ]);

        $textKeys = [
            'company_name', 'tagline', 'head_office', 'email', 'phone', 'address',
            'hero_title', 'hero_text', 'about_title', 'about_text', 'footer_text',
            'service1_title', 'service1_text', 'service2_title', 'service2_text',
            'service3_title', 'service3_text',
            'stat1_num', 'stat1_label', 'stat2_num', 'stat2_label', 'stat3_num', 'stat3_label',
            'authorized_signature_name',
            'status_options', 'charge_options',
        ];
        foreach ($textKeys as $key) {
            Setting::put($key, $data[$key] ?? '');
        }

        Setting::put('payment_methods', implode(',', $data['payment_methods'] ?? []));

        foreach (['logo', 'signature', 'hero_image', 'about_image'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $name = $fileKey.'_'.time().'.'.$request->file($fileKey)->getClientOriginalExtension();
                $request->file($fileKey)->move(public_path('uploads'), $name);
                Setting::put($fileKey, 'uploads/'.$name);
            }
        }

        return back()->with('success', 'Settings saved.');
    }

    public function updateAccount(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160|unique:users,email,'.Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'Admin account updated.');
    }
}
