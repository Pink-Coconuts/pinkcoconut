<div id="js-manage-listing-content-container" class="manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content col-lg-7 col-md-7 lang-chang-label">

        <div>
        <div>
        <div class="space-4">
        <h3>{{ trans('messages.lys.amenities_title') }}</h3>
        <p class="text-muted">{{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}</p>
        </div>
        <hr>
        </div>


        @foreach($amenities_type as $row_type)

        <div>
        <div class="js-section">
        <div style="display:none;" class="js-saving-progress saving-progress {{ $row_type->id }}">
        <h5>{{ trans('messages.lys.saving') }}...</h5>
        </div>

        <h4>{{ $row_type->name }}</h4>

        @if($row_type->description != '')
        <p class="text-muted">{{ $row_type->description }}</p>
        @endif

        <ul class="list-unstyled amenity-list">
        @foreach($amenities as $row_amenities)

        @if($row_amenities->type_id == $row_type->id)
        <li>
        <!-- <span>&nbsp;&nbsp;</span> -->
        <div class="come">
        <label class="label-large label-inline amenity-label pos-rel">
        <input type="checkbox" value="{{ $row_amenities->id }}" name="amenities" data-saving="{{ $row_type->id }}" {{ in_array($row_amenities->id, $prev_amenities) ? 'checked' : '' }}>
        <span>{{ $row_amenities->name }}</span>
        
        <!-- <span>&nbsp;</span> -->
              
        </label>
        @if($row_amenities->description != '')
         <i  class="icon icon-question" id="amenity-tooltip-{{ $row_amenities->id }}" data-id="{{ $row_amenities->id }}" ></i>
       </div>
        <div class="amit">
        
      <div class="tooltip-amenity tooltip-left-middle" id="ame-tooltip-{{ $row_amenities->id }}" role="tooltip" data-sticky="true" aria-hidden="true">
         
            <div class="tool_tip_prop">{{ $row_amenities->description }}</div>
      </div>
        
        </div>
        @endif
        </li>
        @endif

        @endforeach

        </ul>

        <hr class="space-top-4 space-4">
        </div>
        </div>
        @endforeach

        <div class="not-post-listed row space-top-6 next_step">
        <div class="col-2 space-top-1 lang-chang-label">
        <a data-prevent-default="true" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
        </div>
        <div class="col-10 text-right next_step">
        <a data-prevent-default="true" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="btn btn-large btn-primary next-section-button">{{ trans('messages.lys.next') }}</a>
        </div>
        </div>
        </div>
        </div>

         <div class="lang-help manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel">
    <div class="help-header-icon-container text-center va-container-h header-icon-light">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.amenities') }}</h4>
      
  <p>{{ trans('messages.lys.edit_amenities_desc1') }}</p>
 <p>{{ trans('messages.lys.edit_amenities_desc2') }}</p>
    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>

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