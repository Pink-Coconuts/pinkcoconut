<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/**
 * No Authentication required routes
 */
Route::get('accepted/{id}','EmailController@accepted');
// Api payment
Route::post('experiences/{host_experience_id}/book/complete_payment', 'HostExperiencePaymentController@complete_payment');
Route::get('experiences/{host_experience_id}/book/payment_success', 'HostExperiencePaymentController@payment_success');
Route::get('experiences/{host_experience_id}/book/payment_cancel', 'HostExperiencePaymentController@payment_cancel');
Route::post('experiences/{host_experience_id}/book/update_payment_data', 'HostExperiencePaymentController@update_payment_data');

Route::group(
	['middleware' => ['install', 'locale', 'session_check']],
	function () {
		Route::get('about/{name}', 'HomeController@static_pages');
		Route::get('/', 'HomeController@index');
		Route::get('ajax_home', 'HomeController@ajax_home');
		Route::get('ajax_home_explore', 'HomeController@ajax_home_explore');
		Route::get('ajax_our_community', 'HomeController@ajax_our_community');
	}
);
Route::get('phpinfo', 'HomeController@phpinfo');
Route::get('pcss', 'PcssController@index');
Route::get('googleAuthenticate', 'UserController@googleAuthenticate');
Route::get('facebooklogin', 'HomeController@generateFacebookUrl');
//linkedin login
Route::get(
	'linkedin',
	function () {
		return Redirect::to('login');
	}
);

/**
 * Access only without authertication
 */
Route::get('auth/linkedin', 'UserController@redirectToLinkedin');
Route::get('linkedinLoginVerification', 'UserController@linkedinLoginVerification');
Route::get('linkedinConnect', 'UserController@linkedinConnect');
Route::group(
	['before' => 'no_auth', 'middleware' => ['locale', 'guest:user', 'session_check']],
	function () {
		Route::get('login', 'HomeController@login');
		Route::get(
			'auth/login',
			function () {
				return Redirect::to('login');
			}
		);
		Route::get('signup_login', 'HomeController@signup_login');
		Route::post('create', 'UserController@create');
		Route::post('authenticate', 'UserController@authenticate');
		Route::get('facebookAuthenticate', 'UserController@facebookAuthenticate');
		Route::get('googleLogin', 'UserController@googleLogin');
		Route::match(array('GET', 'POST'), 'forgot_password', 'UserController@forgot_password');
		Route::get('users/set_password/{secret?}', 'UserController@set_password');
		Route::post('users/set_password', 'UserController@set_password');
		Route::get('c/{username}', 'ReferralsController@invite_referral');
		Route::get('user_disabled', 'UserController@user_disabled');
		Route::get('users/signup_email', 'UserController@signup_email');
		Route::post('users/finish_signup_email', 'UserController@finish_signup_email');
		Route::post('users/finish_signup_linkedin_email', 'UserController@finish_signup_linkedin_email');
	}
);

/**
 * No Authentication required routes
 */
Route::group(
	['middleware' => ['locale', 'session_check']],
	function () {
		Route::get(
			'404',
			function () {
				abort('404');
			}
		);
		Route::get('contact', 'HomeController@contact');
		Route::match(['get', 'post'], 'contact_create', 'HomeController@contact_create');
		Route::post('set_session', 'HomeController@set_session');
		Route::get('s', 'SearchController@index');
		Route::match(['get', 'post'], 'searchResult', 'SearchController@searchResult');
		Route::match(['get', 'post'], 'searchResultTest', 'SearchController@searchResultTest');
		Route::match(['get', 'post'], 'searchexperienceResult', 'SearchController@searchexperienceResult');
		Route::post('rooms_photos', 'SearchController@rooms_photos');
		Route::post('host_experience_photos', 'SearchController@host_experience_photos');
		Route::get('s/{id}', 'SearchController@index')->where('id', '[0-9]+');
		Route::post('get_lang_details/{id}', 'RoomsController@get_lang_details');
		Route::post('get_lang', 'RoomsController@get_lang');
		Route::get('currency_cron', 'CronController@currency');
		Route::get('cron/ical_sync', 'CronController@ical_sync');
		Route::get('cron/expire', 'CronController@expire');
		Route::get('cron/travel_credit', 'CronController@travel_credit');
		Route::get('cron/review_remainder', 'CronController@review_remainder');
		Route::get('cron/host_remainder_pending_reservaions', 'CronController@host_remainder_pending_reservaions');
		Route::get('users/show/{id}', 'UserController@show')->where('id', '[0-9]+');
		Route::get('home/cancellation_policies', 'HomeController@cancellation_policies');
		Route::get('help', 'HomeController@help');
		Route::get('help/topic/{id}/{category}', 'HomeController@help');
		Route::get('help/article/{id}/{question}', 'HomeController@help');
		Route::get('ajax_help_search', 'HomeController@ajax_help_search');
		Route::get('wishlist_list', 'WishlistController@wishlist_list');
		Route::post('get_wishlists_home', 'WishlistController@get_wishlists_home');
		Route::post('get_wishlists_experience', 'WishlistController@get_wishlists_experience');
		Route::get('wishlists/{id}', 'WishlistController@wishlist_details')->where('id', '[0-9]+');
		Route::get('users/{id}/wishlists', 'WishlistController@my_wishlists');
		Route::get('invite', 'ReferralsController@invite');
		Route::get('wishlists/popular', 'WishlistController@popular');
		Route::get('wishlists/picks', 'WishlistController@picks');
		Route::get('calendar/ical/{id}', 'CalendarController@ical_export');
	}
);

