<style type="text/css">
  @media (max-width: 1099px){
    #room-type-tooltip{display: none !important;}
  }
</style>
<link rel="stylesheet" type="text/css" href="{{url('css/host_experiences/search.css')}}">
@extends('template')
@section('main')
<div class="flash-container"></div>
<main id="site-content" role="main" ng-controller="search-page" ng-init="setParams()">        
  <div class="home_filter exp_filter">
    <ul ng-cloak>
      <li>
        <button class="dbdate"  ng-class="filter_active('dates')" data-target-filter="dates" ng-click="update_opened_filter('dates')">
        <span ng-if="!is_filter_active('dates')">  {{ trans('messages.your_trips.dates') }}</span>
        <span ng-if="is_filter_active('dates')">@{{format_date(checkin, 'DD MMM')}}-@{{format_date(checkout, 'DD MMM')}}</span>
        </button>
      </li>
      <li class="hide"><button class=" dbdate"><span>12 Nov</span>-<span>14 Nov</span></button></li>
      <li class="frt">
        <button class="guestbut newt" id="gue1" data-target-div="guet1" data-target-filter="guests" ng-class="filter_active('guests')" ng-click="update_opened_filter('guests')">
          <span ng-if="!is_filter_active('guests')">{{ trans_choice('messages.home.guest',2) }}</span>
          <span ng-if="is_filter_active('guests')">@{{search_guest}} {{ trans_choice('messages.home.guest',2) }}</span>
        </button>
        <div class="guestbut1" id="guet1">
          <div class="nameguest nameguest1">
            <div class="forgu">
              <div class="ad">
                {{ trans_choice('messages.home.guest',1) }}
              </div>
                <div class="valunum" ng-init="search_guest={{$guest}}">
                  <button ng-disabled="search_guest==1" class="value-button" id="decrease" ng-click="search_guest=search_guest-1" value="Decrease Value">-</button>
                  <input type="text" class="guest-select" ng-value="search_guest+'+'" readonly="" />
                  <button ng-disabled="search_guest == 10" class="value-button" id="increase" ng-click="search_guest=search_guest-0+1" value="Increase Value">+</button>
                </div>
            </div>
            <div class="newed">
              <span class="newcan"><a href="javascript:void(0)" class="close_target" ng-click="reset_filters('guests')">{{ trans('messages.your_reservations.cancel') }}</a></span>
              <span class="newapp"><a href="javascript:void(0)" ng-click="apply_filters('guests')">{{ trans('messages.payments.apply') }}</a></span>
            </div>
          </div>
        </div>
      </li>
      <li class="frt hide" ng-init="currency_symbol = '{{ $currency_symbol }}'">
        <button class="guestbut newt2" id="gue3" data-target-div="guet3" ng-class="filter_active('prices')" ng-click="update_opened_filter('prices')">
          <span ng-if="!is_filter_active('prices')">  {{ trans('messages.inbox.price') }}</span>
          <span ng-if="is_filter_active('prices')"> @{{filter_btn_text('prices')}}</span>
        </button>
        <div class="guestbut1" id="guet3">
          <div class="nameguest nameguest3">
            <div class="example exap1">
              <div class="ajust">
                <span class="lang-chang-label">
                  <span class="price-min"> 
                    <span>{{ $currency_symbol }}</span>
                    <span class="price" id="min_text">{{ $min_price }}</span>
                  </span>
                  <input type="hidden"  class="min_value" id="min_value" value="{{ $min_price }}">
                </span>
                <span>-</span>
                <span class="text-right">
                  <span class="price-min">
                    <span >{{ $currency_symbol }}</span>
                    <span class="price" id="max_text">{{ $max_price }}+</span>
                  </span>
                  <input type="hidden" class="max_value" id="max_value" value="{{ $max_price }}">
                </span>
              </div>
              <div id="slider" class="price-range-slider p2-slider-new"></div>
            </div>
            <div class="newed">
              <span class="newcan"><a href="javascript:void(0)" class="close_target" ng-click="reset_filters('prices')">{{ trans('messages.your_reservations.cancel') }}</a></span>
              <span class="newapp"><a href="javascript:void(0)" ng-click="apply_filters('prices')">{{ trans('messages.payments.apply') }}</a></span>
            </div>
          </div>
        </div>
      </li>
      <li class="frt">
        <button class="guestbut" id="gue4" data-target-div="guet4" data-target-filter="category_types" ng-class="filter_active('category_types')" ng-click="update_opened_filter('category_types')">
          <span>{{ trans('messages.home.category') }}</span>
          <span ng-if="is_filter_active('category_types')">@{{filter_btn_text('category_types')}}</span>
        </button>
        <div class="guestbut1" id="guet4">
          <div class="nameguest nameguest4 tr_nert">
            <div class="net">
              @foreach($host_experience_categories as $row)
              <div class="col-middle-alt col-sm-6">
                <label class="checkbox">
                  <input class="room-type host_experience_category" id="category_{{$row->id}}" type="checkbox" value="{{ $row->id }}" {{in_array($row->id, $cat_type_selected) ? 'checked' : '' }} >
                  <span class="">{{ $row->name }}</span>
                </label>
              </div>
              @endforeach
            </div>
            <div class="newed newed1">
              <span class="newcan"><a href="javascript:void(0)" class="close_target" ng-click="reset_filters('category_types')">{{ trans('messages.your_reservations.cancel') }}</a></span>
              <span class="newapp"><a href="javascript:void(0)" ng-click="apply_filters('category_types')">{{ trans('messages.payments.apply') }}</a></span>
            </div>
          </div>
        </div>
      </li>
      <li class="frt1">
        <button class="gut5 gtnew"  ng-class="filter_active('dates')" data-target-filter="dates" ng-click="update_opened_filter('dates')">
        <span ng-if="!is_filter_active('dates')">  {{ trans('messages.your_trips.dates') }}</span>
        <span ng-if="is_filter_active('dates')">@{{format_date(checkin, 'DD MMM')}}-@{{format_date(checkout, 'DD MMM')}}</span>
        </button>
      </li>
      <li class="frt1">
        <button class="gut2 gtnew" data-target-filter="guests" ng-class="filter_active('guests')">
          <span ng-if="!is_filter_active('guests')">{{ trans_choice('messages.home.guest',2) }}</span>
          <span ng-if="is_filter_active('guests')">@{{search_guest}} {{ trans_choice('messages.home.guest',2) }}</span>
        </button>
      </li>
      <li class="frt1">
        <button class="gut3" data-target-filter="filters" ng-class="filter_active('filters')" ng-click="update_opened_filter('filters')">
          {{trans('messages.search.filters')}}
          <span ng-if="is_filter_active('filters')">@{{filter_btn_text('filters')}}</span>
        </button>
      </li>
    </ul>
  </div>

  <div class="map-search">
    <div class="sidebar">
      <div class="sidesear">
        <input type="hidden" id="location" value="{{ $location }}">
        <input type="hidden" id="lat" value="{{ $lat }}">
        <input type="hidden" id="long" value="{{ $long }}">
        <!-- Language Translate for inside Search maps -->
        <input type="hidden" id="current_language" value= "{{ trans('messages.search.search_name') }} ">
        <input type="hidden" id="redo_search_value" value= "{{ trans('messages.search.redo_search_name') }} ">
        <!-- Pagination next prev used-->
        <input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }} ">
        <input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }} ">
        <input type="hidden" id="viewport" value='{!! json_encode($viewport) !!}' ng-model="viewport">
        <div class="filter-div">
          <div class="filters collapse" style="bottom: 606px;">
            <div class="panel-header filters-section show-sm">
              {{ trans('messages.search.filters') }}
            </div>    
            <div class="filters-section intro-filter panel-body panel-light">
              <div class="row">
                <div class="col-lg-2 col-md-12 text-center-sm text-center-md row-space-sm-1 filter-label">
                  <label>{{ trans('messages.your_trips.dates') }}</label>
                </div>
                <form class="col-lg-9 trip-form right-pull">
                  <div class="row row-condensed">
                    <div class="col-md-4 col-sm-6 row-space-1-sm">
                      <label for="map-search-checkin" class="screen-reader-only">
                        {{ trans('messages.home.checkin') }}
                      </label>
                      <input  ng-model="checkin" readonly="readonly" autocomplete="off" type="text" ng-change="search_result();"  placeholder="{{ trans('messages.home.checkin') }}" ng-init="checkin = '{{ $checkin }}'" style="display:none;">
                      <input readonly="readonly" onfocus="this.blur()" autocomplete="off" id="checkin" type="text" class="checkin tour-target ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" value="{{ $st_date }}">
                    </div>
                    <div class="col-md-4 col-sm-6 row-space-1-sm">
                      <label for="map-search-checkout" class="screen-reader-only">
                        {{ trans('messages.home.checkout') }}
                      </label>

                      <input readonly="readonly"  onfocus="this.blur()" ng-model="checkout" autocomplete="off" type="text" ng-change="search_result();" class="checkout tour-target ui-datepicker-target " placeholder="{{ trans('messages.home.checkout') }}"
                      ng-init="checkout = '{{ $checkout }}'" style="display:none;">


                      <input readonly="readonly"  onfocus="this.blur()" autocomplete="off" id="checkout" type="text" class="checkout tour-target ui-datepicker-target " placeholder="{{ trans('messages.home.checkout') }}" value="{{ $end_date }}">

                    </div> 


                    <div class="container_mv0xzc-o_O-borderLeft_1ujj4hk-o_O-borderRight_1x9yfnn" style="width: 100%;">       
                      <div class="webcot-lg-datepicker webcot-lg-datepicker--jumbo">
                        <div class="dateRangePicker_e296pg-o_O-_ajz5vs">
                        </div>
                      </div>
                      <div class="focusUnderline_7131v4">
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <div class="select select-block">
                        <label for="guest-select" class="screen-reader-only">
                          {{ trans('messages.home.no_of_guests') }}
                        </label>
                        <select  ng-model="guests" class="guest-select" id="guest-select" data-prefill="" ng-change="search_result();" ng-init="guests = '{{ $guest }}'">
                          <option value="" disabled>{{ trans_choice('messages.home.guest',1) }}</option>
                          @for($i=1;$i<=16;$i++)
                          <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans_choice('messages.home.guest',$i) : $i.' '.trans_choice('messages.home.guest',$i) }} </option>
                          @endfor
                        </select>
                      </div>
                    </div> 
                  </div>

                  <div class="simple-dates-message-container hide">
                    <div class="text-kazan space-top-2">
                      <strong>
                        <i class="icon icon-currency-inr"></i>
                        {{ trans('messages.search.enter_dates') }}
                      </strong>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <div class="filters-section room-type-group intro-filter panel-body panel-light">
              <div class="row">
                <div class="col-lg-2 col-md-12 text-center-sm text-center-md filter-label">
                  <label>
                    {{ trans('messages.lys.room_type') }}
                    <span class="icon icon-question" id="room-type-tooltip"></span>
                    <div class="tooltip-room tooltip-left-middle" role="tooltip" data-trigger="#room-type-tooltip" data-sticky="true" aria-hidden="true" style="left: 120px; top: 80.925px;">
                      <dl class="panel-body">
                        @foreach($room_types as $row)
                        <dt>{{ $row->name }}</dt>
                        <dd>{{ $row->description }}</dd>
                        @endforeach

                      </dl>
                    </div>
                  </label>


                </div>

                <div class="col-lg-9 right-pull">
                  <div class="row row-condensed" id="room-options">
                    @foreach($room_type as $row=>$value)
                    <div class="col-middle-alt col-sm-4">
                      <label class="checkbox panel panel-dark">
                        <input class="pull-right room-type" type="checkbox" ng-model="room_type_{{ $row }}" value="{{ $row }}" ng-change="search_result();" ng-init="room_type_{{$row}} = {{ (in_array($row, $room_type_selected)) ? $row : 'null' }}" ng-true-value="{{$row}}" ng-false-value="null" >
                        @if($row == 1)
                        <i class="icon icon-entire-place h5 needsclick"></i>
                        @endif
                        @if($row == 2)
                        <i class="icon icon-private-room h5 needsclick"></i>
                        @endif
                        @if($row == 3)
                        <i class="icon icon-shared-room h5 needsclick"></i>
                        @endif
                        <!--default icons displaying for newly added room type. -->
                        @if($row >= 4) 
                        <i class="icon icon-home h5 needsclick"></i>
                        @endif
                        <span class="room-typs">{{ $value }}</span>
                      </label>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

