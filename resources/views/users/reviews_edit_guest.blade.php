@extends('template')

@section('main')


<main role="main" id="site-content" ng-controller="reviews_edit_host">
      
<div class="page-container page-container-responsive">
  <div class="row row-space-top-4 row-space-8">
    <div class="col-4 tr_nty">
      <div class="h2 row-space-3">
        {{ trans('messages.reviews.rate_review') }}
      </div>
      <div class="listing">
        <div class="panel-image listing-img">
          <a title="{{ $result->rooms->name }}" class="media-photo media-cover text-center" alt="{{ $result->rooms->name }}" href="{{ url('/') }}/rooms/{{ $result->room_id }}">
            <img src="{{ $result->rooms->src }}" class="img-responsive-height" alt="9a1ec6ec_original">
</a>    </div>
        <div class="panel-body panel-card-section">
          <div class="media">
            <a class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" href="{{ url('/') }}/users/show/{{ $result->rooms->users->id }}">
              <img width="68" height="68" title="{{ $result->rooms->users->first_name }}" src="{{ $result->rooms->users->profile_picture->src }}" alt="{{ $result->rooms->users->first_name }}">
</a>
            <div class="h5 listing-name text-truncate row-space-top-1">
              {{ $result->rooms->name }}
            </div>
            <div class="text-muted listing-location text-truncate">
              {{ $result->rooms->room_type_name }} · {{ $result->rooms->rooms_address->city }}
            </div>
            <div class="h5 listing-name text-truncate row-space-top-1">
              {{ trans('messages.reviews.hosted_by') }}
                {{ $result->rooms->users->full_name }}
            </div>
            <div class="text-muted listing-location text-truncate">
              {{ $result->dates }}
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
    {{ trans('messages.reviews.write_review_for') }} {{ $result->rooms->users->first_name }}
  </h3>
  <p class="text-lead text-muted">
      {{ trans('messages.reviews.write_review_guest_desc1') }}
      {{ trans('messages.reviews.write_review_guest_desc2') }}
  </p>

  <p class="text-lead text-muted">
    {{ trans('messages.reviews.write_review_guest_desc3') }}
  </p>

  <button class="btn btn-primary btn-large next-facet">
    {{ trans('messages.reviews.write_a_review') }}
  </button>
</section>

</div>
<input type="hidden" value="{{ $result->id }}" name="reservation_id" id="reservation_id">
<input type="hidden" value="{{ $review_id }}" name="review_id" id="review_id">
          <div class="review-facet hide" id="host-summary">
            <form id="host-summary-form">
              <input type="hidden" value="host_summary" name="section" id="section">
              <section class="row-space-6">
          <div class="h3 row-space-1">
            {{ trans('messages.reviews.describe_your_exp') }}
            <small>({{ trans('messages.reviews.required') }})</small>
          </div>
  <div class="text-lead text-muted row-space-2">
      {{ trans('messages.reviews.describe_your_exp_guest_desc',['site_name'=>$site_name]) }}
  </div>
  <label style="display: none;" class="invalid" generated="true" for="review_comments">{{ trans('messages.reviews.this_field_is_required') }}</label><textarea rows="8" placeholder="{{ trans('messages.reviews.describe_your_exp_placeholder') }}" name="comments" id="review_comments" data-behavior="countable" cols="40" class="input-large valid" onkeyup="countChar(this)">{{ @$result->review_details($review_id)->comments }}</textarea>
  <div data-behavior="counter" class="h6 pull-right row-space-top-1">
    <span id="charNum">{{500-strlen(@$result->review_details($review_id)->comments)}}</span><span> {{ trans('messages.reviews.500_words_left') }}</span>
  <span class="reached-warning hide">
      {{ trans('messages.reviews.reached_500_words') }}
    </span>
  </div>
</section>

<section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.private_host_feedback') }}
  </div>
  <div class="text-lead text-muted row-space-2">
    {{ trans('messages.reviews.private_host_feedback_desc',['site_name'=>$site_name]) }}
  </div>

  <div class="row-space-2">
    <label for="love_comments">{{ trans('messages.reviews.what_did_you_love_about_staying') }}</label>
    <textarea rows="3" name="love_comments" id="love_comments" class="input-large valid">{{ @$result->review_details($review_id)->love_comments }}</textarea>
  </div>
  <div>
    <label for="improve_comments">{{ trans('messages.reviews.how_host_improve') }}</label>
    <textarea rows="3" name="improve_comments" id="improve_comments" class="input-large valid">{{ @$result->review_details($review_id)->improve_comments }}</textarea>
  </div>
