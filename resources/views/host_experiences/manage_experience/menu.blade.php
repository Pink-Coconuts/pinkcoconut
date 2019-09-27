@foreach(@$host_experience_steps_group[""] as $k => $step)
<ul class="list-unstyled text-uppercase bold side_menu space-top-5" ng-class="host_experience_steps[{{@$step['step_num']}}].locked ? 'op_lw' : ''">
  <li ng-if="host_experience_steps[{{@$step['step_num']}}].locked"> 
    <span class="icon-lock">
    </span> {{@$step['name']}}
  </li>
  <li class="op_lw1 refresh_main_content" data-step-num="{{$step['step_num']}}" ng-if="!host_experience_steps[{{@$step['step_num']}}].locked" ng-class="{{@$step['step_num']}} == step_num ? 'active' : ''">
    <a href="javascript:void(0)" class="rm_link" ng-if="host_experience_steps[{{@$step['step_num']}}].step == 'experience_page' && host_experience.title" style="text-transform: uppercase"> @{{host_experience.title}}
    </a>
    <a href="javascript:void(0)" class="rm_link" ng-if="host_experience_steps[{{@$step['step_num']}}].step != 'experience_page' || !host_experience.title"> {{@$step['name']}}
    </a>
  </li>
  @if(@$host_experience_steps_group[$step['step']])
  @foreach(@$host_experience_steps_group[$step['step']] as $sub_step)
  <li class="op_lw1 refresh_main_content" data-step-num="{{$sub_step['step_num']}}" ng-if="!host_experience_steps[{{@$step['step_num']}}].locked" ng-class="({{@$sub_step['step_num']}} == step_num && host_experience.is_reviewed) ? 'active' : ''">
    <a href="javascript:void(0)" class="rm_link"> {{$sub_step['name']}}
    </a> 
    <i class="icon icon2-tick pull-right text-rausch" ng-if="steps_status['{{@$sub_step['step']}}'] == true && host_experience.status == NULL">
    </i>
    <i class="icon icon-alert pull-right text-beach" ng-if="steps_status['{{@$sub_step['step']}}'] == false && host_experience.status != NULL">
    </i>
  </li>
  @endforeach
  @endif
</ul>
@endforeach
