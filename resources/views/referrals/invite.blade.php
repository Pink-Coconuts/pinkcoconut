@extends('template')
@section('main')
<main id="site-content" role="main"> 
<div class="media-photo media-photo-block referrals-hero">
  <div class="media-cover media-cover-dark referrals-bg-img"></div>
  <div class="row row-table row-full-height">
    <div class="col-sm-12 col-middle text-center text-contrast">
      <div class="hide-sm">
      </div>
      <h1 class="referrals-heading text-special row-space-3 hide-sm">
        {{ trans('messages.referrals.earn_free_coupons',['site_name'=>$site_name]) }}!<br>{{ trans('messages.referrals.get_up_to') }} {{ $result->value(5) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.every_friend_invite') }}.
      </h1>
      <h3 class="referrals-heading text-special row-space-3 show-sm">
        {{ trans('messages.referrals.earn_up_to') }} {{ $result->value(5) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.everyone_invite') }}.
      </h3>
      <div class="page-container-responsive hide-sm">
        <a href="{{ url('login') }}" class="btn btn-primary btn-large" data-login-modal="">{{ trans('messages.referrals.login_invite_friends') }}</a>
<p class="text-lead h3 row-space-top-4">
  {{ trans('messages.referrals.dont_have_an_account') }} <a href="{{ url('signup_login') }}">{{ trans('messages.referrals.signup') }}</a>.
</p>

      </div>
    </div>
  </div>
  </div>


<section class="page-container-responsive row-space-top-4 row-space-6">
  <div class="row">
    <div class="col-sm-12 text-center">
      <div class="page-container-responsive text-justified show-sm">
        <a href="{{ url('login') }}" class="btn btn-primary btn-large" data-login-modal="">{{ trans('messages.referrals.login_invite_friends') }}</a>
<p class="text-lead h3 row-space-top-4">
  {{ trans('messages.referrals.dont_have_an_account') }} <a href="{{ url('signup_login') }}">{{ trans('messages.referrals.signup') }}</a>.
</p>

      </div>
      <p class="text-lead">
        {{ trans('messages.referrals.invite_your_friends',['site_name'=>$site_name]) }}. <br>{{ trans('messages.referrals.when_send_friend') }} {{ $result->value(5) }}{{ $result->value(4) }} {{ trans('messages.payments.in') }} {{ $site_name }} {{ trans('messages.referrals.credit_you_will_get') }} {{ $result->value(5) }}{{ $result->value(2) }} {{ trans('messages.referrals.when_they_travel') }} {{ $result->value(5) }}{{ $result->value(3) }} {{ trans('messages.referrals.when_they_host') }}. <br>{{ trans('messages.referrals.available_travel_credit') }}.
      </p>

      <!-- <a class="text-lead" href="{{ url('/') }}/referrals/terms_and_conditions">
        <small>
          Referrals Terms and Conditions
        </small>
      </a> -->
    </div>
  </div>
</section>
</main>
@stop