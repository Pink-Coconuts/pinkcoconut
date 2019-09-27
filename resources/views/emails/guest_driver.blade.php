@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
 <b>Hi {{ $name }}</b>
</div>
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
   	Our {{ $site_name}} Driver will Pick you from the Airport by {{ $day}}. To know the Driver details please Contact admin {{ $supprt_number}}.
 </div>
@stop