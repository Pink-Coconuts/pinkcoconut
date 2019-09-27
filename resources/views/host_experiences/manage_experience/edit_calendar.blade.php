<div class="main_bar pull-left padd5 text-black bg-white pos_rel">
  <div class="pos_fix2 bg-white">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="row">    
    <div class="col-md-8 ed_cle">
      <div id="calendar" class="col-md-12 ">
        @include('host_experiences.manage_experience.calendar')
      </div>
      <div class="check_detail1-1 col-md-12">
        <button class="btn-primary2 space-top-8 space-8 next_step" data-step-num="{{$step_num +1}}" type="button" > 
          <a href="javascript:void(0)" class="rm_link"> {{trans('experiences.manage.next')}}
          </a>
        </button>
      </div>
    </div>
    <div class="host-calendar-sidebar col-md-4 ed_avai">
      <div class="calendar-edit-form panel host-calendar-sidebar-item hide">
        <form name="calendar-edit-form" class="ng-pristine ng-valid">
          <div class="panel-header text-center panel-header-small" ng-init="segment_status = 'available'">
            <div class="segmented-control">
              <label class="segmented-control__option segmented-control__option--selected" id="avi" ng-class="(segment_status == 'available') ? 'segmented-control__option--selected' : '' ">
                <span>{{trans('messages.lys.Available')}}
                </span>
                <input type="radio" id="available_check" ng-checked="segment_status == 'available'" name="radio" ng-model="segment_status" value="available" class="segmented-control__input ng-pristine ng-untouched ng-valid" checked="checked">
              </label>
              <label id="unavi" class="segmented-control__option" ng-class="(segment_status == 'not available') ? 'segmented-control__option--selected' : ''">
                <span>{{trans('messages.lys.Blocked')}}
                </span>
                <input type="radio" id="notavailable_check" ng-checked="segment_status == 'not available'" name="radio" value="not available" ng-model="segment_status" class="segmented-control__input ng-pristine ng-untouched ng-valid">
              </label>
            </div>
          </div>
          <div class="panel-body date_fields" style="display: none;">
            <div class="row text-muted text-center">
              <div class="col-6">
                <label>{{trans('experiences.manage.start_date')}}
                </label>
              </div>
              <div class="col-6">
                <label>{{trans('experiences.manage.end_date')}}
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <input type="text" class="ui-datepicker-target ng-pristine ng-untouched ng-valid" readonly id="calendar-edit-start" ng-model="calendar_edit_start_date">
                <input type="hidden" id="calendar-start">
              </div>
              <div class="col-6">
                <input type="text" class="ui-datepicker-target ng-pristine ng-untouched ng-valid" readonly id="calendar-edit-end" ng-model="calendar_edit_end_date">
                <input type="hidden" id="calendar-end">
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="row space-top-6">
              <div class="col-sm-8 ed_cal">
                <div class="pos_rel input_new2" ng-init="calendar_edit_price = '';">
                  <span class="h3">{{$host_experience->currency_code}}
                  </span>
                  <input type="text" class="input_new1 numeric-values" value="" name="calendar_price_per_guest" id="host_experience_calendar_price_per_guest" ng-model="calendar_edit_price" placeholder="{!! $host_experience->currency->original_symbol !!}">
                </div>
              </div>
            </div>
          </div>
          <div class="panel-footer panel-footer-flex onboarding-dim calendar_ctrl_btn_area ">
            <a class="btn" data-prevent-default="true" id="calendar_edit_cancel">{{trans('experiences.manage.cancel')}}
            </a>
            <button type="button" class="btn btn-host-save" id="calendar_edit_form_save" ng-disabled="!calendar_edit_price || calendar_edit_price <= 0">{{trans('experiences.manage.save')}}
            </button>
          </div>
        </form>
      </div>
      <div> 
      </div> 
    </div>
  </div>
</div>
<!--  main_bar end -->