<!--           <div class="filters-section intro-filter panel-body panel-light">
<div class="row">
<div class="col-lg-2 col-md-12 col-sm-4 text-center-sm text-center-md filter-label">
<label>{{ trans('messages.search.price_range') }}</label>
</div>
<div class="col-lg-9 col-md-12 right-pull col-sm-7">
<div class="example">
<div id="slider" class="price-range-slider p2-slider-new exp_price_range"></div>
<div class="row" style="margin-top: 20px;">
<div class="col-6 lang-chang-label">
<span class="price-min"> <span>{{ $currency_symbol }}</span> <span class="price" id="min_text">{{ $min_price }}</span> <span></span></span><input type="hidden" id="min_value" value="{{ $min_price }}"></div>
<div class="col-6 text-right"><span class="price-min">
  <span >{{ $currency_symbol }}</span><span class="price" id="max_text">{{ $max_price }}+</span><span></span></span><input type="hidden" id="max_value" value="{{ $max_price }}">
</div>
</div>
</div>
</div>
</div>
</div> -->

<div class="filters-section intro-filter panel-body panel-light " >

  <div class="row">
    <div class="col-lg-2 col-md-12 text-center-sm text-center-md space-sm-1 ">
      <div id="filter-section-header-ib-select">
        <i class="icon icon-instant-book icon-beach h4"></i>
        <span>{{trans('messages.lys.instant_book')}}</span>
      </div>
    </div>
    <div class="col-lg-9 col-md-11 right-pull">
      <div class="row row-condensed ib_p2_filter">
        <div class="pull-right text_padding">
          <label class="toggle toggle--color-babu" for="instant_book" ng-class="instant_book == 1 ? 'toggle--checked' : ''"  >
            <input type="checkbox" class="toggle__checkbox" value="1" name="instant_book" id="instant_book" ng-model="instant_book" ng-init="instant_book = '{{$instant_book}}'" ng-checked="instant_book == 1" ng-change="search_result();"  >
            <div class="toggle__track" ></div>
            <div class="toggle__handle" for="instant_book" >
              <span ng-bind-html="instant_book == 1  ? 'On' : 'Off'" ></span>
            </div>
          </label>
        </div>
        <div class="button_padding" style="padding-top:5px;">
          <span>{{trans('messages.search.instant_book_desc')}}</span>
        </div>
      </div>
    </div>
  </div>

