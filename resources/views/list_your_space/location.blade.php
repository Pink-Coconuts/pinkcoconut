 <style type="text/css">

       html[lang="ar"] #js-manage-listing-nav{position: fixed !important;}
          @media (min-width: 767px){
   
  #ajax_container{margin-left:25%;}
  html[lang="ar"] #js-manage-listing-nav{position: fixed;}
  html[lang="ar"] #ajax_container{margin-right:25% !important;margin-left: 0px !important;}
}
  @media(min-width: 1100px){
  #ajax_container{margin-left: 16.66667%;}
  html[lang="ar"] #ajax_container{margin-right: 16.66667% !important; margin-left: 0px !important;}
}
@media (max-width: 760px){
  .modal-cell{padding-top: 0px !important;}
}
.pac-container {
    position: absolute !important;
}
    </style>
<div id="js-manage-listing-content-container" class="manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="lang-chang-label manage-listing-content col-lg-7 col-md-7"><div>
<div class="row-space-4">
  <div class="row">
    
      <h3 class="col-12">{{ trans('messages.lys.location_title') }}</h3>
    
  </div>
  <p class="text-muted">{{ trans('messages.lys.location_desc1') }}</p>
</div>

  <hr>

<div id="js-location-container" class="js-section">
  <div style="display: none;" class="js-saving-progress saving-progress">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.account.address') }}</h4>

<p>{{ trans('messages.lys.location_desc2') }}</p>

<div class="media-photo address-static-map">
  
    <div class="location-map-container-v2 {{ ($rooms_status->location == 0) ? 'empty-map' : '' }}"></div>
    <div class="location-map-pin-v2 map-pin {{ ($rooms_status->location == 0) ? 'moving' : 'hide set' }}"></div>

    @if($rooms_status->location == 1)
      <img width="100%" height="275" src="https://maps.googleapis.com/maps/api/staticmap?size=570x275&amp;center={{ $result->rooms_address->latitude }},{{ $result->rooms_address->longitude }}&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key={{ $map_key }}&amp;markers=icon:{{url('/')}}/images/map-pin-set-3460214b477748232858bedae3955d81.png%7C{{ $result->rooms_address->latitude }},{{ $result->rooms_address->longitude }}">      
    @endif
  
</div>
<div class="panel">
  <div class="panel-body text-center">
      
    @if($rooms_status->location == 0)
      <div class="text-center">
        <button id="js-add-address" class="btn btn-large">
          {{ trans('messages.lys.add_address') }}
        </button>
      </div>
    @endif

<address class="{{ ($rooms_status->location == 0) ? 'hide' : '' }}">
  <span class="address-line" ng-init="address_line_1 = '{{ addslashes($result->rooms_address->address_line_1) }}'; address_line_2 = '{{ addslashes($result->rooms_address->address_line_2) }}'">{{ $result->rooms_address->address_line_1 }} {{ ($result->rooms_address->address_line_2) ? '/ '.$result->rooms_address->address_line_2 : '' }}</span>
  <span class="address-line" ng-init="city = '{{ addslashes($result->rooms_address->city) }}'; state = '{{ addslashes($result->rooms_address->state) }}'">{{ $result->rooms_address->city }} {{ $result->rooms_address->state }}</span>
  <span class="address-line" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">{{ $result->rooms_address->postal_code }}</span>
  <span class="address-line" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">{{ $result->rooms_address->country_name }}</span>
</address>

<a data-event-name="edit_address_click" id="js-edit-address" class="js-edit-address-link edit-address-link {{ ($rooms_status->location == 0) ? 'hide' : '' }}" href="javascript:;">
  {{ trans('messages.lys.edit_address') }}
</a>
    
  </div>
</div>

</div>

<div id="address-flow-view"></div>

<div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  <div class="col-2 row-space-top-1 next_step">
    
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/description') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    
  </div>
  <div class="col-10 text-right next_step">
    
    
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
    
  </div>
</div>

</div></div>
                <div class="lang-help manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel">
    <div class="help-header-icon-container text-center va-container-h header-icon-light">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.your_trips.location') }}</h4>
      
  <p>{{ trans('messages.lys.edit_location_desc') }}</p>
<div class="tip-address-img"></div>
    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>