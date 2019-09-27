<div class="main_bar pull-left padd5">
	<div class="pos_fix2" id="manage_experience_header">
		@include('host_experiences.manage_experience.header')  
	</div>
	<div class="clearfix" ng-cloak>
		<div class="col-md-7 bg_chng">
			<p class="h1 bold space-top-8">{{trans('experiences.manage.hi_name', ['name' => @Auth::user()->first_name])}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.this_is_where_you_create_experience_4_steps')}}{{trans('experiences.manage.we_offer_tips_examples_along_way')}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.hosting_experienceis_new_growing_community', ['site_name' => SITE_NAME])}} {{trans('experiences.manage.we_cant_wait_to_see')}}
			</p>
			<a class="btn-primary3 space-top-8 space-8 next_step" data-step-num="{{$step_num +1}}" href="javascript:void(0);">{{trans('experiences.manage.next')}}
			</a>
		</div>
		<div class="pos_fix1">
			<img src="{{url('images/host_experiences/host_exper.jpg')}}" class="img-responsive img_style1">
		</div>
	</div>
</div>
