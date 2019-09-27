<div class="phone_eff" style="background-image: url('{{url('images/host_experiences/mb_cover.png')}}');">
  <div class="over_scroll1">
    <a href="javascript:void(0)" target="_blank" class="rm_link refresh_main_content_step" data-step="photos" >
      <div class="bg2 text-center bg_cover" ng-style="get_mobile_photo_elem_style()">      
      </div>
    </a>
    <p class="h3 bold padd7 space-0 hover1 refresh_main_content_step" id="mobile_host_experience_title" data-step="title" ng-init="experience_locale = '{{trans('experiences.home.experience')}}'">@{{host_experience.title ? host_experience.title : experience_locale}}
    </p>
    <a href="javascript:void(0)" class="h4 padd7 space-0 hover1 rm_link dis_blk refresh_main_content_step" id="mobile_host_experience_tagline" data-step="tagline">{{$host_experience->city_details->name}} &sdot; 
      <span class="text-muted" ng-init="tagline_placeholder = '{{trans('experiences.manage.your_tagline_goes_here')}}'">@{{host_experience.tagline ? host_experience.tagline : tagline_placeholder}}
      </span>
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <div class="tabl padd7">
      <div class="tb_cell col_sm_8">
        <p class="h4 space-top-3 space-3">{{@$host_experience->category_details->name}} {{trans('experiences.manage.experience_hosted_by')}}
          <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank" class="rm_link text-rausch">{{$host_experience->user->first_name}}
          </a>
        </p>
      </div>
      <div class="tb_cell col_sm_4">
        <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank" class="rm_link ">
          <div class="prof2 pull-right" style="background-image: url('{{$host_experience->user->profile_picture->header_src}}');">
          </div>
        </a>
      </div>
    </div>
    <p class="space-top-3 padd7 space-3">
      <span class="icon icon2-clock h4">
      </span>&nbsp;&nbsp; 
      <span class="h4">@{{total_hours()}} {{trans('experiences.manage.hours_total')}}
      </span>
    </p>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link refresh_main_content_step" data-step="what_will_do" >
      <p class="space-top-3 padd7 space-1 h4">{{trans('experiences.manage.what_will_do')}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted" style="font-size: 15px;" ng-init="what_will_do_placeholder = '{{trans('experiences.manage.give_an_overview_description_of_what_will_do')}}'; more_link_status[0] = false;" ng-bind-html="text_more_content((host_experience.what_will_do ? host_experience.what_will_do : what_will_do_placeholder), 200, 300, 0)">
      </p>
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link refresh_main_content_step" data-step="what_will_provide" >
      <p class="space-top-3 padd7 space-1 h4">{{trans('experiences.manage.what_will_provide')}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted" ng-if="!steps_status['what_will_provide']">{{trans('experiences.manage.let_your_guests_know_you_include_anything')}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted" ng-repeat="provide in host_experience_provides" ng-if="steps_status['what_will_provide'] && provide.name.length > 0 && provide.host_experience_provide_item_id > 0">
        @{{provide.name}}&nbsp; <img src="@{{get_provide_image(provide.host_experience_provide_item_id)}}" class="provide_icon"> <br>
        @{{provide.additional_details}} <br ng-if="provide.additional_details">
      </p>
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link refresh_main_content_step" data-step="where_will_be">
      <p class="space-top-3 padd7 space-1 h4">{{trans('experiences.manage.where_will_be')}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted" ng-init="where_will_be_placeholder = '{{trans('experiences.manage.tell_your_guests_where_you_taking_them')}}' ">@{{host_experience.where_will_be ? host_experience.where_will_be : where_will_be_placeholder}}
      </p>
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link refresh_main_content_step" data-step="notes">
      <p class="space-top-3 padd7 space-1 h4">{{trans('experiences.manage.notes')}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted" ng-init="notes_placeholder = '{{trans('experiences.manage.is_there_anything_guest_to_know_before_booking')}}' " ng-bind-html="text_more_content((host_experience.notes ? host_experience.notes : notes_placeholder), 200, 300, 0)">
      </p>
    </a>
    <a href="javascript:void(0)" class="mobile_location_area rm_link refresh_main_content_step" data-step="where_will_meet" role="button" tabindex="0">
      <div class="bg-white mobile_location_title">
        <p class="space-top-2 padd7 space-1 h4">{{trans('experiences.manage.where_will_meet')}}
        </p>
        <p class="space-top-1 padd7 space-2 text-muted">@{{host_experience.host_experience_location.location_name ? host_experience.host_experience_location.location_name+' - ': ''}} @{{host_experience.host_experience_location.city != '' ? host_experience.host_experience_location.city : host_experience.city_details.name}}
        </p>
      </div>
      <div id="host_experience_location_mobile_map" style="width: 100%; height: 300px;"></div>
    </a>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link refresh_main_content_step" data-step="about_you" >
      <p class="space-top-3 padd7 space-1 h4">{{trans('experiences.manage.about_your_host')}}, {{$host_experience->user->first_name}}
      </p>
      <p class="space-top-1 padd7 space-3 text-muted">@{{host_experience.about_you}}
      </p>
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link padd7 refresh_main_content_step" data-step="group_size" ng-show="host_experience.number_of_guests">
      <p class="space-0 text-muted" ng-init="group_size_text = '{{trans('experiences.manage.group_size_upto_guests')}}'">@{{ locale_string(group_size_text, {number_of_guests : (host_experience.number_of_guests ? host_experience.number_of_guests : 0)}) }}
      </p>    
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <a href="javascript:void(0)" class="hover1 dis_blk rm_link padd7 refresh_main_content_step" data-step="guest_requirements" ng-show="host_experience.guest_requirements.minimum_age">
      <p class="space-0 text-muted">{{trans('experiences.manage.who_can_come')}}
      </p>    
      <p class="space-0 text-muted" ng-init="who_can_come_text = '{{trans('experiences.details.guest_ages_age_and_up_can_attend')}}'" >@{{ locale_string(who_can_come_text, {count : (host_experience.guest_requirements.minimum_age ? host_experience.guest_requirements.minimum_age : 0)}) }}
      </p> 
    </a>
    <div class="padd7">
      <hr class="mr0">
    </div>
    <div class="padd5">
    </div>
    <div class="padd7">
    </div>
  </div>
</div>
