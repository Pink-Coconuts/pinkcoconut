@extends('template')
@section('main')
<main id="site-content" role="main">

  <div id="recommendation_container" class="clearfix container">
  </div>

  <div class="user-pro-page page-container page-container-responsive row-space-top-4 row-space-8">
    <div class="row">
      <div class="col-lg-3 col-md-4 profile-view-left hide-sm">
        <div id="user" class="row-space-4">
          <div>
            <div class="row-space-2" id="user-media-container">
              <div id="slideshow" class="slideshow">
                <div class="slideshow-preload"></div>
                <ul class="slideshow-images">
                  <li class="active media-photo media-photo-block">
                    <img alt="{{ $result->first_name }}" class="img-responsive" src="{{ $result->profile_picture->src }}" title="{{ $result->first_name }}" width="225" height="225" style="height:225px;">
                  </li>
                  <li class="media-photo media-photo-block"></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        @if($result->school || $result->work || $result->languages_name)
        <div class="panel row-space-4">
          <div class="panel-header">
            {{ trans('messages.profile.about_me') }}
          </div>
          <div class="panel-body">
            <dl>
              @if($result->school)
              <dt>{{ trans('messages.profile.school') }}</dt>
              <dd>{{ $result->school }}</dd>
              @endif
              @if($result->work)
              <dt>{{ trans('messages.profile.work') }}</dt>
              <dd>{{ $result->work }}</dd>
              @endif
              @if($result->languages_name)
              <dt>{{ trans('messages.profile.languages') }}</dt>
              <dd style="word-wrap: break-word;">{{ $result->languages_name }}</dd>
              @endif
            </dl>
          </div>
        </div>
        @endif

        @if($result->users_verification->show() || $result->verification_status == 'Verified')
        <div class="panel row-space-4 verifications">
          <div class="panel-header row">
            <div class="pull-left">
              {{ trans('messages.dashboard.verifications') }}
            </div>
          </div>
          <div class="panel-body">
            <ul class="list-unstyled">
              @if($result->users_verification->email == 'yes')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      {{ trans('messages.dashboard.email_address') }}
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.verified') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              @if($result->users_verification->phone_number == 'yes')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      {{ trans('messages.profile.phone_number') }}
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.verified') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              @if($result->users_verification->facebook == 'yes')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      Facebook
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.validated') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              @if($result->users_verification->google == 'yes')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      Google
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.validated') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              @if($result->users_verification->linkedin == 'yes')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      LinkedIn
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.validated') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
              @if($result->verification_status == 'Verified')
              <li class="row row-condensed">
                <div class="media">
                  <i class="icon icon-ok icon-lima h3 pull-left"></i>
                  <div class="media-body">
                    <div>
                      ID Verification
                    </div>
                    <div class="text-muted">
                      {{ trans('messages.dashboard.verified') }}
                    </div>
                  </div>
                </div>
              </li>
              @endif
            </ul>
    <!-- <a href="{{ url('/') }}/users/edit_verification">
      {{ trans('messages.dashboard.add_verifications') }}
    </a> -->
  </div>
</div>
@endif
</div>

