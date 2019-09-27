<div class="main_bar pull-left padd5 text-black bg-white pos_rel net_gt" ng-init="countries = {{json_encode($countries)}};">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.add_a_meeting_location')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.where_will_meet_page_desc')}}
      </p>
      <p class="bold h4 space-top-4">{{trans('experiences.manage.step')}} 1: {{trans('experiences.manage.provide_an_address')}}
      </p>
      <p class="bold h4 space-top-4 space-2">{{trans('experiences.manage.location_name')}}
      </p>
      <input type="text" name="host_experience_location[location_name]" class="input_new1" id="host_experience_location_location_name" ng-model="host_experience.host_experience_location.location_name" />
      <p class="text-danger" ng-show="form_errors.host_experience.location_name.length">@{{form_errors.host_experience.location_name[0]}} </p>  
      <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.country')}}
      </p>        
      <div class="drp_dwn_cng1 pos_rel dropdown2">
        <i class="icon-chevron-down">
        </i>
        <select class="" name="host_experience_location[country]" id="host_experience_location_country" ng-model="host_experience.host_experience_location.country">
          <option ng-if="host_experience.host_experience_location.country == ''" value="">{{trans('experiences.manage.select_country')}}
          </option>
          @foreach($countries as $k => $v)
          <option value="{{$k}}">{{$v}}
          </option>
          @endforeach
        </select> 
        <p class="text-danger" ng-show="form_errors.host_experience.country.length">@{{form_errors.host_experience.country[0]}} </p>           
      </div> 
      <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.street_address')}}
      </p> 
      <input type="text" name="host_experience_location[address_line_1]" class="input_new1 ne_st" id="host_experience_location_address_line_1" ng-model="host_experience.host_experience_location.address_line_1" />
      <p class="text-danger" ng-show="form_errors.host_experience.address_line_1.length">@{{form_errors.host_experience.address_line_1[0]}} </p>  
      <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.address_line_1_name')}}
      </p> 
      <input type="text" name="host_experience_location[address_line_2]" class="input_new1" id="host_experience_location_address_line_2" ng-model="host_experience.host_experience_location.address_line_2" />
      <div class="row"> 
        <div class="col-sm-6">
          <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.city')}}
          </p>
          <input type="text" name="host_experience_location[city]" class="input_new1" id="host_experience_location_city" ng-model="host_experience.host_experience_location.city" />
          <p class="text-danger" ng-show="form_errors.host_experience.city.length">@{{form_errors.host_experience.city[0]}} </p>  
        </div>
        <div class="col-sm-6">
          <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.state')}}
          </p>
          <input type="text" name="host_experience_location[state]" class="input_new1" id="host_experience_location_state" ng-model="host_experience.host_experience_location.state" />
        </div>
      </div>
      <div class="row"> 
        <div class="col-sm-6">
          <p class="bold h4 space-top-2 space-2">{{trans('experiences.manage.zip_code')}}
          </p>        
          <input type="text" name="host_experience_location[postal_code]" class="input_new1" id="host_experience_location_postal_code" ng-model="host_experience.host_experience_location.postal_code" />
        </div>
      </div>
      <div ng-show="host_experience.host_experience_location.latitude && host_experience.host_experience_location.longitude">
        <p class="bold h4 space-top-8">{{trans('experiences.manage.step')}} 2: {{trans('experiences.manage.drop_a_pin_on_the_map')}}
        </p>
        <p class="bold h4 space-top-4 space-1">{{trans('experiences.manage.map_pin')}}
        </p>
        <p class="h4 text-muted">{{trans('experiences.manage.drag_the_pin_on_the_map_for_exact_location')}}
        </p>
        <input type="hidden" name="host_experience_location[latitude]" class="input_new1" id="latitude" ng-model="host_experience.host_experience_location.latitude" />
        <input type="hidden" name="host_experience_location[longitude]" class="input_new1" id="host_experience_location_longitude" ng-model="host_experience.host_experience_location.longitude" />
        
        <div id="host_experience_location_map" style="width: 100%; height: 340px;"></div>
        
        <small>{{trans('experiences.manage.this_wont_share_with_guests_until_book')}}
        </small>
        <p class="bold h4 space-top-4 space-2">{{trans('experiences.manage.directions_optional')}}
        </p>        
        <textarea class="input_new1" rows="3" id="host_experience_location_directions" name="host_experience_location[directions]" ng-model="host_experience.host_experience_location.directions">{{trans('experiences.manage.directions_example')}}
        </textarea>
      </div>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1" ng-init="initialize_autocomplete(); initialize_map();">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->