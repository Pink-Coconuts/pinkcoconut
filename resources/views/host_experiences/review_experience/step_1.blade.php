<div class="main_bar pull-left padd5">
	<div class="pos_fix2" id="manage_experience_header">
		@include('host_experiences.manage_experience.header')  
	</div>
	<div class="clearfix" ng-cloak>
		<div class="col-md-7 bg_chng">
			<p class="h1 bold space-top-8">{{trans('experiences.manage.review_hosting_standards')}}
			</p>
			<p class="h4 line1 space-2">{{trans('experiences.manage.its_important_to_think_yourself_as_guide')}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.make_sure_you_meet_this_qualities')}}
			</p>
			<p class="h4 bold space-top-4 space-1"> 1. {{trans('experiences.manage.credible')}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.credible_desc')}}
			</p>
			<p class="h4 bold space-top-4 space-1"> 2. {{trans('experiences.manage.genuine')}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.genuine_desc')}}
			</p>
			<p class="h4 bold space-top-4 space-1"> 3. {{trans('experiences.manage.empathetic')}}
			</p>
			<p class="h4 line1">{{trans('experiences.manage.empathetic_desc')}}
			</p>
			<label class="space-top-4">
				<input type="checkbox" name="hosting_standards_reviewed" ng-model="host_experience.hosting_standards_reviewed" ng-true-value="'Yes'" ng-false-value="false" ng-checked="host_experience.hosting_standards_reviewed == 'Yes'"> 
				<span class="h4"> {{trans('experiences.manage.thats_me')}}
				</span>
				<p class="text-danger" ng-show="form_errors.hosting_standards_reviewed.length">@{{form_errors.hosting_standards_reviewed[0]}}
				</p>
			</label>
			<a class="btn-primary3 space-top-8 space-8 " data-step-num="{{$step_num +1}}" href="javascript:void(0);" ng-class="host_experience.hosting_standards_reviewed == 'Yes' ? 'save_next_step' : ''" ng-disabled="host_experience.hosting_standards_reviewed != 'Yes' ">{{trans('experiences.manage.next')}}
			</a>
		</div>
		<div class="pos_fix1">
			<img src="{{url('images/host_experiences/review2.jpg')}}" class="img-responsive img_style1">
		</div>
	</div>
</div>
