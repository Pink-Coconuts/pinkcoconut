@extends('template')
 
@section('main')

<main id="site-content" role="main" ng-controller="conversation">

@include('common.subheader')  
<div class="page-container page-container-responsive row-space-top-4">
    <div class="row">
      <div class="col-md-7 col-md-push-5 messaging-thread-main">
        <div id="message_friction_react" class="thread-list-item">
        </div>
        <div class="js-messaging-react-container messaging-thread-container">
        <div>
        <input type="hidden" value="{{ $messages[0]->reservation_id }}" id="reservation_id">
        @if($messages[0]->message_type == 1)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.payments.request_sent') }}</span>
        </strong>
        </div>
        <div>
        <span>{{ trans('messages.inbox.reservation_isnot_confirmed') }}</span> 
        </div>
        </div>
        @endif
        @if($messages[0]->message_type == 2)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.inbox.reservation_confirmed_place') }} {{ $messages[0]->reservation->rooms->rooms_address->city }}, {{ $messages[0]->reservation->rooms->rooms_address->country_name }}</span>
        </strong>
        </div>
        <a href="{{ url('/') }}/reservation/itinerary?code={{ $messages[0]->reservation->code }}" class="btn space-top-3">
        <span>{{ trans('messages.your_trips.view_itinerary') }}</span>
        </a>
        <!-- <a href="{{ url('/') }}/reservation/change?code={{ $messages[0]->reservation->code }}" class="btn space-top-3">
        <span>Change or Cancel</span>
        </a> -->
        </div>
        @endif
        @if($messages[0]->message_type == 3)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.inbox.request_declined') }}</span>
        </strong>
        </div>
        <div>
        <span>{{ trans('messages.inbox.more_places_available') }}</span>
        </div>
        <a class="btn space-top-3" href="{{ url('/') }}/s?location={{ $messages[0]->reservation->rooms->rooms_address->city }}">
        <span>{{ trans('messages.inbox.keep_searching') }}</span>
        </a>
        </div>
        @endif
        @if($messages[0]->reservation->special_offer)
        <div class="panel action-status row-space-6">
        <div class="panel-body text-center">
        <div class="h4 space-1">
        <span>{{ ucfirst($messages[0]->reservation->rooms->users->first_name) }} {{ trans('messages.inbox.pre_approved_trip') }}</span>
        </div>
        <div class="space-top-3">
        
        @if(@$messages[0]->message_type!=8)
        @if(@$messages[0]->reservation->avablity ==0 || @$messages[0]->reservation->special_offer->avablity==0)
        @if(@$messages[0]->reservation->special_offer->checkin >= date("Y-m-d"))       
        @if(@$messages[0]->reservation->special_offer->is_booked)
        <a href="{{ url('/') }}/payments/book?checkin={{ @$messages[0]->reservation->special_offer->checkin }}&amp;checkout={{ @$messages[0]->reservation->special_offer->checkout }}&amp;room_id={{ @$messages[0]->reservation->special_offer->room_id }}&amp;number_of_guests={{ @$messages[0]->reservation->special_offer->number_of_guests }}&amp;ref=qt2_preapproved&amp;special_offer_id={{ @$messages[0]->reservation->special_offer->id }}" class="btn btn-primary {{ (@$messages[0]->reservation->special_offer->id) ? '':'prefer' }}" data-id="{{ $messages[0]->reservation->id }}" data-room="{{ $messages[0]->reservation->room_id }}" data-checkin="{{ $messages[0]->reservation->checkin }}" data-checkout="{{ $messages[0]->reservation->checkout }}"  >

        <span>{{ trans('messages.inbox.book_now') }}</span>
        @endif
        </a>
        @if(@$messages[0]->reservation->special_offer->id != '' && $messages[0]->reservation->special_offer->type == 'special_offer')
        <div class="row-space-top-3">
          <div class="special_offer-detail pull-left">
          <div class="text-medium-gray row-space-1">
          <span>{{ trans('messages.lys.listing_name') }}</span>
          </div>
          <div class="row-space-3 h5">  
            <a class="text-normal" href="{{$messages[0]->reservation->special_offer->rooms->link }}">{{ $messages[0]->reservation->special_offer->rooms->name }}</a>
          </div>
          </div>
          <div class="special_offer-detail pull-left">
          <div class="text-medium-gray row-space-1">
          <span>{{ trans('messages.your_reservations.checkin') }}</span>
          </div>
          <div class="row-space-3 h5"> {{@$messages[0]->reservation->special_offer->checkin_arrive}} </div>
          </div>
          <div class="special_offer-detail pull-left">
          <div class="text-medium-gray row-space-1">
          <span>{{ trans('messages.your_reservations.checkout') }}</span>
          </div>
          <div class="row-space-3 h5"> {{@$messages[0]->reservation->special_offer->checkout_depart}} </div>
          </div>
          <div class="special_offer-detail pull-left">
          <div class="text-medium-gray row-space-1">
          <span>{{ trans_choice('messages.home.guest',@$messages[0]->reservation->special_offer->number_of_guests ) }}</span>
          </div>
          <div class="row-space-3 h5"> {{@$messages[0]->reservation->special_offer->number_of_guests }} </div>
          </div>
          <div class="reservation-info-section pull-left">
          <div class="text-medium-gray row-space-1">
          <span> {{ trans('messages.inbox.special_offer') }}</span>
          </div>
          <div class="row-space-3 h5">{{ $messages[0]->reservation->currency->symbol}}{{@$messages[0]->reservation->special_offer->price }} </div>
          </div>
        </div>
        @endif
        @else
          <span class="label label-info">{{trans('messages.dashboard.Expired')}}</span>
          @endif
        @else
        @if($messages[0]->reservation->special_offer->checkin >= date("Y-m-d"))
        @if(@$messages[0]->reservation->special_offer->is_booked)
        <span style="color:red" id="al_res{{ $messages[0]->reservation->id }}">{{ trans('messages.inbox.already_booked') }}</span> @endif
         @else
        <span class="label label-info">{{trans('messages.dashboard.Expired')}}</span>
        @endif
        @endif
        @endif
        </div>
        </div>
        </div>
        @elseif($messages[0]->reservation->status=='Pre-Accepted')
        <div class="panel action-status row-space-6">
        <div class="panel-body text-center">
        <div class="h4 space-1">
        <span>{{ ucfirst($messages[0]->reservation->rooms->users->first_name) }} <!-- {{ trans('messages.inbox.pre_approved_trip') }} -->  {{ trans('messages.inbox.preaccept_booking') }} </span>
        </div>
        <div class="space-top-3">
        @if($messages[0]->message_type!=8)
        @if(@$messages[0]->reservation->avablity==0)
        @if($messages[0]->reservation->checkin >= date("Y-m-d"))
        <a href="{{ url('/') }}/payments/book?reservation_id={{$messages[0]->reservation->id}}" class="btn btn-primary" data-id="{{ $messages[0]->reservation->id }}" data-room="{{ $messages[0]->reservation->room_id }}" data-checkin="{{ $messages[0]->reservation->checkin }}" data-checkout="{{ $messages[0]->reservation->checkout }}" >
        <p hidden="hidden" class="pending_id" ><?php echo $messages[0]->reservation->id;?></p>
        <span>{{ trans('messages.inbox.book_now') }}</span>
        </a>
        @else
          <span class="label label-info">{{trans('messages.dashboard.Expired')}}</span>
          @endif
        @else
        @if($messages[0]->reservation->checkin >= date("Y-m-d"))
        <span style="color:red" id="al_res{{ $messages[0]->reservation->id }}">{{ trans('messages.inbox.already_booked') }}</span>
         @else
        <span class="label label-info">{{trans('messages.dashboard.Expired')}}</span>
        @endif
        @endif
        @endif
        </div>
        </div>
        </div>
        @endif
        <div id="post_message_box" data-key="guest_conversation" class="row row-condensed row-space-6 send-message-box"><div class="col-sm-10 col-xs-11"><div class="panel-quote-flush panel-quote panel-quote-right panel"><div class="panel-body text-left text-medium-gray"><textarea rows="3" placeholder="" class="send-message-textarea" id="message_text" name="message"></textarea></div><div class="panel-body panel-dark text-right"><button class="btn" id="reply_message" ng-click="reply_message('guest_conversation')">{{ trans('messages.your_reservations.send_message') }}</button></div></div></div><div class="col-sm-2 col-xs-1 text-right"><div class="media-photo media-round"><img width="70" height="70" src="{{ Auth::user()->profile_picture->src }}"></div></div></div>
        <div id="thread-list">
        <div>
        <div id="thread-list">
        @for($i=0; $i<count($messages); $i++)
        @if($messages[$i]->message_type=='12')
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.preaccept_booking') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 9)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.contact_request_sent') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 2)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_confirmed') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 3)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_declined') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 4)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_expired') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 6)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $messages[$i]->reservation->rooms->users->first_name }} {{ trans('messages.inbox.pre_approved_you') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 7)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $messages[$i]->reservation->rooms->users->first_name }} {{ trans('messages.inbox.sent_special_offer') }} </span>
        <span>{{ $messages[$i]->special_offer->currency->symbol.$messages[$i]->special_offer->price }}/ {{ @$special_offer_nights }} {{ ucfirst(trans_choice('messages.rooms.night',1)) }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 8)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.host_decline.' . $messages[$i]->reservation->decline_reason) }}</span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 11)
          <div class="inline-status text-branding space-6">
          <div class="horizontal-rule-text">
          <span class="horizontal-rule-wrapper">
          <span>
          <span>{{ trans('messages.inbox.reservation_declined') }} </span>
          <span>{{ $messages[$i]->created_time }}</span>
          </span>
          </span>
          </div>
          </div>
    @endif
    @if($messages[$i]->message_type == 10)
          <div class="inline-status text-branding space-6">
          <div class="horizontal-rule-text">
          <span class="horizontal-rule-wrapper">
          <span>
          <span>{{ trans('messages.inbox.reservation_cancelled') }} </span>
          <span>{{ $messages[$i]->created_time }}</span>
          </span>
          </span>
          </div>
          </div>
    @endif
        @if($messages[$i]->user_from != Auth::user()->id && $messages[$i]->message != '')
        <div>
        <div>
        <div class="row row-condensed row-space-6 post">
        <div class="col-sm-2 col-xs-1 text-left">
        <div class="media-photo media-round">
        <img width="70" height="70" src="{{ $messages[$i]->user_details->profile_picture->src }}" class="user-profile-photo">
        </div>
        </div>
        <div class="col-sm-10 col-xs-11">
        <div class="panel-quote-flush panel-quote panel panel-quote-left">
        <div class="panel-body">
        <div>
        <div>
        <div class="pull-right">
        <a data-prevent-default="true" title="Report this message" class="flag-trigger link-reset" href="#">
        <!-- <i class="icon icon-flag h4"> -->
        </i>
        </a>
        </div>
        </div>
        </div>
        <div>
        <span class="message-text">{{ $messages[$i]->message }}</span>
        </div>
        <div class="space-top-2 text-muted">
        <span class="time">{{ $messages[$i]->created_time }}</span>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        @endif
        @if($messages[$i]->user_from == Auth::user()->id)
        <div>
        <div>
        <div class="row row-condensed row-space-6 post">
        <div class="col-sm-10 col-xs-11">
        <div class="panel-quote-flush panel-quote panel panel-quote-right panel-quote-dark">
        <div class="panel-body panel-dark">
        <div>
        <span class="message-text">{{ $messages[$i]->message }}</span>
        </div>
        <div class="space-top-2 col-xs-1 text-muted">
        <span class="time">{{ $messages[$i]->created_time }}</span>
        </div>
        </div>
        </div>
        </div>
        <div class="col-sm-2 col-xs-1 text-right">
        <div class="media-photo media-round">
        <img width="70" height="70" src="{{ Auth::user()->profile_picture->src }}" class="user-profile-photo">
        </div>
        </div>
        </div>
        </div>
        </div>
        @endif
        @endfor
        </div>
        </div>

        </div>
        </div>
        </div>
      </div>

