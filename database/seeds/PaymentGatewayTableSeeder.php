<?php

use Illuminate\Database\Seeder;

class PaymentGatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateway')->delete();

        DB::table('payment_gateway')->insert([
                ['name' => 'username', 'value' => 'pvignesh90-facilitator_api1.gmail.com', 'site' => 'PayPal'],
                ['name' => 'password', 'value' => '1381798304', 'site' => 'PayPal'],
                ['name' => 'signature', 'value' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacALfsdnEmmarK-6V7JsbXFL2.hoZ8', 'site' => 'PayPal'],
                ['name' => 'mode', 'value' => 'sandbox', 'site' => 'PayPal'],
                ['name' => 'client', 'value' => 'ASeeaUVlKXDd8DegCNSuO413fePRLrlzZKdGE_RwrWqJOVVbTNJb6-_r6xX9GdsRUVNc8butjTOIK_Xm', 'site' => 'PayPal'],
                ['name' => 'secret', 'value' => 'ENCGBUb_QSpHzGIAxjtSehkRIAI9lOELOiZUUjZUTEdjACeILOUUG58ijBNsuzdV-RPyDbHNxYTPkapn', 'site' => 'PayPal'],
                
                ['name' => 'publish', 'value' => 'pk_test_HI0olTFjGwzfS7LAdyhKqHRl', 'site' => 'Stripe'],
                ['name' => 'secret', 'value' => 'sk_test_RsheL8d6N4wkIcWsM93sr8Pw', 'site' => 'Stripe'],
                ['name' => 'client_id', 'value' => 'ca_BD2o6qLkSlfq8xpypwhEaZDBxrf2q3G5', 'site' => 'Stripe'],
            ]);
    }
}
