<?php

use Illuminate\Database\Seeder;

class MetasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('metas')->delete();

        DB::table('metas')->insert([
                ['url' => '/', 'title' => 'Vacation Rentals, Homes, Apartments &amp; Rooms for Rent', 'description' => 'Vacation Rentals, Homes, Apartments &amp; Rooms for Rent', 'keywords' => ''],
                ['url' => 'login', 'title' => 'Log In / Sign Up', 'description' => 'Log In / Sign Up', 'keywords' => ''],
                ['url' => 'signup_login', 'title' => 'Log In / Sign Up', 'description' => 'Log In / Sign Up', 'keywords' => ''],
                ['url' => 'inbox', 'title' => 'Inbox', 'description' => 'Inbox', 'keywords' => ''],
                ['url' => 'z/q/{id}', 'title' => 'Conversation', 'description' => 'Conversation', 'keywords' => ''],
                ['url' => 'messaging/qt_with/{id}', 'title' => 'Conversation', 'description' => 'Conversation', 'keywords' => ''],
                ['url' => 'payments/book/{id?}', 'title' => 'Booking', 'description' => 'Booking', 'keywords' => ''],
                ['url' => 'api_payments/book/{id?}', 'title' => 'Booking', 'description' => 'Booking', 'keywords' => ''],
                ['url' => 'reservation/{id}', 'title' => 'Reservation Request', 'description' => 'Reservation Request', 'keywords' => ''],
                ['url' => 'my_reservations', 'title' => 'Your Reservations', 'description' => 'Your Reservations', 'keywords' => ''],
                ['url' => 'reservation/itinerary', 'title' => 'Travel Itinerary, Confirmation Code', 'description' => 'Travel Itinerary, Confirmation Code', 'keywords' => ''],
                ['url' => 'reservation/requested', 'title' => 'Accommodations', 'description' => 'Accommodations', 'keywords' => ''],
                ['url' => 'reservation/change', 'title' => 'Reservation Change', 'description' => 'Reservation Change', 'keywords' => ''],
                ['url' => 'alterations/{code}', 'title' => 'Reservation Change', 'description' => 'Reservation Change', 'keywords' => ''],
                ['url' => 'rooms', 'title' => 'Your Listings', 'description' => 'Your Listings', 'keywords' => ''],
                ['url' => 'rooms/new', 'title' => 'Rent Out Your Room, House or Apartment', 'description' => 'Rent Out Your Room, House or Apartment', 'keywords' => ''],
                ['url' => 'manage-listing/{id}/{page}', 'title' => 'Edit Listing', 'description' => 'Edit Listing', 'keywords' => ''],
                ['url' => 's', 'title' => 'Search', 'description' => 'Search', 'keywords' => ''],
                ['url' => 'trips/current', 'title' => 'Your Trips', 'description' => 'Your Trips', 'keywords' => ''],
                ['url' => 'trips/previous', 'title' => 'Your Trips', 'description' => 'Your Trips', 'keywords' => ''],
                ['url' => 'reservation/receipt', 'title' => 'Receipt, Confirmation Code', 'description' => 'Receipt, Confirmation Code', 'keywords' => ''],
                ['url' => 'dashboard', 'title' => 'Dashboard', 'description' => 'Dashboard', 'keywords' => ''],
                ['url' => 'forgot_password', 'title' => 'Forgot Password', 'description' => 'Forgot Password', 'keywords' => ''],
                ['url' => 'users/set_password/{secret?}', 'title' => 'Reset Password', 'description' => 'Reset Password', 'keywords' => ''],
                ['url' => 'users/edit', 'title' => 'Profile', 'description' => 'Profile', 'keywords' => ''],
                ['url' => 'users/edit/media', 'title' => 'Profile', 'description' => 'Profile', 'keywords' => ''],
                ['url' => 'users/security', 'title' => 'Account', 'description' => 'Account', 'keywords' => ''],
                ['url' => 'users/payout_preferences/{id}', 'title' => 'Account', 'description' => 'Account', 'keywords' => ''],
                ['url' => 'users/transaction_history', 'title' => 'Transaction History', 'description' => 'Transaction History', 'keywords' => ''],
                ['url' => 'users/reviews', 'title' => 'Reviews', 'description' => 'Reviews', 'keywords' => ''],
                ['url' => 'reviews/edit/{id}', 'title' => 'Reviews', 'description' => 'Reviews', 'keywords' => ''],
                ['url' => 'home/cancellation_policies', 'title' => 'Cancellation Policies', 'description' => 'Cancellation Policies', 'keywords' => ''],
                ['url' => 'help', 'title' => 'Help Center', 'description' => 'Help Center', 'keywords' => ''],
                ['url' => 'help/topic/{id}/{category}', 'title' => 'Help Center', 'description' => 'Help Center', 'keywords' => ''],
                ['url' => 'help/article/{id}/{question}', 'title' => 'Help Center', 'description' => 'Help Center', 'keywords' => ''],
                ['url' => 'wishlists/my', 'title' => 'Wish Lists', 'description' => 'Wish Lists', 'keywords' => ''],
                ['url' => 'users/{id}/wishlists', 'title' => 'Wish Lists', 'description' => 'Wish Lists', 'keywords' => ''],
                ['url' => 'wishlists/{id}', 'title' => 'Wish Lists', 'description' => 'Wish Lists', 'keywords' => ''],
                ['url' => 'invite', 'title' => 'Invite Friends', 'description' => 'Invite Friends', 'keywords' => ''],
                ['url' => 'c/{username}', 'title' => 'Invite Friends', 'description' => 'Invite Friends', 'keywords' => ''],
                ['url' => 'wishlists/popular', 'title' => 'Popular', 'description' => 'Popular', 'keywords' => ''],
                ['url' => 'wishlists/picks', 'title' => 'Picks', 'description' => 'Picks', 'keywords' => ''],
                ['url' => 'users/edit_verification', 'title' => 'Trust and Verification', 'description' => 'Trust and Verification', 'keywords' => ''],
                ['url' => 'in_secure', 'title' => 'In Secure Connection', 'description' => 'In Secure Connection', 'keywords' => ''],
                ['url' => 'disputes', 'title' => 'Disputes', 'description' => 'Disputes', 'keywords' => ''],
                ['url' => 'dispute_details/{id}', 'title' => 'Disputes', 'description' => 'Disputes', 'keywords' => ''],
                /*HostExperiencePHPCommentStart*/
                ['url' => 'host/experiences', 'title' => 'Become an Experience Host', 'description' => 'Become an Experience Host', 'keywords' => ''],
                ['url' => 'host/review_experience/{host_experience_id}', 'title' => 'Become an Experience Host', 'description' => 'Become an Experience Host', 'keywords' => ''],
                ['url' => 'host/manage_experience/{host_experience_id}', 'title' => 'Create your Experience', 'description' => 'Create your Experience', 'keywords' => ''],
                ['url' => 'host_experience_reviews/edit/{id}', 'title' => 'Reviews', 'description' => 'Reviews', 'keywords' => ''],
                /*HostExperiencePHPCommentEnd*/
            ]);
    }
}
