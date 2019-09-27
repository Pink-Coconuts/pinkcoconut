@extends('template')
@section('main')
<main role="main" id="site-content">
       <div class="page-container-responsive page-container-auth" style="margin-top:40px; margin-bottom:40px;">
  <div class="row">
    <div class="col-md-7 col-lg-5 col-center">
      <div class="panel top-home">
      
        {!! Form::open(['url' => url('forgot_password')]) !!}
  <div id="forgot_password_container">
    <h3 class="log-ash-head" style="margin:0px;">
      {{ trans('messages.login.reset_pwd') }}
    </h3>
    <div class="panel-padding panel-body" style="border-top:0px !important;">
      <p>{{ trans('messages.login.reset_pwd_desc') }}</p>
      @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
      <div id="inputEmail" class="textInput text-input-container">
        {!! Form::email('email', '', ['placeholder' => trans('messages.login.email'), 'id' => 'forgot_email', 'class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore']) !!}
      </div>
    
    </div>
     <div class="panel-body bottom-panel" style="overflow: hidden;padding: 7px 20px;">
      <button id="reset-btn" class="btn btn-primary" type="submit">
        {{ trans('messages.login.send_reset_link') }}
      </button>
      </div>
  </div>
{!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

    </main>

@stop