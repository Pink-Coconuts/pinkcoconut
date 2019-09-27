@extends('template')
@section('main')   
<main id="site-content" role="main">
  @include('common.subheader')
  <div id="host-dashboard-container">
    <div class="host-dashboard">
      <div class="page-container-full">
        <div class="header-color">
          <div class="page-container-responsive">
            <div class="row header-background">
              <div class="col-md-8 text-contrast dash_boader_detail">
                <div class="row">
                  <div class="col-md-2 image_dashboard">
                    <div class="va-container va-container-h collapsed-header">
                      <div class="va-middle">
                        <a href="{{ url('users/show/'.$user->id) }}" class="media-photo media-round pull-right" data-tracking="{&quot;section&quot;:&quot;header_profile_photo&quot;}">
                          <img src="{{$user->profile_picture->src}}" class="img-responsive">
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-10 image_content_dashboard">
                    <div class="va-container va-container-h collapsed-header">
                      <div class="va-middle text-lead">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
                       <div class="carousel-inner">
      <div class="item active">
        <strong>
                            {{trans('messages.host_dashboard.hi_first_name', ['first_name' => $user->first_name])}}
                          </strong>                           
                       {{ trans('messages.host_dashboard.title') }}
      </div>
<div class="item">
        <strong>
                            {{trans('messages.host_dashboard.hi_first_name', ['first_name' => $user->first_name])}}
                          </strong>
                          {{trans('messages.host_dashboard.welcome_message')}}
      </div>
      <div class="item">
        <strong>
                            {{trans('messages.host_dashboard.hi_first_name', ['first_name' => $user->first_name])}}
                          </strong>                         
                          {{ trans('messages.host_dashboard.title') }}
      </div>
                    
                          
                      
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="text-contrast col-md-4 panel-right collapsed-header">
                <div class="text-center">
                  <div class="va-container collapsed-header va-container-h">
                    <div class="va-middle text-contrast text-lead">
                    <h2><sup>{{$currency_symbol}}</sup><strong>{{ $completed_payout  + $future_payouts }} </strong></h2>
                    <p> {{trans('messages.host_dashboard.for_nights_in_month', ['count' => (@$completed_nights + @$future_nights),'count1' => (@$total_rooms),'month' => trans('messages.lys.'.date('F'))])}}</p>

                     <div class="table-responsive">          
                     <table class="table borderless">
                      <thead>
                        <tr><th class="text-center" colspan="2">{{trans('messages.lys.'.date('F'))}} {{ trans('messages.host_dashboard.breakdown') }}</th></tr>
                      </thead>
    <tbody>
      <tr>
        <td class="text-left">{{ trans('messages.host_dashboard.already_paid_out') }}</td>
        <td class="text-right"><strong><sup>{{ $currency_symbol }}</sup>{{ $completed_payout }}</strong></td>
     </tr>
     <tr>
      <td class="text-left">{{ trans('messages.host_dashboard.expected_earnings') }}</td>
      <td class="text-right"><strong><sup>{{$currency_symbol}}</sup>{{ $future_payouts }}</strong></td>
      </tr>
      <tr class="total">
      <td class="text-left">
        {{ trans('messages.rooms.total') }}

        <i class="fa fa-question-circle tool-amenity2" title="{{trans('messages.host_dashboard.total_details')}}" rel="tooltip" ></i>

</td>
        <td class="text-right"><strong><sup>{{$currency_symbol}}</sup>{{ $completed_payout  + $future_payouts}} </strong></td>
     </tr>
     <tr class="total_paid">
        <td class="text-left"> {{ trans('messages.host_dashboard.total_paid_out_in') }} {{date('Y')}}</td>
        <td class="text-right"><strong><sup>{{$currency_symbol}}</sup>{{ $total_payout}}</strong></td>
     </tr>
    </tbody>
  </table>
  </div>

