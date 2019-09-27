@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists" ng-cloak>

<div class="app_view">

  @include('common.wishlists_subheader')

  <div class="wishlists-container page-container-responsive row-space-4 row-space-top-4">
  <div class="index_view">
  <div class="top-bar row row-table row-space-4">
    <div class="col-6 col-middle ">
        <div class="media whishlist_category wishlist-page">
  <a href="{{ url('/') }}/users/show/{{ $user->id }}" class="lang-chang-label img-pad pull-left media-photo media-round" title="{{ $user->first_name }}">
    <img src="{{ $user->profile_picture->src }}" alt="{{ $user->first_name }}" >
  </a>
  <div class="media-body">
    <div class="row row-table wishlist-header-text">
      <div class="col-12 col-middle">
        <a href="" class="h4">
          {{ $user->first_name }}’s {{ trans_choice('messages.header.wishlist', 2) }}
        </a>
        <div>
          <span class="text-muted whishlist_name">{{ trans_choice('messages.wishlist.wishlist', $result->count()) }}:</span>
          <strong>{{ $result->count() }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="col-6 col-middle text-right lang-text whishlist_button">
    @if($owner)
      <div class="btn-group social-share-widget-container hide"></div>
      <div class="btn-group">
        <button class="btn pull-right create">{{ trans('messages.wishlist.create_new_wishlist') }}</button>
      </div>
      @endif
    </div>
  </div>

  <div class="row wishlists-body">
    @foreach($result as $row)
    @if(trim($row->name) !='')
      <div class="col-4 row-space-4">
      <div class="panel">
  <a href="{{ url('wishlists/'.$row->id) }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
    <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('{{@$row->saved_wishlists[0]->photo_name }}');">
    </div>
    <div class="row row-table row-full-height">
      <div class="col-12 col-middle text-contrast">
        <div class="count_section">
          <div class="">
            @if($row->privacy)
            <i class="icon icon-lock h3"></i>
            @endif
            <div class=""><strong>{{ $row->name }}</strong></div>
          </div>
          <span class="">
            @if($row->rooms_count > 0)
            {{ $row->rooms_count }} {{ trans('messages.header.home') }}
            @endif
            @if($row->rooms_count > 0 && $row->host_experience_count > 0)
            .
            @endif
            @if($row->host_experience_count > 0)
            {{ $row->host_experience_count }} {{ trans_choice('messages.home.experience',$row->host_experience_count) }}
            @endif
          </span>
        </div>
      </div>
    </div>
  </a>
</div>
      </div>
      @endif
    @endforeach
  </div>
</div>
</div>
  <div class="wl-preload" style="display: none;">
    <div class="page-container">
      <div class="row">
        <div class="col-12">
          <p class="wl-loading">{{ trans('messages.wishlist.loading') }}…</p>
        </div>
      </div>
    </div>
  </div>
  <div class="loading-indicator wishlist-loading-indicator loading"></div>
</div>
<div class="modal-container modal-transitions hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content" tabindex="-1">
<form action="{{ url('create_new_wishlist') }}" method="post">
{!! Form::token() !!}
<header class="panel-header">
<span>{{ trans('messages.wishlist.create_new_wishlist') }}</span>
</header>
<div class="loading1">
<div class="panel-body">
<label for="wishlistName">
<span>{{ trans('messages.wishlist.wish_list_name') }}</span>
</label>
<input type="text" value="" id="wishlistName" name="name" required>
<label class="row-space-top-2">
<span>{{ trans('messages.wishlist.privacy_settings') }}</span>
</label>
<div class="row row-table">
<div class="col-4 col-middle">
<div class="select select-block" id="wishlist-edit-privacy-setting">
<select name="privacy">
<option selected="" value="0">{{ trans('messages.wishlist.everyone') }}</option>
<option value="1">{{ trans('messages.wishlist.only_me') }}</option>
</select>
</div>
</div>
<div class="col-8 col-middle">
</div>
</div>
</div>
<div class="panel-footer">
<button class="btn save btn-primary" disabled type="submit">
<span>{{ trans('messages.profile.save') }}</span>
</button>
<button class="btn cancel">
<span>{{ trans('messages.your_reservations.cancel') }}</span>
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
{!! Html::script('js/jquery-1.11.3.js') !!}

<script type="text/javascript">
   $(document).ready(function() {
      var f=$('.get_text').val(); 
      if(f=''){ 
        $('.get_button').attr('disabled', true);
      }else
      {
        $('.get_button').attr('disabled', false);
      }

      $('#wishlistName').keyup(function(){
      var wishlistnamelength = $(this).val().trim().length;
      wishlistnamelength >= 1 ? $('.save').removeAttr('disabled') : $('.save').attr('disabled','disabled');
      });


   });

</script>
<style>
#wishlistName{
  background-color: white;
  border-color: #c4c4c4;
}
</style>