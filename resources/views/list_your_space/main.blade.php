@extends('template')

@section('main')
    <main id="site-content" class="whole_list" role="main" ng-controller="manage_listing">
      
<div class="manage-listing  never-listed" id="js-manage-listing">
  <div class="manage-listing-alerts">
    <div id="js-disaster-alert"></div>
  </div>

  <div id="ajax_header">
  @include('list_your_space.header')
  </div>

<!-- Center Part Starting  -->
  <div class="manage-listing-row-container">
    @include('list_your_space.navigation')
   <div id="ajax_container" class="col-lg-10 col-md-9 mar-left-cont lang-left" ng-init="saving_text = '{{trans('messages.lys.saving').'...'}}'; saved_text='{{trans('messages.lys.saved').'!'}}'; rooms_default_description = {{json_encode(['name' => $result->name, 'summary' => $result->summary])}}">
   @if($room_step == 'calendar')
    @include('list_your_space.edit_calendar')
   @else
    @include('list_your_space.'.$room_step)
   @endif
   </div>

   
    
      <div id="js-manage-listing-background" class="hide-sm">
        <div class="illustration-container va-container-v va-container-h">
          <!-- {!! Html::image('images/amenities.png', '', ['class' => 'bg-illustration illst-amenities hide']) !!}
          {!! Html::image('images/basics.png', '', ['class' => 'bg-illustration illst-basics hide']) !!}
          {!! Html::image('images/calendar.png', '', ['class' => 'bg-illustration illst-calendar hide']) !!}
          {!! Html::image('images/description.png', '', ['class' => 'bg-illustration illst-description hide']) !!}
          {!! Html::image('images/location.png', '', ['class' => 'bg-illustration illst-location hide']) !!}
          {!! Html::image('images/photos.png', '', ['class' => 'bg-illustration illst-photos hide']) !!}
          {!! Html::image('images/pricing.png', '', ['class' => 'bg-illustration illst-pricing hide']) !!}
          {!! Html::image('images/rules_prep.png', '', ['class' => 'bg-illustration illst-rules-and-prep illst-instant-book hide']) !!} -->
        </div>
      </div>
  </div>
<!-- Center Part Ending -->

  @include('list_your_space.footer')

</div>

    <div id="gmap-preload" class="hide"></div>
    
    <div class="ipad-interstitial-wrapper"><span data-reactid=".2"></span></div>