<div class="transaction_history">
<h6><a href="{{ url('users/transaction_history') }}">{{ trans('messages.host_dashboard.transaction_history') }}</a></h6>
</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="page-container-full alt-bg-module-panel-container relative">
        <div>
          <div class="page-container-responsive relative">
            <div ng-controller="Tabsh">
            <ul role="tablist" class="tabs">
           
              <li>
                <a href="javascript:;"  ng-click="show= 1;tab1=true;tab2=false" class="tab-item text-lead h4" role="tab" aria-controls="hdb-tab-standalone-first"  aria-selected="@{{tab1}}" data-tracking="{&quot;section&quot;:&quot;inbox_pending_requests_tab&quot;}">
                  ({{@$pending_count}} {{ trans('messages.dashboard.new') }}) {{ trans('messages.host_dashboard.Pending_requests_and_inquiries') }}
                </a>
              </li>
              <li class="relative">
                <a href="javascript:;" ng-click="show = 2 ;tab2=true;tab1=false" class="tab-item text-lead h4" role="tab" aria-controls="hdb-tab-standalone-second" aria-selected="@{{tab2}}" data-tracking="{&quot;section&quot;:&quot;inbox_alerts_tab&quot;}">
                   {{ trans('messages.host_dashboard.Notifications') }}
                   <i class="alert-count text-center {{ @$notification_count != '0' ? '' : 'hide' }}">{{@$notification_count}}</i>
                </a>
                
              </li>
            </ul>
         
          <ul class="list-unstyled" ng-show="show == 1">
            <li id="hdb-tab-standalone-first" class="tab-panel hdb-light-bg" role="tabpanel" aria-hidden="false">
              <div class="text-lead text-muted no-req-res-row text-center">
               <div class="panel space-4">

                <ul class="list-layout">
            @foreach($pending as $all)
             @if(@$all->host_check==1 && (@$all->reservation->status == 'Pending' ||  @$all->reservation->status == 'Inquiry'))
            <li id="thread_{{ $all->id }}" class="panel-body thread-read thread">
  <div class="row">

    <div class="col-sm-2 col-md-4 col-lg-3 lang-chang-label thread-author inbox_history1">
      <div class="row row-table">
        <div class="thread-avatar col-md-5 lang-chang-label">       
          <a data-popup="true" href="{{ url('users/show/'.$all->user_details->id)}}"><img height="50" width="50" title="{{ @$all->user_details->first_name }}" src="{{ $all->user_details->profile_picture->src }}" class="media-round media-photo" alt="{{ $all->user_details->first_name }}"></a>
        </div>
        <div class=" thread-name list_name1">
          <span class="text_name3">  {{ $all->user_details->first_name }}</span>
          <br>
           <span class="thread-date"> {{ $all->created_time }}</span>
        </div>
      </div>
    </div>

<div class="col-sm-7 col-md-5 col-lg-6 thread-body lang-chang-label inbox_history2" >

