@extends('template')

@section('main')

<main role="main" id="site-content" ng-controller="reviews_edit_host">

  <div class="page-container page-container-responsive">
    <div class="row row-space-top-4 row-space-8">
      <div class="col-4">
        <div class="h2 row-space-3">
          {{ trans('messages.reviews.rate_review') }}
        </div>


        <div class="listing">
          <div class="panel-image panel-image1 listing-img">
            <a title="{{ $result->rooms->title }}" class="media-photo media-cover text-center" alt="{{ $result->rooms->title }}" href="{{ url('/') }}/experiences/{{ $result->room_id }}">
              <img src="{{ $result->rooms->photo_name }}" class="img-responsive-height" alt="{{ $result->rooms->title }}">
            </a>    </div>
            <div class="panel-body panel-card-section">
              <div class="media">
                <a class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" href="{{ url('/') }}/users/show/{{ $result->rooms->users->id }}">
                  <img width="68" height="68" title="{{ $result->rooms->users->first_name }}" src="{{ $result->rooms->users->profile_picture->src }}" alt="{{ $result->rooms->users->first_name }}">
                </a>
                <div class="h5 listing-name text-truncate row-space-top-1">
                  {{ $result->rooms->title }}
                </div>
                <div class="text-muted listing-location text-truncate">
                  {{ $result->rooms->rooms_address->city }}
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

        <div class="col-offset-1 col-7">
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
                    <label style="display: none;" class="invalid" generated="true" for="review_comments">{{ trans('messages.reviews.this_field_is_required') }}</label>
                  </section>

                  <section class="row-space-6">
                    <div>
                      <textarea rows="3" name="improve_comments" id="improve_comments" class="input-large valid">{{ @$result->review_details($review_id)->comments }}</textarea>
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
                  <button class="btn btn-primary btn-large exp_review_submit" type="submit">
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