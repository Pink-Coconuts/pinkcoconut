@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">

<div class="app_view">

  @include('common.wishlists_subheader')

  <div class="wishlists-container page-container-responsive row-space-top-4">
  <div class="index_view">

  <div class="row wishlists-body">
@if($result->count() == 0)
<h2 class="text-center" style="margin-top:10%">
{{ trans('messages.search.no_results_found') }}!
</h2>
@endif
    @foreach($result as $row)
      <div class="col-4 row-space-4">
      <div class="panel">
  <a href="{{ url('wishlists/'.$row->id) }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
    <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('{{ @$row->saved_wishlists[0]->photo_name }}');">
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
    @endforeach
  </div>
</div>
</div>
</div>
</div>
</div>
</main>
@stop