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
            <div class="panel-body pad-25 bor-none padd1 ">

                <a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block btn-large row-space-1 btn-facebook font-normal pad-top">
                  <span ><i class="icon icon-facebook"></i></span>
                  <span >{{ trans('messages.login.login_with')}} Facebook</span>
                </a>

                <a href="javascript:;"  id="google_login" class="google_login g-signin2 btn icon-btn btn-block btn-large row-space-1 btn-google font-normal pad-top mr1">
                  <span ><i class="icon icon-google-plus"></i></span>
                  <span >{{ trans('messages.login.login_with')}} Google</span>
                </a>

                <!-- Hided LinkedIn
                 <a href="{{URL::to('auth/linkedin')}}" class="li-button li-blue btn icon-btn btn-block btn-large row-space-1 btn-linkedin mr1">
                  <span ><i class="icon icon-linkedin"></i></span>
                  <span >{{ trans('messages.login.login_with')}} LinkedIn</span>
                </a> -->

              <div class="signup-or-separator">
                <span class="h6 signup-or-separator--text">{{ trans('messages.login.or')}}</span>  <hr>
              </div>

              <div class="clearfix"></div>

              <form method="POST" action="{{ url('authenticate') }}" accept-charset="UTF-8" class="signup-form login-form ng-pristine ng-valid" data-action="Signin" novalidate="true"><input name="_token" type="hidden">

                <input id="from" name="from" type="hidden" value="email_login">

               <div class="control-group row-space-2 field_ico">
                 @if ($errors->has('login_email')) <p class="help-block">{{ $errors->first('login_email') }}</p> @endif
                 <div class="pos_rel">
                 <i class="icon-envelope"></i>
                  <input class="{{ $errors->has('login_email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}"  placeholder="{{ trans('messages.login.email_address') }}" id="signin_email" name="login_email" type="email" value="">
                     </div>
                </div>


                <div class="control-group row-space-3 field_ico">
                   @if ($errors->has('login_password')) <p class="help-block">{{ $errors->first('login_password') }}</p> @endif
                 <div class="pos_rel">
                  <i class="icon-lock"></i>
                  <input class="{{ $errors->has('login_password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.password') }}" id="signin_password" data-hook="signin_password" name="login_password" type="password" value="">
                 </div>
                </div>

                <div class="clearfix row-space-3">
                  <label for="remember_me2" class="checkbox remember-me">
                    <input id="remember_me2" class="remember_me" name="remember_me" type="checkbox" value="1"> {{ trans('messages.login.remember_me')}}
                  </label>
                  <a href="" class="forgot-password forgot-password-popup link_color pull-right h5">{{ trans('messages.login.forgot_pwd')}}</a>
                </div>

                <input class="btn btn-primary btn-block btn-large pad-top btn_new" id="user-login-btn" type="submit" value="{{ trans('messages.header.login') }}">
              </form>
            </div>
          <div class="panel-body bottom-panel1 text-center">  <hr>
                        {{ trans('messages.login.dont_have_account')}}
            <a href="" class="link-to-signup-in-login login-btn link_color signup_popup_head">
              {{ trans('messages.header.signup')}} </a>
          </div>
        </div>
       </div>

  </div>
</div>

    </main>
    
 
@stop


