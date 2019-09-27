@extends('template')

@section('main')
    <main id="site-content" role="main" ng-controller="home_owl" ng-cloak>
    	
<div class="hero shift-with-hiw js-hero cls_hero">
  <div class="hero__background" data-native-currency="ZAR" aria-hidden="true">

    @if($home_page_media == 'Slider')
        <ul class="rslides" id="home_slider">
          @foreach($home_page_sliders as $row)
          <li style="background:url({{ $row->image_url }});">
            <!-- <img alt="" src="{{ $row->image_url }}" width="100%"> -->
          </li>
          @endforeach
        </ul>
    @elseif($home_page_media == 'Video')
        <video id="pretzel-video" class="video-playing" preload="true" autoplay muted loop>
        @if($browser != 'chrome')
          <source src="{{ $home_video }}" id="mp4" type="video/mp4">
        @endif
          <source src="{{ $home_video_webm }}" id="webm" type="video/webm">
        </video>
    @endif
  </div>

  <div class="hero__content page-container page-container-full text-center" style="padding:0px !important;">
    <div class="va-container va-container-v va-container-h">
      <div class="va-middle">
   
        <h2 class="text-branding text-jumbo text-contrast space-1" style="padding:0px 15px;">
          Explore
          LGBTQ+ 
          Horizons
        </h2>
        <div class="h4 text-contrast space-4" style="padding:0px 15px;">
          {{ trans('messages.home.desc') }}
        </div>
        <div class="back-black">
        <div class="show-sm sm-search">
        <form id="simple-search" class="simple-search hide js-p1-simple-search">
<div class="alert alert-with-icon alert-error  hide space-2 js-search-error">
  <i class="icon alert-icon icon-alert-alt"></i>
        {{ trans('messages.home.search_validation') }}
</div>
    <label for="simple-search-location" class="screen-reader-only">
      {{ trans('messages.home.where_do_you_go') }}
    </label>
    <input type="text" placeholder="{{ trans('messages.home.where_do_you_go') }}" autocomplete="off" name="location" id="simple-search-location" class="input-large js-search-location">

    <div class="row row-condensed space-top-2 space-2">
      <div class="col-sm-6">
        <label for="simple-search-checkin" class="screen-reader-only">
          {{ trans('messages.home.checkin') }}
        </label>
        <input id="simple-search-checkin" type="text" name="checkin" class="input-large checkin js-search-checkin" placeholder="{{ trans('messages.home.checkin') }}">
      </div>
      <div class="col-sm-6">
        <label for="simple-search-checkout" class="screen-reader-only">
           {{ trans('messages.home.checkout') }}
          </label>
        <input id="simple-search-checkout" type="text" name="checkout" class="input-large checkout js-search-checkout" placeholder=" {{ trans('messages.home.checkout') }}">
      </div>
    </div>

    <div class="select select-block space-2">
      <label for="simple-search-guests" class="screen-reader-only">
        {{ trans('messages.home.no_of_guests') }}
      </label>
      <select id="simple-search-guests" name="guests" class="js-search-guests">
      @for($i=1;$i<=16;$i++)
        <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans_choice('messages.home.guest',$i) : $i.' '.trans_choice('messages.home.guest',$i) }} </option>
      @endfor
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-large btn-block">
      {{ trans('messages.home.no_of_guest') }}
    </button>
 </form>

  <div class="input-addon js-p1-search-cta" id="sm-search-field">
    <span class="input-stem input-large fake-search-field">
      {{ trans('messages.home.where_do_you_go') }}
    </span>
    <i class="input-suffix btn btn-primary icon icon-full icon-search"></i>
  </div>
        </div>
        </div>
        <a href="javascript:void(0);" class="btn hide-sm btn-contrast btn-large btn-semi-transparent js-show-how-it-works">
         Explore Destinations
        </a>
      </div>
    </div>
    <div class="hero__content-footer hide-sm">
      <div class="col-sm-12">
