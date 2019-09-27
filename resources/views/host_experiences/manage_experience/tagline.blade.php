<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.write_a_tagline')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.tagline_page_desc')}}
      </p>
      <a href="#" class="rm_link text-rausch h4 pop_link hide" data-id="popup2"> 
        <span class="icon icon2-light-bulb h3">
        </span> {{trans('experiences.manage.tips_and_examples')}}
      </a>
      <br>
      <input type="text" name="tagline" class="input_new1 space-top-6" id="host_experience_tagline" placeholder="{{trans('experiences.manage.write_your_tagline_here')}}" ng-model="host_experience.tagline" />
      <p class="focus_show space-top-2" ng-class="steps_status[step] ? '' : 'text-warning'">@{{character_length_validation(1, 60, host_experience.tagline.length)}}
      </p>
      <p class="text-danger" ng-show="form_errors.tagline.length">@{{form_errors.tagline[0]}}</p>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->