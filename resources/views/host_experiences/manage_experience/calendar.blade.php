<div class="host-calendar-container">
  <div class="calendar-month col-lg-12 col-md-12">
    <div class="row-space-2 deselect-on-click"> 
      <a href="javascript:void(0)" class="month-nav month-nav-previous panel text-center refresh_calendar" data-year="{{$prev_year}}" data-month="{{$prev_month}}"> 
        <i class="icon icon-chevron-left h3">
        </i> 
      </a> 
      <a href="javascript:void(0)" class="month-nav month-nav-next panel text-center refresh_calendar" data-year="{{$next_year}}" data-month="{{$next_month}}"> 
        <i class="icon icon-chevron-right h3">
        </i> 
      </a> 
      <div class="current-month-selection" data-month="{{date('m', $current_time)}}" data-year="{{date('Y', $current_time)}}"> 
        <h2> 
          <span class="full-month">{{trans('messages.lys.'.date('F', $current_time))}}
          </span>  
          <span>{{date('Y', $current_time)}}
          </span> 
          <span> &nbsp;
          </span> 
          <span class="current-month-arrow">▾
          </span> 
        </h2>
        {!! Form::select('year_month', $year_months, date('Y-m', $current_time), ['id' => 'calendar_months_dropdown', 'data-href' =>'']) !!}
        <div class="spinner-next-to-month-nav">Just a moment...
        </div>
      </div> 
    </div>
    <div class="days-of-week deselect-on-click"> 
      <ul class="list-layout clearfix"> 
        <li>{{trans('messages.lys.Monday')}}
        </li>  
        <li>{{trans('messages.lys.Tuesday')}}
        </li>  
        <li>{{trans('messages.lys.Wednesday')}}
        </li>  
        <li>{{trans('messages.lys.Thursday')}}
        </li>  
        <li>{{trans('messages.lys.Friday')}}
        </li>  
        <li>{{trans('messages.lys.Saturday')}}
        </li>  
        <li>{{trans('messages.lys.Sunday')}}
        </li> 
      </ul> 
    </div>
    <div id="calendar_selection" ng-init="calendar_data = {{json_encode($calendar_data)}}">
      <div class="days-container panel clearfix"> 
        <ul class="list-unstyled">
          <li ng-repeat="data in calendar_data" class="tile no-tile-status both get_click @{{data.status}}" id="@{{data.date}}" data-day="@{{$index}}"> 
            <div class="date">
              <span class="day-number"> 
                <span>@{{data.date_d}}</span>
              </span> 
            </div>
            <div class="spots-left" ng-if="data.is_reserved">
              <span class="">(@{{data.spots_left}} {{trans('experiences.details.spots_left')}})</span>
            </div>
            <div class="price" style="display: inline-flex;"> 
              <span>{!! $host_experience->currency->original_symbol !!}</span> 
              <span>@{{data.price}}</span> 
            </div>
          </li>
        </ul> 
      </div>               
    </div>
  </div>
  <div class=" col-lg-4 col-md-12"></div>
</div>
<style type="text/css">
 .spots-left {
    position: absolute;
    top: 30%;
    left: 0px;
    white-space: nowrap;
    overflow: hidden;
     /*text-overflow: ellipsis; */
    width: 85%;
}
</style>