</div>

<div id="search_more_filter">
  <div class="filters-section size-group toggle-group panel-body panel-light">
    <div class="row">
      <div class="col-lg-2 col-md-12 text-center-sm text-center-md filter-label">
        <label>{{ trans('messages.search.size') }}</label>
      </div>

      <form class="col-lg-9 right-pull">
        <div class="row row-condensed">
          <div class="col-md-4 col-sm-12 row-space-1">
            <div class="select select-block">
              <label for="map-search-min-bedrooms" class="screen-reader-only">
                {{ trans('messages.search.minimum_bedrooms') }}
              </label>
              <select name="min_bedrooms" ng-model="bedrooms" class="min-bedrooms" id="map-search-min-bedrooms" ng-init="bedrooms = '{{ $bedrooms }}'">
                <option value="">{{ trans('messages.lys.bedrooms') }}</option>
                @for($i=1;$i<=10;$i++)
                <option value="{{ $i }}" >
                  {{ $i}} {{ trans('messages.lys.bedrooms') }}
                </option>
                @endfor 
              </select>
            </div>
          </div>

          <div class="col-md-4 col-sm-12 row-space-1">
            <div class="select select-block">
              <label for="map-search-min-bathrooms" class="screen-reader-only">
                {{ trans('messages.search.minimum_bathroms') }}
              </label>
              <select name="min_bathrooms" ng-model="bathrooms" class="min-bathrooms input-medium" id="map-search-min-bathrooms" ng-init="bathrooms = '{{ $bathrooms }}'">
                <option value="">{{ trans('messages.lys.bathrooms') }}</option>

                @for($i=0.5;$i<=8;$i+=0.5)
                <option class="bathrooms" value="{{ $i }}" >
                  {{ ($i == '8') ? $i.'+' : $i }} {{ trans('messages.lys.bathrooms') }}
                </option>
                @endfor

              </select>
            </div>
          </div>

          <div class="col-md-4 col-sm-12">
            <div class="select select-block">
              <label for="map-search-min-beds" class="screen-reader-only">
                {{ trans('messages.search.minimum_beds') }}
              </label>
              <select name="min_beds" ng-model="beds" class="min-beds input-medium" id="map-search-min-beds" ng-init="beds = '{{ $beds }}'">
                <option value="">{{ trans('messages.lys.beds') }}</option>
                @for($i=1;$i<=16;$i++)
                <option value="{{ $i }}" >
                  {{ ($i == '16') ? $i.'+' : $i }} {{ trans('messages.lys.beds') }}
                </option>
                @endfor 
              </select>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="checkbox-group hosting_amenities filters-section panel-body panel-light toggle-group" data-name="hosting_amenities">
    <div class="row">
      <div class="col-lg-2 col-md-12 filter-label text-center-md">
        <label>{{ trans('messages.lys.amenities') }}</label>
      </div>

      <div class="col-lg-9 col-md-11 right-pull">
        <div class="row row-condensed filters-columns">
          @php $row_inc = 1 @endphp
          @foreach($amenities as $row_amenities)
          @if($row_inc <= 3)

          <div class="col-md-4">
            <label class="media checkbox text-truncate" title="{{ $row_amenities->name }}">
              <input type="checkbox" name="more_filter[]" value="{{ $row_amenities->id }}" class="pull-left amenities" id="map-search-amenities-{{ $row_amenities->id }}" ng-checked="{{ (in_array($row_amenities->id, $amenities_selected)) ? 'true' : 'false' }}">
              {{ $row_amenities->name }}
            </label>
          </div>
          @endif
          @php $row_inc++ @endphp
          @endforeach

        </div>

        <div class="filters-more collapse">
          <hr>
          <div class="row row-condensed filters-columns">
            @php $amen_inc = 1 @endphp

            @foreach($amenities as $row_amenities)
            @if($amen_inc > 3)

            <div class="col-md-4">
              <label class="media checkbox text-truncate" title="{{ $row_amenities->name }}">
                <input type="checkbox" name="more_filter[]" value="{{ $row_amenities->id }}" class="pull-left amenities" id="map-search-amenities-{{ $row_amenities->id }}" ng-checked="{{ (in_array($row_amenities->id, $amenities_selected)) ? 'true' : 'false' }}">
                {{ $row_amenities->name }}
              </label>
            </div>
            @endif
            @php $amen_inc++ @endphp
            @endforeach

          </div>
        </div>
      </div>

      <div class="col-md-1">
        <label class="show-more">
          <span>
            <i class="icon icon-chevron-down hide-sm"></i>
            <strong class="text-muted show-sm">+ {{ trans('messages.profile.more') }}</strong>
          </span>
          <span class="hide"><i class="icon icon-chevron-up"></i></span>
        </label>
      </div>
    </div>
  </div>


  <div class="panel-body panel-light show-sm toggle-group">
    <div class="sticky-apply-filters-placeholder"></div>
  </div>


