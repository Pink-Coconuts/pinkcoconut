<div class="main_bar pull-left padd5">
  <div class="pos_fix2" >
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="clearfix" id="manage_experience_main_content" ng-cloak>
    <div class="col-md-7 fini bg_chng">
      <p class="h1 bold space-top-8">{{trans('experiences.manage.finishing_thoughts')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.just_a_few_more_things_you_ready_to_submit')}}
      </p>
      <button class="btn-primary3 space-top-8 space-8 next_step" type="button" data-step-num="{{$step_num +1}}" >
        <a class="rm_link" href="javascript:void(0);">{{trans('experiences.manage.next')}}
        </a>
      </button>
    </div>
    <div class="pos_fix1 text-black">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
