<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.mention_where_you_will_be')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.where_will_be_page_desc')}}
      </p>
      <a href="#" class="rm_link text-rausch h4 pop_link hide" data-id="popup1"> 
        <span class="icon icon2-light-bulb h3">
        </span> {{trans('experiences.manage.tips_and_examples')}}
      </a>
      <br>
      <textarea class="input_new1 space-top-6" placeholder="{{trans('experiences.manage.where_will_be')}}" rows="5" id="host_experience.where_will_be" ng-model="host_experience.where_will_be" >
      </textarea> 
      <p class="focus_show space-top-2" ng-class="steps_status[step] ? '' : 'text-warning'">@{{character_length_validation(100, 450, host_experience.where_will_be.length)}}
      </p>
      <p class="text-danger" ng-show="form_errors.where_will_be.length">@{{form_errors.where_will_be[0]}}</p>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->