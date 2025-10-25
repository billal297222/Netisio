<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Artisan;



class SettingController extends Controller
{
    public function index()
    {
        $data['system_settings'] = SystemSetting::first();
        return view('backend.layouts.setting.systemSetting', $data);
    }

    public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'system_title' => 'required|string|max:150',
        'system_short_title' => 'nullable|string|max:100',
        'tag_line' => 'nullable|string|max:255',
        'company_name' => 'required|string|max:150',
        'phone_code' => 'required|string|max:5',
        'phone_number' => 'required|string|max:15|regex:/^\d+$/',
        'email' => 'required|email|max:150',
        'copyright' => 'nullable|string|max:500',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'favicon' => 'nullable|image|mimes:ico,png,svg,jpeg,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    try {
        $setting = SystemSetting::firstOrNew();

        $data = $request->all();
        $data['system_title'] = Str::title($request->system_title);

        // Logo upload
if ($request->hasFile('logo')) {
    if ($setting->logo && file_exists(public_path('logo/' . $setting->logo))) {
        unlink(public_path('logo/' . $setting->logo));
    }

    $logoFile = $request->file('logo');
      // Resize image to 300x300
    // $logoFile=Image::make($logoFile)->resize(300, 300);
    $logoName = uniqid() . '.' . $logoFile->getClientOriginalExtension();


    // Resize and save using Intervention Image
    //   Image::make($logoFile)
    //     ->resize(300, 300)
    //     ->save(public_path('logo/' . $logoName));

    $data['logo'] = $logoName; // Store only filename

    $logoFile->move(public_path('logo'), $logoName);
    $data['logo'] = $logoName; // ✅ Only filename
}

// Favicon upload
if ($request->hasFile('favicon')) {
    if ($setting->favicon && file_exists(public_path('favicon/' . $setting->favicon))) {
        unlink(public_path('favicon/' . $setting->favicon));
    }

    $faviconFile = $request->file('favicon');
    $faviconName = uniqid() . '.' . $faviconFile->getClientOriginalExtension();
    $faviconFile->move(public_path('favicon'), $faviconName);
    $data['favicon'] = $faviconName; // ✅ Only filename
}


        $setting->update($data);

        return redirect()->back()->with('success', 'Settings updated successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}


    public function admin_index()
    {
        $data['system_settings'] = SystemSetting::first();
        return view('backend.layouts.setting.adminSetting', $data);
    }

 public function admin_update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'admin_title' => 'required|string|max:150',
        'admin_short_title' => 'nullable|string|max:100',
        'admin_copyright_text' => 'nullable|string|max:500',
        'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'admin_favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    try {
        $setting = SystemSetting::firstOrNew([]);

        $data = $request->only(['admin_title', 'admin_short_title', 'admin_copyright_text']);
        $data['admin_title'] = Str::title($request->admin_title);

         // Admin Logo Upload
        if ($request->hasFile('admin_logo')) {
            if (!empty($setting->admin_logo) && file_exists(public_path($setting->admin_logo))) {
                @unlink(public_path($setting->admin_logo));
            }
            $logoName = uniqid() . '.' . $request->admin_logo->getClientOriginalExtension();
            $request->admin_logo->move(public_path('admin_logo'), $logoName);
            $data['admin_logo'] = 'admin_logo/' . $logoName;
        }
        // === Admin Favicon Upload ===
        if ($request->hasFile('admin_favicon')) {
            if (!empty($setting->admin_favicon) && file_exists(public_path($setting->admin_favicon))) {
                @unlink(public_path($setting->admin_favicon));
            }

            $faviconName = uniqid() . '.' . $request->admin_favicon->getClientOriginalExtension();
            $request->admin_favicon->move(public_path('admin_favicon'), $faviconName);
            $data['admin_favicon'] = 'admin_favicon/' . $faviconName;
        }

        $setting->fill($data)->save();

        return redirect()->back()->with('success', 'Admin settings updated successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}



    public function mail()
    {
        return view('backend.layouts.setting.mail');
    }

     public function mail_store(Request $request){

        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|string',
        ]);

        // Get the current .env content
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Prepare new values
        $replacements = [
            '/MAIL_MAILER=(.*)\s/'       => 'MAIL_MAILER=' . $request->mail_mailer,
            '/MAIL_HOST=(.*)\s/'         => 'MAIL_HOST=' . $request->mail_host,
            '/MAIL_PORT=(.*)\s/'         => 'MAIL_PORT=' . $request->mail_port,
            '/MAIL_USERNAME=(.*)\s/'     => 'MAIL_USERNAME=' . $request->mail_username,
            '/MAIL_PASSWORD=(.*)\s/'     => 'MAIL_PASSWORD=' . $request->mail_password,
            '/MAIL_ENCRYPTION=(.*)\s/'   => 'MAIL_ENCRYPTION=' . $request->mail_encryption,
            '/MAIL_FROM_ADDRESS=(.*)\s/' => 'MAIL_FROM_ADDRESS=' . $request->mail_from_address,
        ];

        // Replace all values
        foreach ($replacements as $pattern => $replacement) {
            $envContent = preg_replace($pattern, $replacement . "\n", $envContent);
        }

        // Save updated .env
        File::put($envPath, $envContent);

        return redirect()->back()->with('success', 'Mail settings updated successfully.');
    }



     public function adIndex()
    {
        return view('backend.layouts.setting.activeDirectory');
    }

    public function adUpdate(Request $request)
    {
        $request->validate([
            'LDAP_CONNECTION' => 'required|string',
            'LDAP_HOST'       => 'required|string',
            'Alt_LDAP_HOST'   => 'nullable|string',
            'LDAP_USERNAME'   => 'nullable|string',
            'LDAP_PASSWORD'   => 'nullable|string',
            'LDAP_BASE_DN'    => 'nullable|string',
            'LDAP_PORT'       => 'nullable|string',
            'LDAP_SSL'        => 'nullable|string',
            'LDAP_TLS'        => 'nullable|string',
            'LDAP_TIMEOUT'    => 'nullable|string',
            'LDAP_LOGGING'    => 'nullable|string',
        ]);

        $envPath = base_path('.env');
        $envContent = File::exists($envPath) ? File::get($envPath) : '';

        $replacements = [
            'LDAP_CONNECTION' => $request->LDAP_CONNECTION,
            'LDAP_HOST'       => $request->LDAP_HOST,
            'Alt_LDAP_HOST'   => $request->Alt_LDAP_HOST,
            'LDAP_USERNAME'   => $request->LDAP_USERNAME,
            'LDAP_PASSWORD'   => $request->LDAP_PASSWORD,
            'LDAP_BASE_DN'    => $request->LDAP_BASE_DN,
            'LDAP_PORT'       => $request->LDAP_PORT,
            'LDAP_SSL'        => $request->LDAP_SSL,
            'LDAP_TLS'        => $request->LDAP_TLS,
            'LDAP_TIMEOUT'    => $request->LDAP_TIMEOUT,
            'LDAP_LOGGING'    => $request->LDAP_LOGGING,
        ];

        foreach ($replacements as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);

        // Clear config cache to apply new .env values immediately
        Artisan::call('config:clear');

        return redirect()->route('directory.setting')->with('success', 'Active Directory settings updated successfully.');
    }
}



