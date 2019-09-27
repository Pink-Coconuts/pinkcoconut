<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.maximum_number_guests')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.what_number_guests_you_can_accommodate')}}
      </p>
      <div class="row space-top-1">
        <div class="col-sm-8 dtner">
          <div class="drp_dwn_cng1 pos_rel dropdown2 space-top-2">
            <i class="icon-chevron-down">
            </i>
            <select class="" name="number_of_guests" id="host_experience_number_of_guests" ng-model="host_experience.number_of_guests">
              <option ng-if="host_experience.number_of_guests == ''" value="">{{trans('experiences.manage.choose_number_of_guests')}}
              </option>
              @foreach($host_experience->group_size_array as $size)
              <option value="{{$size}}">{{$size}}
              </option>
              @endforeach
            </select>            
            <p class="text-danger" ng-show="form_errors.number_of_guests.length">@{{form_errors.number_of_guests[0]}}</p>        
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