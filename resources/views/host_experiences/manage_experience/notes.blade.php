<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.what_else_guests_know')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.notes_page_desc')}}
      </p>
      <textarea class="input_new1 space-top-6" placeholder="{{trans('experiences.manage.notes_example_placeholder')}}" rows="3" id="host_experience.notes" ng-model="host_experience.notes" ng-change="need_notes_change();" >
      </textarea> 
      <p class="focus_show space-top-2" ng-class="steps_status[step] ? '' : 'text-warning'">@{{character_length_validation(1, 200, host_experience.notes.length)}}
      </p>
      <p class="text-danger" ng-show="form_errors.notes.length">@{{form_errors.notes[0]}}</p>
      <div id="need_notes_part" ng-show="host_experience.need_notes == null">
        <p class="bold h4 space-top-4 space-2">{{trans('experiences.manage.is_there_nothing_else_guests_should_know')}}
        </p>
        <label class="space-top-4">
          <input type="checkbox" name="need_notes" ng-model="host_experience.need_notes" ng-true-value="'No'" ng-false-value="false" ng-checked="host_experience.need_notes == 'No'"> 
          <span class="h4"> {{trans('experiences.manage.i_have_no_additional_notes_guests')}}
          </span>
          <p class="text-danger" ng-show="form_errors.need_notes.length">@{{form_errors.need_notes[0]}}
          </p>
        </label>
      </div>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->