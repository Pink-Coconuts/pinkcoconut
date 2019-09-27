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
    </style>
<div class="manage-listing-content-container" id="js-manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content no-top-padding col-lg-7 col-md-7" id="js-manage-listing-content"><div>
  
    <div class="row row-space-4 language-tabs-container">
      <div class="col-9">
        
      </div>
    </div>
  
<div class="row-space-4">
  <div class="row">
    
      <h3 class="col-12">{{ trans('messages.lys.details_title') }}</h3>
    
  </div>
  <p class="text-muted">{{ trans('messages.lys.details_desc') }}</p>
</div>

  <hr>

<div class="js-section" id="js-section-details">
  <div class="js-saving-progress saving-progress help-panel-saving" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.lys.the_trip') }}</h4>

    <div class="row-space-2" id="help-panel-space">
      <label class="label-large">{{ trans('messages.lys.the_space') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="space" rows="4" placeholder="{{ trans('messages.lys.space_placeholder') }}">{{ $result->rooms_description->space }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-access">
      <label class="label-large">{{ trans('messages.lys.guest_access') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="access" rows="4" placeholder="{{ trans('messages.lys.guest_access_placeholder') }}">{{ $result->rooms_description->access }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-interaction">
      <label class="label-large">{{ trans('messages.lys.interaction_with_guests') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="interaction" rows="4" placeholder="{{ trans('messages.lys.interaction_with_guests_placeholder') }}">{{ $result->rooms_description->interaction }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-notes">
      <label class="label-large">{{ trans('messages.lys.other_things_note') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="notes" rows="4" placeholder="{{ trans('messages.lys.other_things_note_placeholder') }}">{{ $result->rooms_description->notes }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-house-rules">
      <label class="label-large">{{ trans('messages.lys.house_rules') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="house_rules" rows="4" placeholder="{{ trans('messages.lys.house_rules_placeholder') }}">{{ $result->rooms_description->house_rules }}</textarea>
    </div>

</div>

  <hr class="row-space-top-6 row-space-5">

<div class="js-section">
  <div class="js-saving-progress saving-progress help-panel-neigh-saving" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.lys.the_neighborhood') }}</h4>

    <div class="row-space-2" id="help-panel-neighborhood">
      <label class="label-large">{{ trans('messages.lys.overview') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="neighborhood_overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}">{{ $result->rooms_description->neighborhood_overview }}</textarea>
    </div>
    <div id="help-panel-transit">
      <label class="label-large">{{ trans('messages.lys.getting_around') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="transit" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}">{{ $result->rooms_description->transit }}</textarea>
    </div>
  
</div>

  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  <div class="col-2 row-space-top-1 next_step">
  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
  </div>
 <div class="col-10 text-right next_step">
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/terms') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
  </div>
</div>

</div></div>
        <div class="manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel">
    <div class="help-header-icon-container text-center va-container-h header-icon-light">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.guest_access') }}</h4>
      
  <p>{{ trans('messages.lys.guest_access_desc') }}</p>

    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>