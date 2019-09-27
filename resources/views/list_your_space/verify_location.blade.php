
<div class="panel">
  <div class="panel-header">
    <a data-behavior="modal-close" class="modal-close" href="javascript:;"></a>
    <div class="h4 js-address-nav-heading">
      {{ trans('messages.lys.verify_location') }}<br>
      <small>{{ trans('messages.lys.move_map_to_pin_listing_location') }}</small>
    </div>
  </div>
  <div class="flash-container" id="js-flash-error-clicked-frozen-field"></div>
  <div class="panel-body">
    <div id="js-disaster-address-alert" class="media row-space-2 hide">
      <i class="icon icon-flag icon-beach pull-left icon-size-2"></i>
      <div class="media-body">
        <strong>{{ trans('messages.lys.new_location_outside_disaster') }}</strong><br>
        <span class="text-muted">{{ trans('messages.lys.price_reset_daily_rate') }}</span>
      </div>
    </div>
    

<div class="panel">
  <div class="map-canvas" id="map" style="position: relative; background-color: rgb(229, 227, 223); overflow: hidden;">
  </div>
  <i class="verify-map-pin map-pin unset"></i>

  <!-- <div class="btn-group map-zoom-controls js-map-zoom-controls">
    <button class="btn icon icon-minus js-zoom-out"></button>
    <button class="btn icon icon-add js-zoom-in"></button>
  </div> -->

  <div class="panel-body">
    <p id="error_invalid_pin" class="hide icon-rausch">
    {{ trans('messages.lys.moved_invalid_destination') }}
    </p>
  <address class="">
  <span class="address-line">{{ $result->address_line_1 }} {{ ($result->address_line_2) ? '/ '.$result->address_line_2 : '' }}</span>
  <span class="address-line">{{ $result->city }} {{ $result->state }}</span>
  <span class="address-line">{{ $result->postal_code }}</span>
  <span class="address-line">{{ $result->country_name }}</span>
</address>
<a data-event-name="edit_address_click" class="js-edit-address-link" id="js-edit-address" href="javascript:;">
  {{ trans('messages.lys.edit_address') }}
</a>

  </div>
</div>


  </div>
  <div class="panel-footer">
    
    <div class="force-oneline">
      
        <button class="btn js-adjust-pin-location js-secondary-btn hide">
  {{ trans('messages.lys.adjust_pin_location') }}
</button>

      
      <button id="js-next-btn3" class="btn btn-primary js-next-btn" ng-disabled="location_found == false">{{ trans('messages.lys.finish') }}</button>
    </div>
  </div>
</div>
<input type="hidden" id="address_line_1" value="{{ $result->address_line_1 }}">
<input type="hidden" id="address_line_2" value="{{ $result->address_line_2 }}">
<input type="hidden" id="city" value="{{ $result->city }}">
<input type="hidden" id="state" value="{{ $result->state }}">
<input type="hidden" id="postal_code" value="{{ $result->postal_code }}">
<input type="hidden" id="country" value="{{ $result->country }}">
<input type="hidden" ng-model="latitude2" id="latitude2" ng-value="{{ $result->latitude }}">
<input type="hidden" ng-model="longitude2" id="longitude2" ng-value="{{ $result->longitude }}">