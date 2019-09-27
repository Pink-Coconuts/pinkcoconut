<div class="manage-listing-content-container" id="js-manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div class="manage-listing-content" id="js-manage-listing-content" style="background-color: transparent !important;">
      <div class="new-calendar">
        <div id="calendar-container col-lg-7 col-md-12">
          <div class="calendar-prompt-container">
          </div>
          <div class="calendar-settings-btn-container pull-right post-listed">
            <span class="label-contrast label-new hide">{{ trans('messages.lys.new') }}
            </span>
            <a class="text-normal link-icon" id="js-calendar-settings-btn" href="{{ url('manage-listing/'.$room_id.'/calendar') }}">
              <i class="icon icon-cog text-lead">
              </i>
              <span class="link-icon__text">{{ trans('messages.header.settings') }}
              </span>
            </a>
          </div>
          <div id="calendar">
            {!! $calendar !!}
            <footer class="space-top-6 calendar-footer-buttoned col-lg-12">
              <li>
                <a href="" class="text-muted" id="import_button">{{ trans('messages.lys.import_calc') }}
                </a>
              </li>
              <li>
                <a class="js-calendar-sync text-muted" data-prevent-default="true" href="{{ url('calendar/sync/'.$result->id) }}">{{ trans('messages.lys.sync_other_calc') }}
                </a>
              </li>
              <li>
                <a href="" class="text-muted" id="export_button">{{ trans('messages.lys.export_calc') }}
                </a>
              </li>
              <li>
                <a href="" class="text-muted remove_sync_button" id="remove_sync_button">{{ trans('messages.lys.remove_calc') }}
                </a>
              </li>
            </footer>
          </div>
        </div>
        <div class="pricing-tips-sidebar-container">
        </div>
      </div>
    </div>
    <div id="calendar-rules" class="sidebar-overlay" ng-init="rs_errors = []">
      <div class="sidebar-overlay-inner js-section">
        <h3 class="pull-left row-space-4 sidebar-overlay-heading">
          {{ trans('messages.lys.reservation_settings') }}
        </h3>
        <a href="javascript:void(0)" id="js-close-calendar-settings-btn" class="modal-close" data-prevent-default="">
        </a>
        <div class="js-saving-progress reservation_settings-saving saving-progress" style="display: none;">
          <h5>{{ trans('messages.lys.saving') }}...
          </h5>
        </div>
        <div class="clearfix">
        </div>
        <div class="row-space-4 hide">
          <label for="select-min_days_notice" class="text-muted">
            {{ trans('messages.lys.sameday_requests') }}
          </label>
          <div id="min-days-select" class="calendar-select">
            <div class="select                          select-block select-chosen">
              <select name="min_days_notice" id="select-min_days_notice" style="display: none;">
                <option value="-1" selected="selected">{{ trans('messages.lys.are_okay') }}
                </option>
                <option value="0">{{ trans('messages.lys.donot_want_sameday_requests') }}
                </option>
                <option value="1">{{ trans('messages.lys.donot_sameday_nextday_requests') }}
                </option>
              </select>
              <div class="chosen-container chosen-container-single chosen-container-single-nosearch" style="width: 279px;" title="" id="select_min_days_notice_chosen">
                <a class="chosen-single" tabindex="-1">
                  <span>{{ trans('messages.lys.are_okay') }}
                  </span>
                  <div>
                    <b>
                    </b>
                  </div>
                </a>
                <div class="chosen-drop">
                  <div class="chosen-search">
                    <input type="text" autocomplete="off" readonly="">
                  </div>
                  <ul class="chosen-results">
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row-space-4 hide">
          <label for="select-turnover_days" class="text-muted">
            {{ trans('messages.lys.preparation_time') }}
          </label>
          <div id="turnover-days-select" class="calendar-select">
            <div class="select                          select-block select-chosen">
              <select name="turnover_days" id="select-turnover_days" style="display: none;">
                <option value="0" selected="selected">{{ trans('messages.account.none') }}
                </option>
                <option value="1">{{ trans('messages.lys.saving',['count'=>1]) }}
                </option>
                <option value="2">{{ trans('messages.lys.saving',['count'=>2]) }}
                </option>
              </select>
              <div class="chosen-container chosen-container-single chosen-container-single-nosearch" style="width: 279px;" title="" id="select_turnover_days_chosen">
                <a class="chosen-single" tabindex="-1">
                  <span>{{ trans('messages.account.none') }}
                  </span>
                  <div>
                    <b>
                    </b>
                  </div>
                </a>
                <div class="chosen-drop">
                  <div class="chosen-search">
                    <input type="text" autocomplete="off" readonly="">
                  </div>
                  <ul class="chosen-results">
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row-space-4 hide">
          <label for="select-max_days_notice" class="text-muted">
            {{ trans('messages.lys.distant_requests') }}
          </label>
          <div id="max-days-select" class="calendar-select">
            <div class="select select-block select-chosen">
              <select name="max_days_notice" id="select-max_days_notice" style="display: none;">
                <option value="-1" selected="selected">{{ trans('messages.lys.guests_arriving_anytime') }}
                </option>
                <option value="90">{{ trans('messages.lys.guests_arrive_month',['count'=>3]) }}
                </option>
                <option value="180">{{ trans('messages.lys.guests_arrive_month',['count'=>6]) }}
                </option>
                <option value="365">{{ trans('messages.lys.guests_arrive_year') }}
                </option>
              </select>
              <div class="chosen-container chosen-container-single chosen-container-single-nosearch" style="width: 279px;" title="" id="select_max_days_notice_chosen">
                <a class="chosen-single" tabindex="-1">
                  <span>{{ trans('messages.lys.guests_arriving_anytime') }}
                  </span>
                  <div>
                    <b>
                    </b>
                  </div>
                </a>
                <div class="chosen-drop">
                  <div class="chosen-search">
                    <input type="text" autocomplete="off" readonly="">
                  </div>
                  <ul class="chosen-results">
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div data-hook="min_max_nights" class="row row-space-2">
          <div class="col-6">
            <label class="label-large">{{ trans('messages.lys.minimum_stay') }}
            </label>
            <div class="input-addon">
              <input name="minimum_stay" id="min-nights" value="{{$result->rooms_price->minimum_stay}}" type="text" class="input-stem input-large reservation_settings_inputs">
              <span class="input-suffix">{{ trans('messages.lys.nights') }}
              </span>
            </div>
            <p class="ml-error">@{{rs_errors['minimum_stay'][0]}}</p>
          </div>
          <div class="col-6">
            <label class="label-large">{{ trans('messages.lys.maximum_stay') }}
            </label>
            <div class="input-addon">
              <input name="maximum_stay" id="max-nights" value="{{$result->rooms_price->maximum_stay}}" type="text" class="input-stem input-large reservation_settings_inputs">
              <span class="input-suffix">{{ trans('messages.lys.nights') }}
              </span>
            </div>
            <p class="ml-error">@{{rs_errors['maximum_stay'][0]}}</p>
          </div>
          <p id="min-max-error" class="ml-error" style="display:none;">
          </p>
        </div>
        <div data-hook="seasonal_min_max_overview" ng-init="availability_rules = {{json_encode($result->availability_rules->count() ? $result->availability_rules : [] )}};">
          <div class="row">
            <div class="col-12" id="availability_rules_wrapper">
              <div class="row space-2" ng-repeat="item in availability_rules" >
                <div class="col-md-9 col-sm-9 col-xs-8 small edit_arb">
                  <p class="space-0">
                    {{trans('messages.lys.during')}} @{{item.during}},
                  </p>
                  <p class="space-0" ng-if="item.minimum_stay">
                    {{trans('messages.lys.guest_stay_for_minimum')}} @{{item.minimum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                  <p class="space-0" ng-if="item.maximum_stay">
                    {{trans('messages.lys.guest_stay_for_maximum')}} @{{item.maximum_stay}} {{trans('messages.lys.nights')}}
                  </p>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-4  edit_arb1">
                  <a href="javascript:void(0)" class="text-normal link-icon" ng-click="remove_availability_rule($index)" style="margin:0 5px;">
                    <span class="fa fa-trash">
                    </span>
                  </a>
                  <a href="javascript:void(0)" class="text-normal link-icon" ng-click="edit_availability_rule($index)" style="margin:0 5px;">
                    <span class="fa fa-edit">
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <small>
                <a href="javascript:void(0)" id="js-add-availability-rule-link" class="text-muted link-underline" data-prevent-default="true">{{ trans('messages.lys.add_requirement_seasons') }}
                </a>
              </small>
            </div>
          </div>
        </div>
        <div class="js-calendar-sync-section sidebar-overlay-highlight-section hide">
          <div>
          </div>
          <h3 id="calendar_sync_heading" data-hook="calendar_sync_heading" class="row-space-4 sidebar-overlay-heading">
            {{ trans('messages.lys.sync_calc') }}
          </h3>
          <div data-hook="calendar_sync">
            <div class="space-2">
              <div class="row row-condensed">
                <div class="col-sm-12">
                  <ul class="list-unstyled" style="margin-bottom:0;">
                    <li class="space-1">
                      <a href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="true" class="text-muted link-icon">
                        <i name="download" class="icon icon-download">
                        </i>
                        <span>&nbsp;
                        </span>
                        <span>{{ trans('messages.lys.import_calc') }}
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="true" class="text-muted link-icon">
                        <i name="share" class="icon icon-share">
                        </i>
                        <span>&nbsp;
                        </span>
                        <span>{{ trans('messages.lys.export_calc') }}
                        </span>
                      </a>
                    </li>
                  </ul>
                  <p class="get_n_day" hidden="hidden">
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="calendar-rules-overlay hide" id="calendar-rules-custom" ng-init="availability_rule_item = {type: 'month'}; ar_errors=[];">
      <div class="panel text-center">
        <div class="panel-body">
          <div class="row row-condensed">
            <a class="modal-close" href="javascript:void(0)" id="js-close-availability-rule-btn">
            </a>
            <div class="col-11 ed_calbox"  id="availability_rule_item_wrapper">
              <p class="row-space-2">
                <strong class="-heading">
                </strong>
              </p>
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="control-label col-md-4">
                    {{trans('messages.lys.select_dates')}}
                  </label>
                  <div class="col-md-8 space-1" ng-init="availability_rule_item.type = availability_rule_item.id ? 'prev' : ''">
                    <div class="select select-block select-large">
                    <select name="availability_rule_item[type]" class="form-control" id="availability_rule_item_type" ng-model="availability_rule_item.type" ng-click="availability_rules_type_change();" >
                      <option value="" ng-disabled="availability_rule_item.type != ''" ng-if="!availability_rule_item.id">
                        {{trans('messages.lys.select_dates')}}
                      </option>
                      <option value="prev" data-start_date="@{{availability_rule_item.start_date_formatted}}" data-end_date="@{{availability_rule_item.end_date_formatted}}" ng-if="availability_rule_item.id">
                        @{{availability_rule_item.during}}
                      </option>
                      @foreach($availability_rules_months_options as $date => $option)
                      <option value="month" data-start_date="{{$option['start_date']}}" data-end_date="{{$option['end_date']}}">
                        {{$option['text']}}
                      </option>
                      @endforeach
                      <option value="custom">
                        {{trans('messages.lys.custom')}}
                      </option>
                    </select>
                    </div>
                    <p class="ml-error space-0 text-left h6">@{{ar_errors['type'][0]}}</p>
                  </div>
                </div>

                <div class="form-group col-sm-12" ng-show="availability_rule_item['type'] == 'custom'">
                  <label class="col-md-4 control-label">
                    {{trans('messages.lys.custom_dates')}}
                  </label>
                  <div class="col-md-4 col-sm-6 col-xs-12 intbut" ng-init="availability_rule_item.start_date = availability_rule_item.start_date_formatted">
                    <input type="text" readonly name="availability_rule_item[start_date]" class="form-control" id="availability_rules_start_date" placeholder="{{trans('messages.lys.start_date')}}" ng-model="availability_rule_item.start_date" >
                    <p class="ml-error space-0 text-left h6">@{{ar_errors['start_date'][0]}}</p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-12  space-1 intbut" ng-init="availability_rule_item.end_date = availability_rule_item.end_date_formatted">
                    <input type="text" readonly name="availability_rule_item[end_date]" class="form-control" id="availability_rules_end_date" placeholder="{{trans('messages.lys.end_date')}}" ng-model="availability_rule_item.end_date" >
                    <p class="ml-error space-0 text-left h6">@{{ar_errors['end_date'][0]}}</p>
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label col-md-4">
                    {{trans('messages.lys.minimum_stay')}}
                  </label>
                  <div class="col-md-8 space-1">
                    <div class="input-addon">
                      <input type="text" name="availability_rule_item[minimum_stay]" class="form-control availability_minimum_stay" id="availability_rules_minimum_stay" placeholder="{{trans('messages.lys.minimum_stay')}}" ng-model="availability_rule_item.minimum_stay" >
                      <span class="input-suffix">
                        {{trans('messages.lys.nights')}}
                      </span>
                    </div>
                    <p class="ml-error space-0 text-left h6">@{{ar_errors['minimum_stay'][0]}}</p>
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label col-md-4">
                    {{trans('messages.lys.maximum_stay')}}
                  </label>
                  <div class="col-md-8 space-1">
                    <div class="input-addon">
                      <input type="text" name="availability_rule_item[maximum_stay]" class="form-control availability_maximum_stay" id="availability_rules_maximum_stay" data-minimum_stay="#availability_rules_minimum_stay" placeholder="{{trans('messages.lys.maximum_stay')}}" ng-model="availability_rule_item.maximum_stay" >
                      <span class="input-suffix">
                        {{trans('messages.lys.nights')}}
                      </span>
                    </div>
                    <p class="ml-error space-0 text-left h6">@{{ar_errors['maximum_stay'][0]}}</p>
                  </div>
                </div>
              </div>
              <div class="-example-image-container row-space-top-4 hide">
              </div>
              <div class="-rule-caption">
              </div>
              <div class="-jump-to-month row-space-top-3 hide">
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer panel-footer-flex onboarding-dim ">
          <a class="btn" data-prevent-default="true" href="javascript:void(0)" id="js-cancel-availability-rule-btn" >{{trans('messages.your_reservations.cancel')}}</a>
          <button type="button" class="btn btn-host-save" id="js-save-calendar-rule-btn" ng-click="update_availability_rule()" style="" >{{trans('messages.wishlist.save_changes')}}</button>
        </div>
      </div>
    </div>
    <div class="manage-listing-help hide" id="js-manage-listing-help">
    </div>
  </div>
  <div class="manage-listing-content-background" style="background-color: transparent !important;">
  </div>
</div>
<style type="text/css">
  #js-manage-listing-content-container, #ajax_container, .manage-listing-content-wrapper {
    /*height: auto !important;*/
  }

  .manage-listing-content, #site-content #ajax_container .manage-listing-content-wrapper{overflow: auto !important;}
  @if($result->status != NULL)
  .btn.btn-host{
    padding: 5px 10px !important;
    border: 1px solid #ccc !important;
    border-radius: 0px !important;
    top: 0px !important;
  }
  @endif
  @media (min-width: 765px){
    html[lang="ar"] .calendar-month.col-lg-8.col-md-12{float: right;}
    html[lang="ar"] #ajax_container{
      /*left: 16.66667% !important;*/
    }
  }
  .subnav.ml-header-subnav{padding-right: 0px !important}
  html[lang="ar"] .manage-listing-content {
    float: right !important;
    width: 100% !important;
  }
  html[lang="ar"] .calendar-month .tile,html[lang="ar"] .calendar-month .days-of-week li{float: right;}
  html[lang="ar"] .calendar-month .month-nav-next{margin-left:3px;margin-right:0px;margin-top: 8px;float: right;}
  html[lang="ar"] .calendar-month .month-nav-prev{margin-left: 10px;}
  html[lang="ar"] .has-collapsed-nav .manage-listing-nav{left: auto;right: 0px;width: 269px !important;}
  html[lang="ar"] .has-collapsed-nav .manage-listing-nav.collapsed{left: auto;right: -270px;}
  .btn_status_change{
    display: none;
  }
