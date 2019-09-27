<tr>
  <td class="status">
    <span class="label label-orange label-{{ $trip_row->status_color }}">
      @if($trip_row->status == 'Pre-Accepted' || $trip_row->status == 'Inquiry')
        @if($trip_row->checkin >= date("Y-m-d"))
        <span class="label label-{{ $trip_row->status_color }}">
          {{ trans('messages.dashboard.'.$trip_row->status) }}
        </span>
        @else
        <span class="label label-info">{{trans('messages.dashboard.Expired')}}
        </span>
        @endif
      @else
      <span class="label label-{{ $trip_row->status_color }}">
        {{ trans('messages.dashboard.'.$trip_row->status) }}
      </span>
      @endif
      
      @if($trip_row->status=='Pre-Accepted')
      <div class="space-top-3" >
        @if($trip_row->checkin >= date("Y-m-d"))
          @if( $trip_row->avablity!=1 || $trip_row->date_check!='No' )
          <a href="{{url('payments/book?reservation_id='.$trip_row->id)}}" class="btn btn-primary" id="{{ $trip_row->id }}" data-room="{{ $trip_row->room_id }}" data-checkin="{{ $trip_row->checkin }}" data-checkout="{{ $trip_row->checkout }}" >
            <p hidden="hidden" class="pending_id" ><?php echo $trip_row->id;?>
            </p>
            <span>{{ trans('messages.inbox.book_now') }}
            </span>
          </a>
          @else
          <span style="color: #ff5a5f;font-weight: normal !important;" id="al_res{{ $trip_row->id }}">{{ trans('messages.inbox.already_booked') }}
          </span>
          @endif
        @endif
      </div>
      @endif
    </span>
    <br>
  </td>
  <td class="location">
    <a href="{{$trip_row->rooms->link}}" class="{{$trip_row->title_class}}">{{ $trip_row->rooms->name }}
    </a>
    @if($trip_row->list_type == 'Experiences' )
      <br>
      <span>{{$trip_row->rooms->category_details->name}} {{trans('experiences.details.experience')}}
      </span>
    @endif
    <br>      
    @if(@$trip_row->rooms->rooms_address->city != '') 
      {{ $trip_row->rooms->rooms_address->city }}
    @else
      {{ $trip_row->rooms->rooms_address->state }}
    @endif
  </td>
  <td class="host">
    <a href="{{ url('/') }}/users/show/{{ $trip_row->host_id }}">{{ $trip_row->rooms->users->full_name }}
    </a>
  </td>
  <td class="dates">{{ $trip_row->dates }}
  </td>
  <td>
    <ul class="unstyled button-list list-unstyled">
      <li class="row-space-1">
        <a class="button-steel" href="{{ url('/') }}/z/q/{{ $trip_row->id }}">{{ trans('messages.your_reservations.message_history') }}
        </a>
      </li>

      @if($trip_type == 'Current')
      @if($trip_row->status != "Cancelled" && $trip_row->status != "Declined" && $trip_row->status != "Expired" && $trip_row->status != " ")
      <li class="row-space-1">
        <a class="button-steel" href="{{ url('/') }}/reservation/itinerary?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_itinerary') }}
        </a>
      </li>
      <li class="row-space-1">
        <a class="button-steel" href="{{ url('/') }}/reservation/receipt?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_receipt') }}
        </a>
      </li>
      <li class="row-space-1">
        <a class="button-steel" href="javascript:void(0)" id="{{$trip_row->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}
        </a>
      </li>
      @endif
      @endif

      @if($trip_type == 'Pending')
      <li class="row-space-1">        
        @if($trip_row->date_check!='No')
          @if($trip_row->checkin >= date("Y-m-d"))
          <a rel="nofollow" data-method="post" data-confirm="Are you sure that you want to cancel the request? Any money transacted will be refunded." id="{{ $trip_row->id }}-trigger-pending" class="button-steel" href="javascript:void(0)" >{{ trans('messages.your_trips.cancel_request') }}
          </a>
          @endif
        @endif
      </li>
      @endif

      @if($trip_type == 'Upcoming')
      @if($trip_row->status != "Cancelled"  && $trip_row->status != "Declined" && $trip_row->status != "Expired")
        <li class="row-space-1">
          <a class="button-steel" href="{{ url('/') }}/reservation/itinerary?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_itinerary') }}
          </a>
        </li>
        <li class="row-space-1">
          <a class="button-steel" href="{{ url('/') }}/reservation/receipt?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_receipt') }}
          </a>
        </li>
        
        <li class="row-space-1">
          <a class="button-steel" href="javascript:void(0)" id="{{$trip_row->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}
          </a>
        </li>

      @endif
      @endif

      @if($trip_type == 'Previous')
      @if($trip_row->status != "Cancelled" && $trip_row->status != "Declined" && $trip_row->status != "Expired" && $trip_row->status != "Pre-Accepted" && $trip_row->status != "Inquiry" )
      <li class="row-space-1">
        <a class="button-steel" href="{{ url('/') }}/reservation/itinerary?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_itinerary') }}
        </a>
      </li>
      <li class="row-space-1">
        <a class="button-steel" href="{{ url('/') }}/reservation/receipt?code={{ $trip_row->code }}">{{ trans('messages.your_trips.view_receipt') }}
        </a>
      </li>

      @if($trip_row->review_days > 0 && $trip_row->review_days < 15)
      <li class="row-space-1">
                      @if($trip_row->list_type=="Rooms")
                      @if($trip_row->guest_reviews->count() > 0)
                      <a href="{{ url('/') }}/reviews/edit/{{ $trip_row->id }}?/trips_review">{{ trans('messages.reviews.edit') }} {{ trans('messages.email.review') }}</a>
                      @else
                      <a href="{{ url('/') }}/reviews/edit/{{ $trip_row->id }}?/trips_review">{{ trans('messages.reviews.write_a_review') }}</a>
                      @endif
                      @else
                      @if($trip_row->guest_reviews->count() > 0)
                      <a href="{{ url('/') }}/host_experience_reviews/edit/{{ $trip_row->id }}?/trips_review">{{ trans('messages.reviews.edit') }} {{ trans('messages.email.review') }}</a>
                      @else
                      <a href="{{ url('/') }}/host_experience_reviews/edit/{{ $trip_row->id }}?/trips_review">{{ trans('messages.reviews.write_a_review') }}</a>
                      @endif
                      @endif
                    </li>
                      @endif
      @endif
      @endif
      
      @if($trip_row->can_apply_for_dispute && $trip_row->paymode != '' && $trip_row->list_type == 'Rooms')
      <button class="btn btn-primary" type="button" id="js_dispute_btn" ng-click="trigger_create_dispute({{collect(['id' => $trip_row->id, 'currency_code' => $trip_row->currency_code, 'currency_symbol' => $trip_row->currency->symbol])->toJson()}})">{{trans('messages.disputes.dispute')}}</button>
      @endif 
        </ul>
  </td>
</tr>