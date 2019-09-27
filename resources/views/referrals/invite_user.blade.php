@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="footer">
      
<section class="error-section">
<div class="alert alert-with-icon alert-error  hide" id="contact-importer-alert">
  <i class="icon alert-icon icon-alert-alt"></i>
      <a href="javascript:void();" class="alert-close"></a>
    {{ trans('messages.referrals.couldnot_find_contact') }}.
</div>
</section>

<div class="media-photo media-photo-block referrals-hero" style = "width:100%;">
  <div class="media-cover media-cover-dark referrals-bg-img"></div>
  <div class="row row-table row-full-height">
    <div class="col-sm-12 col-middle text-center text-contrast">
      <div class="page-container-responsive">
        <h1 class="referrals-heading text-special row-space-4 hide-sm">
          {{ trans('messages.referrals.earn_up_to') }} {{ $result->value(5) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.everyone_invite') }}.
        </h1>
        <h3 class="referrals-heading text-special row-space-4 show-sm">
          {{ trans('messages.referrals.earn_up_to') }} {{ $result->value(5) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.everyone_invite') }}.
        </h3>
      </div>
    </div>
  </div>
</div>

<section class="page-container-responsive row-space-top-3 row-space-6">
  <div class="row">
    <div class="col-sm-12">
      <p class="text-center text-lead">
        {{ trans('messages.referrals.send_friend') }} {{ $result->value(5) }}{{ $result->value(4) }} {{ $site_name }} {{ trans('messages.referrals.credit_you_will_get') }} {{ $result->value(5) }}{{ $result->value(2) }} {{ trans('messages.referrals.when_they_travel') }} {{ $result->value(5) }}{{ $result->value(3) }} {{ trans('messages.referrals.when_they_host') }}.
      </p>
    </div>
  </div>

  <div id="share-container" class="row">
    <fieldset class="col-sm-12">
      <div id="email-entry" class="row row-condensed row-space-1">
        <div class="col-sm-12 col-md-6 col-lg-5 col-lg-push-1 lang-new-stl">
          <div class="input-addon">
            <div id="bloodhound">
            <span class="twitter-typeahead" style="position: relative; background-color: white;">
            <input type="text" class="typeahead input-large tt-hint" autocorrect="off" readonly="" autocomplete="off" spellcheck="false" tabindex="-1" dir="ltr" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 0; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);">
            <input id="email-list" type="text" class="typeahead input-large tt-input" autocorrect="off" placeholder="{{ trans('messages.referrals.add_friends_email') }}" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: white;"
            ><pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Circular, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></pre>
            <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;"><div class="tt-dataset tt-dataset-email-autocomplete"></div></div>
            </span>
            </div>
            <a href="javascript:;" class="btn btn-primary btn-large input-suffix" id="send-email">{{ trans('messages.inbox.send') }}</a>
          </div>
          <p class="instructions-text help-block">
            <span id="info_message">
              {{ trans('messages.referrals.separate_email_commas') }}.
            </span>
            <span id="success_message">
              {{ trans('messages.referrals.invitation_sent') }}!
            </span>
            <span id="error_message">
              {{ trans('messages.referrals.emails_invalid') }}!
            </span>
          </p>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-5 col-lg-push-1 referral-link-share ">
          <div class="input-addon invite_share">
            <input id="share-link" class="input-large" type="text" value="{{ url('c/'.$username) }}" readonly="">

              <a class="btn btn-primary btn-large input-suffix share-btn btn-facebook_alt" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ url('c/'.$username) }}" target="_blank">
                <i class="icon icon-facebook float-none ico-size"></i>
                {{ trans('messages.inbox.facebook') }}
              </a>
          </div>
          <div class="instructions-text">
            <div class="social-share-widget invite_twitter">
  <span class="share-title">
    {{ trans('messages.rooms.share') }}:
  </span>
  <span class="share-triggers">
      <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=Travel+on+{{ $site_name }}+and+get+{{ $result->value(5) }}{{ $result->value(4) }}+in+travel+credit!+{{ '@'.$site_name }}&amp;url={{ url('c/'.$username) }}" target="_blank">
        <span class="screen-reader-only">Twitter</span>
        <i class="icon icon-twitter social-icon-size"></i>
      </a>
    <span class="hide more-btn">
      ···
    </span>
  </span>
  
</div>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</section>

<div class="sent-referrals-overview space-top-5">
<div class="page-container-responsive">
<div class="space-top-7 space-top-sm-4 space-4 space-sm-6">
<div class="status-section">
<h3 class="text-center space-6">
<span>
<span>{{ trans('messages.referrals.you_have_got') }} </span>
<span>
<span class="text-babu">
<span>{{ $result->value(5).$credited_amount }}</span>
</span>
</span>
<span> {{ trans('messages.referrals.travel_credit_spend') }}!</span>
</span>
</h3>
@if($result->value(1) <= $creditable_amount)
<p class="text-center space-4 text-danger">{{trans('messages.referrals.reached_max_amount', ['amount' => $result->value(5).$result->value(1)])}}</p>
@endif
<div>
@foreach($referrals as $row)
<div class="media sent-referral">
<div class="hide-sm">
<a href="{{ url('users/show/'.$row->friend_users->id) }}">
<img class="media-photo media-round pull-left referred-profile-pic" src="{{ $row->friend_users->profile_picture->header_src }}" alt="{{ $row->friend_users->first_name }}" width="50" height="50">
</a>
</div>
<div class="media-body">
<div class="row row-space-top-1 referred-row">
<div class="referred-row-body">
<div class="col-sm-7 col-md-9">
<span class="text-wrap">
<span>
<span>
<a href="{{ url('users/show/'.$row->friend_users->id) }}" class="link-reset text-wrap">{{ $row->friend_users->first_name }}</a>
</span>
<span> </span>
<span>
</span>
</span>
</span>
</div>
<div class="col-sm-5 col-md-3 text-right">
    @if($row->creditable_amount > 0) 
<span class="text-gray">{{ $row->currency->symbol }}{{ $row->if_friend_guest_amount + $row->if_friend_host_amount }} {{ trans('messages.referrals.pending') }}</span>
    @endif
</div>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
</main>
@stop