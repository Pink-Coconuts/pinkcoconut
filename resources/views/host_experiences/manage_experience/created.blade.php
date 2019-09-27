<div class="main_bar pull-left padd5">
  <div class="pos_fix2" >
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="clearfix" id="manage_experience_main_content" ng-cloak>
    <div class="col-md-7 bg_chng">
      <p class="h1 bold space-top-8">{{trans('experiences.manage.congrats_you_all_set')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.added_to_waitlist_back_within_weeks_expect_email')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.feel_free_to_edit_make_it_unique')}}
      </p>
      <a class="btn-primary3 space-top-3" href="{{url('host/experiences')}}" >{{trans('experiences.manage.exit')}}
      </a>
      &nbsp;&nbsp;
      <a class="btn-primary4 space-top-3" href="{{url('host/manage_experience/'.$host_experience->id)}}" >{{trans('experiences.manage.edit_submission')}}
      </a>
    </div>
    <div class="pos_fix1 text-black">
      
    </div>
  </div>
</div>
