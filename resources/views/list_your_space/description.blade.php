    <div class="manage-listing-content-container" id="js-manage-listing-content-container" style="position:relative;">
      <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content col-lg-7 col-md-7" id="js-manage-listing-content">

  <div class="row space-4 language-tabs-container" >
      <div class="col-md-9 col-sm-9 description_heading">
        
          <ul class="tabs multiple-description-tabs pull-left tab_adj" id="multiple_description" role="tablist">
  
  <li style="display:none;" class="tab-pager prev-tab-page" role="tab">
    <a href="#" class="tab-item">
      <i class="icon icon-arrow-left"></i>
    </a>
  </li>
  <li style="display:none;" class="tab-pager next-tab-page" role="tab">
    <a href="#" class="tab-item">
      <i class="icon icon-arrow-right"></i>
    </a>
  </li>
  <input type="hidden" id="current_tab_code" value="en" >
  <li>
    <a href="javascript:void(0);" class="tab-item" role="tab" id="en" aria-controls="tab-pane-0"  aria-selected="true" ng-click="getdescription('en')">
    English
    </a>
  </li>

  <li ng-repeat="lan_row in lan_description">  
    <a href="javascript:void(0);" class="tab-item" role="tab" id="@{{ lan_row.lang_code }}" aria-controls="tab-pane-0"  aria-selected="false" ng-click="getdescription(lan_row.lang_code)" ng-cloak>
      @{{ lan_row.language.name }}
    </a>
  </li>
  
  
  
</ul>
      </div>
      
      <div class="col-md-3 col-sm-3 add-language-container">
        <a href="javascript:void(0);" class="add-first-language" id="add_language" title="{{trans('messages.lys.write_title_and_description')}}">
          <i class="icon icon-add"></i> {{trans('messages.lys.add_language')}}
        </a>

        <div id="delete_language" style="display:none">
        <a href="javascript:void(0);"  class="remove-locale">
          <h3><i class="icon icon-trash icon-rausch"></i></h3>
        </a>
      </div>

      </div>
    </div>

