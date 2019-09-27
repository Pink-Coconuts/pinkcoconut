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
            <div class="col-md-7 col-lg-7 col-center">
                <div class="panel top-home bor-none">
                    <div class="alert alert-with-icon alert-error alert-header panel-header hidden-element notice" id="notice">
                        <i class="icon alert-icon icon-alert-alt"></i>
                    </div>
                    <div class="log-ash-head">
                        <p>{{trans('messages.login.complete_info')}}</p>
                        <h6>{{trans('messages.login.provide_miss_info')}}</h6>
                    </div>
                    <div class="panel-padding panel-body pad-25">
                        <div class="text-center">
                            <a href="{{URL::to('/')}}/signup_login?sm=2" class="create-using-email btn-block  row-space-2  icon-btn hide" id="create_using_email_button">
                                <i class="icon icon-envelope"></i>
                                {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
                            </a>    
                        </div>
                        @if(@$user['fb_id'])
                        {!! Form::open(['action' => 'UserController@finish_signup_email', 'class' => 'signup-form', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
                        @else
                        {!! Form::open(['action' => 'UserController@finish_signup_linkedin_email', 'class' => 'signup-form', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
                        @endif
                        <div class="signup-form-fields">
                            {!! Form::hidden('from', 'email_signup', ['id' => 'from']) !!}
                            {!! Form::hidden('fb_id', @$user['fb_id'], ['id' => 'fb_id']) !!}
                             {!! Form::hidden('linkedin_id', @$user['linkedin_id'], ['id' => 'linkedin_id']) !!}
                            {!! Form::hidden('profile_pic', @$user['profile_pic'], ['id' => 'profile_pic']) !!}
                            <div class="control-group row-space-1" id="inputFirst">
                                @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                                {!! Form::text('first_name', $user['first_name'], ['class' =>  $errors->has('first_name') ? 'decorative-input invalid ' : 'decorative-input name-icon', 'placeholder' => trans('messages.login.first_name')]) !!}
                            </div>
                            <div class="control-group row-space-1" id="inputLast">
                                @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                                {!! Form::text('last_name', $user['last_name'], ['class' => $errors->has('last_name') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon', 'placeholder' => trans('messages.login.last_name')]) !!}
                            </div>
                            <div class="control-group row-space-1" id="inputEmail">
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                {!! Form::email('email', '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-mail name-icon', 'placeholder' => trans('messages.login.email_address')]) !!}
                            </div>
                            <div class="control-group row-space-top-3 row-space-1">
                                <strong>{{ trans('messages.login.birthday') }}</strong>
                                <strong data-behavior="tooltip" aria-label="To sign up, you must be 18 or older. Other people won’t see your birthday." style="position:relative;">
                                    <i class="icon icon-question  tool-amenity2"></i>
                                    <div class="tooltip-amenity tooltip-amenity2 tooltip-bottom-middle" data-sticky="true" aria-hidden="true" style="left: -63px;top: -108px;">
                                        <dl class="panel-body">
                                            <dt>{{ trans('messages.login.birthday_message') }} </dt>
                                        </dl>
                                    </div>
                                </strong>
                            </div>
                            <div class="control-group row-space-1" id="inputBirthday"></div>
                            @if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) <p class="help-block"> {{ $errors->has('birthday_day') ? $errors->first('birthday_day') : ( $errors->has('birthday_month') ? $errors->first('birthday_month') : $errors->first('birthday_year') ) }} </p> @endif
                            <div class="control-group row-space-2">
                                <div class="select month">
                                    {!! Form::selectMonthWithDefault('birthday_month', null, trans('messages.header.month'), [ 'class' => $errors->has('birthday_month') ? 'invalid' : '', 'id' => 'user_birthday_month']) !!}
                                </div>
                                <div class="select day month">
                                    {!! Form::selectRangeWithDefault('birthday_day', 1, 31, null, trans('messages.header.day'), [ 'class' => $errors->has('birthday_day') ? 'invalid' : '', 'id' => 'user_birthday_day']) !!}
                                </div>
                                <div class="select month">
                                    {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, null, trans('messages.header.year'), [ 'class' => $errors->has('birthday_year') ? 'invalid' : '', 'id' => 'user_birthday_year']) !!}
                                </div>
                            </div>
                            @if(@$user['fb_id'])
                            <p class="text-center">{{trans('messages.login.info_from_fb')}}</p>
                            @else
                            <p class="text-center">{{trans('messages.login.info_from_linkedin')}}</p>
                            @endif
                            <div class="control-group row-space-top-3 row-space-1">
                                {!! Form::submit(trans("messages.login.finish_signup"), ['class' => 'btn btn-primary btn-block btn-large ' , 'id' => '', 'style' => 'float:none;'])  !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</main>
@stop