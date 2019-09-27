<style type="text/css">
  /*html[lang="ar"] #js-manage-listing-nav{position: fixed !important;right: 0px !important;}*/
  @media (min-width: 767px){
    #ajax_container{margin-left:25%;}
    html[lang="ar"] #js-manage-listing-nav{position: fixed;}
    html[lang="ar"] #ajax_container{margin-right:25% !important;margin-left: 0px !important;}
  }
  @media(min-width: 1100px){
    #ajax_container{margin-left: 16.66667%;}
    html[lang="ar"] #ajax_container{margin-right: 16.66667% !important; margin-left: 0px !important;}
  }
</style>
<div id="js-manage-listing-content-container" class="manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div id="js-manage-listing-content" class="manage-listing-content col-lg-7 col-md-7">
      <div>
        <div class="row-space-4">
          <div class="row">
            <h3 class="col-12">{{ trans('messages.lys.pricing_title') }}
            </h3>
          </div>
          <p class="text-muted extra_muted">{{ trans('messages.lys.pricing_desc') }}
          </p>
        </div>
        <hr>
        <div id="help-panel-nightly-price" class="js-section">
          <div style="display: none;" class="js-saving-progress saving-progress base_price">
            <h5>{{ trans('messages.lys.saving') }}...
            </h5>
          </div>
          <h4>{{ trans('messages.lys.base_price') }}
          </h4>
          <label for="listing_price_native" class="label-large">{{ trans('messages.lys.nightly_price') }}
          </label>
          <div class="row">
            <div class="col-md-6 col-sm-10 pricing_field_list">
              <div class="input-addon price_field">
                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                </span>
                <input type="number" min="0" limit-to=9 data-suggested="" id="price-night" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night" class="input-stem input-large autosubmit-text" data-saving="base_price">
                <input type="hidden" id="price-night-old" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night_old" class="input-stem input-large autosubmit-text">
              </div>
            </div>
            <div class="col-8 col-middle">
              <!-- <span class="text-highlight"> -->
              <!-- <strong>Price tip: ₹805</strong> -->
              <!-- </span>  -->
              <!-- <span id="suggestion_disclaimer_daily" class="icon icon-question grey" >  -->
              <!-- <i=""></i> -->
              <!-- </span> -->
            </div>
          </div>
          <p data-error="price" class="ml-error">
          </p>
          <div class="row row-space-top-3">
            <div class="col-md-6 col-sm-10 pricing_field_list">
              <label class="label-large">{{ trans('messages.account.currency') }}
              </label>
              <div id="currency-picker">
                <div class="select select-large select-block">
                  {!! Form::select('currency_code',$currency, $result->rooms_price->currency_code, ['id' => 'price-select-currency_code', 'data-saving' => 'base_price']) !!}
                </div>
              </div>
            </div>
          </div>
        </div>
        {{--
        @if($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0)
        <p id="js-set-long-term-prices" class="row-space-top-6 text-center text-muted set-long-term-prices">
          {{ trans('messages.lys.offer_discounts') }} 
          <a data-prevent-default="" href="javascript:void(0)" id="show_long_term">{{ trans('messages.lys.weekly_monthly') }}
          </a> {{ trans('messages.lys.prices') }}.
        </p>
        <hr class="row-space-top-6 row-space-5 set-long-term-prices">
        @endif
        <div id="js-advanced-pricing-content">
          <!-- Modal for advanced pricing goes here -->
        </div>
        <div class="row-space-top-8 row-space-8 {{ ($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0) ? 'hide' : '' }}" id="js-long-term-prices">
          <div id="js-long-term-prices" class="js-section">
            <div style="display: none;" class="js-saving-progress saving-progress long_price">
              <h5>{{ trans('messages.lys.saving') }}...
              </h5>
            </div>
            <h4>{{ trans('messages.lys.long_term_prices') }}
            </h4>
            <div class="row-space-3">
              <div>
                <label for="listing_weekly_price_native" class="label-large">
                  {{ trans('messages.lys.weekly_price') }}
                </label>
                <div class="row">
                  <div class="col-md-6 col-sm-10 pricing_field_list ">
                    <div class="input-addon price_field">
                      <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                      </span>
                      <input type="number" min="0" limit-to=9 data-suggested="" id="price-week" class="input-stem input-large autosubmit-text" value="{{ ($result->rooms_price->original_week == 0) ? '' : $result->rooms_price->original_week }}" name="week" data-saving="long_price">
                    </div>
                  </div>
                  <div class="col-8 col-middle text-highlight">
                  </div>
                </div>
                <p data-error="week" class="ml-error hide">
                </p>
                <div class="js-advanced-weekly-pricing">
                </div>
              </div>
            </div>
            <div class="row-space-3">
              <div>
                <label for="listing_monthly_price_native" class="label-large">
                  {{ trans('messages.lys.monthly_price') }}
                </label>
                <div class="row">
                  <div class="col-md-6 col-sm-10 pricing_field_list ">
                    <div class="input-addon price_field">
                      <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                      </span>
                      <input type="number" min="0" limit-to=9 data-suggested="₹16905" id="price-month" class="autosubmit-text input-stem input-large" value="{{ ($result->rooms_price->original_month == 0) ? '' : $result->rooms_price->original_month }}" name="month" data-saving="long_price">
                    </div>
                  </div>
                  <div class="col-8 col-middle text-highlight">
                  </div>
                </div>
                <p data-error="month" class="ml-error hide">
                </p>
                <span class="js-advanced-monthly-pricing">
                </span>
              </div>
            </div>
          </div>
        </div>
        --}}
        <div class="js-section">
          <hr class="row-space-top-6 row-space-5">
          <div style="display: none;" class="js-saving-progress saving-progress additional-saving">
            <h5>{{ trans('messages.lys.saving') }}...
            </h5>
          </div>
          <h4>{{ trans('messages.lys.additional_pricing') }}
          </h4>
          <p class="text-muted extra_muted">
          </p>
          <div id="js-cleaning-fee" class="js-tooltip-trigger">
            <label for="listing_cleaning_fee_native_checkbox" class="label-large label-inline">
              <input type="checkbox" data-extras="true" ng-model="cleaning_checkbox" id="listing_cleaning_fee_native_checkbox" ng-init="cleaning_checkbox = {{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}">
              {{ trans('messages.lys.cleaning') }}
            </label>
            <div class="pricing_extra_amt" data-checkbox-id="listing_cleaning_fee_native_checkbox" ng-show="cleaning_checkbox" ng-cloak>
              <div class="row">
                <div class="col-md-6 col-sm-10 pricing_field_list ">
                  <div class="input-addon price_field">
                    <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                    </span>
                    <input type="number" min="0" limit-to=9 data-extras="true" id="price-cleaning" value="{{ ($result->rooms_price->original_cleaning == 0) ? '' : $result->rooms_price->original_cleaning }}" name="cleaning" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                  </div>
                </div>
                <div class="col-8 col-middle">
                </div>
              </div>
              <p class="text-muted extra_muted1">
                {{ trans('messages.lys.cleaning_desc') }}
              </p>
              <p data-error="extras_price" class="ml-error">
              </p>
            </div>
          </div>
          <div id="js-additional-guests" class="js-tooltip-trigger">
            <label for="price_for_extra_person_checkbox" class="label-large label-inline">
              <input type="checkbox" data-extras="true" ng-model="extra_person_checkbox" id="price_for_extra_person_checkbox" ng-init="extra_person_checkbox = {{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}">
              {{ trans('messages.lys.additional_guests') }}
            </label>
            <div class="pricing_extra_amt" data-checkbox-id="price_for_extra_person_checkbox" ng-show="extra_person_checkbox" ng-cloak>
              <div class="row">
                <div class="col-md-6 col-sm-10 pricing_field_list ">
                  <div class="input-addon price_field">
                    <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                    </span>
                    <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_additional_guest == 0) ? '' : $result->rooms_price->original_additional_guest }}" id="price-extra_person" name="additional_guest" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                  </div>
                </div>
              </div>
               <div class="row">
                <div class="col-md-6 col-sm-10 pricing_field_list text-right">
                  <label class="label-large label_right">{{ trans('messages.lys.for_each_guest_after') }}
                  </label>
                  <div id="guests-included-select">
                    <div class="select select-large select-block">
                      <select id="price-select-guests_included" name="guests" data-saving="additional-saving">
                        @for($i=1;$i
                        <=16;$i++)
                        <option value="{{ $i }}" {{ ($result->rooms_price->guests == $i) ? 'selected' : '' }}>
                          {{ ($i == '16') ? $i.'+' : $i }}
                        </option>
                        @endfor 
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
              <p data-error="price_for_extra_person" class="ml-error extra_muted">
              </p>
              <p class="text-muted extra_muted1">
                {{ trans('messages.lys.additional_guests_desc') }}
              </p>
            </div>
              </div>
               
              </div>
            </div>
          </div>
          <div id="js-security-deposit" class="js-tooltip-trigger">
            <label for="listing_security_deposit_native_checkbox" class="label-large label-inline">
              <input type="checkbox" data-extras="true" ng-model="security_checkbox" id="listing_security_deposit_native_checkbox" ng-init="security_checkbox = {{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}">
              {{ trans('messages.lys.security_deposit') }}
            </label>
            <div class="pricing_extra_amt" data-checkbox-id="listing_security_deposit_native_checkbox" ng-show="security_checkbox" ng-cloak>
              <div class="row">
                <div class="col-md-6 col-sm-10 pricing_field_list ">
                  <div class="input-addon price_field">
                    <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                    </span>
                    <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_security == 0) ? '' : $result->rooms_price->original_security }}" id="price-security" name="security" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                  </div>
                </div>
              </div>
              <p data-error="security_deposit" class="ml-error">
              </p>
              <p class="text-muted extra_muted1">
                {{ trans('messages.lys.security_deposit_desc') }}
              </p>
            </div>
          </div>
          <div id="js-weekend-pricing" class="js-tooltip-trigger">
            <label for="listing_weekend_price_native_checkbox" class="label-large label-inline">
              <input type="checkbox" data-extras="true" ng-model="weekend_checkbox" id="listing_weekend_price_native_checkbox" ng-init="weekend_checkbox = {{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}">
              {{ trans('messages.lys.weekend_pricing') }}
            </label>
            <div class="pricing_extra_amt" data-checkbox-id="listing_weekend_price_native_checkbox" ng-show="weekend_checkbox" ng-cloak>
              <div class="row">
                <div class="col-md-6 col-sm-10 pricing_field_list ">
                  <div class="input-addon price_field">
                    <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}
                    </span>
                    <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_weekend == 0) ? '' : $result->rooms_price->original_weekend }}" id="price-weekend" name="weekend" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                  </div>
                </div>
              </div>
              <p class="text-muted extra_muted1">
                {{ trans('messages.lys.weekend_pricing_desc') }}
              </p>
            </div>
          </div>
        </div>

        <div class="js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
          <hr class="row-space-top-6 row-space-5 ">
          <div style="display: none;" class="js-saving-progress saving-progress price_rules-length_of_stay-saving">
            <h5>{{ trans('messages.lys.saving') }}...
            </h5>
          </div>
          <h4>{{ trans('messages.lys.length_of_stay_discounts') }}
          </h4>
          <p class="text-muted extra_muted">
          </p>
          <div id="js-length_of_stay_wrapper" class="row-space-3 js-tooltip-trigger"  ng-init="length_of_stay_items = {{json_encode($result->length_of_stay_rules)}}; length_of_stay_options= {{json_encode($length_of_stay_options)}}; ls_errors= [];">
            <div class="row">
              <div class="col-md-12" ng-repeat="item in length_of_stay_items">
                <div class="row">
                  <div class="length_whole">
                  <input type="hidden" name="length_of_stay[@{{$index}}][id]" value="@{{item.id}}">
                  <div class="col-md-5 col-sm-12 pricing_field_list">
                    <div class="select select-large select-block">
                      <select name="length_of_stay[@{{$index}}][period]" class="form-control ls_period" id="length_of_stay_period_@{{$index}}" data-index="@{{$index}}" ng-model="length_of_stay_items[$index].period">
                        <option disabled>
                          {{trans('messages.lys.select_nights')}}
                        </option>
                        <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights) || option.nights == item.period" ng-selected="item.period == option.nights" value="@{{option.nights}}">
                          @{{option.text}}
                        </option>
                      </select>
                    </div>
                    <p class="ml-error">@{{ls_errors[$index]['period'][0]}}</p>
                  </div>
                  <div class="col-md-5 col-sm-10 pricing_field_list" id="">
                    <div class="input-addon price_field1">
                      <input type="text" name="length_of_stay[@{{$index}}][discount]" class="form-control ls_discount" id="length_of_stay_discount_@{{$index}}" data-index="@{{$index}}" ng-model="length_of_stay_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}" >
                      <span class="input-suffix">
                        %
                      </span>
                    </div>
                    <p class="ml-error">@{{ls_errors[$index]['discount'][0]}}</p>
                  </div>
                  <div class="col-md-2 col-sm-2 pricing_field_list">
                    <div class="row">
                    <button href="javascript:void(0)" class="btn btn-danger btn-xs delete_length" id="js-length_of_stay-rm-btn-@{{$index}}" ng-click="remove_price_rule('length_of_stay', $index)">
                      <span class="fa fa-trash">
                      </span>
                    </button>
                  </div>
                  </div>
                </div>
                </div>
              </div>
              <div class="col-md-12 row-space-top-1" ng-init="length_of_stay_period_select = ''" ng-show="length_of_stay_items.length < length_of_stay_options.length">
                <div class="row">
                  <div class="col-md-6 col-sm-10 pricing_field_list " >
                    <div class="select select-large select-block">
                      <select name="" class="form-control" id="length_of_stay_period_select" ng-model="length_of_stay_period_select" ng-change="add_price_rule('length_of_stay')">
                        <option value="">
                          {{trans('messages.lys.select_nights')}}
                        </option>
                        <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights)" value="@{{option.nights}}">
                          @{{option.text}}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>

        <div class="js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
          <hr class="row-space-top-5 row-space-5 ">
          <div style="display: none;" class="js-saving-progress saving-progress price_rules-early_bird-saving">
            <h5>{{ trans('messages.lys.saving') }}...
            </h5>
          </div>
          <h4>{{ trans('messages.lys.early_bird_discounts') }}
          </h4>
          <p class="text-muted extra_muted">
          </p>
          <div id="js-early_bird_wrapper" class="row-space-3 js-tooltip-trigger"  ng-init="early_bird_items = {{json_encode($result->early_bird_rules)}}; eb_errors= [];">
            <div class="row">
              
              <div class="col-md-12" ng-repeat="item in early_bird_items">
                <div class="row">
                  <div class="early_bird_whole">
                  <input type="hidden" name="early_bird[@{{$index}}][id]" value="@{{item.id}}">
                  <div class="col-md-5 col-sm-12 pricing_field_list">
                    <div class="input-addon price_field1 early_bird_fillopt">
                      <input type="text" name="early_bird[@{{$index}}][period]" class="form-control eb_period" id="early_bird_period_@{{$index}}" data-index="@{{$index}}" ng-model="early_bird_items[$index].period" placeholder="{{trans('messages.lys.no_of_days')}}">
                      <span class="input-suffix" style="text-transform: capitalize;">
                        {{trans_choice('messages.reviews.day', 2)}}
                      </span>
                    </div>
                    <p class="ml-error">@{{eb_errors[$index]['period'][0]}}</p>
                  </div>
                  <div class="col-md-5 col-sm-10 pricing_field_list">
                    <div class="input-addon price_field1">
                      <input type="text" name="early_bird[@{{$index}}][discount]" class="form-control eb_discount" id="early_bird_discount_@{{$index}}" data-index="@{{$index}}" ng-model="early_bird_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}">
                      <span class="input-suffix">
                        %
                      </span>
                    </div>
                    <p class="ml-error">@{{eb_errors[$index]['discount'][0]}}</p>
                  </div>
                  <div class="col-md-2 col-sm-2 pricing_field_list">
                    <div class="row">
                    <button href="javascript:void(0)" class="btn btn-danger btn-xs  delete_length" id="js-early_bird-rm-btn-@{{$index}}" ng-click="remove_price_rule('early_bird', $index)">
                      <span class="fa fa-trash">
                      </span>
                    </button>
                  </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="col-md-12" >
                <div class="row">
                  <div class="col-md-6 col-sm-10 pricing_field_list " >
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" ng-click="add_price_rule('early_bird')">
                      <span class="fa fa-plus">
                      </span>
                      {{trans('messages.lys.add')}}
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
          <hr class="row-space-top-6 row-space-5 ">
          <div style="display: none;" class="js-saving-progress saving-progress price_rules-last_min-saving">
            <h5>{{ trans('messages.lys.saving') }}...
            </h5>
          </div>
          <h4>{{ trans('messages.lys.last_min_discounts') }}
          </h4>
          <p class="text-muted extra_muted">
          </p>
          <div id="js-last_min_wrapper" class="row-space-3 js-tooltip-trigger"  ng-init="last_min_items = {{json_encode($result->last_min_rules)}}; lm_errors= [];">
            <div class="row">
              <div class="col-md-12" ng-repeat="item in last_min_items">
                <div class="row">
                  <div class="early_bird_whole">
                  <input type="hidden" name="last_min[@{{$index}}][id]" value="@{{item.id}}">
                  <div class="col-md-5 col-sm-12 pricing_field_list">
                    <div class="input-addon price_field1 early_bird_fillopt">
                      <input type="text" name="last_min[@{{$index}}][period]" class="form-control lm_period" id="last_min_period_@{{$index}}" data-index="@{{$index}}" ng-model="last_min_items[$index].period" placeholder="{{trans('messages.lys.no_of_days')}}">
                      <span class="input-suffix" style="text-transform: capitalize;">
                        {{trans_choice('messages.reviews.day', 2)}}
                      </span>
                    </div>
                    <p class="ml-error">@{{lm_errors[$index]['period'][0]}}</p>
                  </div>
                  <div class="col-md-5 col-sm-10 pricing_field_list" >
                    <div class="input-addon price_field1">
                      <input type="text" name="last_min[@{{$index}}][discount]" class="form-control lm_discount" id="last_min_discount_@{{$index}}" data-index="@{{$index}}" ng-model="last_min_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}">
                      <span class="input-suffix">
                        %
                      </span>
                    </div>
                    <p class="ml-error">@{{lm_errors[$index]['discount'][0]}}</p>
                  </div>
                  <div class="col-md-2 col-sm-2 pricing_field_list" >
                    <div class="row">
                    <button href="javascript:void(0)" class="btn btn-danger btn-xs  delete_length" id="js-last_min-rm-btn-@{{$index}}" ng-click="remove_price_rule('last_min', $index)">
                      <span class="fa fa-trash">
                      </span>
                    </button>
                  </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="col-md-12" >
                <div class="row">
                  <div class="col-md-6 col-sm-10 pricing_field_list " >
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" ng-click="add_price_rule('last_min')">
                      <span class="fa fa-plus">
                      </span>
                      {{trans('messages.lys.add')}}
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="js-donation-tool-placeholder">
        </div>
        <div class="not-post-listed row row-space-top-6 progress-buttons">
          <div class="col-12">
            <div class="separator">
            </div>
          </div>
          <div class="col-2 row-space-top-1 next_step">
            @if($result->status == NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="back-section-button">{{ trans('messages.lys.back') }}
            </a>
            @endif
            @if($result->status != NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="back-section-button">{{ trans('messages.lys.back') }}
            </a>
            @endif
          </div>
          <div class="col-10 text-right next_step">
            @if($result->status == NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="btn btn-large btn-primary next-section-button">
              {{ trans('messages.lys.next') }}
            </a>
            @endif
            @if($result->status != NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/booking') }}" class="btn btn-large btn-primary next-section-button">
              {{ trans('messages.lys.next') }}
            </a>
            @endif
          </div>
        </div>
    
    </div>
    <div id="js-manage-listing-help" class="manage-listing-help col-lg-4 col-md-4 hide-sm   manage-listing-page">
      <div class="manage-listing-help-panel-wrapper">
        <div class="panel manage-listing-help-panel" >
          <div class="help-header-icon-container text-center va-container-h   header-icon-light">
            <img class="col-center" src="{{ url('images/lightbulb2x.png') }}">
          </div>
          <div class="panel-body">
            <h4 class="text-center">{{ trans('messages.lys.nightly_price') }}
            </h4>
            <p>{{ trans('messages.lys.nightly_price_desc') }}
            </p>
          </div>
        </div>
      </div>
    </div>
      </div>
  </div>
  <div class="manage-listing-content-background">
  </div>
</div>