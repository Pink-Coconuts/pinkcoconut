@extends('template')

@section('main')



<main id="site-content" role="main" ng-controller="rooms_detail">

  <div class="subnav-container">

    <div data-sticky="true" data-transition-at="#summary" aria-hidden="true" class="subnav section-titles">
      <div class="page-container-responsive">
        <ul class="subnav-list">
          <li>
            <a href="#photos" aria-selected="true" class="subnav-item">
              {{ trans_choice('messages.header.photo',2) }}
            </a>
          </li>
          <li>
            <a href="#summary" class="subnav-item" data-extra="#summary-extend" >
              {{ trans('messages.rooms.about_this_listing') }}
            </a>
          </li>
          <li>
            <a href="#reviews" class="subnav-item">
              {{ trans_choice('messages.header.review',2) }}
            </a>
          </li>
          <li>
            <a href="#host-profile" class="subnav-item">
              {{ trans('messages.rooms.the_host') }}
            </a>
          </li>
          <li>
            <a href="#neighborhood" class="subnav-item">
              {{ trans('messages.your_trips.location') }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div id="og_pro_photo_prompt" class="container"></div>

  <div id="room" itemscope="" itemtype="http://schema.org/Product">

    <div id="photos" class="with-photos with-modal">

        <span class="cover-img-container img-box1 photo-gallery1" data-hook="cover-img-container">
        <a href="{{ url('rooms/'.$result->id.'/slider') }}" oncontextmenu="return false" class="gallery" data-lightbox-type="iframe"> <div  id ="frontimage_slider" class="cover-img" data-hook="img-lg" style="background-image:url('{{ $result->banner_photo_name }}')">
        </div></a>
      </div>
    </span>

    <div id="summary" class="panel room-section">
      <div class="page-container-responsive">
        <div class="row">
          <div class="col-lg-8 lang-chang-label col-sm-12">

            <div class="row-space-4 row-space-top-4 summary-component">
              <div class="row">

                <div class="col-md-3 space-sm-4 text-center space-sm-2 lang-chang-label">

                  <div class="media-photo-badge">

                    <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo media-round">

                      <img alt="User Profile Image" class="host-profile-image" data-pin-nopin="true" height="115" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="115">
                    </a>
                    <a href="" class="link-reset text-wrap">{{ $result->users->first_name }}</a>
                  </div>

                </div>

                <div class="col-md-9">


                  <h1 itemprop="name" class="overflow h3 row-space-1 text-center-sm" id="listing_name">
                    {{ $result->name }}
                  </h1>


                  <div id="display-address" class="row-space-2 text-muted text-center-sm" itemprop="aggregateRating" itemscope="">

                    <a href="" class="link-reset"><span class="lang-chang-label">{{$result->rooms_address->city}} @if($result->rooms_address->city !=''),</span><span class="lang-chang-label">@endif {{$result->rooms_address->state}} @if($result->rooms_address->state !=''),</span><span class="lang-chang-label">@endif {{$result->rooms_address->country_name}}</span></a>
                    &nbsp;
                    @if($result->overall_star_rating)
                    <a href="#reviews" class="link-reset hide-sm">
                      <div class="star-rating-wrapper">
                        {!! $result->overall_star_rating !!}
                        <span> </span>
                        <span>
                          <small>
                            <span>(</span>
                            <span>{{ $result->reviews->count() }}</span>
                            <span>)</span>
                          </small>
                        </span>
                      </div>
                    </a>
                    @endif
                  </div>

                  <div class="row row-condensed text-muted text-center roomtype-img">
                    <div class="col-md-3 col-sm-3 col-xs-4 roomty">
                       <i class="room_list_img_icon"><img src="{{ url($result->room_type_data->image_name) }}" alt="{{ $result->room_type_name }}"></i>
                      <div class="numfel"> {{ $result->room_type_name }}
                       @if($result->is_shared == 'Yes')
                       <p class="space-1"><em class="text-beach h6" >{{trans('messages.shared_rooms.shared_room_notes')}}</em></p>
                       @endif
                     </div>
                   </div>
                   <div class="col-md-3 col-sm-3 col-xs-4 roomty">
                    <i class="icon icon-group icon-size-2"></i>
                    <div class="numfel">{{ $result->accommodates }} {{ trans_choice('messages.home.guest',2) }}</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-4 roomty">
                    <i class="icon icon-double-bed icon-size-2"></i>
                    <div class="numfel">{{ $result->beds }} {{ trans_choice('messages.lys.bed',$result->beds) }}</div>
                  </div>


    <!-- </div>

 

   

    <div class="col-md-10"> -->
      <div class="row row-condensed text-muted text-center roomtype-img">
        <div class="col-sm-3">

        </div>
        <div class="col-sm-3">

        </div>
        <div class="col-sm-3">

        </div>
      </div>
    </div>
  </div>

</div>
</div>

</div>
<div class="col-lg-4 col-sm-12" style="top:1px;">

  <div id="tax-descriptions-tip trt" class="tooltip1 tooltip-top-middle1" role="tooltip" data-sticky="true" data-trigger="#tax-descriptions-tooltip">
  </div>
  <form accept-charset="UTF-8" action="{{ url('payments/book/'.$room_id) }}" id="book_it_form" method="post">
    {!! Form::token() !!}
    <h4 class="screen-reader-only">
      {{ trans('messages.rooms.request_to_book') }}
    </h4>
    <div id="pricing" itemprop="offers" itemscope="" class="">
      <div id="price_amount" class="book-it-price-amount pull-left h3 text-special"><span class="lang-chang-label">{{ $result->rooms_price->currency->symbol }}</span> <span  id="rooms_price_amount" class="lang-chang-label" value="">{{ $result->rooms_price->night }}</span>
        @if($result->booking_type == 'instant_book')
        <span aria-label="Book Instantly"  class="h3 icon-beach" style="position:relative;">
         <i class="icon icon-instant-book icon-flush-sides tool-amenity1"  ></i>
         <div class="tooltip-amenity tooltip-bottom-middle tooltip-amenity1"  role="tooltip" data-sticky="true" aria-hidden="true" style="left: -132px; top: -120px; display: none;">
          <dl class="panel-body" style="padding:18px;">
            <dt style="    font-weight: bold !important;
            font-size: 15px !important;">Instant Book</dt>
            <dt style="    font-size: 13px;
            line-height: 22px;
            padding-top: 10px;">Book without waiting for the host to respond</dt>
          </dl>
        </div>
      </span>
      @endif
    </div>
    <i class="icon icon-bolt icon-beach pull-left h3 pricing__bolt"></i>

    <div id="payment-period-container" class="pull-right">
      <div id="per_night" class="per-night">
        {{ trans('messages.rooms.per_night') }}
      </div>
      <div id="per_month" class="per-month hide">
        {{ trans('messages.rooms.per_month') }}
        <i id="price-info-tooltip" class="icon icon-question hide" data-behavior="tooltip"></i>
      </div>
    </div>
  </div>

  <div id="book_it" class="display-subtotal" style="top: -1px;">
    <div class="panel book-it-panel">
      <div class="panel-body panel-light">
        <div class="form-fields">
          <div class="row row-condensed space-3">
            <div class="col-md-9 col-sm-9 lang-chang-label">
              <div class="row row-condensed">
                <div class="col-sm-6 space-1-sm lang-chang-label">
                  <label for="checkin">
                    {{ trans('messages.home.checkin') }}
                  </label>
                  <input readonly="readonly" class="checkin ui-datepicker-target" onfocus="this.blur()" autocomplete="off" id="list_checkin"  placeholder="{{ strtolower(DISPLAY_DATE_FORMAT) }}" type="text">
                  <input type="hidden" name="checkin" value="{{ $formatted_checkin }}" class="formatted_checkin">
                </div>

                <input readonly="readonly" type="hidden" ng-model="room_id" ng-init="room_id = {{ $room_id }}">
                <input type="hidden" id="room_blocked_dates" value="" >
                <input type="hidden" id="calendar_available_price" value="" >
                <input type="hidden" id="room_available_price" value="" >
                <input type="hidden" id="price_tooltip" value="" >
                <input type="hidden" id="weekend_price_tooltip" value="" >
                <input type="hidden" id="url_checkin" value="{{ $checkin }}" required >
                <input type="hidden" id="url_checkout" value="{{ $checkout }}" >
                <input type="hidden" id="url_guests" value="{{ $guests }}" >
                <input type="hidden" name="booking_type" id="booking_type" value="{{ $result->booking_type }}" >
                <input type="hidden" name="cancellation" id="cancellation" value="{{ $result->cancel_policy }}" >

                <div class="col-sm-6 space-1-sm">
                  <label for="checkout">
                    {{ trans('messages.home.checkout') }}
                  </label>
                  <input readonly="readonly" class="checkout ui-datepicker-target" onfocus="this.blur()" autocomplete="off" id="list_checkout" placeholder="{{ strtolower(DISPLAY_DATE_FORMAT) }}" type="text">
                  <input type="hidden" name="checkout" value="{{ $formatted_checkout }}" class="formatted_checkout">
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-3">
              <label for="number_of_guests">
                {{ trans_choice('messages.home.guest',2) }}
              </label>
              <div class="select select-block">
                <select id="number_of_guests" name="number_of_guests">
                  @for($i=1;$i<= $result->accommodates;$i++)
                  <option value="{{ $i }}"> {{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>

          <div id="guest_error" class="simple-dates-message-container" style="display:none">
            <div class="media text-kazan space-top-2 space-2">
              <div class="pull-left message-icon">
                <i class="icon icon-currency-inr"></i>
              </div>
              <div class="media-body">
                <strong>
                  {{ trans('messages.search.enter_guest') }}
                </strong>
              </div>
            </div>
          </div>
          <div class="simple-dates-message-container hide">
            <div class="media text-kazan space-top-2 space-2">
              <div class="pull-left message-icon">
                <i class="icon icon-currency-inr"></i>
              </div>
              <div class="media-body">
                <strong>
                  {{ trans('messages.search.enter_dates') }}
                </strong>
              </div>
            </div>
          </div>
        </div>
        <div class="js-book-it-status">
          <div class="js-book-it-enabled clearfix">
            <div class="js-subtotal-container book-it__subtotal panel-padding-fit" style="display:none;">
              <table class="table table-bordered price_table" >
                <tbody>
                  <tr>
                    <td class="pos-rel room-night">
                     <span class="lang-chang-label"> {{ $result->rooms_price->currency->symbol }}</span>  <span  class="lang-chang-label" id="rooms_price_amount_1" value="">{{ $result->rooms_price->night }}</span> <span class="lang-chang-label">  x </span><span  id="total_night_count" value="">0</span>
                     <span ng-if="night_cnt <= 1">{{ trans_choice('messages.rooms.night', 1) }}</span>
                     <span ng-if="night_cnt > 1">{{ trans_choice('messages.rooms.night', 2) }}</span>
                     <i id="service-fee-tooltip" rel="tooltip" class="icon icon-question" title="{{ trans('messages.rooms.avg_night_rate') }}" ></i>
                   </td>
                   <td class="total_night_price"><span class="lang-chang-label">{{ $result->rooms_price->currency->symbol }}</span><span  id="total_night_price" value="">0</span></td>
                 </tr>

                 <tr class="early_bird booking_period text-beach"> 
                  <td>
                    <span class="booked_period_discount" value="">0</span>% {{ trans('messages.rooms.early_bird_price_discount') }}
                  </td>
                  <td>-{{ $result->rooms_price->currency->symbol }}<span  class="booked_period_discount_price" value="">0</span></td>
                </tr>
                <tr class="last_min booking_period text-beach"> 
                  <td>
                    <span class="booked_period_discount" value="">0</span>% {{ trans('messages.rooms.last_min_price_discount') }}
                  </td>
                  <td>-{{ $result->rooms_price->currency->symbol }}<span  class="booked_period_discount_price" value="">0</span></td>
                </tr>

                <tr class="weekly text-beach"> 
                  <td>
                    <span id="weekly_discount" value="">0</span>% {{ trans('messages.rooms.weekly_price_discount') }}
                  </td>
                  <td>-{{ $result->rooms_price->currency->symbol }}<span  id="weekly_discount_price" value="">0</span></td>
                </tr>
                <tr class="monthly text-beach"> 
                  <td>
                    <span id="monthly_discount" value="">0</span>% {{ trans('messages.rooms.monthly_price_discount') }}
                  </td>
                  <td>-{{ $result->rooms_price->currency->symbol }}<span  id="monthly_discount_price" value="">0</span></td>
                </tr>
                <tr class="long_term text-beach"> 
                  <td>
                    <span id="long_term_discount" value="">0</span>% {{ trans('messages.rooms.long_term_price_discount') }}
                  </td>
                  <td>-{{ $result->rooms_price->currency->symbol }}<span  id="long_term_discount_price" value="">0</span></td>
                </tr>
                <tr class="service_fee">
                  <td class="pos-rel room-ser-fee">
                    {{ trans('messages.rooms.service_fee') }}

                    <i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.rooms.24_7_help') }}"></i>

                  </td>
                  <td><span class="lang-chang-label">{{ $result->rooms_price->currency->symbol }}</span><span  id="service_fee" value="">0</span></td>
                </tr>

                <tr class="airport_fee">
                  <td class="pos-rel room-ser-fee">
                    {{ trans('messages.rooms.airport_fee') }}
                    <i id="airport-fee-tooltip"  rel="tooltip" class="icon icon-question" title="It is for Airport Pickup"></i>
                  </td>
                  <td><span class="lang-chang-label">{{ $result->rooms_price->currency->symbol }}</span><span  id="airport_fee" value="">0</span></td>
                </tr>

                <tr class = "additional_price"> 
                  <td>
                    {{ trans('messages.rooms.addtional_guest_fee') }}
                  </td>
                  <td>{{ $result->rooms_price->currency->symbol }}<span  id="additional_guest" value="">0</span></td>
                </tr>

                <tr class = "cleaning_price"> 
                  <td>
                    {{ trans('messages.rooms.cleaning_fee') }}
                  </td>
                  <td>{{ $result->rooms_price->currency->symbol }}<span  id="cleaning_fee" value="">0</span></td>
                </tr>

                <tr class="total">
                  <td>{{ trans('messages.rooms.total') }}</td>
                  <td><span class="lang-chang-label">{{ $result->rooms_price->currency->symbol }}</span><span  id="total" value="">0</span></td>
                </tr>

                <tr class = "security_price"> 
                  <td>
                    {{ trans('messages.rooms.security_fee') }} <i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i>
                  </td>
                  <td>{{ $result->rooms_price->currency->symbol }}<span  id="security_fee" value="">0</span></td>
                </tr>

              </tbody></table>
            </div>

            <div id="book_it_disabled" class="text-center" style="display:none;">
              <p id="book_it_disabled_message" class="icon-rausch book_it_disabled_msg">
                {{ trans('messages.rooms.dates_not_available') }}
              </p>
              <p id="book_it_error_message" class="text-danger book_it_disabled_msg">

              </p>
              <a href="{{URL::to('/')}}/s?location={{$result->rooms_address->city }}" class="btn btn-large btn-block" id="view_other_listings_button">
                {{ trans('messages.rooms.view_other_listings') }}
              </a>
            </div> 
            <input type="hidden" id="verification_status" value="{{$verification_status }}">
             <input type="hidden" id="username" value="{{$username }}">
             <input type="hidden" id="reason" value="{{$reason }}">
            <div class="js-book-it-btn-container {{ ($result->user_id == @Auth::user()->id) ? 'hide' : '' }}">
              <button type="submit" class="js-book-it-btn btn btn-large btn-block btn-primary">
                <span class="book-it__btn-text {{ ($result->booking_type != 'instant_book') ? '' : 'hide' }}">
                  {{ trans('messages.rooms.request_to_book') }}
                </span>
                <span class="{{ ($result->booking_type == 'instant_book') ? '' : 'book-it__btn-text--instant' }}">
                  <i class="icon icon-bolt text-beach h4 book-it__instant-book-icon"></i>
                  {{ trans('messages.lys.instant_book') }}
                </span>
              </button> 
              <input type="hidden" name="instant_book" value="{{ $result->booking_type }}">
            </div>
            <p class="text-muted book-it__btn-text--instant-alt space-1 space-top-3 text-center {{ ($result->user_id == @Auth::user()->id) ? 'hide' : '' }}">
              <small>
                {{ trans('messages.rooms.review_before_paying') }}
              </small>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="panel wishlist-panel space-top-6">
      <div class="panel-body panel-light">
        @if(Auth::check())
        <div class="wishlist-wrapper ">
          <div class="rich-toggle wish_list_button not_saved" data-hosting_id="{{ $result->id }}">
            <input type="checkbox" name="wishlist-button" id="wishlist-button" @if(@$is_wishlist > 0 ) checked @endif >
            <label for="wishlist-button" class="btn btn-block btn-large">
              <span class="rich-toggle-checked">
                <i class="icon icon-heart icon-rausch"></i>
                Saved to Wish List
              </span>
              <span class="rich-toggle-unchecked">
                <i class="icon icon-heart-alt icon-light-gray"></i>
                {{ trans('messages.wishlist.save_to_wishlist') }}
              </span>
            </label>
          </div>
        </div>
        @endif
        <div class="other-actions  text-center">
          <div class="social-share-widget space-top-3 p3-share-widget">
            <span class="share-title">
              {{ trans('messages.rooms.share') }}:
            </span>
            <span class="share-triggers">

              <a class="share-btn link-icon" data-email-share-link="" data-network="email" rel="nofollow" title="{{ trans('messages.login.email') }}" href="mailto:?subject=I love this room&amp;body=Check out this {{ Request::url() }}">
                <span class="screen-reader-only">{{ trans('messages.login.email') }}</span>
                <i class="icon icon-envelope social-icon-size"></i>
              </a>
              <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Facebook</span>
                <i class="icon icon-facebook social-icon-size"></i>
              </a>

              <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/intent/tweet?text=Love this! {{ $result->name }} - {{ $result->property_type_name }} for Rent - {{ "@".$site_name}} Travel&url={{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Twitter</span>
                <i class="icon icon-twitter social-icon-size"></i>
              </a>  

              <a class="share-btn link-icon" data-network="pinterest" rel="nofollow" title="Pinterest" href="http://pinterest.com/pin/create/button/?url={{ Request::url() }}&media={{ $result->photo_name }}&description={{ $result->summary }}" target="_blank">
                <span class="screen-reader-only">Pinterest</span>
                <i class="icon icon-pinterest social-icon-size"></i>
              </a> 


              <!-- <a class="share-btn link-icon" title="Google" href="https://plus.google.com/share?url={{ Request::url() }}"  itemprop="nofollow" rel="publisher" target="_blank">
                <span class="screen-reader-only">Google+</span>
                <i class="icon social-icon-size icon-google-plus"></i>
              </a>   -->

            </span>


          </div>


        </div>
      </div>
    </div>
  </div>
</div>

<input id="hosting_id" name="hosting_id" type="hidden" value="{{ $result->id }}">
<input id="room_types" name="room_types" type="hidden" value="{{ $room_types }}">
</form></div>
</div>
</div>
</div>

<div id="details" class="details-section webkit-render-fix">
  <div id="summary-extend" class="page-container-responsive">
    <div class="row">
      <div class="col-lg-8 lang-chang-label col-sm-12" id="details-column">

        <div class="row-space-8 row-space-top-8">

          <h4 class="row-space-4 text-center-sm">
            {{ trans('messages.rooms.about_this_listing') }}
          </h4>


          <p>{!! nl2br($result->summary) !!}</p>

          @if(Auth::check())
          @if(Auth::user()->id != $result->user_id)
          <p class="text-center-sm">
            <a id="contact-host-link" href="javascript:void(0);">
              <strong>{{ trans('messages.rooms.contact_host') }}</strong>
            </a>
          </p>
          @endif
          @endif
          {{--
          <div class="space-4 space-top-4 show-sm">
            @foreach($rooms_photos as $row_photos)
            <div class="inline-photo panel-image mob_photo-gallery">
              <a href="{{ $row_photos->name }}" class="photo-trigger gallery" data-index="1">
                <img src="{{ $row_photos->name }}" alt="{{ $row_photos->highlights }}" class="media-photo media-photo-block space-1 space-top-1 img-responsive">
                <div class="panel-overlay-top-right panel-overlay-label panel-overlay-button-icon">
                  <i class="icon icon-full-screen icon-white icon-size-2"></i>
                </div>
              </a>    </div>
              <div class="row">
                <div class="col-lg-9">
                  <p class="text-muted pull-left">{{ $row_photos->highlights }}</p>
                </div>
                <div class="col-lg-3">
                </div>
              </div>
              @endforeach
            </div>
            --}}
            <div class="space-4 space-top-4 show-sm">
              @foreach($rooms_photos->slice(0, 1) as $i => $row_photos)
              <div class="mob_photo-gallery">

                <div class="row featured-height">
                  <div class="col-12 img-box1 photo-gallery1">
                    <a class="photo-trigger gallery mobile_view_gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="1" data-lightbox-type="iframe">
                    </a>
                    <a style="background-image: url({{ $row_photos->slider_image_name }});" class="photo-grid-photo photo-trigger gallery mobile_view_gallery1" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="5" data-lightbox-type="iframe">
                      <div class="row row-table supporting-height">
                        <div class="col-6 col-middle text-center text-contrast">
                          <div class="h5">
                            {{ trans('messages.rooms.see_all') }} {{ round(count($rooms_photos))}} {{ trans_choice('messages.header.photo',2) }}
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>

              </div>
              @endforeach
            </div>

            <hr>



            <div class="row">
              <div class="col-md-3 lang-chang-label col-sm-12">
                <div class="text-muted">
                  {{ trans('messages.lys.the_space') }}
                </div>

              </div>
              <div class="col-md-9 col-sm-12">
                <div class="row">
                  <div class="col-md-6 lang-chang-label col-sm-6">
                    @if($result->bed_type_name != NULL)
                    <div>{{ trans('messages.rooms.bed_type') }}: <strong>{{ $result->bed_type_name }}</strong></div>
                    @endif
                    <div>{{ trans('messages.rooms.property_type') }}: <strong>{{ $result->property_type_name }}</strong></div>

                    <div>{{ trans('messages.lys.accommodates') }}: <strong>{{ $result->accommodates }}</strong></div>
                  </div>
                  <div class="col-md-6">

                    <div>{{ trans('messages.lys.bedrooms') }}: <strong>{{ $result->bedrooms }}</strong></div>

                    <div>{{ trans('messages.lys.bathrooms') }}: <strong>{{ $result->bathrooms }}</strong></div>

                    <div>{{ trans_choice('messages.lys.bed',$result->beds) }}: <strong>{{ $result->beds }}</strong></div>
                  </div>
                </div>
              </div>
            </div>


            <hr>

            @if(count($amenities) !=0)
            <div class="row amenities">
              <div class="col-md-3 text-muted lang-chang-label col-sm-12">
                {{ trans('messages.lys.amenities') }}
              </div>



              <div class="col-md-9 expandable expandable-trigger-more">
                <div class="expandable-content-summary">
                  <div class="row rooms_amenities_before" >


                    <div class="col-sm-6 lang-chang-label clrleft">

                     @php $i = 1 @endphp

                     @php $count = round(count($amenities)/2) @endphp

                     @foreach($amenities as $all_amenities)


                     @if($i < 6)

                     @if($all_amenities->status != null)
                     <div class="row-space-1">
                      @else
                      <div class="row-space-1 text-muted">
                        @endif                       
                       <img class="icon-img" src="{{ $all_amenities->image_name}}"/>
                        &nbsp;
                        <span class="js-present-safety-feature future_basics"><strong>
                          @if($all_amenities->status == null)
                          <del> 
                            @endif


                            @if(Session::get('language')=='en')
                            {{ $all_amenities->name }}
                            @elseif($all_amenities->namelang == null)
                            {{ $all_amenities->name }}
                            @else
                            {{ $all_amenities->namelang }}
                            @endif

                            @if($all_amenities->status == null)
                          </del> 
                          @endif
                        </strong></span>

                      </div>


                    </div>
                    <div class="col-sm-6 clrleft">
                      @endif
                      @php $i++ @endphp
                      @endforeach
                      @if(count($amenities)>5)
                      <a class="expandable-trigger-more amenities_trigger" href="">
                        <strong>+ {{ trans('messages.profile.more') }}</strong>
                      </a>
                      @endif

                    </div>

                  </div>

                  <div class="row rooms_amenities_after " style="display:none;">


                    <div class="col-sm-6 lang-chang-label clrleft">

                     @php $i = 1 @endphp

                     @php $count = round(count($amenities)/2) @endphp

                     @foreach($amenities as $all_amenities)



                     @if($all_amenities->status != null)
                     <div class="row-space-1 new_id {{ $all_amenities->type_id }}">
                      <p hidden="hidden" class="get_type" data-id="{{ $all_amenities->type_id }}"><{{ $all_amenities->type_id}}</p>
                      @else
                      <div class="row-space-1 text-muted new_id{{ $all_amenities->type_id }}">
                        <p hidden="hidden" class="get_type" data-id="{{ $all_amenities->type_id }}">{{ $all_amenities->type_id }}</p>
                        @endif
                        <img class="icon-img" src="{{$all_amenities->image_name}}"/>
                        &nbsp;
                        <span class="js-present-safety-feature future_basics"><strong>
                         @if($all_amenities->status == null)
                         <del> 
                          @endif

                          @if(Session::get('language')=='en')
                          {{ $all_amenities->name }}
                          @elseif($all_amenities->namelang == null)
                          {{ $all_amenities->name }}
                          @else
                          {{ $all_amenities->namelang }}
                          @endif

                          @if($all_amenities->status == null)
                        </del> 
                        @endif
                      </strong></span>

                    </div>


                  </div>
                  <div class="col-sm-6 clrleft">

                    @php $i++ @endphp
                    @endforeach

                  </div>

                </div>

              </div>
            </div>

          </div>

          <hr>
          @endif


          <div class="row">
            <div class="col-md-3 lang-chang-label col-sm-12">
              <div class="text-muted">
                {{ trans('messages.rooms.prices') }}
              </div>

            </div>
            <div class="col-md-9 col-sm-12">
              <div class="row">
                <div class="col-md-6 lang-chang-label col-sm-6 fle">
                  <div>{{ trans('messages.rooms.extra_people') }}: <strong> 
                    @if($result->rooms_price->guests !=0 && $result->rooms_price->additional_guest!=0)

                    <span> {{ $result->rooms_price->currency->symbol }} {{ $result->rooms_price->additional_guest }}   / {{ trans('messages.rooms.night_after_guest',['count'=>$result->rooms_price->guests]) }}</span>

                    @else
                    <span >{{ trans('messages.rooms.no_charge') }}</span>
                    @endif
                  </strong>
                </div>

                  <!-- weekend price -->
                  @if($result->rooms_price['original_weekend'] != 0)
                  <div>{{ trans('messages.lys.weekend_pricing') }}:                 
                    <strong> <span id="weekly_price_string">{{ $result->rooms_price->currency->symbol }} {{ number_format($result->rooms_price->weekend) }}</span> /{{ trans('messages.lys.weekend') }}</strong> 
                  </div>
                  @endif


                </div>
                <div class="col-md-6 ">


                  <div>{{ trans('messages.your_reservations.cancellation') }}:
                    <a href="{{ url('/home/cancellation_policies#'.$result->cancel_policy) }} " id="cancellation-policy"><strong >{{trans('messages.cancellation_policy.'.strtolower($result->cancel_policy))}}</strong></a>
                  </div>
                </div>

              </div>
              @if($result->length_of_stay_rules->count() > 0)
              <div class="row space-top-2">
                <div class="col-md-12">
                  <h6 class="text-black"><strong>{{trans('messages.lys.length_of_stay_discounts')}}</strong></h6>
                </div>
                @foreach($result->length_of_stay_rules->splice(0,2) as $i => $rule)
                @if(@$rule['period'])
                <div class="col-md-6 att">
                  @if($rule['period'] == 7)
                  {{trans('messages.lys.weekly')}}
                  @elseif($rule['period'] == 28)
                  {{trans('messages.lys.monthly')}}
                  @else
                  {{$rule['period']}} {{trans('messages.lys.nights')}}
                  @endif
                </div>
                <div class="col-md-6 att">
                  {{$rule['discount']}}%
                </div>
                @endif
                @endforeach 
                @if($result->length_of_stay_rules->count() > 0)
                <div class="col-md-12" >
                  <a class="expandable-trigger-more " href="javascript:void(0)" onclick="$(this).hide(); $('#expand_data_length_of_stay').show();">
                    <strong>+ {{trans('messages.profile.more')}}</strong>
                  </a>
                </div>
                <div id="expand_data_length_of_stay" style="display: none;" > 
                  @foreach($result->length_of_stay_rules as $i => $rule)
                  @if(@$rule['period'])
                  <div class="col-md-6 att">
                    @if($rule['period'] == 7)
                    {{trans('messages.lys.weekly')}}
                    @elseif($rule['period'] == 28)
                    {{trans('messages.lys.monthly')}}
                    @else
                    {{$rule['period']}} {{trans('messages.lys.nights')}}
                    @endif
                  </div>
                  <div class="col-md-6 att">
                    {{$rule['discount']}}%
                  </div>
                  @endif
                  @endforeach 
                </div>
                @endif
              </div>
              @endif
              @if($result->early_bird_rules->count() > 0)
              <div class="row space-top-2">
                <div class="col-md-12">
                  <h6 class="text-black"><strong>{{trans('messages.lys.early_bird_discounts')}}</strong></h6>
                </div>
                @foreach($result->early_bird_rules->splice(0,2) as $rule)
                <div class="col-md-6 att">
                  {{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
                </div>
                <div class="col-md-6 att">
                  {{$rule['discount']}}%
                </div>
                @endforeach 
                @if($result->early_bird_rules->count() > 0)
                <div class="col-md-12" >
                  <a class="expandable-trigger-more " href="javascript:void(0)" onclick="$(this).hide(); $('#expand_data_early_bird').show();">
                    <strong>+ {{trans('messages.profile.more')}}</strong>
                  </a>
                </div>
                <div id="expand_data_early_bird" style="display: none;" > 
                  @foreach($result->early_bird_rules as $rule)
                  <div class="col-md-6 att">
                    {{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
                  </div>
                  <div class="col-md-6 att">
                    {{$rule['discount']}}%
                  </div>
                  @endforeach 
                </div>
                @endif
              </div>
              @endif
              @if($result->last_min_rules->count() > 0)
              <div class="row space-top-2">
                <div class="col-md-12">
                  <h6 class="text-black"><strong>{{trans('messages.lys.last_min_discounts')}}</strong></h6>
                </div>
                @foreach($result->last_min_rules->splice(0,2) as $rule)
                <div class="col-md-6 att">
                  {{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
                </div>
                <div class="col-md-6 att">
                  {{$rule['discount']}}%
                </div>
                @endforeach
                @if($result->last_min_rules->count() > 0)
                <div class="col-md-12" >
                  <a class="expandable-trigger-more " href="javascript:void(0)" onclick="$(this).hide(); $('#expand_data_last_min').show();">
                    <strong>+ {{trans('messages.profile.more')}}</strong>
                  </a>
                </div>
                <div id="expand_data_last_min" style="display: none;" > 
                  @foreach($result->last_min_rules as $rule)
                  <div class="col-md-6 att">
                    {{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
                  </div>
                  <div class="col-md-6 att">
                    {{$rule['discount']}}%
                  </div>
                  @endforeach
                </div> 
                @endif
              </div>
              @endif 
            </div>
          </div>

          <hr>


          @if($result->rooms_description->space !='' || $result->rooms_description->access !='' || $result->rooms_description->interaction !='' || $result->rooms_description->neighborhood_overview !='' || $result->rooms_description->transit || $result->rooms_description->notes) 
          @php 
          $res =$result->rooms_description->toArray();
          $res = array_filter($res);
          @endphp
          <div class="row description">

            <div class="col-md-3 text-muted lang-chang-label">
              {{ trans('messages.lys.description') }}
            </div>

            <div class="col-md-9 expandable expandable-trigger-more all_description">
              @foreach (array_slice($res, 1, 2) as $key => $value)
              @if($key == 'space')
              <p><strong>{{ trans('messages.lys.the_space') }}</strong></p>
              <p>{!! nl2br($result->rooms_description->space) !!}</p>
              @endif
              @if($key == 'access')
              <p><strong>{{ trans('messages.lys.guest_access') }}</strong></p>
              <p>{!! nl2br($result->rooms_description->access) !!} </p>
              @endif
              @if($key == 'interaction')
              <p><strong>{{ trans('messages.lys.interaction_with_guests') }}</strong></p>
              <p> {!! nl2br($result->rooms_description->interaction) !!}</p>
              @endif
              @if($key == 'neighborhood_overview')
              <p><strong>{{ trans('messages.lys.the_neighborhood') }}</strong></p>
              <p> {!! nl2br($result->rooms_description->neighborhood_overview) !!}</p>
              @endif
              @if($key == 'transit')
              <p><strong>{{ trans('messages.lys.getting_around') }}</strong></p>
              <p>{!! nl2br($result->rooms_description->transit) !!}</p>
              @endif
              @endforeach
              <div class="expandable-content" id="des_content" style="display: none;">
                @foreach (array_slice($res, 3, count($res)) as $key => $value)
                @if($key == 'space')
                <p><strong>{{ trans('messages.lys.the_space') }}</strong></p>
                <p>{!! nl2br($result->rooms_description->space) !!}</p>
                @endif
                @if($key == 'access')
                <p><strong>{{ trans('messages.lys.guest_access') }}</strong></p>
                <p>{!! nl2br($result->rooms_description->access) !!} </p>
                @endif
                @if($key == 'interaction')
                <p><strong>{{ trans('messages.lys.interaction_with_guests') }}</strong></p>
                <p> {!! nl2br($result->rooms_description->interaction) !!}</p>
                @endif
                @if($key == 'notes')
                <p><strong>{{ trans('messages.lys.other_things_note') }}</strong></p>
                <p> {!! nl2br($result->rooms_description->notes) !!}</p>
                @endif
                <!-- @if($key == 'house_rules')
                <p><strong>{{ trans('messages.lys.house_rules') }}</strong></p>
                <p> {!! nl2br($result->rooms_description->house_rules) !!}</p>
                @endif -->
                @if($key == 'neighborhood_overview')
                <p><strong>{{ trans('messages.lys.the_neighborhood') }}</strong></p>
                <p> {!! nl2br($result->rooms_description->neighborhood_overview) !!}</p>
                @endif
                @if($key == 'transit')
                <p><strong>{{ trans('messages.lys.getting_around') }}</strong></p>
                <p>{!! nl2br($result->rooms_description->transit) !!}</p>
                @endif
                @endforeach
              </div>
              @if (count($res) > 3)
              <a class="expandable-trigger-more desc" id="desc" href="">
                <strong >+ {{ trans('messages.profile.more') }}</strong>
              </a>
              @endif

            </div>
          </div>

          <hr>
          @endif


          @if($result->rooms_description->house_rules !='')
          <div class="row">
            <div class="col-md-3 lang-chang-label col-sm-12">
              <div class="text-muted">
                {{ trans('messages.lys.house_rules') }}
              </div>

            </div>
            <div class="col-md-9 expandable expandable-trigger-more expanded col-sm-12">
              <div class="expandable-content">
                <p>{!! nl2br($result->rooms_description->house_rules) !!}</p>
                <div class="expandable-indicator"></div>
              </div>
              <a class="expandable-trigger-more" href="#">
                <strong>+ {{ trans('messages.profile.more') }}</strong>
              </a>

            </div>
          </div>

          <hr>
          @endif


          <div class="js-p3-safety-features-section">
            @if(count($safety_amenities) !=0)
            <div class="row">
              <div class="col-md-3 lang-chang-label col-sm-12">
                <div class="text-muted">
                  {{ trans('messages.rooms.safety_features') }}
                </div>

              </div>
              <div class="col-md-9 col-sm-12">
                <div class="js-no-safety-features-text hide">
                  {{ trans('messages.account.none') }}
                </div>
                <div class="row">
                  <div class="col-sm-6 lang-chang-label clrleft">

                   @php $i = 1 @endphp

                   @php $count = round(count($safety_amenities)/2) @endphp

                   @foreach($safety_amenities as $row_safety)

                   @if($row_safety->status != null)
                   <div class="row-space-1">
                    @else
                    <div class="row-space-1 text-muted">
                      @endif
                      <img class="icon-img" src="{{$row_safety->image_name}}"/>
                      &nbsp;
                      <span class="js-present-safety-feature cut-span"><strong>
                       @if($row_safety->status == null)
                       <del> 
                        @endif
                        
                        @if(Session::get('language')=='en')
                        {{ $row_safety->name }}
                        @elseif($row_safety->namelang == null)
                        {{ $row_safety->name }}
                        @else
                        {{ $row_safety->namelang }}
                        @endif

                        @if($row_safety->status == null)
                      </del> 
                      @endif
                    </strong></span>

                  </div>


                </div>
                <div class="col-sm-6 lang-chang-label clrleft">

                  @php $i++ @endphp
                  @endforeach

                </div>
              </div>
            </div>

          </div>
          <hr>
          @endif

          <div class="row">
            <div class="col-md-3 lang-chang-label col-sm-12">
              <div class="text-muted">
                {{ trans('messages.rooms.availability') }}
              </div>

            </div>
            <div class="col-md-9 col-sm-12">
              @if($result->rooms_price->minimum_stay || $result->rooms_price->maximum_stay)
              <div class="row">
                @if($result->rooms_price->minimum_stay)
                <div class="col-md-6">
                  <span>{{trans('messages.lys.minimum_stay')}}:</span>
                  <strong>{{$result->rooms_price->minimum_stay}}</strong>
                </div>
                @endif
                @if($result->rooms_price->maximum_stay)
                <div class="col-md-6">
                  <span>{{trans('messages.lys.maximum_stay')}}:</span>
                  <strong>{{$result->rooms_price->maximum_stay}}</strong>
                </div>
                @endif
              </div>
              @endif
              @if($result->availability_rules->count() > 0)
              <div class="row space-top-2">
                @foreach($result->availability_rules->splice(0, 2) as $rule)
                <div class="col-md-12 space-2" >
                  {{trans('messages.lys.during')}} {{$rule->during}}
                  @if($rule->minimum_stay)
                  <p class="space-0" style="text-transform: capitalize;" >
                    {{trans('messages.lys.guest_stay_for_minimum')}} {{$rule->minimum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                  @endif
                  @if($rule->maximum_stay)
                  <p class="space-0" style="text-transform: capitalize;" >
                    {{trans('messages.lys.guest_stay_for_maximum')}} {{$rule->maximum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                  @endif
                </div>
                @endforeach 
                @if($result->availability_rules->count() > 0)
                <div class="col-md-12" >
                  <a class="expandable-trigger-more " href="javascript:void(0)" onclick="$(this).hide(); $('#expand_data_availability_rules').show();">
                    <strong>+ {{trans('messages.profile.more')}}</strong>
                  </a>
                </div>
                <div id="expand_data_availability_rules" style="display: none;" > 
                 @foreach($result->availability_rules as $rule)
                 <div class="col-md-12 space-2" >
                  {{trans('messages.lys.during')}} {{$rule->during}}
                  @if($rule->minimum_stay)
                  <p class="space-0" style="text-transform: capitalize;" >
                    {{trans('messages.lys.guest_stay_for_minimum')}} {{$rule->minimum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                  @endif
                  @if($rule->maximum_stay)
                  <p class="space-0" style="text-transform: capitalize;" >
                    {{trans('messages.lys.guest_stay_for_maximum')}} {{$rule->maximum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                  @endif
                </div>
                @endforeach 
              </div>
              @endif
            </div>
            @endif
            <div class="row">
              <div class="col-md-6 lang-chang-label col-sm-6">
                <a id="view-calendar" href="javascript:void(0);"><strong>{{ trans('messages.rooms.view_calendar') }}</strong></a>
              </div>
            </div>
          </div>
        </div>




        <div class="photo-gallery photo-grid row-space-4 row-space-top-4 hide-sm ">

          @php $i = 1 @endphp

          <!-- @php $count = round(count($amenities)/2) @endphp -->
          <!-- {{ count($rooms_photos)}} -->

          @foreach($rooms_photos->sortByDesc('featured') as $row_photos)

          @if(count($rooms_photos) == 1)
          <div class="featured-height">
           <div class="col-12 row-full-height img-box1 photo-gallery1">
            <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="1" data-lightbox-type="iframe">
              <!-- <img  src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt=""> -->
            </a>
          </div></div>
          @else


          @if($i == 1)
          <div class="featured-height">
           <div class="col-12 row-full-height img-box1 photo-gallery1">
            <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="1" data-lightbox-type="iframe">
              <!-- <img src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt=""> -->
            </a>
          </div></div>
          @endif
          @if($i==2 && $i >1)               
          <div class="col-6 supporting-height img-box1 photo-gallery1"> 
            <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="{{ url('rooms/'.$result->id.'/slider?order=id') }}" data-index="2" data-lightbox-type="iframe">
              <!-- <img src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt=""> -->
            </a> 
          </div>
          @endif

          @if($i==3 && $i >2)

          <div class="col-6 supporting-height img-box1 photo-gallery1">
            <div class="media-photo media-photo-block row-full-height">
              <div class="media-cover media-cover-dark img-box1">
                <a class="photo-grid-photo photo-trigger gallery"
                style="background-image: url({{ $row_photos->slider_image_name }})"
                href="{{ url('rooms/'.$result->id.'/slider') }}"
                data-index="5" data-lightbox-type="iframe">
                <img src="{{ $row_photos->name }}"
                class="hide"
                alt="Private shower/Longterm/Decent B&amp;B">
              </a>
            </div>
            <a class="photo-trigger gallery"
            href="{{ url('rooms/'.$result->id.'/slider') }}"
            data-index="5" data-lightbox-type="iframe">
            <div class="row row-table supporting-height">
              <div class="col-6 col-middle text-center text-contrast">
                <div class="h5">
                  {{ trans('messages.rooms.see_all') }} {{ round(count($rooms_photos))}} {{ trans_choice('messages.header.photo',2) }}
                </div>
              </div>
            </div>
          </a></div></div>
          @endif
          @endif
          @php $i++ @endphp
          @endforeach
        </div>
        @if($result->video)
        <iframe width="100%" height="300" src="{{ $result->video }}" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>
        @endif
      </div>
    </div>
  </div>
</div>
</div>


<div id="reviews" class="room-section webkit-render-fix">
  <div class="panel">
    <div class="page-container-responsive row-space-2">

      <div class="row">
       <div class="col-lg-8 lang-chang-label col-sm-12">   
        <!--Start-->
         <div class="col-md-9 col-sm-12">
            <div class="row row-condensed">
              <div class="col-sm-4 col-md-3 lang-chang-label col-sm-12">
                  <a class="link-reset" rel="nofollow" href="{{ url('users/show/'.$result->user_id) }}#reviews">
                  <div class="text-center text-wrap hrt">
                    <div class="badge-pill h3">
                      <span class="badge-pill-count">{{ $result->users->reviews->count() }}</span>
                    </div>
                    <div class="row-space-top-1">{{ trans_choice('messages.header.review',2) }}</div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          @if(!$result->reviews->count())
          <div class="review-content">
            <div class="panel-body">
              <h4 class="row-space-4 text-center-sm ">
                {{ trans('messages.rooms.no_reviews_yet') }}
              </h4>
              @if($result->users->reviews->count())
              <p>
                {{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count(), ['count'=>$result->users->reviews->count()]) }}
              </p>
              <a href="{{ url('users/show/'.$result->user_id) }}" class="btn">{{ trans('messages.rooms.view_other_reviews') }}</a>

              @endif
            </div>
          </div>
          @else

          <div class="review-wrapper">
            <div>
              <div class="row space-2 space-top-8 row-table">
                <div class="review-header col-md-8 lang-chang-label">
                  <div class="va-container va-container-v va-container-h">
                    <div class="va-bottom review-header-text">
                      <h4 class="text-center-sm col-middle">
                        <span>{{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}</span>
                        <div style="display:inline-block;">
                          <div class="star-rating-wrapper">
                            {!! $result->overall_star_rating !!}
                          </div>
                        </div>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <hr>
              </div>
            </div>


            <div class="review-main">
              <div class="review-inner space-top-2 space-2">

                <div class="row">
                  <div class="col-lg-3 show-lg lang-chang-label">
                    <div class="text-muted">
                      <span>{{ trans('messages.lys.summary') }}</span>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="row">
                      <div class="col-lg-6 lang-chang-label summary_details">
                      <div class="cls_sumstartdetails">
                         <strong title="{{ trans('messages.reviews.pride_inclusion_score') }}">{{ trans('messages.reviews.pride_inclusion_score') }}</strong>
                         <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->PrideScoreStarRating !!}
                              <span> </span>
                            </div>
                          </div>
                         
                        </div>

                        <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.reviews.accuracy') }}">{{ trans('messages.reviews.accuracy') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->accuracy_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                          
                        </div>
                         <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.reviews.communication') }}">{{ trans('messages.reviews.communication') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->communication_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                        </div>
                        <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.reviews.cleanliness') }}">{{ trans('messages.reviews.cleanliness') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->cleanliness_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 lang-chang-label  summary_details">
                        <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.reviews.location') }}">{{ trans('messages.reviews.location') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->location_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                        </div>
                         <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.home.checkin') }}">{{ trans('messages.home.checkin') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->checkin_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                        </div>
                        <div class="cls_sumstartdetails">
                          <strong title="{{ trans('messages.reviews.value') }}">{{ trans('messages.reviews.value') }}</strong>
                          <div class="">
                            <div class="star-rating-wrapper">
                              {!! $result->value_star_rating !!}
                              <span> </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="review-content">
                <div class="panel-body">
                  @foreach($result->reviews as $row_review)
                  <div>
                    <div class="row review">
                      <div class="col-md-3 col-sm-12 text-center space-2 lang-chang-label">
                        <div class="media-photo-badge">
                          <a class="media-photo media-round" href="{{ url('users/show/'.$row_review->user_from) }}">
                            <img width="67" height="67" title="{{ $row_review->users_from->first_name }}" src="{{ $row_review->users_from->profile_picture->src }}" data-pin-nopin="true" alt="shared.user_profile_image">
                          </a>
                        </div>
                        <div class="name">
                          <a target="_blank" class="text-muted link-reset" href="{{ url('users/show/'.$row_review->user_from) }}">{{ $row_review->users_from->first_name }}</a>
                        </div>
                      </div>
                      <div class="col-md-9 col-sm-12">
                        <div class="space-2">
                          <div class="review-text" data-review-id="{{ $row_review->id }}">
                            <div class="react-expandable expanded text-center-sm">
                              <div class="expandable-content" tabindex="-1" style="">
                                <p>{{ $row_review->comments }}</p>
                              </div>
                            </div>
                          </div>

                          <div class="text-muted review-subtext">
                            <div class="review-translation-language">
                            </div>
                            <div class="">
                              <div class="text-center-sm">
                                <span class="date" style="display:inline-block;">
                                  {{-- date($php_format_date,strtotime($row_review->date_fy)) --}}
                                  {{ $row_review->date_fy }}
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <span>
                        </span>
                      </div>
                      <div class="row space-2">
                        <div class="col-md-9 col-md-push-3">
                          <hr>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  @if($result->users->reviews->count() - $result->reviews->count())
                  <div class="row row-space-top-2">
                    <div class="col-lg-9 col-offset-3">
                      <p>
                        <span>{{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count() - $result->reviews->count(), ['count'=>$result->users->reviews->count() - $result->reviews->count()]) }}</span>
                      </p>
                      <a target="blank" class="btn" href="{{ url('users/show/'.$result->user_id) }}">
                        <span>{{ trans('messages.rooms.view_other_reviews') }}</span>
                      </a>
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div id="host-profile" class="room-section webkit-render-fix">
  <div class="page-container-responsive space-top-8 space-8">
    <div class="row">
      <div class="col-lg-8 lang-chang-label col-sm-12">
        <h4 class="row-space-4 text-center-sm">
          {{ trans('messages.rooms.about_host') }}, {{ $result->users->first_name }}
        </h4>
        <div class="row">
          <div class="col-md-3 text-center lang-chang-label col-sm-12">
            <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo media-round"><img alt="{{ $result->users->first_name }}" data-pin-nopin="true" height="90" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="90"></a>
          </div>
          <div class="col-md-9 col-sm-12">
            <div class="row row-condensed space-2">
              <div class="col-md-6 lang-chang-label col-sm-6">
                @if($result->users->live)
                <div>
                  {{ $result->users->live }}
                </div>
                @endif
                <div>
                  {{ trans('messages.profile.member_since') }} 
                  {{ $result->users->since }}
                </div>
              </div>
            </div>
            @if(Auth::check())
            @if(Auth::user()->id != $result->user_id)
            <div id="contact_wrapper">
              <button id="host-profile-contact-btn" class="btn btn-small btn-primary">
                {{ trans('messages.rooms.contact_host') }}
              </button>
            </div>
            @endif
            @endif
          </div>
        </div>

        <hr class="space-4 space-top-4">
        <div class="row">
          <div class="col-md-3 lang-chang-label col-sm-12">
            <!--<div class="text-muted">
              {{ trans('messages.rooms.trust') }}
            </div>-->
          </div>
          <div class="col-md-9 col-sm-12">
            <!--<div class="row row-condensed">
              <div class="col-sm-4 col-md-3 lang-chang-label col-sm-12">
                  <a class="link-reset" rel="nofollow" href="{{ url('users/show/'.$result->user_id) }}#reviews">
                  <div class="text-center text-wrap hrt">
                    <div class="badge-pill h3">
                      <span class="badge-pill-count">{{ $result->users->reviews->count() }}</span>
                    </div>
                    <div class="row-space-top-1">{{ trans_choice('messages.header.review',2) }}</div>
                  </div>
                </a>
              </div>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="neighborhood" class="room-section">
  <div class="page-container-responsive" id="map-id" data-reactid=".2" style="position:relative;">
    <div class="panel location-panel">
      <div id="map" data-lat="{{ $result->rooms_address->latitude }}" data-lng="{{ $result->rooms_address->longitude }}"> </div>
      <ul id="guidebook-recommendations" class="hide">
        <li class="user-image">
          <a href=""><img alt="Jeya" data-pin-nopin="true" height="90" src="" title="Jeya" width="90"></a>
        </li>
      </ul>

      <div id="hover-card" class="panel">
        <div class="panel-body">
          <div class="text-center">
            {{ trans('messages.rooms.listing_location') }}
          </div>
          <div class="text-center">
            <span>
              <a href="" class="text-muted"><span>{{$result->rooms_address->state}},</span></a>
            </span>
            <span>
              <a href="" class="text-muted"><span>{{$result->rooms_address->country_name}}</span></a>
            </span>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<div id="similar-listings" class="similar-slide row-space-top-4">

  @if(count($similar)> 3)
  <div  id="slider-next" class="" data-reactid=".2.0.1.2">
    <i class="" data-reactid=".2.0.1.2.0"></i>
  </div>
  <div  id="slider-prev" class="" data-reactid=".2.0.1.2">
    <i class="" data-reactid=".2.0.1.2.0"></i>
  </div>
  @endif
  @if(count($similar)!= 0)

  <div class="page-container-responsive">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
      <h4 class="row-space-4 text-center-sm">
        {{ trans('messages.rooms.similar_listings') }}
      </h4>

      <div class="slider1 owl-carousel slide1">
        @foreach($similar as $rooms)
        <!-- <div class="col-md-4 col-sm-12"> -->
        <div class="listing list_view" >
          <div class="panel-image listing-img">
            <div class="listing-description wl-data-{{ $rooms->id }}">
              <div class="summary">
                <p>
                  {{ $rooms->summary }}
                  <a href="{{$rooms->link}}" id="tooltip-sticky-{{ $rooms->id }}" class="learn-more">{{ trans('messages.search.learn_more') }}
                  </a>
                </p>
              </div>
              <p class="address">{{ $rooms->city }}
              </p>
            </div>
            <a href="{{$rooms->link}}" target="listing_{{$rooms->id}}" class="media-photo media-cover">
              <div class="listing-img-container media-cover text-center">
                <img id="rooms_image_{{$rooms->id}}"  src="{{ $rooms->photo_name }}" class="img-responsive-height" alt="{{$rooms->name}}">
              </div>
            </a>
            <div class="target-prev target-control rooms-slider block-link hide"  data-room_id="{{$rooms->id}}">
              <i class="icon icon-chevron-left icon-size-2 icon-white">
              </i>
            </div>
            <div class="target-next target-control rooms-slider block-link hide" data-room_id="{{$rooms->id}}">
              <i class="icon icon-chevron-right icon-size-2 icon-white">
              </i>
            </div>
            <div class="panel-overlay-top-right wl-social-connection-panel hide">
              <span class="rich-toggle wish_list_button wishlist-button not_saved">
                <input type="checkbox" id="wishlist-widget-{{$rooms->id}}" name="wishlist-widget-{{$rooms->id}}" data-for-hosting="{{$rooms->id}}" ng-checked="rooms.saved_wishlists">
                <label for="wishlist-widget-{{$rooms->id}}" class="hide-sm">
                  <span class="screen-reader-only">Wishlist
                  </span>
                  <i class="icon icon-heart icon-rausch icon-size-2 rich-toggle-checked">
                  </i>
                  <i class="icon icon-heart wishlist-heart-unchecked icon-size-2 rich-toggle-unchecked">
                  </i>
                  <i class="icon icon-heart-alt icon-white icon-size-2" id="wishlist-widget-icon-{{$rooms->id}}" data-room_id="{{$rooms->id}}" data-img="{{$rooms->photo_name}}" data-name="{{$rooms->name}}" data-address="{{$rooms->rooms_address->city}}" data-price="{{ $rooms->rooms_price->currency->symbol }}{{ $rooms->rooms_price->night }}" data-review_count="" data-host_img="{{ $rooms->users->profile_picture->src }}" data-star_rating="" data-summary="{{ $rooms->summary }}" data-room_type="{{ $rooms->room_type_name }}" data-property_type_name="{{ $rooms->property_type_name }}" data-person_capacity_string="" data-bedrooms_string="" data-space_tab_content="" data-neighborhood_tab_content="">
                  </i>
                </label>
              </span>
            </div>
          </div>
          <div class="panel-body panel-card-section">
            <div class="media">
              <div class="category_city hm_cate">
                <span class="pull-left">{{ $rooms->room_type_name }}
                </span>
                <span class="pull-left dot-cont">·
                </span>
                <span class="pull-left">{{ $rooms->beds }} {{ trans_choice('messages.lys.bed',$rooms->beds) }}
                </span>
              </div>
              <a href="{{$rooms->link}}" target="listing_{{$rooms->id}}" class="text-normal">
                <h3 title="{{$rooms->name}}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1">
                  {{$rooms->name}}
                </h3>
              </a>
              <div class="exp_price">
                <span >{{ $rooms->rooms_price->currency->symbol }}
                </span>
                {{ $rooms->rooms_price->night }}
                {{ trans("messages.rooms.per_night") }}
                @if($rooms->booking_type == 'instant_book')
                <span> 
                  <i class="icon icon-instant-book icon-beach h4">
                  </i>
                </span>
                @endif
              </div>
              <div itemprop="description" class="pull-left text-muted listing-location text-truncate nt_star">
                <a href="{{$rooms->link}}" class="text-normal link-reset pull-left">
                  @if($rooms->overall_star_rating)
                  <span class="pull-left">
                    <span class="pull-left">{!!$rooms->overall_star_rating!!}
                    </span>
                  </span>
                  @endif
                  @if($rooms->reviews_count)
                  <span class="pull-left">
                    <span class="pull-left dot-cont">·
                    </span>
                    <span class="pull-left r-count">{{$rooms->reviews_count}}
                    </span>
                    <span class="pull-left r-label">{{ trans_choice('messages.header.review', $rooms->reviews_count) }}
                    </span>
                  </span>
                  @endif
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- </div> -->
        @endforeach
      </div>
    </div>
  </div>
  @endif
</div>
</div>
</div>
</div>
</div>



<div><div>
  <span>
    <div class="modal-container modal-transitions contact-modal hide">
      <div class="modal-table popup-scroll">
        <div class="modal-cell">
          <div class="modal-content host-sec">
            <a data-behavior="modal-close" class="modal-close mod_cls" href="#" style="font-size:3em;"></a>
            <div id="contact-host-panel" class="hedi">
              <div id="compose-message" class="contact-host-panel panel-dark">
                <div class="row">
                  <div class="host-questions-panel panel panel-dark col-md-4 col-sm-12 lang-chang-label con_new">
                    <div class="panel-body">
                      <div class="text-center">
                        <div class="media-photo media-round">
                          <div class="media-photo-badge">
                            <a href="{{ url('/') }}/users/show/{{ $result->user_id }}" class="media-photo media-round">
                              <img alt="shared.user_profile_image" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="120" height="120">
                            </a>
                          </div>
                        </div>
                      </div>
                      <div>
                        <h5>
                          <span>{{ trans('messages.rooms.send_a_message',['first_name'=>$result->users->first_name]) }}</span>
                        </h5>
                        <p>
                          <span>{{ trans('messages.rooms.share_following') }}:</span>
                        </p>
                        <ul>
                          <li>
                            <span>{{ trans('messages.rooms.tell_about_yourself',['first_name'=>$result->users->first_name]) }}</span>
                          </li>
                          <li>
                            <span>{{ trans('messages.rooms.what_brings_you',['city'=>$result->rooms_address->city]) }}?</span>
                          </li>
                          <li>
                            <span>{{ trans('messages.rooms.love_about_listing') }}!</span>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="guest-message-panel panel col-md-8 col-sm-12 con_new2">
                    <div class="alert alert-with-icon alert-info error-block row-space-4 alert-header panel-header contacted-before hide">
                      <i class="icon alert-icon icon-comment">
                      </i>
                      <div class="not-available">
                        <span id="not_available">{{ trans('messages.rooms.dates_arenot_available') }}</span>
                        <span id="error">{{ trans('messages.rooms.dates_arenot_available') }}</span>
                      </div>
                      <div class="other">
                        <strong>
                        </strong>
                      </div>
                    </div>
                    <div class="panel-body">
                      <form id="message_form" class="contact-host-panel" action="{{ url('/') }}/users/ask_question/{{ $result->id }}?src_url=rooms/{{ $result->id }}" method="POST">
                        {!! Form::token() !!}
                        <h5>
                          <span>{{ trans('messages.rooms.when_you_traveling') }}?</span>
                        </h5>
                        <div class="row-space-4 clearfix">
                          <div>
                            <div class="col-4 input-col lang-chang-label tt">
                              <label class="screen-reader-only">{{ trans('messages.home.checkin') }}</label>
                              <input value="" readonly="readonly" id="message_checkin" onfocus="this.blur()" class="checkin text-center ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" type="text" required />
                              <input type="hidden" name="message_checkin">
                            </div>
                            <span hidden="hidden" id="room_id">{{ $result->id }}</span>
                            <div class="col-4 input-col lang-chang-label">
                              <label class="screen-reader-only">{{ trans('messages.home.checkout') }}</label>
                              <input value="" readonly="readonly" id="message_checkout" onfocus="this.blur()" class="checkout text-center ui-datepicker-target" placeholder="{{ trans('messages.home.checkout') }}" type="text" required />
                              <input type="hidden" name="message_checkout">
                            </div>
                          </div>
                          <div class="col-4 input-col lang-chang-label rm_select">
                            <div class="select select-block">
                              <select class="text-center" name="message_guests" id="message_guests">
                                @for($i=1;$i<= $result->accommodates;$i++)
                                <option value="{{ $i }}">{{ $i }} {{ trans_choice('messages.home.guest',$i) }}</option>
                                @endfor
                              </select>
                            </div>
                          </div>
                          <p style="color: red" class="hide" id="errors">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="message-panel  tooltip-fixed tooltip-bottom-left row-space-4" style="background-color: #fff; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);">
                              <div class="panel-body">
                                <textarea class="focus-on-active" name="question" placeholder="{{ trans('messages.rooms.start_your_msg') }}..."></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                        <noscript>
                        </noscript>
                        <input name="message_save" value="1" type="hidden">
                      </form>
                      <div class="row">
                        <div class="col-4 lang-chang-label">
                          <div class="media-photo media-round">
                            <div class="media-photo-badge">
                              <a href="{{ url('/') }}/users/show/{{ (Auth::check()) ? Auth::user()->id : '' }}" class="media-photo media-round">
                                <img alt="shared.user_profile_image" data-pin-nopin="true" src="{{ (Auth::check()) ? Auth::user()->profile_picture->src : '' }}" title="{{ (Auth::check()) ? Auth::user()->first_name : '' }}" width="68" height="68">
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="col-7 col-offset-1">
                          <button id="contace_request_message_send" type="submit" class="btn btn-block btn-large btn-primary row-space-top-2">
                            <span>{{ trans('messages.your_reservations.send_message') }}</span>
                          </button>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <div class="contact-host-panel hide">
                <div class="panel">
                  <div class="panel-header panel-header-message-sent text-center">
                    <strong>{{ trans('messages.rooms.message_sent') }}!</strong>
                  </div>
                  <div class="panel-body text-center">
                    <div class="row">
                      <p class="col-10 col-center row-space-top-4 text-lead">
                        <span>{{ trans('messages.rooms.keep_contacting_other') }}</span>
                      </p>
                    </div>
                    <div class="row">
                      <div class="col-6 col-center row-space-top-4 row-space-2">
                        <a href="#" class="btn btn-block btn-primary confirmation btn-large text-wrap">{{ trans('messages.rooms.ok') }}</a>
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
  </span>
</div>
</div>

<div class="modal-container modal-transitions wl-modal__modal hide">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="wl-modal">
          <div class="row row-margin-zero">
            <div class="hide-sm col-lg-7 wl-modal__col">
              <div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url({{ $result->photo_name }});">
              </div>
              <div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
                <div class="va-container media">
                  <img class="pull-left host-profile-img media-photo media-round space-2" height="67" width="67" src="{{ $result->users->profile_picture->src }}">
                  <div class="media-body va-middle">
                    <div class="h4 space-1 wl-modal-listing__name">{{ $result->name }}</div>
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
                      <span class="wl-modal-listing__address wl-modal-listing__text">{{ $result->rooms_address->city }}</span>
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
                        <span >@{{ item.name }}</span>
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
                        <input type="text" class="wl-modal-footer__text wl-modal-footer__input" autocomplete="off" id="wish_list_text" value="{{ $result->rooms_address->city }}" placeholder="Name Your Wish List" required>
                      </div>
                      <div class="pull-right">
                        <button id="wish_list_btn" class="btn btn-flat wl-modal-wishlists__footer__save-button btn-contrast">{{ trans('messages.wishlist.create') }}</button>
                      </div>
                    </strong>
                  </form>
                  <div class="text-rausch va-container va-container-v va-container-h">
                    <div class="va-middle text-lead wl-modal-footer__text div_check">{{ trans('messages.wishlist.create_new_wishlist') }}</div>
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
</main>
@stop
{!! Html::script('js/jquery-1.11.3.js') !!}
<style type="text/css">
  .show_off{
    display: none;
  }
  /*#pricing.fixed .tooltip-amenity.tooltip-bottom-middle{display: none !important;}*/
  .tooltip-amenity.tooltip-bottom-middle::before {
    content: "";
    display: inline-block;
    position: absolute;
    bottom: -10px;
    left: 50% !important;
    margin-left: -10px;
    top: auto !important;
    border: 10px solid transparent;
    border-bottom: 0;
    border-top-color: rgba(0, 0, 0, 0.1);
  }
  .tooltip-amenity{border-radius: 3px !important;}
  .tooltip-amenity.tooltip-bottom-middle::after {
    content: "";
    display: inline-block;
    position: absolute;
    bottom: -9px;
    left: 50% !important;
    margin-left: -9px;
    top: auto !important;
    border: 9px solid transparent;
    border-bottom: 0;
    border-top-color: #fff;
  }
  .tooltip-amenity1, .tooltip-amenity2 {
    min-width: 274px !important;
  }
  @media (max-width: 767px) {
    .ad-gallery .ad-image-wrapper .ad-image{
      width: 66% !important;
      left: 16% !important;
    }
  }
  @media (max-width: 1000px) {
    .tooltip-amenity1, .tooltip-amenity2 {
      left: -85px !important;
      top: -120px;
      min-width: 184px !important;
    }
  }
</style>
<script type="text/javascript">

  $(document).ready(function() {
    $('.desc').click(function() {
      $("#des_content").css("display", "block");
    });
    $('.div_check').show();
    var type_id=4;
    $( ".get_type" ).each( function() {
      var new_val=$(this).data('id');
      if(new_val == type_id)
      {
        $( ".new_id"+type_id ).addClass("show_off");
      }
    });

    $("#wish_list_text").keyup(function(){
      $('#wish_list_btn').prop('disabled', true);
      var v_value =  $(this).val();
      var len =v_value.trim().length;
          // alert(len);
          if (len == 0)
          {
            $('#wish_list_btn').prop('disabled', true);
          }
          else{
            $('#wish_list_btn').prop('disabled', false);
          }
        });
// $('#wish_list_btn').click(function(){
//     $('.div_check').show();
// });
});

  
</script>