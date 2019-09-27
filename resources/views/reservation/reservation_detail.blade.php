<style type="text/css">
 html[lang="ar"] .tooltip-amenity2 {
    top: -48px !important;
    right: 115px !important;
    left: auto !important;
}
 html[lang="ar"] .panel-footer {
    text-align: left;
}
</style>
@extends('template')

@section('main')
    <main id="site-content" role="main">
    
    @include('common.subheader')  

  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3 col-sm-12 lang-chang-label">
        
        @include('common.sidenav')

      </div>

      <div class="col-md-9 col-sm-12 ">
        <!-- <div class="your-listings-flash-container"></div> -->
        

<div class="panel row-space-4">
  <div class="panel-header" style="overflow:hidden;">
  <div class="col-6 right-pull"> 
    {{ trans('messages.your_reservations.requested_reservation') }}
    </div>
    @if($result->status == 'Pending')
    <div class="pull-right reserve-left">
        <span class="label label-info">
    <i class="icon icon-time"></i>
    {{ trans('messages.your_reservations.expires_in') }}

    
    <span class="countdown_timer hasCountdown"><span class="countdown_row countdown_amount" id="countdown_1"></span></span>
  </span>
    </div>
    @endif
  </div>
  <div id="accept_decline" class="panel-body">

    <table class="table table-bordered">
      <tbody>
        <tr>
          <td>{{ trans('messages.your_reservations.property') }}</td>
          <td>
            {{ $result->rooms->name }}
            <a href="{{ url('/') }}/rooms/{{ $result->room_id }}" class="pull-right" data-popup="true">{{ trans('messages.your_reservations.view_property') }}</a>
          </td>
        </tr>
        <tr>
          <td>{{ trans('messages.your_reservations.checkin') }}</td>
          <td>
            {{ $result->checkin_mdy }}
            <a href="{{ url('/') }}/manage-listing/{{ $result->room_id }}/calendar" class="pull-right" data-popup="true">{{ trans('messages.your_reservations.view_calendar') }}</a>
          </td>
        </tr>
        <tr>
          <td>{{ trans('messages.your_reservations.checkout') }}</td>
          <td>
            {{ $result->checkout_mdy }}
          </td>
        </tr>
        <tr>
          <td>
              {{ ucfirst(trans_choice('messages.rooms.night',2)) }}
          </td>
          <td>
            {{ $result->nights }}
          </td>
        </tr>
        <tr>
          <td>{{ trans_choice('messages.home.guest',2) }}</td>
          <td>
            {{ $result->number_of_guests }}
          </td>
        </tr>
        <tr>
          <td>{{ trans('messages.your_reservations.cancellation') }}</td>
          <td>
            {{trans('messages.cancellation_policy.'.strtolower($result->cancellation))}}
            <a href="{{ url('/') }}/home/cancellation_policies#{{ $result->cancellation }}" class="pull-right" id="cancel-policy-modal-trigger">{{ trans('messages.your_reservations.view_policy') }}</a>
          </td>
        </tr>
          <tr>
            <td class="pos-rel">
                {{ trans('messages.your_reservations.rate_per_night') }}
                <i class="icon icon-question icon-question-sign tool-amenity2" title="{{ trans('messages.your_reservations.different_rates') }}" rel="tooltip" ></i>
                 
            </td>
            <td>
              <span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ $result->base_per_night }}
            </td>
          </tr>
          <tr>
            <td class="pos-rel">
              <span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ $result->base_per_night }} x {{$result->nights}}   
            </td>
            <td>
              <span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ $result->base_per_night * $result->nights}}
            </td>
          </tr>
          @foreach($result->discounts_list as $list) 
          <tr class="text-beach">
            <td class="pos-rel">
                {{$list['text']}}
            </td>
            <td>
              -<span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ $list['price'] }}
            </td>
          </tr>
          @endforeach
          @if($result->cleaning != 0)
          <tr>
            <td>
              {{ trans('messages.your_reservations.cleaning_fee') }}
            </td>
            <td>
            <span class="lang-chang-label">   {{ $result->currency->symbol }}</span>{{ $result->cleaning }}
            </td>
          </tr>
          @endif
          @if($result->additional_guest != 0)
          <tr>
            <td>
              {{ trans('messages.your_reservations.additional_guest_fee') }}
            </td>
            <td>
              <span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ $result->additional_guest }}
            </td>
          </tr>
          @endif
          <tr>
            <td>
              {{ trans('messages.your_reservations.subtotal') }}
            </td>
            <td>
             <span class="lang-chang-label">  {{ $result->currency->symbol }}</span>{{ $result->subtotal }}
            </td>
          </tr>
          @if($result->host_fee) 
          <tr>
            <td>
                  {{ trans('messages.your_reservations.host_fee') }}
                  <i class="icon icon-question icon-question-sign" title="{{ trans('messages.your_reservations.host_fee_desc',['site_name'=>$site_name]) }}" rel="tooltip" ></i>
            </td>
            <td>
             <span class="lang-chang-label">  - {{ $result->currency->symbol }}</span>{{ $result->host_fee }}
            </td>
          </tr>
          @endif
          @if($result->security != 0)
          <tr>
            <td>
              {{ trans('messages.your_reservations.security_fee') }} <i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i>
            </td>
            <td>
             <span class="lang-chang-label">  {{ $result->currency->symbol }}</span>{{ $result->security }}
            </td>
          </tr>
          @endif
          <tr id="total">
            <td>
              {{ trans('messages.your_reservations.total_payout') }}
            </td>
            <td>
              <strong><span class="lang-chang-label"> {{ $result->currency->symbol }}</span>{{ @$result->host_payout }}</strong>
            </td>
          </tr>
      </tbody>
    </table> 