<div class="col-md-5 col-md-pull-7 bg-white qt-sidebar-redesign space-4">
<form accept-charset="UTF-8" action="{{ url('/') }}/messaging/qt_reply/{{ $messages[0]->reservation_id }}" method="post">
<div class="text-center mini-profile media">
  <div class="panel-image">
    <div class="verified-badge-aligner">
      <a href="{{ url('/') }}/users/show/{{ $messages[0]->reservation->rooms->users->id }}" class="media-photo media-round">
        <img src="{{ $messages[0]->reservation->rooms->users->profile_picture->src }}" alt="{{ $messages[0]->reservation->rooms->users->first_name }}" height="150" width="150">
      </a>
    </div>
  </div>

  <div class="space-top-3 text-center">
    <div class="h4">
      <a href="{{ url('/') }}/users/show/{{ $messages[0]->reservation->rooms->users->id }}" class="text-normal">{{ $messages[0]->reservation->rooms->users->first_name }}</a>
    </div>
    <div class="text-medium-gray row-space-top-1">
       {{ $messages[0]->reservation->rooms->users->live }}
    </div>
  </div>

  @if($messages[0]->reservation->rooms->users->about)
    <div class="space-top-1 text-center text-wrap">
      <div class="expandable expandable-trigger-more expanded">
          <div class="expandable-content">
            <p>{{ $messages[0]->reservation->rooms->users->about }}</p>
            <div class="expandable-indicator expandable-indicator-light">
            </div>
          </div>
              <a class="expandable-trigger-more" href="#">
      <strong>+ {{ trans('messages.profile.more') }}</strong>
    </a>
        </div>
    </div>
  @endif

  @if($messages[0]->reservation->status == 'Accepted')
  <div class="space-top-3 text-left">
      <div class="text-medium-gray">
        {{ trans('messages.login.email') }}
      </div>
      <div class="space-top-1">
        {{ $messages[0]->reservation->rooms->users->email }}
      </div>
  </div>
  @endif
  @if($messages[0]->reservation->status == 'Accepted' && $messages[0]->reservation->host_users->primary_phone_number != '' )
  <div class="space-top-3 text-left">
      <div class="text-medium-gray">
        {{ trans('messages.profile.phone_number') }}
      </div>
      <div class="space-top-1">
        {{ $messages[0]->reservation->host_users->primary_phone_number }}
      </div>
  </div>
  @endif
