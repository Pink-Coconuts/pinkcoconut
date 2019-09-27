<!DOCTYPE html>
<html  dir="{{ (((Session::get('language')) ? Session::get('language') : $default_language[0]->value) == 'ar') ? 'rtl' : '' }}" lang="{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"  xmlns:fb="http://ogp.me/ns/fb#"><!--<![endif]--><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" >
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0' >
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name = "viewport" content = "user-scalable=no, width=device-width">
<meta name="daterangepicker_format" content = "{{ $daterangepicker_format  }}">
<meta name="datepicker_format" content = "{{$datepicker_format }}"> 
<meta name="datedisplay_format" content = "{{ strtolower(DISPLAY_DATE_FORMAT) }}"> 
<meta name="php_date_format" content = "{{ PHP_DATE_FORMAT }}"> 
<!-- <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet"> -->
<!-- <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet"> -->
<link rel="dns-prefetch" href="https://maps.googleapis.com/">
<link rel="dns-prefetch" href="https://maps.gstatic.com/">
<link rel="dns-prefetch" href="https://mts0.googleapis.com/">
<link rel="dns-prefetch" href="https://mts1.googleapis.com/">
<link rel="shortcut icon" href="{{ $favicon }}">

<!--[if IE]><![endif]-->
<meta charset="utf-8">

    <!--[if IE 8]>
      {!! Html::style('css/common_ie8.css?v='.$version) !!}
      <![endif]-->
      <!--[if !(IE 8)]><!-->

      @if (!isset($exception))

      @if(!isset($exception) && Route::current()->uri() == '/' && $default_home =='')  

      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!} 
      {!! Html::style('css/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/themes.css?v='.$version) !!}        
      {!! Html::style('pcss?css=css/dynamic') !!} 
      {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/home.css?v='.$version) !!}
      {!! Html::style('css/main.css?v='.$version) !!} 
      {!! Html::style('css/styles.css?v='.$version) !!}   

      @endif
      @endif

   @if (isset($exception))
      @if ($exception->getStatusCode()  == '404')
      {!! Html::style('css/error_pages_pretzel.css?v='.$version) !!}
      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!} 
      {!! Html::style('css/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/themes.css?v='.$version) !!}        
      {!! Html::style('pcss?css=css/dynamic') !!} 
      {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/home.css?v='.$version) !!}
      {!! Html::style('css/main.css?v='.$version) !!} 
      {!! Html::style('css/styles.css?v='.$version) !!}
      @endif  
      @endif

      @if(@$default_home == 'two')

      {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/home.css?v='.$version) !!}
      {!! Html::style('css/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/themes.css?v='.$version) !!}   
      {!! Html::style('pcss?css=css/dynamic') !!}
      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!}   
      {!! Html::style('css/home_two.css?v='.$version) !!} 
      {!! Html::style('css/main.css?v='.$version) !!}        
      {!! Html::style('css/common_two.css?v='.$version) !!}
      {!! Html::style('css/styles.css?v='.$version) !!} 
      {!! Html::style('css/jquery.bxslider.css?v='.$version) !!}
      {!! Html::style('css/header_two.css?v='.$version) !!}
      
      @endif

      @if (!isset($exception))
      @if (Route::current()->uri() != '/')

      {!! Html::style('css/lightgallery.min.css?v='.$version) !!}  
      {!! Html::style('css/lightslider.min.css?v='.$version) !!} 
      {!! Html::style('css/nouislider.min.css?v='.$version) !!}
      {!! Html::style('css/styles.css?v='.$version) !!} 
      {!! Html::style('css/jquery.mCustomScrollbar.css?v='.$version) !!}
      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!} 
      {!! Html::style('css/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/themes.css?v='.$version) !!}        
      {!! Html::style('pcss?css=css/dynamic') !!} 
      {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/home.css?v='.$version) !!}
      {!! Html::style('css/main.css?v='.$version) !!} 
      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!}   
      {!! Html::style('css/jquery.bxslider.css?v='.$version) !!}
      {!! Html::style('css/common_two.css?v='.$version) !!}
      {!! Html::style('css/header_two.css?v='.$version) !!}
      {!! Html::style('css/home_two.css?v='.$version) !!}
      @endif
      @endif

      @if (Request::segment(1) == 'host' || Request::segment(1) == 'experiences')
      {!! Html::style('css/host_experiences/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/host_experiences/host_experience.css?v='.$version) !!}
      {!! Html::style('css/host_experiences/fonts.css?v='.$version) !!}   
      @endif

      @if (!isset($exception))
      @if (Route::current()->uri() == 'signup_action')
      {!! Html::style('css/signinup.css?v='.$version) !!}
      @endif

      @if(Route::current()->uri() != 'host')
      {!! Html::style('css/header_two.css?v='.$version) !!} 
      @endif

      @if (isset($exception))
      @if ($exception->getStatusCode()  == '404')
      {!! Html::style('css/error_pages_pretzel.css?v='.$version) !!}
      {!! Html::style('css/jquery.selectBoxIt.css?v='.$version) !!}
      {!! Html::style('css/daterangepicker.css?v='.$version) !!} 
      {!! Html::style('css/owl.carousel.min.css?v='.$version) !!}
      {!! Html::style('css/themes.css?v='.$version) !!}        
      {!! Html::style('pcss?css=css/dynamic') !!} 
      {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/home.css?v='.$version) !!}
      {!! Html::style('css/main.css?v='.$version) !!} 
      {!! Html::style('css/styles.css?v='.$version) !!}
      @endif
      @endif

      @if (Route::current()->uri() == 'dashboard')  
      {!! Html::style('css/host_dashboard.css?v='.$version) !!}
      {!! Html::style('css/dashboard.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'trips/current')

      @endif

      @if (Route::current()->uri() == 'trips/previous')   

      @endif

      @if (Route::current()->uri() == 'users/transaction_history')   

      @endif

      @if (Route::current()->uri() == 'users/security')   

      @endif

      @if (Route::current()->uri() == 'rooms/new')
      {!! Html::style('css/new.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'inbox' || Route::current()->uri() == 'disputes' || Route::current()->uri() == 'dispute_details/{id}')
      {!! Html::style('css/threads.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'reservation/change')
      {!! Html::style('css/alterations.css?v='.$version) !!}
      {!! Html::style('css/policy_timeline_v2.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'alterations/{code}')
      {!! Html::style('css/alterations.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'z/q/{id}')
      {!! Html::style('css/messaging.css?v='.$version) !!}
      {!! Html::style('css/tooltip.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'messaging/qt_with/{id}')
      {!! Html::style('css/messaging.css?v='.$version) !!}
      {!! Html::style('css/tooltip.css?v='.$version) !!}
      {!! Html::style('css/responsive_calendar.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'manage-listing/{id}/{page}')
      {!! Html::style('css/manage_listing.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'wishlists/picks' || Route::current()->uri() == 'wishlists/my' || Route::current()->uri() == 'wishlists/popular' || Route::current()->uri() == 'wishlists/{id}' || Route::current()->uri() == 'users/{id}/wishlists')
      {!! Html::style('css/wishlists.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'rooms/{id}')
      {!! Html::style('css/rooms_detail.css?v='.$version) !!}
      {!! Html::style('css/slider/nivo-lightbox.css?v='.$version) !!}
      {!! Html::style('css/slider/default.css?v='.$version) !!}
      {!! Html::style('css/jquery.bxslider.css?v='.$version) !!}    
      @endif

      @if (Route::current()->uri() == 'dispute_details/{id}')
      {!! Html::style('css/slider/nivo-lightbox.css?v='.$version) !!}
      {!! Html::style('css/slider/default.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'rooms/{id}' || Route::current()->uri() == 'experiences/{host_experience_id}')
      {!! Html::style('css/p3.css?v='.$version) !!}
      @endif    

      @if (Route::current()->uri() == 'rooms')
      {!! Html::style('css/index.css?v='.$version) !!}
      {!! Html::style('css/unlist_modal.css?v='.$version) !!}
      {!! Html::style('css/dashboard.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'reservation/itinerary')

      @endif

      @if (Route::current()->uri() == 'reservation/receipt')
      {!! Html::style('css/receipt.css?v='.$version) !!}
      {!! Html::style('css/receipt-print.css?v='.$version,['media'=>'print']) !!}
      @endif

      @if (Route::current()->uri() == 's' || Route::current()->uri() == 'wishlists/popular')
      {!! Html::style('css/map_search.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'payments/book/{id?}' || Route::current()->uri() == 'api_payments/book/{id?}'|| Route::current()->uri() == 'dispute_details/{id}')
      {!! Html::style('css/payments.css?v='.$version) !!}
      {!! Html::style('css/p4.css?v='.$version) !!}
      {!! Html::style('css/StyleSheet.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'reservation/requested')
      {!! Html::style('css/page5.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'users/edit')
      {!! Html::style('css/address_widget.css?v='.$version) !!}      
      {!! Html::style('css/phonenumbers.css?v='.$version) !!}
      {!! Html::style('css/edit_profile.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'users/show/{id}')
      {!! Html::style('css/profile.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'users/payout_preferences/{id}')
      {!! Html::style('css/payout_preferences.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'home/cancellation_policies')
      {!! Html::style('css/policy_timeline_v2.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'reviews/edit/{id}' || Route::current()->uri() == 'host_experience_reviews/edit/{id}')
      {!! Html::style('css/reviews.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'invite' || Route::current()->uri() == 'c/{username}')
      {!! Html::style('css/referrals.css?v='.$version) !!}
      @endif

      @if (Route::current()->uri() == 'help' || Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}')
      {!! Html::style('css/help.css?v='.$version) !!}
      {!! Html::style('css/jquery-ui.css?v='.$version) !!}
      @endif

      {!! Html::style('css/common1.css?v='.$version) !!}

      @endif

      @if(Session::get('language')=='ar')

      {!! Html::style('css/common_arr.css?v='.$version) !!}

      @endif

      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css">

      <style type="text/css">
        .ui-selecting { background: #FECA40; }
        .ui-selected { background: #F39814; color: white; }
  </style>


  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">



  <meta name="keywords" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'keywords') }}">


  <meta name="twitter:widgets:csp" content="on">


  @if (!isset($exception))
  @if (Route::current()->uri() == 'rooms/{id}')
  <meta property="og:image" content="{{ $result->photo_name }}">
  <meta itemprop="image" src="{{ $result->photo_name }}">
  <link rel="image_src" href="#" src="{{ $result->photo_name }}">
  @endif
  @if (Route::current()->uri() == 'experiences/{host_experience_id}')
  <title>{{ @$result->title.' - '.$site_name }}</title>
  <meta name="description" content="{{ @$result->city_details->name }} - {{ @$result->tagline }} - {{ @$result->about_you}}">
  <meta name="twitter:widgets:csp" content="on">
  <meta property="og:url"                content="{{ $result->link }}">
  <meta property="og:type"               content="website" />
  <meta property="og:title"              content="{{ @$result->title }}">
  <meta property="og:description"        content="{{ @$result->city_details->name }} - {{ @$result->tagline }} - {{ @$result->about_you}}">
  <meta property="og:image" content="{{ @$result->host_experience_photos[0]->og_image }}">
  <meta property="og:image:height" content="1280">
  <meta property="og:image:width" content="853">

  <meta itemprop="image" src="{{ @$result->photo_name }}">
  <link rel="image_src" href="#" src="{{ @$result->photo_name }}">
  <meta name="twitter:title" content="{{ @$result->title }}">
  <meta name="twitter:site" content="{{ SITE_NAME }}">
  <meta name="twitter:url" content="{{ $result->link }}">
  @endif

  @if (Route::current()->uri() == 'wishlists/{id}')
  <meta property="og:image" content="{{@$result[0]->saved_wishlists[0]->photo_name}}">
  <meta itemprop="image" src="{{@$result[0]->saved_wishlists[0]->photo_name}}">
  <link rel="image_src" href="#" src="{{ @$result[0]->saved_wishlists[0]->photo_name }}">
  @endif

  @endif
  <link rel="search" type="application/opensearchdescription+xml" href="#" title="">
  <title>{{ $title or Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }} {{ $additional_title or '' }}</title>
  <meta name="description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
  <meta name="mobile-web-app-capable" content="yes">

  <meta name="theme-color" content="#f5f5f5">


</head>

<body class="{{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'home_view v2 simple-header p1' : '') : '' }} {{ (!isset($exception)) ? (@$default_home != 'two' ? 'home-one' : '') : '' }} {{(!isset($exception)) ? (@Route::current()->uri() == 's' ? 'search_page' : '') : ''}}" ng-app="App">