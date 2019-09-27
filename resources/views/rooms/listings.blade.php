@extends('template')

@section('main')
    <main id="site-content" role="main">
    
    @include('common.subheader')  

  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3 col-sm-12  lang-chang-label">
        
        @include('common.sidenav')

      </div>

      <div class="col-md-9 col-sm-12 ">
        <div class="your-listings-flash-container"></div>
            <div id="listings-container">
            <div>
            <div class="suspension-container">
            <div class="suspension-overlay hide"></div>

    @if($listed_result->count() > 0)
        <div class="panel row-space-4">
        <div class="panel-header active-panel-header">
        <div class="row">
        <div class="col-12 active-panel-padding lang-chang-label">{{ trans('messages.your_listing.listed') }}</div>

        <div id="ib-master-switch-container" class="col-6"></div>
        </div>
        </div>
        <ul class="list-unstyled list-layout">
        @foreach($listed_result as $row)
        <li class="listing panel-body">
        <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 list_reserve_img your_listing_image">
        <a href="{{ url('rooms/'.$row->id) }}">
        <div class="media-photo media-photo-block listing_profile_coverimg">
        <div class="media-cover text-center media_cover_img">
        {!! Html::image($row->photo_name, '', ['class' => 'img-responsive-height']) !!}
        </div>
        </div>
        </a>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-8 list_reserve your_resrvation_list">
        <span class="h4">
        <a href="{{ url('rooms/'.$row->id) }}" class="text-normal">{{ ($row->name == '') ? $row->sub_name : $row->name }}</a>
        </span>
        <div class="actions row-space-top-1">
        <a class="listing-link-space" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ trans('messages.your_listing.manage_listing_calendar') }}</a>
        </div>
        <div class="listing-criteria-header activation-notification hide-lg hide-md space-top-2">
        <div class="listing-criteria-header-message">
        @if($row->steps_count != 0)
        <a class="btn btn-host step_count" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}</a>
        @else
        <div id="availability-dropdown" data-room-id="div_{{ $row->id }}">
        <i class="dot row-space-top-2 col-top dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>&nbsp;
        <div class="select">
        <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
            <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>{{ trans('messages.your_listing.listed') }}</option>
            <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
        </select>
        </div>
        </div>
        @endif
        </div>
        </div>
        </div>
        <div class="col-lg-3 col-md-3 text-right">
        <div class="listing-criteria-header activation-notification show-lg show-md">
        <div class="listing-criteria-header-message">
        @if($row->steps_count != 0)
        <a class="btn btn-host step_count" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}</a>
        @else
        <div id="availability-dropdown" data-room-id="div_{{ $row->id }}">
        <i class="dot row-space-top-2 col-top dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>&nbsp;
        <div class="select">
        <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
            <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>{{ trans('messages.your_listing.listed') }}</option>
            <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
        </select>
        </div>
        </div>
        @endif
        </div>
        </div>
        </div>
        </div>
        </li>
        @endforeach
        </ul>
        </div>
    @endif

       @if($unlisted_result->count() > 0)
        <div class="panel row-space-4">
        <div class="panel-header active-panel-header">
        <div class="row">
        <div class="col-12 active-panel-padding">{{ trans('messages.your_listing.unlisted') }}</div>
        <div id="ib-master-switch-container" class="col-6"></div>
        </div>
        </div>
        <ul class="list-unstyled list-layout">
        @foreach($unlisted_result as $row)
        <li class="listing panel-body">
        <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 list_reserve_img your_listing_image">
        <a href="{{ url('rooms/'.$row->id) }}">
        <div class="media-photo media-photo-block listing_profile_coverimg">
        <div class="media-cover text-center media_cover_img">
        {!! Html::image($row->photo_name, '', ['class' => 'img-responsive-height']) !!}
        </div>
        </div>
        </a>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-8 list_reserve your_resrvation_list">
        <span class="h4">
        <a href="{{ url('rooms/'.$row->id) }}" class="text-normal">{{ ($row->name == '') ? $row->sub_name : $row->name }}</a>
        </span>
        <div class="actions row-space-top-1">
        <a href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ trans('messages.your_listing.manage_listing_calendar') }}</a>
        </div>
        <div class="listing-criteria-header activation-notification hide-lg hide-md space-top-2">
        <div class="listing-criteria-header-message">
        @if($row->steps_count == 0 && $row->status == NULL)
            <a class="btn btn-host" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ trans('messages.your_listing.pending') }}</a>
        @elseif($row->steps_count != 0)
            <a class="btn btn-host step_count" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}</a>
        @else
        <div id="availability-dropdown" data-room-id="div_{{ $row->id }}">
        <i class="dot row-space-top-2 col-top dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>&nbsp;
        <div class="select">
        <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
            <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>{{ trans('messages.your_listing.listed') }}</option>
            <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
        </select>
        </div>
        </div>
        @endif
        </div>
        </div>
        </div>
        <div class="col-lg-3 col-md-3 text-right">
        <div class="listing-criteria-header activation-notification show-lg show-md">
        <div class="listing-criteria-header-message">
        @if($row->steps_count == 0 && $row->status == NULL)
            <a class="btn btn-host" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ trans('messages.your_listing.pending') }}</a>
        @elseif($row->steps_count != 0)
            <a class="btn btn-host step_count" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}</a>
        @else
        <div id="availability-dropdown" data-room-id="div_{{ $row->id }}">
        <i class="dot row-space-top-2 col-top dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>&nbsp;
        <div class="select">
        <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
            <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>{{ trans('messages.your_listing.listed') }}</option>
            <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
        </select>
        </div>
        </div>
        @endif
        </div>
        </div>
        </div>
        </div>
        </li>
        @endforeach
        </ul>
        </div>
    @endif  



    @if($unlisted_result->count() == 0 && $listed_result->count() == 0)

    <div class="panel">
    <div class="panel-body">
      <p>
          {{ trans('messages.your_listing.no_listings') }}
      </p>
        <a href="{{ url('/') }}/rooms/new" class="hostverification btn btn-special list-your-space-btn" id="list-your-space">{{ trans('messages.your_listing.add_new_listings') }}</a>
    </div>
    </div>

    @endif

            </div>
            </div>
            </div>
  </div>

      </div>
   </div> 
    </main>
@stop