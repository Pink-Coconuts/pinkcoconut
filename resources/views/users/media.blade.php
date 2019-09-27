@extends('template')

@section('main')

<main id="site-content" role="main">
      
 @include('common.subheader')  

 <span id="js-photo-id" class="hide">
 <div class="flash-container">
    <div class="alert alert-info" role="alert">
      <a href="#" class="alert-close"></a>
   {{trans('messages.profile.picture_remove')}}
    </div>
</div>
</span>     

<div id="notification-area"></div>
<div class="page-container-responsive space-top-4 space-4" ng-controller="user_media">
  <div class="row">
    <div class="col-md-3 lang-chang-label space-sm-4">
      <div class="sidenav">
      @include('common.sidenav')
      </div>
      <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-block row-space-top-4">{{ trans('messages.dashboard.view_profile') }}</a>
    </div>
    <div class="col-md-9">
      <div id="dashboard-content">
        
<div class="panel space-4" ng-cloak>
  <div class="panel-header">
    {{ trans('messages.profile.profile_photo') }}
  </div>
  <div class="panel-body photos-section" ng-init="original_src='{{ $result->profile_picture->getOriginal('src') }}'">
    <div class="row">
      <div class="col-lg-4 lang-chang-label text-center">
        <div data-picture-id="91711885" class="profile_pic_container picture-main space-sm-2 space-md-2">
          <div class="media-photo profile-pic-background" style="display:none;">
            <img class="user_profile_pic" width="225" height="225" title="{{ $result->first_name }}" src="{{ $result->profile_picture->src }}" alt="{{ $result->first_name }}">
          </div>
          <div class="media-photo media-round">
            <img class="user_profile_pic" width="225" height="225" title="{{ $result->first_name }}" src="{{ $result->profile_picture->src }}" alt="{{ $result->first_name }}">
          </div>
          <a href="#" class="delete_profile" ng-click="remove_profile_picture({{ $result->id }})" ng-show="original_src != ''">
            <i class="profile_delete_icon fa fa-trash" aria-hidden="true"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-8">
        <ul class="list-layout picture-tiles clearfix ui-sortable"></ul>
        <p>
          {{ trans('messages.profile.profile_photo_desc') }}
        </p>
        <div class="row row-condensed">
          <div class="col-md-6">
            <span class="btn btn-block btn-large file-input-container">
                {{ trans('messages.profile.upload_file_from_computer') }}
                
              <form name="ajax_upload_form" method="post" id="ajax_upload_form" enctype="multipart/form-data" action="{{ url('/') }}/users/image_upload" accept-charset="UTF-8">
                {!! Form::token() !!}
                  <input type="hidden" value="{{ $result->id }}" name="user_id" id="user_id">
                  <input type="file" name="profile_pic" id="user_profile_pic">
              </form>              
              <iframe style="display:none;" name="upload_frame" id="upload_frame"></iframe>
            </span>
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