<div class="modal welcome-new-host-modal" aria-hidden="{{ ($result->started == 'Yes') ? 'true' : 'false' }}">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-body">
          <div class="row row-table">
            <div class="col-12 col-middle text-center">
              <div class="h2 row-space-6 row-space-top-4">
                  {{ trans('messages.lys.you_created_listing') }} </div>
              <div class="steps-remaining-circle anim-bounce-in visible">
                <div class="h1 steps-remaining-text">
                  <strong>
                    5
                  </strong>
                </div>
              </div>
              <div class="h4 steps-remaining-more-text text-center row-space-top-2 row-space-4 fade-in">
                {{ trans('messages.lys.more_steps_to_lys') }}
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body text-center">
          <button class="btn btn-primary js-finish" data-track="welcome_modal_finish_listing">
            {{ trans('messages.lys.finish_my_listing') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="address-flow-view">
<div aria-hidden="true" style="" class="modal" role="dialog" data-sticky="true">
  <div class="modal-table">
    <div class="modal-cell" style="padding-top:30px; padding-bottom:10px;">
      <div class="modal-content">
      <div id="js-address-container">
        
      </div>
        
      </div>
    </div>
  </div>
</div>
</div>

<div id="js-error" class="modal show" aria-hidden="true" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">
          
        </div>
        <div class="panel-body">
          <p> </p>
        </div>
        <div class="panel-footer">
          <button data-behavior="modal-close" class="btn">
            {{ trans('messages.home.close') }}
          </button>
          <button class="btn btn-primary js-delete-photo-confirm hide" data-id="">
            {{ trans('messages.lys.delete') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@if($result->status == NULL)
<div id="js-list-space-tooltip" class="animated tooltip tooltip-bottom-left list-space-tooltip anim-fade-in-up show finish_tooltip" aria-hidden="true">
  <div class="panel-body">
    <div class="media">
      <a class="pull-right panel-close" href=""></a>
      <div class="media-body">
          <h4>{{ trans('messages.lys.listing_congratulation') }}</h4>
            <p>{{ trans('messages.lys.listing_congratulation_desc') }}</p>
      </div>
    </div>
  </div>
</div>
@endif

<div class="modal finish-modal hide" aria-hidden="false" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content content-container">
        <div class="panel">
          <a data-behavior="modal-close" class="modal-close" href="javascript:;" onclick="window.location.reload()" ></a>
          
            <div class="finish-modal-header"></div>
            <div class="listing-card-container">
              
<div class="listing">

  <div class="panel-image listing-img">
    
    <a class="media-photo media-cover" target="" href="{{ url('rooms/'.$result->id) }}">
      <div class="listing-img-container media-cover text-center">
        
        <img alt="@{{ room_name }}" class="img-responsive-height" ng-src="@{{ popup_photo_name }}" data-current="0" itemprop="image">
        
      </div>
    </a>

    <a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="" href="{{ url('rooms/'.$result->id) }}">
      <div>
        <sup class="h6 text-contrast"><span id="symbol_finish"></span></sup>
        <span class="h3 text-contrast price-amount">@{{ popup_night }}</span>
        <sup class="h6 text-contrast"></sup>
        
      </div>
      
    </a>
    
    <div class="panel-overlay-top-right wl-social-connection-panel">
      
    </div>

  </div>

  <div class="panel-body panel-card-section">
    <div class="media">
      
        <a class="media-photo-badge pull-right card-profile-picture card-profile-picture-offset" href="{{ url('users/show/'.$result->user_id) }}">
          <div class="media-photo media-round">
            <img alt="" src="{{ $result->users->profile_picture->src }}">
          </div>
          
        </a>
      
      <h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="d">
        
        <a class="text-normal" target="" href="{{ url('rooms/'.$result->id) }}">
          @{{ popup_room_name }}
        </a>
      </h3>
      <div class="text-muted listing-location text-truncate" itemprop="description">@{{ popup_room_type_name }} · @{{ popup_state }}, @{{ popup_country }}</div>
      <div class="text-muted listing-location text-truncate" itemprop="description">{{$default_currency->symbol}} @{{ popup_price }} {{ trans('messages.rooms.per_night') }}</div>
    </div>

  </div>
</div>
            </div>
          
          <div>
            <div class="panel-body finish-modal-body">
              <h3 class="text-center">
                {{ trans('messages.lys.listing_published') }}!
              </h3>
              <p class="col-11 col-center text-center text-muted">
                {{ trans('messages.lys.listing_published_desc') }}
              </p>
              <div class="row row-space-top-5">
                <div class="col-offset-1 col-5">
                  <a target="_blank" href="{{ url('rooms/'.$result->id) }}" id="view-listing-button" class="btn btn-block">{{ trans('messages.lys.view_listing') }}</a>
                </div>
                <div class="col-5">
                  <a href="{{ url('manage-listing/'.$result->id.'/calendar') }}" class="btn btn-block btn-primary">{{ trans('messages.lys.go_to_calendar') }}</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div tabindex="-1" aria-hidden="true" role="dialog" class="modal show import_pop" id="export_popup">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<div class="panel">
<div class="panel-header">
<span>{{ trans('messages.lys.export_calc') }}</span>
<a data-behavior="modal-close" class="modal-close" href="javascript:;">
</a>
</div>
<div class="panel-body">
<p>
<span>{{ trans('messages.lys.copy_past_ical_link') }}</span>
</p>
<input type="text" value="{{ url('calendar/ical/'.$result->id.'.ics') }}" readonly="">
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Remove Already synced Calendar -->
<div tabindex="-1" aria-hidden="true" role="dialog" class="modal show import_pop" id="remove_sync_popup">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel">
          <div class="panel-header">
            <span>{{ trans('messages.lys.remove_calc') }}</span>
            <a data-behavior="modal-close" class="modal-close" href="javascript:;">
            </a>
          </div>
          <div class="panel-body remove_sync_cal_container">
            <div ng-repeat="sync_cal in sync_cal_details">
              <a class="sync_cal_name" href="javascript:;" id="remove_cal_confirm" data-ical_id="@{{ sync_cal.id }}" ng-click="show_confirm_popup(sync_cal.id)">
                @{{ sync_cal.name }}
              </a>
            </div>
            <div ng-show="sync_cal_details.length == 0">
              <p> {{ trans('messages.lys.no_cal_synced') }}</p>
            </div>         
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Remove Already synced Calendar -->

<!-- Confirm Remove Synced Calendar -->
<div tabindex="-1" aria-hidden="true" role="dialog" class="modal show import_pop" id="remove_sync_confirm_popup">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel remove_sync_confirm_panel">
          <div class="panel-header">
            <span>{{ trans('messages.lys.remove_calc') }}</span>
            <a data-behavior="modal-close" class="modal-close remove_sync_button" href="javascript:;">
            </a>
          </div>
          <div class="panel-body">
              <p>{{ trans('messages.lys.remove_calc_confirm_message') }}</p>
          </div>
          <div class="panel-footer">
            <button class="btn btn-danger btn-ok remove_sync_button" data-behavior="modal-close" class="btn btn-danger">{{ trans('messages.your_reservations.cancel') }}</button>
            <button class="btn btn-primary btn-ok remove_ical_link" data-ical_id="" ng-click="remove_sync_cal()">{{ trans('messages.lys.delete') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Confirm Remove Synced Calendar -->

<div tabindex="-1" aria-hidden="{{ ($errors->has('name')) ? 'false' : 'true' }}" role="dialog" class="modal show import_pop" id="import_popup">
<div class="modal-table">
<div class="modal-cell">
<div style="max-width:552px;" class="modal-content">
<div class="wizard">
<div class="wizard-page-wrapper">
<div class="panel">
<div class="panel-header">
<span>{{ trans('messages.lys.import_new_calc') }}</span>
<a data-behavior="modal-close" class="modal-close" href="javascript:;">
</a>
</div>
<div class="panel-body">
<p style="margin-bottom:20px;">
<span>{{ trans('messages.lys.import_calendar_desc') }}</span>
</p>
{!! Form::open(['url' => url('calendar/import/'.$result->id), 'name' => 'export']) !!}
<label style="margin-bottom:20px;padding:0;">
<p style="margin-bottom:10px;" class="label">
<span>{{ trans('messages.lys.calendar_address') }}</span>
</p>
<input type="text" value="{{ old('url') }}" name="url" placeholder="{{ trans('messages.lys.ical_url_placeholder') }}" class="space-1 {{ ($errors->has('url')) ? 'invalid' : '' }}">
<span class="text-danger">{{ $errors->first('url') }}</span>
</label>
<label style="padding:0;margin-bottom:0;">
<p style="margin-bottom:10px;" class="label">
<span>{{ trans('messages.lys.name_your_calendar') }}</span>
</p>
<input type="text" value="{{ old('name') }}" name="name" placeholder="{{ trans('messages.lys.ical_name_placeholder') }}" class="space-1 {{ ($errors->has('name')) ? 'invalid' : '' }}">
<span class="text-danger">{{ $errors->first('name') }}</span>
</label>
<div style="margin-top:20px;">
<button name="" data-prevent-default="true" class="btn btn-primary" ng-disabled="export.$invalid">
<span>{{ trans('messages.lys.import_calc') }}</span>
</button>
</div>
{!! Form::close() !!}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<input type="hidden" id="room_id" value="{{ $result->id }}">
<input type="hidden" id="room_status" value="{{ $result->status }}">

</main>

@stop
<style type="text/css">
body{overflow-y: hidden;}
/*  #js-list-space-tooltip{left: 45px !important;top: auto !important;
bottom: 142px;}*/
html[lang="ar"]  #js-list-space-tooltip{left: auto !important;right:45px !important;top: auto !important;
bottom: 145px;}
@media (max-width: 350px){
/*   #js-list-space-tooltip{left: 80px !important;top: auto !important;
bottom: 90px !important;width: 215px !important;}*/
html[lang="ar"]  #js-list-space-tooltip{left: auto !important;right:80px !important;top: auto !important;
bottom: 90px !important; width: 215px !important;}
  }
/*@media (max-width: 760px){
  .manage-listing-content{width: 100% !important;}
  .select.select-large.select-block.row-space-2{margin-top: 20px;}
}
@media (min-width: 760px){
  .manage-listing-content{width: 58.33% !important;}
}
@media (min-width: 760px){
  .manage-listing-content{width: 58.33% !important;}
}
@media (min-width: 1100px){
  .manage-listing-content{width: 58.33% !important;}
}*/
.location-map-pin-v2{left: 45% !important;}
</style>
@push('scripts')
@if($result->started == 'No')
<script type="text/javascript">
  $('body').addClass('pos-fix3');   
</script>
@endif
@endpush