<div class="main_bar pull-left padd5">
  <div class="pos_fix2" >
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="clearfix" id="manage_experience_main_content" ng-cloak>
    <div class="col-md-7 bg_chng">
      <p class="h1 bold space-top-8">{{trans('experiences.manage.review_experience_last_time')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.to_qualify_you_re_responsible_please_review', ['site_name' => SITE_NAME])}}
      </p>
      <div class="tabl space-top-4">
        <div class="tb_cell ver_top">
          <input type="checkbox" name="quality_standards_reviewed" id="check1" class="chekbox1" id="host_experience_quality_standards_reviewed" ng-model="host_experience.quality_standards_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check1"> {!! trans('experiences.manage.you_meet_site_quality_standards', ['quality_standards_link' => '<a target="_blank" href="'.url('quality_standards').'" class="rm_link under_line"> '.trans('experiences.manage.quality_standards').'</a>', 'site_name' => SITE_NAME]) !!}
          </label>
        </div>
      </div>
      <div class="tabl space-top-3">
        <div class="tb_cell ver_top">
          <input type="checkbox" name="local_laws_reviewed" id="check2" class="chekbox1 " id="host_experience_local_laws_reviewed" ng-model="host_experience.local_laws_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check2"> {!! trans('experiences.manage.compiles_with_local_laws_lear_more_read', ['read_link' => '<a href="javascript:void(0)" class="rm_link under_line"> '.trans('experiences.manage.read').'.</a>']) !!}
          </label>
        </div>
      </div>
      <div class="tabl space-top-3">
        <div class="tb_cell ver_top">
          <input type="checkbox" name="terms_service_reviewed" id="check3" class="chekbox1 " id="host_experience_terms_service_reviewed" ng-model="host_experience.terms_service_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check3"> {!! trans('experiences.manage.agree_site_terms_and_condition', ['terms_link' => '<a target="_blank" href="'.url('terms_of_service').'" class="rm_link under_line"> '.trans('experiences.manage.site_additional_terms_and_conditions', ['site_name' => SITE_NAME]).'.</a>']) !!}
          </label>
        </div>
      </div>
      <a href="javascript:void(0)" class="rm_link">
        <button class="btn-primary3 space-top-3 space-8 save_next_step" data-step-num="@{{step_num-0+1}}" type="button" ng-disabled="host_experience.quality_standards_reviewed =='No' || host_experience.local_laws_reviewed == 'No' || host_experience.terms_service_reviewed == 'No' ">{{trans('experiences.manage.submit')}}
        </button>
      </a>
    </div>
    <div class="pos_fix1 text-black">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
