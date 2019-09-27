@extends('template')
@section('main')
<main id="site-content" role="main">
<div class="hide">
  <img src="{{ $result->profile_picture->src }}">
</div>
<div class="media-photo media-photo-block referrals-invitation-hero full_wid">
  <div class="media-cover media-cover-dark referrals-bg-img"></div>
  <div class="row row-table row-full-height">
    <div class="col-11 col-center col-middle text-center text-contrast">
      <div class="profile-circle-responsive media-photo media-round travel_img">
        <a href="{{ url('/') }}/users/show/{{ $result->id }}" target="_blank" title="{{ $result->first_name }}"><img alt="{{ $result->first_name }}" class="profile-img-responsive" src="{{ $result->profile_picture->src }}" title="{{ $result->first_name }}" width="100%" height="100%"></a>
      </div>
      <div class="h1 row-space-top-1 row-space-2 text-special">
          {{ $result->first_name }} {{ trans('messages.referrals.gave_you') }} {{ $referral->value(5) }}{{ $referral->value(4) }} {{ trans('messages.referrals.to_travel') }}.
      </div>

      <p class="text-lead hide-sm">
        {{ $site_name }} {{ trans('messages.referrals.best_way_to_rent_unique') }}.
      </p>

      <a href="{{ url('/') }}/signup_login?referral={{ $result->id }}" class="btn btn-primary btn-large" data-signup-modal="" id="signup_login_button">{{ trans('messages.referrals.signup_to_claim') }}</a>
      <p class="text-lead row-space-top-3 show-sm">
        {{ $site_name }} {{ trans('messages.referrals.best_way_to_rent_unique') }}.
      </p>
      <p></p>
    </div>
  </div>
</div>

<div class="panel panel-dark">

  <div class="page-container page-container-responsive">
    <div class="row row-space-top-8 text-center">
      <h1 class="text-special">
        {{ trans('messages.referrals.how_it_works') }}
      </h1>
    </div>
    <div class="row text-center">
      <p class="text-lead">
        {{ trans('messages.referrals.rent_unique') }}.
      </p>
    </div>

    <div class="row">
  <div class="col-10 col-push-1">
    <ul class="supporting-points row row-space-top-2 list-unstyled">
      <li class="col-4 supporting-point explore">
        <a href="javascript:void();" class="icon"></a>
        <div class="point-text">
          <h3 class="text-special">
            {{ trans('messages.referrals.explore') }}
          </h3>
          <p>
            {{ trans('messages.referrals.find_perfect_place') }}.
          </p>
        </div>
      </li>

      <li class="col-4 supporting-point contact">
        <a href="javascript:void();" class="icon"></a>
        <div class="point-text">
          <h3 class="text-special">
            {{ trans('messages.referrals.contact') }}
          </h3>
          <p>
            {{ trans('messages.referrals.message_hosts') }}.
          </p>
        </div>
      </li>

      <li class="col-4 supporting-point book">
        <a href="javascript:void();" class="icon"></a>
        <div class="point-text">
          <h3 class="text-special">
            {{ trans('messages.referrals.book') }}
          </h3>
          <p>
            {{ trans('messages.referrals.view_your_itinerary') }}.
          </p>
        </div>
      </li>
    </ul>
  </div>
</div>

  </div>

</div>

    </main>
@stop