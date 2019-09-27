@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:15px;border-bottom-width:1px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;background-color:#000;color:#ffffff;padding:0;min-height:198px;background:url({{ $url.'images/referrals-email-header.jpg' }}) no-repeat center center;background-color:#000000">
  
      
<table border="0" cellspacing="0" cellpadding="0" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;vertical-align:middle;height:198px;text-align:center">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:24px;line-height:28px;padding-bottom:10px;color:#ffffff">
  
        {{ $result['first_name'] }} {{ trans('messages.email.sent_you',['symbol'=>$symbol,'travel_credit'=>$travel_credit,], null, $locale) }}!

</div>
    </td>
  </tr>
</tbody></table>


</div>

    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-bottom:15px;padding-left:15px;border-bottom-width:1px;padding-top:15px;background-color:#ffffff;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0">
  
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
        <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;text-align:center">
  
    <img src="{{ $result['profile_picture']['src'] }}" alt="Small Profile" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;width:68px;border-radius:50px;min-height:68px" class="CToWUd">

</div>
</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

        <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
          <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;text-align:center;padding-left:8%;padding-right:8%">
  
            {{ trans('messages.email.on',[], null, $locale) }} <span class="il">{{ $site_name }}</span> {{ trans('messages.email.discover_unique_place',[], null, $locale) }}
            {{ trans('messages.email.simply_sign_up_to_get',['symbol'=>$symbol,'travel_credit'=>$travel_credit], null, $locale) }}
</div>

  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:8%;color:#9ca299;text-align:center;padding-right:8%;margin-top:1em">
</div>

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

</div>
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-right:15px;padding-bottom:15px;padding-left:15px;border-bottom-width:1px;padding-top:15px;border-bottom-left-radius:3px;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:0;background-color:#f7f7f7;color:#565a5c;border-bottom-right-radius:3px;border-style:solid;border-bottom:1px solid #dbdbdb!important">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
      <a href="{{ $url.'c/'.$result['id'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block;padding:10px 16px;text-decoration:none;border-radius:2px;border:1px solid;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px" target="_blank">
        {{ trans('messages.email.accept_invitation',[], null, $locale) }}</a>

</div>
      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>

</div>
</div>
@stop