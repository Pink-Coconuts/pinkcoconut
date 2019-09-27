<div class="panel">
  <div class="panel-header">
    <a data-behavior="modal-close" class="modal-close" href="javascript:;"></a>
    <div class="h4 js-address-nav-heading">
      <span ng-hide="location_found">{{ trans('messages.lys.exact_location_not_found') }}</span>
      <span ng-show="location_found">{{ trans('messages.lys.location_found') }}</span>
      <br>
      <small>{{ trans('messages.lys.manually_pin_location') }}</small>
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
    
  <p ng-hide="location_found">{{ trans('messages.lys.couldnot_automatically_find') }}</p>
  <p ng-show="location_found">{{ trans('messages.lys.manually_pin_location_instead') }}</p>

  <address class="">
  <span class="address-line">{{ $result->address_line_1 }} {{ ($result->address_line_2) ? '/ '.$result->address_line_2 : '' }}</span>
  <span class="address-line">{{ $result->city }} {{ $result->state }}</span>
  <span class="address-line">{{ $result->postal_code }}</span>
  <span class="address-line">{{ $result->country_name }}</span>
</address>
<a data-event-name="edit_address_click" class="js-edit-address-link edit-address-link" href="javascript:;">
  {{ trans('messages.lys.edit_address') }}
</a>

  </div>
  <div class="panel-footer">
    <div class="force-oneline">
      <button class="btn js-edit-address-btn" id="js-edit-address">
        {{ trans('messages.lys.edit_address') }}
      </button>
      <button id="js-next-btn2" class="btn btn-primary  js-next-btn">
        {{ trans('messages.lys.pin_on_map') }}
      </button>
    </div>
  </div>
</div>