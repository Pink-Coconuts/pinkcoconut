<?php

use Illuminate\Database\Seeder;

class ThemeSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('theme_settings')->delete();

        DB::table('theme_settings')->insert([
                ['name' => 'body_bg_color', 'value' => '#f5f5f5'],
                ['name' => 'body_font_color', 'value' => '#565a5c'],
                ['name' => 'body_font_size', 'value' => '14px'],
                ['name' => 'header_bg_color', 'value' => '#fff'],
                ['name' => 'footer_bg_color', 'value' => '#2b2d2e'],
                ['name' => 'href_color', 'value' => '#ff5a5f'],
                ['name' => 'primary_btn_color', 'value' => '#ff5a5f'],
            ]);
    }
}