</div>
<div class="panel-header">
  {{ trans('messages.your_reservations.about_guest') }}
</div>
<div class="panel-body">
  <div class="row">
    <div class="col-4 user_reserve right-pull">
      <img alt="{{ $result->users->first_name }}" class="media-round reserve-img" height="215" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="215">
      <h4 class="right-pull">
        <a href="{{ url('/') }}/users/show/{{ $result->users->id }}" class="verification_user_name">{{ $result->users->first_name }}</a>
          <!-- <i id="verified-id-icon" class="icon icon-verified-id icon-lima" data-tooltip-el="#verifications-tooltip" data-tooltip-position="bottom" data-tooltip-sticky="true"></i> -->
      </h4>
        <span class="h5">{{ $result->users->live }}</span>
      <p class="right-pull" style="width:100%;">
        {{ trans('messages.profile.member_since') }} {{ $result->users->since }}
      </p>
      @if($result->users->age)
      <dl>
          <dt class="right-pull" style="width:100%;">{{ trans('messages.your_reservations.age') }}</dt>
          <dd class="right-pull mar-rit" style="width:100%;">{{ $result->users->age }}</dd>
      </dl>
      @endif
      @if($result->users->users_verification->show())
      <div class="panel">
  <div class="panel-header row">
    <div class="pull-left">
      {{ trans('messages.dashboard.verifications') }}
    </div>
  </div>
  <div class="panel-body">
      <ul class="list-unstyled">
      @if($result->users->users_verification->email == 'yes')
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
      @if($result->users->users_verification->phone_number == 'yes')
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
      @if($result->users->users_verification->facebook == 'yes')
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
      @if($result->users->users_verification->google == 'yes')
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
      @if($result->users->users_verification->linkedin == 'yes')
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
      @if($result->users->verification_status == 'Verified')
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
  </div>
</div>
      @endif
    </div>
    <div class="col-8">
        <div class="panel panel-quote panel-dark row-space-4">
          <div class="panel-body">
            <p class="trans">{{ $result->messages->message }}</p>
            <a href="{{ url('/') }}/messaging/qt_with/{{ $reservation_id }}" class="view_message_history_link" target="_blank">{{ trans('messages.your_reservations.view_msg_history') }}</a>
          </div>
        </div>
    </div>
  </div>
