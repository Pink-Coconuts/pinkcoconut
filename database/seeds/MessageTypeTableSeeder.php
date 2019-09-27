<?php

use Illuminate\Database\Seeder;

class MessageTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('message_type')->delete();

        DB::table('message_type')->insert([
                ['id' => 1, 'name' => 'Reservation Request'],
                ['id' => 2, 'name' => 'Reservation Accept'],
                ['id' => 3, 'name' => 'Reservation Decline'],
                ['id' => 4, 'name' => 'Reservation Expire'],
                ['id' => 5, 'name' => 'Reservation Discuss'],
                ['id' => 6, 'name' => 'Pre-Approval'],
                ['id' => 7, 'name' => 'Special Offer'],
                ['id' => 8, 'name' => 'Unavailable'],
                ['id' => 9, 'name' => 'Contact Request'],
                ['id' => 10,'name'=>  'Cancel Reservation by Guest'],
                ['id' => 11,'name'=>  'Cancel Reservation by Host'],
                ['id' => 12,'name'=>  'Pre-Accept'],
                ['id' => 13,'name'=>  'Resubmit_id_document']
            ]);
    }
}
