@extends('template')
@section('main')
<main id="site-content" role="main">
<div class=" page-container-responsive row-space-top-8 row-space-8">
  <div class="row">
    <div class="col-4 col-center">
      <div class="panel">
        <div class="panel-header">
          {{ trans('messages.login.reset_your_pwd') }}
        </div>
        <div class="panel-body">
          {!! Form::open(['url' => url('users/set_password'), 'id' => 'password-form']) !!}
            <input id="id" name="id" type="hidden" value="{{ $result->id }}">
            <input id="token" name="token" type="hidden" value="{{ $token }}">
            <div class="row-space-1">
              {!! Form::password('password', ['id' => 'new_password', 'placeholder' => trans('messages.login.new_pwd'), 'size' => '30', 'class' => $errors->has('password') ? 'invalid' : '']) !!}
              <div data-hook="password-strength" class="password-strength">
              @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
              </div>
            </div>
            <div class="row-space-3">
              {!! Form::password('password_confirmation', ['id' => 'user_password_confirmation', 'placeholder' => trans('messages.login.confirm_pwd'), 'size' => '30', 'class' => $errors->has('password_confirmation') ? 'invalid' : '']) !!}
              @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
            </div>
            <input class="btn btn-primary btn-block btn-large" name="commit" type="submit" value="{{ trans('messages.login.save_continue') }}">
            <div class="row-space-top-2">
              <small>
                {{ trans('messages.login.reset_pwd_agree') }} <a href="{{ url('terms_of_service') }}" target="_blank">{{ trans('messages.login.terms_service') }}</a> &amp; <a href="{{ url('privacy_policy') }}" target="_blank">{{ trans('messages.login.privacy_policy') }}</a>.
              </small>
            </div>
         {!! Form::close() !!}
      </div>
      </div>
    </div>
  </div>
</div>
</main>
@stop