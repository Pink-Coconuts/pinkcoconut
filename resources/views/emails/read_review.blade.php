@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  

  <hr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color:#dbdbdb;min-height:1px;border:none">

  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;text-align:center">
  
  <img src="{{ $profile_picture }}" style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;max-width:100%;margin:0 10px;border-radius:999px;border:2px solid white;width:68px;min-height:68px;width:90px;min-height:90px" class="CToWUd">
  

</div>



  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:24px;line-height:28px;padding-bottom:10px;text-align:center">
  

    {{ trans('messages.email.read_review',['firstname'=>$users['first_name']], null, $locale) }}

</div>

    <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%">
  <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
    <td style="padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;display:block!important;margin:0 auto!important;clear:both!important;max-width:610px!important">
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-style:solid;padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:15px;background-color:#ffffff;border-bottom-width:1px;border-color:#dbdbdb;border-left-width:1px;border-bottom-left-radius:3px;border-top-width:0;border-bottom-right-radius:3px;border-top-left-radius:3px;border-top-right-radius:3px;border-right-width:1px;border-bottom:1px solid #dbdbdb!important;border-top:1px solid #dbdbdb!important">
  
      <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
        <em style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          “{{ $result['comments'] }}”
        </em>

</div>



</div>

      </div>
    </td>
    <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"></td>
  </tr>
</tbody></table>
  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">


  

  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    
     {{ trans('messages.email.now_write_review',['site_name'=> SITE_NAME ], null, $locale) }}

</div>



  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  
    <!-- While {{ $users['first_name'] }}’s feedback can’t be changed or removed, you can write a response that will appear directly below it. -->
    {{ trans('messages.email.review_feedback',['firstname'=>$users['first_name']], null, $locale) }}

</div>



  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" align="center">
  
    <a href="{{ @$view_url }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border:1px solid;padding:10px 16px;text-decoration:none;border-radius:2px;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px;display:inline-block;padding-left:40px;padding-right:40px" target="_blank">
     
       {{ trans('messages.email.write_responce',[], null, $locale) }}
    </a>

</div>

</div>
@stop