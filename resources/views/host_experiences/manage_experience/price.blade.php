<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8 space-0">{{trans('experiences.manage.set_price_per_guest')}}
      </p>
      <p class="h4 line1 space-2">{!! trans('experiences.manage.price_is_upto_you_play_with_calc_read_tips', ['read_up_link' => '<a href="javascript:void(0)" class="text-rausch rm_link">'.trans('experiences.manage.read_up').'</a>']) !!}
      </p>
      <div class="row space-top-6">
        <div class="col-sm-8">
          <div class="pos_rel input_new2">
            <span class="h3">{{$host_experience->currency_code}}
            </span>
            <input type="text" class="input_new1 numeric-values" value="" name="price_per_guest" id="host_experience_price_per_guest" ng-model="host_experience.price_per_guest" placeholder="{!! $host_experience->currency->original_symbol !!}">
            <p class="text-danger" ng-show="form_errors.price_per_guest.length">@{{form_errors.price_per_guest[0]}}</p>
          </div>
          <p><small>*{{trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $host_experience->currency->original_symbol.$host_experience->minimum_price])}}</small></p>
        </div>
      </div>
      <div class="tabl space-top-3" ng-show="host_experience.guest_requirements.allowed_under_2 == 'Yes'">
        <div class="tb_cell ver_top">
          <input type="checkbox" class="chekbox1" name="is_free_under_2" id="host_experience_is_free_under_2" ng-model="host_experience.is_free_under_2" ng-true-value="'Yes'" ng-false-value="'No'">
        </div>
        <div class="tb_cell">
          <label class="h4 cursor padd0" for="check1">{{trans('experiences.manage.free_for_under_2')}}
          </label>
        </div>
      </div>        
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1 padd1">
      <p class="h3 bold space-top-2">{{trans('experiences.manage.pricing_calculator')}}
      </p>
      <div class="row space-top-8">
        <div class="col-sm-6">
          <p class="h4">{{trans('experiences.manage.price_per_guest')}}
          </p>
        </div>
        <div class="col-sm-6 text-right">
          <p class="h4">{!! $host_experience->currency->original_symbol !!}@{{price_filter(host_experience.price_per_guest)}}
          </p>
        </div>
      </div>
      <hr>
      <div class="row space-top-3 space-3">
        <div class="col-sm-6">
          <p class="h4 space-0 space-top-1">{{trans('experiences.manage.number_of_guests')}}
          </p>
        </div>
        <div class="col-sm-6" ng-init="price_calc_guest = 1">
          <input type="number" name="" value="4" class="wid1 pull-right" ng-model="price_calc_guest" >
        </div>
      </div>
      <hr>
      <div class="row space-top-5 space-2">
        <div class="col-sm-6">
          <p class="h4 space-0">{{trans('experiences.manage.you_could_make')}}
          </p>
        </div>
        <div class="col-sm-6 text-right">
          <p class="h4">{!! $host_experience->currency->original_symbol !!} @{{price_calc_guest * price_filter(host_experience.price_per_guest)}}
          </p>
        </div>
      </div>   
      <small>{!! trans('experiences.manage.this_is_amount_after_service_fee_by_site', ['site_name' => SITE_NAME, 'service_fee_link' => trans('experiences.manage.service_fee'), 'service_fee' => $service_fee]) !!}        
      </small>
    </div>
  </div>
</div>
<!--  main_bar end -->