</div>
</div>
<div class="filters-placeholder hide hide-sm"></div>
<a id="docked-filters"></a>
<div class="sidebar-header-placeholder"></div>
</div>
<div class="search-results toggle-hide back-change">
  <div class="outer-listings-container row-space-2" ng-cloak>

    <div class="listings-container">
      <div class="">

        <div class="col-sm-12 row-space-2 col-md-4 col-sm-6 col-xs-12 hover-list-img" ng-repeat="rooms in room_result.data" ng-cloak>
          <div class="listing list_view" ng-mouseover="on_mouse($index);" ng-mouseleave="out_mouse($index);" data-room_id="@{{rooms.id}}"  >

            <div class="panel-image listing-img">
              <div class="listing-description wl-data-@{{ rooms.id }}">
                <div class="summary">
                  <p>
                    @{{ rooms.about_you }}
                    <a href="{{ url('s') }}/@{{ location }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" id="tooltip-sticky-@{{ rooms.id }}" class="learn-more">{{ trans('messages.search.learn_more') }}</a>
                  </p>

                </div>
                <p class="address">@{{ rooms.host_experience_location.city }}</p>

              </div>
              <a href="@{{ rooms.link }}?checkin=@{{checkin}}&checkout=@{{checkout}}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="media-photo media-cover">
                <div class="listing-img-container media-cover text-center">
                  <img id="rooms_image_@{{ rooms.id}}"  ng-src="@{{ rooms.photo_name }}" class="img-responsive-height" alt="@{{ rooms.name }}">
                </div>
              </a>

              <div class="target-prev target-control rooms-slider block-link"  data-room_id="@{{rooms.id}}">
                <i class="icon icon-chevron-left icon-size-2 icon-white"></i>
              </div>