<div class="description_form">
<div class="row-space-4">
   <h3>{{ trans('messages.lys.amenities_title') }}</h3>   
  <p class="text-muted">{{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}</p>
</div>

  <hr>
    <form name="overview">
    <div class="js-section" ng-init='name="{{ @$result->name_original }}";summary="{{ @$result->summary_original }}";
     space="{{ @$result->rooms_description->space }}"; access="{{ @$result->rooms_description->access }}";                   interaction="{{ @$result->rooms_description->interaction }}" ; other_notes="{{ @$result->rooms_description->notes }}";        house_rules="{{ @$result->rooms_description->house_rules }}" ;neighborhood_overview="{{ @$result->rooms_description->neighborhood_overview }}" ; transit="{{ @$result->rooms_description->transit }}"'>

  
      <div class="js-saving-progress saving-progress" style="display: none;">
       


  <h5>{{ trans('messages.lys.saving') }}...</h5>

</div>
  
      <div class="row-space-2 clearfix" id="help-panel-name">
        <div class="row row-space-top-2">
          <div class="col-4">
            <label class="label-large">{{ trans('messages.lys.listing_name') }}</label>
          </div>
          <div class="col-8">
            <div id="js-name-count" class="row-space-top-1 h6 label-large text-right">
              <span ng-bind="35 - name.length">35</span> {{ trans('messages.lys.characters_left') }}
            </div>
          </div>
        </div>

        <input type="text" name="name" value="{{ @$result->name }}" class="overview-title input-large name_required" placeholder="{{ trans('messages.lys.name_placeholder') }}" maxlength="35" ng-model="name">
        <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

        <p class="hide icon-rausch error-value-required row-space-top-1 name_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

      </div>

      <div id="help-summary">
        <div class="row">
          <div class="col-4 text_heading">
            <label class="label-large">{{ trans('messages.lys.summary') }}</label>
          </div>
          <div class="col-8 text_sub_heading">
            <div id="js-summary-count" class="row-space-top-1 h6 label-large text-right">
              <span ng-bind="500 - summary.length">500</span> {{ trans('messages.lys.characters_left') }}
            </div>
          </div>
        </div>

        <textarea class="overview-summary input-large summary_required" name="summary" rows="6" placeholder="{{ trans('messages.lys.summary_placeholder') }}" maxlength="500" ng-model="summary">{{ @$result->summary }}</textarea>
      </div>

      <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

      <p class="hide icon-rausch error-value-required row-space-top-1 summary_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

    </div>
    </form>
</div>

    <div class="row text-center" id="add_language_des" style="display: none;">
  <div class="col-10 col-center">
    <i class="icon icon-globe icon-size-3 icon-rausch space-top-3"></i>
    <h3>{{trans('messages.lys.write_description_other_language')}}</h3>
    <p class="text-lead text-muted space-5">
      {{trans('messages.lys.site_provide_your_own_version', ['site_name' => $site_name])}}
    </p>
    <div class="row row-table">
      <div class="col-offset-1 col-7 col-middle">
        <div class="select select-large select-block">
          <select id="language-select">
            <option disabled="" selected="">{{trans('messages.footer.choose_language')}}...</option>
            
              <option value="@{{ lan_row.value }}" ng-repeat="lan_row in all_language" >@{{ lan_row.name }}</option>
             
          </select>
        </div>
      </div>
      <div class="col-3 col-middle">
        <button class="btn btn-large " disabled id="write-description-button" ng-click="addlanguageRow()">
          <i class="icon icon-add float-none"></i> {{trans('messages.lys.add')}}
        </button>
      </div>
    </div>
  </div>
</div>
     <p class="row-space-top-6 not-post-listed write_more_p">
        {{ trans('messages.lys.you_can_add_more') }} <a href="javascript:void(0);" id="js-write-more">{{ trans('messages.lys.details') }}</a> {{ trans('messages.lys.tell_travelers_about_your_space') }}
      </p>

        <hr class="more_details_hr" style="display:none;">

    <div class="js-section" id="js-section-details" style="display:none;">
      <div class="js-saving-progress saving-progress help-panel-saving" style="display: none;">
      <h5>{{ trans('messages.lys.saving') }}...</h5>
    </div>

      <h4>{{ trans('messages.lys.the_trip') }}</h4>

        <div class="row-space-2" id="help-panel-space">
          <label class="label-large">{{ trans('messages.lys.the_space') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="space" rows="4" ng-model="space" placeholder="{{ trans('messages.lys.space_placeholder') }}">{{ @$result->rooms_description->space }}</textarea>
        </div>
        <div class="row-space-2" id="help-panel-access">
          <label class="label-large">{{ trans('messages.lys.guest_access') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="access" ng-model="access" rows="4" placeholder="{{ trans('messages.lys.guest_access_placeholder') }}">{{ @$result->rooms_description->access }}</textarea>
        </div>
        <div class="row-space-2" id="help-panel-interaction">
          <label class="label-large">{{ trans('messages.lys.interaction_with_guests') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="interaction" ng-model="interaction" rows="4" placeholder="{{ trans('messages.lys.interaction_with_guests_placeholder') }}">{{ @$result->rooms_description->interaction }}</textarea>
        </div>
        <div class="row-space-2" id="help-panel-notes" >
    
          <label class="label-large">{{ trans('messages.lys.other_things_note') }}</label>
         
          <textarea class="input-large textarea-resize-vertical" name="notes" ng-model="other_notes" rows="4" placeholder="{{ trans('messages.lys.other_things_note_placeholder') }}">{{ @$result->rooms_description->notes }}</textarea>
        </div>
        <div class="row-space-2" id="help-panel-house-rules">
          <label class="label-large">{{ trans('messages.lys.house_rules') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="house_rules" ng-model="house_rules" rows="4" placeholder="{{ trans('messages.lys.house_rules_placeholder') }}">{{ @$result->rooms_description->house_rules }}</textarea>
        </div>

    </div>

      <hr class="row-space-top-6 row-space-5 more_details_hr" style="display:none;">

    <div class="js-section" id="js-section-details_2" style="display:none;">
      <div class="js-saving-progress saving-progress help-panel-neigh-saving" style="display: none;">
      <h5>{{ trans('messages.lys.saving') }}...</h5>
    </div>

      <h4>{{ trans('messages.lys.the_neighborhood') }}</h4>

        <div class="row-space-2" id="help-panel-neighborhood">
          <label class="label-large">{{ trans('messages.lys.overview') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="neighborhood_overview" ng-model="neighborhood_overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}">{{ @$result->rooms_description->neighborhood_overview }}</textarea>
        </div>
        <div id="help-panel-transit">
          <label class="label-large">{{ trans('messages.lys.getting_around') }}</label>
          <textarea class="input-large textarea-resize-vertical" name="transit" ng-model="transit" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}">{{ @$result->rooms_description->transit }}</textarea>
        </div>
      
    </div>
      

      <div class="not-post-listed row row-space-top-6 progress-buttons">
      <div class="col-12">
        <div class="separator"></div>
      </div>
      <div class="col-2 row-space-top-1 next_step">
        
          <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/basics') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
        
      </div>
      <div class="col-10 text-right next_step">
        
        
          <a class="btn btn-large btn-primary next-section-button" href="{{ url('manage-listing/'.$room_id.'/location') }}" data-prevent-default="">
            {{ trans('messages.lys.next') }}
          </a>
        
      </div>
    </div>
    

    </div>
     <div class="manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
      <div class="panel manage-listing-help-panel">
        <div class="help-header-icon-container text-center va-container-h header-icon-light">
          {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center', 'width' => '50', 'height' => '50']) !!}
        </div>
        <div class="panel-body">
          <h4 class="text-center">{{ trans('messages.lys.listing_name') }}</h4>
          
      <p>{{ trans('messages.lys.listing_name_desc') }}</p>
      <p>{{ trans('messages.lys.example_name') }}</p>

        </div>
      </div>
    </div>

    </div>
  <div class="manage-listing-content-background"></div>
</div>
</div>