</section>

<section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.overall_exp') }}
    <small>({{ trans('messages.reviews.required') }})</small>
  </div>
  <label style="display: none;" class="invalid" generated="true" for="review_rating">{{ trans('messages.reviews.this_field_is_required') }}</label>
  <div class="star-rating">
    <input type="radio" value="5" name="rating" id="review_rating_5" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->rating == 5) ? 'checked="true"' : '' }}>
    <label for="review_rating_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="rating" id="review_rating_4" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->rating == 4) ? 'checked="true"' : '' }}>
    <label for="review_rating_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="rating" id="review_rating_3" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->rating == 3) ? 'checked="true"' : '' }}>
    <label for="review_rating_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="rating" id="review_rating_2" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->rating == 2) ? 'checked="true"' : '' }}>
    <label for="review_rating_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="rating" id="review_rating_1" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->rating == 1) ? 'checked="true"' : '' }}>
    <label for="review_rating_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</section>

<section class="row-space-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.pride_inclusion_score') }}<small>({{ trans('messages.reviews.required') }})</small>
  </div>
  <label style="display: none;" class="invalid" generated="true" for="pride_comments">{{ trans('messages.reviews.this_field_is_required') }}</label>
  
  <div class="star-rating">
    @for ($i = 5; $i >= 1; $i--)
    <input type="radio" value="{{ $i }}" name="pride_comments" id="pride_comments_{{ $i }}" class="star-rating-input needsclick valid" {{ (@$result->review_details($review_id)->pride_comments == $i) ? 'checked="true"' : '' }}>
    <label for="pride_comments_{{ $i }}" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    @endfor
  </div>
</section>


<button class="btn btn-primary btn-large review_submit" type="submit">
  {{ trans('messages.account.next') }}
</button>

</form>    
</div>

          <div tabindex="-1" class="review-facet hide" id="host-details">
            <form id="host-details-form">
              <input type="hidden" value="host_details" name="section" id="section">
              
<div class="text-lead row-space-4">
  {{ trans('messages.reviews.guest_star_reviews_desc') }}
