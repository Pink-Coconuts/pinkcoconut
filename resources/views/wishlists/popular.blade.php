@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">

<div class="app_view">

  @include('common.wishlists_subheader')

<div class="wishlists-container page-container-responsive row-space-top-4">
<div class="popular_view feed_view">
<div data-feed-container="" class="list-view"><div>
<div data-infinity-pageid="4" style="margin: 0px; padding: 0px; border: none;">
<div class="popular_2up_listings_view row row-space-4">
@if($result->count() == 0)
<h2 class="text-center" style="margin-top:10%">
{{ trans('messages.search.no_results_found') }}!
</h2>
@endif
@foreach($result as $row)
  <div class="col-6">
    <div class="listing">
  <div class="panel-image listing-img img-large">
    
      <a href="{{ url('rooms/'.$row->id) }}" class="media-photo media-cover wishlist-bg-img" target="_blank">
         <img src="{{ $row->photo_name }}" width="639" height="426">
      </a>
    
    <div class="panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label">
      <span class="h3 price-amount">
        
          <sup class="h6 text-contrast">{{ $row->rooms_price->currency->symbol }}</sup>
          {{ $row->rooms_price->night }}
        
      </span>
      <sup class="h6 text-contrast"></sup>
    </div>
    <div class="panel-overlay-top-right wl-social-connection-panel">
      <span class="rich-toggle wish_list_button wishlist-button not_saved" data-hosting_id="{{ $row->id }}" data-img="{{ $row->photo_name }}" data-name="{{ $row->name }}" data-address="{{ $row->rooms_address->city }}" title="Save to Wish List">
        <input type="checkbox" id="wishlist-widget-{{ $row->id }}" name="wishlist-widget-{{ $row->id }}" data-for-hosting="{{ $row->id }}" ng-checked="{{$row->saved_wishlists}}">
        <label for="wishlist-widget-{{ $row->id }}">
          <i class="icon icon-heart icon-rausch icon-size-2 rich-toggle-checked"></i>
          <i class="icon icon-heart wishlist-heart-unchecked icon-size-2 rich-toggle-unchecked"></i>
          <i class="icon icon-heart-alt icon-white icon-size-2" id="wishlist-widget-icon-{{ $row->id }}" data-room_id="{{ $row->id }}" data-img="{{ $row->photo_name }}" data-name="{{ $row->name }}" data-address="{{ $row->rooms_address->city }}" data-host_img="{{ $row->users->profile_picture->src }}"></i>
        </label>
      </span>
    </div>
  </div>
  <div class="panel-body panel-card-section">
    <div class="media">
      <a href="{{ url('users/show/'.$row->user_id) }}" class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" title="{{ $row->users->first_name }}">
        <img src="{{ $row->users->profile_picture->src }}" height="60" width="60" alt="{{ $row->users->first_name }}">
      </a>
        
          <a href="{{ url('rooms/'.$row->id) }}" class="text-normal" target="_blank">
            <div title="{{ $row->name }}" class="h5 listing-name text-truncate row-space-top-1">
              {{ $row->name }}
            </div>
          </a>
        
      <a href="{{ url('s/?location='.$row->rooms_address->city) }}" class="text-muted text-normal">
        <div class="listing-location text-truncate">{{ $row->rooms_address->city }}, {{ $row->rooms_address->country_name }}</div>
      </a>
    </div>
  </div>
</div>

  </div>
@endforeach
</div>
    </div>
</div>
</div>
</div>
</div>
</div>

</div>

<div class="modal-container modal-transitions wl-modal__modal hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<div class="wl-modal">
<div class="row row-margin-zero">
<div class="hide-sm hide-md col-lg-7 wl-modal__col">
<div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url();">
</div>
<div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
<div class="va-container media popup-wishlist">
<img class="pull-left host-profile-img media-photo media-round space-2"  src="">
<div class="media-body va-middle">
<div class="h4 space-1 wl-modal-listing__name"></div>
<div class="wl-modal-listing__rating-container">
<span class="hide">
<div class="star-rating-wrapper">
<div class="star-rating" content="0">
<div class="foreground">
<span> </span>
</div>
<div class="background">
<span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
</span>
</div>
</div>
<span> </span>
<span class="h6 hide">
<small>
<span>(</span>
<span>
</span>
<span>)</span>
</small>
</span>
</div>
<span> · </span>
<span class="wl-modal-listing__text">
</span>
<span> · </span>
</span>
<span class="wl-modal-listing__address wl-modal-listing__text"></span>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-5 wl-modal__col">
<div class="panel-header panel-light wl-modal__header">
<div class="va-container va-container-h va-container-v">
<div class="va-middle">
<div class="pull-left h3">{{ trans('messages.wishlist.save_to_wishlist') }}</div>
<a class="modal-close wl-modal__modal-close">
</a>
</div>
</div>
</div>
<div class="wl-modal-wishlists">
<div class="panel-body panel-body-scroll wl-modal-wishlists__body wl-modal-wishlists__body--scroll">
<div class="text-lead text-gray space-4 hide">{{ trans('messages.wishlist.save_fav_list') }}</div>
<div class="wl-modal-wishlist-row clickable" ng-repeat="item in wishlist_list" ng-class="(item.saved_id) ? 'text-dark-gray' : 'text-gray'" ng-click="wishlist_row_select($index)" id="wishlist_row_@{{ $index }}">
<div class="va-container va-container-v va-container-h">
<div class="va-middle text-left text-lead wl-modal-wishlist-row__name">
<span> </span>
<span>@{{ item.name }}</span>
<span> </span>
</div>
<div class="va-middle text-right">
<div class="h3 wl-modal-wishlist-row__icons">
<i class="icon icon-heart-alt icon-light-gray wl-modal-wishlist-row__icon-heart-alt" ng-hide="item.saved_id"></i>
<i class="icon icon-heart icon-rausch wl-modal-wishlist-row__icon-heart" ng-show="item.saved_id"></i>
</div>
</div>
</div>
</div>
</div>
<div class="text-beach panel-body wl-modal-wishlists__body hide">
<small>
</small>
</div>
<div class="panel-footer wl-modal-footer clickable">
<form class="wl-modal-footer__form hide">
<strong>
<div class="pull-left text-lead va-container va-container-v">
<input type="text" class="wl-modal-footer__text wl-modal-footer__input" value="" placeholder="Name Your Wish List">
</div>
<div class="pull-right">
<button class="btn btn-flat wl-modal-wishlists__footer__save-button btn-contrast">{{ trans('messages.wishlist.create') }}</button>
</div>
</strong>
</form>
<div class="text-rausch va-container va-container-v va-container-h">
<div class="va-middle text-lead wl-modal-footer__text">{{ trans('messages.wishlist.create_new_wishlist') }}</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
@stop