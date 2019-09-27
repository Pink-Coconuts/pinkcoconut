
@if(!isset($contact_name))
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.thanks',[], null, $locale) }},<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"> {{ trans('messages.email.team', ['site_name'=>$site_name], null, $locale) }}
</div>
@endif
</div>
</div>
</td>

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
</tr>

<tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>

<td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:600px;padding:15px;margin:0 auto;display:block;padding-bottom:5px;padding-top:0px;color:#9ca299;font-size:14px;text-align:center;padding-left:5px;padding-right:5px">

<table cellpadding="10" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;padding:10px">
<tbody>

<tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<td align="center" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<table cellpadding="5" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;width:auto">
<tbody>

<tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:5px">
<a href="{{ $join_us[0]->value }}" title="Facebook" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank" rel="noreferrer">
<img alt="Facebook" height="42" src="{{ $url.('images/email_facebook.png') }}" width="42" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;border:0">
</a> 
</td>

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:5px">

<a href="{{ $join_us[2]->value }}" title="Twitter" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank" rel="noreferrer">
<img alt="Twitter" height="42" src="{{ $url.('images/email_twitter.png') }}" width="42" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;border:0">
</a> 

</td>

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:5px">

<a href="{{ $join_us[6]->value }}" title="Instagram" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank" rel="noreferrer">
<img alt="Instagram" height="42" src="{{ $url.('images/email_instagram.png') }}" width="42" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;border:0">
</a>

</td>

<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:5px">

<a href="{{ $join_us[4]->value }}" title="Pinterest" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank" rel="noreferrer">
<img alt="Pinterest" height="42" src="{{ $url.('images/email_pinterest.png') }}" width="42" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;border:0">
</a> 

</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>

<table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;">
<tbody>
<tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
</td>
<td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">

<div style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:15px;max-width:600px;margin:0 auto;display:block;padding-left:5px;padding-right:5px;padding-bottom:5px;padding-top:0px;color:#9ca299;font-size:14px;text-align:center">
{{ trans('messages.email.sent_with_from' ,['site_name'=>$site_name], null, $locale) }} <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
</div>

</td>
<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
</tr>
</tbody></table>
</div>
</div>
</td>
<td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
</tr>
</tbody></table>
<span style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:1px!important;color:#eeeeee!important">### {{ $site_name }} ###</span>
</div>
</div>