
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 home_exprt" >
  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 " style="padding:0px;     margin: 45px 0px 15px;" ng-if="host_experiences.length >= 1">
    <h3 class="rowHeader pull-left">
      {{ $title_text }}
    </h3>
  </div>
  <div class="home-bx-slider1 col-md-12 col-lg-12 col-sm-12 col-xs-12 " style="position:relative;padding:0px;">
    <div class="row hm_slide1" >
      <div class="cateimg1" ng-repeat="(key, host_experience) in host_experiences" ng-if="key<=7">
        <div class="col-md-3">
          <a href="@{{ host_experience.link }}">
            <img ng-src="@{{ host_experience.photo_resize_name }}" >
          </a>
          <div class="panel-body panel-card-section" style="padding:10px 0px;">
          <div class="media">
            <div class="category_city nt_ctcity">
              <span class="pull-left">@{{host_experience.category_details.name}}</span>
              <span class="pull-left dot-cont">·</span>
              <span class="pull-left">@{{host_experience.city_details.name}}</span>
            </div>
            <a href="@{{ host_experience.link }}"  class="text-normal" style="text-decoration:none !important;">
            <h3 title="@{{host_experience.title}}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1 ng-binding">
             @{{ host_experience.title }}
            </h3>
            </a>

            <div class="exp_price" ><span ng-bind-html="host_experience.currency.symbol"></span> @{{ host_experience.session_price }} {{ trans("messages.wishlist.per_guest") }}

            </div>

            <div class="star-rating-wrapper" >
              <div class="category_city" ng-if="host_experience.overall_star_rating != '' ">
                <span class="pull-left" ng-bind-html="host_experience.overall_star_rating"></span>
                <span class="pull-left dot-cont">·</span>
                <span class="pull-left">@{{ host_experience.reviews_count }} @{{ host_experience.reviews_count_lang }}</span>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="seeMoreContainer_11b8zgn" ng-if="host_experiences.length > 8">

      <a href="{{ $see_all_link }}" >
        <button class="button_ops1o9-o_O-text_13lu1ne-o_O-button_flushRight_s5eog0">
          <span class="text_13lu1ne">
            <span>{{ trans('messages.header.seeall') }}
            </span>
          </span>
          <svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="fill: currentcolor; height: 10px; width: 10px;"><path fill-rule="evenodd" d="M4.293 1.707A1 1 0 1 1 5.708.293l7.995 8a1 1 0 0 1 0 1.414l-7.995 8a1 1 0 1 1-1.415-1.414L11.583 9l-7.29-7.293z"></path></svg>
        </button>
      </a>

    </div>
</div>

@push('scripts')
<script type="text/javascript">
 $('.home-bx-slider .bxslider.host_experience_bxslider').bxSlider({
     infiniteLoop: false,
     hideControlOnEnd: true,
     minSlides: 1,
     maxSlides: 4,
     slideWidth: 240,
     slideMargin: 14,
     moveSlides: 1,
    // preventDefaultSwipeY: true,
     onSliderLoad: function() {
         setTimeout(function() {
             $("#lazy_load_slider").removeClass('lazy-load');
         }, 2000);
     }
 });
</script>
<style type="text/css">
.home-bx-slider .bx-wrapper .experience-panel-card img
{
    height: 164px !important;
    width: 100%;
    border-radius: 3px;
}
.experience-panel-card a {
    display: inline-block;
    width: 100%;
}
/*
.host_experiences_wrapper .bx-wrapper .bx-viewport
{
  height: auto !important;
}*/
</style>
@endpush