</style>
@if(@Request::segment(1) == 'ajax-manage-listing')
<script type="text/javascript">
  $(document).ready(function() {
    // $('#js-manage-listing-nav').addClass('manage-listing-nav');
    // $('#js-manage-listing-nav').removeClass('pos-abs');
    // $('#js-manage-listing-nav').addClass('collapsed');
    // $('#ajax_container').removeClass('mar-left-cont');
    var input = document.getElementById("myInput");
    input.onkeypress = function(e) {    e = e || window.event;
    var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
    // Allow non-printable keys
    if (!charCode || charCode == 8 /* Backspace */ ) {
      return;
    }
    var typedChar = String.fromCharCode(charCode);
    // Allow numeric characters
    if (/\d/.test(typedChar)) {
      return;
    }
    // Allow the minus sign (-) if the user enters it first
    if (typedChar == "-" && this.value == "") {
      return;
    }
    // In all other cases, suppress the event
    return false;
    };
    $('.getprice').keyup(function(event){
      if (event.shiftKey == true) {
        event.preventDefault();
      }
      if($(this).val().indexOf('') !== -1 && event.keyCode == 190)
        event.preventDefault();
    });
    $('#s_chck1').addClass('btn_status_change');
    $('.segmented-control__input').change(function(){ 
      var options= $(this).val();
    // alert(options);
    if(options != "available")
    {
      $('#s_chck1').removeClass('btn_status_change');
      $('#s_chck').addClass('btn_status_change'); 
    }
    else
    {
    // alert(options);
    $('#s_chck').removeClass('btn_status_change');
    $('#s_chck1').addClass('btn_status_change');
    // $('.get_price').text(' ');
    }
    });
    $('#s_chck').click(function(){
      var new_price=$('.get_price').val();
      var ii_id= '{{ $room_id }}';
      $.ajax({
        type: "post",
        url: '{{ url("/") }}/manage-listing/'+ii_id+'/currency_check',
        data:{'n_price': new_price },
        success:function(data){
          if(data =='success')
          {
            setTimeout( function(){  }  , 5000 );
            $('#s_chck1').click();
            $(".price_error").hide();
          }
          else
          {
            $(".price_error").show();
            return false;
          }
        },
      });
    });
  });
