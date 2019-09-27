@extends('emails.template')
@section('emails.main')
<div style="color: #484848;line-height: 1.6;font-size: 18px;text-align: left;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.excited',[], null, $locale) }}
<br><br>
{{ trans('messages.email.get_ready',[], null, $locale) }}
</div>
<div style="padding: 25px 0px;">
<hr style="border: 1px solid lightgrey;width: 10%;float: left;">
</div>

<div>
<img style="float:right;padding-left: 35px;" src="{{ $url }}images/mails/host_laws.png">
<div style="font-size:24px;line-height: 1.2;font-weight: 700;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.local_laws',[], null, $locale) }} {{ @$city }}
</div>

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.all_host_on',[], null, $locale) }} {{ @$site_name }} {{ trans('messages.email.understand_laws',[], null, $locale) }}
</div>
<br>
<a style="text-decoration: none;line-height: 1.3;color: #00848a!important;" href="{{ $url }}help/topic/5/deciding-to-host">{{ trans('messages.email.view_res_host',[], null, $locale) }}</a>
</div>

<div>
<img style="float:right;padding-left: 35px;" src="{{ $url }}images/mails/host_phone.png">
<div style="font-size:24px;line-height: 1.2;font-weight: 700;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.is',[], null, $locale) }} {{ @$enc_phone_number }} 
{{ trans('messages.email.best_num',[], null, $locale) }}
</div>

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.call_learn',[], null, $locale) }} 
</div>
<br>
<a style="text-decoration: none;line-height: 1.3;color: #00848a!important;" href="{{ $url }}users/edit">{{ trans('messages.email.update_num',[], null, $locale) }}</a>
</div>

<div>
<img style="float:right;padding-left: 35px;" src="{{ $url }}images/mails/host_identity.png">
<div style="font-size:24px;line-height: 1.2;font-weight: 700;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.confirm_identity',[], null, $locale) }}
</div>

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.every_host',[], null, $locale) }}  {{ @$site_name }} {{ trans('messages.email.needs_confirm',[], null, $locale) }}
</div>
<br>
<a style="text-decoration: none;line-height: 1.3;color: #00848a!important;" href="{{ $url }}users/edit_verification">{{ trans('messages.email.btn_confirm_identy',[], null, $locale) }}</a>
</div>

<div>
<img style="float:right;padding-left: 35px;" src="{{ $url }}images/mails/host_edits.png">
<div style="font-size:24px;line-height: 1.2;font-weight: 700;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.edit_submission',[], null, $locale) }}
</div>

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.free_time',[], null, $locale) }} 
</div>
<br>
<a style="text-decoration: none;line-height: 1.3;color: #00848a!important;" href="{{ $url }}host/manage_experience/{{ @$room_id }}">{{ trans('messages.email.btn_edit_submission',[], null, $locale) }}</a>
</div>

</div>
@stop