/**
 * Access only with authentication
 */
Route::group(
	['before' => 'guest', 'middleware' => ['locale', 'auth:user', 'session_check']],
	function () {
		Route::get('dashboard', 'UserController@dashboard');
		Route::get('host_dashboard', 'UserController@host_dashboard');
		Route::get('users/edit', 'UserController@edit');
		Route::match(['get', 'post'], 'users/get_users_phone_numbers', 'UserController@get_users_phone_numbers');
		Route::post('users/update_users_phone_number', 'UserController@update_users_phone_number');
		// Route::get('welcom_email/{id}/{type}', ['uses' => 'EmailController@wrote_review']);
		Route::post('users/remove_users_phone_number', 'UserController@remove_users_phone_number');
		Route::post('users/verify_users_phone_number', 'UserController@verify_users_phone_number');
		Route::get('users/edit/media', 'UserController@media');
		Route::get('users/edit_verification', 'UserController@verification');
		Route::get('users/get_verification_documents', 'UserController@get_verification_documents');
        Route::post('users/delete_document','UserController@delete_document');
        Route::post('users/upload_verification_documents','UserController@upload_verification_documents');
		Route::get('facebookConnect', 'UserController@facebookConnect');
		Route::get('facebookDisconnect', 'UserController@facebookDisconnect');
		Route::get('googleLoginVerification', 'UserController@googleLoginVerification');
		Route::get('googleConnect/{id}', 'UserController@googleConnect')->where('id', '[0-9]+');
		Route::get('googleDisconnect', 'UserController@googleDisconnect');
		Route::get('linkedinDisconnect', 'UserController@linkedinDisconnect');
		Route::post('users/image_upload', 'UserController@image_upload');
		Route::post('users/remove_images', 'UserController@remove_images');
		Route::get('users/reviews', 'UserController@reviews');
		Route::match(['get', 'post'], 'reviews/edit/{id}', 'UserController@reviews_edit')->where('id', '[0-9]+');
		Route::match(['get', 'post'], 'host_experience_reviews/edit/{id}', 'UserController@host_experience_reviews_edit')->where('id', '[0-9]+');
		Route::post('users/update/{id}', 'UserController@update')->where('id', '[0-9]+');
		Route::get('users/confirm_email/{code?}', 'UserController@confirm_email');
		Route::get('users/request_new_confirm_email', 'UserController@request_new_confirm_email');
		Route::get('users/security', 'UserController@security');
		Route::post('wishlist_create', 'WishlistController@create');
		Route::post('create_new_wishlist', 'WishlistController@create_new_wishlist');
		Route::post('edit_wishlist/{id}', 'WishlistController@edit_wishlist')->where('id', '[0-9]+');
		Route::get('delete_wishlist/{id}', 'WishlistController@delete_wishlist')->where('id', '[0-9]+');
		Route::post('remove_saved_wishlist/{id}', 'WishlistController@remove_saved_wishlist')->where('id', '[0-9]+');
		Route::post('add_note_wishlist/{id}', 'WishlistController@add_note_wishlist')->where('id', '[0-9]+');
		Route::post('save_wishlist', 'WishlistController@save_wishlist');
		Route::post('save_wishlist_experience', 'WishlistController@save_wishlist_experience');
		Route::get('wishlists/my', 'WishlistController@my_wishlists');
		Route::post('share_email/{id}', 'WishlistController@share_email')->where('id', '[0-9]+');
		Route::match(['get', 'post'], 'users/payout_preferences/{id}', 'UserController@payout_preferences')->where('id', '[0-9]+');
		Route::match(['get', 'post'], 'users/update_payout_preferences/{id}', 'UserController@update_payout_preferences')->where('id', '[0-9]+');
		Route::match(['get', 'post'], 'users/stripe_payout_preferences', 'UserController@stripe_payout_preferences');
		Route::get('users/payout_delete/{id}', 'UserController@payout_delete')->where('id', '[0-9]+');
		Route::get('users/payout_default/{id}', 'UserController@payout_default')->where('id', '[0-9]+');
		Route::get('users/transaction_history', 'UserController@transaction_history');
		Route::post('users/result_transaction_history', 'UserController@result_transaction_history');
		Route::get('transaction_history/csv/{id}', 'UserController@transaction_history_csv')->where('id', '[0-9]+');
		Route::post('change_password', 'UserController@change_password');
		Route::get(
			'account',
			function () {
				return Redirect::to('users/payout_preferences/' . Auth::user()->id);
			}
		);
		Route::get('rooms', 'RoomsController@index');
		Route::get('rooms/new', 'RoomsController@new_room');
		Route::post('rooms/create', 'RoomsController@create');
		Route::get(
			'rooms/create',
			function () {
				return redirect('rooms/new');
			}
		);
		Route::group(
			['middleware' => 'manage_listing_auth'],
			function () {
				Route::post('manage-listing/{id}/update_rooms', 'RoomsController@update_rooms')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_amenities', 'RoomsController@update_amenities')->where('id', '[0-9]+');
				Route::post('add_photos/{id}', 'RoomsController@add_photos')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/featured_image', 'RoomsController@featured_image')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/delete_photo', 'RoomsController@delete_photo')->where('id', '[0-9]+');
				Route::get('manage-listing/{id}/photos_list', 'RoomsController@photos_list')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/photo_highlights', 'RoomsController@photo_highlights')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_price', 'RoomsController@update_price')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/currency_check', 'RoomsController@currency_check')->where('id', '[0-9]+');
				Route::get('manage-listing/{id}/currency_check', 'RoomsController@currency_check')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_description', 'RoomsController@update_description')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/lan_description', 'RoomsController@lan_description');
				Route::post('manage-listing/{id}/get_description', 'RoomsController@get_description');
				Route::post('manage-listing/{id}/get_all_language', 'RoomsController@get_all_language');
				Route::post('manage-listing/{id}/add_description', 'RoomsController@add_description');
				Route::post('manage-listing/{id}/delete_language', 'RoomsController@delete_language');
				Route::get('manage-listing/{id}/rooms_steps_status', 'RoomsController@rooms_steps_status');
				Route::get('manage-listing/{id}/rooms_data', 'RoomsController@rooms_data')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/calendar_edit', 'RoomsController@calendar_edit')->where('id', '[0-9]+');
				//if session out after redirect calendar page
				Route::match(['get', 'post'], 'calendar/import/{id}', 'CalendarController@ical_import')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_price_rules/{type}', 'RoomsController@update_price_rules')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/delete_price_rule/{rule_id}', 'RoomsController@delete_price_rule')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/delete_availability_rule/{rule_id}', 'RoomsController@delete_availability_rule')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_reservation_settings', 'RoomsController@update_reservation_settings')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/update_availability_rule', 'RoomsController@update_availability_rule')->where('id', '[0-9]+');
				Route::post('manage-listing/{id}/delete_availability_rule/{rule_id}', 'RoomsController@delete_availability_rule')->where('id', '[0-9]+');
			}
		);
		/*HostExperiencePHPCommentStart*/
		Route::group(
			['prefix' => 'host'],
			function () {
				Route::get('delete_host_experience/{host_experience_id}', 'HostExperiencesController@delete_host_experience')->where('host_experience_id', '[0-9]+');
				Route::match(['get', 'post'], 'experiences/new', 'HostExperiencesController@new_host_experience');
				Route::group(
					['middleware' => 'manage_experience_auth'],
					function () {
						Route::get('review_experience/{host_experience_id}', 'HostExperiencesController@review_experience')->where('host_experience_id', '[0-9]+');
						Route::post('ajax_review_experience/{host_experience_id}', 'HostExperiencesController@ajax_review_experience')->where('host_experience_id', '[0-9]+');
						Route::get('manage_experience/{host_experience_id}', 'HostExperiencesController@manage_experience')->where('host_experience_id', '[0-9]+');
						Route::post('ajax_manage_experience/{host_experience_id}', 'HostExperiencesController@ajax_manage_experience')->where('host_experience_id', '[0-9]+');
						Route::post('manage_experience/{host_experience_id}/update_experience', 'HostExperiencesController@update_experience')->where('host_experience_id', '[0-9]+');
						Route::post('manage_experience/{host_experience_id}/upload_photo', 'HostExperiencesController@upload_photo')->where('host_experience_id', '[0-9]+');
						Route::post('manage_experience/{host_experience_id}/delete_photo', 'HostExperiencesController@delete_photo')->where('host_experience_id', '[0-9]+');
						Route::post('manage_experience/{host_experience_id}/refresh_calendar', 'HostExperiencesController@refresh_calendar')->where('host_experience_id', '[0-9]+');
						Route::post('manage_experience/{host_experience_id}/update_calendar', 'HostExperiencesController@update_calendar')->where('host_experience_id', '[0-9]+');
					}
				);
			}
		);
		/*HostExperiencePHPCommentEnd*/
		Route::get('experiences/{host_experience_id}/book/{tab}', 'HostExperiencePaymentController@index');
		Route::post('remove_sync_calendar', 'CalendarController@remove_sync_calendar');
		Route::post('get_sync_calendar', 'CalendarController@get_synced_calendar');
		Route::get('calendar/sync/{id}', 'CalendarController@ical_sync')->where('id', '[0-9]+');
		Route::post('manage-listing/{id}/remove_video', 'RoomsController@remove_video')->where('id', '[0-9]+');
		Route::get('manage-listing/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::post('ajax-manage-listing/{id}/{page}', 'RoomsController@ajax_manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::post('ajax-header/{id}/{page}', 'RoomsController@ajax_header')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		//get method to redirect manage listing
		Route::get('ajax-manage-listing/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::get('ajax-header/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::get('enter_address/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::get('location_not_found/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::get('verify_location/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		Route::get('finish_address/{id}/{page}', 'RoomsController@manage_listing')
			->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|video|pricing|calendar|details|guidebook|terms|booking']);
		//end
		Route::post('enter_address/{id}/{page}', 'RoomsController@enter_address');
		Route::post('location_not_found/{id}/{page}', 'RoomsController@location_not_found');
		Route::post('verify_location/{id}/{page}', 'RoomsController@verify_location');
		Route::post('finish_address/{id}/{page}', 'RoomsController@finish_address');
		Route::get('inbox', 'InboxController@index');
		Route::match(['get', 'post'], 'payments/book/{id?}', 'PaymentController@index')->where('id', '[0-9]+');
		Route::post('payments/apply_ngo', 'PaymentController@apply_ngo');
		Route::post('payments/apply_coupon', 'PaymentController@apply_coupon');
		Route::post('payments/remove_coupon', 'PaymentController@remove_coupon');
		Route::match(['get', 'post'], 'payments/create_booking', 'PaymentController@create_booking');
		Route::match(['get', 'post'], 'payments/pre_accept', 'PaymentController@pre_accept');
		Route::get('payments/success', 'PaymentController@success');
		Route::get('payments/cancel', 'PaymentController@cancel');
		Route::post('users/ask_question/{id}', 'RoomsController@contact_request')->where('id', '[0-9]+');
		// Message
		Route::post('inbox/archive', 'InboxController@archive');
		Route::post('inbox/star', 'InboxController@star');
		Route::post('inbox/message_by_type', 'InboxController@message_by_type');
		Route::post('inbox/all_message', 'InboxController@all_message');
		Route::get('z/q/{id}', 'InboxController@guest_conversation')->where('id', '[0-9]+');
		Route::get('messaging/qt_with/{id}', 'InboxController@host_conversation')->where('id', '[0-9]+');
		Route::post('messaging/qt_reply/{id}', 'InboxController@reply');
        Route::get('admin_messages/{id}','InboxController@admin_messages');
		Route::get('messaging/remove_special_offer/{id}', 'InboxController@remove_special_offer')->where('id', '[0-9]+');
		Route::post('inbox/calendar', 'InboxController@calendar');
		Route::post('inbox/message_count', 'InboxController@message_count');
		// Reservation
		Route::get('reservation/{id}', 'ReservationController@index')->where('id', '[0-9]+');
		Route::post('reservation/accept/{id}', 'ReservationController@accept')->where('id', '[0-9]+');
		Route::post('reservation/decline/{id}', 'ReservationController@decline')->where('id', '[0-9]+');
		Route::get('reservation/expire/{id}', 'ReservationController@expire')->where('id', '[0-9]+');
		Route::get('my_reservations', 'ReservationController@my_reservations');
		Route::get('reservation/itinerary', 'ReservationController@print_confirmation');
		Route::get('reservation/requested', 'ReservationController@requested');
		Route::post('reservation/itinerary_friends', 'ReservationController@itinerary_friends');
		// Change and cancel
		Route::match(['get', 'post'], 'reservation/change', 'ReservationController@host_cancel_change');
		Route::match(['get', 'post'], 'alterations/{code}', 'ReservationController@cancel_alterations');
		Route::match(['get', 'post'], 'reservation/reservation_alterations', 'ReservationController@reservation_alterations');
		// Cancel Reservation
		Route::match(['get', 'post'], 'trips/guest_cancel_pending_reservation', 'TripsController@guest_cancel_pending_reservation');
		Route::match(['get', 'post'], 'trips/guest_cancel_reservation', 'TripsController@guest_cancel_reservation');
		Route::match(['get', 'post'], 'reservation/host_cancel_reservation', 'ReservationController@host_cancel_reservation');
		Route::match(['get', 'post'], 'checking/{id}', 'TripsController@get_status')->where('id', '[0-9]+');
		Route::match(['get', 'post'], 'status_update', 'TripsController@status_update');
		Route::match(['get', 'post'], 'reservation/cencel_request_send', 'ReservationController@cencel_request_send');
		// Trips
		Route::get('trips/current', 'TripsController@current');
		Route::get('trips/previous', 'TripsController@previous');
		Route::get('reservation/receipt', 'TripsController@receipt');
		Route::post('invite/share_email', 'ReferralsController@share_email');
		Route::post('disputes/create', 'DisputesController@create_dispute');
		Route::get('disputes', 'DisputesController@index');
		Route::post('get_disputes', 'DisputesController@get_disputes');
		Route::get('dispute_details/{id}', 'DisputesController@details');
		Route::get('dispute_documents_slider/{id}', 'DisputesController@documents_slider');
		Route::post('dispute_keep_talking/{id}', 'DisputesController@keep_talking');
		Route::post('dispute_documents_upload/{id}', 'DisputesController@documents_upload');
		Route::post('dispute_involve_site/{id}', 'DisputesController@involve_site');
		Route::post('dispute_accept_amount/{id}', 'DisputesController@accept_amount');
		Route::post('dispute_pay_amount/{id}', 'DisputesController@pay_amount');
		Route::get('dispute_pay_amount_success/{id}', 'DisputesController@pay_amount_success');
		Route::get('dispute_pay_amount_cancel/{id}', 'DisputesController@pay_amount_cancel');
		Route::post('dispute_details/dispute_delete_document', 'DisputesController@dispute_delete_document');
	}
);

/**
 * No Authentication required routes
 */
Route::group(
	['middleware' => ['locale', 'session_check']],
	function () {
		// Rooms details
		Route::get('rooms/{id}', 'RoomsController@rooms_detail')->where('id', '[0-9]+');
		Route::get('rooms/{id}/slider', 'RoomsController@rooms_slider')->where('id', '[0-9]+');
		Route::post('rooms/rooms_calendar', 'RoomsController@rooms_calendar');
		Route::post('rooms/rooms_calendar_alter', 'RoomsController@rooms_calendar_alter');
		Route::post('rooms/price_calculation', 'RoomsController@price_calculation');
		Route::post('rooms/check_availability', 'RoomsController@check_availability');
		Route::post('rooms/current_date_check', 'RoomsController@current_date_check');
		Route::post('rooms/checkin_date_check', 'RoomsController@checkin_date_check');
		/*HostExperiencePHPCommentStart*/
		Route::get('host/experiences', 'HostExperiencesController@index');
		Route::get('host/experiences/set_city', 'HostExperiencesController@set_city');
		Route::get('experiences/{host_experience_id}', 'HostExperiencesController@experience_detail')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/get_available_dates', 'HostExperiencesController@get_available_dates')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/choose_date', 'HostExperiencesController@choose_date')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/get_all_reviews', 'HostExperiencesController@get_all_reviews')->where('host_experience_id', '[0-9]+');
		Route::post('experiences/{host_experience_id}/contact_host', 'HostExperiencesController@contact_host')->where('host_experience_id', '[0-9]+');
		/*HostExperiencePHPCommentEnd*/
	}
);
Route::get(
	'logout',
	function () {
		Auth::guard('user')->logout();
		return Redirect::to('login');
	}
);

Route::get(
	'in_secure',
	function () {
		return view('errors.in_secure');
	}
);