</script>
@endif
@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    // $('#js-manage-listing-nav').addClass('manage-listing-nav');
    // $('#js-manage-listing-nav').removeClass('pos-abs');
    // $('#js-manage-listing-nav').addClass('collapsed');
    // $('#ajax_container').removeClass('mar-left-cont');
    var input = document.getElementById("myInput");
    input.onkeypress = function(e) {    e = e || window.event;
    var charCode = (typeof e.which == "number") ? e.which : e.keyCode;
    // Allow non-printable keys
    if (!charCode || charCode == 8 /* Backspace */ ) {
      return;
    }
    var typedChar = String.fromCharCode(charCode);
    // Allow numeric characters
    if (/\d/.test(typedChar)) {
      return;
    }
    // Allow the minus sign (-) if the user enters it first
    if (typedChar == "-" && this.value == "") {
      return;
    }
    // In all other cases, suppress the event
    return false;
    };
    $('.getprice').keyup(function(event){
      if (event.shiftKey == true) {
        event.preventDefault();
      }
      if($(this).val().indexOf('') !== -1 && event.keyCode == 190)
        event.preventDefault();
    });
    $('#s_chck1').addClass('btn_status_change');
    $('.segmented-control__input').change(function(){ 
      var options= $(this).val();
    // alert(options);
    if(options != "available")
    {
      $('#s_chck1').removeClass('btn_status_change');
      $('#s_chck').addClass('btn_status_change'); 
    }
    else
    {
    // alert(options);
    $('#s_chck').removeClass('btn_status_change');
    $('#s_chck1').addClass('btn_status_change');
    // $('.get_price').text(' ');
    }
    });
    $('#s_chck').click(function(){
      var new_price=$('.get_price').val();
      var ii_id= '{{ $room_id }}';
      $.ajax({
        type: "post",
        url: '{{ url("/") }}/manage-listing/'+ii_id+'/currency_check',
        data:{'n_price': new_price },
        success:function(data){
          if(data =='success')
          {
            setTimeout( function(){  }  , 5000 );
            $('#s_chck1').click();
            $(".price_error").hide();
          }
          else
          {
            $(".price_error").show();
            return false;
          }
        },
      });
    });
  });
</script>
@endpush