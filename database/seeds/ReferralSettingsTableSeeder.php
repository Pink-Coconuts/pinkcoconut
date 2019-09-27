<?php

use Illuminate\Database\Seeder;

class ReferralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('referral_settings')->delete();

        DB::table('referral_settings')->insert([
                ['name' => 'per_user_limit', 'value' => '5000'],
                ['name' => 'if_friend_guest_credit', 'value' => '20'],
                ['name' => 'if_friend_host_credit', 'value' => '80'],
                ['name' => 'new_referral_user_credit', 'value' => '20'],
                ['name' => 'currency_code', 'value' => 'USD'],
            ]);
    }
}
