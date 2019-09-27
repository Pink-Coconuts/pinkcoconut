<?php

use Illuminate\Database\Seeder;

class OurCommunityBannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('our_community_banners')->delete();
        
        DB::table('our_community_banners')->insert([
            ['title' => 'Garry & Lianne', 'description' => 'Across an ocean or across town, garry & Lianne are always in search in local experiences <br> Learn more about travel on Makent', 'image' => 'our_community_bannners_1.jpg', 'link' => 'http://makent.trioangle.com/terms_of_service'],
            ['title' => 'Makent for Business', 'description' => 'Feel at home. wherever your work takes you <br> Get your team on Makent', 'image' => 'our_community_bannners_2.jpg', 'link' => 'http://makent.trioangle.com/privacy_policy'],
            ['title' => 'Patricia', 'description' => 'A professional photographer, patricia loves helping guests explore Shanghai\'s arts scene. <br> Learn more about hosting on Makent', 'image' => 'our_community_bannners_3.jpg', 'link' => 'http://makent.trioangle.com/host_guarantee'],
        ]);
    }
}
