<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings  = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.super.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name'        => 'required|string|max:255',
            'app_logo'        => 'nullable|image|max:2048',
            'meta_description'=> 'nullable|string',
            'footer_copyright'=> 'nullable|string',
        ]);

        foreach (['app_name', 'meta_description', 'footer_copyright'] as $key) {
            if (isset($validated[$key])) {
                Setting::set($key, $validated[$key]);
            }
        }

        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            
            if ($file->isValid() && isset($_FILES['app_logo']) && $_FILES['app_logo']['error'] === UPLOAD_ERR_OK) {
                $tmpPath   = $_FILES['app_logo']['tmp_name'];
                $extension = $file->getClientOriginalExtension();
                
                $storageDir = storage_path('app/public/logos');
                if (!file_exists($storageDir)) {
                    mkdir($storageDir, 0755, true);
                }

                $filename    = time() . '_' . uniqid() . '.' . $extension;
                $destination = $storageDir . DIRECTORY_SEPARATOR . $filename;
                $dbPath      = 'logos/' . $filename;

                if (move_uploaded_file($tmpPath, $destination)) {
                    Setting::set('app_logo', $dbPath);
                } else {
                    return back()->with('error', 'Gagal memindahkan file logo. Pastikan folder public dapat diakses.');
                }
            } else {
                return back()->with('error', 'File logo tidak valid atau melebihi batas ukuran (Max 2MB).');
            }
        }

        return back()->with('status', 'Pengaturan berhasil diperbarui.');
    }
}
