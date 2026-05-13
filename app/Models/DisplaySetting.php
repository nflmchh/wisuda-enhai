<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplaySetting extends Model
{
    protected $fillable = [
        'background_image',
        'name_x_position', 'name_y_position',
        'nrp_x_position', 'nrp_y_position',
        'name_font_size', 'nrp_font_size',
        'font_color', 'show_category', 'show_time',
    ];

    protected function casts(): array
    {
        return [
            'show_category' => 'boolean',
            'show_time' => 'boolean',
        ];
    }

    public static function global(): self
    {
        return static::firstOrCreate(
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
