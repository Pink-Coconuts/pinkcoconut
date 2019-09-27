<div class="main_bar pull-left padd5 text-black bg-white pos_rel" ng-init="check_single_photo();">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="clearfix ">
    <div class="col-lg-7">
      <p class="h2 bold space-top-8">{{trans('experiences.manage.add_photos_for_your_experience')}}
      </p>
      <p class="h4 line1 space-2">{{trans('experiences.manage.photos_page_desc')}}
      </p>
      <a href="javascript:void(0)" class="rm_link text-rausch h4 pop_link hide" data-id="popup2"> 
        <span class="icon icon2-light-bulb h3">
        </span> {{trans('experiences.manage.tips_and_examples')}}
      </a>
      <br>
      @{{photo_style}}
      <div ng-repeat="photos in host_experience_photos">
        <label class="browse text-center space-top-8" id="photo_div_@{{$index}}" ng-class="photos.name ? ' bg_cover pos_rel' : ''" ng-init="photo_style_dis[$index] = photos.name ? {'background-image':'url('+photos.image_url+')'} : {}" ng-style="photo_style($index);">
          <a class="icon icon2-cancel close1 rm_link" ng-if="photos.name" href="javascript:void(0)" ng-click="remove_photo($index)">
          </a> 
          <span class="icon-add h4 text-rausch ">
          </span>
          <input type="file" id="host_experience_photo_@{{$index}}" class="host_experience_photos_element" name="host_experiences_photos[]" ng-disabled="photos.name" data-index="@{{$index}}">
        </label>
        <br>
      </div>
      @include('host_experiences.manage_experience.control_buttons')
    </div>
    <div class="pos_fix1">
      @include('host_experiences/manage_experience/mobile_preview')
    </div>
  </div>
</div>
<!--  main_bar end -->