<div class="main_bar pull-left padd5 text-black bg-white pos_rel" ng-init="categories = {{json_encode($categories)}}">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8 set_tim1">{{trans('experiences.manage.what_type_of_experience_you_host')}}
      </p>
      <p class="h4 line1 space-2 set_timp">{{trans('experiences.manage.category_step_desc')}}
      </p>
      <div class="drp_dwn_cng1 pos_rel dropdown1 space-top-4 space-4">
        <i class="icon-chevron-down">
        </i>
        <select class="light" id="host_experience_category" name="category" ng-model="host_experience.category" ng-init="host_experience.category = host_experience.category == null ? '' : host_experience.category;">
          <option ng-if="host_experience.category == ''" value=''>{{trans('experiences.manage.choose_a_category')}}
          </option>
          <option ng-repeat="category in categories" ng-selected="host_experience.category == category.id" value="@{{category.id}}">@{{category.name}}
          </option>
        </select>
        <p class="text-danger" ng-show="form_errors.category.length">@{{form_errors.category[0]}} </p>
      </div>
      <div class="space-top-5" ng-init="is_secondary = (host_experience.secondary_category > 0)" ng-class="!is_secondary ? 'hide' : ''">
        <span class="h3">{{trans('experiences.manage.second_category_optional')}}
        </span> 
        <a href="#" class="text-rausch rm_link h4" ng-click="host_experience.secondary_category = ''; is_secondary = false">&nbsp;{{trans('experiences.manage.remove')}}
        </a>
        <div class="drp_dwn_cng1 pos_rel dropdown1 space-top-2 space-4">
          <i class="icon-chevron-down">
          </i>
          <select class="light" id="host_experience_secondary_category" name="secondary_category" ng-model="host_experience.secondary_category" ng-init="host_experience.secondary_category = host_experience.secondary_category == null ? '' : host_experience.secondary_category;">
            <option ng-if="host_experience.secondary_category == ''" value=''>{{trans('experiences.manage.choose_a_category')}}
            </option>
            <option ng-repeat="category in categories" ng-selected="host_experience.secondary_category == category.id" ng-if="category.id != host_experience.category" value="@{{category.id}}">@{{category.name}}
            </option>
          </select>
          <p class="text-danger" ng-show="form_errors.secondary_category.length">@{{form_errors.secondary_category[0]}} </p>
        </div>
      </div>
      <a href="javascript:void(0)" class="rm_link text-rausch h4" ng-show="host_experience.category" ng-class="is_secondary ? 'hide' : ''" ng-click=" is_secondary = true;">+ {{trans('experiences.manage.add_a_second_category_optional')}}
      </a>
      <div class="hide">
        <p class="h4 space-top-6 space-1 bold"> 
          <i class="icon icon2-ribbon">
          </i> Is this a social impact experience?
        </p>
        <p class="h4 line1 space-top-4">If you’re partnering with a nonprofit or a charitable organization, you can host a social impact experience. To host, you’ll have to validate your organization with our partner, TechSoup. 
          <a href="#" class="rm_link text-rausch"> Learn more
          </a>
        </p>
        <label class="space-top-4">
          <input type="checkbox" name="" class="chekbox1 check_detail_tri"> 
          <span class="h4"> Yes, this is a social impact experience
          </span>
        </label>
        <div class="check_detail hide">
          <p class="h4 space-top-6 space-1 bold"> Are you signed into the correct account?
          </p>
          <p class="h4 line1 space-top-4">Your payout method must go to your nonprofit. It cannot be changed once this experience is published.
          </p>
          <div class="tabl">
            <div class="tb_cell">
              <div class="prof1" style="background-image: url('{{url('images/host_experiences/pro.jpg')}}');">
              </div>
            </div>
            <div class="tb_cell">
              <p class="h4 bold space-0"> &nbsp;&nbsp;Arun
              </p>
            </div>
          </div>
          <label class="space-top-4">
            <input type="checkbox" name="" class="chekbox1 check_detail_tri1"> 
            <span class="h4"> This is the correct account and not my personal Airbnb account
            </span>
          </label>
        </div>
      </div>
      <!-- check_detail end -->
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
</div>
<!--  main_bar end -->