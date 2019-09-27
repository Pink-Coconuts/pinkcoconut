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
<div id="js-manage-listing-content-container" class="manage-listing-content-container" ng-cloak>
      <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content col-lg-7 col-md-7"><div id="photos">
<div class="row-space-4">
  <div class="row">
      <h3 class="col-12">{{ trans('messages.lys.photos_title') }}</h3>
  </div>
  <p class="text-muted">{{ trans('messages.lys.photos_desc') }}</p>
</div>

<div id="js-photos-grid" class="photo-encourage">
<div class="row row-table">
  <div class="col-md-6">
<div class="add-photos-button">
  <input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos" accept="image/*">
  <button id="photo-uploader" class="btn btn-large row-space-2" style="position: relative; z-index: 0;">
    <i class="icon icon-upload ico_left"></i> {{ trans_choice('messages.lys.add_photo',2) }}
  </button>
</div>
  </div>

<div class="h4 text-right text-muted" id="photo_count"  ng-cloak ng-show="photos_list.length > 0" {{ ($rooms_status->photos == 0) ? 'style="display:none;"' : ''}}>
<small>@{{ photos_list.length }} {{ trans_choice('messages.lys.photo',1) }}<span ng-show="photos_list.length > 1">s</span></small>
</div>
</div>
</div>

<hr>

<div class="row">
  <div id="js-first-photo-text" class="col-12 row-space-2 h5 ">
    {{ trans('messages.lys.set_featured_image') }}
  </div>
</div>

<ul id="js-photo-grid" class="row list-unstyled sortable">

<li ng-repeat="item in photos_list" class="col-lg-4 col-md-6 row-space-4" data-id="@{{ item.id }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
    <div class="panel photo-item">
      <!-- <div class="first-photo-ribbon"><i class="icon icon-star text-center"></i></div> -->
      <div class="first-photo-ribbon" style="z-index:9; background:none; margin:0; padding:0; " ><input type="radio" name="featured_image" ng-click="featured_image($index, item.id);" ng-checked="(item.featured == 'Yes') ? true : false"></div>

      <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ item.id }}"></div>
      <a class="media-photo media-photo-block text-center photo-size" href="#">
      <img alt="" class="img-responsive-height" ng-src="@{{ item.name }}" >
      </a>
      <button data-photo-id="@{{ item.id }}" ng-click="delete_photo(item,item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-photo-btn overlay-btn js-delete-photo-btn">
        <i class="icon icon-trash"></i>
      </button>
      <div class="panel-body panel-condensed">
        <textarea name="@{{ item.id }}" ng-model="item.highlights" ng-keyup="keyup_highlights(item.id, item.highlights)" rows="3" placeholder="{{ trans('messages.lys.highlights_photo') }}" class="input-large highlights" tabindex="1"></textarea>
      </div>
    </div>
</li>

<!-- <li id="js-photo-grid-placeholder" class="col-md-6 col-lg-6 row-space-4" draggable="true">
  
  <div id="photo-placeholder" class="panel photo-item add-photos empty-photo-frame">
    <div class="empty-photo-image">
      <i class="icon icon-picture icon-light-gray icon-size-3"></i>
    </div>
    <p class="row-space-top-4 row-space-4 text-center text-rausch">
      <a data-prevent-default="" id="add-photo-text" href="#" style="text-decoration: none;">+{{ trans_choice('messages.lys.add_photo',1) }}</a>
    </p>
  </div>
</li> -->
<input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">

</ul>

<hr>

<div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  <div class="col-2 row-space-top-1 next_step">
    
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    
  </div>
  <div class="col-10 text-right next_step">
    
    @if($result->status == NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
    @endif

    @if($result->status != NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
    @endif
    
  </div>
</div>

</div></div>
        <div id="js-manage-listing-help" class="manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page">
  <div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel" >
    <div class="help-header-icon-container text-center va-container-h header-icon-light">
      <img class="col-center" src="{{ url('images/lightbulb2x.png') }}">
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.guests_love_photos') }}</h4>
      
    <p class="text-center no-margin-bottom">{{ trans('messages.lys.include_well_lit_photos') }}</p>
    <p class="text-center">{{ trans('messages.lys.phone_photos_find') }}</p>
  
    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>