@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  

  <hr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;background-color:#dbdbdb;min-height:1px;border:none">

  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  <div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;text-align:center">
  
  <img src="{{ $profile_picture }}" style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;max-width:100%;margin:0 10px;border-radius:999px;border:2px solid white;width:68px;min-height:68px;width:90px;min-height:90px" class="CToWUd">
  
</div>

  <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;font-size:24px;line-height:28px;padding-bottom:10px;text-align:center">
  
      {{ trans('messages.email.review_name',[], null, $locale) }} {{ $review_name }}

</div>

    <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">

<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">


    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
  {{ trans('messages.email.review_description',['firstname'=>$users['first_name'],'date'=>$result['review_end_date']], null, $locale) }}
</div>



    <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif" align="center">
  
      <a href="{{ $url.('reviews/edit/'.$result['id']) }}" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:10px 16px;text-decoration:none;border-radius:2px;border:1px solid;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px;display:inline-block;padding-left:40px;padding-right:40px" target="_blank">
       {{ trans('messages.reviews.write_a_review',[], null, $locale) }}
        
      </a>

</div>

</div>
@stop