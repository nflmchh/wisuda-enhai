<?php

namespace Database\Seeders;

use App\Models\DisplaySetting;
use Illuminate\Database\Seeder;

class DisplaySettingSeeder extends Seeder
{
    public function run(): void
    {
        DisplaySetting::updateOrCreate(
            ['id' => 1],
            [
                'name_x_position' => 50,
                'name_y_position' => 45,
                'nrp_x_position' => 50,
                'nrp_y_position' => 55,
                'name_font_size' => 72,
                'nrp_font_size' => 48,
                'font_color' => '#FFFFFF',
                'show_category' => true,
                'show_time' => true,
            ]
        );
    }
}
