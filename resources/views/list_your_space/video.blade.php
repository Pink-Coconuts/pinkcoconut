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
    <div class="manage-listing-content-container" id="js-manage-listing-content-container" style="position:relative;">
      <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content col-lg-7 col-md-7" id="js-manage-listing-content"><div>
  
<div class="row-space-4">
  <div class="row">
    
      <h3 class="col-12">{{ trans('messages.lys.video_title') }}</h3>
    
  </div>
  <p class="text-muted">{{ trans('messages.lys.video_desc') }}</p>
</div>

  <hr>
    <form name="overview">
    <div class="js-section" >
      <div class="js-saving-progress saving-progress" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>
      <div class="js-saving-progress icon-rausch error-value-required row-space-top-1" id="video_error" style="display: none;float:right">
  <h5>{{ trans('messages.lys.video_error_msg') }}</h5>
</div>
  
      <div class="row-space-2 clearfix" id="help-panel-video" ng-init="video='{{ $result->video }}'">
        <div class="row row-space-top-2">
          <div class="col-4">
            <label class="label-large">{{ trans('messages.lys.youtube') }}</label>
          </div>
        </div>

        <input type="text" name="video" id='video' value="{!! $result->video !!}" class="input-large" placeholder="{{ trans('messages.lys.youtube') }}" ng-model="video">

      </div>
      <br>
     <div class="row">
      <div class="col-md-12 @if($result->video == '') hide @endif">
      <a class="remove_rooms_video link-reset" id="remove_rooms_video" data-saving="video_saving" href="javascript:void(0);" style="float:right; position: absolute;     top: 47px;     right: 33px;     color: white;  font-size: 23px;   background-color: #f51f24;"><i class="icon icon-trash" ></i></a>
        <iframe src="{{$result->video}}?showinfo=0" style="width:100%; height:250px;" id="rooms_video_preview"  allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>
      </div>
  </div>

    </div>
    </form>
  
</div>
  

  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  <div class="col-2 row-space-top-1 next_step">
    
    @if($result->status == NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    @endif

    @if($result->status != NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    @endif

  </div>
  <div class="col-10 text-right next_step">
    
    @if($result->status == NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
    @endif

    @if($result->status != NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
    @endif
    
  </div>
</div>



</div>
        <div class="manage-listing-help col-lg-4 col-md-4 hide-sm manage-listing-page" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel">
    <div class="help-header-icon-container text-center va-container-h header-icon-light">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.guests_love_video') }}</h4>
      
  <p class="text-center">{{ trans('messages.lys.phone_video_fine') }}</p>

    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>