<div id="searchbar">
<div class="searchbar" data-reactid=".1">
    <form action="{{ url('s') }}" class="simple-search" method="get" id="searchbar-form" name="simple-search">
    <div class="saved-search-wrapper searchbar__input-wrapper">
      <label class="input-placeholder-group searchbar__location">
        <span class="input-placeholder-label screen-reader-only">{{ trans('messages.home.where_do_you_go') }}</span>
        <input class="menu-autocomplete-input form-inline location input-large input-contrast" placeholder="{{ trans('messages.home.where_do_you_go') }}" type="text" name="location" id="location" aria-autocomplete="both" autocomplete="off" value="">
        </label>
        <div class="searchbar__location-error hide">{{ trans('messages.home.search_validation') }}</div>
        <label class="input-placeholder-group searchbar__checkin">
        <span class="input-placeholder-label screen-reader-only">{{ trans('messages.home.checkin') }}</span>
        <input type="text" readonly="readonly" onfocus="this.blur()" id="checkin" class="checkin input-large input-contrast ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}">
        <input type="hidden" name="checkin">
        </label>
        <label class="input-placeholder-group searchbar__checkout">
        <span class="input-placeholder-label screen-reader-only"> {{ trans('messages.home.checkout') }}</span>
        <input type="text" id="checkout" onfocus="this.blur()" readonly="readonly" class="checkout input-large input-contrast ui-datepicker-target" placeholder=" {{ trans('messages.home.checkout') }}">
        <input type="hidden" name="checkout">
        </label>
        <label class="searchbar__guests">
        <span class="screen-reader-only">{{ trans('messages.home.no_of_guests') }}</span>
        <div class="select select-large">
        <select id="guests" name="guests">
        @for($i=1;$i<=16;$i++)
        <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans_choice('messages.home.guest',$i) : $i.' '.trans_choice('messages.home.guest',$i) }} </option>
        @endfor
        </select>
        </div>
        </label>
        <div id="autocomplete-menu-sbea76915" aria-expanded="false" class="menu hide" aria-role="listbox">
        <div class="menu-section">
        </div>
        </div>
        </div>
        <input type="hidden" name="source" value="bb">
        <button id="submit_location" type="submit" class="searchbar__submit btn btn-primary btn-large">{{ trans('messages.home.search') }}</button>
{!! Form::close() !!}

</div>
</div>

      </div>
    </div>
  </div>
</div>

<div class="hide show-md show-lg show-sm">
  
<section class="how-it-works how-it-works--overlay resp-how js-how-it-works" aria-hidden="true" style="top: 0px;display:none;height:361px;">
    <a href="javascript:void(0);" class="how-it-works__close panel-close js-close-how-it-works">
      <span class="screen-reader-only">
        {{ trans('messages.home.close') }} {{ trans('messages.home.how_it_works') }}
      </span>
    </a>

  <div class="page-container-responsive panel-contrast text-contrast">

    <h2 class="screen-reader-only">
      {{ trans('messages.home.how_it_works') }}
    </h2>

    <div class="row space-top-8 text-center">

      <div class="how-it-works__step how-it-works__step-one col-md-4">
        <div class="panel-body">
          <div class="how-it-works__image"></div>
          <h3>
            {{ trans('messages.home.discover_places') }}
          </h3>
          <p>
            {{ trans('messages.home.discover_places_desc') }}
          </p>
        </div>
      </div>

      <div class="how-it-works__step how-it-works__step-two col-md-4">
        <div class="panel-body">
          <div class="how-it-works__image"></div>
          <h3>
            {{ trans('messages.home.book_stay') }}
          </h3>
          <p>
            {{ trans('messages.home.book_stay_desc', ['site_name'=>$site_name]) }}
          </p>
        </div>
      </div>

      <div class="how-it-works__step how-it-works__step-three col-md-4">
        <div class="panel-body">
          <div class="how-it-works__image"></div>
          <h3>
            {{ trans('messages.home.travel') }}
          </h3>
          <p>
            {{ trans('messages.home.travel_desc') }}
          </p>
        </div>
      </div>

    </div>

  </div>
</section>

</div>

