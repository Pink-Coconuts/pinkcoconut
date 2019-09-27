@extends('template')

@section('main')

<main role="main" id="site-content" ng-controller="reviews_edit_host">
      
<div class="page-container page-container-responsive">
  <div class="row row-space-top-4 row-space-8">
    <div class="col-4">
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
                <img src="{{ $result->rooms->photo_name }}" alt="9a1ec6ec_original" width="40" height="40">
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

    <div class="col-offset-1 col-7">
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

</section>

<section class="row-space-6">

  <textarea rows="5" placeholder="{{ trans('messages.reviews.private_guest') }}" name="private_feedback" id="review_private_feedback" cols="40" class="input-large">{{ @$result->review_details($review_id)->comments }}</textarea>
</section>

<section class="row-space-4">
  <div class="h3 row-space-1">
    {{ trans('messages.reviews.rate_review') }}
  </div>
  <div class="star-rating">
    <input type="radio" value="5" name="cleanliness" id="review_cleanliness_5" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 5) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="cleanliness" id="review_cleanliness_4" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 4) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="cleanliness" id="review_cleanliness_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 3) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="cleanliness" id="review_cleanliness_2" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 2) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="cleanliness" id="review_cleanliness_1" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 1) ? 'checked="true"' : '' }}>
    <label for="review_cleanliness_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</section>

<button data-track-submit="" class="btn btn-primary btn-large exp_review_submit" type="submit">
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