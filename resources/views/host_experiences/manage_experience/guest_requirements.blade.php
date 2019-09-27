<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.who_can_attend_the_experience')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.guest_requirement_page_desc')}}
      </p>
      <p class="h4 bold line1 space-2 space-top-6">{{trans('experiences.manage.alcohol')}}
      </p>
      <div class="tabl space-top-3">
        <div class="tb_cell ver_top">
          <input type="checkbox" name="guest_requirements[includes_alcohol]" id="guest_requirements_includes_alcohol" class="chekbox1" ng-model="host_experience.guest_requirements.includes_alcohol" ng-true-value="'Yes'" ng-false-value="'No'">
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check1">{{trans('experiences.manage.includes_alcohol_legal_drinking_age_only')}}
          </label>
        </div>
      </div>
      <p class="h4 bold line1 space-1 space-top-6">{{trans('experiences.manage.minimum_age')}}
      </p>
      <small>{{trans('experiences.manage.set_age_limit_for_guests_minors_with_gaurdians')}}
      </small>
      <div class="row space-top-1">
        <div class="col-sm-5 new_rew">
          <div class="drp_dwn_cng1 pos_rel dropdown2">
            <i class="icon-chevron-down">
            </i>
            <div class="seleddiv">
            <select class="" name="host_experience[minimum_age]" id="guest_requirements_minimum_age" ng-model="host_experience.guest_requirements.minimum_age">
              <option ng-if="host_experience.guest_requirements.minimum_age == ''" value="">{{trans('experiences.manage.select_minimum_age')}}
              </option>
              @foreach($host_experience->minimum_age_array as $age)
              <option value="{{$age}}">{{$age}}
              </option>
              @endforeach
            </select>    
            </div>
            
            
            <p class="text-danger" ng-show="form_errors.guest_requirements.minimum_age.length">@{{form_errors.guest_requirements.minimum_age[0]}}</p>        
          </div>
        </div>
      </div>
      <div class="tabl space-top-3">
        <div class="tb_cell ver_top">
          <input type="checkbox" class="chekbox1" name="guest_requirements[allowed_under_2]" id="guest_requirements_allowed_under_2" ng-model="host_experience.guest_requirements.allowed_under_2" ng-true-value="'Yes'" ng-false-value="'No'" >
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check2">{{trans('experiences.manage.parents_can_bring_under_2')}}
          </label>
        </div>
      </div>
      <p class="h4 bold line1 space-1 space-top-8">{{trans('experiences.manage.special_certifications')}}
      </p>        
      <textarea class="input_new1 space-top-3" placeholder="{{trans('experiences.manage.special_certifications_example')}}" rows="3" name="guest_requirements[special_certifications]" id="guest_requirements_special_certifications" ng-model="host_experience.guest_requirements.special_certifications" >
      </textarea>  
      <p class="h4 bold line1 space-1 space-top-7">{{trans('experiences.manage.additional_requirements')}}
      </p>        
      <textarea class="input_new1 space-top-3" placeholder="{{trans('experiences.manage.additional_requirements_example')}}" rows="3" name="guest_requirements[additional_requirements]" id="guest_requirements_additional_requirements" ng-model="host_experience.guest_requirements.additional_requirements" >
      </textarea>         
      <div class="hide">
        <p class="h4 bold line1 space-1 space-top-6">Verified ID
        </p>
        <small>The primary booker has to successfully complete verified ID in order for them and their guests to attend your experience.
        </small>
        <div class="tabl space-top-2">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check3" class="chekbox1">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check3">Require the primary booker to 
              <a href="#" class="text-rausch rm_link"> verify their ID.
              </a>
            </label>
          </div>
        </div>  
      </div>  
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->