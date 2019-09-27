<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/**
 * Api Payments Routes
 */
Route::match(['get', 'post'], 'api_payments/book/{id?}', 'PaymentController@index');
Route::post('api_payments/pre_accept', 'PaymentController@pre_accept');
Route::post('api_payments/create_booking', 'PaymentController@create_booking');
Route::get('api_payments/success', 'PaymentController@success');
Route::get('api_payments/cancel', 'PaymentController@cancel');
Route::post('api_payments/apply_coupon', 'PaymentController@apply_coupon');
Route::post('api_payments/remove_coupon', 'PaymentController@remove_coupon');
Route::get('experiences/book/{host_experience_id?}', 'Api\HostExperiencePaymentController@index');

/**
 * Api routes
 */
Route::group(
	['prefix' => 'api'],
	function () {
		Route::get('register', 'Api\TokenAuthController@register');
		Route::get('authenticate', 'Api\TokenAuthController@authenticate');
		Route::get('token', 'Api\TokenAuthController@token');
		Route::get('signup', 'Api\TokenAuthController@signup');
		Route::get('login', 'Api\TokenAuthController@login');
		Route::get('emailvalidation', 'Api\TokenAuthController@emailvalidation');
		Route::get('forgotpassword', 'Api\TokenAuthController@forgotpassword');
		Route::match(['get', 'post'], 'add_payout_perference', 'Api\PaymentController@add_payout_perference');

		Route::get('explore', 'Api\SearchController@explore_details');
		Route::get('rooms', 'Api\RoomsController@rooms_detail');
		//get room and property type
		Route::get('room_property_type', 'Api\RoomsController@room_property_type');
		Route::get('amenities_list', 'Api\HomeController@amenities_list');
		Route::get('currency_list', 'Api\HomeController@currency_list');
		Route::get('calendar_availability_status', 'Api\RoomsController@calendar_availability_status');
		Route::get('user_profile_details', 'Api\UserController@user_profile_details');
		Route::get('review_detail', 'Api\RoomsController@review_detail');
		/*HostExperiencePHPCommentStart*/
				Route::get('host_experience_categories', 'Api\HostExperiencesController@host_experience_categories');
				Route::get('explore_experiences', 'Api\SearchController@explore_experiences');
				Route::get('experience', 'Api\HostExperiencesController@experience_details');
				Route::get('experience_review_detail', 'Api\HostExperiencesController@experience_review_detail');
				
		/*HostExperiencePHPCommentEnd*/

		Route::group(
			['middleware' => ['jwt.auth', 'disable_user']],
			function () {
				Route::get('authenticate/user', 'Api\TokenAuthController@getAuthenticatedUser');
				Route::get('user_details', 'Api\UserController@user_details');
				Route::get('home_cities', 'Api\UserController@home_cities');
				Route::get('payout_details', 'Api\UserController@payout_details');
				Route::get('payout_changes', 'Api\UserController@payout_changes');
				Route::get('maps', 'Api\RoomsController@maps');
				Route::get('calendar_availability', 'Api\RoomsController@calendar_availability');
				Route::get('country_list', 'Api\HomeController@country_list');
				Route::get('stripe_supported_country_list', 'Api\HomeController@stripe_supported_country_list');
				Route::get('pre_payment', 'Api\PaymentController@pre_payment');
				Route::get('after_payment', 'Api\PaymentController@after_payment');
				Route::get('payment_methods', 'Api\PaymentController@payment_methods');
				Route::get('apply_coupon', 'Api\PaymentController@apply_coupon');
				Route::get('house_rules', 'Api\RoomsController@house_rules');
				Route::get('add_rooms_price', 'Api\RoomsController@add_rooms_price');
				Route::get('update_Long_term_prices', 'Api\RoomsController@update_Long_term_prices');
				Route::get('update_room_currency', 'Api\RoomsController@update_room_currency');
				Route::get('update_location', 'Api\RoomsController@update_location');
				Route::get('disable_listing ', 'Api\RoomsController@disable_listing');
				Route::get('update_price_rule ', 'Api\RoomsController@update_price_rule');
				Route::get('delete_price_rule ', 'Api\RoomsController@delete_price_rule');
				Route::get('update_availability_rule ', 'Api\RoomsController@update_availability_rule');
				Route::get('delete_availability_rule ', 'Api\RoomsController@delete_availability_rule');
				Route::get('get_price_rules_list ', 'Api\RoomsController@get_price_rules_list');
				Route::get('get_availability_rules_list ', 'Api\RoomsController@get_availability_rules_list');
				Route::get('update_minimum_maximum_stay ', 'Api\RoomsController@update_minimum_maximum_stay');
				Route::get('logout', 'Api\UserController@logout');
				Route::get('currency_change', 'Api\PaymentController@currency_change');

				Route::get('update_house_rules', 'Api\HomeController@update_house_rules');
				Route::get('update_description', 'Api\HomeController@update_description');
				Route::get('update_title_description', 'Api\HomeController@update_title_description');
				Route::get('update_amenities', 'Api\HomeController@update_amenities');
				Route::get('add_whishlist', 'Api\HomeController@add_whishlist');
				Route::get('update_calendar', 'Api\HomeController@update_calendar');
				Route::get('home_pending_request', 'Api\HomeController@home_pending_request');
				Route::get('rooms_list_calendar', 'Api\HomeController@rooms_list_calendar');
				Route::get('new_add_room', 'Api\RoomsController@new_add_room');
				Route::get('listing', 'Api\RoomsController@listing_rooms');
				Route::get('listing_rooms_beds', 'Api\RoomsController@listing_rooms_beds');
				Route::get('send_message', 'Api\MessagesController@send_message');
				Route::get('inbox_reservation', 'Api\ReservationController@inbox_reservation');
				Route::get('view_profile', 'Api\UserController@view_profile');
				Route::get('edit_profile', 'Api\UserController@edit_profile');
				Route::get('conversation_list', 'Api\ReservationController@conversation_list');
				Route::get('trips_type', 'Api\TripsController@trips_type');
				Route::get('trips_details', 'Api\TripsController@trips_details');
				Route::get('add_wishlists', 'Api\WishlistsController@add_wishlists');
				Route::get('get_whishlist', 'Api\WishlistsController@get_whishlist');
				Route::get('get_particular_wishlist', 'Api\WishlistsController@get_particular_wishlist');
				Route::get('delete_wishlist', 'Api\WishlistsController@delete_wishlist');
				Route::get('edit_wishlist', 'Api\WishlistsController@edit_wishlist');
				Route::get('book_now', 'Api\PaymentController@book_now');
				Route::get('pay_now', 'Api\PaymentController@pay_now');
				Route::get('payment_success', 'Api\PaymentController@payment_success');
				
				Route::get('guest_cancel_pending_reservation', 'Api\TripsController@guest_cancel_pending_reservation');
				Route::get('guest_cancel_reservation', 'Api\TripsController@guest_cancel_reservation');
				Route::get('reservation_list', 'Api\ReservationController@reservation_list');
				Route::get('update_booking_type', 'Api\RoomsController@update_booking_type');
				Route::get('contact_request', 'Api\RoomsController@contact_request');
				Route::get('update_policy', 'Api\RoomsController@update_policy');
				Route::get('remove_uploaded_image', 'Api\RoomsController@remove_uploaded_image');
				Route::get('host_cancel_reservation', 'Api\ReservationController@host_cancel_reservation');
				Route::get('pre_approve', 'Api\ReservationController@pre_approve');
				Route::get('pre_accept', 'Api\PaymentController@pre_accept');
				Route::get('accept', 'Api\PaymentController@accept');
				Route::get('decline', 'Api\PaymentController@decline');
				Route::get('new_update_calendar', 'Api\HomeController@new_update_calendar');

				/*HostExperiencePHPCommentStart*/
				
				Route::get('choose_date', 'Api\HostExperiencesController@choose_date');
				Route::get('add_guest_details', 'Api\HostExperiencesController@add_guest_details');
				Route::get('experience_pre_payment', 'Api\HostExperiencePaymentController@experience_pre_payment');
				
				Route::get('experience_payment', 'Api\HostExperiencePaymentController@book_now');
				Route::get('experiences/{host_experience_id}/book/update_payment_data', 'Api\HostExperiencePaymentController@update_payment_data');
				Route::get('experiences/contact_host', 'Api\HostExperiencesController@contact_host');

				/*HostExperiencePHPCommentEnd*/
			}
		);
		Route::match(array('GET', 'POST'), 'upload_profile_image', 'Api\UserController@upload_profile_image');
		Route::match(array('GET', 'POST'), 'upload_profile_images', 'Api\UserController@upload_profile_images');
		Route::match(array('GET', 'POST'), 'room_image_upload', 'Api\RoomsController@room_image_upload');
		Route::match(array('GET', 'POST'), 'room_image_uploads', 'Api\RoomsController@room_image_uploads');
	}
);
