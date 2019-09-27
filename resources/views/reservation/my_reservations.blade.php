@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="cancel_reservation">
  @include('common.subheader')  
  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3 col-sm-12 lang-chang-label">
        @include('common.sidenav')
      </div>
      <div class="col-md-9 col-sm-12">
        <div class="your-listings-flash-container">
        </div>
        <div class="panel" id="print_area">
          @if($reservations->count() == 0 && $code != 1)
          <div class="panel-body">
            <p>
              {{ trans('messages.your_reservations.no_upcoming_reservations') }}
            </p>
            <a href="{{ url('/') }}/my_reservations?all=1">{{ trans('messages.your_reservations.view_past_reservation_history') }}
            </a>
          </div>
          @elseif($reservations->count() == 0 && $code == 1)
          <div class="panel-body">
            <p>
              {{ trans('messages.your_reservations.no_reservations') }}
            </p>
            <a href="{{ url('/') }}/rooms/new" class="hostverification btn btn-special list-your-space-btn" id="list-your-space">{{ trans('messages.your_listing.add_new_listings') }}
            </a>
          </div>
          @else
          <div class="panel-header">
            <div class="row row-table">
              <div class="col-md-6 col-middle mid-name-title col-sm-6">
                {{ ($code == 1) ? trans('messages.your_reservations.all') : trans('messages.your_reservations.upcoming') }} {{ trans('messages.inbox.reservations') }}
              </div>
              <div class="col-md-6 col-middle reser-cont col-sm-6">
                <a class="btn pull-right print-btn bold_non" href="{{ url('/') }}/my_reservations?all={{ $code }}&amp;print={{ $code }}">
                  <span>{{ trans('messages.your_reservations.print_this_page') }}
                  </span>
                  <i class="icon icon-description">
                  </i>
                </a>        
              </div>
            </div>
          </div>
          <div class="table-responsive reservationsMY table_style1">
            <table style="background-color:white" class="table panel-body space-1">
              <tbody>
                <tr>
                  <th>{{ trans('messages.your_reservations.status') }}
                  </th>
                  <th>{{ trans('messages.your_reservations.dates_location') }}
                  </th>
                  <th>{{ trans_choice('messages.home.guest',1) }}
                  </th>
                  <th>{{ trans('messages.your_reservations.details') }}
                  </th>
                </tr>
                @foreach($reservations as $row)
                <tr data-reservation-id="{{ $row->id }}" class="reservation">
                  <td>
                    @if($row->status == 'Pre-Accepted' || $row->status == 'Inquiry')
                    @if($row->checkin >= date("Y-m-d"))
                    <span class="label label-{{ $row->status_color }}">
                      {{ trans('messages.dashboard.'.$row->status) }}
                    </span>
                    @else
                    <span class="label label-info">{{trans('messages.dashboard.Expired')}}
                    </span>
                    @endif
                    @else
                    <span class="label label-{{ $row->status_color }}">
                      {{ trans('messages.dashboard.'.$row->status) }}
                    </span>
                    @endif
                  </td>
                  <td>
                    {{ $row->dates }}
                    <br>
                    <a locale="en" href="{{$row->rooms->link}}" class="{{$row->title_class}}">{{ $row->rooms->name }}
                    </a>
                    <br>
                    {{ $row->rooms->rooms_address->address_line_1 }}
                    <br>
                    @if($row->rooms->rooms_address->city !='') {{ $row->rooms->rooms_address->city }},@endif
                    @if($row->rooms->rooms_address->state !='') {{ $row->rooms->rooms_address->state }}@endif
                    @if($row->rooms->rooms_address->postal_code !='') {{ $row->rooms->rooms_address->postal_code }}@endif
                    <br>
                  </td>
                  <td>
                    <div class="media va-container reserve">
                      <a class="pull-left media-photo media-round" href="{{ url('/') }}/users/show/{{ $row->users->id }}">
                        <img width="50" height="50" title="{{ $row->users->first_name }}" src="{{ $row->users->profile_picture->src }}" alt="{{ $row->users->first_name }}">
                      </a>      <div class="va-top">
                        <a class="text-normal" href="{{ url('/') }}/users/show/{{ $row->users->id }}">{{ $row->users->full_name }}
                        </a>
                        <br>
                        @if($row->status == 'Accepted')
                        <a href="{{ url('/') }}/messaging/qt_with/{{ $row->id }}" class="text-normal">
                          <i class="icon icon-envelope">
                          </i>
                          {{ trans('messages.your_reservations.send_message') }}
                        </a>
                        <br>
                        <a href="mailto:{{ $row->users->email }}">
                          {{ trans('messages.your_reservations.contact_by_email') }} 
                        </a>
                        @if($row->users->primary_phone_number != '')
                        <br>
                        {{ $row->users->primary_phone_number }}
                        @endif
                        @endif
                        <br>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{$row->currency->symbol}}  {{$row->subtotal - $row->host_fee}} {{ trans('messages.your_reservations.total') }}
                    <ul class="list-unstyled">
                      <li>
                        <a href="{{ url('/') }}/messaging/qt_with/{{ $row->id }}">{{ trans('messages.your_reservations.message_history') }}
                        </a>
                      </li>
                      @if($row->status == "Pre-Accepted")
                      <li>
                        <a href="javascript:void(0)" id="{{$row->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}
                        </a>
                      </li>
                      @endif
                      @if($row->status == "Accepted")
                      <li>
                        <a target="_blank" href="{{ url('/') }}/reservation/itinerary?code={{ $row->code }}">{{ trans('messages.your_reservations.print_confirmation') }}
                        </a>
                      </li>
                      @if(!$row->checkout_cross)
                      <li>
                        <a href="javascript:void(0)" id="{{$row->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}
                        </a>
                      </li>
                      @endif
                      @if($row->review_days > 0 && $row->review_days < 15)
      <li>
                      @if($row->list_type=="Rooms")
                      @if($row->guest_reviews->count() > 0)
                      <a href="{{ url('/') }}/reviews/edit/{{ $row->id }}?/reservations_review">{{ trans('messages.reviews.edit') }} {{ trans('messages.email.review') }}</a>
                      @else
                      <a href="{{ url('/') }}/reviews/edit/{{ $row->id }}?/reservations_review">{{ trans('messages.reviews.write_a_review') }}</a>
                      @endif
                      @else
                      @if($row->guest_reviews->count() > 0)
                      <a href="{{ url('/') }}/host_experience_reviews/edit/{{ $row->id }}?/reservations_review">{{ trans('messages.reviews.edit') }} {{ trans('messages.email.review') }}</a>
                      @else
                      <a href="{{ url('/') }}/host_experience_reviews/edit/{{ $row->id }}?/reservations_review">{{ trans('messages.reviews.write_a_review') }}</a>
                      @endif
                      @endif
                    </li>
                      @endif
                      @endif
                    </ul>
                    @if($row->can_apply_for_dispute && $row->paymode != '')
                    <button class="btn btn-primary" type="button" id="js_dispute_btn" ng-click="trigger_create_dispute({{collect(['id' => $row->id, 'currency_code' => $row->currency_code, 'currency_symbol' => $row->currency->symbol])->toJson()}})">{{trans('messages.disputes.dispute')}}</button>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if($code == '0' || $code == '')
          <div class="panel-body">
            <a href="{{ url('/') }}/my_reservations?all=1">{{ trans('messages.your_reservations.view_all_reservation_history') }}
            </a>
          </div>
          @else
          <div class="panel-body">
            <a href="{{ url('/') }}/my_reservations?all=0">{{ trans('messages.your_reservations.view_upcoming_reservations') }}
            </a>
          </div>
          @endif
          @endif
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" value="{{ $print }}" id="print">
  <div class="modal" role="dialog" id="cancel-modal" aria-hidden="true">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <form accept-charset="UTF-8" action="{{ url('reservation/host_cancel_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
            {!! Form::token() !!}
            <div class="panel-header">
              <a href="javascript:;" class="modal-close" data-behavior="modal-close">
              </a>
              {{ trans('messages.your_reservations.cancel_this_reservation') }}
            </div>
            <div class="panel-body">
              <div id="decline_reason_container">
                <p>
                  {{ trans('messages.your_reservations.reason_cancel_reservation') }}
                </p>
                <p>
                </p>
                <div class="select">
                  <select id="cancel_reason" name="cancel_reason">
                    <option value="">{{trans('messages.host_cancel.why_are__you_cancelling')}}
                    </option>
                    <option value="no_longer_available">{{trans('messages.host_cancel.no_longer_available')}}
                    </option>
                    <option value="offer_a_different_listing">{{trans('messages.host_cancel.offer_a_different_listing')}}
                    </option>
                    <option value="need_maintenance">{{trans('messages.host_cancel.need_maintenance')}}
                    </option>
                    <option value="I_have_an_extenuating_circumstance">{{trans('messages.host_cancel.I_have_an_extenuating_circumstance')}}
                    </option>
                    <option value="my_guest_needs_to_cancel">{{trans('messages.host_cancel.my_guest_needs_to_cancel')}}
                    </option>
                    <option value="other">{{trans('messages.your_reservations.other')}}
                    </option>
                  </select>
                </div>
              </div>
              <label for="cancel_message" class="row-space-top-2">
                {{ trans('messages.your_reservations.type_msg_guest') }}...
              </label>
              <textarea cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
            <p style="color: red" class="hide" id="errors">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
              <input type="hidden" name="id" id="reserve_id" value="">
            </div>
            <div class="panel-footer">
              <input type="hidden" name="decision" value="decline">
              <input class="btn btn-primary" id="cancel_submit" name="commit" type="submit" value="Cancel My Reservation">
              <button class="btn" data-behavior="modal-close">
                {{ trans('messages.home.close') }}
              </button>
            </div>
          </form>      
        </div>
      </div>
    </div>
  </div>
  @include('trips/dispute_modal')
</main>
<script>
  if(document.getElementById('print').value >= '0')
  {
    window.print();
    window.onfocus=function(){ window.location.href=APP_URL+'/my_reservations'; }
  }
</script>
@if($print >= '0')
<style>
body * {
  visibility: hidden;
}
#print_area, #print_area * {
  visibility: visible;
}
#print_area {
  position: fixed;
  left: 0px;
  top: 0px;
}
a[href]:after {
  content: none !important;
}
</style>
@endif
@stop