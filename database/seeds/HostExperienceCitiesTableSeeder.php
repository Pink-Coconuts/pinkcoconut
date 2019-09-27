<?php

use Illuminate\Database\Seeder;

class HostExperienceCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('host_experience_cities')->delete();
    	
        DB::table('host_experience_cities')->insert([
            ['name' => 'Amsterdam',               'timezone' => 64,     'currency_code' => 'EUR'],
            ['name' => 'Bangkok',                 'timezone' => 164,     'currency_code' => 'THB'],
            ['name' => 'Barcelona',               'timezone' => 64,     'currency_code' => 'EUR'],
            ['name' => 'Berlin',                  'timezone' => 66,     'currency_code' => 'EUR'],
            ['name' => 'Big Bear Lakes',          'timezone' => 8,     'currency_code' => 'CAD'],
            ['name' => 'Buenos Aires',            'timezone' => 38,     'currency_code' => 'ARS'],
            ['name' => 'Cape Town',               'timezone' => 116,     'currency_code' => 'ZAR'],
            ['name' => 'Chicago',                 'timezone' => 16,     'currency_code' => 'USD'],
            ['name' => 'Costa Rica',              'timezone' => 18,     'currency_code' => 'USD'],
            ['name' => 'Delhi',                   'timezone' => 155,     'currency_code' => 'INR'],
            ['name' => 'Detroit',                 'timezone' => 29,     'currency_code' => 'USD'],
            ['name' => 'Dublin',                  'timezone' => 55,     'currency_code' => 'EUR'],
            ['name' => 'Florence',                'timezone' => 86,     'currency_code' => 'EUR'],
            ['name' => 'Hawaii - Kauai',          'timezone' => 4,     'currency_code' => 'USD'],
            ['name' => 'Hawaii - Maui',           'timezone' => 4,     'currency_code' => 'USD'],
            ['name' => 'Hawaii - Oahu',           'timezone' => 4,     'currency_code' => 'USD'],
            ['name' => 'Hawaii - The Big Island', 'timezone' => 4,     'currency_code' => 'USD'],
            ['name' => 'Ho Chi Minh',             'timezone' => 165,     'currency_code' => 'VND'],
            ['name' => 'Hong Kong',               'timezone' => 171,     'currency_code' => 'CNY'],
            ['name' => 'Honolulu',                'timezone' => 5,     'currency_code' => 'USD'],
            ['name' => 'Lisbon',                  'timezone' => 58,     'currency_code' => 'EUR'],
            ['name' => 'London',                  'timezone' => 59,     'currency_code' => 'GBP'],
            ['name' => 'Los Angeles',             'timezone' => 7,     'currency_code' => 'USD'],
            ['name' => 'Madrid',                  'timezone' => 80,     'currency_code' => 'EUR'],
            ['name' => 'Mammoth Lakes',           'timezone' => 8,     'currency_code' => 'USD'],
            ['name' => 'Melbourne',               'timezone' => 197,     'currency_code' => 'AUD'],
            ['name' => 'Mexico City',             'timezone' => 17,     'currency_code' => 'USD'],
            ['name' => 'Miami',                   'timezone' => 24,     'currency_code' => 'USD'],
            ['name' => 'Milan',                   'timezone' => 80,     'currency_code' => 'EUR'],
            ['name' => 'Moscow',                  'timezone' => 128,     'currency_code' => 'RUB'],
            ['name' => 'New York',                'timezone' => 25,     'currency_code' => 'USD'],
            ['name' => 'Osaka',                   'timezone' => 188,     'currency_code' => 'JPY'],
            ['name' => 'Paris',                   'timezone' => 83,     'currency_code' => 'EUR'],
            ['name' => 'Prague',                  'timezone' => 85,     'currency_code' => 'CZK'],
            ['name' => 'Queenstown',              'timezone' => 210,     'currency_code' => 'NZD'],
            ['name' => 'Rome',                    'timezone' => 86,     'currency_code' => 'EUR'],
            ['name' => 'San Diego',               'timezone' => 7,     'currency_code' => 'USD'],
            ['name' => 'San Francisco',           'timezone' => 8,     'currency_code' => 'USD'],
            ['name' => 'San Luis Obispo',         'timezone' => 8,     'currency_code' => 'USD'],
            ['name' => 'Santa Barbara',           'timezone' => 8,     'currency_code' => 'USD'],
            ['name' => 'Seattle',                 'timezone' => 8,     'currency_code' => 'USD'],
            ['name' => 'Seoul',                   'timezone' => 186,     'currency_code' => 'KRW'],
            ['name' => 'Seville',                 'timezone' => 77,     'currency_code' => 'EUR'],
            ['name' => 'Shanghai',                'timezone' => 175,     'currency_code' => 'CNY'],
            ['name' => 'Singapore',               'timezone' => 183,     'currency_code' => 'SGD'],
            ['name' => 'Sydney',                  'timezone' => 198,     'currency_code' => 'AUD'],
            ['name' => 'Tokyo',                   'timezone' => 187,     'currency_code' => 'JPY'],
            ['name' => 'Toronto',                 'timezone' => 27,     'currency_code' => 'USD'],
            ['name' => 'Vancouver',               'timezone' => 8,     'currency_code' => 'CAD'],
        ]);
    }
}