<div class="col-lg-9 col-md-12 profile-view-right">
  <div class="row row-space-4 tet1">
    <div class="col-sm-4 show-sm">
      <div class="media-photo media-photo-block media-round">
        <img alt="{{ $result->first_name }}" class="img-responsive" src="{{ $result->profile_picture->src }}" title="{{ $result->first_name }}">
      </div>
    </div>
    <div class="col-sm-8 col-md-12 col-lg-12">
      <h1>
        {{ trans('messages.profile.hey_iam',['first_name'=>$result->first_name]) }}!
      </h1>
      <div class="h5 row-space-top-2">
        @if($result->live)
        <a href="{{ url('s?location='.$result->live) }}" class="link-reset">{{ $result->live }}</a>
        ·
        @endif
        <span class="text-normal">
          {{ trans('messages.profile.member_since') }} {{ $result->since }}
        </span>
      </div>
      <div class="flag_controls text-muted row-space-top-2 hide"></div>
      @if(@Auth::user()->id == $result->id)
      <div class="edit_profile_container row-space-3">
        <a href="{{ url('users/edit') }}">{{ trans('messages.header.edit_profile') }}</a>
      </div>
      @endif
    </div>
  </div>
  <div class="row-space-top-2 show-sm">
    @if($result->school || $result->work || $result->languages)
    <div class="panel row-space-4">
      <div class="panel-header">
        {{ trans('messages.profile.about_me') }}
      </div>
      <div class="panel-body">
        <dl>
          @if($result->school)
          <dt>{{ trans('messages.profile.school') }}</dt>
          <dd>{{ $result->school }}</dd>
          @endif
          @if($result->work)
          <dt>{{ trans('messages.profile.work') }}</dt>
          <dd>{{ $result->work }}</dd>
          @endif
          @if($result->languages)
          <dt>{{ trans('messages.profile.languages') }}</dt>
          <dd style="word-wrap: break-word;">{{ $result->languages_name }}</dd>
          @endif
        </dl>
      </div>
    </div>
    @endif

    @if($result->users_verification->show())
    <div class="panel row-space-4 verifications">
      <div class="panel-header row">
        <div class="pull-left">
          {{ trans('messages.dashboard.verifications') }}
        </div>
      </div>
      <div class="panel-body">
        <ul class="list-unstyled">
          @if($result->users_verification->email == 'yes')
          <li class="row row-condensed">
            <div class="media">
              <i class="icon icon-ok icon-lima h3 pull-left"></i>
              <div class="media-body">
                <div>
                  {{ trans('messages.dashboard.email_address') }}
                </div>
                <div class="text-muted">
                  {{ trans('messages.dashboard.verified') }}
                </div>
              </div>
            </div>
          </li>
          @endif
          @if($result->users_verification->phone_number == 'yes')
          <li class="row row-condensed">
            <div class="media">
              <i class="icon icon-ok icon-lima h3 pull-left"></i>
              <div class="media-body">
                <div>
                  {{ trans('messages.profile.phone_number') }}
                </div>
                <div class="text-muted">
                  {{ trans('messages.dashboard.verified') }}
                </div>
              </div>
            </div>
          </li>
          @endif
          @if($result->users_verification->facebook == 'yes')
          <li class="row row-condensed">
            <div class="media">
              <i class="icon icon-ok icon-lima h3 pull-left"></i>
              <div class="media-body">
                <div>
                  Facebook
                </div>
                <div class="text-muted">
                  {{ trans('messages.dashboard.validated') }}
                </div>
              </div>
            </div>
          </li>
          @endif
          @if($result->users_verification->google == 'yes')
          <li class="row row-condensed">
            <div class="media">
              <i class="icon icon-ok icon-lima h3 pull-left"></i>
              <div class="media-body">
                <div>
                  Google
                </div>
                <div class="text-muted">
                  {{ trans('messages.dashboard.validated') }}
                </div>
              </div>
            </div>
          </li>
          @endif
          @if($result->users_verification->linkedin == 'yes')
          <li class="row row-condensed">
            <div class="media">
              <i class="icon icon-ok icon-lima h3 pull-left"></i>
              <div class="media-body">
                <div>
                  LinkedIn
                </div>
                <div class="text-muted">
                  {{ trans('messages.dashboard.validated') }}
                </div>
              </div>
            </div>
          </li>
          @endif
        </ul>
    <!-- <a href="{{ url('/') }}/users/edit_verification">
      {{ trans('messages.dashboard.add_verifications') }}
    </a> -->
  </div>
</div>
@endif
</div>
<div class="row-space-top-2 tet1">
  <p>{{ $result->about }}</p>
