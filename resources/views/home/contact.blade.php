<title>Contact</title>
@extends('template')

@section('main')

<main id="site-content" role="main">
@if( Auth::check())
@include('common.subheader')
@endif


<div class="page-container-responsive page-container-auth margintop" style="margin-top:40px;margin-bottom:40px;">
    <div class="col-md-7 col-lg-7 col-center panel" style="padding:0px;">     
      <div class="panel-header" >
   {{ trans('messages.contactus.contactus') }}
      </div>
      
      <div class="panel-body">
       {!! Form::open(['action' => 'HomeController@contact_create','accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
      <div class="row row-condensed space-4">
        <label class="text-left col-sm-3 lang-chang-label">
          {{ trans('messages.contactus.name') }}<em class="text-danger">*</em>
        </label>
        <div class="col-sm-9">
       
        {!! Form::text('name', '', ['class' =>  $errors->has('name') ? '' : 'focus', 'placeholder' => trans('messages.contactus.name')]) !!}
       @if ($errors->has('name')) <span class="text-danger">{{ $errors->first('name') }}</span> @endif  
        
        </div>
      </div>
      <div class="row row-condensed space-4">
        <label class="text-left col-sm-3 lang-chang-label">
          {{ trans('messages.contactus.email') }}<em class="text-danger">*</em>
        </label>
        <div class="col-sm-9">    
        {!! Form::email('email', '', ['class' => $errors->has('email') ? '' : 'focus', 'placeholder' => trans('messages.contactus.email_address')]) !!}
        @if ($errors->has('email'))  <span class="text-danger">{{ $errors->first('email') }}</span> @endif
        </div>
      </div>
      <div class="row row-condensed space-4">
        <label class="text-left col-sm-3 lang-chang-label">
          {{ trans('messages.contactus.feedback') }}<em class="text-danger">*</em>
        </label>
        <div class="col-sm-9">

       {!! Form::textarea('feedback', '', ['class' => $errors->has('feedback') ? '' : 'focus', 'placeholder' => trans('messages.contactus.feedback')]) !!}
        @if ($errors->has('feedback'))  <span class="text-danger">{{ $errors->first('feedback') }}</span> @endif
        </div>
      </div>
         <div class="row row-condensed space-4">
        <label class="text-left col-sm-3 lang-chang-label">
          
        </label>
        <div class="col-sm-9 text-right" >

    {!! Form::submit( trans('messages.contactus.send') , ['class' => 'contact_submit lang-btn-cange btn btn-primary btn-large'])  !!}
    {!! Form::close() !!}

        </div>
      </div>
      </div>
      </div>
      </div>
</main>
@stop