</div>

@if($result->status == 'Pending')
<div class="panel-header">
  <div class="row row-table">
    <div class="col-6  right-pull">
      {{ trans('messages.your_reservations.accept_request') }}?
    </div>
    <div class="pull-right left-pull timer">
        <span class="label label-info">
    <i class="icon icon-time"></i>
    {{ trans('messages.your_reservations.expires_in') }}
    <span class="countdown_timer hasCountdown"><span class="countdown_row countdown_amount" id="countdown_2"></span></span>
  </span>
    </div>
  </div>
</div>
@if($result->host_penalty == '1')
<div class="panel-body">
  <p>
    {{ trans('messages.your_reservations.penalized_if_expires') }}
  </p>
</div>
@endif


<div class="panel-footer">
    <button class="js-host-action btn btn-large btn-primary" id="accept-modal-trigger">
      {{ trans('messages.inbox.pre_accept') }}
    </button>
    <button class="js-host-action btn btn-large" id="decline-modal-trigger">
      {{ trans('messages.your_reservations.decline') }}
    </button>
    <button class="js-host-action btn btn-large" id="discuss-modal-trigger">
      {{ trans('messages.your_reservations.discuss') }}
    </button>
        </div>
  @else
  <div class="panel-header">
    <div class="row row-table">
      <div class="col-10 col-middle text-center label-{{ $result->status_color }}">
      @if(@$result->status == 'Pre-Accepted')
      {{ trans('messages.inbox.pre_accepted') }}
      @endif
      @if(@$result->status == 'Expired')  
      {{ trans('messages.dashboard.Expired') }}    
      @endif

      </div>
    </div>
  </div>
  @endif

      </div>
    </div>
  </div>
</div>

<div class="modal hide" role="dialog" id="accept-modal" aria-hidden="false" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">
          {{ trans('messages.your_reservations.accept_this_request') }}
        </div>
        <div id="accept-modal-alert" class="panel-header alert alert-header alert-with-icon alert-info hide">
          <i class="icon icon-comment alert-icon"></i>
          <span id="alert-content"></span>
        </div>
        <form accept-charset="UTF-8" action="{{ url('reservation/accept/'.$reservation_id) }}" id="accept_reservation_form" method="post" name="accept_reservation_form">
        {!! Form::token() !!}
          <div class="panel-body">
            <label for="accept_message">
              {{ trans('messages.your_reservations.type_msg_to_guest') }}...
            </label>
            <textarea cols="40" id="accept_message" name="message" rows="10"></textarea>
            <div class="row">
                <div class="col-1 row-space-top-2">
                  <input id="tos_confirm" name="tos_confirm" type="checkbox" value="0">
                </div>
                <div class="col-11">
                  <label class="label-inline" for="tos_confirm">{{ trans('messages.your_reservations.by_checking_box') }} <br>
                  @foreach($company_pages as $company_page)
                  @if($company_page->name=='Terms of Service')
                  <a href="{{ url('/') }}/terms_of_service" target="_blank">{{ trans('messages.login.terms_service') }}</a>,
                  @endif
                  @if($company_page->name=='Host Guarantee')
                  <a href="{{ url('/') }}/host_guarantee" target="_blank">{{ trans('messages.your_reservations.host_terms_conditions') }}</a>,
                  @endif
                  @if($company_page->name=='Guest Refund')
                  and <a href="{{ url('/') }}/guest_refund" target="_blank">{{ trans('messages.your_reservations.guest_refund_terms') }}</a>.
                  @endif
                  @endforeach 
                  </label>
                </div>
            </div>
          </div>
          <div class="panel-footer">
            <input type="hidden" name="decision" value="accept">
            <input class="btn btn-primary" id="accept_submit" name="commit" type="submit" value="{{ trans('messages.inbox.pre_accept') }}">
            <button class="btn" data-behavior="modal-close">
              {{ trans('messages.home.close') }}
            </button>
          </div>
</form>      </div>
    </div>
  </div>
