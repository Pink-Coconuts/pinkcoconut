@extends('template')
@section('main')
@section('available_date_item')
<div class="update">
  <p ng-if="data.is_reserved" class="badge-spots-left">
    @{{data.spots_left}} {{strtoupper(trans('experiences.details.spots_left'))}}
  </p>
  <div class="monthup">@{{format_date(data.date, "ddd, Do MMM")}}
  </div>
  <div class="timing">@{{format_time(data.start_time, "HH:mm")}} - @{{format_time(data.end_time, "HH:mm")}}. @{{data.currency_symbol}}@{{data.price}}
    <span>{{trans('experiences.details.per_person')}}
    </span>
  </div>
</div>
<div class="choosebut">
  @if(@Auth::user()->id  != $host_experience->user_id)
  <a href="javascript:void(0);" id="js_choose_btn_@{{$index}}" class="js-choose-booking-date" ng-click="start_booking($index, data.date, '{{@Auth::user()->id}}')" data-date="@{{data.date}}" >{{trans('experiences.details.choose')}}
  </a>
  @endif
</div>
@endsection
<div class="expdet" ng-controller="host_experience_details" ng-cloak>
  <div class="page-container-responsive">
    <div class="col-md-12">
      <div class="exphole">

         <div class="col-md-5 col-sm-6 col-xs-12 hostexp_right">
          <div class="rightside" id="sticky">
         <!--    <div class="owl-carousel host_experience_photos_slider" id="host_experience_photos_slider">
              @foreach($host_experience->host_experience_photos as $photo)
              <div class="videoimage">
                <img src="{{$photo->image_url}}">
              </div>
              @endforeach
            </div> -->
            <div class="whish_list_exp">
              <span class="wishlist_save save" ng-init="wishlisted={{ (@$is_wishlist > 0 ) ? '1' : '0' }}">
                <span ng-if="wishlisted==1" class="rich-toggle-checked">
                 
                  <i class="icon icon-heart icon-rausch"></i>
                </span>
                <span ng-if="wishlisted==0" class="rich-toggle-unchecked">
                  
                  <i class="icon icon-heart-alt icon-light-gray"></i>
                </span>
              </span>
            </div>


               <div class="host_experience_photos_slider" id="host_experience_photos_slider">
            @if($host_experience->host_experience_photos->count()>1)
            <button class="more_photo" type="button">More photos</button>
            @endif
            <div ng-class="{{($host_experience->host_experience_photos->count()<2) ? 'no_slide' : ' '}}">
            </div>
             <ul id="imageGallery">
              @foreach($host_experience->host_experience_photos as $photo)
                 <li data-thumb="{{$photo->image_url}}" data-src="{{$photo->image_url}}">
                  <img src="{{$photo->image_url}}"/>
                </li>
              @endforeach
            </ul>
            
            </div>



            <div class="mobover">
              <div class="videorate">
                <div class="forrate">{{$host_experience->currency->symbol}}{{$host_experience->session_price}}
                  <span>{{trans('experiences.details.per_person')}}
                  </span>
                </div>
                <div class="star1 all_reviews_popup_btn" id="">
                  @if($result->reviews->count())
                    @if($result->reviews->count()>1)
                    <a href="#all_review_popup" data-effect="mfp-zoom-in">
                    {!! $result->overall_star_rating !!}
                    </a>
                    @else
                    {!! $result->overall_star_rating !!}
                    @endif
                  @endif
                  <span>
                    <span class="num">
                      @if(!$result->reviews->count())
                      {{ trans('messages.rooms.no_reviews_yet') }}
                      @else
                      {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
                      @endif
                    </span>
                  </span>
                </div>
              </div>
              <div class="seedet available_dates_popup_btn" id="">
                <a href="#available_dates_popup" data-effect="mfp-zoom-in">
                  <button class="btn btn-primary">{{trans('experiences.details.see_dates')}}</button>
                </a>
              </div>
            </div>
            <div class="icon1" ng-init="link_copied=0">
              <span class="fonticon">
                <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                  <span class="screen-reader-only">Facebook</span>
                  <i class="icon icon-facebook social-icon-size"></i>
                </a>
                <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/home?status=Love this! {{ $result->name }} {{ "@".$site_name}} Travel  {{ Request::url() }}" target="_blank">
                  <span class="screen-reader-only">Twitter</span>
                  <i class="icon icon-twitter social-icon-size"></i>
                </a>   
                <!-- <a class="share-btn link-icon" href="https://plus.google.com/share?url={{ Request::url() }}"  itemprop="nofollow" rel="publisher" target="_blank">
                  <span class="screen-reader-only">Google+</span>
                  <i class="icon social-icon-size icon-google-plus"></i>
                </a> -->  
                <div class="inline-block" id="share-popups" ng-click="link_copied=0">
                  <a href="#share-popup"  data-effect="mfp-zoom-in">
                    <i class="fa fa-ellipsis-h" aria-hidden="true">
                    </i>
                  </a>
                </div>
              </span>
              <span class="wishlist_save save" ng-init="wishlisted={{ (@$is_wishlist > 0 ) ? '1' : '0' }}">
                <span ng-if="wishlisted==1" class="rich-toggle-checked">
                  {{ trans('messages.wishlist.save_to_wishlist') }}
                  <i class="icon icon-heart icon-rausch"></i>
                </span>
                <span ng-if="wishlisted==0" class="rich-toggle-unchecked">
                  {{ trans('messages.wishlist.save_to_wishlist') }}
                  <i class="icon icon-heart-alt icon-light-gray"></i>
                </span>
              </span>
            </div>
            <div class="price">{{trans('experiences.details.price_may_vary_depending_upon_the_date_select')}}
            </div>
          </div>
        </div>


        <div class="col-md-7 col-sm-6 col-xs-12 ntsbn">
        <div id="rf">
          <div class="expright" ng-init="host_experience_id= '{{$host_experience->id}}'; available_dates = [];">
            <h1>{{$host_experience->title}}
            </h1>
         <!--    <div class="exptyp"> 
              <span class="move">{{@$host_experience->city_details->name}}
              </span>
              <span class="forexp">{{$host_experience->tagline}}
              </span>
            </div> -->

              <div class="experie">
              <div class="col-md-10 col-sm-10 col-xs-10">
                <div class="row">
                 <h3>{{$host_experience->category_details->name}} {{trans('experiences.details.experience')}}
                  </h3>
                  <div class="host">{{trans('experiences.details.hosted_by')}} 
                    <a target="_blank" href="{{url('users/show/'.$host_experience->user_id)}}"> {{$host_experience->user->first_name}}
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2">
                <div class="row">
                  <div class="pro">
                    <a target="_blank" href="{{url('users/show/'.$host_experience->user_id)}}">
                      <img src="{{$host_experience->user->profile_picture->header_src510}}">
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="exper">
          
            <div class="timer">
              <div class="time1">
                <i class="icon icon3-clock" aria-hidden="true">
                </i>{{$host_experience->total_hours}} {{trans('experiences.manage.hours_total')}}
              </div>
              @if($host_experience->host_experience_provides->count() > 0)
              <div class="time1">
                <i class="icon icon3-invoice" aria-hidden="true">
                </i>
                @foreach($host_experience->host_experience_provides as $k => $provide)
                {{@$provide->provide_item->name}} 
                @if($k+2  == $host_experience->host_experience_provides->count())
                {{trans('experiences.details.and')}}
                @elseif($k+1  != $host_experience->host_experience_provides->count())
                ,
                @endif
                @endforeach
              </div>
              @endif
              <div class="time1">
                <i class="icon icon3-chat-oval-filled-speech-bubbles" aria-hidden="true">
                </i>{{trans('experiences.details.offered_in')}} {{$host_experience->language_details->name}}
              </div>
            </div>

            <div class="mobile_social_media">
                  <div class="icon1" ng-init="link_copied=0">
              <span class="fonticon">
                <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                  <span class="screen-reader-only">Facebook</span>
                  <i class="icon icon-facebook social-icon-size"></i>
                </a>
                <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/home?status=Love this! {{ $result->name }} {{ "@".$site_name}} Travel  {{ Request::url() }}" target="_blank">
                  <span class="screen-reader-only">Twitter</span>
                  <i class="icon icon-twitter social-icon-size"></i>
                </a>   
                <!-- <a class="share-btn link-icon" href="https://plus.google.com/share?url={{ Request::url() }}"  itemprop="nofollow" rel="publisher" target="_blank">
                  <span class="screen-reader-only">Google+</span>
                  <i class="icon social-icon-size icon-google-plus"></i>
                </a> -->  
                <div class="inline-block" id="share-popups" ng-click="link_copied=0">
                  <a href="#share-popup"  data-effect="mfp-zoom-in">
                    <i class="fa fa-ellipsis-h" aria-hidden="true">
                    </i>
                  </a>
                </div>
              </span>
              
            </div>
            <div class="price">{{trans('experiences.details.price_may_vary_depending_upon_the_date_select')}}
            </div>
            </div>

          </div>
          <div class="pep hide">
            <div class="pep1">
              <h3>People are eyeing this experience. Over 10,600 people have viewed it this week.
              </h3>
            </div>
            <div class="forpep">
              <img src="
              {{url('images/host_experiences/glass.gif')}}">
            </div>
          </div>
          <div class="about">
            <div class="abthole">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.about_your_host')}}, {{$host_experience->user->first_name}}
                </p>
              </div>
              <div class="forpro">
                <p>{{$host_experience->about_you}}
                </p>
              </div>
            </div>
            <div class="abthole">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.what_will_do')}}
                </p>
              </div>
              <div class="forpro">
                <p>{{$host_experience->what_will_do}}
                </p>
              </div>
            </div>
            @if($host_experience->host_experience_provides->count() > 0)
            <div class="abthole abthole1">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.what_will_provide')}}
                </p>
              </div>
              <div class="forpro1">
                @foreach($host_experience->host_experience_provides as $provide)
                <p>{{$provide->name}}
                  <img src="{{$provide->provide_item->image_url}}" class="provide_icon">
                </p>
                <p>{{$provide->additional_details}}
                </p>
                @endforeach
              </div>
            </div>
            @endif
            @if($host_experience->guest_requirements->minimum_age)
            <div class="abthole abthole1">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.who_can_come')}}
                </p>
              </div>
              <div class="forpro1">
                <p>{{trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $host_experience->guest_requirements->minimum_age])}}
                </p>
              </div>
            </div>
            @endif
            @if($host_experience->notes != '')
            <div class="abthole abthole1">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.notes')}}
                </p>
              </div>
              <div class="forpro1 forpro">
                <p>{{$host_experience->notes}}
                </p>
              </div>
            </div>
            @endif
            <div class="abthole abthole2">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.where_will_be')}}
                </p>
              </div>
              <div class="forpro2">
                <p>{{$host_experience->where_will_be}}
                </p>
              </div>
            </div>
            <div class="map mobile_location_area" id="host_experience_details_map_popup_btn" ng-init="host_experience_location = {{json_encode($host_experience->host_experience_location)}}">
              <a href="#host_experience_map_popup" data-effect="mfp-zoom-in" class="rm_link" >  
                <div class="bg-white mobile_location_title">
                  <h3 class="space-top-2 padd7 space-1 h4">{{trans('experiences.manage.where_will_meet')}}
                  </h3>
                  <p class="space-top-1 padd7 space-2 text-muted">@{{host_experience_location.location_name ? host_experience_location.location_name+' - ': ''}} {{$host_experience->host_experience_location->city}}
                  </p>
                </div>
                <div id="host_experience_details_map" style="width: 100%; height: 350px;">
                </div>
              </a>
            </div>
            <div class="upcome height-limited" ng-class="available_dates_page <= 1 ? 'dot-loading' : ''">
              <h4> {{trans('experiences.details.upcoming_availability')}}
              </h4>
            </div>
            <div class="month" ng-repeat="data in available_dates" ng-if="$index < 3">
              @yield('available_date_item')
            </div>
            <div class="forsee available_dates_popup_btn" id="newshare">
              <a href="#available_dates_popup" data-effect="mfp-zoom-in" class="contlink">{{trans('experiences.details.see_all_available_dates')}}  
              </a>
              @if(Auth::user() && @Auth::user()->id != $host_experience->user_id)
               <a href="#available_dates_popup1" data-effect="mfp-zoom-in" class="conlink1">{{trans('messages.rooms.contact_host')}}
              </a>
              @endif
            </div>
          

            <div class="review">
              <h2>{{trans_choice('messages.header.review',2)}} 
              </h2>
              @if(@$result->reviews->count())
                @php $row_inc = 1 @endphp
                @foreach($result->reviews as $row_review)
                  @if($row_inc <= 3)
                    <div class="review1">
                      <div class="fordiv">
                        <a href="{{ url('users/show/'.$row_review->user_from) }}" class="prorew">
                          <img title="{{ $row_review->users_from->first_name }}" src="{{ $row_review->users_from->profile_picture->src }}">
                        </a>
                      </div>
                      <div class="reviewpro">
                        <a href="{{ url('users/show/'.$row_review->user_from) }}">{{ $row_review->users_from->first_name }}
                        </a>
                        <div class="reviewmonth">{{ $row_review->date_fy }}
                        </div>
                      </div>
                      <p style="word-break: break-all;">{{ $row_review->comments }}
                      </p>
                    </div>
                  @endif
                  @php $row_inc++ @endphp
                @endforeach
              @endif 
            </div>
            <div class="forsee all_reviews_popup_btn" id="">
              @if(!$result->reviews->count())
              {{ trans('messages.rooms.no_reviews_yet') }}
              @else
                @if($result->reviews->count()>3)
                <a href="#all_review_popup" data-effect="mfp-zoom-in">
                  <div class="read">
                    Read all 
                    {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
                  </div>
                </a>
                @else
                <div class="reviews_count">
                    {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
                  </div>
                @endif
                <span class="starright review_stars">
                  <div class=""> 
                    {!! $result->overall_star_rating !!}
                  </div>
                </span>
              @endif              
            </div>
            <div class="abthole abthole1">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.group_size')}}
                </p>
              </div>
              <div class="forpro1">
                <h5>{{trans('experiences.details.there_are_spots_available_in_this_experience', ['count' => $host_experience->number_of_guests])}}
                </h5>
                <p>{{trans('experiences.details.you_dont_fill_all_experience_are_social')}}
                </p>
              </div>
            </div>
            <div class="abthole abthole1">
              <div class="aboutdet">
                <p>{{trans('experiences.manage.guest_requirements')}}
                </p>
              </div>
              <div class="forpro1">
                @if($host_experience->guest_requirements->minimum_age < 18)
                <h4>{{trans('experiences.details.bringing_guests_under_18')}}
                </h4>
                <p>{{trans('experiences.details.bring_guest_under_18_your_responsibility')}}
                </p>
                @endif
                @if($host_experience->guest_requirements->includes_alcohol == 'Yes')
                <h4>{{trans('experiences.manage.alcohol')}}
                </h4>
                <p>{{trans('experiences.details.this_alcohol_includes_only_for_legal_age')}}
                </p>
                @endif
                @if($host_experience->guest_requirements->special_certifications || $host_experience->guest_requirements->additional_requirements)
                <h4>{{trans('experiences.details.from_the_host')}}
                </h4>
                <div class="forpro">
                  <p>
                    {!! $host_experience->guest_requirements->special_certifications ? $host_experience->guest_requirements->special_certifications.'<br>' : '' !!}
                    {{$host_experience->guest_requirements->additional_requirements}}
                  </p>
                </div>
                @endif
                <h4>{{trans('experiences.manage.who_can_come')}}
                </h4>
                <p>{{trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $host_experience->guest_requirements->minimum_age])}}
                  @if($host_experience->is_free_under_2=="Yes")
                  <p>{{trans('experiences.manage.free_for_under_2')}}</p>
                  @endif
                </p>
              </div>
            </div>
            <div class="abthole abthole2 cancellation_policy">
              <div class="aboutdet">
                <p>{{trans('experiences.details.flexible_cancellation_policy')}}
                </p>
              </div>
              <div class="forpro1">
                <h4>{{trans('experiences.details.flexible_cancellation_policy_desc')}}
                  <a target="_blank" href="{{url('hosts_experience_cancellation_policy')}}" class="newa">{{trans('experiences.details.see_cancellation_policy')}}
                  </a>
                </h4>
              </div>
            </div>
          </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>
  <div id="available_dates_popup" class="white-popup mfp-with-anim mfp-hide see_popp" ng-init="available_dates_page = 1;">
    <h2> {{trans('experiences.details.when_do_you_want_to_go')}}
    </h2>
    <div class="month pop_see" ng-repeat="data in available_dates">
      @yield('available_date_item')
    </div>
    <div class="dot-loading height-limited" id="available_dates_next_page" ng-class="available_dates_loading ? 'dot-loading' : '';">
    </div>
  </div>
  <div id="host_experience_map_popup" class="host_experience_map_popup white-popup mfp-with-anim mfp-hide">
    <div class="map">
      <div id="host_experience_details_popup_map" style="width: 100%; height: 600px;">
      </div>
    </div>
  </div>
  <div id="all_review_popup" class="white-popup mfp-with-anim mfp-hide" ng-init="all_review_page = 1;">
    <h2> {{trans_choice('messages.header.review',2)}} 
    </h2>
    <div class="month" ng-repeat="review_data in all_reviews">
      <div class="">
        <div class="fordiv">
          <a href="{{ url('users/show') }}/@{{ review_data.users_from.id }}" class="prorew">
            <img ng-src="@{{ review_data.users_from.profile_picture.src }}">
          </a>
        </div>
        <div class="reviewpro">
          <a href="{{ url('users/show/') }}@{{ review_data.users_from }}">@{{ review_data.users_from.first_name }}
          </a>
          <div class="reviewmonth">@{{ review_data.date_fy }}
          </div>
        </div>
        <p style="word-break: break-all;text-indent:20px">@{{ review_data.comments }}
                      </p>
      </div> 
    </div>
    <div class="dot-loading height-limited" id="all_review_next_page" ng-class="all_review_loading ? 'dot-loading' : '';">
    </div>
  </div>

  
      @if($similar_items->count() > 0)
  <div class="slider" id="slider">

    <div class="page-container-responsive">
      <div class="bottop">
        <h3 class="tit">{{trans('experiences.details.similar_items_in_city', ['city' => $host_experience->city_details->name])}}
        </h3>
        <div class="owl-carousel cate1">
          @foreach($similar_items as $item)
          <a href="{{url('experiences/'.$item->id)}}" target="_blank">
            <div class="product">
              <div class="fixed">
                <img src="{{@$item->host_experience_photos[0]->image_url}}" alt="image">
              </div>
              <div class="nt_ctcity category_city">
                <span>{{$item->category_details->name}} · {{$item->host_experience_location->city}}</span>
              </div>
              <div class="off">{{$item->title}}
              </div>
              <div href="#" class="enq">
                <span>{{$item->currency->symbol}} {{$item->session_price}}
                </span> {{trans('experiences.details.per_person')}}
              </div>

              <div class="" id="">
                @if($item->reviews->count())
                  {!! $item->overall_star_rating !!}
                @endif
                <span class="numnew">
              @if(!$item->reviews->count())

              @else
              {{ $item->reviews->count() }} {{ trans_choice('messages.header.review',$item->reviews->count()) }}
              @endif
              </span>
              </div>
              
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </div>
  
  </div>
    @endif


  <div id="share-popup" class="white-popup mfp-with-anim sharepop mfp-hide">
    <h4>{{ trans('messages.rooms.share') }}</h4>
    <label class="share-popup-title">
      {{ @$result->title }}
    </label>
    <ul class="share-popup-ul">
      <li>
        <a class="share-btn link-icon" data-email-share-link="" data-network="email" rel="nofollow" title="{{ trans('messages.login.email') }}" href="mailto:?subject={{ $result->title }}&amp;body=Check out {{ @$host_experience->city_details->name }} - {{ $result->title }}  - {{ Request::url() }}">
          <i class="share-popup-icon icon icon-envelope social-icon-size"></i>
          <label class="share-popup-label">Email</label>
        </a>
      </li>
      <li>
        <a class="share-btn link-icon" data-network="pinterest" rel="nofollow" title="Pinterest" href="https://www.pinterest.co.uk/pin/create/button/?url={{ Request::url() }}&media={{ $result->host_experience_photos[0]->image_url }}&description={{ $result->title }}" target="_blank">
          <i class="share-popup-icon icon icon-pinterest social-icon-size"></i>
          <label class="share-popup-label">Pinterest</label>
        </a>
      </li>
      <li>
        <div id="copy_div"></div>
        <i class="fa fa-clipboard copy-label-i" aria-hidden="true"></i>
        <label data-copy="{{ Request::url() }}" class="share-popup-label share-copy-label">
          <span ng-if="!link_copied">Copy Link</span>
          <span ng-if="link_copied">Link Copied</span>
        </label>
      </li>
    </ul>
  </div>

  <div class="modal-container modal-transitions wl-modal__modal hide">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <div class="wl-modal">
            <div class="row row-margin-zero">
              <div class="hide-sm col-lg-7 wl-modal__col">
                <div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url({{ @$result->host_experience_photos[0]->image_url }});">
                </div>
                <div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
                  <div class="va-container media">
                    <img class="pull-left host-profile-img media-photo media-round space-2" height="67" width="67" src="{{ @$result->user->profile_picture->header_src }}">
                    <div class="media-body va-middle">
                      <div class="h4 space-1 wl-modal-listing__name">{{ @$result->title }}</div>
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
                        <span class="wl-modal-listing__address wl-modal-listing__text">{{ @$host_experience->city_details->name }}</span>
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
                          <input type="text" class="wl-modal-footer__text wl-modal-footer__input" autocomplete="off" id="wish_list_text" value="{{ @$host_experience->city_details->name }}" placeholder="Name Your Wish List" required>
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
  <div id="available_dates_popup1" class="white-popup mfp-with-anim needmr mfp-hide">
    <form name="host_experience_contact_host_form" id="host_experience_contact_host_form" method="post" action="{{url('experiences/'.$host_experience->id.'/contact_host')}}">
      <div class="nedd">
        <h4>{{trans('experiences.details.need_more_info_about_name', ['name' => $host_experience->title])}}
        </h4>
        <p>{!! trans('experiences.details.if_you_have_general_faq_link', ['link' => '<a href="'.url('help').'">'.trans('experiences.details.visit_our_faq').'</a>']) !!}
        </p>
      </div>
      <div class="needimg" ng-init="contact_host_message = ''">
        <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank">
          <img src="{{@$host_experience->user->profile_picture->header_src510}}">
        </a>
      </div>
      <textarea class="neddtext" id="contact_host_message" name="contact_host_message" ng-model="contact_host_message" ></textarea>
      <p class="text-danger" id="contact_host_message_error" style="display: none;">{{trans_choice('messages.dashboard.message', 1)}} {{trans('messages.login.field_is_required')}}</p>
      <a href="javascript:void(0)" class="sendmr" id="contact_host_form_submit">{{trans('messages.your_reservations.send_message')}}</a>
    </form>
  </div>
</div>

@stop
@push('scripts')
<script src="{{url('js/host_experiences/magnify.js')}}">
</script>
<link rel="stylesheet" type="text/css" href="{{url('css/host_experiences/magnify.css')}}"> 
@endpush
