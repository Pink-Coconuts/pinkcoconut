 @extends('template')

@section('main')
 
 

  <main id="site-content" role="main" ng-controller="payment">
  
<div id="main-view" class="main-view page-container-responsive row-space-top-6 row-space-6">
@if($reservation_id!='' || $booking_type == 'instant_book')
     @if(Session::get('get_token')=='')
  <form action="{{ url('payments/create_booking') }}" method="post" id="checkout-form">
     @else
  <form action="{{ url('api_payments/create_booking') }}" method="post" id="checkout-form">   
     @endif
@else 
  @if(Session::get('get_token')=='')
  <form action="{{ url('payments/pre_accept') }}" method="post" id="checkout-form">
  @else
  <form action="{{ url('api_payments/pre_accept') }}" method="post" id="checkout-form">
  @endif
@endif
    <input name="room_id" type="hidden" value="{{ $room_id }}">
    <input name="checkin" type="hidden" value="{{ $checkin }}">
    <input name="special_offer_id" type="hidden" value="{{ $special_offer_id }}">
    <input name="checkout" type="hidden" value="{{ $checkout }}">
    <input name="number_of_guests" type="hidden" value="{{ $number_of_guests }}">
    <input name="nights" type="hidden" value="{{ $nights }}">    
    <input name="cancellation" type="hidden" value="{{ $cancellation }}">
    <input name="currency" type="hidden" value="{{ $result->rooms_price->code }}">
    <input name="session_key" type="hidden" value="{{ $s_key }}">
    <input name="guest_token" type="hidden" value="{{ Session::get('get_token') }}">

    {!! Form::token() !!}

    <div class="row">
      <div class="col-md-5 col-md-push-7 col-lg-4 col-lg-push-8 row-space-2 lang-ar-left" >
        <div class="panel payments-listing payment_list_right" id="sticky4">
          <div class="media-photo media-photo-block text-center payments-listing-image">
            {!! Html::image($result->photo_name, $result->name, ['class' => 'img-responsive-height']) !!}
          </div>
          <div class="panel-body">
            <section id="your-trip" class="your-trip">
              <div class="hosting-info">
                <div class="payments-listing-name h4 row-space-1" style="word-wrap: break-word;">{{ $result->name }}  <p style="font-weight: normal;