</div>
<div class="row-space-6 row-space-top-6 row row-condensed tet1">
  @if($reviews_count > 0)
  <div class="col-md-3 col-sm-4 lang-chang-label">
    <a href="#reviews" rel="nofollow" class="link-reset">
      <div class="text-center text-wrap toms">
        <div class="badge-pill h3">
          <span class="badge-pill-count">{{ $reviews_count }}</span>
        </div>
        <div class="row-space-top-1">{{ trans_choice('messages.header.review',1) }}</div>
      </div>
    </a>
    <span></span>
  </div>
  @endif
</div>
<!-- Start User Rooms & Experience Details -->
<div class="profile-room-slides row">
  <div class="profile_workshop">
    @if(!$rooms->isEmpty() )
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 nwt_slid">
      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 newsl" style="padding:0px;margin: 0px 0px 15px;">
        <h3 class="rowHeader pull-left">
          {{ trans('messages.header.homes') }}
        </h3>
      </div>
      <div class="home-bx-slider col-md-12 col-lg-12 col-sm-12 col-xs-12 " style="position:relative;padding:0px;">
        <div class="owl-carousel profile_slider">
          @foreach($rooms as $rooms_data)
          @if($rooms_data->rooms_price->night != '')
          @if($rooms_data->photo_name != '')
          <div class="cateimg">
            <a href="{{ $rooms_data->link }}">
              <img src="{{ $rooms_data->photo_name }} " >
            </a>
            <div class="panel-body panel-card-section">
              <div class="media">
                <div class="category_city hm_cate">
                  <span class="pull-left">{{ $rooms_data->room_type_name }}</span>
                  <span class="pull-left dot-cont">·</span>
                  <span class="pull-left">{{ $rooms_data->beds }} {{ $rooms_data->bed_lang }}</span>
                </div>
                <a href="{{ $rooms_data->link}}" target="listing_10001" class="text-normal" style="text-decoration:none !important;">
                  <h3 title="{{ $rooms_data->name}}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1" style="width:95%;">
                    {{ $rooms_data->name }}
                  </h3>
                </a>                          
                <div class="exp_price">
                  <span>
                    {{ $rooms_data->rooms_price->currency->symbol }}{{ $rooms_data->rooms_price->night }}
                  </span>
                  {{ __("messages.rooms.per_night") }}
                  <span ng-if="{{ $rooms_data->booking_type == 'instant_book' }}">
                    <i class="icon icon-instant-book icon-beach"></i>
                  </span>
                </div>
                @if($rooms_data->overall_star_rating != "")
                <div itemprop="description" class="pull-left text-muted rt_set listing-location text-truncate"><a href=" {{ $rooms_data->link }}" class="text-normal link-reset pull-left ">
                  <span class="pull-left">
                    {!! $rooms_data->overall_star_rating !!}
                  </span>
                  <a href=" {{ $rooms_data->link }}">
                    <span class="pull-left mr_mb" style="padding-left: 5px;">
                      <span class="pull-left r-count ng-binding " ng-if="{{ $rooms_data->reviews_count > 0 }} " style="font-size:15px;color:#555;">
                        {{ $rooms_data->reviews_count }}
                      </span>
                      <span ng-if="{{ $rooms_data->overall_star_rating!='' }}" class="pull-left r-label ng-binding" style="font-size:12px;color:#555;">
                        {{ $rooms_data->reviews_count_lang }}
                      </span>
                    </span>
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
          @endif
          @endif
          @endforeach
        </div>
      </div>
    </div>
    @endif
    {{--HostExperienceBladeCommentStart--}}
    @if(!$host_experiences->isEmpty() )
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 home_exprt">
      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 " style="padding:0px;margin: 45px 0px 15px;">
        <h3 class="rowHeader pull-left">
          {{ __('experiences.home.experiences') }}
        </h3>
      </div>
      <div class="home-bx-slider1 col-md-12 col-lg-12 col-sm-12 col-xs-12 " style="position:relative;padding:0px;">
        <div class="owl-carousel profile_slider">
          @foreach($host_experiences as $host_experience)
          <div class="cateimg">
            <a href="{{ $host_experience->link }}">
              <img src="{{ $host_experience->photo_resize_name }}"  >
            </a>
            <div class="panel-body panel-card-section" style="padding:10px 0px;">
              <div class="media home_media">
                <div class="category_city nt_ctcity">
                  <span class="pull-left">{{$host_experience->category_name}}</span>
                  <span class="pull-left dot-cont">·</span>
                  <span class="pull-left">{{$host_experience->city_details->name}}</span>
                </div>
                <a href="{{ $host_experience->link }}" target="listing_{{$host_experience->id}}" class="text-normal" style="text-decoration:none !important;">
                  <h3 title="{{$host_experience->title}}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1 ng-binding">
                    {{str_limit($host_experience->title, 50)}}
                  </h3>
                </a>
                <div class="exp_price">
                  {{$host_experience->currency->symbol }}{{ $host_experience->session_price }} {{ trans("messages.wishlist.per_guest") }}
                </div>
                <div class="star-rating-wrapper">
                  @if($host_experience->overall_star_rating != "")
                  <div class="category_city rating_cls">
                    <span class="pull-left">{!! $host_experience->overall_star_rating !!}</span>
                    <span class="pull-left dot-cont">·</span>
                    <span class="pull-left">{{ $host_experience->reviews_count }} {{ trans_choice('messages.header.review',$host_experience->reviews_count) }}</span>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
    {{--HostExperienceBladeCommentEnd--}}
  </div>
