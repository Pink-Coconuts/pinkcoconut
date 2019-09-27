<?php

use Illuminate\Database\Seeder;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *

     * @return void
     */
    public function run()
    {
        DB::table('fees')->delete();

        DB::table('fees')->insert([
                ['id'=>1,'name' => 'service_fee', 'value' => 5],
                ['id'=>2,'name' => 'host_fee', 'value' => 0],
                ['id'=>3,'name' => 'host_penalty', 'value' => 1],
                ['id'=>4,'name' => 'currency', 'value' => 'USD'],
                ['id'=>5,'name' => 'before_seven_days', 'value' => 50],
                ['id'=>6,'name' => 'after_seven_days', 'value' => 100],
                ['id'=>7,'name' => 'cancel_limit', 'value' => 3],
                ['id'=>8,'name' => 'min_service_fee', 'value' => 1],
                ['id'=>9,'name' => 'fees_currency', 'value' => 'USD'],
                /*HostExperiencePHPCommentStart*/
                ['id'=>10,'name' => 'experience_service_fee', 'value' => 5],
                ['id'=>11,'name' => 'expr_min_service_fee', 'value' => 1],
                ['id'=>12,'name' => 'expr_fees_currency', 'value' => 'USD'],
                /*HostExperiencePHPCommentEnd*/
                ['id'=>13,'name' => 'airport_fee', 'value' => 0],
                ['id'=>14,'name' => 'airport_fee_currency', 'value' => 'USD'],
            ]);
    }
}