</div>
 @if($messages[0]->reservation->list_type == 'Rooms' || ($messages[0]->reservation->list_type == 'Experiences' && $messages[0]->reservation->type != 'contact' ))
  <div class="js-messaging-sidebar-react-container">
  <div>
  <hr class="space-top-6">
  <div>
  <div class="row-space-6 row-space-top-6">
  <div class="qt-reservation-info clearfix">
  <div class="row-space-6 h4">
  <a class="text-normal" href="{{$messages[0]->reservation->rooms->link }}">{{ $messages[0]->reservation->rooms->name }}</a>
  </div>
  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans('messages.your_reservations.checkin') }}</span>
  </div>
  
 <!--  @if($messages[0]->special_offer_id!='')
  <div class="row-space-3 h4">{{ $checkin }}</div>
  </div>
  @else
  @endif -->
  <div class="row-space-3 h4">  {{$messages[0]->reservation->checkin_formatted}}</div>
  </div>

  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans('messages.your_reservations.checkout') }}</span>
  </div>
  <!-- @if($messages[0]->special_offer_id!='')
  <div class="row-space-3 h4">{{ $checkout }}</div>
  </div>
  @else
   @endif -->
   <div class="row-space-3 h4">{{ $messages[0]->reservation->checkout_formatted}}</div>
  </div>
 
  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans_choice('messages.home.guest',$messages[0]->reservation->number_of_guests ) }}</span>
  </div>
