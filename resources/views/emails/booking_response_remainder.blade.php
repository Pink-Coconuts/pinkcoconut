@extends('emails.template')

@section('emails.main')
<div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:15px;max-width:600px;margin:0 auto;display:block;text-align:center;padding-right:5px;padding-bottom:5px;padding-top:0px;padding-left:5px;color:#ffffff;background-color:#ffaa91">
            <div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:15px;max-width:600px;margin:0 auto;display:block;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:0px">
              <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;padding-top:56.25px;width:100%" width="100%" cellpadding="0" cellspacing="0">
                <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
                  <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" valign="top" align="center">
                    <a href="{{ $url }}" title="{{ $site_name }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
                      <img src="{{ EMAIL_LOGO_URL }}" alt="{{ $site_name }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;border:0;margin:0;display:block" width="160" border="0" class="CToWUd">
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <div width="50%" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" align="center">
            </div>
              <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-top:25px;padding-bottom:56.25px">
                    <h1 style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;padding-bottom:10px;text-align:center;font-size:40px;line-height:48px;margin:0;padding:0;letter-spacing:-1px;color:#ffffff">
     {{ trans('messages.email.respond_to',[], null, $locale) }}  {{ $result['users']['first_name'] }} {{ trans('messages.email.inquiry',[], null, $locale) }}  
  </h1>

  <h3 style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;padding-top:10px;text-align:center;font-size:22px;line-height:25px;margin:0;padding-bottom:0;color:#ffffff">
     {{ trans('messages.email.going_to_expire_in',[], null, $locale) }} {{$hours}} {{ trans('messages.email.hours',[], null, $locale) }}  
  </h3>
  <p style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;text-align:center;font-size:22px;padding:18px 15px 0 15px;padding-top:18px;font-weight:normal;line-height:32px">
    {{ $result['nights'] }} {{ trans_choice('messages.rooms.night',$result['nights'],[],null, $locale) }} {{ trans('messages.email.at',[], null, $locale) }} {{ $result['rooms']['name'] }}
  </p>
 

              </div>
          </div>

<div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ffffff;padding-right:5px;padding-bottom:5px;padding-top:0px;padding-left:5px">
              <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
              <tbody style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
                <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" valign="top">
                  

<div style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-top:37.5px;margin:0 auto;padding-left:30px;padding-right:30px;max-width:500px">
  
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:125px;height:125px">
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;float:left;width:125px" height="100%">
        <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0 0px">
            <img alt="" src="{{ $result['users']['profile_picture']['email_src'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border-radius:999px" width="125px" height="125px" class="CToWUd">
          </td>
        </tr>
        <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0 0px" valign="bottom">
            <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;min-height:35px">
                <img alt="" src="{{ $url.('images/caret_up_28x11.png') }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;padding-left:50px;padding-right:0;padding-bottom:0;padding-top:20px" width="25px" height="16px" class="CToWUd">
            </div>
          </td>
        </tr>
      </tbody></table>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:20px;padding-bottom:16px;color:#565a5c" valign="middle">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-size:22px">
        <h2 style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:28px;color:#565a5c;font-size:26px;font-weight:bold;padding-bottom:5px">{{ $result['users']['first_name'] }}</h2>
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px">{{ $result['users']['live'] }}</span>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px">
            {{ $site_name }}   {{ trans('messages.profile.member_since',[], null, $locale) }} {!! $result['users']['since'] !!}
          </span>
      </div>
    </td>
  </tr>
</tbody></table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:30px;border-style:solid;color:#565a5c;border-color:#dbdbdb!important;border-width:1px!important">
        
    

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="30">
      &nbsp;
    </td>
  </tr>
</tbody></table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
      <img alt="" src="{{ $result['rooms']['photo_name'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%" width="100%" class="CToWUd">

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="30">
      &nbsp;
    </td>
  </tr>
</tbody></table>

  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-size:22px">
        
    <h3 style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;line-height:24px;font-weight:bold;font-size:22px;padding-bottom:3px!important">{{ $result['rooms']['name'] }}</h3>
    <p style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#565a5c;padding-top:5px">
      <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;white-space:nowrap">
        {{ $result['number_of_guests'] }} 
        {{ trans_choice('messages.home.guest',$result['number_of_guests'], [], null, $locale) }}
      </span>
        •
        <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;white-space:nowrap">
          {{ $result['nights'] }} {{ trans_choice('messages.rooms.night',$result['nights'],[], null, $locale) }} 
        </span>
         •
        <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;white-space:nowrap">
          {{ $check_in_time }} : {{ $check_out_time }} 
        </span>
       
    </p>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="25">
      &nbsp;
    </td>
  </tr>
</tbody></table>
    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;font-size:22px;width:auto!important">
        <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:32px;width:auto!important">
            <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.home.checkin',[], null, $locale) }}</strong>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;font-weight:normal;line-height:2em;padding-bottom:3px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkinformatted'] }}</a>
          </span>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkin_time',[], null, $locale) }}
          </span>
          </td>
          <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:32px;padding:0 30px;min-width:11px!important;min-height:21px!important;width:11px!important;height:21px!important">
            <img alt="-" src="{{ $url.('images/caret_right_11x21.png') }}" valign="middle" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%" class="CToWUd">
          </td>
          <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:32px;width:auto!important">
            <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.your_reservations.checkout',[], null, $locale) }}</strong>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;font-weight:normal;line-height:2em;padding-bottom:3px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkoutformatted'] }}</a>
          </span>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkout_time',[], null, $locale) }}
          </span>
          </td>
        </tr>
      </tbody></table>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

  </div>
  

      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:31.25px">
    <a href="{{ $url.('reservation/'.$result['id']) }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block;text-decoration:none;border-radius:2px;border:1px solid;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;background-color:#ff5a5f;color:#ffffff;border-top-width:1px;border-color:transparent;font-size:22px;padding:16px" target="_blank">
      {{ trans('messages.your_reservations.accept',[], null, $locale) }} / {{ trans('messages.your_reservations.decline',[], null, $locale) }}
    </a>
  </div>


  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:50px">
  <hr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color:#dbdbdb;min-height:1px;border:none">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="60">
      &nbsp;
    </td>
  </tr>
</tbody></table>
  <h2 style="display:none;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;line-height:28px;padding-bottom:10px;font-size:26px;color:#565a5c">
    {{ trans('messages.email.respond_within_24_hours',[], null, $locale) }}
  </h2>
  <table style="display:none;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="40">
      &nbsp;
    </td>
  </tr>
</tbody></table>
  <table style="display:none;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
        <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:22px;line-height:32px;color:#565a5c">
          {{ trans('messages.header.help',[], null, $locale) }}  {{ $result['users']['first_name'] }} 
          {{ trans('messages.email.fullmessage',[], null, $locale) }} 
        </p>
      </td>
    </tr>
  </tbody></table>
</div>

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

                </td>
              </tr></tbody>
            </table>
          </div>
@stop