<div class="panel panel-dark pull-left" style="width:100%;border:none !important;">
  <div id="discovery-container">
    <div class="discovery-section hide page-container-responsive page-container-no-padding" id="discovery-saved-searches">
    </div>

    <div class="discovery-section hide page-container-responsive page-container-no-padding" id="weekend-recommendations">
    </div>
@if(count($host_banners)>0)
    <div class="discovery-section page-container-responsive page-container-no-padding" id="host-banners">
      <div class="row row-condensed space-top-8 ban-host">
          <div class="col-md-7 col-lg-8 col-sm-12 row-condensed">
              <ul class="host_banner_slides" id="host_banner_slider">
                  @foreach($host_banners as $host_banner)
                    <li>
                      <img alt="{{ $host_banner->title }}" src="{{ $host_banner->image_url }}" width="100%">
                    </li>
                  @endforeach
              </ul>
          </div>
          <input type="hidden" id="host_banners_count" value="{{$host_banners->count()-1}}" >
          <div class="col-md-5 col-lg-4 col-sm-12 host-banner-slider-content row-condensed">
              <ul class="host_banner_slides" id="host_banner_content_slider">
                  @foreach($host_banners as $k => $host_banner)
                                     <li class="host_banner_content_slider_item" id="host_banner_content_slider_item_{{$k}}" >
                      
                        <div class="host-banner-content text-left">
                          <div class="space-4 host-banner-content-title"><strong>{{$host_banner->title}}</strong></div>
                          <div class="space-4 host-banner-content-description">{{$host_banner->description}}</div >
                          <a class="btn btn-host-banner host-banner-content-btn" href="{{url($host_banner->link_url)}}" target="_blank" >{{$host_banner->link_title}}</a>
                        </div>
                      
                    </li>
                  @endforeach
              </ul>

          </div>
      </div>
    </div>
    @endif

    <div class="discovery-section explore_community exploer_banner1 page-container-responsive page-container-no-padding" id="discover-recommendations">

      <div class="section-intro text-center row-space-6 row-space-top-8" ng-if="home_city_explore.length!=0">
        <h2 class="row-space-1">
          <strong>{{ trans('messages.home.explore_world') }}</strong>
        </h2>
        <p class="text-lead">
          {{ trans('messages.home.explore_desc') }}
        </p>
      </div>
      <div class="discovery-tiles">
        <div class="row">
          <div ng-repeat="explore in home_city_explore " ng-init="j=6;x=7">
            <div  ng-class="$index % 10 == 0 || $index == j || $index == j+10 ? 'col-lg-8 col-md-6 col-sm-12 rm-padding-sm' : 'col-lg-4 col-md-6 col-sm-12 rm-padding-sm'" >
              <div class="discovery-card rm-padding-sm row-space-4 darken-on-hover " style="background-image:url('@{{ explore.image_url }}');">
                <a href="@{{ explore.search_url }}" class="link-reset" data-hook="discovery-card">
                  <div class="va-container va-container-v va-container-h">
                    <div class="va-middle text-center text-contrast">
                      <div class="h2">
                        <strong>
                         @{{ explore.name }}
                       </strong>
                     </div>
                   </div>
                 </div>
               </a>
             </div> 
           </div>
         </div>
       </div>
     </div>
</div>

 <div class="hero shift-with-hiw js-hero col-lg-12 col-md-12 col-sm-12 cls_btmhero" style="padding:0px; height:auto !important;">
<div class="hero__background_slider" data-native-currency="ZAR" aria-hidden="true" style="height: 100%;">
        <ul class="rslides" id="bottom_slider" style="height: 100%;">
          @foreach($bottom_sliders as $bottom_slider)
          <li>
            <img alt="" src="{{$bottom_slider->image_url}}" width="100%" style="height: 100%;">
            <div class="bot-slider-text">
            <h2 class="text-branding text-jumbo text-contrast space-1 over-head">
              {{$bottom_slider->title}}
               </h2>
            <div class="h4 text-contrast space-4 over-head">
             {{$bottom_slider->description}}
              </div>
             </div>
          </li>
          @endforeach
                    
        </ul>
              
  </div>

  </div>



