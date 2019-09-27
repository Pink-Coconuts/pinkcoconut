@extends('emails.template')
 
@section('emails.main')
<td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;margin:0 auto!important;display:block!important;max-width:none!important"><span class="im">
          <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;text-align:center;color:#ffffff;background-color:#ffaa91">
            <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              <table width="100%" cellpadding="0" cellspacing="0" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;padding-bottom:18.75px;padding-top:18.75px">
                <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
                  <td align="center" valign="top" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
                    <a href="{{ $url }}" title="{{ $site_name }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
                      <img src="{{ EMAIL_LOGO_URL }}" border="0" alt="{{ $site_name }}" width="160" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0;margin:0;display:block" class="CToWUd">
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <div width="50%" align="center" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            </div>
              <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-bottom:37.5px">
                  <h1 style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;padding-bottom:10px;font-size:40px;line-height:48px;padding:0;margin:0;letter-spacing:-1px;color:#ffffff">
    {{ trans('messages.payments.get_ready_for',[], null, $locale) }} {{ $result['rooms']['rooms_address']['city'] }}!
  </h1>

              </div>
          </div>
          </span><div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:15px;max-width:600px;margin:0 auto;display:block;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:0px">
              <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;padding-top:56.25px;width:100%">
              <tbody style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
                <td valign="top" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
                  
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><span class="im">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-bottom:31.25px;padding-left:3%;padding-right:3%">
        
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    Hi,

</div>


<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
  
      {{ $result['users']['full_name'] }} {{ trans('messages.email.booked',[], null, $locale) }} <a href="{{ $url.('rooms/'.$result['room_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">{{ trans('messages.email.a_place_in',[], null, $locale) }} {{ $result['rooms']['rooms_address']['city'] }} </a>{{ trans('messages.email.shared',[], null, $locale) }}

</div>




      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>


  </span><table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-bottom:31.25px">
        
    <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-top:15px;padding-bottom:15px;padding-left:15px;padding-right:15px;border-bottom-width:1px;background-color:#ffffff;border-style:solid;border-top-left-radius:3px;border-left-width:1px;border-right-width:1px;border-top-width:0;border-top-right-radius:3px;border-color:#dbdbdb;border-top:1px solid #dbdbdb!important">
  
    

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
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkin_time',[], null, $locale) }}
          </span>
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
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;line-height:100%;font-size:14px;font-size:14px">
              {{ trans('messages.your_reservations.flexible_checkout_time',[], null, $locale) }}
          </span>
        </td>
      </tr>
      </tbody></table>
    </td>
  </tr>
</tbody></table>


</div><span class="im">
  

<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:15px;border-bottom-width:1px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;padding:0px;border-bottom:0px;text-align:center;background-color:#cacccd;line-height:0">
  
    <a href="{{ $url.('rooms/'.$result['room_id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">

  <img src="{{ $result['rooms']['photo_name'] }}" alt="{{ $result['rooms']['name'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0" class="CToWUd">

    </a>

</div>
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:15px;padding-bottom:15px;border-bottom-width:1px;border-top-width:0;padding-top:15px;background-color:#ffffff;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;padding-right:15px;padding:0!important">
  
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;width:100%;border-spacing:0">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;padding:4px 15px">
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <a href="{{ $url.('rooms/'.$result['room_id']) }}" title="{{ $result['rooms']['name'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#565a5c;text-decoration:none" target="_blank">
              
    <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:10px 0px">
      <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-right:7px;vertical-align:middle">{{ $result['rooms']['name'] }}</strong> 
      <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;color:#9ca299;font-size:14px">
        <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          {{ $result['rooms']['property_type_name'] }}/{{ $result['rooms']['room_type_name'] }}
        </span>
      </div>
    </div>

            </a>
        </div>
      </td>
    </tr>
  </tbody></table>

</div>

      </span></div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>


      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>

  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:3%;padding-right:3%"><span class="im">
  
    
<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:24px;line-height:28px;padding-bottom:10px;font-weight:normal">
  
{{ trans('messages.email.getting_there',[], null, $locale) }}
</div>

</div>



      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>

<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:18.75px">
        
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td width="90" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
        <a href="http://maps.google.com/maps?daddr={{ $result['rooms']['rooms_address']['latitude'] }},{{ $result['rooms']['rooms_address']['longitude'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
          <img src="https://maps.googleapis.com/maps/api/staticmap?key={{ $map_key }}&size=100x100&amp;center={{ $result['rooms']['rooms_address']['latitude'] }},{{ $result['rooms']['rooms_address']['longitude'] }}&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&amp;maptype=mobile&amp;markers={{ $result['rooms']['rooms_address']['latitude'] }},{{ $result['rooms']['rooms_address']['longitude'] }}&amp;language={{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}" width="90" height="90" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0;border-radius:50px" class="CToWUd">
        </a>
      </td>
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:300px;margin-left:20px">
  
            <a href="http://maps.google.com/maps?daddr={{ $result['rooms']['rooms_address']['latitude'] }},{{ $result['rooms']['rooms_address']['longitude'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
                <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['rooms']['rooms_address']['address_line_1'] }}<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['rooms']['rooms_address']['city'] }}, {{ $result['rooms']['rooms_address']['state'] }} {{ $result['rooms']['rooms_address']['postal_code'] }}<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['rooms']['rooms_address']['country_name'] }}</p>
            </a>

</div>


      </td>
    </tr>
  </tbody></table>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>


  </span>
</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><hr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color:#dbdbdb;min-height:1px;border:none">
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:3%;padding-right:3%"><span class="im">

<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:24px;line-height:28px;padding-bottom:10px;font-weight:normal">
      {{ trans('messages.email.meet_your_host',[], null, $locale) }}

</div>

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>

<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-bottom:18.75px;margin-top:18.75px">
        
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td width="110" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top">
        <a href="{{ $url.('users/show/'.$result['host_users']['id']) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
          <img src="{{ $result['host_users']['profile_picture']['src'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border:0;width:90px;min-height:90px;border-radius:45px" class="CToWUd">
        </a>
      </td>
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:middle">
        <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['host_users']['first_name'] }}</strong>
          <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <!-- Phone Number -->
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
          <!-- Hided email
           <a href="mailto:{{ $result['host_users']['email'] }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">
            {{ $result['host_users']['email'] }}
          </a> -->

</div>


      </td>
    </tr>
  </tbody></table>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>

@if($result['host_users']['about'])
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:16px">
      {{ trans('messages.profile.about_me',[], null, $locale) }}
    </span>

    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
      <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ $result['host_users']['about'] }}</p>

</div>

</div>
@endif

  </span>
</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><hr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color:#dbdbdb;min-height:1px;border:none">
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:3%;padding-right:3%"><span class="im">
  
    
<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:24px;line-height:28px;padding-bottom:10px;font-weight:normal">
  
    {{ trans('messages.email.customer_support',[], null, $locale) }}

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>


<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:6.25px">
        
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    {{ trans('messages.your_reservations.contact_number',[], null, $locale) }} :
    {{ $contact }}

</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>
<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top;clear:both!important;max-width:600px!important;display:block!important;margin:0 auto!important">
      <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:6.25px">
        
  {{ trans('messages.email.reservation_message',[], null, $locale) }}

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:top"></td>
  </tr>
</tbody></table>

  </span>
</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
</div>

                </td>
              </tr></tbody>
            </table>
@stop