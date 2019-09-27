<div class="main_bar pull-left padd5 text-black bg-white pos_rel" ng-init="check_single_packing_list();">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.create_a_packing_list')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.lets_guests_know_what_to_bring')}}
      </p>
      <p class="h4 space-4 bold">@{{host_experience.title}}
      </p>
      <p class="h4 space-4">{{trans('experiences.manage.what_should_your_guests_bring')}}
      </p>
      <div class="clone_elem pos_rel" ng-repeat="item in host_experience_packing_lists">
        <span class="icon icon2-cancel cursor" ng-click="remove_packing_list($index)">
        </span>
        <input type="hidden" name="packing_lists[][id]" id="packing_list_@{{$index}}_id" ng-model="host_experience_packing_lists[$index].id">
        <input type="text" class="input_new1 space-1" placeholder="{{trans('experiences.manage.enter_item_here')}}" name="packing_lists[][item]" id="packing_list_@{{$index}}_item" ng-model="host_experience_packing_lists[$index].item" ng-change="host_experience_packing_lists_changed(); need_packing_lists_change();">
      </div>
      <a href="javascript:void(0)" class="rm_link text-rausch h4 dis_blk space-top-3" ng-if="packing_list_can_add_more" ng-click="add_packing_list();"> + {{trans('experiences.manage.add_an_item')}}
      </a>
      <div class="space-top-8" id="need_packing_lists_part" ng-show="host_experience.need_packing_lists == null">
        <p class="bold h4 space-top-4 space-2">{{trans('experiences.manage.do_guests_not_bringing_anything')}}
        </p>
        <p class="h4 space-top-4 space-2">{{trans('experiences.manage.if_so_make_sure_you_provide_everything')}}
        </p>
        <label class="space-top-4">
          <input type="checkbox" name="need_packing_lists" ng-model="host_experience.need_packing_lists" ng-true-value="'No'" ng-false-value="false" ng-checked="host_experience.need_packing_lists == 'No'"> 
          <span class="h4"> {{trans('experiences.manage.my_guests_dont_need_to_bring_anything')}}
          </span>
          <p class="text-danger" ng-show="form_errors.need_packing_lists.length">@{{form_errors.need_packing_lists[0]}}
          </p>
        </label>
      </div>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1 padd1">
    </div>
  </div>
</div>
<!--  main_bar end -->