<div class=" thread-name list_name">
          <span class="text_name3">  {{ $all->user_details->first_name }}</span>
             <span class="thread-date"> {{ $all->created_time }}</span>
        </div>

    <div class="common_inbox">
    @if(@$all->host_check ==1 && @$all->reservation->status == 'Pending')
    <a class="link-reset text-muted1" href="{{ url('reservation')}}/{{ @$all->reservation_id }}">
    @elseif(@$all->host_check ==1 && @$all->reservation->status != 'Pending')
    <a class="link-reset text-muted1" href="{{ url('messaging/qt_with')}}/{{ @$all->reservation_id }}">
    @endif
    @if(@$all->guest_check !=0)
    <a class="link-reset text-muted1" href="{{ url('z/q')}}/{{ @$all->reservation_id }}">
    @endif
   
         <span class="thread-subject ng-binding unread_message">{{ @$all->message }}</span>
 <div class="msg_view_guest">
                      <span class="text_address">
        <span class="text-muted1">
          @if($all->reservation->list_type == 'Experiences')
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->reservation->rooms->host_experience_location->address_line_1 }} {{ @$all->reservation->rooms->host_experience_location->address_line_2 }},</span><span class="locality">{{ @$all->reservation->rooms->host_experience_location->city }},</span> <span class="region">{{ @$all->reservation->rooms->host_experience_location->state }}</span>
          @else
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->rooms_address->address_line_1 }} {{ @$all->rooms_address->address_line_2 }},</span><span class="locality">{{ @$all->rooms_address->city }},</span> <span class="region">{{ @$all->rooms_address->state }}</span>
          @endif
          @if($all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
         <span> ({{  ( date($php_format_date, strtotime( @$all->reservation->checkin)) ) }} - {{  (date($php_format_date, strtotime( @$all->reservation->checkout))) }})</span>
         @endif
        </span>       
      </span>
        @if(@$all->inbox_thread_count > 1)
          <span>
            <i class="alert-count1 text-center inbox_message_count">
              {{ $all->inbox_thread_count }}
            </i>
          </span>
        @endif
      </div>
</a>
</a>
</a>
</div>
@if($all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
<div class="next_list">
  <div class="status_list">
    <span class="label label-{{ @$all->reservation->status_color }}">{{ @$all->reservation->status_language }}</span>
        <br>

       <span class="lang-chang-label" ng-show="{{ ($all->host_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->subtotal - @$all->reservation->host_fee }} 
            </span>
       <span class="lang-chang-label" ng-show="{{ ($all->guest_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->total }} 
            </span>
</div>
</div>
@endif
</div>

<div class="col-md-3 col-sm-7 col-lg-3 thread-label lang-chang-label inbox_history">
  @if($all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
        <span class="label label-{{ @$all->reservation->status_color }}">{{ @$all->reservation->status_language }}</span>
        <br>

       <span class="lang-chang-label" ng-show="{{ ($all->host_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->subtotal - @$all->reservation->host_fee }} 
            </span>
       <span class="lang-chang-label" ng-show="{{ ($all->guest_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->total }} 
            </span>
            @endif
    </div>
  </div>
</li>@endif
@endforeach

          </ul>
          <div class="panel-body">
            <a href="{{ url('inbox') }}">{{ trans('messages.dashboard.all_messages') }}</a>
          </div>
      </div>
              </div>
            </li>
            
          </ul>

<!-- notification -->

          <ul class="list-unstyled" ng-show="show==2" id="{{ count($unread) }}">
            <li id="hdb-tab-standalone-first" class="tab-panel hdb-light-bg" role="tabpanel" aria-hidden="false">
              <div class="text-lead text-muted no-req-res-row text-center">
               <div class="panel space-4">
      
        <ul class="list-layout">
            @foreach($unread as $all)             
            <li id="thread_{{ $all->id }}" class="panel-body thread-read thread">
  <div class="row">
    <div class="col-sm-2 col-md-4 col-lg-3 lang-chang-label thread-author inbox_history1">
      <div class="row row-table">
        <div class="thread-avatar col-md-5 lang-chang-label">
        @if($all->user_to == $all->user_from)
          <a data-popup="true" href="#">
        <img height="50" width="50" title="{{ $all->admin_name }}" src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="media-round media-photo" alt="{{ $all->admin_name }}">
        </a>
        @else         
          <a data-popup="true" href="{{ url('users/show/'.$all->user_details->id)}}"><img height="50" width="50" title="{{ @$all->user_details->first_name }}" src="{{ $all->user_details->profile_picture->src }}" class="media-round media-photo" alt="{{ $all->user_details->first_name }}"></a>
          @endif
        </div>
       <div class=" thread-name list_name1">
        @if($all->user_to == $all->user_from)
         <span class="text_name3"> {{ $all->admin_name }}</span>
          <br>
        <span class="thread-date">   {{ $all->created_time }}</span>
        @else
         <span class="text_name3"> {{ $all->user_details->first_name }}</span>
          <br>
        <span class="thread-date">   {{ $all->created_time }}</span>
        @endif
        </div>
      </div>
    </div>
    <div class="col-sm-7 col-md-5 col-lg-6 thread-body lang-chang-label inbox_history2" >
  <div class=" thread-name list_name">
    @if($all->user_to == $all->user_from)
         <span class="text_name3"> {{ $all->admin_name }}</span>
         
        <span class="thread-date">   {{ $all->created_time }}</span>
        @else
         <span class="text_name3"> {{ $all->user_details->first_name }}</span>
  
        <span class="thread-date">   {{ $all->created_time }}</span>
        @endif
        </div>
        <div class="common_inbox">
    @if(@$all->host_check ==1 && @$all->reservation->status == 'Pending')
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted1" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted1" href="{{ url('reservation')}}/{{ @$all->reservation_id }}">
      @endif
    @elseif(@$all->host_check ==1 && @$all->reservation->status != 'Pending')
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted1" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted1" href="{{ url('messaging/qt_with')}}/{{ @$all->reservation_id }}">
    @endif
    @endif
    @if(@$all->guest_check !=0)
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted1" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted1" href="{{ url('z/q')}}/{{ @$all->reservation_id }}">
      @endif
    @endif
             <span class="thread-subject ng-binding unread_message">{{ @$all->message }}</span>
        <div class="msg_view_guest">
                      <span class="text_address">
        <span class="text-muted1">
              @if($all->user_to != $all->user_from)
          @if(@$all->reservation->list_type == 'Experiences')
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->reservation->rooms->host_experience_location->address_line_1 }} {{ @$all->reservation->rooms->host_experience_location->address_line_2 }},</span><span class="locality">{{ @$all->reservation->rooms->host_experience_location->city }},</span> <span class="region">{{ @$all->reservation->rooms->host_experience_location->state }}</span>
          @else
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->rooms_address->address_line_1 }} {{ @$all->rooms_address->address_line_2 }},</span><span class="locality">{{ @$all->rooms_address->city }},</span> <span class="region">{{ @$all->rooms_address->state }}</span>
          @endif
          @if(@$all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
         <span> ({{  (date($php_format_date, strtotime( @$all->reservation->checkin))) }} - {{  (date($php_format_date, strtotime( @$all->reservation->checkout))) }})</span>
         @endif
        </span>
        @endif     
        </span>   
        @if(@$all->inbox_thread_count > 1)
          <span>
            <i class="alert-count1 text-center inbox_message_count">
              {{ $all->inbox_thread_count }}
            </i>
          </span>
        @endif  
   </div>
</a>

</a>
</a>
</div>
@if(@$all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
<div class="next_list">
  <div class="status_list">
   <span class="label label-{{ @$all->reservation->status_color }}">{{ @$all->reservation->status_language }}</span>
    <br> 

       <span class="lang-chang-label" ng-show="{{ ($all->host_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->subtotal - @$all->reservation->host_fee }} 
            </span>
       <span class="lang-chang-label" ng-show="{{ ($all->guest_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->total }} 
            </span>
</div>
</div>
 @endif

   </div>
  <div class="col-md-3 col-sm-7 col-lg-3 thread-label lang-chang-label inbox_history">
    @if(@$all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
        <span class="label label-{{ @$all->reservation->status_color }}">{{ @$all->reservation->status_language }}</span>
    <br>
       <span class="lang-chang-label" ng-show="{{ ($all->host_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->subtotal - @$all->reservation->host_fee }} 
            </span>
       <span class="lang-chang-label" ng-show="{{ ($all->guest_check) ? 'true' : 'false' }}"> {{ @$all->reservation->currency->original_symbol }} {{ @$all->reservation->total }} 
            </span>
      @endif
    </div>
  </div>
</li>
@endforeach

          </ul>
          <div class="panel-body">
            <a href="{{ url('inbox') }}">{{ trans('messages.dashboard.all_messages') }}</a>
          </div>
      </div>
              </div>
            </li>
           
          </ul>
          </div>
        </div>
      </div>
      <div class="page-container-full alt-bg-module-panel-container relative">
        <div class="page-container-responsive referral-panel">
          <div class="panel">
            <div class="panel-body text-center">
              <div class="space-top-4 space-4">
                <h3>
                  <strong>
                    {{trans('messages.host_dashboard.earn_Travel')}}
                  </strong>
                </h3>
                <p>
                 {{ trans('messages.referrals.earn_up_to') }} {{ $result->value(5) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.everyone_invite') }}.
                </p>
                <a data-tracking="{&quot;section&quot;:&quot;promo_invite_friends&quot;}" href="{{ url('invite') }}" class="btn btn-large btn-primary">
                   {{trans('messages.host_dashboard.invite_friends')}}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop      

