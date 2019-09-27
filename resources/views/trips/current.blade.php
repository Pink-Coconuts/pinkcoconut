@extends('template')
@section('main')
<main id="site-content" role="main"  ng-controller="cancel_reservation">
  @include('common.subheader')
  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3 trip-left-sec">
        @include('common.sidenav')
      </div>
      <div class="col-md-9 trip-right-sec">
        @if($pending_trips->count() == 0 && $current_trips->count() == 0 && $upcoming_trips->count() == 0)
        <div class="panel">
          <div class="panel-header">
            {{ trans('messages.header.your_trips') }}
          </div>
          <div class="panel-body">
            <p>
              {{ trans('messages.your_trips.no_current_trips') }}
            </p>
            <div class="row">
              <div class="col-8  trip-search">
                <form method="get" class="row" action="{{ url('/') }}/s" accept-charset="UTF-8">
                  <div class="col-8 trip-search-bar">
                    <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" name="location" id="location" autocomplete="off" class="location">
                  </div>
                  <div class="col-4 trip-search-btn">
                    <button id="submit_location" class="btn btn-primary" type="submit">
                      {{ trans('messages.home.search') }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        @endif
        @if($pending_trips->count() > 0)
        <div class="panel row-space-4">
          <div class="panel-header">
            {{ trans('messages.your_trips.pending_trips') }}
          </div>
          <div class="table-responsive">
            <table class="table panel-body panel-light">
              <tbody>
                <tr>
                  <th>{{ trans('messages.your_reservations.status') }}
                  </th>
                  <th>{{ trans('messages.your_trips.location') }}
                  </th>
                  <th>{{ trans('messages.your_trips.host') }}
                  </th>
                  <th>{{ trans('messages.your_trips.dates') }}
                  </th>
                  <th>{{ trans('messages.your_trips.options') }}
                  </th>
                </tr>
                @foreach($pending_trips as $pending_trip)
                @include('trips/trip_row',   ['trip_row' => $pending_trip, 'trip_type' => 'Pending'])
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
        @if($current_trips->count() > 0)
        <div class="panel row-space-4">
          <div class="panel-header">
            {{ trans('messages.your_trips.current_trips') }}
          </div>
          <div class="table-responsive">
            <table class="table panel-body panel-light">
              <tbody>
                <tr>
                  <th>{{ trans('messages.your_reservations.status') }}
                  </th>
                  <th>{{ trans('messages.your_trips.location') }}
                  </th>
                  <th>{{ trans('messages.your_trips.host') }}
                  </th>
                  <th>{{ trans('messages.your_trips.dates') }}
                  </th>
                  <th>{{ trans('messages.your_trips.options') }}
                  </th>
                </tr>
                @foreach($current_trips as $current_trip)
                @include('trips/trip_row',   ['trip_row' => $current_trip, 'trip_type' => 'Current'])
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
        @if($upcoming_trips->count() > 0)
        <div class="panel row-space-4">
          <div class="panel-header">
            {{ trans('messages.your_trips.upcoming_trips') }}
          </div>
          <div class="table-responsive">
            <table class="table panel-body panel-light">
              <tbody>
                <tr>
                  <th>{{ trans('messages.your_reservations.status') }}
                  </th>
                  <th>{{ trans('messages.your_trips.location') }}
                  </th>
                  <th>{{ trans('messages.your_trips.host') }}
                  </th>
                  <th>{{ trans('messages.your_trips.dates') }}
                  </th>
                  <th>{{ trans('messages.your_trips.options') }}
                  </th>
                </tr>
                @foreach($upcoming_trips as $upcoming_trip)
                @include('trips/trip_row',   ['trip_row' => $upcoming_trip, 'trip_type' => 'Upcoming'])
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
  @if($upcoming_trips->count() > 0 || $current_trips->count() > 0)
  <div class="modal" role="dialog" id="cancel-modal" aria-hidden="true">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <form accept-charset="UTF-8" action="{{ url('trips/guest_cancel_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
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
                  <strong>
                    {{ trans('messages.your_trips.response_not_shared_host') }}
                  </strong>
                </p>
                <div class="select">
                  <select id="cancel_reason" name="cancel_reason">
                    <option value="">{{ trans('messages.your_reservations.why_declining') }}
                    </option>
                    <option value="no_longer_need_accommodations">{{ trans('messages.your_reservations.I_no_longer_need_accommodations') }}
                    </option>
                    <option value="travel_dates_changed">{{ trans('messages.your_reservations.My_travel_dates_changed_successfully') }}
                    </option>
                    <option value="made_the_reservation_by_accident">{{ trans('messages.your_reservations.i_made_the_reservation_by_accident') }}
                    </option>
                    <option value="I_have_an_extenuating_circumstance">{{ trans('messages.your_reservations.i_have_an_extenuating_circumstance') }}
                    </option>
                    <option value="my_host_needs_to_cancel">{{ trans('messages.your_reservations.my_host_need_to_cancel') }}
                    </option>
                    <option value="uncomfortable_with_the_host">{{ trans('messages.your_reservations.i_m_uncomfortable_with_the_host') }}
                    </option>
                    <option value="place_not_okay">{{ trans('messages.your_reservations.the_place_is_not_what_was_expecting') }}
                    </option>
                    <option value="other">{{ trans('messages.your_reservations.other') }}
                    </option>
                  </select>
                </div>
                <div id="cancel_reason_other_div" class="hide row-space-top-2">
                  <label for="cancel_reason_other">
                    {{ trans('messages.your_reservations.why_cancel') }}
                  </label>
                  <textarea id="decline_reason_other" name="decline_reason_other" rows="4"></textarea>
                </div>
              </div>
              <label for="cancel_message" class="row-space-top-2">
                {{ trans('messages.your_trips.type_msg_host') }}... 
              </label> 
              <textarea cols="40" id="cancel_message" name="cancel_message" rows="10" wrap="virtual"></textarea>
            <p style="color: red" class="hide" id="errors">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
              <input type="hidden" name="id" id="reserve_code" value="">
            </div>
            <div class="panel-footer">
              <input type="hidden" name="decision" value="decline">
              <input class="btn btn-primary" id="cancel_submit" name="commit" type="submit" value="{{ trans('messages.your_reservations.cancel_this_reservation') }}">
              <button class="btn" data-behavior="modal-close">
                {{ trans('messages.home.close') }}
              </button>
            </div>
          </form>      
        </div>
      </div>
    </div>
  </div>
  @endif
  @if($pending_trips->count() > 0) 
  <div class="modal" role="dialog" id="pending-cancel-modal" aria-hidden="true">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <form accept-charset="UTF-8" action="{{ url('trips/guest_cancel_pending_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
            {!! Form::token() !!}
            <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
            <div class="panel-header">
              <a href="#" class="modal-close" data-behavior="modal-close">
              </a>
              {{ trans('messages.your_reservations.cancel_this_reservation') }}
            </div>
            <div class="panel-body">
              <div id="decline_reason_container">
                <p>
                  {{ trans('messages.your_reservations.reason_cancel_reservation') }}
                </p>
                <p>
                  <strong>
                    {{ trans('messages.your_trips.response_not_shared_host') }}
                  </strong>
                </p>
                <div class="select">
                  <select id="canceled_reason" name="cancel_reason">
                    <option value="">{{ trans('messages.your_reservations.why_declining') }}
                    </option>
                    <option value="no_longer_need_accommodations">{{ trans('messages.your_reservations.I_no_longer_need_accommodations') }}
                    </option>
                    <option value="travel_dates_changed">{{ trans('messages.your_reservations.My_travel_dates_changed_successfully') }}
                    </option>
                    <option value="made_the_reservation_by_accident">{{ trans('messages.your_reservations.i_made_the_reservation_by_accident') }}
                    </option>
                    <option value="I_have_an_extenuating_circumstance">{{ trans('messages.your_reservations.i_have_an_extenuating_circumstance') }}
                    </option>
                    <option value="my_host_needs_to_cancel">{{ trans('messages.your_reservations.my_host_need_to_cancel') }}
                    </option>
                    <option value="uncomfortable_with_the_host">{{ trans('messages.your_reservations.i_m_uncomfortable_with_the_host') }}
                    </option>
                    <option value="place_not_okay">{{ trans('messages.your_reservations.the_place_is_not_what_was_expecting') }}
                    </option>
                    <option value="other">{{ trans('messages.your_reservations.other') }}
                    </option>
                  </select>
                </div>
                <div id="cancel_reason_other_div" class="hide row-space-top-2">
                  <label for="cancel_reason_other">
                    {{ trans('messages.your_reservations.why_cancel') }}
                  </label>
                  <textarea id="decline_reason_other" name="decline_reason_other" rows="4">
                  </textarea>
                </div>
              </div>
              <label for="cancel_message" class="row-space-top-2">
                {{ trans('messages.your_trips.type_msg_host') }}...
              </label>
             <textarea cols="40" id="canceled_message" name="cancel_message" rows="10" wrap="virtual"></textarea>
               <p style="color: red" class="hide" id="errors1">{{ trans('messages.your_trips.please_fill_the_details') }}</p>
              <input type="hidden" name="id" id="reserve_code_pending" value="">
            </div>
            <div class="panel-footer">
              <input type="hidden" name="decision" value="decline">
              <input class="btn btn-primary" id="canceled_submit" name="commit" type="submit" value="{{ trans('messages.your_reservations.cancel_this_reservation') }}">
              <button class="btn" data-behavior="modal-close">
                {{ trans('messages.home.close') }}
              </button>
            </div>
          </form>      
        </div>
      </div>
    </div>
  </div>
  @endif
  @include('trips/dispute_modal')
</main>
@stop
