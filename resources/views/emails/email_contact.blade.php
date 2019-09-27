@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">

@if(isset($admin_name))
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
{{ trans('messages.contactus.hi_name',['admin_name'=>$admin_name], null, $locale) }},
</div>
@endif

<span>&nbsp; </span>

<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding-left:15px;padding-bottom:15px;border-bottom-width:1px;padding-right:15px;padding-top:15px;border-style:solid;border-color:#dbdbdb;border-left-width:1px;border-right-width:1px;border-top-width:1px;background-color:#f7f7f7;color:#565a5c;border-bottom:1px solid #eeeeee">
  
        <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;border-collapse: collapse;">
          <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;border-bottom: 1px solid #eeeeee;">
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
             {{ trans('messages.contactus.name') }} 
            </td>
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
            &nbsp;
            </td>
            <td style="margin:0;padding-left:30;;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              {{ trans('messages.contactus.contact_name',['contact_name'=>$contact_name], null, $locale) }} 
            </td>
          </tr>

          <br>

          <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;border-bottom: 1px solid #eeeeee;">
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
             {{ trans('messages.contactus.email') }} 
            </td>
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
            &nbsp;
            </td>
            <td style="margin:0;padding-left:30;;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              {{ trans('messages.contactus.contact_email',['contact_email'=>$contact_email], null, $locale) }} 
            </td>
          </tr>
          
        <br>
          <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%">
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
             {{ trans('messages.contactus.feedback') }} 
            </td>
            <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;font-weight:bold;color:#565a5c;width:55px" align="left">
            &nbsp;
            </td>
            <td style="margin:0;padding-left:30;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
              {{ trans('messages.contactus.contact_feedback',['contact_feedback'=>$contact_feedback], null, $locale) }} 
            </td>
          </tr>
        </tbody></table>

</div></div>
@if(isset($contact_name))
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{{ trans('messages.email.thanks',[], null, $locale) }},<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"> {{ trans('messages.contactus.contact_name', ['contact_name'=>$contact_name], null, $locale) }}
</div> 
@endif


@stop