<div class="login-close">
    <div class="login_popup" id="dispute_payment_popup">
        <div class="page-container-responsive page-container-auth margintop">
            <div class="row">
                <div class="payment_popup col-center" style="max-width: 700px;">
                    <div class="panel top-home">
                        <div class="panel-body pad-25 bor-none padd1 ">
                            <i class="icon-remove-1 rm_lg">
                            </i>
                            <form method="POST" action="{{url('dispute_pay_amount/'.$dispute->id)}}" id="dispute_payment_form">
                                <section id="payment" class="checkout-main__section payment">
                                    <h2 class="section-title">{{ trans('messages.payments.payment') }}
                                    </h2>
                                    <input type="hidden" name="payment_message" id="input_payment_message" ng-model="dispute_payment_data.message">
                                    <div class="payment-section">
                                        <div class="row">
                                            <div class="col-lg-6" ng-init="dispute_payment_data.country = '{{$default_country_code ?: @array_keys($country->toArray())[0] }}'">
                                                <label for="country-select">
                                                    {{ trans('messages.account.country') }}
                                                </label>
                                                <div class="select select-block">
                                                    {!! Form::select('payment_country', $country, $default_country_code, ['id' => 'country-select','ng-model' => 'dispute_payment_data.country']) !!}
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
                                                <div class="col-lg-6 row-space-2"  ng-init="dispute_payment_data.payment_type = 'cc'">
                                                    <div class="select select-block">
                                                        <select name="payment_type" class="grouped-field" id="payment-method-select" ng-model="dispute_payment_data.payment_type">
                                                            <!--change for Api payment_type-->
                                                            <option value="cc" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
                                                                {{ trans('messages.payments.credit_card') }}
                                                            </option>
                                                            <option value="paypal" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
                                                                PayPal
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="payment-method grouped-field cc" style="display:block" >
                                                        <div class="payment-logo visa">{{ trans('messages.payments.credit_card') }}
                                                        </div>
                                                        <div class="payment-logo master">
                                                        </div>
                                                        <div class="payment-logo american_express">
                                                        </div>
                                                        <div class="payment-logo discover">
                                                        </div>
                                                        <i class="icon icon-lock icon-light-gray h3">
                                                        </i>
                                                    </div>
                                                    <div class="payment-method grouped-field paypal">
                                                        <div class="payment-logo paypal">PayPal
                                                        </div>
                                                    </div>
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
                                                                {!! Form::text('cc_number', '', ['class' => 'cc-med', 'id' => 'credit-card-number', 'autocomplete' => 'off', 'ng-model' => 'dispute_payment_data.cc_number']) !!}
                                                                <div class="label label-warning inline-error">@{{dispute_form_errors.cc_number[0]}}
                                                                </div>
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
                                                                            {!! Form::selectRangeWithDefault('cc_expire_month', 1, 12, null, 'mm', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-month', 'ng-model' => 'dispute_payment_data.cc_expire_month']) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="select select-block">
                                                                            <label for="credit-card-expire-year" class="screen-reader-only">
                                                                                {{ trans('messages.login.year') }}
                                                                            </label>
                                                                            {!! Form::selectRangeWithDefault('cc_expire_year', date('Y'), date('Y')+30, null, 'yyyy', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-year', 'ng-model' => 'dispute_payment_data.cc_expire_year']) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="label label-warning inline-error">
                                                                    @{{dispute_form_errors.cc_expire_month[0] ? dispute_form_errors.cc_expire_month[0] : dispute_form_errors.cc_expire_year[0]}}
                                                                </div> 
                                                            </div>
                                                            <div class="control-group cc-security-code col-md-4">
                                                                <label class="control-label" for="credit-card-security-code">
                                                                    {{ trans('messages.payments.security_code') }}
                                                                </label>
                                                                <div class="row">
                                                                    <div class="col-sm-6 col-md-8">
                                                                        {!! Form::text('cc_security_code', '', ['class' => 'cc-short', 'id' => 'credit-card-security-code', 'autocomplete' => 'off', 'ng-model' => 'dispute_payment_data.cc_security_code']) !!}
                                                                    </div>
                                                                </div>
                                                                <div class="label label-warning inline-error">@{{dispute_form_errors.cc_security_code[0]}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h2>{{ trans('messages.payments.billing_info') }}
                                                                </h2>
                                                                <p>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="control-group cc-first-name col-md-6">
                                                                <label class="control-label" for="credit-card-first-name">
                                                                    {{ trans('messages.login.first_name') }}
                                                                </label>
                                                                {!! Form::text('first_name', '', ['id' => 'credit-card-first-name', 'ng-model' => 'dispute_payment_data.first_name']) !!}
                                                                <div class="label label-warning inline-error">@{{dispute_form_errors.first_name[0]}}
                                                                </div>
                                                            </div>
                                                            <div class="control-group cc-last-name col-md-6">
                                                                <label class="control-label" for="credit-card-last-name">
                                                                    {{ trans('messages.login.last_name') }}
                                                                </label>
                                                                {!! Form::text('last_name', '', ['id' => 'credit-card-last-name', 'ng-model' => 'dispute_payment_data.last_name']) !!}
                                                                <div class="label label-warning inline-error">@{{ dispute_form_errors.last_name[0] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="control-group cc-zip cc-zip-new col-md-6 col-lg-3">
                                                                <label for="credit-card-zip">
                                                                    {{ trans('messages.payments.postal_code') }}
                                                                </label>
                                                                {!! Form::text('zip', '', ['id' => 'credit-card-zip', 'class' => 'cc-short cc-zip-text', 'ng-model' => 'dispute_payment_data.zip']) !!}
                                                                <div class="label label-warning inline-error">@{{ dispute_form_errors.zip[0] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-3">
                                                                <label aria-hidden="true">
                                                                    <span class="screen-reader-only"></span>
                                                                    &nbsp;
                                                                </label>
                                                                <div class="help-inline credit-card-country-name">
                                                                    <strong id="billing-country">
                                                                    </strong>
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
                                                        <strong>
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary" type="button" ng-click="pay_dispute_amount()">{{trans('messages.lys.continue')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