<!--                       <a href="@{{ rooms.link }}?checkin=@{{checkin}}&checkout=@{{checkout}}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label">
  <div>
    <sup class="h6 text-contrast"><span ng-bind-html="rooms.currency.symbol"></span></sup>
    <span class="h3 text-contrast price-amount"> @{{ rooms.price_per_guest }}</span>
    <sup class="h6 text-contrast"></sup>
  </div>

</a> -->
<div class="target-next target-control rooms-slider block-link" data-room_id="@{{rooms.id}}">
  <i class="icon icon-chevron-right icon-size-2 icon-white"></i>
</div>

<div class="panel-overlay-top-right wl-social-connection-panel">
  <span class="rich-toggle wish_list_button wishlist-button not_saved">
    <input type="checkbox" id="wishlist-widget-@{{ rooms.id }}" name="wishlist-widget-@{{ rooms.id }}" data-for-hosting="@{{ rooms.id }}" ng-checked="rooms.saved_wishlists">
    <label for="wishlist-widget-@{{ rooms.id }}" class="hide-sm">
      <span class="screen-reader-only">Wishlist</span>
      <i class="icon icon-heart icon-rausch icon-size-2 rich-toggle-checked"></i>
      <i class="icon icon-heart wishlist-heart-unchecked icon-size-2 rich-toggle-unchecked"></i>
      <i class="icon icon-heart-alt icon-white icon-size-2"  data-what="{{$current_refinement}}" id="wishlist-widget-icon-@{{ rooms.id }}" data-room_id="@{{ rooms.id }}" data-img="@{{ rooms.photo_name }}" data-name="@{{ rooms.title }}" data-address="@{{ rooms.host_experience_location.city }}" data-price="@{{ rooms.currency.symbol }}@{{ rooms.session_price }}" data-review_count="" data-host_img="@{{ rooms.user.profile_picture.src }}" data-star_rating="" data-summary="@{{ rooms.about_you }}" data-room_type="" data-property_type_name="" data-person_capacity_string="" data-bedrooms_string="" data-space_tab_content="" data-neighborhood_tab_content=""></i>
    </label>
  </span>

</div>

</div>

<div class="panel-body panel-card-section">
  <div class="media nt_media">
<!--                         <a href="{{ url('users/show/') }}/@{{ rooms.user_id }}" class="media-photo-badge pull-right card-profile-picture card-profile-picture-offset">
    <div class="media-photo media-round">
      <img ng-src="@{{ rooms.user.profile_picture.src }}" alt="">
    </div>
  </a> -->

   <div class="pull-left category_city nt_ctcity">
    <span class="pull-left">@{{ rooms.category_details.name }}</span>
    <span class="pull-left dot-cont">·</span>
    <span class="pull-left">@{{ rooms.host_experience_location.city }}</span>
  </div>
  <a href="@{{ rooms.link }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="text-normal">
    <h3 title="@{{ rooms.title }}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1">
      @{{ rooms.title }}
    </h3>
  </a>
  
  <div class="exp_price">
    <span ng-bind-html="rooms.currency.symbol"></span> 
    @{{ rooms.session_price }} 
    {{ trans("messages.wishlist.per_guest") }}
  </div>
  <div itemprop="description" class="pull-left text-muted listing-location text-truncate nt_star"><a href="@{{ rooms.link }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" class="text-normal link-reset pull-left">
    <span class="pull-left" ng-show="rooms.overall_star_rating">
      <span class="pull-left" ng-bind-html="rooms.overall_star_rating"></span>
    </span>
    <span class="pull-left" ng-show="rooms.reviews_count">
      <span class="pull-left dot-cont">·</span>
      <span class="pull-left r-count">@{{ rooms.reviews_count }} </span><span class="pull-left r-label">{{ trans_choice('messages.header.review',1) }}@{{ (rooms.reviews_count > 1) ? 's' : '' }}
    </span></span>
  </a>
</div>
</div>

</div>
</div>
</div>

<h2 ng-hide="room_result.data.length" class="text-center" id="no_results" ng-cloak>
  {{ trans('messages.search.no_results_found') }}
</h2>

</div>

</div>

</div>