</div>
<!-- End User Rooms & Experience Details -->
@if($wishlists->count())
<div class="social_connections_and_reviews">
  <div class="row-space-6">
    <h2 class="row-space-3">
      {{ trans_choice('messages.header.wishlist',$wishlists->count()) }}
      <small>({{ $wishlists->count() }})</small>
    </h2>
    <div class="row">
      @foreach($wishlists as $row)
      <div class="col-lg-4 col-md-6 lang-chang-label arr_profile">
        <div class="panel">
          <a href="{{ url('wishlists/'.$row->id) }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
            <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('@if($row->saved_wishlists->count() > 0){{ $row->saved_wishlists[0]->photo_name }} @endif');">
            </div>
            <div class="row row-table row-full-height">
              <div class="col-12 col-middle  text-contrast ">
                <div class="count_section">
                  <div class="">
                    <div class="">{{ $row->name }}</div>
                  </div>
                  <span class="">
                    @if($row->rooms_count > 0)
                    {{ $row->rooms_count }} {{ trans('messages.header.home') }}
                    @endif
                    @if($row->rooms_count > 0 && $row->host_experience_count > 0)
                    .
                    @endif
                    @if($row->host_experience_count > 0)
                    {{ $row->host_experience_count }} {{ trans_choice('messages.home.experience',$row->host_experience_count) }}
                    @endif
                  </span>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      @endforeach
    </div>
    <div class="row-space-top-2">
    </div>
  </div>
</div>
@endif

