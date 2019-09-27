@extends('emails.template')

@section('emails.main')

<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:125px;height:125px">
          <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;float:left;width:125px" height="100%">
            <tbody>
              <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
                <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0 0px">
                  <img alt="" src="{{ $result['users']['profile_picture']['src'] }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;border-radius:999px" width="125px" height="125px" class="CToWUd">
                </td>
              </tr>
            </tbody>
          </table>
        </td>
        <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:20px;padding-bottom:16px;color:#565a5c" valign="middle">
          <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-size:22px">
            <h2 style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:28px;color:#565a5c;font-size:26px;font-weight:bold;padding-bottom:5px">{{ $result['users']['first_name'] }}
            </h2>
            <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px">{{ $result['users']['live'] }}
            </span>
            <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <span style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;line-height:28px">
              {{ $site_name }}   {{ trans('messages.profile.member_since',[], null, $locale) }} {!! date('Y', strtotime($result['users']['since'])) !!}
            </span>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <div style="margin:15px 0 0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <p style="margin:0;text-align:left;font-weight:300;font-family:&quot;Circular&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#484848;word-break:normal;border-radius:4px;font-size:18px;line-height:1.4;padding:24px;background-color:#ffffff;margin-bottom:0px!important">
      {{$question}}
    </p>
    <br>
    <a href="{{$url.'messaging/qt_with/'.$result['id']}}" class="" style="font-family:'Circular',Helvetica,Arial,sans-serif;font-weight:normal;margin:0;text-align:left;line-height:1.3;color:#2199e8;text-decoration:none;background-color:#ff5a5f;border-radius:4px;padding:19px 24px 19px 24px;display:block" target="_blank">
      <p style="font-weight:normal;padding:0;margin:0;text-align:center;color:white;font-family:&quot;Circular&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:24px;line-height:32px;margin-bottom:0px!important">
        {{trans('messages.email.reply')}}
      </p>
    </a>
  </div>
</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

@stop