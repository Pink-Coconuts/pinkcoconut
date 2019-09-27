<?php

use Illuminate\Database\Seeder;

class DateformatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dateformats')->delete();
        
        DB::table('dateformats')->insert([
            ['id' => '1','display_format' => 'M d','display_format1' => 'Jan 1','php_format' => 'M d','uidatepicker_format' => 'M d','daterangepicker_format' => 'MMM DD','status' => 'Inactive'],
          ['id' => '2','display_format' => 'DD/MM/YYYY','display_format1' => '01/01/2000','php_format' => 'd/m/Y','uidatepicker_format' => 'dd/mm/yy','daterangepicker_format' => 'DD/MM/YYYY','status' => 'Active'],
          ['id' => '3','display_format' => 'MM/DD/YYYY','display_format1' => '01/01/2000','php_format' => 'm/d/Y','uidatepicker_format' => 'mm/dd/yy','daterangepicker_format' => 'MM/DD/YYYY','status' => 'Active'],
          ['id' => '4','display_format' => 'YYYY/MM/DD','display_format1' => '2000/01/01','php_format' => 'Y/m/d','uidatepicker_format' => 'yy/mm/dd','daterangepicker_format' => 'YYYY/MM/DD','status' => 'Active'],
          ['id' => '5','display_format' => 'DD-MM-YYYY','display_format1' => '01-01-2000','php_format' => 'd-m-Y','uidatepicker_format' => 'dd-mm-yy','daterangepicker_format' => 'DD-MM-YYYY','status' => 'Active'],
          ['id' => '6','display_format' => 'MM-DD-YYYY','display_format1' => '01-01-2000','php_format' => 'm-d-Y','uidatepicker_format' => 'mm-dd-yy','daterangepicker_format' => 'MM-DD-YYYY','status' => 'Active'],
          ['id' => '7','display_format' => 'YYYY-MM-DD','display_format1' => '2000-01-01','php_format' => 'Y-m-d','uidatepicker_format' => 'yy-mm-dd','daterangepicker_format' => 'YYYY-MM-DD','status' => 'Active'],
          ['id' => '8','display_format' => 'M d Y','display_format1' => 'Jan 1 2000','php_format' => 'M d Y','uidatepicker_format' => 'M d yy','daterangepicker_format' => 'MMM DD YYYY','status' => 'Inactive'],
        ]);
    }
}
