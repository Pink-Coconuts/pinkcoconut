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
     {{ trans('messages.email.booking_confirmed',[], null, $locale) }}  {{ $result['users']['first_name'] }}’s {{ trans('messages.email.inquiry',[], null, $locale) }}  
  </h1>
  <p style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;text-align:center;font-size:22px;padding:18px 15px 0 15px;padding-top:18px;font-weight:normal;line-height:32px">
    {{ trans('messages.email.at',[], null, $locale) }} {{ $result['host_experiences']['title'] }}
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
            <a href="{{$url.('users/show').'/'.$result['users']['id']}}"><img alt="" src="{{ $result['users']['profile_picture']['src'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border-radius:999px" width="125px" height="125px" class="CToWUd"></a>
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
          @if($result['users']['primary_phone_number'])
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px; font-size: 15px;font-weight: bold;">
            {{ trans('messages.profile.phone_number',[], null, $locale) }}: {{$result['users']['primary_phone_number']}}
          </span>
          @endif
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px">
            {{ $site_name }}   {{ trans('messages.profile.member_since',[], null, $locale) }} {{ $result['users']['since'] }}
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
        
    <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-size:22px;line-height:32px">
  
      {{ $result['messages']['message'] }}

</div>


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
        
      <img alt="" src="{{ $result['host_experiences']['photo_name'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%" width="100%" class="CToWUd">

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

  <table cellspacing="0" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;padding:18px 0;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td width="47%" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;margin-left:auto;margin-right:auto;width:auto">
      <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
          <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.home.checkin',[], null, $locale) }}</strong>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;font-weight:normal;line-height:2em;padding-bottom:3px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkinformatted'] }}</a>
          </span>
          {{--
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkin_time',[], null, $locale) }}
          </span>
          --}}
        </td>
      </tr>
      </tbody></table>
    </td>

    <td width="6%" align="center" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;min-width:20px;vertical-align:middle">
      <img src="{{ $url.'/images/caret_8x14.png' }}" height="14" width="8" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%" class="CToWUd">
    </td>

    <td width="47%" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;margin-left:auto;margin-right:auto;width:auto">
      <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
          <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.your_reservations.checkout',[], null, $locale) }}</strong>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;font-weight:normal;line-height:2em;padding-bottom:3px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkoutformatted'] }}</a>
          </span>
          {{--
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkout_time',[], null, $locale) }}
          </span>
          --}}
        </td>
      </tr>
      </tbody></table>
    </td>
  </tr>
</tbody></table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="25">
      &nbsp;
    </td>
  </tr>
</tbody></table>


      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

  </div>
 <h2 style="margin:20px 0 0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;line-height:28px;padding-bottom:10px;font-size:26px;color:#565a5c">
    {{ trans('messages.email.price_details',[], null, $locale) }}
  </h2>
  <table style="display:none;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="40">
      &nbsp;
    </td>
  </tr>
</tbody></table>
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
    <tbody>

    
    
    <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
        <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
          {{ trans('messages.your_reservations.total_payout', [], null, $locale) }}
        </p>
      </td>
      <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
        <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
          {{$result['currency']['symbol']}}{{$result['subtotal']}}
        </p>
      </td>
    </tr>
  </tbody></table>


  

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

                </td>
              </tr></tbody>
            </table>
          </div>
@stop