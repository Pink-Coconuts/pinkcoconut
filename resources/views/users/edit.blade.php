@extends('template')

@section('main')

<main id="site-content" role="main" ng-controller="edit_profile">
      
 @include('common.subheader')  
      
<div id="notification-area"></div>
<div class="page-container-responsive space-top-4 space-4">
  <div class="row">
    <div class="col-md-3 lang-chang-label space-sm-4">
      <div class="sidenav">
      @include('common.sidenav')
      </div>
      <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-block row-space-top-4">{{ trans('messages.dashboard.view_profile') }}</a>
    </div>
    <div class="col-md-9">
      
      <div id="dashboard-content">
        
{!! Form::open(['url' => url('users/update/'.Auth::user()->id), 'id' => 'update_form']) !!}

<div class="panel row-space-4">
  <div class="panel-header">
    {{ trans('messages.profile.required') }}
  </div>
  <div class="panel-body">
          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_first_name">
          {{ trans('messages.profile.first_name') }} 
        </label>
        <div class="col-sm-9">

      {!! Form::text('first_name', Auth::user()->first_name, ['id' => 'user_first_name', 'size' => '30', 'class' => 'focus']) !!}
      <span class="text-danger">{{ $errors->first('first_name') }}</span>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_last_name">
          {{ trans('messages.profile.last_name') }}
        </label>
        <div class="col-sm-9">
          
      {!! Form::text('last_name', Auth::user()->last_name, ['id' => 'user_last_name', 'size' => '30', 'class' => 'focus']) !!}
      <span class="text-danger">{{ $errors->first('last_name') }}</span>
          <div class="text-muted row-space-top-1">{{ trans('messages.profile.last_name_never_share', ['site_name'=>$site_name]) }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_gender">
          {{ trans('messages.profile.i_am') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">
          
      <div class="select">
        {!! Form::select('gender', ['Male' => trans('messages.profile.male'), 'Female' => trans('messages.profile.female'), 'Other' => trans('messages.profile.other')], Auth::user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus']) !!}
      </div>
          <span class="text-danger">{{ $errors->first('gender') }}</span>
          <div class="text-muted row-space-top-1">{{ trans('messages.profile.gender_never_share') }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_birthdate">
          {{ trans('messages.profile.birth_date') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">

      <div class="select">
      {!! Form::selectMonthWithDefault('birthday_month', (Auth::user()->dob==""?"":(int)$dob[1]), trans('messages.header.month'), ['id' => 'user_birthday_month', 'class' => 'focus']) !!}
      </div>

      <div class="select">
      {!! Form::selectRangeWithDefault('birthday_day', 1, 31, (Auth::user()->dob==""?"":(int)$dob[2]), trans('messages.header.day'), ['id' => 'user_birthday_day', 'class' => 'focus']) !!}
      </div>

      <div class="select">
      {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, (Auth::user()->dob==""?"":$dob[0]), trans('messages.header.year'), ['id' => 'user_birthday_year', 'class' => 'focus']) !!}
      </div>
          <span class="text-danger">@if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) {{ $errors->has('birthday_day') ? $errors->first('birthday_day') : ( $errors->has('birthday_month') ? $errors->first('birthday_month') : $errors->first('birthday_year') ) }} @endif</span>
          <div class="text-muted row-space-top-1">{{ trans('messages.profile.birth_date_never_share') }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_email">
          {{ trans('messages.dashboard.email_address') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">
          
          {!! Form::text('email', Auth::user()->email, ['id' => 'user_email', 'size' => '30', 'class' => 'focus']) !!}
          <span class="text-danger">{{ $errors->first('email') }}</span>
          <div class="text-muted row-space-top-1">{{ trans('messages.profile.email_never_share', ['site_name'=>$site_name]) }}</div>
        </div>
      </div>
      
  </div>
</div>


<div class="panel row-space-4">
  <div class="panel-header">
    {{ trans('messages.profile.optional') }}
  </div>
  <div class="panel-body">
    <div class="row row-condensed space-4">

        <label class="text-right col-sm-3 lang-chang-label" >
          @lang('messages.profile.phone_number')<i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>

        <div class="col-sm-9 phn-cross" id="phone_numbers_wrapper" ng-cloak>
          <input type="hidden" ng-model="default_phone_code" ng-init="default_phone_code ='{{$country_phone_codes[0]->phone_code}}'" />
          <div class="phone-number-block" ng-repeat="phone_number in users_phone_numbers" >
            <div class="phone-status-block" ng-show="phone_number.status == 'Confirmed'">
              <input type="text" name="phone" class="focus width-30 " readonly value="@{{phone_number.phone_number_protected}}">
              <label class="focus width-70" style="background:none;" id="phone_number_status"><span class="confirm-tick"><i class="icon icon-ok" aria-hidden="true"></i></span>@{{phone_number.phone_number_status_message}} <a style="cursor: pointer; background: url(../images/drag_cross_67_16.png); background-repeat: no-repeat; background-size: 12px; padding: 7px; float:right;" id="remove_phone_number" ng-click="remove_phone_number($index)" href="javascript:void(0);"></a></label>
            </div>

            <div class="phone-number-verify-widget sms-verify col-lg-8 col-md-12 col-xs-12" ng-show="phone_number.status == 'Null'" >
              <p class="pnaw-verification-error"></p>
              <div class="pnaw-step1">
                <div class="phone-number-input-widget" id="phone-number-input-widget-c5c92311">
                  <label for="phone_country">@lang('messages.profile.choose_a_country'):</label>
                  <div class="select">
                    <select id="phone_country" name="phone_country" style="margin:0 0 15px !important;" ng-model="phone_code_val[$index]" ng-init="phone_code_val[$index] = default_phone_code">
                      @foreach($country_phone_codes as $k => $country)
                        <option value="{{$country->phone_code}}" >{{$country->long_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <label for="phone_number">@lang('messages.profile.add_a_phone_number'):</label>
                  <div class="pniw-number-container clearfix">
                    <div class="pniw-number-prefix">+ @{{phone_code_val[$index]}}</div>
                    <input type="tel" ng-model="phone_number_val[$index]" class="pniw-number" id="phone_number">
                  </div>
                </div>
                <div class="pnaw-verify-container">
                  <a class="btn btn-primary sms-btn" ng-click="update_phone_number($index)" href="javascript:void(0);" rel="sms">@lang('messages.profile.verify_via_sms')</a>
                </div>
                <p class="text-danger">@{{phone_number.phone_number_error}}</p>
              </div>
            </div>
            
            <div class="phone-number-verify-widget verify" ng-show="phone_number.status == 'Pending'">
              <div class="pnaw-step2" style="display: block;">
                <p class="message">@lang('messages.profile.we_sent_verification')<strong>@{{phone_number.phone_number_full}}</strong>.</p>
                <label for="phone_number_verification">@lang('messages.profile.please_enter_ver_code'):</label>
                <input type="text" pattern="[0-9]*"  ng-model="otp_val[$index]" id="phone_number_verification">
                <div class="pnaw-verify-container">
                  <a class="btn btn-primary" href="javascript:void(0);" ng-click="verify_phone_number($index)"  rel="verify">
                    @lang('messages.profile.verify')
                  </a>
                  <a class="cancel" rel="cancel" href="javascript:void(0);" ng-click="remove_phone_number($index)" >
                    @lang('messages.profile.cancel')
                  </a>
                </div>
                <p class="cancel-message text-danger">@{{phone_number.otp_error}}</p>
                <p class="cancel-message hide">If it doesn't arrive, click cancel and try call verification instead.</p>
              </div>
            </div>
          </div>
          <div class="phone-number-add-block col-sm-12">
            <a class="add-phn" ng-click="add_phone_number()"> <span style="padding-top:0px;">+</span>@lang('messages.profile.add_phone_number')</a>
          </div>
          <div class="text-muted row-space-top-1" style="float:left;">{{ trans('messages.profile.phone_nuber_share_text',['site_name'=>$site_name]) }} </div>
        </div>

      </div> 

      <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_live">
          {{ trans('messages.profile.where_you_live') }}
        </label>
        <div class="col-sm-9">
          
          {!! Form::text('live', Auth::user()->live, ['id' => 'user_live', 'placeholder' => 'e.g. Paris, FR / Brooklyn, NY / Chicago, IL', 'size' => '30', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_about">
          {{ trans('messages.profile.describe_yourself') }}
        </label>
        <div class="col-sm-9">
          
      {!! Form::textarea('about', Auth::user()->about, ['id' => 'user_about', 'cols' => '40', 'rows' => '5', 'class' => 'focus']) !!}

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.about_desc1', ['site_name'=>$site_name]) }}<br><br>{{ trans('messages.profile.about_desc2') }}<br><br>{{ trans('messages.profile.about_desc3', ['site_name'=>$site_name]) }}<br><br>{{ trans('messages.profile.about_desc4') }}</div>
        </div>
      </div>
          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_profile_info_university">
          {{ trans('messages.profile.school') }} 
        </label>
        <div class="col-sm-9">

      {!! Form::text('school', Auth::user()->school, ['id' => 'user_profile_info_university', 'size' => '30', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_profile_info_employer">
          {{ trans('messages.profile.work') }} 
        </label>
        <div class="col-sm-9">
          
      {!! Form::text('work', Auth::user()->work, ['id' => 'user_profile_info_employer', 'size' => '30', 'placeholder' => 'e.g. Airbnb / Apple / Taco Stand', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_time_zone">
          {{ trans('messages.profile.timezone') }} 
        </label>
        <div class="col-sm-9 lang-time">
          
      <div class="select">
      {!! Form::select('timezone', @$timezone, @$time_zones, ['id' => 'user_time_zone', 'class' => 'focus']) !!}
      </div>

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.timezone_desc') }}</div>
        </div>
      </div>

      <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_time_zone" style="word-wrap:break-word;">
         @lang('messages.profile.languages')
        </label>
        <div class="col-sm-9">
          
 <p class="hide" style="margin: 7px 0px;"> @lang('messages.account.none')</p>
 <span id="selected_language" class="multiselect-option">
  @if($known_languages)
  @php $i=0 @endphp
  @foreach($known_languages_name as $value)
  @if($value == null)
  <p style="margin: 7px 0px;"> @lang('messages.account.none')</p>
  @else
  <span class="btn btn-lang space-1">
    {{ $value }} 
      <a href="javascript:void(0)" id="remove_language" class="text-normal">
        <i class="x icon icon-remove" title="Remove from selection"></i>
         <input type="hidden" value="{{ $known_languages[$i] }}" name="language[]"> 
      </a>
  </span>
  @endif
  @php $i++ @endphp
  @endforeach
  @endif
</span>
<div style="width:100%;">
 <span style="padding-top: 0px;
font-size: 24px;
position: relative;
top: 3px;color: #8D8D8D;"> + </span><a class="language-link" href="javascript:avoid(0);">@lang('messages.profile.add_more')</a>
</div>
          <div class="text-muted row-space-top-1">@lang('messages.profile.desc_msg', ['site_name' => $site_name])</div>
        </div>
      </div>

      <div class="row row-condensed space-4">
        <label class="text-right col-sm-3 lang-chang-label" for="user_time_zone">
          {{ trans('messages.profile.email_language') }} 
        </label>
        <div class="col-sm-9 lang-time">
          
      <div class="select">
      {!! Form::select('user_email_language', @$email_languages, @$email_default_language, ['id' => 'user_email_language', 'class' => 'focus']) !!}
      </div>

          <div class="text-muted row-space-top-1">
          {{ trans('messages.profile.email_language_desc') }}</div>
        </div>
      </div>

  </div>
</div>

<button type="submit" class="lang-btn-cange btn btn-primary btn-large">
  {{ trans('messages.profile.save') }}
</button>

{!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


    </main>

<div class="login-close-language language-panel" >
<div class="mini-language" style="display:none;">

 <div class="page-container-responsive page-container-auth row-space-top-4 row-space-8">
  <div class="row">
    <div class="col-md-7 col-lg-6 col-center ">
     <div class="panel top-home">
      
        
   <div >
    <h3 class="panel-header panel-header-gray">
   @lang('messages.profile.spoken_languages')
    </h3>
    <div class="panel-padding panel-body" style="padding:20px 0px;">
   
    
          <div class="col-lg-12 col-sm-12 col-md-12" style="padding:0px !important;">
        @foreach($languages as $lan)
         <div class="col-lg-6 col-sm-6 col-md-6"> 
         <input class="language_select" type="checkbox" value="{{ $lan->id  }}" data-name="{{ $lan->name }}" style="margin-top: 9px; float:left;" id="{{ $lan->id }}" name="language" {{ in_array($lan->id, $known_languages) ? 'checked' : '' }}>
         <label for="{{ $lan->id }}" style="float:left;"> {{ $lan->name }}</label>
             </div> 
        @endforeach
     </div>

      <hr class="col-lg-12 col-sm-12 col-md-12">
      <div style="text-align:right;padding:0px 20px;">
       <a class="btn login-close-language" href="#" >@lang('messages.your_reservations.cancel')</a>
      <button id="language_save_button" class="btn btn-primary" type="submit" >
        @lang('messages.profile.save')
      </button>
      <!-- <button data-track-submit="" class="btn btn-primary btn-large language_save_button" type="submit">
  Save
</button> -->
      </div>
     </div>
  </div> 

      </div>
    </div>
  </div>
</div>
</div>
</div>
<style type="text/css">
  .select select{
    padding: 7px 30px 7px 6px !important;
  }
  .select:before{
    top: 13px !important;
  }
</style>
@stop
