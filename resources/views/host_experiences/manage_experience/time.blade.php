<div class="main_bar pull-left padd5 text-black bg-white pos_rel" ng-init="times_array = {{json_encode($times_array)}}">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8 set_tim">{{trans('experiences.manage.set_your_default_time')}}
      </p>
      <p class="h4 line1 space-2 set_timp">{{trans('experiences.manage.time_page_desc')}}
      </p>
      <br>
      <div class="row">
        <div class="col-sm-5 newfive">
          <div class="drp_dwn_cng1 pos_rel dropdown2">
            <i class="icon-chevron-down">
            </i>
            <select class="" name="start_time" id="host_experience_start_time" ng-model="host_experience.start_time" ng-init="host_experience.start_time = host_experience.start_time == null ? '' : host_experience.start_time;">
              <option ng-if="host_experience.start_time == ''" value=''>{{trans('experiences.manage.start_time')}}
              </option>
              <option ng-repeat="(key, time) in times_array" ng-selected="host_experience.start_time == key" ng-if="key < '23:00:00'" value="@{{key}}">@{{time}}
              </option>
            </select>
            <p class="text-danger" ng-show="form_errors.start_time.length">@{{form_errors.start_time[0]}}</p>
          </div>
        </div>
        <div class="col-sm-1 teb">
          <p class="space-top-3 h4 text-center">{{trans('experiences.manage.to')}}
          </p>
        </div>
        <div class="col-sm-5 newfive">
          <div class="drp_dwn_cng1 pos_rel dropdown2">
            <i class="icon-chevron-down">
            </i>
            <select class="" name="end_time" id="host_experience_end_time" ng-model="host_experience.end_time"  ng-init="host_experience.end_time = host_experience.end_time == null ? '' : host_experience.end_time;">
              <option ng-if="host_experience.end_time == ''" value=''>{{trans('experiences.manage.end_time')}}
              </option>
              <option ng-repeat="(key, time) in times_array" ng-selected="host_experience.end_time == key" ng-if="key >= minimum_end_time" value="@{{key}}">@{{time}}
              </option>
            </select>
            <p class="text-danger" ng-show="form_errors.end_time.length">@{{form_errors.end_time[0]}}</p>
          </div>
        </div>
      </div>
      <!-- row end -->
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->