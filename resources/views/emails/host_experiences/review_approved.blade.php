@extends('emails.template')
@section('emails.main')
<div style="color: #484848;line-height: 1.6;font-size: 18px;text-align: left;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.thanks_for_review',[], null, $locale) }}, {{ @$site_name }} {{ trans('messages.email.experience_fit',[], null, $locale) }} <br>
</div>
<a style="text-align:center;font-size:24px;color: #fff;margin: 20px 0px;text-decoration: none; border-radius: 4px; padding: 19px 24px 19px 24px; background-color: #00848a; display: block;" href="{{ $view_url }}">{{ trans('messages.email.btn_view_exp',[], null, $locale) }}</a>
<a style="text-align:center;font-size:24px;color: #484848;margin: 20px 0px;text-decoration: none; border: 2px solid #dbdbdb; border-radius: 4px; padding: 19px 24px 19px 24px; display: block; width: auto!important;" href="{{ $url }}host/experiences">{{ trans('messages.email.submit_another',[], null, $locale) }}</a>
</div>
@stop