@if($reviews_count > 0)
<div class="social_connections_and_reviews">
  <div id="reviews" class="reviews row-space-4">
    <h2>
      {{ trans_choice('messages.header.review',2) }}
      <small>({{ $reviews_count }})</small>
    </h2>
    <div>
      @if($reviews_from_hosts->count() > 0)
      <div class="reviews_section as_guest row-space-top-3">
        <h4 class="row-space-4">
          {{ trans('messages.profile.reviews_from_hosts') }}
        </h4>
        <div class="reviews">
          @foreach($reviews_from_hosts->get() as $row_host)
          <div id="review-{{ $row_host->id }}" class="row text-center-sm">
            <div class="col-md-2 col-sm-12 ">
              <div class="avatar-wrapper">
                <a class="text-muted" href="{{ url('/') }}/users/show/{{ $row_host->user_from }}">
                  <div class="media-photo media-round row-space-1">
                    <img width="68" height="68" style="display: inline;" title="{{ $row_host->users_from->first_name }}" src="{{ $row_host->users_from->profile_picture->src }}" class="lazy" alt="{{ $row_host->users_from->first_name }}">
                  </div>
                  <div class="text-center profile-name text-wrap">
                    {{ $row_host->users_from->first_name }}
                  </div>
                </a>          <div class="text-muted date show-sm">{{ $row_host->date_fy }}</div>
              </div>
            </div>
            <div class="col-md-10 col-sm-12">
              <div class="row-space-2">
                <div class="comment-container expandable expandable-trigger-more row-space-2 expanded">
                  <div class="expandable-content">
                    <p>{{ $row_host->comments }}</p>
                    <div class="expandable-indicator"></div>
                  </div>
                  <a href="{{ url('/') }}/users/show/49483864#" class="expandable-trigger-more text-muted">
                    <strong>+ {{ trans('messages.profile.more') }}</strong>
                  </a>
                </div>
                <div class="text-muted date hide-sm pull-left">
                  @if($row_host->users_from->live)
                  {{ trans('messages.profile.from') }} <a class="link-reset" href="{{ url('/') }}/s?location={{ $row_host->users_from->live }}">{{ $row_host->users_from->live }}</a> ·
                  @endif
                  {{ $row_host->date_fy }}
                </div>
              </div>
            </div>
          </div>
          <div class="row row-space-2 line-separation">
            <div class="col-10 col-offset-2">
              <hr>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
      @if($reviews_from_guests->count() > 0)
      <div class="reviews_section as_guest row-space-top-3">
        <h4 class="row-space-4">
          {{ trans('messages.profile.reviews_from_guests') }}
        </h4>
        <div class="reviews">
          
          @foreach($reviews_from_guests->get() as $row_guest)
          <div id="review-{{ $row_guest->id }}" class="row text-center-sm">
            <div class="col-md-2 col-sm-12 lang-chang-label">
              <div class="avatar-wrapper">
                <a class="text-muted" href="{{ url('/') }}/users/show/{{ $row_guest->user_from }}">
                  <div class="media-photo media-round row-space-1">
                    <img width="68" height="68" style="display: inline;" title="{{ $row_guest->users_from->first_name }}" src="{{ $row_guest->users_from->profile_picture->src }}" class="lazy" alt="{{ $row_guest->users_from->first_name }}">
                  </div>
                  <div class="text-center profile-name text-wrap">
                    {{ $row_guest->users_from->first_name }}
                  </div>
                </a>          <div class="text-muted date show-sm">{{ $row_guest->date_fy }}</div>
              </div>
            </div>
            <div class="col-md-10 col-sm-12">
              <div class="row-space-2">
                <div class="comment-container expandable expandable-trigger-more row-space-2 expanded">
                  <div class="expandable-content">
                    <p>{{ $row_guest->comments }}</p>
                    <div class="expandable-indicator"></div>
                  </div>
                 

                  <a href="{{ url('/') }}/users/show/" class="expandable-trigger-more text-muted">
                    <strong>+ {{ trans('messages.profile.more') }}</strong>
                  </a>
                </div>
                <div class="text-muted date hide-sm pull-left">
                  @if($row_guest->users_from->live)
                  {{ trans('messages.profile.from') }} <a class="link-reset" href="{{ url('/') }}/s?location={{ $row_guest->users_from->live }}">{{ $row_guest->users_from->live }}</a> ·
                  @endif
                  {{ $row_guest->date_fy }}
                </div>
              </div>
            </div>
          </div>
          <div class="row row-space-2 line-separation">
            <div class="col-10 col-offset-2">
              <hr>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endif
</div>
</div>
</div>

<div id="staged-photos"></div>

</main>

@stop