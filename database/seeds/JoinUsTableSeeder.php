<?php

use Illuminate\Database\Seeder;

class JoinUsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('join_us')->delete();

        DB::table('join_us')->insert([
                ['name' => 'facebook', 'value' => 'https://www.facebook.com/Trioangle.Technologies/'],
                ['name' => 'google_plus', 'value' => 'https://plus.google.com/u/2/101787514526246577959'],
                ['name' => 'twitter', 'value' => 'https://twitter.com/TrioangleTech'],
                ['name' => 'linkedin', 'value' => 'https://www.linkedin.com/company/13184720'],
                ['name' => 'pinterest', 'value' => 'https://in.pinterest.com/TrioangleTech/'],
                ['name' => 'youtube', 'value' => 'https://www.youtube.com/channel/UC2EWcEd5dpvGmBh-H4TQ0wg'],
                ['name' => 'instagram', 'value' => 'https://www.instagram.com/trioangletech'],
                ['name' => 'play_store', 'value' => 'https://play.google.com/store/apps/details?id=com.makent.trioangle'],
                ['name' => 'app_store', 'value' => 'https://itunes.apple.com/in/app/makent/id1203256175?mt=8'],
            ]);
    }
}