<div class="results-footer">

  <div class="pagination-buttons-container row-space-8" ng-cloak>
    <div class="results_count">
      <p>
        <span ng-if="room_result.to != 0">
          @{{ room_result.from }} – 
        </span>

        @{{ room_result.to }} {{ trans('messages.search.of') }} @{{ room_result.total }} {{ trans_choice('messages.home.experience',1) }}
      </p>
    </div>
    <div>
      <posts-pagination ng-if="room_result.total != 0" ></posts-pagination>
    </div>
  </div>

 <!--  <div class="nearby-links" ng-style="{'visibility': room_result.data[0].host_experience_location.country_name == '' ?'visible':'hidden'}">
    <div class="breadcrumbs row-space-top-1" itemprop="breadcrumb" ng-cloak>
      <span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{ url('s?location=') }}@{{ room_result.data[0].host_experience_location.country_name }}" itemprop="url">
          <span itemprop="title" ng-if= "room_result.data[0].host_experience_location.country_name != ''" >@{{ room_result.data[0].host_experience_location.country_name }}<i class="icon icon-chevron-right breadcrumb-spacer"></i>
            <i class="icon icon-chevron-left breadcrumb-spacer1" style="display: none;"></i></span>

          </a>

        </span>
        <span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="{{ url('s?location=') }}@{{ room_result.data[0].host_experience_location.state }}-@{{ room_result.data[0].host_experience_location.country_name }}" itemprop="url">
            <span itemprop="title" ng-if= "room_result.data[0].host_experience_location.state != ''" >@{{ room_result.data[0].host_experience_location.state }}<i class="icon icon-chevron-right breadcrumb-spacer"></i>
              <i class="icon icon-chevron-left breadcrumb-spacer1" style="display: none;"></i></span>

            </a>

          </span>
          <span>@{{ room_result.data[0].host_experience_location.city }}</span>
        </div>
      </div> -->

    </div>


  </div>
 <!-- <div class="container_1r1rje newbt" style="bottom:20px;" ><div class="container_1xmexls" >
  <button type="button" class="button_ipunk" ><span>Map</span></button>
  <div class="panel-btn-sm hide filters-btn fixed">
    <button class="js-small-filter-toggle btn btn-large btn-block btn-primary">{{ trans('messages.search.filters') }}</button>
  </div>
</div>

</div> -->

 <div class="container_1r1rje newbt" style="bottom:20px;" ><div class="container_1xmexls" >
  <button type="button" class="button_ipunk" ><span>{{ trans('messages.search.map') }}</span></button>
 <!--  <button type="button" class="button_ipunk-result" ><span>Results</span></button> -->
  <button type="button" class="button_ipunk4 gut3"><span>{{ trans('messages.search.filters') }}</span></button></div></div>
  <div class="panel-btn-sm hide filters-btn fixed">
    <button class="js-small-filter-toggle btn btn-large btn-block btn-primary">{{ trans('messages.search.filters') }}</button>
  </div>
<!-- 
<button class="hide-sm btn footer-toggle btn_alt btn_padd">
  <span class="open-content">
    <i class="icon icon-globe ico_left"></i> {{ trans('messages.search.language_currency') }}
  </span>
  <span class="close-content"><i class="icon icon-remove ico_left"></i> {{ trans('messages.home.close') }}</span>
</button> -->
</div>
</div>
<button class="hide-sm btn footer-toggle btn_alt btn_padd">
  <span class="open-content">
    <i class="icon icon-globe ico_left"></i> {{ trans('messages.search.language_currency') }}
  </span>
  <span class="close-content"><i class="icon icon-remove ico_left"></i> {{ trans('messages.home.close') }}</span>
</button>
</div>

<div id="js-ib-icon-tooltip" class="tooltip tooltip-left-middle ib-icon-tooltip" role="tooltip" aria-hidden="true">
  <div class="panel-body">
    <h5>{{ trans('messages.lys.instant_book') }}</h5>
    <p class="text-muted">
      {{ trans('messages.search.instant_book_desc') }}
    </p>
  </div>
</div>

