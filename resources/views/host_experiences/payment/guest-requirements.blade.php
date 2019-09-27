<div class="reguest">
	<h1> {{trans("experiences.payment.review_guest_requirement")}}
	</h1>
	@if($host_experience->guest_requirements->includes_alcohol == 'Yes')
    <h4>{{trans('experiences.manage.alcohol')}}
    </h4>
    <p>{{trans('experiences.details.this_alcohol_includes_only_for_legal_age')}}
    </p>
    @endif
	<h4> {{trans('experiences.details.from_the_host')}}
	</h4>
	<p>{{$host_experience->guest_requirements->special_certifications}}
	</p>
</div>
<div class="whocan reguest">
	<h4> {{trans('experiences.manage.who_can_come')}}
	</h4>
	<p> {{trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $host_experience->guest_requirements->minimum_age])}}
	</p>
	@if($host_experience->guest_requirements->minimum_age < 18)
	<p>{{trans('experiences.details.bring_guest_under_18_your_responsibility')}}
	</p>
	@endif
	<div class="butget">
		<button type="button" ng-click="next_step()">{{trans('experiences.manage.next')}}
		</button>
	</div>
</div>
