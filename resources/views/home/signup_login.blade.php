  <style type="text/css">
    .btn-large {
    padding: 20px 27px !important;
    }
  </style>
@extends('template')

@section('main')

    <main id="site-content" role="main">
      

<div class="page-container-responsive page-container-auth margintop" style="margin-top:40px;margin-bottom:40px;">
  <div class="row">
    
        <div class="log_pop col-center">          
          <div class="panel top-home">
          <!--<div class="login-close">
          <img src="images/close.png">
          </div>-->
            <div class="alert alert-with-icon alert-error alert-header panel-header hidden-element notice" id="notice">
              <i class="icon alert-icon icon-alert-alt"></i>
            </div>
            <div class="panel-padding panel-body pad-25 padd1">
              <div class="social-buttons">
                <a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block row-space-1 btn-large btn-facebook pad-23" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate">
                  <span>
                    <i class="icon icon-facebook"></i>
                  </span>
                  <span >
                    {{ trans('messages.login.signup_with') }} Facebook
                  </span>
                </a>

      
                <a href="javascript:;" id="google_login" class="btn icon-btn btn-block row-space-1 btn-large btn-google pad-23">
                  <span>
                    <i class="icon icon-google-plus"></i>
                  </span>
                  <span >
                    {{ trans('messages.login.signup_with') }} Google
                  </span>
                </a>
               <!--  Hided LinkedIn
                <a href="{{URL::to('auth/linkedin')}}" class="li-button li-blue btn icon-btn btn-block btn-large row-space-1 btn-linkedin">
                  <span >
                    <i class="icon icon-linkedin"></i>
                  </span>
                  <span >
                    {{ trans('messages.login.signup_with') }} LinkedIn
                  </span>
                </a> -->
              </div>

              <div class="text-center social-links hide">
                {{ trans('messages.login.signup_with') }} <a href="{{ $fb_url }}" class="facebook-link-in-signup">Facebook</a> {{ trans('messages.login.or') }} <a href="{{URL::to('googleLogin')}}">Google</a>
              </div>

              <div class="signup-or-separator">
                <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
                <hr>
              </div>

              <div class="text-center">
                <a href="" class="create-using-email btn-block  row-space-2 btn btn-primary btn-block btn-large large icon-btn pad-23 signup_popup_head2 btn_new1" id="create_using_email_button">
                <span >
                  <i class="icon icon-envelope"></i>
                </span>
                <span >
                  {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
                </span>
                </a>
              </div>

            <div id="tos_outside" class="row-space-top-3">
              <small class="small-font style1">
                {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s 
                @foreach($company_pages as $company_page)
        <a href="{{ url($company_page->url) }}" class="link_color" data-popup="true">,{{ $company_page->name }}</a>
      @endforeach 
              </small>
            </div>
          </div>

          <div class="panel-body bottom-panel1 text-center">
            <hr>
            {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
            <a href="{{ url('login') }}" class="modal-link link-to-login-in-signup login-btn login_popup_head link_color" data-modal-href="/login_modal?" data-modal-type="login">
              {{ trans('messages.header.login') }}
            </a>
          </div>
        </div>
      </div>
    
  </div>
</div>    </main>
    
 @stop