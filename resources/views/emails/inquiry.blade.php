@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">  
<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
 <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-bottom:15px;padding-left:15px;border-bottom-width:1px;padding-top:15px;border-top-left-radius:3px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;border-top-right-radius:3px;background-color:#ffffff;min-height:32px;border-top:1px solid #dbdbdb!important">
  
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
        <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $question }}</p>

</div>
        <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
  
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;text-align:center;font-size:0.9em;display:block;border-top:1px solid #dbdbdb;padding-top:12px">
            {{ trans('messages.email.remember',[], null, $locale) }}: {{ $site_name }} {{ trans('messages.email.money',[], null, $locale) }}
          </span>

</div>
</div>
      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-bottom-width:1px;border-style:solid;border-left-width:1px;border-right-width:1px;border-top-width:0;background-color:#f7f7f7;color:#565a5c;border-color:#dbdbdb;padding-bottom:10px!important;padding-left:10px!important;padding-top:17px!important;padding-right:0!important">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;width:100%;border-spacing:0">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:78px;line-height:0">
          <a href="{{ $url.('users/show/'.$result['user_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">

        <img src="{{ $result['users']['profile_picture']['src'] }}" alt="" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0;border-radius:2px" width="68" height="68" class="CToWUd">

          </a>
      </td>
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <a href="{{ $url.('users/show/'.$result['user_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">
              
    <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#565a5c;font-weight:bold">{{ $result['users']['first_name'] }}</span>
      <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      {{ $result['guests'] }}

            </a>
        </div>
      </td>
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:12px;padding-right:10px">
          <a href="{{ $url.('users/show/'.$result['user_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">
            <img src="{{ $url.('images/caret_8x14.png') }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0" width="8" height="14" class="CToWUd">
          </a>
        </td>
    </tr>
  </tbody></table>

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:15px;padding-bottom:15px;border-bottom-width:1px;padding-right:15px;padding-top:15px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;background-color:#f7f7f7;color:#565a5c;border-bottom:1px solid #eeeeee">
  
        <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
          <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="center">
              {{ $result['users']['users_verification']['verified_count'] }}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              {{ trans('messages.email.verification',[], null, $locale) }} {{ ($result['users']['users_verification']['verified_count'] > 1) ? 's' : '' }}
            </td>
          </tr>
        </tbody></table>

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody>
</table>

    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-bottom:15px;padding-left:15px;border-bottom-width:1px;padding-top:15px;border-bottom-left-radius:3px;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;background-color:#f7f7f7;color:#565a5c;border-bottom-right-radius:3px;border-style:solid;border-bottom:1px solid #dbdbdb!important">
  
        <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
          <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="center">
              {!! count($result['users']['reviews']) !!}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              {{ trans_choice('messages.header.review',count($result['users']['reviews']),[], null, $locale) }} 
            </td>
          </tr>
        </tbody></table>

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-left:15px;border-bottom-width:1px;background-color:#ffffff;border-top-left-radius:3px;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;border-top-right-radius:3px;border-style:solid;padding-top:0;padding-bottom:0;border-top:1px solid #dbdbdb!important">
  
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;font-weight:bold;height:57px;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;font-weight:bold;width:1px;white-space:nowrap" height="57">Inquiry</td>
  </tr>
</tbody></table>
</div>

<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:15px;border-bottom-width:1px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;padding:0px;border-bottom:0px;text-align:center;background-color:#cacccd;line-height:0">
  
    <a href="{{ $url.('rooms/'.$result['room_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">

  <img src="{{ $result['rooms']['photo_name'] }}" alt="{{ $result['rooms']['name'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0" class="CToWUd">

    </a>

</div>
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:15px;padding-bottom:15px;border-bottom-width:1px;border-top-width:0;padding-top:15px;background-color:#ffffff;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;padding-right:15px;padding:0!important">
  
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;width:100%;border-spacing:0">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:4px 15px">
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <a href="{{ $url.('rooms/'.$result['room_id']) }}" title="{{ $result['rooms']['name'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">

      <b style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['rooms']['name'] }}</b><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;font-size:14px">
        <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['rooms']['property_type_name'] }} - {{ $result['rooms']['room_type_name'] }}</span>
          •
          <b style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['number_of_guests'] }}</b> Guest{{ ($result['number_of_guests']>1) ? 's' : '' }}
          •
          <b style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"> {{ trans('messages.email.you_could_earn',[], null, $locale) }}  {{ $result['currency']['symbol'].$result['host_payout'] }}</b> ( {{ trans('messages.inbox.once_reservation_made',[], null, $locale) }} )
      </div>
            </a>
        </div>
      </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0px;min-width:56px;line-height:0" valign="top" width="11%">
            <a href="{{ $url.('users/show/'.$result['host_id']) }}" title="{{ $result['host_users']['first_name'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">

          <img src="{{ $result['host_users']['profile_picture']['src'] }}" alt="" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0" width="100%" class="CToWUd">

            </a>
        </td>
    </tr>
  </tbody></table>

</div>

  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-bottom:15px;padding-left:15px;border-bottom-width:1px;padding-top:15px;background-color:#ffffff;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0">
  
      <table style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;padding:18px 0;width:100%" cellspacing="0">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" width="47%">
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;margin-left:auto;margin-right:auto;width:auto">
      <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-bottom:12.5px"><b style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.home.checkin',[], null, $locale) }}</b></div>
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:18px;line-height:24px;padding-bottom:10px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkinformatted'] }}</a>
          </span>

        </td>
      </tr>
      </tbody></table>
    </td>

    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;min-width:20px" width="6%" align="center">
      <img src="{{ $url.('images/caret_8x14.png') }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%" width="8" height="14" class="CToWUd">
    </td>

    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top" width="47%">
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;margin-left:auto;margin-right:auto;width:auto">
      <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-bottom:12.5px"><b style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.home.checkout',[], null, $locale) }}</b></div>
          <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:18px;line-height:24px;padding-bottom:10px">
            <a style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none">{{ $result['checkoutformatted'] }}</a>
          </span>

        </td>
      </tr>
      </tbody></table>
    </td>
  </tr>
</tbody></table>

</div>

  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-top:15px;padding-bottom:15px;padding-left:15px;padding-right:15px;border-bottom-width:1px;background-color:#ffffff;border-style:solid;border-bottom-left-radius:3px;border-left-width:1px;border-right-width:1px;border-top-width:0;border-bottom-right-radius:3px;border-color:#dbdbdb;border-bottom:1px solid #dbdbdb!important">
  
          <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  @if($result['status']=='Pending')
      <a href="{{ $url.('reservation/'.$result['id']) }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block;padding:10px 16px;text-decoration:none;border-radius:2px;border:1px solid;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px" target="_blank">
        {{ trans('messages.inbox.pre_accept',[], null, $locale) }} / {{ trans('messages.your_reservations.decline',[], null, $locale) }}
      </a> 

  @else
       <a href="{{ $url.('messaging/qt_with/'.$result['id']) }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block;padding:10px 16px;text-decoration:none;border-radius:2px;border:1px solid;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px" target="_blank">
        {{ trans('messages.inbox.pre_approve',[], null, $locale) }} / {{ trans('messages.your_reservations.decline',[], null, $locale) }}
      </a>
      @endif

</div>

</div>

</div>

<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
@stop