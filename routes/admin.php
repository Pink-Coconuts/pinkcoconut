<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Admin panel routes
 */





Route::group(
    ['prefix' => '{ADMIN_URL}', 'middleware' => 'auth:admin'],
    function () {
        Route::get(
            '/',
            function () {
                return Redirect::to(ADMIN_URL.'/dashboard');
            }
        );
        Route::get('dashboard', 'Admin\AdminController@index');
        Route::get('logout', 'Admin\AdminController@logout');

        Route::get('users', 'Admin\UsersController@index')->middleware('admin_can:users');
        Route::match(array('GET', 'POST'), 'add_user', 'Admin\UsersController@add')->middleware('admin_can:add_user');
        Route::match(array('GET', 'POST'), 'edit_user/{id}', 'Admin\UsersController@update')->where('id', '[0-9]+')->middleware('admin_can:edit_user');
        Route::get('delete_user/{id}', 'Admin\UsersController@delete')->where('id', '[0-9]+')->middleware('admin_can:delete_user');
        
        Route::match(['GET', 'POST'], 'reports', 'Admin\ReportsController@index')->middleware('admin_can:reports');
        Route::get('reports/export/{from}/{to}/{category}', 'Admin\ReportsController@export');

        Route::group(['middleware' => 'admin_can:reservations'], function() {
            Route::get('reservations', 'Admin\ReservationsController@index');
            Route::get('reservation/detail/{id}', 'Admin\ReservationsController@detail')->where('id', '[0-9]+');
            /*HostExperiencePHPCommentStart*/
            Route::get('host_experiences_reservation/detail/{id}', 'Admin\ReservationsController@host_experience_detail')->where('id', '[0-9]+');
            /*HostExperiencePHPCommentEnd*/
            Route::get('reservation/conversation/{id}', 'Admin\ReservationsController@conversation')->where('id', '[0-9]+');
            Route::get('reservation/need_payout_info/{id}/{type}/{list_type?}', 'Admin\ReservationsController@need_payout_info');
            Route::post('reservation/payout', 'Admin\ReservationsController@payout');
            
            Route::get('host_penalty', 'Admin\HostPenaltyController@index');
        });
        
        Route::get('rooms', 'Admin\RoomsController@index')->middleware('admin_can:rooms');
        Route::match(array('GET', 'POST'), 'add_room', 'Admin\RoomsController@add')->middleware('admin_can:add_room');
        Route::post('admin_update_rooms', 'Admin\RoomsController@update_video');
        Route::match(array('GET', 'POST'), 'edit_room/{id}', 'Admin\RoomsController@update')->where('id', '[0-9]+')->middleware('admin_can:edit_room');
        Route::get('delete_room/{id}', 'Admin\RoomsController@delete')->where('id', '[0-9]+')->middleware('admin_can:delete_room');
        Route::get('popular_room/{id}', 'Admin\RoomsController@popular')->where('id', '[0-9]+')->where('id', '[0-9]+');
        Route::get('feature_experience/{id}', 'Admin\HostExperiencesController@feature')->where('id', '[0-9]+')->where('id', '[0-9]+');
        Route::get('recommended_room/{id}', 'Admin\RoomsController@recommended')->where('id', '[0-9]+')->where('id', '[0-9]+');
        Route::post('ajax_calendar/{id}', 'Admin\RoomsController@ajax_calendar')->where('id', '[0-9]+');
        Route::post('delete_photo', 'Admin\RoomsController@delete_photo');
        Route::post('admin_pricelist', 'Admin\RoomsController@update_price');
        Route::post('featured_image', 'Admin\RoomsController@featured_image');
        Route::post('photo_highlights', 'Admin\RoomsController@photo_highlights');
        Route::get('rooms/users_list', 'Admin\RoomsController@users_list');
        Route::post('rooms/delete_price_rule/{id}', 'Admin\RoomsController@delete_price_rule')->where('id', '[0-9]+');
        Route::post('rooms/delete_availability_rule/{id}', 'Admin\RoomsController@delete_availability_rule')->where('id', '[0-9]+');
        
        // Manage Admin Permission Routes
        Route::group(
            ['before' => 'manage_admin', 'middleware' => 'admin_can:manage_admin'],
            function () {
                Route::get('admin_users', 'Admin\AdminusersController@index');
                Route::match(array('GET', 'POST'), 'add_admin_user', 'Admin\AdminusersController@add');
                Route::match(array('GET', 'POST'), 'edit_admin_user/{id}', 'Admin\AdminusersController@update')->where('id', '[0-9]+');
                Route::get('delete_admin_user/{id}', 'Admin\AdminusersController@delete')->where('id', '[0-9]+');
                Route::get('roles', 'Admin\RolesController@index');
                Route::match(array('GET', 'POST'), 'add_role', 'Admin\RolesController@add');
                Route::match(array('GET', 'POST'), 'edit_role/{id}', 'Admin\RolesController@update')->where('id', '[0-9]+');
                Route::get('delete_role/{id}', 'Admin\RolesController@delete')->where('id', '[0-9]+');
                Route::match(array('GET', 'POST'), 'add_permission', 'Admin\PermissionsController@add');
                Route::match(array('GET', 'POST'), 'edit_permission/{id}', 'Admin\PermissionsController@update')->where('id', '[0-9]+');
                Route::get('delete_permission/{id}', 'Admin\PermissionsController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Amenities Routes
        Route::group(
            ['before' => 'manage_amenities', 'middleware' => 'admin_can:manage_amenities'],
            function () {
                Route::get('amenities', 'Admin\AmenitiesController@index');
                Route::match(array('GET', 'POST'), 'add_amenity', 'Admin\AmenitiesController@add');
                Route::match(array('GET', 'POST'), 'edit_amenity/{id}', 'Admin\AmenitiesController@update')->where('id', '[0-9]+');
                Route::get('delete_amenity/{id}', 'Admin\AmenitiesController@delete')->where('id', '[0-9]+');
                Route::get('amenities_type', 'Admin\AmenitiesTypeController@index');
                Route::match(array('GET', 'POST'), 'add_amenities_type', 'Admin\AmenitiesTypeController@add');
                Route::match(array('GET', 'POST'), 'edit_amenities_type/{id}', 'Admin\AmenitiesTypeController@update')->where('id', '[0-9]+');
                Route::get('delete_amenities_type/{id}', 'Admin\AmenitiesTypeController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Property Type Routes
        Route::group(
            ['before' => 'manage_property_type', 'middleware' => 'admin_can:manage_property_type'],
            function () {
                Route::get('property_type', 'Admin\PropertyTypeController@index');
                Route::match(array('GET', 'POST'), 'add_property_type', 'Admin\PropertyTypeController@add');
                Route::match(array('GET', 'POST'), 'edit_property_type/{id}', 'Admin\PropertyTypeController@update')->where('id', '[0-9]+');
                Route::get('delete_property_type/{id}', 'Admin\PropertyTypeController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Home Page Cities Routes
        Route::get('referrals', 'Admin\ReferralsController@index')->middleware(['admin_can:manage_referrals']);
        Route::get('referral_details/{id}', 'Admin\ReferralsController@details')->where('id', '[0-9]+')->middleware(['admin_can:manage_referrals']);
        // Manage Home Cities
        Route::group(
            ['before' => 'manage_home_cities', 'middleware' => 'admin_can:manage_home_cities'],
            function () {
                Route::get('home_cities', 'Admin\HomeCitiesController@index');
                Route::match(array('GET', 'POST'), 'add_home_city', 'Admin\HomeCitiesController@add');
                Route::match(array('GET', 'POST'), 'edit_home_city/{id}', 'Admin\HomeCitiesController@update')->where('id', '[0-9]+');
                Route::get('delete_home_city/{id}', 'Admin\HomeCitiesController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Home Page Slider Routes
        Route::group(
            ['before' => 'manage_home_page_sliders', 'middleware' => 'admin_can:manage_home_page_sliders'],
            function () {
                Route::get('slider', 'Admin\SliderController@index');
                Route::match(array('GET', 'POST'), 'add_slider', 'Admin\SliderController@add');
                Route::match(array('GET', 'POST'), 'edit_slider/{id}', 'Admin\SliderController@update')->where('id', '[0-9]+');
                Route::get('delete_slider/{id}', 'Admin\SliderController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Home Page Bottom Slider Routes
        Route::group(
            ['before' => 'manage_home_page_bottom_sliders', 'middleware' => 'admin_can:manage_home_page_bottom_sliders'],
            function () {
                Route::get('bottom_slider', 'Admin\BottomSliderController@index');
                Route::match(array('GET', 'POST'), 'add_bottom_slider', 'Admin\BottomSliderController@add');
                Route::match(array('GET', 'POST'), 'edit_bottom_slider/{id}', 'Admin\BottomSliderController@update')->where('id', '[0-9]+');
                Route::get('delete_bottom_slider/{id}', 'Admin\BottomSliderController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Room Type Routes
        Route::group(
            ['before' => 'manage_room_type', 'middleware' => 'admin_can:manage_room_type'],
            function () {
                Route::get('room_type', 'Admin\RoomTypeController@index');
                Route::match(array('GET', 'POST'), 'add_room_type', 'Admin\RoomTypeController@add');
                Route::match(array('GET', 'POST'), 'edit_room_type/{id}', 'Admin\RoomTypeController@update')->where('id', '[0-9]+');
                Route::match(array('GET', 'POST'), 'status_check/{id}', 'Admin\RoomTypeController@chck_status')->where('id', '[0-9]+');
                Route::match(array('GET', 'POST'), 'bed_status_check/{id}', 'Admin\BedTypeController@chck_status')->where('id', '[0-9]+');
                Route::get('delete_room_type/{id}', 'Admin\RoomTypeController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Our Community Banners Routes
        Route::group(
            ['before' => 'manage_our_community_banners', 'middleware' => 'admin_can:manage_our_community_banners'],
            function () {
                Route::get('our_community_banners', 'Admin\OurCommunityBannersController@index');
                Route::match(array('GET', 'POST'), 'add_our_community_banners', 'Admin\OurCommunityBannersController@add');
                Route::match(array('GET', 'POST'), 'edit_our_community_banners/{id}', 'Admin\OurCommunityBannersController@update')->where('id', '[0-9]+');
                Route::get('delete_our_community_banners/{id}', 'Admin\OurCommunityBannersController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Host Banners Routes
        Route::group(
            ['before' => 'manage_host_banners', 'middleware' => 'admin_can:manage_host_banners'],
            function () {
                Route::get('host_banners', 'Admin\HostBannersController@index');
                Route::match(array('GET', 'POST'), 'add_host_banners', 'Admin\HostBannersController@add');
                Route::match(array('GET', 'POST'), 'edit_host_banners/{id}', 'Admin\HostBannersController@update')->where('id', '[0-9]+');
                Route::get('delete_host_banners/{id}', 'Admin\HostBannersController@delete')->where('id', '[0-9]+');
            }
        );

        Route::group(
            ['before' => 'manage_hotel_partners', 'middleware' => 'admin_can:manage_hotel_partners'],
            function () {
                Route::get('features', 'Admin\HotelPartnersController@index');
                Route::match(array('GET', 'POST'), 'add_features', 'Admin\HotelPartnersController@add');
                Route::match(array('GET', 'POST'), 'edit_features/{id}', 'Admin\HotelPartnersController@update')->where('id', '[0-9]+');
                Route::get('delete_features/{id}', 'Admin\HotelPartnersController@delete')->where('id', '[0-9]+');
            }
        );

        // Manage Help Routes
        Route::group(
            ['before' => 'manage_help', 'middleware' => 'admin_can:manage_help'],
            function () {
                Route::get('help_category', 'Admin\HelpCategoryController@index');
                Route::match(array('GET', 'POST'), 'add_help_category', 'Admin\HelpCategoryController@add');
                Route::match(array('GET', 'POST'), 'edit_help_category/{id}', 'Admin\HelpCategoryController@update')->where('id', '[0-9]+');
                Route::get('delete_help_category/{id}', 'Admin\HelpCategoryController@delete')->where('id', '[0-9]+');
                Route::get('help_subcategory', 'Admin\HelpSubCategoryController@index');
                Route::match(array('GET', 'POST'), 'add_help_subcategory', 'Admin\HelpSubCategoryController@add');
                Route::match(array('GET', 'POST'), 'edit_help_subcategory/{id}', 'Admin\HelpSubCategoryController@update')->where('id', '[0-9]+');
                Route::get('delete_help_subcategory/{id}', 'Admin\HelpSubCategoryController@delete')->where('id', '[0-9]+');
                Route::get('help', 'Admin\HelpController@index');
                Route::match(array('GET', 'POST'), 'add_help', 'Admin\HelpController@add');
                Route::match(array('GET', 'POST'), 'edit_help/{id}', 'Admin\HelpController@update')->where('id', '[0-9]+');
                Route::get('delete_help/{id}', 'Admin\HelpController@delete')->where('id', '[0-9]+');
                Route::post('ajax_help_subcategory/{id}', 'Admin\HelpController@ajax_help_subcategory')->where('id', '[0-9]+');
            }
        );
        // Manage Bed Type Routes
        Route::group(
            ['before' => 'manage_bed_type', 'middleware' => 'admin_can:manage_bed_type'],
            function () {
                Route::get('bed_type', 'Admin\BedTypeController@index');
                Route::match(array('GET', 'POST'), 'add_bed_type', 'Admin\BedTypeController@add');
                Route::match(array('GET', 'POST'), 'edit_bed_type/{id}', 'Admin\BedTypeController@update')->where('id', '[0-9]+');
                Route::get('delete_bed_type/{id}', 'Admin\BedTypeController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Pages Routes
        Route::group(
            ['before' => 'manage_pages', 'middleware' => 'admin_can:manage_pages'],
            function () {
                Route::get('pages', 'Admin\PagesController@index');
                Route::match(array('GET', 'POST'), 'add_page', 'Admin\PagesController@add');
                Route::match(array('GET', 'POST'), 'edit_page/{id}', 'Admin\PagesController@update')->where('id', '[0-9]+');
                Route::match(array('GET', 'POST'), 'page_status_check/{id}', 'Admin\PagesController@chck_status')->where('id', '[0-9]+');
                Route::get('delete_page/{id}', 'Admin\PagesController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Currency Routes
        Route::group(
            ['before' => 'manage_currency', 'middleware' => 'admin_can:manage_currency'],
            function () {
                Route::get('currency', 'Admin\CurrencyController@index');
                Route::match(array('GET', 'POST'), 'add_currency', 'Admin\CurrencyController@add');
                Route::match(array('GET', 'POST'), 'edit_currency/{id}', 'Admin\CurrencyController@update')->where('id', '[0-9]+');
                Route::get('delete_currency/{id}', 'Admin\CurrencyController@delete')->where('id', '[0-9]+');
            }
        );
        // Manage Coupon Code Routes
        Route::group(
            ['before' => 'manage_coupon_code', 'middleware' => 'admin_can:manage_coupon_code'],
            function () {
                Route::get('coupon_code', 'Admin\CouponCodeController@index');
                Route::match(array('GET', 'POST'), 'add_coupon_code', 'Admin\CouponCodeController@add');
                Route::match(array('GET', 'POST'), 'edit_coupon_code/{id}', 'Admin\CouponCodeController@update')->where('id', '[0-9]+');
                Route::get('delete_coupon_code/{id}', 'Admin\CouponCodeController@delete');
            }
        );
        // Manage Language Routes
        Route::group(
            ['before' => 'manage_language', 'middleware' => 'admin_can:manage_language'],
            function () {
                Route::get('language', 'Admin\LanguageController@index');
                Route::match(array('GET', 'POST'), 'add_language', 'Admin\LanguageController@add');
                Route::match(array('GET', 'POST'), 'edit_language/{id}', 'Admin\LanguageController@update')->where('id', '[0-9]+');
                Route::get('delete_language/{id}', 'Admin\LanguageController@delete')->where('id', '[0-9]+');
            }
        );


        // Manage Country Routes
        Route::group(
            ['before' => 'manage_country', 'middleware' => 'admin_can:manage_country'],
            function () {
                Route::get('country', 'Admin\CountryController@index');
                Route::match(array('GET', 'POST'), 'add_country', 'Admin\CountryController@add');
                Route::match(array('GET', 'POST'), 'edit_country/{id}', 'Admin\CountryController@update')->where('id', '[0-9]+');
                Route::get('delete_country/{id}', 'Admin\CountryController@delete')->where('id', '[0-9]+');
            }
        );

        Route::group(
            ['before' => 'manage_ngofee', 'middleware' => 'admin_can:manage_ngofee'],
            function () {
                Route::get('ngofees', 'Admin\NgofeeController@index');
                Route::get('add_ngo', 'Admin\NgofeeController@add_ngo');
                Route::get('edit_ngofee/{id}', 'Admin\NgofeeController@edit_ngo');
                Route::post('update_ngo', 'Admin\NgofeeController@update_ngo');
                Route::post('insert_ngofee', 'Admin\NgofeeController@insert_ngofee');
                Route::get('delete_ngofee/{id}', 'Admin\NgofeeController@delete_ngofee');
            }
        );



        Route::match(array('GET', 'POST'), 'api_credentials', 'Admin\ApiCredentialsController@index')->middleware(['admin_can:api_credentials']);
        Route::match(array('GET', 'POST'), 'payment_gateway', 'Admin\PaymentGatewayController@index')->middleware(['admin_can:payment_gateway']);

        Route::match(array('GET', 'POST'), 'email_settings', 'Admin\EmailController@index')->middleware(['admin_can:email_settings']);
        Route::match(array('GET', 'POST'), 'send_email', 'Admin\EmailController@send_email')->middleware(['admin_can:send_email']);

        Route::match(array('GET', 'POST'), 'site_settings', 'Admin\SiteSettingsController@index')->middleware(['admin_can:site_settings']);
        // Route::match(array('GET', 'POST'), 'theme_settings', 'Admin\ThemeSettingsController@index')->middleware(['admin_can:site_settings']);
        Route::match(array('GET', 'POST'), 'referral_settings', 'Admin\ReferralSettingsController@index')->middleware(['admin_can:manage_referral_settings']);

        Route::match(array('GET', 'POST'), 'fees', 'Admin\FeesController@index')->middleware(['admin_can:manage_fees']);
        Route::match(array('GET', 'POST'), 'host_service_fees', 'Admin\FeesController@host_service_fees');
          Route::match(array('GET', 'POST'), 'fees/airport_fee', 'Admin\FeesController@airport_fee');
        Route::match(array('GET', 'POST'), 'fees/host_penalty_fees', 'Admin\FeesController@host_penalty_fees');

        Route::match(array('GET', 'POST'), 'metas', 'Admin\MetasController@index')->middleware(['admin_can:manage_metas']);
        Route::match(array('GET', 'POST'), 'edit_meta/{id}', 'Admin\MetasController@update')->where('id', '[0-9]+');

        Route::match(array('GET', 'POST'), 'reviews', 'Admin\ReviewsController@index')->middleware(['admin_can:manage_reviews']);
        Route::match(array('GET', 'POST'), 'edit_review/{id}', 'Admin\ReviewsController@update')->where('id', '[0-9]+')->middleware(['admin_can:manage_reviews']);
        /*HostExperiencePHPCommentStart*/
        Route::match(array('GET', 'POST'), 'host_experiences_reviews', 'Admin\ReviewsController@host_experiences_reviews')->middleware(['admin_can:manage_reviews']);
        Route::match(array('GET', 'POST'), 'exp_edit_review/{id}', 'Admin\ReviewsController@exp_update')->where('id', '[0-9]+')->middleware(['admin_can:manage_reviews']);
        /*HostExperiencePHPCommentEnd*/
        
        Route::match(array('GET', 'POST'), 'wishlists', 'Admin\WishlistController@index')->middleware(['admin_can:manage_wishlists']);
        Route::match(array('GET', 'POST'), 'pick_wishlist/{id}', 'Admin\WishlistController@pick')->where('id', '[0-9]+');

        Route::match(array('GET', 'POST'), 'join_us', 'Admin\JoinUsController@index')->middleware(['admin_can:join_us']);
        /*HostExperiencePHPCommentStart*/
        // Host experiences Routes
        Route::group(
            ['before' => 'manage_host_experience_cities'],
            function () {
                Route::get('host_experience_cities', 'Admin\HostExperienceCitiesController@index');
                Route::match(array('GET', 'POST'), 'host_experience_cities/add', 'Admin\HostExperienceCitiesController@add');
                Route::match(array('GET', 'POST'), 'host_experience_cities/edit/{id}', 'Admin\HostExperienceCitiesController@update')->where('id', '[0-9]+');
                Route::get('host_experience_cities/delete/{id}', 'Admin\HostExperienceCitiesController@delete')->where('id', '[0-9]+');
            }
        );
        Route::group(
            ['before' => 'manage_host_experience_categories'],
            function () {
                Route::get('host_experience_categories', 'Admin\HostExperienceCategoriesController@index');
                Route::match(array('GET', 'POST'), 'host_experience_categories/add', 'Admin\HostExperienceCategoriesController@add');
                Route::match(array('GET', 'POST'), 'host_experience_categories/edit/{id}', 'Admin\HostExperienceCategoriesController@update')->where('id', '[0-9]+');
                Route::get('host_experience_categories/delete/{id}', 'Admin\HostExperienceCategoriesController@delete')->where('id', '[0-9]+');
                Route::get('feature_experience_categories/{id}', 'Admin\HostExperienceCategoriesController@feature')->where('id', '[0-9]+')->where('id', '[0-9]+');
            }
        );
        Route::group(
            ['before' => 'manage_host_experience_provide_items'],
            function () {
                Route::get('host_experience_provide_items', 'Admin\HostExperienceProvideItemsController@index');
                Route::match(array('GET', 'POST'), 'host_experience_provide_items/add', 'Admin\HostExperienceProvideItemsController@add');
                Route::match(array('GET', 'POST'), 'host_experience_provide_items/edit/{id}', 'Admin\HostExperienceProvideItemsController@update')->where('id', '[0-9]+');
                Route::get('host_experience_provide_items/delete/{id}', 'Admin\HostExperienceProvideItemsController@delete')->where('id', '[0-9]+');
            }
        );
        Route::get('host_experiences', 'Admin\HostExperiencesController@index');
        Route::get('host_experiences_reservation', 'Admin\ReservationsController@host_experiences');
        Route::get('host_experiences_inquiries', 'Admin\HostExperienceCategoriesController@host_experiences_inquiries');
        Route::match(array('GET', 'POST'), 'host_experiences/add', 'Admin\HostExperiencesController@add');
        Route::match(array('GET', 'POST'), 'host_experiences/edit/{id}', 'Admin\HostExperiencesController@update')->where('id', '[0-9]+');
        Route::get('host_experiences/delete/{id}', 'Admin\HostExperiencesController@delete')->where('id', '[0-9]+');
        Route::post('host_experiences/photo_delete/{id}', 'Admin\HostExperiencesController@photo_delete')->where('id', '[0-9]+');
        Route::post('host_experiences/provide_item_delete/{id}', 'Admin\HostExperiencesController@provide_item_delete')->where('id', '[0-9]+');
        Route::post('host_experiences/packing_list_delete/{id}', 'Admin\HostExperiencesController@packing_list_delete')->where('id', '[0-9]+');
        Route::post('host_experiences/refresh_calendar/{id}', 'Admin\HostExperiencesController@refresh_calendar')->where('id', '[0-9]+');
        Route::post('update_hostexperience_status', 'Admin\HostExperiencesController@update_hostexperience_status');
        /*HostExperiencePHPCommentEnd*/
        Route::group(
            ['before' => 'manage_disputes'],
            function () {
                Route::get('disputes', 'Admin\DisputesController@index');
                Route::get('dispute/details/{id}', 'Admin\DisputesController@details');
                Route::get('dispute/close/{id}', 'Admin\DisputesController@close');
                Route::post('dispute_admin_message/{id}', 'Admin\DisputesController@admin_message');
                Route::get('dispute_confirm_amount/{id}', 'Admin\DisputesController@confirm_amount');
            }
        );
        // Admin Panel User Route Permissions
        if (Schema::hasTable('site_settings')) {
            $admin_prefix=DB::table('site_settings')->where('name', 'admin_prefix')->first()->value;
        } else {
            $admin_prefix="admin";
        }
    }
);
Route::group(
    ['prefix' => '{ADMIN_URL}', 'middleware' => 'guest:admin'],
    function () {
        Route::get('login', 'Admin\AdminController@login');
        Route::get('get', 'Admin\AdminController@get');
    }
);
Route::post('{ADMIN_URL}/authenticate', 'Admin\AdminController@authenticate');
Route::get('{ADMIN_URL}/create', 'Admin\AdminController@create');
