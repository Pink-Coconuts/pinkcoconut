<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">    
    <div class="col-md-7 lg_ipad">
      <p class="h2 bold space-top-8 set_tim">{{trans('experiences.manage.which_language_will_you_host')}}
      </p>
      <p class="h4 line1 space-2 set_timp">{{trans('experiences.manage.language_step_desc')}}
      </p>
      <p class="h3 space-top-4 space-1 nt_bd"> {{trans('experiences.manage.submission_language')}}
      </p>
      <div class="row">
        <div class="col-sm-9 mob_se">
          <div class="drp_dwn_cng1 pos_rel dropdown2 space-top-4">
            <i class="icon-chevron-down">
            </i>
            {!! Form::select('language', $languages, '', ['id' => 'host_experience_language', 'ng-model' => 'host_experience.language', 'class' => 'light', 'placeholder' => trans('experiences.manage.select_a_language')]) !!}
            <p class="text-danger" ng-show="form_errors.language.length">@{{form_errors.language[0]}}</p>
          </div>
        </div>
      </div>   
      <div class="hide">
        <p class="h3 space-top-6 space-1 nt_bd"> {{trans('experiences.manage.fluent_in_more_languages')}}
        </p>
        <p class="h4 line1 cfr">{{trans('experiences.manage.you_have_chance_to_translate')}}
        </p>
        <label class="space-top-4 op_lw">
          <input type="checkbox" name="" checked="" class="chekbox1" disabled=""> 
          <span class="h4"> {{trans('experiences.manage.let_me_know_when_i_translate')}}
          </span>
        </label>
      </div>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1 padd6">
      <p class="h4 space-2 space-top-8 line1 nt_bd">{{trans('experiences.manage.languages_spoken_by_city', ['city' => $host_experience->city_details->name, 'site_name' => SITE_NAME])}}
      </p>
      @foreach($language_spoken_data as $language_data)
      <div class="row space-top-4">
        <div class="col-sm-8">
          <p class="space-1 h5">{{$language_data['name']}}
          </p>
        </div>
        <div class="col-sm-4 text-right">
          <p class="space-1 h5">{{$language_data['percentage']}}%
          </p>
        </div>
      </div>
      <div class="progress_bar">
        <div class="progress_val anim" style="width: {{$language_data['percentage']}}%">
        </div>
      </div>
      @endforeach
      <div ng-init="language_progress_start()"></div>
    </div>
  </div>
</div>
<!--  main_bar end -->