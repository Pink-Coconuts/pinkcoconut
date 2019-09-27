@extends('template')

@section('main')

<main id="site-content" role="main">
      
 @include('common.subheader')  
      
<div id="notification-area"></div>
<div class="page-container-responsive space-top-4 space-4" ng-controller="verification_controller" ng-cloak>
  <div class="row">
    <div class="col-md-3 lang-chang-label space-sm-4">
      <div class="sidenav">
      @include('common.sidenav')
      </div>
      <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-block row-space-top-4">{{ trans('messages.dashboard.view_profile') }}</a>
    </div>
    <div class="col-md-9">
      
      <div id="dashboard-content" ng-init="id_verification_status ='{{Auth::user()->id_document_verification_status}}';">
@if(Auth::user()->users_verification->email != 'no' || Auth::user()->users_verification->facebook != 'no' || Auth::user()->users_verification->google != 'no' || Auth::user()->users_verification->linkedin != 'no' || Auth::user()->verification_status == 'Verified')
<div class="panel verified-container">
  <div class="panel-header">
    {{ trans('messages.profile.current_verifications') }}
  </div>
  <div class="panel-body">
      <ul class="list-layout edit-verifications-list">
        @if(Auth::user()->verification_status == 'Verified')
      <li class="email unverified row-space-4 clearfix">
        <div class="row">
          <div class="col-7 lang-chang-label">
            <h4>
              {{ trans('messages.profile.id_verification') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
            </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-12 lang-chang-label">
            <div class="row">
            <div class="col-6">
            <h4 class="verified_head" >
              {{ trans('messages.profile.id_document') }}
            </h4>
          </div>
            <div class="col-6" ng-if="id_verification_status != ''">
              <div class="connect-button">
                <span class="btn btn-block large email-button" style="cursor:default">
                  @{{ id_verification_status }}
                </span>
              </div>
            </div>
        </div>
            <div class="row">
            <div class="col-6">
              <div class="id_document_upload">
                
                  <div class="col-md-12" ng-show="id_verification_status != 'Verified'">
                    <button class="upload_btn btn btn-block" onclick="$('#id_document').trigger('click');"> {{ trans('messages.profile.upload_document') }} <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    </button>
                    <input class="upload_photos" type="file" ng-model="id_documents" style="display:none" accept="image/*" id="id_document" name='id_document' onchange="angular.element(this).scope().upload_verification_documents(this,'id_document')" />
                  </div>
                </div>
            </div>
            
          </div>
            <div class="">
              <div class="row row-space-top-4 id_document_slider1 clearfix">
                  <div class="item col-md-3 col-sm-4 col-xs-12" ng-repeat="photos in id_documents">
                      <img ng-src="@{{ photos.src }}" >
                      <!-- <button type="button" data-photo-id="@{{ photos.id }}" ng-click="delete_document(photos,photos.id,'id_document','{{ trans('messages.profile.delete_document') }}','{{ trans('messages.profile.delete_document_desc') }}')" ng-show="id_verification_status != 'Verified'">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                      </button> -->
                      <a href="@{{photos.download_src }}"  download="@{{photos.name }}" class="delete_but_edit">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </li>
       @endif

@if(Auth::user()->users_verification->email == 'yes')
        <li class="edit-verifications-list-item clearfix email verified">
          <h4>{{ trans('messages.dashboard.email_address') }}</h4>
          <p class="description">{{ trans('messages.profile.you_have_confirmed_email') }} <b>{{ Auth::user()->email }}</b>.  {{ trans('messages.profile.email_verified') }}
        </p></li>
        @endif

@if(Auth::user()->users_verification->phone_number == 'yes')
        <li class="edit-verifications-list-item clearfix email verified">
          <h4>{{ trans('messages.profile.phone_number') }}</h4>
          <p class="description">{{ trans('messages.profile.you_have_confirmed_phone') }} <b>{{ Auth::user()->primary_phone_number_protected }}</b>.
        </p></li>
        @endif

@if(Auth::user()->users_verification->facebook == 'yes')
        <li class="edit-verifications-list-item clearfix google verified">
          <h4>Facebook</h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.facebook_verification') }}
    </p>
  </div>
    <div class="col-5">
      <div class="disconnect-button-container">
        <a href="{{ url('facebookDisconnect') }}" class="btn gray btn-block" data-method="post" rel="nofollow">{{ trans('messages.profile.disconnect') }}</a>
      </div>
    </div>
</div>
        </li>
        @endif
@if(Auth::user()->users_verification->google == 'yes')
        <li class="edit-verifications-list-item clearfix google verified">
          <h4>Google</h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
    </p>
  </div>
    <div class="col-5">
      <div class="disconnect-button-container">
        <a href="{{ url('googleDisconnect') }}" class="btn gray btn-block" data-method="post" rel="nofollow">{{ trans('messages.profile.disconnect') }}</a>
      </div>
    </div>
</div>
        </li>
        @endif
@if(Auth::user()->users_verification->linkedin == 'yes')
        <li class="edit-verifications-list-item clearfix google verified">
          <h4>LinkedIn</h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.linkedin_verification', ['site_name'=>$site_name]) }}
    </p>
  </div>
    <div class="col-5">
      <div class="disconnect-button-container">
        <a href="{{ url('linkedinDisconnect') }}" class="btn gray btn-block" data-method="post" rel="nofollow">{{ trans('messages.profile.disconnect') }}</a>
      </div>
    </div>
</div>
        </li>
    @endif
      </ul>
  </div>
</div>
@endif

@if(Auth::user()->users_verification->email != 'yes' || Auth::user()->users_verification->facebook != 'yes' || Auth::user()->users_verification->google != 'yes' || Auth::user()->users_verification->linkedin != 'yes'  || Auth::user()->verification_status != 'Verified')
<div class="panel row-space-top-4 unverified-container">
  <div class="panel-header">
    {{ trans('messages.profile.add_more_verifications') }}
  </div>
  <div class="panel-body">
    <ul class="list-layout edit-verifications-list">
      @if(Auth::user()->verification_status != 'Verified')
      <li class="email unverified row-space-4 clearfix">
        <div class="row">
          <div class="col-7 lang-chang-label">
            <h4>
              {{ trans('messages.profile.id_verification') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
            </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-12 lang-chang-label">
              <div class="row">
            <div class="col-6">
             
             <h4 class="verified_head">
              {{ trans('messages.profile.id_document') }}
            </h4>
          </div>
            <div class="col-6">
              <div  ng-if="id_verification_status != '' && id_verification_status == 'Verified'">
              <div class="connect-button">
                <span class="btn btn-block large email-button" style="cursor:default">
                  @{{ id_verification_status }}
                </span>
              </div>
            </div>
            </div>
        </div>

            <div class="row">
            <div class="col-6">
             
              <div class="id_document_upload">
                  <div class="" ng-if="id_verification_status != 'Verified'">
                    <button class="upload_btn btn btn-block" onclick="$('#id_document').trigger('click');"> {{ trans('messages.profile.upload_document') }} <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    </button>                   
                    <input class="upload_photos" type="file" ng-model="id_documents" style="display:none" accept="image/*" multiple="true" id="id_document" name='id_document[]' onchange="angular.element(this).scope().upload_verification_documents(this,'id_document')" />
                  </div>
                 
                  
                </div>
                <div class="doc_error" style="color: red;display: none;">
                  
                </div>
                </div>
            
            <div class="col-6" ng-if="id_verification_status != '' && id_verification_status != 'Verified'">
              <div class="connect-button">
                <span class="btn btn-block large email-button" style="cursor:default">
                  @{{ id_verification_status }}
                </span>
              </div>
            </div>

            </div>
            <div class="row">
            <div class="col-md-12">
               <div class="row row-space-top-4 id_document_slider1 clearfix">
                  <div class="item item-@{{ photos.id }} col-md-3 col-sm-4 col-xs-12" ng-repeat="photos in id_documents">
                      <img ng-src="@{{ photos.src }}" >
                      <button type="button" data-photo-id="@{{ photos.id }}" ng-click="delete_document(photos,photos.id,'id_document','{{ trans('messages.profile.delete_document') }}','{{ trans('messages.profile.delete_document_desc') }}')" ng-show="id_verification_status != 'Verified'" class="delete_but_edit">
                      <i class="fa fa-trash js-delete-id-confirm" aria-hidden="true"></i>
                      </button>
                      <a href="@{{photos.download_src }}"  download="@{{photos.name }}" class="delete_but_edit">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12" ng-if='!id_documents.length'>
              <div class="image_frnt_upload">
                {{ trans('messages.profile.no_photos_uploaded') }}
              </div>
            </div>
          </div>
        </div>
      </li>
       @endif

    @if(Auth::user()->users_verification->email == 'no')
        <li class="email unverified row-space-4 clearfix">
          <h4>
            {{ trans('messages.login.email') }}
          </h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.email_verification') }} <b>{{ Auth::user()->email }}</b>.
    </p>
  </div>

    <div class="col-5">
      <div class="connect-button">
        <a href="{{ url('users/request_new_confirm_email?redirect=verification') }}" class="btn btn-block large email-button">{{ trans('messages.profile.connect') }}</a>
      </div>
    </div>
</div>

        </li>
@endif

    @if(Auth::user()->users_verification->facebook == 'no')
        <li class="facebook unverified row-space-4 clearfix">
          <h4>
            Facebook
          </h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
     {{ trans('messages.profile.facebook_verification') }}
    </p>
  </div>

    <div class="col-5">
      <div class="connect-button">

        <a href="{{ $fb_url }}" class="btn btn-block large facebook-button">{{ trans('messages.profile.connect') }}</a>
      </div>
    </div>
</div>

        </li>
@endif

    @if(Auth::user()->users_verification->google == 'no')
        <li class="google unverified row-space-4 clearfix">
          <h4>
            Google
          </h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
    </p>
  </div>
      <div class="col-5">
        <div class="connect-button">
          <a class="btn btn-block large" id="google_connect" href="javascript:;">
            {{ trans('messages.profile.connect') }}
          </a>
        </div>
      </div>
</div>
        </li>
@endif

    @if(Auth::user()->users_verification->linkedin == 'no')
        <li class="linkedin unverified row-space-4 clearfix">
          <h4>
            LinkedIn
          </h4>
          <div class="row">
  <div class="col-7 lang-chang-label">
    <p class="description verification-text-description">
      {{ trans('messages.profile.linkedin_verification', ['site_name'=>$site_name]) }}
    </p>
  </div>
      <div class="col-5">
        <div class="connect-button">
          <a class="btn btn-block" href="{{URL::to('linkedinLoginVerification')}}">{{ trans('messages.profile.connect') }}</a>
        </div>
      </div>
</div>
        </li>
@endif

    </ul>
  </div>
</div>
@endif
</div>

    </div>
  </div>
</div>

    </main>

@stop