@extends('template')

@section('main')

<main role="main" id="site-content" ng-controller="reviews_edit_host">
      
<div class="page-container page-container-responsive">
  <div class="row row-space-top-4 row-space-8">
    <div class="col-4 tr_nty">
      <div class="h2 row-space-3">
        {{ trans('messages.reviews.rate_review') }}
      </div>
      <div class="panel-image listing-img">
        <a title="{{ $result->users->full_name }}" class="media-photo media-photo-block media-cover text-center" alt="{{ $result->users->full_name }}" href="{{ url('users/show/'.$result->user_id) }}">
          <img width="225" height="225" title="{{ $result->users->first_name }}" src="{{ $result->users->profile_picture->src }}" class="img-responsive-height" alt="{{ $result->users->first_name }}">
          <div class="media-caption media-caption-large">
            <div class="text-left">
                {{ $result->users->first_name }}
            </div>
          </div>
        </a>      
      </div>
      <div class="panel">
        <div class="panel-body panel-body-condensed">
          <div class="row row-condensed row-space-2">
            <div class="media">
              <a class="pull-left media-photo" href="{{ url('rooms/'.$result->room_id) }}">
                <img src="{{ $result->rooms->src }}" alt="9a1ec6ec_original" width="40" height="40">
              </a>              
              <div class="media-body">
                <div>
                  {{ trans('messages.reviews.stayed_at') }}
                  {{ $result->rooms->name }}
                </div>
                <div>
                  {{ $result->dates }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-offset-1 col-7 tr_vier">
      <div class="review-container">
        <div class="review-facets panel-body">
          <div id="double-blind-copy" class="review-facet">
            <section class="row-space-6">
  <h3>
    {{ trans('messages.reviews.write_review_for') }} {{ $result->users->first_name }}
  </h3>
  <p class="text-lead text-muted">
      {{ trans('messages.reviews.write_review_host_desc1') }}
      {{ trans('messages.reviews.write_review_host_desc2',['site_name'=>$site_name]) }}
  </p>
  <p class="text-lead text-muted">
    {{ trans('messages.reviews.write_review_host_desc3') }}
  </p>
  <button class="btn btn-primary btn-large next-facet">
    {{ trans('messages.reviews.write_a_review') }}
  </button>
</section>
          </div>
          <input type="hidden" value="{{ $review_id }}" name="review_id" id="review_id">
          <input type="hidden" value="{{ $result->id }}" name="reservation_id" id="reservation_id">
          <div tabindex="-1" class="review-facet hide" id="guest">
            <form id="guest-form" class="edit_review">
              <input type="hidden" value="guest" name="section" id="section">
              <section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.describe_your_exp') }}
    <small>({{ trans('messages.reviews.required') }})</small>
  </div>
  <div class="text-lead text-muted row-space-2">
      {{ trans('messages.reviews.describe_your_exp_host_desc',['first_name'=>$result->users->first_name]) }}
  </div>
   <label style="display: none;" class="invalid" generated="true" for="review_comments">{{ trans('messages.reviews.this_field_is_required') }}</label>
    <textarea rows="5" placeholder="{{ trans('messages.reviews.host_descrip') }}" name="comments" id="review_comments" data-behavior="countable" cols="40" class="input-large" onkeyup="countChar(this)">{{ @$result->review_details($review_id)->comments }}</textarea>
    <div data-behavior="counter" class="h6 pull-right row-space-top-1">
      <span id="charNum">{{500-strlen(@$result->review_details($review_id)->comments)}}</span><span> {{ trans('messages.reviews.500_words_left') }}</span>
      <span class="reached-warning hide">
        {{ trans('messages.reviews.reached_500_words') }}
      </span>
    </div>
      <br>
      <div class="h6 space-top-1 personal-info-warning">
        {{ trans('messages.reviews.describe_your_exp_host_desc2') }}
      </div>
</section>

<section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.private_guest_feedback') }}
  </div>
  <div class="text-lead text-muted row-space-2">
    {{ trans('messages.reviews.private_guest_feedback_desc') }}
  </div>
  <textarea rows="5" placeholder="{{ trans('messages.reviews.private_guest') }}" name="private_feedback" id="review_private_feedback" cols="40" class="input-large">{{ @$result->review_details($review_id)->private_feedback }}</textarea>
</section>

<section class="row-space-4">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.cleanliness') }}
  </div>
  <div class="text-lead text-muted row-space-1">
    {{ trans('messages.reviews.cleanliness_host_desc') }}
  </div>
  <div class="star-rating">
    <input type="radio" value="5" name="cleanliness" id="review_cleanliness_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->cleanliness == 5) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="cleanliness" id="review_cleanliness_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->cleanliness == 4) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="cleanliness" id="review_cleanliness_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->cleanliness == 3) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="cleanliness" id="review_cleanliness_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->cleanliness == 2) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="cleanliness" id="review_cleanliness_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->cleanliness == 1) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</section>

