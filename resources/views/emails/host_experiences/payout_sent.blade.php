@extends('emails.template')

@section('emails.main')
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
      {{ trans('messages.email.issue_payout',[], null, $locale) }} {{ $result['currency']['symbol'] }}{{ $payout_amount }} {{ trans('messages.email.via_paypal',[], null, $locale) }}.
      {{ trans('messages.email.weekends_holidays',[], null, $locale) }}.
</div>
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
  
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;width:100%;table-layout:fixed">
        <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:33%;text-align:left;padding:0 10px 10px 0">
            {{ trans('messages.account.date',[], null, $locale) }}
          </th>
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;text-align:left;padding:0 10px 10px 0;display:block">
            {{ trans('messages.your_reservations.details',[], null, $locale) }}
          </th>
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:25%;word-wrap:break-word;white-space:nowrap;text-align:right;padding:0 10px 10px 0">
            {{ trans('messages.account.amount',[], null, $locale) }}
          </th>
        </tr>
          <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:33%;text-align:left;padding:0 10px 10px 0">
              {{ $result['checkinformatted'] }} - {{ $result['checkoutformatted'] }}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;text-align:left;padding:0 10px 10px 0;overflow:hidden;text-overflow:ellipsis">
              {{ $result['code'] }} - {{ $full_name }} - {{ $result['host_experiences']['title'] }}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:25%;word-wrap:break-word;text-align:right;padding:0 10px 10px 0;white-space:nowrap">
              {{ $result['currency']['symbol'] }}{{ $payout_amount }}
            </td>
          </tr>
      </tbody></table>

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    {{ trans('messages.email.status_payouts',[], null, $locale) }} <a href="{{ $url.('users/transaction_history') }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">{{ trans('messages.header.transaction_history') }}</a>.
      {{ trans('messages.email.question_contact',[], null, $locale) }}.
</div>
@stop