</div>

  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.accuracy') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.accuracy_desc1') }}
    </div>
    <div class="star-rating">
    <input type="radio" value="5" name="accuracy" id="review_accuracy_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->accuracy == 5) ? 'checked="true"' : '' }}>
    <label for="review_accuracy_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="accuracy" id="review_accuracy_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->accuracy == 4) ? 'checked="true"' : '' }}>
    <label for="review_accuracy_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="accuracy" id="review_accuracy_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->accuracy == 3) ? 'checked="true"' : '' }}>
    <label for="review_accuracy_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="accuracy" id="review_accuracy_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->accuracy == 2) ? 'checked="true"' : '' }}>
    <label for="review_accuracy_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="accuracy" id="review_accuracy_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->accuracy == 1) ? 'checked="true"' : '' }}>
    <label for="review_accuracy_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>

      <label for="accuracy_comments" class="threshold-comment">{{ trans('messages.reviews.accuracy_desc2') }}</label>
    <textarea rows="2" name="accuracy_comments" id="review_accuracy_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->accuracy_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.cleanliness') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.cleanliness_desc1') }}
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

      <label for="cleanliness_comments" class="threshold-comment">{{ trans('messages.reviews.cleanliness_desc2') }}</label>
    <textarea rows="2" name="cleanliness_comments" id="review_cleanliness_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->cleanliness_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.arrival') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.arrival_desc1') }}
    </div>
    <div class="star-rating">
    <input type="radio" value="5" name="checkin" id="review_checkin_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->checkin == 5) ? 'checked="true"' : '' }}>
    <label for="review_checkin_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="checkin" id="review_checkin_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->checkin == 4) ? 'checked="true"' : '' }}>
    <label for="review_checkin_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="checkin" id="review_checkin_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->checkin == 3) ? 'checked="true"' : '' }}>
    <label for="review_checkin_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="checkin" id="review_checkin_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->checkin == 2) ? 'checked="true"' : '' }}>
    <label for="review_checkin_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="checkin" id="review_checkin_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->checkin == 1) ? 'checked="true"' : '' }}>
    <label for="review_checkin_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>

      <label for="checkin_comments" class="threshold-comment">{{ trans('messages.reviews.arrival_desc2') }}</label>
    <textarea rows="2" name="checkin_comments" id="review_checkin_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->checkin_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.amenities') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.amenities_desc1') }}
    </div>
    <div class="star-rating">
    <input type="radio" value="5" name="amenities" id="review_amenities_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->amenities == 5) ? 'checked="true"' : '' }}>
    <label for="review_amenities_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="amenities" id="review_amenities_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->amenities == 4) ? 'checked="true"' : '' }}>
    <label for="review_amenities_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="amenities" id="review_amenities_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->amenities == 3) ? 'checked="true"' : '' }}>
    <label for="review_amenities_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="amenities" id="review_amenities_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->amenities == 2) ? 'checked="true"' : '' }}>
    <label for="review_amenities_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="amenities" id="review_amenities_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->amenities == 1) ? 'checked="true"' : '' }}>
    <label for="review_amenities_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>

      <label for="amenities_comments" class="threshold-comment">{{ trans('messages.reviews.amenities_desc2') }}</label>
    <textarea rows="2" name="amenities_comments" id="review_amenities_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->amenities_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.communication') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.communication_desc1') }}
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

      <label for="communication_comments" class="threshold-comment">{{ trans('messages.reviews.communication_desc2') }}</label>
    <textarea rows="2" name="communication_comments" id="review_communication_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->communication_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.location') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.location_desc1') }}
    </div>
    <div class="star-rating">
    <input type="radio" value="5" name="location" id="review_location_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->location == 5) ? 'checked="true"' : '' }}>
    <label for="review_location_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="location" id="review_location_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->location == 4) ? 'checked="true"' : '' }}>
    <label for="review_location_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="location" id="review_location_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->location == 3) ? 'checked="true"' : '' }}>
    <label for="review_location_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="location" id="review_location_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->location == 2) ? 'checked="true"' : '' }}>
    <label for="review_location_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="location" id="review_location_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->location == 1) ? 'checked="true"' : '' }}>
    <label for="review_location_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>

      <label for="location_comments" class="threshold-comment">{{ trans('messages.reviews.location_desc2') }}</label>
    <textarea rows="2" name="location_comments" id="review_location_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->location_comments }}</textarea>
  </section>
  <section class="row-space-4">
    <div class="h3 row-space-1">
      {{ trans('messages.reviews.value') }}
    </div>
    <div class="text-lead text-muted row-space-1">
      {{ trans('messages.reviews.value_desc1') }}
    </div>
    <div class="star-rating">
    <input type="radio" value="5" name="value" id="review_value_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->value == 5) ? 'checked="true"' : '' }}>
    <label for="review_value_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="value" id="review_value_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->value == 4) ? 'checked="true"' : '' }}>
    <label for="review_value_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="value" id="review_value_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->value == 3) ? 'checked="true"' : '' }}>
    <label for="review_value_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="value" id="review_value_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->value == 2) ? 'checked="true"' : '' }}>
    <label for="review_value_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="value" id="review_value_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->value == 1) ? 'checked="true"' : '' }}>
    <label for="review_value_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>

      <label for="value_comments" class="threshold-comment">{{ trans('messages.reviews.value_desc2') }}</label>
    <textarea rows="2" name="value_comments" id="review_value_comments" cols="40" class="input-large row-space-top-2 threshold-comment">{{ @$result->review_details($review_id)->value_comments }}</textarea>
  </section>

<section class="row-space-4 row-space-top-6">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.would_you_recommend') }}
  </div>
  <div class="text-lead text-muted row-space-1">
    {{ trans('messages.reviews.would_you_recommend_desc') }}
  </div>

  <div class="thumbs-widget">
  <input type="radio" value="0" name="recommend" id="review_recommend_0" {{ (@$result->review_details($review_id)->recommend == 1) ? 'checked="true"' : '' }}>
  <label class="h5 icon-gray" for="review_recommend_0">
    <i class="icon icon-thumbs-down icon-size-2 icon-gray"></i>
    {{ trans('messages.reviews.no') }}
  </label>
  <input type="radio" value="1" name="recommend" id="review_recommend_1" {{ (@$result->review_details($review_id)->recommend == 0) ? 'checked="true"' : '' }}>
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