</div>

<div class="modal" role="dialog" id="decline-modal" aria-hidden="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <form accept-charset="UTF-8" action="{{ url('reservation/decline/'.$reservation_id) }}" id="decline_reservation_form" method="post" name="decline_reservation_form">
        {!! Form::token() !!}
          <div class="panel-header">
            <a href="#" class="modal-close" data-behavior="modal-close"></a>
            {{ trans('messages.your_reservations.decline_this_request') }}
          </div>
          <div class="panel-body">
            <div id="decline_reason_container">
              <p>
                {{ trans('messages.your_reservations.reason_declining') }}?
              </p>
              <p>
                <strong>
                  {{ trans('messages.your_reservations.not_shared_with_guest') }}
                </strong>
              </p>
              <div class="select">
                <select id="decline_reason" name="decline_reason"><option value="">{{trans('messages.host_decline.why_are__you_declining')}}</option>
<option value="dates_not_available">{{trans('messages.host_decline.dates_not_available')}}</option>
<option value="not_comfortable">{{trans('messages.host_decline.not_comfortable')}}</option>
<option value="not_a_good_fit">{{trans('messages.host_decline.not_a_good_fit')}}</option>
<option value="waiting_for_better_reservation">{{trans('messages.host_decline.waiting_for_better_reservation')}}</option>
<option value="different_dates_than_selected">{{trans('messages.host_decline.different_dates_than_selected')}}</option>
<option value="spam">{{trans('messages.host_decline.spam')}}</option>
<option value="other">{{trans('messages.your_reservations.other')}}</option></select>
              </div>

              <div id="decline_reason_other_div" class="hide row-space-top-2">
                <label for="decline_reason_other">
                  {{ trans('messages.your_reservations.why_declining') }}?
                </label>
                <textarea id="decline_reason_other" name="decline_reason_other" rows="4"></textarea>
            <p style="color: red" class="hide" id="other_errors">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
              </div>

             <!--  <div class="row-space-top-4">
                  <input type="checkbox" checked="checked" name="block_calendar" value="yes">
                {{ trans('messages.your_reservations.block_calc_from') }} <b>{{ $result->checkin_md }}</b> {{ trans('messages.your_reservations.through') }} <b>{{ $result->checkout_md }}</b>
              </div> -->
            </div>

            <label for="decline_message" class="row-space-top-2">
              {{ trans('messages.your_reservations.type_msg_to_guest') }}...
            </label>
            <textarea cols="40" id="decline_message" name="message" rows="10"></textarea>
            <p style="color: red" class="hide" id="errors">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
          </div>
          <div class="panel-footer">
            <input type="hidden" name="decision" value="decline">
            <input class="btn btn-primary" id="decline_submit" name="commit" type="submit" value="{{trans('messages.your_reservations.decline')}}">
            <button class="btn" data-behavior="modal-close">
              {{ trans('messages.home.close') }}
            </button>
          </div>
</form>      </div>
    </div>
  </div>
</div>

<div class="modal" role="dialog" data-trigger="#discuss-modal-trigger" id="discuss-modal" aria-hidden="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">
          {{ trans('messages.your_reservations.discuss_this_request') }}
        </div>
        <div class="panel-body">
          <p>
            {{ trans('messages.your_reservations.before_accept_decline') }}:
          </p><ul class="list-unstyled">
            <li>
              <a href="{{ url('/') }}/messaging/qt_with/{{ $result->id }}" id="other_reservation_send_message">{{ trans('messages.your_reservations.send_msg_to') }} {{ $result->users->first_name }}</a>
            </li>
          </ul>
          <p></p>
          <p>{{ trans('messages.your_reservations.after_msg_accept') }}
          </p>
        </div>
        <div class="panel-footer">
          <button class="btn btn-primary" data-behavior="modal-close">
            {{ trans('messages.home.close') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="expired_at" value="{{ $result->created_at_timer }}">
<input type="hidden" id="reservation_id" value="{{ $reservation_id }}">
</main>
@stop