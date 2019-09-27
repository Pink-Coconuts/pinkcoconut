@include('common.head')

@if(!isset($exception))
	@if (Route::current()->uri() != 'api_payments/book/{id?}') 
	 	@if(Session::get('get_token')=='' && !isset($is_mobile))
	   		@include('common.header')
	 	@endif
	@endif
@else   
        @if(Session::get('get_token')=='')
   			@include('common.header')
   		@endif
@endif

@yield('main')

@if (!isset($exception))
	@if (Route::current()->uri() != 'payments/book/{id?}' && Route::current()->uri() != 'reservation/receipt'  && Route::current()->uri() != 'reservation/itinerary' && Route::current()->uri() != 'api_payments/book/{id?}')
	    @if(Session::get('get_token')=='' && !isset($is_mobile))
		   	@include('common.footer')
		@endif
	@endif
@else
    @if(Session::get('get_token')=='')
		@include('common.footer')
	@endif
@endif

@include('common.foot')