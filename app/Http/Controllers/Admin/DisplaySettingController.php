<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisplaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisplaySettingController extends Controller
{
    public function index()
    {
        $setting = DisplaySetting::global();
        return view('admin.display-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name_x_position' => 'required|integer|min:0|max:100',
            'name_y_position' => 'required|integer|min:0|max:100',
            'nrp_x_position' => 'required|integer|min:0|max:100',
            'nrp_y_position' => 'required|integer|min:0|max:100',
            'name_font_size' => 'required|integer|min:12|max:200',
            'nrp_font_size' => 'required|integer|min:12|max:200',
            'font_color' => 'required|string|max:20',
            'show_category' => 'nullable|boolean',
            'show_time' => 'nullable|boolean',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data['show_category'] = $request->boolean('show_category');
        $data['show_time'] = $request->boolean('show_time');

        $setting = DisplaySetting::global();

        if ($request->hasFile('background_image')) {
            if ($setting->background_image) {
                Storage::disk('public')->delete($setting->background_image);
            }
            $data['background_image'] = $request->file('background_image')
                ->store('display-backgrounds', 'public');
        }

        $setting->update($data);

        return back()->with('success', 'Pengaturan display berhasil disimpan dan berlaku untuk semua gate.');
    }
}
