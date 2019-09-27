@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
  <span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      {{  trans('messages.email.we_have',['currency_symbol'=>$currency_symbol,'payout_amount'=>$payout_amount,'site_name'=>$site_name], null, $locale) }}  <a href="{{ $url.('users/payout_preferences/'.$user_id) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank"> {{ trans('messages.email.add_a_payout_method',[], null, $locale) }} </a>.
    </span>
</div>
</div>
@stop