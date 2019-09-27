<div id="js-manage-listing-content-container" class="manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content col-lg-7 col-md-7"><div><div id="calendar-container">
  <div class="calendar-prompt-container">    
<div class="row-space-4">
  <div class="row">    
      <h3 class="col-12">{{ trans('messages.lys.calendar_title') }}</h3>    
  </div>
  <p class="text-muted">{{ trans('messages.lys.calendar_desc') }}</p>
</div>
  <div class="space-4"></div>
</div>
  <div class="calendar-settings-btn-container pull-right post-listed">
    <span class="label-contrast label-new
      hide">{{ trans('messages.lys.new') }}</span>
    <a href="#" id="js-calendar-settings-btn" class="text-normal link-icon">
      <i class="icon icon-cog text-lead"></i>
      <span class="link-icon__text">{{ trans('messages.lys.header') }}</span>
    </a>
  </div>
  <div id="calendar">
  <div class="row" id="wizard-container">
  <div class="wizard-pane row row-table">
  <div class="col-12 col-middle">
    <hr>
    <div class="row row-space-top-6">
      <h4 class="col-6 lang-right">{{ trans('messages.lys.select_an_option') }}:</h4>
      <div class="col-6 text-right section-availability-dates">
        <div style="display: none;" class="js-saving-progress saving-progress">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

      </div>
    </div>

    <ul class="list-unstyled row text-center row-space-top-1 display-flex" ng-init="selected_calendar = '{{ lcfirst($result->calendar_type) }}'">
    
      <li class="availability-option col-4 display-flex">
        <div data-slug="always" class="option-container available-always {{ ($result->calendar_type == 'Always') ? 'selected' : '' }}" id="available-always">
          <div class="calendar-image available-always"></div>
          <p class="row-space-top-4 row-space-1">{{ trans('messages.lys.always_available') }}</p>
          <div class="h6 choice-description row-space-1">{{ trans('messages.lys.always_available_desc') }}</div>
        </div>
      </li>
    <!-- 
      <li class="availability-option col-4 display-flex">
        <div data-slug="sometimes" class="option-container available-sometimes {{ ($result->calendar_type == 'Sometimes') ? 'selected' : '' }}" id="available-sometimes">
          <div class="calendar-image available-sometimes"></div>
          <p class="row-space-top-4 row-space-1">{{ trans('messages.lys.somtimes_available') }}</p>
          <div class="h6 choice-description row-space-1">{{ trans('messages.lys.somtimes_available_desc') }}</div>
        </div>
      </li>
    
      <li class="availability-option col-4 display-flex">
        <div data-slug="onetime" class="option-container available-onetime {{ ($result->calendar_type == 'Onetime') ? 'selected' : '' }}" id="available-onetime">
          <div class="calendar-image available-onetime"></div>
          <p class="row-space-top-4 row-space-1">{{ trans('messages.lys.specific_dates') }}</p>
          <div class="h6 choice-description row-space-1">{{ trans('messages.lys.specific_dates_desc') }}</div>
        </div>
      </li> -->
    
    </ul>
  </div>
</div>
</div>

<div id="calendar-settings-container" class="row-space-6 hide">
  <hr>
  
<div class="js-section">
  <div style="display: none;" class="js-saving-progress saving-progress">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.lys.calendar_settings') }}</h4>

  <div class="row">
    <div class="col-5">
      <div id="min-max-nights-container" class="row"></div>
      <div id="advance-notice-container" class="row row-space-top-3"></div>
    </div>
  </div>
  
</div>

</div>

<div id="calendar-wizard-navigation">
  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  @if($result->status == NULL)
  <div class="col-2 row-space-top-1 next_step">
    
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    
  </div>
  @endif

  @if($result->steps_count != 0)
  <div class="col-10 text-right">
    
      <a data-prevent-default="" href="" class="btn btn-large btn-primary remaining-steps-section-button" id="finish_step">
        {{ trans('messages.lys.finish_remaining_steps') }}
      </a>
    
  </div>
  @endif

   @if($result->status != NULL)
  <div class="col-10 text-right next_step">
  
      <a class="btn btn-large btn-primary next-section-button" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" data-prevent-default="">
        {{ trans('messages.lys.next') }}
      </a>
  </div>
  @endif
  
</div>

</div>
</div>
</div>

<div class="pricing-tips-sidebar-container"></div>
</div></div>
        <div id="js-manage-listing-help" class="manage-listing-help col-lg-4 col-md-4 hide-sm"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel" >
    <div class="help-header-icon-container text-center va-container-h">
      <img width="50" height="50" class="col-center" src="{{ url('images/lightbulb2x.png') }}">
    </div>
    <div class="panel-body">
      <h4 class="text-center">Calendar</h4>
      
  <p>{{ trans('messages.lys.calender_descrip') }}</p>

    </div>
  </div>
</div>


</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>
    <style type="text/css">
    html[lang="ar"] .col-10.text-right{text-align: left;}
      @media (min-width: 767px){
   
  #ajax_container{margin-left:25%;}
  html[lang="ar"] #js-manage-listing-nav{position: fixed;}
  html[lang="ar"] #ajax_container{margin-right:25% !important;margin-left: 0px !important;}
}
@media (min-width: 767px) and (max-width: 1099px){
  #calendar-container, .calendar-month{width: 100% !important; font-size: 14px;}
}
  @media(min-width: 1100px){
  /*#ajax_container{margin-left: 16.66667%;}*/
  html[lang="ar"] #ajax_container{margin-right: 16.66667% !important; margin-left: 0px !important;}
}
    </style>

@push('scripts')
    <script type="text/javascript">
    $('#js-manage-listing-nav').addClass('pos-abs');
  </script>
  @endpush