<!--   @if($messages[0]->special_offer_id!='')
  <div class="row-space-3 h4"> {{@$messages[0]->reservation->special_offer->number_of_guests }}</div>
  </div>
  @else 
  @endif -->
  <div class="row-space-3 h4">{{ $messages[0]->reservation->number_of_guests }}</div>
  </div>

  </div>
  </div>
  @endif
  </div>
  <hr>
  @if($messages[0]->reservation->list_type == 'Rooms' || ($messages[0]->reservation->list_type == 'Experiences' && $messages[0]->reservation->type != 'contact' ))
  
  <div class="row-space-top-6 qt-payment-info">
  <div class="h4 row-space-6">
  <span>{{ trans('messages.payments.payment') }}</span>
  </div>
  <div class="row-space-top-6">
  <div>
  <div class="row text-emphasis-gray">
  <div class="col-sm-8 text-left">
 <!--  @if($messages[0]->special_offer_id == '' || @$special_offer[0]->type =='pre-approval')
@endif -->
 <span>
  <span>
    <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->base_per_night }}</span> 
    </span>
    <span> x {{ $messages[0]->reservation->subtotal_multiply_text }}</span>
  </span>
 
  </div>
  <div class="col-sm-4 text-right">

<!--  @if($messages[0]->special_offer_id == '' || @$special_offer[0]->type =='pre-approval' )
 @endif -->

 <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->base_per_night*($messages[0]->reservation->list_type == 'Experiences' ? $messages[0]->reservation->number_of_guests : $messages[0]->reservation->nights) }}</span>
  </span>
  </div>
  </div>
  </div>
  @if($messages[0]->special_offer_id == '' || @$special_offer[0]->type =='pre-approval')
    @endif
  @foreach($messages[0]->reservation->discounts_list as $list)
  @if(@$list['price'])
  <div class="row text-emphasis-gray row-space-2 row-space-top-2 ">
  <div class="col-sm-8 text-left text-beach">
  <span>{{@$list['text']}}</span>
  </div>
  <div class="col-sm-4 text-right text-beach">
  <span>
  <span>-{{ $messages[0]->reservation->currency->symbol.@$list['price'] }}</span>
  </span>
  </div>
  </div>
  @endif
  @endforeach

  @if($messages[0]->reservation->additional_guest != 0 )
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.addtional_guest_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->additional_guest }}</span>
  </span>
  </div>
  </div>
  @endif
  @if($messages[0]->reservation->cleaning != 0 )
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.cleaning_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->cleaning }}</span>
  </span>
  </div>
  </div>
  @endif

  <!-- @if($messages[0]->special_offer_id != '' && $special_offer[0]->type=='special_offer')
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">

  <div class="col-sm-8 text-left">
  <span>
  <span>
  <span>{{ trans('messages.inbox.special_offer') }}</span>
  
  <span>({{ $messages[0]->reservation->currency->symbol.round($special_offer[0]->price / $special_offer_nights,2)  }}</span>
  
  </span>
  <span> x {{ $special_offer_nights }} {{ trans_choice('messages.rooms.night',$special_offer_nights) }})</span>
  </span>
  </div>

  <div class="col-sm-4 text-right">
  <span>

  <span>{{ $messages[0]->reservation->currency->symbol}}{{$special_offer[0]->price }}</span>

  </span>
  </div>
  </div>
  @endif -->