<div class="modal-container modal-transitions wl-modal__modal hide">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="wl-modal">
          <div class="row row-margin-zero">
            <!-- <div class="hide-sm hide-md col-lg-7 wl-modal__col"> -->
            <div class="col-lg-7 wl-modal__col">
              <div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url();">
              </div>
              <div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
                <div class="va-container media">
                  <img class="pull-left host-profile-img media-photo media-round space-2" height="67" width="67" src="">
                  <div class="media-body va-middle">
                    <div class="h4 space-1 wl-modal-listing__name"></div>
                    <div class="wl-modal-listing__rating-container">
                      <span class="hide">
                        <div class="star-rating-wrapper">
                          <div class="star-rating" content="0">
                            <div class="foreground">
                              <span> </span>
                            </div>
                            <div class="background">
                              <span>
                                <span>
                                  <i class="icon-star icon icon-light-gray icon-star-big">
                                  </i>
                                  <span> </span>
                                </span>
                                <span>
                                  <i class="icon-star icon icon-light-gray icon-star-big">
                                  </i>
                                  <span> </span>
                                </span>
                                <span>
                                  <i class="icon-star icon icon-light-gray icon-star-big">
                                  </i>
                                  <span> </span>
                                </span>
                                <span>
                                  <i class="icon-star icon icon-light-gray icon-star-big">
                                  </i>
                                  <span> </span>
                                </span>
                                <span>
                                  <i class="icon-star icon icon-light-gray icon-star-big">
                                  </i>
                                  <span> </span>
                                </span>
                              </span>
                            </div>
                          </div>
                          <span> </span>
                          <span class="h6 hide">
                            <small>
                              <span>(</span>
                              <span>
                              </span>
                              <span>)</span>
                            </small>
                          </span>
                        </div>
                        <span> · </span>
                        <span class="wl-modal-listing__text">
                        </span>
                        <span> · </span>
                      </span>
                      <span class="wl-modal-listing__address wl-modal-listing__text"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5 wl-modal__col">
              <div class="panel-header panel-light wl-modal__header">
                <div class="va-container va-container-h va-container-v">
                  <div class="va-middle">
                    <div class="pull-left h3">{{ trans('messages.wishlist.save_to_wishlist') }}</div>
                    <a class="modal-close wl-modal__modal-close">
                    </a>
                  </div>
                </div>
              </div>
              <div class="wl-modal-wishlists">
                <div class="panel-body panel-body-scroll wl-modal-wishlists__body wl-modal-wishlists__body--scroll">
                  <div class="text-lead text-gray space-4 hide">{{ trans('messages.wishlist.save_fav_list') }}</div>
                  <div class="wl-modal-wishlist-row clickable" ng-repeat="item in wishlist_list" ng-class="(item.saved_id) ? 'text-dark-gray' : 'text-gray'" ng-click="wishlist_row_select($index)" id="wishlist_row_@{{ $index }}">
                    <div class="va-container va-container-v va-container-h">
                      <div class="va-middle text-left text-lead wl-modal-wishlist-row__name">
                        <span> </span>
                        <span>@{{ item.name }}</span>
                        <span> </span>
                      </div>
                      <div class="va-middle text-right">
                        <div class="h3 wl-modal-wishlist-row__icons">
                          <i class="icon icon-heart-alt icon-light-gray wl-modal-wishlist-row__icon-heart-alt" ng-hide="item.saved_id"></i>
                          <i class="icon icon-heart icon-rausch wl-modal-wishlist-row__icon-heart" ng-show="item.saved_id"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-beach panel-body wl-modal-wishlists__body hide">
                  <small>
                  </small>
                </div>
                <div class="panel-footer wl-modal-footer clickable">
                  <form class="wl-modal-footer__form hide">
                    <strong>
                      <div class="pull-left text-lead va-container va-container-v">
                        <input type="text" class="wl-modal-footer__text wl-modal-footer__input" autocomplete="off" id="wish_list_text" value="" placeholder="Name Your Wish List" required>
                      </div>
                      <div class="pull-right">
                        <button id="wish_list_btn" class="btn btn-flat wl-modal-wishlists__footer__save-button btn-contrast">{{ trans('messages.wishlist.create') }}</button>
                      </div>
                    </strong>
                  </form>
                  <div class="text-rausch va-container va-container-v va-container-h">
                    <div class="va-middle text-lead wl-modal-footer__text">{{ trans('messages.wishlist.create_new_wishlist') }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- <div class="map hide-sm-view">

  <div id="map_canvas" role="presentation" class="map-canvas" style="position: relative; overflow: hidden; transform: translateZ(0px); background-color: rgb(164, 221, 245);">
  </div>
   <div class="container_1r1rje" style="bottom:20px;" ><div class="container_1xmexls" >
  <button type="button" class="button_ipunk-result" ><span>Results</span></button>

  <div class="panel-btn-sm hide filters-btn fixed">
    <button class="js-small-filter-toggle btn btn-large btn-block btn-primary">{{ trans('messages.search.filters') }}</button>
  </div>
</div>
</div>
</div> -->

<div class="map hide-sm-view">

  <div id="map_canvas" role="presentation" class="map-canvas" style="position: relative; overflow: hidden; transform: translateZ(0px); background-color: rgb(164, 221, 245);">
  </div>
   <div class="container_1r1rje newbt" style="bottom:20px;" ><div class="container_1xmexls" >
  <button type="button" class="button_ipunk-result" ><span>{{ trans('messages.search.results') }}</span></button>
  <button type="button" class="button_ipunk4 gut3"><span>{{ trans('messages.search.filters') }}</span></button></div></div>
  <div class="panel-btn-sm hide filters-btn fixed">
    <button class="js-small-filter-toggle btn btn-large btn-block btn-primary">{{ trans('messages.search.filters') }}</button>
  </div>
</div>

<div class="morefit3">
  <div class="cls12"><div class="allfill" ng-click="reset_filters('filters')"><i class="fa fa-times clsfa3" aria-hidden="true"></i></div>
  <div class="allf">All Filters<span>(@{{filter_btn_text('filters_count')}})</span><span><a href="javascript:void(0)" ng-click="reset_filters('filters')" class="clsfa3">{{ trans('messages.your_reservations.cancel') }}</a></span></div>
 </div>

  <div class="pricetag hide" style="margin-top: 20px;">
    <h4>{{ trans('messages.inbox.price') }}</h4>
    <div class="example exap1">
      <div class="ajust">
        <span class="lang-chang-label">
          <span class="price-min"> 
            <span>{{ $currency_symbol }}</span>
            <span class="price" id="min_text1">{{ $min_price }}</span>
          </span>
          <input type="hidden" class="min_value" id="min_value1" value="{{ $min_price }}">
        </span>
        <span>-</span>  
        <span class="text-right">
          <span class="price-min">
            <span >{{ $currency_symbol }}</span>
            <span class="price" id="max_text1">{{ $max_price }}+</span>
          </span>
          <input type="hidden" class="max_value" id="max_value1" value="{{ $max_price }}">
        </span>
      </div>
      <div id="mobslider" class="price-range-slider p2-slider-new"></div>
    </div>
  </div>
<div class="ecp_ho1">
  <h4>{{ trans('messages.home.category') }}</h4>
  <div class="net">
    @foreach($host_experience_categories as $row)
    <div class="col-middle-alt col-xs-12 col-md-12 col-sm-12">
      <label class="checkbox">
        <input class="room-type host_experience_category" type="checkbox" id="mob_category_{{$row->id}}" value="{{ $row->id }}" {{in_array($row->id, $cat_type_selected) ? 'checked' : ''}} >
        <span class="">{{ $row->name }}</span>
      </label>
    </div>
    @endforeach
  </div>
</div>
<!-- <div class="morebot">
  <div class="holebot">
    <a href="javascript:void(0)" ng-click="apply_filters('filters')" class="seehome">{{ trans('messages.wishlist.see_experiences') }}</a>
  </div>
</div> -->
<div class="morebot">
  <div class="holebot">
    <a href="javascript:void(0)" ng-click="apply_filters('guests')" class="seehome">{{ trans('messages.wishlist.see_result') }}</a>
  </div>
</div>
</div>


<div class="morefit2">
  <div class="cls12"><div class="allfill" ng-click="reset_filters('guests')"><i class="fa fa-times clsfa2" aria-hidden="true"></i></div>
  <div class="allf text-center">{{ trans_choice('messages.home.guest',2) }}<span><a class="text-right clsfa2" href="javascript:void(0)" ng-click="reset_filters('guests')" >{{ trans('messages.your_reservations.cancel') }}</a></span></div>
</div>
<div class="adl">
  <div class="forgu">
    <div class="ad">{{ trans_choice('messages.home.guest',1) }}</div>

    <div class="valunum">
      <button ng-disabled="search_guest==1" class="value-button" id="decrease" ng-click="search_guest=search_guest-1" value="Decrease Value">-</button>
      <input type="text" class="guest-select" ng-value="search_guest+'+'" readonly="" />
      <button ng-disabled="search_guest == 10" class="value-button" id="increase" ng-click="search_guest=search_guest-0+1" value="Increase Value">+</button>
    </div>
  </div>
</div>
<div class="morebot">
  <div class="holebot">
    <a href="javascript:void(0)" ng-click="apply_filters('guests')" class="seehome">{{ trans('messages.wishlist.see_result') }}</a>
  </div>
</div>
</div>

<div class="morefit5">
  <div class="cls12"><div class="allfill" ng-click="reset_filters('guests')"><i class="fa fa-times clsfa5" aria-hidden="true"></i></div>
  <div class="allf text-center">{{trans('messages.header.when')}}<span><a class="text-right clsfa5" href="javascript:void(0)" ng-click="reset_filters('dates')">{{ trans('messages.your_reservations.cancel') }}</a></span>
  <span><a class="text-right clsfa5 mobile_date_clear" href="javascript:void(0)">{{ trans('messages.payments.clear') }}</a></span>
  </div>
</div>
<div class="adl">
  <div class="forgu" id="daterangepicker_modal_div">
   
  </div>
</div>
<div class="morebot">
  <div class="holebot">
    <a href="javascript:void(0)" ng-click="apply_filters('dates')" class="seehome">{{ trans('messages.wishlist.see_result') }}</a>
  </div>
</div>
</div>
</main>


@stop

@push('scripts')
<script type="text/javascript">
  var min_slider_price = {!! $default_min_price !!};
  var max_slider_price = {!! $default_max_price !!};
  var min_slider_price_value = {!! $min_price !!};
  var max_slider_price_value = {!! $max_price !!};
  $(document).ready(function() {

    $("#wish_list_text").keyup(function(){
      $('#wish_list_btn').prop('disabled', true);
      var v_value =  $(this).val();
      var len =v_value.trim().length;
      if (len == 0)
      {
        $('#wish_list_btn').prop('disabled', true);
      }
      else{
        $('#wish_list_btn').prop('disabled', false);
      }
    });
  });
  var APPLY_LANG = "{{ trans('messages.payments.apply') }}"
  var CLEAR_LANG = "{{ trans('messages.payments.clear') }}"
</script>
<style type="text/css">
  .listing-name {
    width: 100%;
  }
</style>
<script src="{{url('js/host_experiences/search1.js')}}"></script>
<script src="{{url('js/host_experiences/search_new.js')}}"></script>
@endpush