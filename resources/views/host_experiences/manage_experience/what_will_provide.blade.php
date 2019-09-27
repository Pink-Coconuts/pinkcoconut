<div class="main_bar pull-left padd5 text-black bg-white pos_rel" ng-init="check_single_provide();">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.confirm_what_will_provide')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.what_will_provide_page_desc')}}
      </p>
      <a href="javascript:void(0)" class="rm_link text-rausch h4 pop_link hide" data-id="popup1"> 
        <span class="icon icon2-light-bulb h3">
        </span> {{trans('experiences.manage.tips_and_examples')}}
      </a>
      <div class="row">        
        @{{$scope.removed_provides}}
        <div class="col-sm-8">
          <div class="clone_sec_wrap">
            <div class="clone_sec" ng-repeat="provide in host_experience_provides">
              <div class="clearfix space-top-8 space-2">
                <p class="bold h4 pull-left space-0">{{trans('experiences.manage.item')}} @{{$index+1}}
                </p>
                <a href="javascript:void(0)" class="h6 rm_link pull-right space-0 text-rausch mul_rm" ng-show="provide.host_experience_provide_item_id > 0" ng-click="remove_provide($index)">{{trans('experiences.manage.remove')}}
                </a>
              </div>
              <div class="drp_dwn_cng1 pos_rel dropdown2 ">
                <i class="icon-chevron-down">
                </i>
                <select class="" id="host_experience_provide_@{{$index}}_host_experience_provide_item_id" name="provides[][host_experience_provide_item_id]" ng-model="host_experience_provides[$index].host_experience_provide_item_id" ng-change="host_experience_provides_changed(); need_provides_change();" >
                  <option ng-if="provide.host_experience_provide_item_id == 0" value="0">{{trans('experiences.manage.select_item')}}
                  </option>
                  <option ng-repeat="item in provide_items" ng-selected="provide.host_experience_provide_item_id == item.id" value="@{{item.id}}" ng-if="check_provide_item_available(item.id, $parent.$index)">@{{item.name}}
                  </option>
                </select>            
              </div>
              <div class="focus_txt" ng-show="provide.host_experience_provide_item_id > 0">
                <input type="text" name="provides[][name]" class="input_new1  top_1px_adj mul_input" id="host_experience_provide_@{{$index}}_name" placeholder="{{trans('experiences.manage.name_item')}}" ng-model="host_experience_provides[$index].name" ng-change="host_experience_provides_changed();" ng-focus="show_element('#provide_name_tips_'+$index);" ng-blur="hide_element('#provide_name_tips_'+$index);" />
                <textarea name="provides[][additional_details]" class="input_new1 top_1px_adj  mul_textarea" rows="3" placeholder="{{trans('experiences.manage.add_additionale_detials_optional')}}" id="host_experience_provide_@{{$index}}_additional_details"  ng-model="host_experience_provides[$index].additional_details" ng-change="host_experience_provides_changed();" ng-focus="show_element('#provide_additional_details_tips_'+$index);" ng-blur="hide_element('#provide_additional_details_tips_'+$index);" ng-show="provide.name">
                </textarea>
              </div>
              <p class="space-top-2" id="provide_name_tips_@{{$index}}" ng-class="steps_status[step] ? '' : 'text-warning'" style="display: none;">@{{character_length_validation(1, 25, host_experience_provides[$index].name.length)}}
              </p>
              <p class="space-top-2" id="provide_additional_details_tips_@{{$index}}" ng-class="steps_status[step] ? '' : 'text-warning'" style="display: none;">@{{character_length_validation(1, 125, host_experience_provides[$index].additional_details.length)}}
              </p>
            </div> 
            <!-- close sec end -->
            <a href="javascript:void(0)" class="rm_link text-rausch dis_blk  mul_add new_rp" ng-click="add_provide();" ng-show="provide_can_add_more">
              <span class="icon-add">
              </span> 
              <span class="h5">{{trans('experiences.manage.add_another_item')}}
              </span> 
            </a>
          </div>
          <!-- clone_sec_wrap end -->
        </div>
      </div>    
      <div id="need_provides_part" ng-show="host_experience.need_provides == null">
        <p class="bold h4 space-top-4 space-2">{{trans('experiences.manage.not_providing_anythig_for_guests')}}
        </p>
        <label class="space-top-4">
          <input type="checkbox" name="need_provides" ng-model="host_experience.need_provides" ng-true-value="'No'" ng-false-value="false" ng-checked="host_experience.need_provides == 'No'"> 
          <span class="h4"> {{trans('experiences.manage.i_am_not_providing_anything')}}
          </span>
          <p class="text-danger" ng-show="form_errors.need_provides.length">@{{form_errors.need_provides[0]}}
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