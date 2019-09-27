<div class="main_bar pull-left padd5">
  <div class="pos_fix2" >
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="clearfix" id="manage_experience_main_content" ng-cloak>
    <div class="col-md-7 bg_chng">
      <p class="h1 bold space-top-8">{{trans('experiences.manage.create_your_experience')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.basics_step_desc', ['site_name' => SITE_NAME])}}
      </p>
      <button class="btn-primary4 space-top-8 space-8 next_step" type="button" data-step-num="{{$step_num +1}}" >
        <a class="rm_link" href="javascript:void(0);">{{trans('experiences.manage.next')}}
        </a>
      </button>
    </div>
    <div class="pos_fix1 text-black">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
