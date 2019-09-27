 @extends('template')
 
   @section('main')
    
    <main id="site-content" role="main">
      
<div class="page-container-responsive">
  <div class="row row-space-top-8 row-space-8">
    <div class="col-md-12 text-center">
      <h1 class="text-jumbo text-ginormous hide-sm">{{ trans('messages.errors.oops') }}!</h1>
      <!-- <h1 class="text-jumbo text-ginormous hide-sm">Coming Soon!</h1> -->
      <h2>{{ trans('messages.errors.404_desc') }}</h2>
      <!-- <h2>We are working on this page, will update it soon.</h2> -->
      <h6>{{ trans('messages.errors.error_code') }}: 404</h6>
      <ul class="list-unstyled">
        <li>{{ trans('messages.errors.helpful_links') }}:</li>
        <li><a href="{{URL::to('/')}}/">{{ trans('messages.header.home') }}</a></li>
        <li><a href="{{URL::to('/')}}/dashboard">{{ trans('messages.header.dashboard') }}</a></li>
        <li><a href="{{URL::to('/')}}/users/edit">{{ trans('messages.header.profile') }}</a></li>
      </ul>
    </div>
  </div>
</div>

    </main>

@stop