<div class="col-lg-12 col-md-12 col-sm-12 our-community" style="background:#edefed;">
<div class="discovery-section explore_community page-container-responsive page-container-no-padding " style="padding:0px !important;">

    <div class="col-lg-12 nopad text-center" style="padding:0px !important;" ng-if="our_community.length!=0">
  
        <h2 class="row-space-1" style="padding:30px 0px 15px;"><strong>{{trans('messages.home.our_community')}}</strong></h2>
        <div class="col-lg-4 pos-rel com-img pad-left col-md-4 new_our_community" ng-repeat="our_community in our_community ">
          <div class="por_ab">
          <a class="com-link-img" href="@{{our_community.link}}" target="_blank" style="background: url(@{{our_community.image_url}});">
          </a>
          <div class="com-header">
            <!-- <a>Travelling</a> -->
          </div>
          <div class="com-sub">
            <h2 class="over-head" style="width:90% !important;">@{{our_community.title}}</h2>
            <p class="over-head" style="width:90% !important;" ng-bind-html="our_community.description"></p>
          </div>
        </div>
        </div>
    </div>
  </div>
</div>

@if(count($hotel_partners) > 0)
 <div class="col-lg-12 col-md-12 col-sm-12 our-partner p-0">
               <div class="">
                 <div class="discovery-section explore_community page-container-responsive page-container-no-padding" style="padding:0px;">
                   <div class="col-lg-12 nopad text-center" style="padding: 0;" ng-if="our_community.length!=0">
                    <h2 class="row-space-1" style="padding:30px 0px 15px;">
                      <strong>
                        {{trans('messages.home.our_partners')}}
                      </strong>
                    </h2>
                   
                    <div class="cls-community owl-carousel owl-theme">
                          @foreach($hotel_partners as $partner)
                          <div class="item">
                                <div class="cls_ourpartnerimg">
                                      <a class="cls_ourpartnerimga" href="{{ $partner->link}}" target="_blank" style="background: url({{ $partner->image_url}});">
                                      </a>
                                      <div class="com-header"></div>
                                     <!--  <div class="cls_ourpartnertext">
                                        <h2 class="" style="width:90% !important;">{{ $partner->name}}</h2>
                                        <p class="" style="width:90% !important;">{{ $partner->description}}</p>
                                      </div> -->
                               </div>
                        </div>
                        @endforeach
                    </div>
                </div>
              </div>
            </div>
          </div>
@endif



<!--[if (gt IE 8)|!(IE)]><!-->
<div id="belong-video-player" class="fullscreen-video-player fullscreen-video-player--hidden" aria-hidden="true">
  <div class="row row-table row-full-height">
    <div class="col-sm-12 col-middle text-center">
      <video preload="true" autoplay muted loop>
  <source src="{{ $home_video }}" type="video/mp4">
</video>

      <i id="play-button-belong" class="fullscreen-video-player__icon icon icon-video-play icon-white hide"></i>
      <a id="close-fullscreen-belong" class="fullscreen-video-player__close link-reset" href="{{URL::to('/')}}/#">
        <i class="icon icon-remove"></i>
        <span class="screen-reader-only">
          {{ trans('messages.home.exit_full_screen') }}
        </span>
      </a>
    </div>
  </div>
</div>
<div id="belo-video-player" class="fullscreen-video-player fullscreen-video-player--hidden" aria-hidden="true">
  <div class="row row-table row-full-height">
    <div class="col-sm-12 col-middle text-center">
      <video preload="true" autoplay muted loop>
  <source src="{{ $home_video }}" type="video/mp4">
</video>

      <i id="play-button-belo" class="fullscreen-video-player__icon icon icon-video-play icon-white hide"></i>
      <a id="close-fullscreen-belo" class="fullscreen-video-player__close link-reset" href="{{URL::to('/')}}/#">
        <i class="icon icon-remove"></i>
        <span class="screen-reader-only">
          {{ trans('messages.home.exit_full_screen') }}
        </span>
      </a>
    </div>
  </div>
</div>
    </main>
 @stop
