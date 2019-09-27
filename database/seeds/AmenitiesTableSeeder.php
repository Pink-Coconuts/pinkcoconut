<?php

use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amenities')->delete();
        
        DB::table('amenities')->insert([
               ['type_id' => '1', 'name' => 'Essentials','description' => 'Essentials','icon' => 'essentials.png', 'mobile_icon' => 'j'],
                ['type_id' => '1', 'name' => 'TV','description' => '','icon' => 'tv.png', 'mobile_icon' => 'z'],
                ['type_id' => '1', 'name' => 'Cable TV','description' => '','icon' => 'cabletv.png', 'mobile_icon' => 'f'],
                ['type_id' => '1', 'name' => 'Air Conditioning ','description' => '','icon' => 'ac.png', 'mobile_icon' => 'b'],
                ['type_id' => '1', 'name' => 'Heating','description' => 'Heating','icon' => 'heating1.png', 'mobile_icon' => 'o'],
                ['type_id' => '1', 'name' => 'Kitchen','description' => 'Kitchen','icon' => 'kitchen.png', 'mobile_icon' => 's'],
                ['type_id' => '1', 'name' => 'Internet','description' => 'Internet','icon' => 'internetwired.png', 'mobile_icon' => 'r'],
                ['type_id' => '1', 'name' => 'Wireless Internet','description' => 'Wireless Internet','icon' => 'wireless.jpeg', 'mobile_icon' => 'B'],
                ['type_id' => '2', 'name' => 'Hot Tub','description' => '','icon' => 'hottub.png', 'mobile_icon' => 'p'],
                ['type_id' => '2', 'name' => 'Washer','description' => 'Washer','icon' => 'washer.png', 'mobile_icon' => 'A'],
                ['type_id' => '2', 'name' => 'Pool','description' => 'Pool','icon' => 'pool.png', 'mobile_icon' => 'w'],
                ['type_id' => '2', 'name' => 'Dryer','description' => 'Dryer','icon' => 'dryerorg.png', 'mobile_icon' => 'n'],
                ['type_id' => '2', 'name' => 'Breakfast','description' => 'Breakfast','icon' => 'breakfast1.png', 'mobile_icon' => 'e'],
                ['type_id' => '2', 'name' => 'Free Parking on Premises','description' => '','icon' => 'parking.png', 'mobile_icon' => 'u'],
                ['type_id' => '2', 'name' => 'Gym','description' => 'Gym','icon' => 'gym.png', 'mobile_icon' => 'm'],
                ['type_id' => '2', 'name' => 'Elevator in Building','description' => '','icon' => 'elevator.png', 'mobile_icon' => 'i'],
                ['type_id' => '2', 'name' => 'Indoor Fireplace','description' => '','icon' => 'fireplace.png', 'mobile_icon' => 'l'],
                ['type_id' => '2', 'name' => 'Buzzer/Wireless Intercom','description' => '','icon' => 'buzzer.png', 'mobile_icon' => 'q'],
                ['type_id' => '2', 'name' => 'Doorman','description' => '','icon' => 'doorman.png', 'mobile_icon' => 'g'],
                ['type_id' => '2', 'name' => 'Shampoo','description' => '','icon' => 'shampoo.png', 'mobile_icon' => 'x'],
                ['type_id' => '3', 'name' => 'Family/Kid Friendly','description' => 'Family/Kid Friendly','icon' => 'family.jpeg', 'mobile_icon' => 'k'],
                ['type_id' => '3', 'name' => 'Smoking Allowed','description' => '','icon' => 'smoking.png', 'mobile_icon' => 'y'],
                ['type_id' => '3', 'name' => 'Suitable for Events','description' => 'Suitable for Events','icon' => 'events.png', 'mobile_icon' => 'c'],
                ['type_id' => '3', 'name' => 'Pets Allowed','description' => '','icon' => 'petsallowed.png', 'mobile_icon' => 'v'],
                ['type_id' => '3', 'name' => 'Pets live on this property','description' => '','icon' => 'petslive.png', 'mobile_icon' => 't'],
                ['type_id' => '3', 'name' => 'Wheelchair Accessible','description' => 'Wheelchair Accessible','icon' => 'wheelchair.png', 'mobile_icon' => 'a'],
                ['type_id' => '4', 'name' => 'Smoke Detector','description' => 'Smoke Detector','icon' => 'smokedet.jpeg', 'mobile_icon' => 't'],
                ['type_id' => '4', 'name' => 'Carbon Monoxide Detector','description' => 'Carbon Monoxide Detector','icon' => 'codetect.png', 'mobile_icon' => 't'],
                ['type_id' => '4', 'name' => 'First Aid Kit','description' => '','icon' => 'firstaidkit.jpeg', 'mobile_icon' => 't'],
                ['type_id' => '4', 'name' => 'Safety Card','description' => 'Safety Card','icon' => 'safety.png', 'mobile_icon' => 't'],
                ['type_id' => '4', 'name' => 'Fire Extinguisher','description' => 'Essentials','icon' => 'fire.png', 'mobile_icon' => 't'],
            ]);
    }
}
