<div class="reguest reguest1">
	<h1>{{trans('experiences.payment.confirm_and_pay')}}
	</h1>
	<div class="bill">
		<label>{{trans('experiences.payment.billing_country')}}
		</label>
		<div class="billing">
			{!! Form::select('payment_country', $countries, $default_country_code, ['id' => 'select_payment_country']) !!}
		</div>
	</div>
	<div class="pay" ng-show="payment_data.total > 0">
		<label>{{trans('experiences.payment.pay_with')}}
		</label>
		<div class="paymode_logo card" ng-cloak>
			<div class="payment_logo cc" ng-show="paymode == 'cc'">
				<div class="payment-logo visa">
				</div>
				<div class="payment-logo master">
				</div>
				<div class="payment-logo american_express">
				</div>
				<div class="payment-logo discover">
				</div>
			</div>
			<div class="payment_logo paypal" ng-show="paymode == 'paypal'">
				<div class="payment-logo paypal">PayPal</div>
			</div>
		</div>
		<div class="billing" ng-init="paymode = '{{$card_type}}';">
          <select name="paymode" class="" ng-model="paymode">
              <option value="cc">
               {{ trans('messages.payments.credit_card') }}
              </option>
              <option value="paypal">
                PayPal
              </option>
             <!--  <option value="Coupon_code" hidden>
                Coupon Code
              </option> -->
          </select>
		</div>
		<div class="paymode_data" ng-cloak>
			<div class="row payment_data cc" ng-show="paymode == 'cc'">
				<div class="control-group space-top-2 col-md-12">
					<span for="input_cc_number">{{ trans('messages.payments.card_number') }}
					</span>
					{!! Form::text('cc_number', '', ['class' => '', 'id' => 'input_cc_number', 'autocomplete' => 'off']) !!}
					<div class="label label-warning inline-error">{{ $errors->first('cc_number') }}
					</div> 
              	</div>
              	<div class="row space-top-2">
	              	<div class="col-md-8 ">
	              		<div class="col-md-12">
		              		<span for="input_cc_expire_month">{{ trans('messages.payments.expires_on') }}
							</span>
						</div>
						<div class="control-group col-md-6 space-top-1">
							<div class="select select-block">
								<label for="input_cc_expire_month" class="screen-reader-only">
								{{ trans('messages.login.month') }}
								</label>
		                  		{!! Form::selectRangeWithDefault('cc_expire_month', 1, 12, null, 'mm', [ 'class' => '', 'id' => 'input_cc_expire_month']) !!}
		                    </div>
		              	</div>
						<div class="control-group col-md-6 space-top-1">
							<div class="select select-block">
								<label for="input_cc_expire_year" class="screen-reader-only">
								{{ trans('messages.login.year') }}
								</label>
		                  		{!! Form::selectRangeWithDefault('cc_expire_year', date('Y'), date('Y')+30, null, 'yyyy', [ 'class' => '', 'id' => 'input_cc_expire_year']) !!}
		                    </div>
		              	</div>
		              	@if ($errors->has('cc_expire_month') || $errors->has('cc_expire_year'))
		                <div class="label label-warning inline-error col-md-12">
			                @if ($errors->has('cc_expire_month'))
			                  {{ $errors->first('cc_expire_month') }}
			                @endif
			                @if ($errors->has('cc_expire_month') == '')
			                  {{ $errors->first('cc_expire_year') }}
			                @endif
		                </div> 
		                @endif
					</div>
	              	<div class="col-md-4">
	              		<div class="col-md-12">
		              		<span for="input_cc_security_code">{{ trans('messages.payments.security_code') }}
							</span>
						</div>
						<div class="control-group col-md-12 space-top-1">
	                  		{!! Form::text('cc_security_code', '', ['class' => '', 'id' => 'input_cc_security_code', 'autocomplete' => 'off']) !!}
		              	</div>
		                <div class="label label-warning inline-error col-md-12">{{ $errors->first('cc_security_code') }}
		                </div> 
					</div>
				</div>
				<div class="col-md-12 space-top-2">
					<h4>{{ trans('messages.payments.billing_info') }}
					</h4>
					<div class="row">
						<div class="control-group col-md-6">
							<span class="control-label" for="input_first_name">{{ trans('messages.login.first_name') }}
							</span>
							{!! Form::text('first_name', '', ['id' => 'input_first_name']) !!}
							<div class="label label-warning inline-error">{{ $errors->first('first_name') }}</div>
			            </div>
						<div class="control-group col-md-6">
							<span class="control-label" for="input_last_name">{{ trans('messages.login.last_name') }}
							</span>
							{!! Form::text('last_name', '', ['id' => 'input_last_name']) !!}
							<div class="label label-warning inline-error">{{ $errors->first('last_name') }}</div>
			            </div>
						<div class="control-group col-md-6">
							<span class="control-label" for="input_postal_code">{{ trans('messages.account.postal_code') }}
							</span>
							{!! Form::text('postal_code', '', ['id' => 'input_postal_code']) !!}
							<div class="label label-warning inline-error">{{ $errors->first('postal_code') }}</div>
			            </div>
		            </div>
				</div>
			</div>
		</div>
	</div>
	<div class="redeem">
		<a href="javascript:void(0);" ng-show="!payment_data.is_coupon_code == true" ng-click="payment_data.is_coupon_code = true;">{{trans('experiences.payment.redeem_a_coupon')}}
		</a>
		<span ng-show="payment_data.is_coupon_code == true">{{trans('experiences.payment.redeem_a_coupon')}}</span>
		<div class="fortext" ng-show="payment_data.is_coupon_code">
			<input type="text" name="coupon_code" ng-model="payment_data.coupon_code" ng-change="coupon_code_changed();">
			<a href="javascript:void(0);" ng-show="!payment_data.coupon_code_applied && payment_data.coupon_code.length >0" ng-click="apply_coupon_code()">{{trans('experiences.payment.apply')}}
			</a>
			<a href="javascript:void(0);" ng-hide="!payment_data.coupon_code_applied && payment_data.coupon_code.length >0" ng-click="remove_coupon_code()">{{trans('experiences.manage.cancel')}}
			</a>
			<br>
			<span class="text-warning">@{{payment_data.coupon_code_error}}</span>
		</div>
	</div>
	<div class="team">
		<h4> {{trans('messages.login.terms_service')}}
		</h4>
		<div class="mob">
			@yield('price_data')
			@yield('cancellation_policy_data')
		</div>
		<p>{{trans('experiences.payment.by_confirm_booking_agree_to_the')}}
			<a target="_blank" href="{{url('terms_of_service')}}">{{trans('experiences.payment.site_additional_terms_service', ['site_name' => SITE_NAME])}}
			</a>,
			<a target="_blank" href="{{url('guest_release_and_waiver')}}"> {{trans('experiences.payment.guest_release_and_waiver')}}
			</a>, {{trans('experiences.payment.and_the')}}
			<a target="_blank" href="{{url('hosts_experience_cancellation_policy')}}">{{trans('experiences.payment.cancellation_policy')}}
			</a>.
		</p>
	</div>
</div>
<div class="whocan1 reguest who">
	<div class="butget">
		<button type="button" ng-click="next_step()" id="btnSubmit">{{trans('experiences.payment.confirm_and_pay')}}
		</button>
	</div>
</div>
