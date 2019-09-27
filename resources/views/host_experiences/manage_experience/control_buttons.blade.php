<div class="check_detail1" ng-show="host_experience_steps[step_num].status == 0">
  <button class="btn-primary3 space-top-8 space-8" type="button" ng-class="steps_status[step] ? 'save_next_step' : ''" data-step-num="{{$step_num +1}}" ng-disabled="!steps_status[step] || save_in_progress"> 
    <a href="javascript:void(0)" class="rm_link"> {{trans('experiences.manage.save_and_continue')}}
    </a>
  </button>
</div>
<div class="check_detail1" ng-show="form_modified && host_experience_steps[step_num].status == 1">
  <button class="btn-primary3 space-top-8 space-8" type="button" ng-class="steps_status[step] ? 'save_step' : ''" data-step-num="{{$step_num +1}}" ng-disabled="!steps_status[step] || save_in_progress" > 
    <a href="javascript:void(0)" class="rm_link"> {{trans('experiences.manage.save')}}
    </a>
  </button> &nbsp;
  <button class="btn-primary2 space-top-8 space-8 undo_step" type="button" ng-show="step != 'photos'"> 
    <a href="javascript:void(0)" class="rm_link"> {{trans('experiences.manage.undo')}}
    </a>
  </button>
</div>
<div class="check_detail1-1 " ng-show="!form_modified && host_experience_steps[step_num].status == 1">
  <button class="btn-primary2 space-top-8 space-8 next_step" data-step-num="{{$step_num +1}}" type="button" > 
    <a href="javascript:void(0)" class="rm_link"> {{trans('experiences.manage.next')}}
    </a>
  </button>
</div>