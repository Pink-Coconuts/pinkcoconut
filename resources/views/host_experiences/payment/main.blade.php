@extends('template')
@section('main')
@section('price_data')
<div class="valed" ng-init="guest_text = '{{trans_choice('experiences.payment.guest_s', 1)}}'; guests_text = '{{trans_choice('experiences.payment.guest_s', 2)}}';">
	<span class="val">@{{payment_data.currency_symbol}}@{{payment_data.price}} x @{{payment_data.number_of_guests}} @{{payment_data.number_of_guests-0 == 1 ? guest_text : guests_text }}
	</span>
	<span class="val1">@{{payment_data.currency_symbol}}@{{payment_data.subtotal}}
	</span>
</div>
<div class="valed1" ng-if="payment_data.service_fee > 0">
	<span class="val">{{trans('experiences.payment.service_fee')}}
	</span>
	<span class="val2">@{{payment_data.currency_symbol}}@{{payment_data.service_fee}}
	</span>
</div>
<div class="valed1" ng-if="payment_data.coupon_price > 0">
	<span class="val">{{trans('messages.payments.coupon_amount')}}
	</span>
	<span class="val2">-@{{payment_data.currency_symbol}}@{{payment_data.coupon_price}}
	</span>
</div>
<div class="valed1">
	<span class="val">{{trans('experiences.payment.total')}}(@{{payment_data.currency_code}})
	</span>
	<span class="val2">@{{payment_data.currency_symbol}}@{{payment_data.total}}
	</span>
	<div class="val3">{{trans('experiences.payment.the_adjusted_exchange_rate_purchase_is')}} <span ng-bind-html="payment_data.currency_symbol"></span>1.00 @{{payment_data.currency_code}} {{trans('experiences.manage.to')}} <span ng-bind-html="payment_data.paypal_currency_symbol"></span>@{{payment_data.paypal_exchange_rate}} @{{payment_data.paypal_currency_code}}.
	</div>
</div>
@endsection
@section('cancellation_policy_data')
<div class="cancel">
	<a href="{{url('hosts_experience_cancellation_policy')}}" target="_blank">{{trans('experiences.payment.cancellation_policy')}}
	</a>
	<div class="getpol">{!! trans('experiences.payment.flexible_cancellation_policy_desc') !!}
	</div>
</div>
@endsection
@section('payment_data_details')
<div class="holevib">
	<div class="vibsale">
		<h3> {{$host_experience->title}}
		</h3>
		<div class="hours">{{$host_experience->total_hours}} {{trans('experiences.payment.hour_experience')}}
		</div>
		<div class="hostdate">{{trans('experiences.details.hosted_by')}} {{$host_experience->user->first_name}}
		</div>
	</div>
	<div class="vibimg">
		<img src="{{@$host_experience->host_experience_photos[0]->image_url}}">
	</div>
</div>
<div class="vibtime">
	<div class="timeer hours">@{{format_date(payment_data.date, "ddd, Do MMM")}}
	</div>
	<div class="timeout hours">@{{format_time(payment_data.start_time, "HH:mm")}} - @{{format_time(payment_data.end_time, "HH:mm")}}
	</div>
</div>
@if($host_experience->host_experience_provides->count() > 0)
<div class="vibtime1">
	<div class="timer hours">
		@foreach($host_experience->host_experience_provides as $k => $provide)
            {{@$provide->provide_item->name}} 
        @if($k+2  == $host_experience->host_experience_provides->count())
        	{{trans('experiences.details.and')}}
        @elseif($k+1  != $host_experience->host_experience_provides->count())
            ,
        @endif
        @endforeach
        {{trans('experiences.payment.provided')}}
	</div>
</div>
	@endif
<div id="price_data" class="height-limited" >
	@yield('price_data')
</div>
@yield('cancellation_policy_data')
@endsection
<div class="inner" ng-controller="host_experiences_payment" ng-cloak ng-init="is_mobile='{{isset($is_mobile)?$is_mobile:''}}';base_url='{{$base_url}}'; scheduled_id='{{$scheduled_id}}'; host_experience_id = '{{$host_experience_id}}';token='{{Session::get('get_token')}}'">
	{!! Form::open(['url' => url($base_url.'/complete_payment?scheduled_id='.$scheduled_id), 'id' => 'host_experience_payment_form', 'accept-charset' => 'UTF-8' , 'name' => 'host_experience_payment_form', 'method' => 'post']) !!}
	<div class="page-container-responsive" ng-init="payment_tabs={{json_encode($payment_tabs)}}; current_tab='{{$current_tab}}'; current_tab_index={{$current_tab_index}};go_to_tab(current_tab_index)">
		<div class="linkpath" ng-if="is_mobile==''">
			<ul class="menu_links">
				<li ng-repeat="tab in payment_tabs" ng-class="$index > current_tab_index ? 'disabled' : ''">
					<a href="javascript:void(0)" ng-if="($index) <= current_tab_index" ng-click="go_to_tab($index);">@{{$index+1}}. @{{tab.name}}
					</a>
					<span ng-if="($index) > current_tab_index">@{{$index+1}}. @{{tab.name}}
					</span>
				</li>
			</ul>
			<div class="step">{{trans('experiences.payment.step')}} @{{current_tab_index+1}} {{trans('experiences.payment.of')}} {{count($payment_tabs)}}
			</div>
		</div>
		<div class="firtpage" ng-init="payment_data={{json_encode($payment_data)}}">
			<div class="col-md-7">
				@foreach($payment_tabs as $tab)
				<div class="tab-content" id="tab-content-{{$tab['tab']}}">
					@include($base_view_path.$tab['tab'])
				</div>
				@endforeach
			</div>
			<div class="col-md-5">
				<div class="vibrate">
					@yield('payment_data_details')
				</div>
			</div>
		</div>
		<div class="forbot" >
			<div class="holeslider">
				<div class="amut">
					<span class="amount-price">@{{payment_data.currency_symbol}}@{{payment_data.price}} 
					</span>
					<span>{{trans('experiences.details.per_person')}}
					</span>
				</div>
				<div class="seelink">
					<a href="javascript:void(0)">{{trans('experiences.payment.see_details')}}
					</a>
				</div>
			</div>
			<div class="nxt" ng-click="next_step()" ng-hide="current_tab == 'payment'">{{trans('experiences.manage.next')}}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
	<div class="vib">
		<div class="vibrate1">
			<div class="newe">
				<i class="fa fa-times" aria-hidden="true">
				</i>
			</div>
			@yield('payment_data_details')
		</div>
	</div>
</div>

@stop
@push('scripts')
<script>
	$(document).ready(function(){
		$(".seelink a,.newe").click(function(){
			$(".vib").slideToggle();
		});
	});
</script>
<script src="{{ url('admin_assets/dist/js/jquery.validate.js') }}"></script>
@endpush
