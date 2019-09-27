@extends('template')

@section('main')

<main id="site-content" role="main">
@include('common.subheader')
<div class="page-container-responsive row-space-top-4 row-space-4">
  <div class="row">
    <div class="col-md-3 password-change-left security_password">
	@include('common.sidenav')
<!-- <div class="space-top-4 space-4">
  <a href="{{ url('invite?r=5') }}" class="btn btn-block">Invite Friends</a>
</div> -->
    </div>
    <div class="col-md-9 password-change-right">
<!-- Change Your Password -->
{!! Form::open(['url' => url('change_password'), 'class' => (Auth::user()->password) ? 'show' : 'hide']) !!}
  <div id="change_your_password" class="panel row-space-4">
    <div class="panel-header">
      {{ trans('messages.account.change_your_pwd') }}
    </div>
    <div class="panel-body">
      <input id="id" name="id" type="hidden" value="33661974">
      <input id="redirect_on_error" name="redirect_on_error" type="hidden" value="/users/security?id=33661974">
      <input id="user_password_ok" name="user[password_ok]" type="hidden" value="true">
        <div class="row">
          <div class="col-lg-7 lang-chang-label">
            <div class="row row-condensed row-space-2">
              <div class="col-md-5 text-right lang-chang-label">
                <label for="old_password">
                  {{ trans('messages.account.old_pwd') }}
                </label>
              </div>
              <div class="col-md-7 ">
                <input class="input-block" id="old_password" name="old_password" type="password">
                @if ($errors->has('old_password')) <p class="help-block text-danger">{{ $errors->first('old_password') }}</p> @endif
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-7 lang-chang-label ">
            <div class="row row-condensed row-space-2">
              <div class="col-md-5 text-right lang-chang-label">
                <label for="user_password">
                  {{ trans('messages.login.new_pwd') }}
                </label>
              </div>
              <div class="col-7">
                <input class="input-block" data-hook="new_password" id="new_password" name="new_password" size="30" type="password">
                @if ($errors->has('new_password')) <p class="help-block text-danger">{{ $errors->first('new_password') }}</p> @endif
              </div>
            </div>

            <div class="row row-condensed row-space-2">
              <div class="col-md-5 text-right lang-chang-label">
                <label for="user_password_confirmation">
                  {{ trans('messages.login.confirm_pwd') }}
                </label>
              </div>
              <div class="col-md-7">
                <input class="input-block" id="user_password_confirmation" name="password_confirmation" size="30" type="password">
                @if ($errors->has('password_confirmation')) <p class="help-block text-danger">{{ $errors->first('password_confirmation') }}</p> @endif
              </div>
            </div>
          </div>
          <div class="col-lg-5 password-strength" data-hook="password-strength"></div>
        </div>
    </div>
    <div class="panel-footer">
      <button type="submit" class="btn btn-primary">
        {{ trans('messages.account.update_pwd') }}
      </button>
    </div>
  </div>
{!! Form::close() !!}

    </div>
  </div>
</div>

    </main>

@stop
<style type="text/css">
  @media (max-width: 760px){
    .text-right{text-align: left !important;}
  }
</style>