<section class="row-space-4">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.communication') }}
  </div>
  <div class="text-lead text-muted row-space-1">
    {{ trans('messages.reviews.communication_host_desc') }}
  </div>
  <div class="star-rating">
    <input type="radio" value="5" name="communication" id="review_communication_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 5) ? 'checked="true"' : '' }}>
    <label for="review_communication_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="communication" id="review_communication_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 4) ? 'checked="true"' : '' }}>
    <label for="review_communication_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="communication" id="review_communication_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 3) ? 'checked="true"' : '' }}>
    <label for="review_communication_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="communication" id="review_communication_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 2) ? 'checked="true"' : '' }}>
    <label for="review_communication_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="communication" id="review_communication_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 1) ? 'checked="true"' : '' }}>
    <label for="review_communication_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</section>

<section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.observance_house_rules') }}
  </div>
  <div class="text-lead text-muted row-space-1">
    {{ trans('messages.reviews.observance_house_rules_desc') }}
  </div>
  <div class="star-rating">
    <input type="radio" value="5" name="respect_house_rules" id="review_respect_house_rules_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->respect_house_rules == 5) ? 'checked="true"' : '' }}>
    <label for="review_respect_house_rules_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="respect_house_rules" id="review_respect_house_rules_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->respect_house_rules == 4) ? 'checked="true"' : '' }}>
    <label for="review_respect_house_rules_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="respect_house_rules" id="review_respect_house_rules_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->respect_house_rules == 3) ? 'checked="true"' : '' }}>
    <label for="review_respect_house_rules_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="respect_house_rules" id="review_respect_house_rules_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->respect_house_rules == 2) ? 'checked="true"' : '' }}>
    <label for="review_respect_house_rules_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="respect_house_rules" id="review_respect_house_rules_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->respect_house_rules == 1) ? 'checked="true"' : '' }}>
    <label for="review_respect_house_rules_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</section>

<section class="row-space-4">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.would_you_recommend') }}
  </div>
  <div class="text-lead text-muted row-space-1">
    {{ trans('messages.reviews.would_you_recommend_host_desc') }}
  </div>
  <div class="thumbs-widget">
  <input type="radio" value="0" name="recommend" id="review_recommend_0" {{ (@$result->review_details($review_id)->recommend == '0') ? 'checked="true"' : '' }}>
  <label class="h5 icon-gray" for="review_recommend_0">
    <i class="icon icon-thumbs-down icon-size-2 icon-gray"></i>
    {{ trans('messages.reviews.no') }}
  </label>
  <input type="radio" value="1" name="recommend" id="review_recommend_1" {{ (@$result->review_details($review_id)->recommend == 1) ? 'checked="true"' : '' }}>
  <label class="h5 icon-gray" for="review_recommend_1">
    <i class="icon icon-thumbs-up icon-size-2 icon-gray"></i>
    {{ trans('messages.reviews.yes') }}!
  </label>
</div>
</section>
<button data-track-submit="" class="btn btn-primary btn-large review_submit" type="submit">
  {{ trans('messages.account.submit') }}
</button>
</form>          
</div>
        </div>
      </div>
    </div>
  </div>
</div>

    </main>

@stop