@extends('template')

@section('main')  

<main id="site-content" role="main">

@include('common.subheader')

<div class="page-container-responsive space-top-4 space-4">
  <div class="row">
    <div class="col-md-3 lang-chang-label dash-left-sec">
      <div class="panel space-4">
        <div class="media media-photo-block
             dashboard-profile-photo panel-image ">
          <a href="{{ url('users/show/'.Auth::user()->id) }}" title="{{ trans('messages.dashboard.view_profile') }}">
          {!! Html::image(Auth::user()->profile_picture->src, Auth::user()->first_name, ['class'=>'img-responsive', 'width' => '190', 'height'=>'190', 'title' => Auth::user()->first_name]) !!}
          </a>
          
            <div class="upload-profile-photo-cta btn btn-contrast btn-block text-wrap">
              <a href="{{ url('users/edit/media') }}" class="link-reset">
                <i class="icon icon-camera"></i>
                {{ trans('messages.dashboard.add_profile_photo') }}
</a>            </div>
        </div>
        <div class="panel-body">
          <h2 class="text-center">
            {{ Auth::user()->first_name }}
          </h2>
          <ul class="list-unstyled text-center">
            <li>
            <a href="{{ url('users/show/'.Auth::user()->id) }}">{{ trans('messages.dashboard.view_profile') }}</a>
            </li>
            @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
            <li>
                <a href="{{ url('users/edit') }}" class="btn btn-primary btn-block text-wrap space-top-1" id="edit-profile">{{ trans('messages.dashboard.complete_profile') }}</a>
            </li>
            @endif
          </ul>
        </div>
      </div>
@if(Auth::user()->users_verification->show() || Auth::user()->verification_status == 'Verified')
  <div class="panel row-space-4 verifications-panel-vertical ">
  <div class="panel-header row">
    <div class="pull-left">
      {{ trans('messages.dashboard.verifications') }}
    </div>
  </div>
  <div class="panel-body">
      <ul class="list-unstyled">
      @if(Auth::user()->users_verification->email == 'yes')
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
      @if(Auth::user()->users_verification->phone_number == 'yes')
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
      @if(Auth::user()->users_verification->facebook == 'yes')
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
      @if(Auth::user()->users_verification->google == 'yes')
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
      @if(Auth::user()->users_verification->linkedin == 'yes')
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
      @if(Auth::user()->verification_status == 'Verified')
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
    <!-- <a href="{{ url('/') }}/users/edit_verification">
      {{ trans('messages.dashboard.add_verifications') }}
    </a> -->
  </div>
