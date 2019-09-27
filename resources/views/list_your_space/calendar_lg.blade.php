<div class="host-calendar-container">
    <div class="calendar-month col-lg-8 col-md-12">
        <div class="deselect-on-click1">
            <div class="common_calender_view">

            <a href="{{url('manage-listing/'.$room_id.'/calendar')}}" class="month-nav month-nav-previous panel text-center" data-year="{{$prev_year}}" data-month="{{$prev_month}}">
                <i class="icon icon-chevron-left h3">
                </i>
            </a>
            <a href="{{url('manage-listing/'.$room_id.'/calendar')}}" class="month-nav month-nav-next panel text-center" data-year="{{$next_year}}" data-month="{{$next_month}}">
                <i class="icon icon-chevron-right h3">
                </i>
            </a>
            <div class="current-month-selection">
                <h2>
                    <span class="full-month">
                        {{trans('messages.lys.'.date('F', $local_date))}}
                    </span>
                    <span>
                        {{date('Y', $local_date)}}
                    </span>
                    <span>
                        &nbsp;
                    </span>
                    <span class="current-month-arrow">
                        ▾
                    </span>
                </h2>
                {!!Form::select('year_month', $year_month, date('Y-m', $local_date), ['id' => 'calendar_dropdown', 'data-href' => url('manage-listing/'.$room_id.'/calendar')]) !!}
                <div class="spinner-next-to-month-nav">
                    Just a moment...
                </div>
            </div>
        </div>
            @if(request()->segment(1) != ADMIN_URL)
            <a class="text-normal link-icon" id="js-calendar-settings-btn" href="javascript:void(0)" data-prevent-default="true">
                <i class="icon icon-cog text-lead">
                </i>
                <span>
                    &nbsp;
                </span>
                <span class="link-icon__text">
                    {{trans('messages.header.settings')}}
                </span>
            </a>
            @endif
        </div>
        <div class="days-of-week deselect-on-click">
            <ul class="list-layout clearfix">
                <li>{{trans('messages.lys.Monday')}}</li>
                <li>{{trans('messages.lys.Tuesday')}}</li>
                <li>{{trans('messages.lys.Wednesday')}}</li>
                <li>{{trans('messages.lys.Thursday')}}</li>
                <li>{{trans('messages.lys.Friday')}}</li>
                <li>{{trans('messages.lys.Saturday')}}</li>
                <li>{{trans('messages.lys.Sunday')}}</li>
            </ul>
        </div>
        <div id="calendar_selection">
            <div class="days-container panel clearfix">
                <ul class="list-unstyled">
                    @foreach($calendar_data as $data)
                    <li class="tile {{@$data['class']}} no-tile-status both get_click" id="{{@$data['date']}}" data-day="{{@$data['day']}}" data-month="" data-year="">
                        <div class="date">
                            <span class="day-number">
                                <span>
                                    {{@$data['day']}}
                                </span>
                                @if($data['date'] == date('Y-m-d'))
                                <span class="today-label">
                                    {{trans('messages.lys.today')}}
                                </span>
                                @endif
                            </span>
                        </div>
                        <div class="price" {!!$data['price_display']!!}>
                            <span>
                                {{$rooms_price->currency->original_symbol}}
                            </span>
                            <span>
                                {{$rooms_price->price($data['date'])}}
                            </span>
                        </div>
                        @if($rooms_price->spots_left($data['date']) != '')
                        <div class="spots_left">
                            <span class="small h6">{{trans('messages.shared_rooms.spots_left')}} {{$rooms_price->spots_left($data['date'])}}
                            </span>
                        </div>
                        @endif
                        @if($rooms_price->notes($data['date']) != '')
                        <div class="tile-notes">
                            <div class="va-container va-container-v va-container-h">
                                <span class="va-middle tile-notes-text">{{$rooms_price->notes($data['date'])}}
                                </span>
                            </div>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="host-calendar-sidebar col-lg-4 col-md-12">
        <div class="calendar-edit-form panel host-calendar-sidebar-item hide">
            <form name="calendar-edit-form" class="ng-pristine ng-valid">
                <div class="panel-header text-center panel-header-small" ng-init="segment_status = &quot;available&quot;">
                    <div class="segmented-control">
                        <label class="segmented-control__option segmented-control__option--selected" id="avi" ng-class="(segment_status == &quot;available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot; ">
                            <span>
                                {{trans('messages.lys.Available')}}
                            </span>
                            <input type="radio" id="available_check" ng-checked="segment_status == &quot;available&quot;" name="radio" ng-model="segment_status" value="available" class="segmented-control__input ng-pristine ng-untouched ng-valid" checked="checked">
                        </label>
                        <label id="unavi" class="segmented-control__option" ng-class="(segment_status == &quot;not available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot;">
                            <span>
                                {{trans('messages.lys.Blocked')}}
                            </span>
                            <input type="radio" id="notavailable_check" ng-checked="segment_status == &quot;not available&quot;" name="radio" value="not available" ng-model="segment_status" class="segmented-control__input ng-pristine ng-untouched ng-valid">
                        </label>
                    </div>
                </div>
                <div class="panel-body" style="display: block;">
                    <div class="row text-muted text-center">
                        <div class="col-6">
                            <label>
                                {{trans('messages.lys.start_date')}}
                            </label>
                        </div>
                        <div class="col-6">
                            <label>
                                {{trans('messages.lys.end_date')}}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="ui-datepicker-target ng-pristine ng-valid ng-touched" id="calendar-edit-start" ng-model="calendar_edit_start_date">
                            <input type="hidden" id="calendar-start">
                        </div>
                        <div class="col-6">
                            <input type="text" class="ui-datepicker-target ng-pristine ng-valid ng-touched" id="calendar-edit-end" ng-model="calendar_edit_end_date">
                            <input type="hidden" id="calendar-end">
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="sidebar-price-container row-space-1">
                        <label>
                            {{trans('messages.lys.price_each_night')}}
                        </label>
                        <div class="embedded-currency">
                            <input type="number" limit-to="9" style="padding-left: 32px;" value="" class="input-giant sidebar-price embedded-currency__input get_price ng-pristine ng-untouched ng-valid" id="myInput" ng-model="calendar_edit_price">
                            <span style="left: 4px; font-size: 19px; top: 11px;" class="embedded-currency__currency embedded-currency__currency--in-input">
                                {{$rooms_price->currency->original_symbol}}
                            </span>
                            <div style="font-size: 25px; line-height: normal; font-weight: bold; padding: 8px 20px; border: 1px solid rgb(196, 196, 196); width: 160px;" class="input-giant sidebar-price embedded-currency__input embedded-currency__input--invisible">
                                <span class="embedded-currency__currency">
                                    $rooms_price->currency->original_symbol
                                </span>
                                <span class="clone-value">
                                </span>
                            </div>
                        </div>
                        <br>
                        <em class="text-danger price_error" style="font-size: 14px;" hidden="hidden">
                            {{trans('validation.min.numeric',['attribute' => trans('messages.inbox.price'), 'min' => $rooms_price->currency->original_symbol.$minimum_amount])}}
                        </em>
                    </div>
                    <div class="row-space-2 onboarding-dim text-center">
                        <div>
                            <a data-prevent-default="true" href="{{url('manage-listing/'.$room_id.'/calendar')}}" class="text-muted link-icon alg_1" onclick="return false;" ng-click="isAddNote = !isAddNote">
                                <span class="link-icon__text">
                                    {{trans('messages.lys.add_note')}}
                                </span>
                                <i class="icon icon-caret-down">
                                </i>
                            </a>
                            <textarea ng-model="notes" ng-show="isAddNote" class="ng-pristine ng-untouched ng-valid ng-hide">
                            </textarea>
                        </div>
                    </div>
                    <p class="get_n_day" hidden="hidden">
                        {{$rooms_price->price(date('Y-m-d', $local_date))}}
                    </p>
                </div>
                <div class="panel-footer panel-footer-flex onboarding-dim">
                    <a class="btn" data-prevent-default="true" href="{{url('manage-listing/'.$room_id.'/calendar')}}" id="calendar_edit_cancel">
                        {{trans('messages.your_reservations.cancel')}}
                    </a>
                    <button type="submit" class="btn btn-host-save" style="" id="s_chck">
                        {{trans('messages.wishlist.save_changes')}}
                    </button>
                    <button type="submit" id="s_chck1" class="btn btn-host-save sub_price btn_status_change" ng-click="calendar_edit_submit('{{url('manage-listing/'.$room_id.'/calendar')}}')">
                        {{trans('messages.wishlist.save_changes')}}
                    </button>
                </div>
            </form>
        </div>
        <div>
        </div>
    </div>
</div>