@if($messages[0]->reservation->service != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.service_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span> 
  <span>
          {{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->service }}
        
     </span>
  </span>
  </div>
  </div>
  @endif

  @if($messages[0]->reservation->airportfee != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.airport_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span> 
  <span>
          {{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->airportfee }}
        
     </span>
  </span>
  </div>
  </div>
  @endif

  @if($messages[0]->reservation->ngo_amount > 0)
    @if(count(json_decode($messages[0]->reservation->ngo_list)) > 0)
        @foreach(json_decode($messages[0]->reservation->ngo_list) as $ngolist)
          <div class="row text-emphasis-gray row-space-2 row-space-top-2">
             <div class="col-sm-8 text-left"><span>Ngo fee - {{ $ngolist->name }} </span></div>
              <div class="col-sm-4 text-right">
                  <span> 
                  <span>{{ $messages[0]->reservation->currency->symbol.$ngolist->amount }}</span>
                  </span>
                </div>
          </div>
        @endforeach
    @endif
  @endif




  @if($messages[0]->reservation->coupon_amount != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>
  @if($messages[0]->reservation->coupon_code == 'Travel_Credit')
  {{ trans('messages.referrals.travel_credit') }}
  @else
  {{ trans('messages.payments.coupon_amount') }}
  @endif
  </span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>-{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->coupon_amount }}</span>
  </span>
  </div>
  </div>
  @endif
  <hr>
  <div class="row row-space-3 row-space-top-2">
  <div class="col-sm-8 text-left">
  <strong>
  <span>{{ trans('messages.rooms.total') }}</span>
  </strong>
  </div>
  <div class="col-sm-4 text-right">
  <strong>
  <span>

  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->total }}</span>

  </span>
  </strong>
  </div>
  </div>
    @if($messages[0]->reservation->security != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.security_fee') }} <i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i></span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->security }}</span>
  </span>
  </div>
  </div>
  @endif
  </div>
  <div class="row-space-6 row-space-top-4 text-gray">
  <span>{{ trans('messages.inbox.protect_your_payments') }}</span>
  <span>&nbsp;</span>
  <span>{{ trans('messages.inbox.never_pay_outside',['site_name'=>$site_name]) }}</span>
  <span>&nbsp;</span>
  <span class="tns pos-rel">
  <i class="icon icon-question tns-payment-tooltip-trigger tool-amenity2">
  </i>
  <div class="tooltip-amenity tooltip-amenity2 tooltip-left-middle" data-sticky="true" aria-hidden="true" style="left: 23px; top: -21px;">
                  <dl class="panel-body" style="padding: 10px;">
                    <dt><p style="margin:0;">{{ trans('messages.inbox.never_pay_outside',['site_name'=>$site_name]) }}</p></dt>
                  </dl>

    </div>
    </span>
  </div>
  </div>
  @endif
  </div>
  </div>
</form>      
</div>
</div>
</div>
</main>
@stop
