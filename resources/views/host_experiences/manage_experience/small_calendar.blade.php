<div id="calendar-container" class="small-calendar row-space-2">
	<div class="responsive-calendar small">
		<input type="hidden" value="{{date('Y', $current_time)}}-{{date('m', $current_time)}}" id="month-dropdown_value">
		<div class="calendarMonthHeader"> 
			<a class="previousMonth pull-left panel text-center " data-year="{{$prev_year}}" data-month="{{$prev_month}}" href="javascript:void(0);"> 
				<i class="icon icon-chevron-left h3">
				</i> 
			</a> 
			<a class="nextMonth pull-left panel text-center " data-year="{{$next_year}}" data-month="{{$next_month}}" href="javascript:void(0);"> 
				<i class="icon icon-chevron-right h3">
				</i> 
			</a> 
			<div class="select "> 
				<div class="loading-wrapper">
					{!! Form::select('year_month', $year_months, date('Y-m', $current_time), ['id' => 'month-dropdown', 'data-href' =>'']) !!}
				</div> 
			</div> 
		</div>
		<div class="calendarDaysHeader text-right"> 
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
		<div class="panel clearfix" > 
			<ul class="list-unstyled calendarDates">
				@foreach($calendar_data as $k => $data)
				<li class="tile  both {{$data['status']}}" id="{{$data['date']}}" data-day="{{$k}}"> 
					<div class="date">
						<span class="day-number"> 
							<span>{{$data['date_d']}}
							</span>  
						</span> 
					</div>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>