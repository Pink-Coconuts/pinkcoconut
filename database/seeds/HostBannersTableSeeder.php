<?php

use Illuminate\Database\Seeder;

class HostBannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('host_banners')->delete();
        
        DB::table('host_banners')->insert([
            ['title' => 'Hosting opens up a world of opportunity ', 'description' => 'Earn money sharing your extra space with travelers. ', 'image' => 'host_banners_1.jpg', 'link_title' => 'See What You Can Earn', 'link' => '/rooms/new'],
            ['title' => 'Earn money as an Makent host', 'description' => 'From saving for home repairs to taking a dream trip, hosts use extra income to fund their passions.', 'image' => 'host_banners_2.jpg', 'link_title' => 'Why Host', 'link' => '/why_host'],
            ['title' => 'We’re here to help', 'description' => 'Makent provides tips and help along the way. From getting your home ready to choosing a price—we’ve got tools for you.', 'image' => 'host_banners_3.jpg', 'link_title' => 'Help', 'link' => '/help'],
        ]);
    }
}