font-size: 14px;
margin: 10px 0px !important;">@if($result->rooms_address->city !='') {{ $result->rooms_address->city }} , @endif 
                              @if($result->rooms_address->state !=''){{ $result->rooms_address->state }} @endif 
                              @if($result->rooms_address->country_name !='') , {{  $result->rooms_address->country_name }} @endif </p></div>
                <div class="">
                
                  <hr>
                  <div class="row-space-1">
                    <strong>        
                    {{ $result->room_type_name }}                   
                    </strong> {{ trans('messages.payments.for') }} <strong>{{ $number_of_guests }} {{ trans_choice('messages.home.guest',$number_of_guests) }}</strong>
                  </div>
                  <div> 
                    <strong>{{ date($php_format_date, strtotime($checkin)) }}</strong> {{ trans('messages.payments.to') }} <strong>{{ date($php_format_date, strtotime($checkout)) }}</strong>
                  </div>
                </div>
                <hr>
                <table class="reso-info-table">
                  <tbody>
                    <tr>
                      <td>{{ trans('messages.payments.cancellation_policy') }}</td>
                      <td>
                        @if($reservation_id!='')
                        <a href="{{ url('home/cancellation_policies#').strtolower($cancellation) }}" class="cancel-policy-link" target="_blank">{{trans('messages.cancellation_policy.'.strtolower($cancellation))}} </a>
                        @else
                        <a href="{{ url('home/cancellation_policies#').strtolower($result->cancel_policy) }}" class="cancel-policy-link" target="_blank">{{trans('messages.cancellation_policy.'.strtolower($result->cancel_policy))}} </a>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('messages.lys.house_rules') }}</td>
                      <td>
                        <a href="#house-rules-agreement" class="house-rules-link">{{ trans('messages.payments.read_policy') }}</a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ ucfirst(trans_choice('messages.rooms.night',2)) }}
                      </td>
                      <td>
                        {{ $nights }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <hr>
                <section id="billing-summary" class="billing-summary">
                    <div class="tooltip tooltip-top-middle taxes-breakdown" role="tooltip" data-sticky="true" data-trigger="#tax-tooltip" aria-hidden="true">
    <div class="panel-body">
      <table>
        <tbody><tr>
          <td colspan="2"></td>
        </tr>
      </tbody></table>
    </div>  </div>
  <div class="tooltip tooltip-top-middle makent-credit-breakdown" role="tooltip" data-sticky="true" data-trigger="#makent-credit-tooltip" aria-hidden="true">
    <div class="panel-body">
      <table class="table makent-credit-breakdown">
      </table>
    </div>
  </div>
  <table id="billing-table" class="reso-info-table billing-table">
    <tbody>
      
    <tr class="base-price">
      <td class="name pos-rel">
       <span class="lang-chang-label">  
       @if(Session::get('get_token')!='')
        {{ Session::get('currency_symbol') }}
        @else
        {{ $result->rooms_price->currency->symbol }}
          @endif
          </span>{{ $price_list->base_rooms_price }}  x {{ $nights }} {{ trans_choice('messages.rooms.night',$nights) }}
         <i id="service-fee-tooltip" rel="tooltip" title="{{ trans('messages.rooms.avg_night_rate') }}" style="position:relative;"  >
         
        </i>    </td>
      <td class="val text-right">
      <span class="lang-chang-label"> @if(Session::get('get_token')!='')
        {{ Session::get('currency_symbol') }}
        @else
        {{ $result->rooms_price->currency->symbol }}
          @endif</span><span >{{ $price_list->total_night_price }}</td>
    </tr>
    @if($price_list->booked_period_type != '' && $price_list->booked_period_discount_price>0)
    <tr class="booked_period text-beach">
      <td class="name pos-rel">
        {{$price_list->booked_period_discount}}% 
        @if($price_list->booked_period_type == 'early_bird')
          {{ trans('messages.rooms.early_bird_price_discount') }}          
        @elseif($price_list->booked_period_type == 'last_min')
          {{ trans('messages.rooms.last_min_price_discount') }}
        @endif
      </td>
      <td class="val text-right">
        -
        <span class="lang-chang-label"> 
          @if(Session::get('get_token')!='')
            {{ Session::get('currency_symbol') }}
          @else
            {{ $result->rooms_price->currency->symbol }}
          @endif
        </span>
        <span >
          {{ $price_list->booked_period_discount_price }}
        </span>
      </td>
    </tr>
    @endif
    @if($price_list->length_of_stay_type != '' && $price_list->length_of_stay_discount_price>0)
    <tr class="length_of_stay text-beach">
      <td class="name pos-rel">
        {{$price_list->length_of_stay_discount}}% 
        @if($price_list->length_of_stay_type == 'weekly')
          {{ trans('messages.rooms.weekly_price_discount') }}          
        @elseif($price_list->length_of_stay_type == 'monthly')
          {{ trans('messages.rooms.monthly_price_discount') }}
        @elseif($price_list->length_of_stay_type == 'custom') 
          {{ trans('messages.rooms.long_term_price_discount') }}
        @endif
      </td>
      <td class="val text-right">
        -
        <span class="lang-chang-label"> 
          @if(Session::get('get_token')!='')
            {{ Session::get('currency_symbol') }}
          @else
            {{ $result->rooms_price->currency->symbol }}
          @endif
        </span>
        <span >
          {{ $price_list->length_of_stay_discount_price }}
        </span>
      </td>
    </tr>
    @endif
    @if($price_list->service_fee)
    <tr class="service-fee">
      <td class="name pos-rel">
        {{ trans('messages.rooms.service_fee') }}
       <i id="service-fee-tooltip" class="icon icon-question" rel="tooltip" title="{{ trans('messages.rooms.24_7_help') }}" style="position:relative;">
        </i>
      </td>
      <td class="val text-right">
      <span class="lang-chang-label"> @if(Session::get('get_token')!='')
        {{ Session::get('currency_symbol') }}
        @else
        {{ $result->rooms_price->currency->symbol }}
          @endif</span><span >{{ $price_list->service_fee }}</span></td>
    </tr>
    @endif

    @if($price_list->airport_fee)
    <tr class="service-fee">
      <td class="name pos-rel">
        {{ trans('messages.rooms.airport_fee') }}
        <i id="airport-fee-tooltip"  rel="tooltip" class="icon icon-question" title="It is for Airport Pickup"></i>
      </td>
      <td class="val text-right">
      <span class="lang-chang-label"> @if(Session::get('get_token')!='')
        {{ Session::get('currency_symbol') }}
        @else
        {{ $result->rooms_price->currency->symbol }}
          @endif</span><span >{{ $price_list->airport_fee }}</span></td>
    </tr>
    @endif



    @if($price_list->additional_guest)
      @if(@$special_offer_id == '' || @$special_offer_type == 'pre-approval' )
        <tr class="additional_price"> 
          <td class="name">
            {{ trans('messages.rooms.addtional_guest_fee') }}
          </td>
        <td class="val text-right">
        <span class="lang-chang-label">
          @if(Session::get('get_token')!='')
            {{ Session::get('currency_symbol') }}
            @else
            {{ $result->rooms_price->currency->symbol }}
              @endif</span><span>{{ $price_list->additional_guest }}</span></td>
        </tr>
        @endif
    @endif

    @if($price_list->cleaning_fee)
      @if(@$special_offer_id =='' || @$special_offer_type == 'pre-approval')
        <tr class="cleaning_price"> 
          <td class="name">
            {{ trans('messages.lys.cleaning') }}
          </td>
        <td class="val text-right">
        <span class="lang-chang-label">
          @if(Session::get('get_token')!='')
            {{ Session::get('currency_symbol') }}
            @else
            {{ $result->rooms_price->currency->symbol }}
              @endif</span><span >{{ $price_list->cleaning_fee }}</span></td>
        </tr>
        @endif
    @endif

     @foreach($ngo_detail as $ngo)
          <tr class="ngo" id='ngo_{{$ngo->id}}' <?php if(!$ngo->checked) { ?> style='display: none'; <?php } ?> >
            <td class="name">
            {{ $ngo->name }}  <i rel="tooltip" class="icon icon-question" title="{{ $ngo->description }}"></i></td>
            <td class="val text-right">{{ $result->rooms_price->currency->symbol }}{{ $ngo->amount }}</td>
          </tr>
      @endforeach
      
      <tr class="editable-fields" id="after_apply">
        <td colspan="2">
          <div class="row-condensed clearfix row-space-1">
            <div class="col-sm-7">
              <input autocomplete="off" class="coupon-code-field" name="coupon_code" type="text" value="">
            </div>
            <div class="col-sm-5">
              <a href="javascript:void(0);" id="apply-coupon" class="btn btn-block apply-coupon">{{ trans('messages.payments.apply') }}</a>
            </div>
          </div>
          
          <p id="coupon_disabled_message" class="icon-rausch" style="display:none"></p>
          <a href="javascript:;" class="cancel-coupon">{{ trans('messages.your_reservations.cancel') }}</a>
        </td>
      </tr>
@if($reservation_id!='' || $booking_type == 'instant_book')
    <tr class="coupon">
        <td class="name">
          <span class="without-applied-coupon">
          <span class="coupon-section-link" id="after_apply_coupon" style="{{ (Session::has('coupon_amount')) ? 'display:Block;' : 'display:none;' }}"> 
          @if($travel_credit !=0 && Session::get('coupon_code') == 'Travel_Credit') 
            {{ trans('messages.referrals.travel_credit') }}
          @else
            {{ trans('messages.payments.coupon') }} 
          @endif
          </span>
          </span>
          <span class="without-applied-coupon" id="restric_apply">
            <a href="javascript:;" class="open-coupon-section-link" style="{{ (Session::has('coupon_amount')) ? 'display:none;' : 'display:Block;' }}">{{ trans('messages.payments.coupon_code') }}</a>
          </span>
        </td>
        <td class="val text-right">
          <div class="without-applied-coupon label label-success" id="after_apply_amount" style="{{ (Session::has('coupon_amount')) ? 'display:Block;' : 'display:none;' }}">
           -{{ $result->rooms_price->currency->symbol }}<span id="applied_coupen_amount">{{ $price_list->coupon_amount }}</span>
          </div>
        </td>
      </tr>

      

      <tr id="after_apply_remove" style="{{ (Session::has('coupon_amount')) ? '' : 'display:none;' }}">
      <td>
      <a data-prevent-default="true" href="javascript:void(0);" id="remove_coupon">
      <span>
          @if($travel_credit !=0  && Session::get('coupon_code') == 'Travel_Credit')
            {{ trans('messages.referrals.remove_travel_credit') }}
          @else
          {{ trans('messages.payments.remove_coupon') }}
          @endif
      </span>
      </a>
      </td>
      <td>
      </td>
      </tr>
      @endif
    </tbody>
  </table>
  
  <hr>
  
  <table id="payment-total-table" class="reso-info-table billing-table">
    <tbody>
      <tr class="total">
        <td class="name"><span class="h3">{{ trans('messages.rooms.total') }}</span></td>
        <td class="text-special icon-dark-gray text-right"><span class="h3">
          @if(Session::get('get_token')!='')
             {{ Session::get('currency_symbol') }}
          @else
           {{ $result->rooms_price->currency->symbol }}
          @endif
        </span> <span class="h3" id="payment_total">{{ $price_list->total }}</span></td>
      </tr>
  @if($price_list->security_fee)
    {{--  @if(@$special_offer_id =='' || @$special_offer_type == 'pre-approval') --}}
        <tr class="security_price"> 
          <td class="name">
            {{ trans('messages.payments.security_deposit') }}
            <i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i>
          </td>
        <td class="val text-right">
        <span class="lang-chang-label">
          @if(Session::get('get_token')!='')
            {{ Session::get('currency_symbol') }}
            @else
            {{ $result->rooms_price->currency->symbol }}
              @endif</span>
        <span >{{ $price_list->security_fee }}</span></td>
        </tr>
      {{-- @endif --}}
    @endif
    </tbody>
  </table>
@if($price_list->total == '0')
<div class="panel-travel_credit-full">
  <hr>
  <small><div>
    <span class="label label-success">
      @if(Session::get('coupon_code') == 'Travel_Credit')
        {{ trans('messages.payments.continue_with_travel_credit') }}
      @else
        {{ trans('messages.payments.continue_with_coupon_code') }}
      @endif
    </span>
  </div></small>
</div>
@endif
<div class="panel-total-charge">
  <hr>

  <small><div>
    <span id="currency-total-charge" class="">
      {{ trans('messages.payments.you_are_paying_in') }}
      <strong><span id="payment-currency" >{{PAYPAL_CURRENCY_SYMBOL}}{{PAYPAL_CURRENCY_CODE}}</span></strong>.
      {{ trans('messages.payments.total_charge_is') }}
      <strong>{{PAYPAL_CURRENCY_SYMBOL}}<span id="payment_total_charge">{{ $paypal_price }}</span></strong>.
    </span>
    <span id="fx-messaging">{{ trans('messages.payments.exchange_rate_booking',['symbol'=>PAYPAL_CURRENCY_SYMBOL]) }} {{ $result->rooms_price->currency->original_symbol }}<span id='paypal_price_rate'>{{ $paypal_price_rate }}</span>{{ $result->rooms_price->currency_code }} ({{ trans('messages.payments.host_listing_currency') }}).</span>
  </div></small>
</div>

                </section>
              </div>
            </section>
          </div>
        </div>
      </div>
      <div id="content-container" class="col-md-7 col-md-pull-5 col-lg-pull-4 lang-ar-right">
<div class="alert alert-with-icon alert-error alert-block hide row-space-2" id="form-errors">
  <i class="icon alert-icon icon-alert-alt"></i>
            <div class="h5 row-space-1 error-header">
            {{ trans('messages.payments.almost_done') }}!
          </div>
          <ul></ul>

</div>
<div class="alert alert-with-icon alert-error alert-block hide row-space-2" id="server-error">
  <i class="icon alert-icon icon-alert-alt"></i>
            {{ trans('messages.payments.connection_timed_out',['site_name'=>$site_name]) }}
</div>
<div class="alert alert-with-icon alert-error alert-block hide row-space-2" id="verification-error">
  <i class="icon alert-icon icon-alert-alt"></i>
            
            {{ trans('messages.payments.card_not_verified') }}
</div>
@if(($reservation_id!='' || $booking_type == 'instant_book') && $price_list->total != '0')
        <section id="payment" class="checkout-main__section payment">
            <h2 class="section-title">{{ trans('messages.payments.payment') }}</h2>

<div class="payment-section">
    <div class="row">
      <div class="col-lg-6">
        <label for="country-select">
          {{ trans('messages.account.country') }}
        </label>
        
        <div class="select select-block">
          @if(Session::get('payment_country')) 

             {!! Form::select('payment_country', $country, Session::get('mobile_payment_counry_code'), ['id' => 'country-select']) !!}
          @else
          {!! Form::select('payment_country', $country, $default_country_code, ['id' => 'country-select']) !!}
          @endif
        </div>
      </div>
    </div>

    <div class="payment-controls">
        <div class="row">
          <div class="col-sm-12">
            <label for="payment-method-select">
              {{ trans('messages.payments.payment_type') }}
            </label> 
          </div>
        </div>
        <div class="row" id="payment-type-select">
          <div class="col-lg-6 row-space-2">
            <div class="select select-block">
              <select name="payment_type" class="grouped-field" id="payment-method-select">
              <!--change for Api payment_type-->
                  <option value="cc" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
                   {{ trans('messages.payments.credit_card') }}
                  </option>
                  <option value="paypal" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="" {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'selected':''}} >
                    PayPal
                  </option>
              </select>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="payment-method grouped-field cc" style={{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'display:none;':'display:block'}}  >
              <div class="payment-logo unionpay hide"></div>
              <div class="payment-logo visa">{{ trans('messages.payments.credit_card') }}</div>
              <div class="payment-logo master"></div>
              <div class="payment-logo american_express"></div>
              <div class="payment-logo discover"></div>
              <div class="payment-logo jcb hide"></div>
              <div class="payment-logo postepay hide"></div>
              <i class="icon icon-lock icon-light-gray h3"></i>
                <div class="cc-data hide">
                  <div class="cc-info">
                    {{ trans('messages.payments.name') }}: <span id="selected-cc-name"></span>
                  </div>
                  <div class="cc-info">
                    {{ trans('messages.payments.expires') }}: <span id="selected-cc-expires"></span>
                  </div>
                </div>
            </div>
              <div class="payment-method grouped-field digital_river_cc">
                <div class="payment-logo visa"></div>
                <div class="payment-logo master"></div>
                <div class="payment-logo american_express"></div>
                <div class="payment-logo hipercard"></div>
                <div class="payment-logo elo"></div>
                <div class="payment-logo aura"></div>
                <i class="icon icon-lock icon-light-gray h3"></i>
              </div>
              <div class="payment-method grouped-field paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'active':''}} ">
                <div class="payment-logo paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'active':''}} ">PayPal</div>
              </div>
          </div>
          <div class="control-group cc-zip col-md-6 cc-zip-retry hide">
            <label for="credit-card-zip">
              {{ trans('messages.payments.postal_code') }}
            </label>

            <input type="text" class="cc-zip-text cc-short cc-short-half" name="zip_retry" id="credit-card-zip-retry">
            <div class="label label-warning inline-error hide"></div>
          </div>
        </div>

    </div>

  <div id="payment-methods-content">
    <div class="payment-method cc active" id="payment-method-cc">
      <div class="payment-method-container">
        
        <input type="hidden" name="payment_method_nonce" id="payment_method_nonce">
    
        <div class="new-card">
          <div class="cc-details row">
            <div class="control-group cc-type col-md-6">
              <label class="control-label" for="credit-card-type">
                {{ trans('messages.payments.card_type') }}
              </label>
                <div class="select select-block">
                  <select id="credit-card-type" class="cc-med" name="cc_type">
                      <option value="visa" selected="selected">
                        Visa
                      </option>
                      <option value="master">
                        MasterCard
                      </option>
                      <option value="american_express">
                        American Express
                      </option>
                      <option value="discover">
                        Discover
                      </option>
                  </select>
                </div>
              </div>
            <div class="control-group cc-number col-md-6">
              <label for="credit-card-number">
                {{ trans('messages.payments.card_number') }}
              </label>
                {!! Form::text('cc_number', '', ['class' => 'cc-med', 'id' => 'credit-card-number', 'autocomplete' => 'off']) !!}
              @if ($errors->has('cc_number')) <div class="label label-warning inline-error">{{ $errors->first('cc_number') }}</div> @endif
              </div>
            </div>
            <div class="row">
              <div class="control-group cc-expiration col-md-6">
                <label aria-hidden="true">
                  {{ trans('messages.payments.expires_on') }}
                </label>
                <div class="row row-condensed">
                  <div class="col-sm-6">
                    <div class="select select-block">
                      <label for="credit-card-expire-month" class="screen-reader-only">
                        {{ trans('messages.login.month') }}
                      </label>
                      {!! Form::selectRangeWithDefault('cc_expire_month', 1, 12, null, 'mm', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-month']) !!}
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="select select-block">
                      <label for="credit-card-expire-year" class="screen-reader-only">
                        {{ trans('messages.login.year') }}
                      </label>
                      {!! Form::selectRangeWithDefault('cc_expire_year', date('Y'), date('Y')+30, null, 'yyyy', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-year']) !!}
                    </div>
                  </div>
                </div>
                @if ($errors->has('cc_expire_month') || $errors->has('cc_expire_year'))
                <div class="label label-warning inline-error">
                @if ($errors->has('cc_expire_month'))
                  {{ $errors->first('cc_expire_month') }}
                @endif
                @if ($errors->has('cc_expire_month') == '')
                  {{ $errors->first('cc_expire_year') }}
                @endif
                </div> 
                @endif
              </div>
              <div class="control-group cc-security-code col-md-4">
                <label class="control-label" for="credit-card-security-code">
                  {{ trans('messages.payments.security_code') }}
                </label>
                <div class="row">
                  <div class="col-sm-6 col-md-8">
                    {!! Form::text('cc_security_code', '', ['class' => 'cc-short', 'id' => 'credit-card-security-code', 'autocomplete' => 'off']) !!}
                  </div>
                </div>
                @if ($errors->has('cc_security_code')) <div class="label label-warning inline-error">{{ $errors->first('cc_security_code') }}</div> @endif
              </div>
            </div>
    
    
          <hr>
          <div class="row">
            <div class="col-sm-12">
              <h2>{{ trans('messages.payments.billing_info') }}</h2><p></p>
            </div>
          </div>
    
    
          <div class="row">
            <div class="control-group cc-first-name col-md-6">
              <label class="control-label" for="credit-card-first-name">
                {{ trans('messages.login.first_name') }}
              </label>
    
              {!! Form::text('first_name', '', ['id' => 'credit-card-first-name']) !!}

              @if ($errors->has('first_name')) <div class="label label-warning inline-error">{{ $errors->first('first_name') }}</div> @endif
            </div>
    
            <div class="control-group cc-last-name col-md-6">
              <label class="control-label" for="credit-card-last-name">
                {{ trans('messages.login.last_name') }}
              </label>
    
              {!! Form::text('last_name', '', ['id' => 'credit-card-last-name']) !!}

              @if ($errors->has('last_name')) <div class="label label-warning inline-error">{{ $errors->first('last_name') }}</div> @endif
            </div>
          </div>
          <div class="row hide">
            <div class="control-group cc-address1 col-md-6">
              <label class="control-label" for="credit-card-address1">
                {{ trans('messages.payments.street_address') }}
              </label>
    
              <input type="text" name="address1" id="credit-card-address1" disabled="">
              @if ($errors->has('address1')) <div class="label label-warning inline-error">{{ $errors->first('address1') }}</div> @endif
            </div>
    
            <div class="col-md-2">
              <label for="credit-card-address2">
                {{ trans('messages.payments.apt') }} #
              </label>
    
              <input type="text" class="cc-short" name="address2" id="credit-card-address2" disabled="">
            </div>
          </div>
    
          <div class="row">
            <div class="control-group cc-city
                       
                        col-md-6 col-lg-5 
                        hide ">
              <label for="credit-card-city">
                {{ trans('messages.account.city') }}
              </label>
    
              <input type="text" name="city" id="credit-card-city" disabled="">
              @if ($errors->has('city')) <div class="label label-warning inline-error">{{ $errors->first('city') }}</div> @endif
            </div>
            <div class="cc-state col-md-2
                       hide">
              <label for="credit-card-state">
                {{ trans('messages.account.state') }}
              </label>
    
              <input type="text" class="cc-short" name="state" id="credit-card-state" disabled="">
            </div>
    
            <div class="control-group cc-zip cc-zip-new
                        
                         col-md-6 col-lg-3">
              <label for="credit-card-zip">
                {{ trans('messages.payments.postal_code') }}
              </label>
              
              {!! Form::text('zip', '', ['id' => 'credit-card-zip', 'class' => 'cc-short cc-zip-text']) !!}

              @if ($errors->has('zip')) <div class="label label-warning inline-error">{{ $errors->first('zip') }}</div> @endif
            </div>
    
            <div class="col-md-6 col-lg-3">
              <label aria-hidden="true">
                <span class="screen-reader-only"></span>
                &nbsp;
              </label>
              <div class="help-inline credit-card-country-name">
                <strong id="billing-country"></strong>
              </div>
            </div>
          </div>
    
        </div>
      </div>
    </div>
   
      <div class="payment-method paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'active':''}}" id="payment-method-paypal">
        <div class="paypal-instructions row-space-top-2">
          <p>
           {{ trans('messages.payments.redirected_to_paypal') }}
                <strong></strong>
          </p>
        </div>
      </div>

<input name="payment_method" type="hidden" value="{{ (@Session::get('payment')[$s_key]['payment_card_type']!='PayPal') ? 'cc':''}}">
<input name="country" type="hidden" value="">
<input name="digital_river[country]" type="hidden" value="">
</section>
@endif
@if($reservation_id!='' || $booking_type == 'instant_book')
      <section class="checkout-main__section">
          <div class="row">
            <div class="col-sm-12"><h2>{{ trans('messages.payments.phoneno_info') }}</h2></div>
          </div>
          <div class="row">
            <div class="control-group cc-first-name col-md-4">
              <label class="control-label" for="credit-card-first-name">
                {{ trans('messages.profile.choose_a_country') }}
              </label>
              <select id="country_code" name="country_code" style="margin:0 0 15px !important;">
                @foreach($country_phone_codes as $k => $country1)
                  <option value="{{$country1->phone_code}}" >{{$country1->long_name}}(+{{$country1->phone_code}})</option>
                  @endforeach
              </select> 
              @if ($errors->has('country_code')) <div class="label label-warning inline-error">{{ $errors->first('country_code') }}</div> @endif
            </div>    
            <div class="control-group cc-last-name col-md-5">
              <label class="control-label" for="credit-card-last-name">
                {{ trans('messages.profile.phone_number') }}
              </label>
              <input type="text" name="phone_number" id="phone_number">
              @if ($errors->has('phone_number')) <div class="label label-warning inline-error">{{ $errors->first('phone_number') }}</div> @endif
            </div>
          </div>
          @if(count($ngo_detail) > 0)
          <div class="row">
            <div class="col-sm-12"><h2>NGO Donation Amount</h2></div>
          </div>
          <div class="row">
            <div class="col-md-12">
            @foreach($ngo_detail as $ngo)
              <div class="col-md-6" style="padding-bottom: 10px;">
                <table>
                  <tr>
                    <td><input type="checkbox" name="charity" value="{{ $ngo->id }}" class="apply_ngofee" {{ $ngo->checked }}></td>
                    <td>{{ $ngo->name }} <i rel="tooltip" class="icon icon-question" title="{{ $ngo->description }}"></i> :  {{ $result->rooms_price->currency->symbol }}{{ $ngo->amount }}</td>
                    </tr>
                  </table>
              </div>
              @endforeach
            </div>
          </div>
          @endif
      </section>
@endif
            
   
            <section class="checkout-main__section">
              @if($price_list->total == '0' && $price_list->coupon_code != '')
              <div class="row">
                  <div class="col-lg-6">
                    <label for="country-select">
                      {{ trans('messages.account.country') }}
                    </label>
                    
                    <div class="select select-block">
                      @if(Session::get('payment_country')) 
                         {!! Form::select('payment_country', $country, Session::get('mobile_payment_counry_code'), ['id' => 'country-select']) !!}
                      @else
                      {!! Form::select('payment_country', $country, $default_country, ['id' => 'country-select']) !!}
                      @endif
                    </div>
                  </div>
                </div>
                @endif
              <div>
                <h2>
                  {{ trans('messages.payments.tell_about_your_trip',['first_name'=>$result->users->first_name]) }}
                </h2>
                <p>
                  {{ trans('messages.payments.helful_trips') }}:
                </p>
                <ul>
                  <li>
                    {{ trans('messages.rooms.what_brings_you',['city'=>$result->rooms_address->city]) }}
                  </li>
                  <li>
                    {{ trans('messages.payments.checkin_plans') }}
                  </li>
                  <li>
                    {{ trans('messages.payments.ask_recommendations') }}
                  </li>
                </ul>

                  <div class="media space-3">
                    <div class="pull-left lang-chang-label">
                      
<div class="media-photo-badge">
@if(Session::get('get_token')=='')
  <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo media-round"><img alt="User Profile Image" class="" data-pin-nopin="true" height="115" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="115"></a>
  @else
  <a href="javascript:void(0);" class="media-photo media-round"><img alt="User Profile Image" class="" data-pin-nopin="true" height="115" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}" width="115"></a>
  @endif
</div>

                    </div>
                    <div class="media-body">
                      <div class="panel panel-quote panel-dark">
                        <p class="panel-body">
                          @if($result->booking_message)
                           {{ $result->booking_message }}
                           @else
                          {{ trans('messages.payments.welcome_to_city',['city'=>$result->rooms_address->city]) }}
                          @endif
                        </p>
                      </div>
                    </div>
                  </div>
              </div>

                <div class="media">
                  <div class="pull-left lang-chang-label">
@if(Session::get('get_token')!='')
<div class="media-photo-badge">
  <a href="javascript:void(0);" class="media-photo media-round"><img alt="User Profile Image" class="" data-pin-nopin="true" height="115" src="{{ @Session::get('payment')[$s_key]['mobile_user_image'] }}" title="" width="115"></a>
</div>
@else
<div class="media-photo-badge">
  <a href="{{ url('users/show/'.Auth::user()->id) }}" class="media-photo media-round"><img alt="User Profile Image" class="" data-pin-nopin="true" height="115" src="{{ Auth::user()->profile_picture->src }}" title="{{ Auth::user()->first_name }}" width="115"></a>
  </div>
@endif
                  </div>
                  <div class="media-body">
                    <div class="panel panel-quote">
                      <div class="message-to-host control-group">
                        <label for="message-to-host-input" class="screen-reader-only">
                          {{ trans('messages.payments.message_your_host') }}...
                        </label>
                         <!--payment_message_to_host set for Api start -->
                        <textarea id="message-to-host-input" name="message_to_host" rows="3" class="message-to-host-quote-input" placeholder="{{ trans('messages.payments.message_your_host') }}..."> @if(@Session::get('payment')[$s_key]['payment_message_to_host']){{ @Session::get('payment')[$s_key]['payment_message_to_host'] }} @endif</textarea>
                        <!--payment_message_to_host set for Api stop -->
                      </div>
                    </div>
                    <div class="label label-warning inline-error"></div>
                  </div>
                </div>
            </section>

        

          <section id="house-rules-agreement" class="checkout-main__section">
  <h2 class="section-title">
    {{ trans('messages.lys.house_rules') }}
  </h2>
  <p>
    {{ trans('messages.payments.by_booking_this_space',['first_name'=>$result->users->first_name]) }}.
  </p>
  <div class="row-space-2">
    <div class="expandable expandable-trigger-more house-rules-panel-body expanded">
      <div class="expandable-content" data-threshold="50">
        <p>{{ $result->rooms_description->house_rules }}</p>
        <div class="expandable-indicator"></div>
      </div>
    </div>
  </div>
</section>
        <section id="policies" class="policies row-space-3">
          <div class="terms media">
            <div class="media-body">
              <label for="agrees-to-terms">
                {{ trans('messages.payments.by_clicking',['booking_type'=>($booking_type == 'instant_book') ? ($price_list->total == '0') ? trans('messages.lys.continue') : trans('messages.payments.book_now') : trans('messages.lys.continue')]) }} 
                @foreach($company_pages as $company_page)
                @if($company_page->name=='Terms of Service')
                <a href="{{ url('terms_of_service') }}" class="terms_link" target="_blank">{{ trans('messages.login.terms_service') }}</a>,
                @endif
                @if($company_page->name=='Guest Refund')
                <a href="{{ url('guest_refund') }}" class="refund_policy_link" target="_blank">{{ trans('messages.login.guest_policy') }}</a>,
                @endif
                @endforeach 
                <a href="#house-rules-agreement" class="house-rules-link">{{ trans('messages.lys.house_rules') }}</a> {{ trans('messages.header.and') }} <a href="{{ url('home/cancellation_policies#flexible') }}" class="cancel-policy-link" target="_blank">{{ trans('messages.payments.cancellation_policy') }}</a>              
              </label>
            </div>
          </div>
        </section>
        <p>
          </p><div id="paypal-container"></div>
           <a id="payment-form-submit" class="btn btn-large btn-primary" ng-click="disableButton()">
            {{ ($booking_type == 'instant_book') ? ($price_list->total == '0') ? trans('messages.lys.continue') : trans('messages.payments.book_now') : trans('messages.lys.continue') }}
           </a>
        <p></p>

        <p class="book-now-explanation default">
            
        </p>
        <p class="book-now-explanation immediate_charge hide">
          {{ trans('messages.payments.clicking') }} <strong>{{ trans('messages.lys.continue') }}</strong> {{ trans('messages.payments.charge_your_payment') }}
        </p>
        <p class="book-now-explanation deferred_payment hide">
          {{ trans('messages.payments.host_will_reply') }}
        </p>
      </div>
    </div>
  </form>

  <div id="house-rules-modal" class="modal" role="dialog" aria-hidden="true">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <div class="panel-header">
            <a href="javascript:void(0);" class="panel-close" data-behavior="modal-close">
              ×
              <span class="screen-reader-only">
                {{ trans('messages.payments.house_rules') }}
              </span>
            </a>
            {{ trans('messages.payments.house_rules') }}
          </div>
          <div class="panel-body"><p>{{ $result->rooms_description->house_rules }}</p></div>
        </div>
      </div>
    </div>
  </div>

  <div id="security-deposit-modal" class="modal" role="dialog" data-trigger="#security-deposit-modal-trigger" aria-hidden="true">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <div class="panel-header">
            <a href="{{ url('payments/book?hosting_id=3357255&s=q315#') }}" class="panel-close" data-behavior="modal-close">
              ×
              <span class="screen-reader-only">
                {{ trans('messages.payments.security_deposit') }}
              </span>
            </a>
            {{ trans('messages.payments.security_deposit') }}
          </div>
          <div class="panel-body">
            <p>
              {{ trans('messages.payments.security_deposit_collected',['site_name'=>$site_name]) }}
            </p>
            <p>
              {{ trans('messages.payments.host_reports_problem',['site_name'=>$site_name]) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    </main>


    <div id="gmap-preload" class="hide"></div>
   
<div class="ipad-interstitial-wrapper"><span data-reactid=".1"></span></div>


    <div id="fb-root" class=" fb_reset"><div style="position: absolute; top: -10000px; height: 0px; width: 0px;"><div></div></div><div style="position: absolute; top: -10000px; height: 0px; width: 0px;"><div></div></div></div>

<div class="tooltip tooltip-top-middle" role="tooltip" data-trigger="#tooltip-cvv" aria-hidden="true">
      <div class="tooltip-cvv"></div>
    </div><div class="tooltip tooltip-bottom-middle" role="tooltip" aria-hidden="true">  <p class="panel-body">{{ trans('messages.payments.fee_charged_by',['site_name'=>$site_name]) }}</p></div></body></html>

@stop
@push('scripts')
  @if(Request::offsetGet('s_key') == '')
    <script type="text/javascript">
      url = window.location.href;
      url += (url.match(/\?/) ? '&' : '?') + "s_key={{$s_key}}";
      history.replaceState(null, null, url);
    </script>
  @endif
<script type="text/javascript">
  // if(typeof $.stickysidebarscroll !== "undefined"){
  //    if ($(window).width() > 760){ 
  //     $.stickysidebarscroll("#payment-right",{offset: {top: 20, bottom: 20}});
  //   }
  //      }
  // $(window).resize(function () { 
  //   $(window).scrollTop( 0 );
  // });

  //credit card number inputs allow only digits validation
 $("#credit-card-number").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });

  function sticky_relocate4() {
      var window_top = $(window).scrollTop();
      var footer_top = $("#payment-form-submit").offset().top;
      var div_top = $('#checkout-form').offset().top;

      window_height = $(window).height();
      window_bottom  = $(window).scrollTop() + $(window).height()
      document_height = $(document).height();

      cancellation_policy_div = $('#content-container');
      cancellation_policy_div_top = cancellation_policy_div.offset().top;
      cancellation_policy_div_height = cancellation_policy_div.height();
      cancellation_policy_div_bottom = cancellation_policy_div_top+cancellation_policy_div_height;

      sticky_div = $("#sticky4");
      sticky_div_top = sticky_div.offset().top;
      sticky_div_height = sticky_div.height();
      sticky_div_bottom = sticky_div_top + sticky_div_height;

      stick_height = $("#sticky4").height();
      window_height = $(window).height();
      var div_height =  stick_height < window_height ? stick_height : window_height;
      var window_width = $(window).width();

      var padding = 10;  
      
      if ((window_top + div_height > footer_top +padding) && window_width >= 768){
        top_position = (cancellation_policy_div_bottom - sticky_div_height);
        // console.log(top_position);
        // console.log( sticky_div_bottom, cancellation_policy_div_bottom, top_position, (window_bottom), document_height );

        $('#sticky4').css({top: (top_position - 200 ), position : 'absolute'})
      }
      else if ((window_top > div_top) &&  window_width >= 768) {
        $('#sticky4').addClass('stick4');
        $('#sticky4').css({top: 0, position: 'fixed'})
        
      } else {
        $('#sticky4').removeClass('stick4');
        $('#sticky4').css({position : 'relative'});
      }
    }
    $(function () {
      $(document).scroll(function(e){
        e.preventDefault();
        sticky_relocate4();
      });
      sticky_relocate4();
    });

</script>
@endpush