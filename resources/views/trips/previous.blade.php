@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="cancel_reservation">
  @include('common.subheader')  
  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3 prevoius-trips-left">
        @include('common.sidenav')
      </div>
      <div class="col-md-9 prevoius-trips-right">
        @if($previous_trips->count() == 0)
        <div class="panel">
          <div class="panel-header">
            {{ trans('messages.your_trips.previous_trips') }}
          </div>
          <div class="panel-body">
            <p>
              {{ trans('messages.your_trips.no_previous_trips') }}
            </p>
            <div class="row">
              <div class="col-8 lang-chang-label">
                <form method="get" class="row" action="{{ url('/') }}/s" accept-charset="UTF-8">
                  <div class="col-8 trip-search-bar">
                    <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" name="location" id="location" autocomplete="off" class="location">
                  </div>
                  <div class="col-4">
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
        @if($previous_trips->count() > 0)
        <div class="panel row-space-4">
          <div class="panel-header">
            {{ trans('messages.your_trips.previous_trips') }}
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
                @foreach($previous_trips as $previous_trip)
                @include('trips/trip_row',   ['trip_row' => $previous_trip, 'trip_type' => 'Previous'])
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
  @include('trips/dispute_modal')
</main>
@stop