</div>
@endif

    </div>

    <div class="col-md-9 dash-right-sec">
        <div class="panel space-4">
          <div class="panel-header">
           <span class="lang-chang-label"> {{ trans('messages.dashboard.welcome') }} {{ $site_name }},</span> {{Auth::user()->first_name }}!
          </div>
          <div class="panel-body">
            <p>
              {{ trans('messages.dashboard.welcome_desc') }} @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '') {{ trans('messages.dashboard.welcome_ask_to_complete_profile') }} @endif
            </p>

            @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
            <ul class="list-unstyled">
              @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
                <li class="space-2">
                  <strong><a href="{{ url('users/edit') }}">{{ trans('messages.dashboard.complete_your_profile') }}</a></strong>
                  <div>{{ trans('messages.dashboard.complete_your_profile_desc') }}</div>
                </li>
              @endif
            </ul>
            @endif
          </div>
        </div>

      @if(Auth::user()->users_verification->email == 'no')
        <div class="panel space-4">
          <div class="panel-header">
            {{ trans_choice('messages.header.notification',2) }}
          </div>
          <div class="panel-body">
            
  <ul class="list-unstyled hdb-light-bg">
    @if(Auth::user()->users_verification->email == 'no')
      <li class="default alert3">
        <div class="row row-table ">
          <div class="col-11 col-middle">
              <span class="dashboard_alert_text">
                {{ trans('messages.dashboard.confirm_your_email') }} <a href="{{ url('users/request_new_confirm_email') }}">{{ trans('messages.dashboard.request_confirmation_email') }}</a> {{ trans('messages.login.or') }} <a href="{{ url('users/edit') }}">{{ trans('messages.dashboard.change_email_address') }}</a>.
              </span>
          </div>
          <div class="col-1 col-middle">
          </div>
        </div>
      </li>
    @endif
  </ul>
          </div>
        </div>
    @endif

      <div class="panel space-4">
        <div class="panel-header">
          {{ trans_choice('messages.dashboard.message',2) }} ({{$all_message->count()}} {{ trans('messages.dashboard.new') }})
        </div>
        <ul class="list-layout">
            @foreach($all_message as $all)
            <li id="thread_{{ $all->id }}" class="panel-body thread-read thread">
  <div class="row">
    <div class="col-sm-2 col-md-4 col-lg-3 lang-chang-label thread-author">
      <div class="row row-table">
       <div class="thread-avatar col-md-5 lang-chang-label">        
          <a data-popup="true" href="#">
            @if($all->user_to == $all->user_from)
        <img height="50" width="50" title="{{ $all->admin_name }}" src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="media-round media-photo" alt="{{ $all->admin_name }}">
        @else
        <img height="50" width="50" title="{{ @$all->user_details->first_name }}" src="{{ $all->user_details->profile_picture->src }}" class="media-round media-photo" alt="{{ $all->user_details->first_name }}">
        @endif
      </a>
        </div>
         <div class=" thread-name list_name1">
          @if($all->user_to == $all->user_from)
          <span class="text_name3"> {{ $all->admin_name }}</span>
          <br>
          <span class="thread-date"> {{ $all->created_time }}</span>
          @else
           <span class="text_name3"> {{ $all->user_details->first_name }}</span>
          
          <br>
          <span class="thread-date"> {{ $all->created_time }}</span>
          @endif
        </div>
      </div>
    </div>
      <div class="col-sm-10 col-md-5 col-lg-7 thread-body lang-chang-label">
    <div class="thread-name list_name">
      @if($all->user_to == $all->user_from)
          <span class="text_name3"> {{ $all->admin_name }}</span>

          <span class="thread-date"> {{ $all->created_time }}</span>
          @else
           <span class="text_name3">{{ $all->user_details->first_name }}</span>
          
         
          <span class="thread-date"> {{ $all->created_time }}</span>
@endif
        </div>
        <div class="common_inbox">
    @if(@$all->host_check ==1 && @$all->reservation->status == 'Pending')
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted text_prag" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted text_prag" href="{{ url('reservation')}}/{{ @$all->reservation_id }}">
      @endif
    @elseif(@$all->host_check ==1 && @$all->reservation->status != 'Pending')
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted text_prag" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted text_prag" href="{{ url('messaging/qt_with')}}/{{ @$all->reservation_id }}">
    @endif
    @endif
    @if(@$all->guest_check !=0)
    @if($all->user_to == $all->user_from)
    <a class="link-reset text-muted text_prag" href="{{ url('admin_messages')}}/{{ @$all->user_to }}">
    @else
    <a class="link-reset text-muted text_prag" href="{{ url('z/q')}}/{{ @$all->reservation_id }}">
    @endif
    @endif
    
         <span class="thread-subject ng-binding unread_message">{{ @$all->message }}</span>
         @if($all->user_to != $all->user_from)
        <div class="msg_view_guest">
                      <span class="text_address">
        <span class="text-muted text_prag">
          @if(@$all->reservation->list_type == 'Experiences')
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->reservation->rooms->host_experience_location->address_line_1 }} {{ @$all->reservation->rooms->host_experience_location->address_line_2 }},</span><span class="locality">{{ @$all->reservation->rooms->host_experience_location->city }},</span> <span class="region">{{ @$all->reservation->rooms->host_experience_location->state }}</span>
          @else
            <span class="street-address" ng-show="{{ @$all->reservation->status == 'Accepted' }}">{{ @$all->rooms_address->address_line_1 }} {{ @$all->rooms_address->address_line_2 }},</span><span class="locality">{{ @$all->rooms_address->city }},</span> <span class="region">{{ @$all->rooms_address->state }}</span>
          @endif
          @if(@$all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
         <span> ({{  (date($php_format_date, strtotime( @$all->reservation->checkin))) }} - {{  (date($php_format_date, strtotime( @$all->reservation->checkout))) }})</span>
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
   @endif
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
   <div class="col-md-3 col-sm-7 col-lg-2 lang-chang-label mob_off">
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
  </div>
</div>

    </main>

@stop    