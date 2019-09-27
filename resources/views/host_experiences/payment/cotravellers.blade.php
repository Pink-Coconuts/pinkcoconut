<div class="reguest reguest1">
	<h1> {{trans('experiences.payment.who_is_coming?')}}
	</h1>
	<div class="username">
		<div class="usename">{{$user->first_name}}
		</div>
		<div class="useimg">
			<div class="forleft">
				<img src="{{$user->profile_picture->header_src510}}">
			</div>
		</div>
	</div>
	<div class="foradd height-limited">
		<div ng-repeat="guest_data in payment_data.guest_details">
			<div class="guest">
				<span class="guestadd" style="text-transform: capitalize;">{{trans_choice('experiences.payment.guest_s', 1)}}
					<span>@{{$index+2}}
					</span>
				</span>
				<span class="remover">
					<a href="javascript:void(0);" ng-click="remove_guest($index)">{{trans('experiences.manage.remove')}}
					</a>
				</span>
			</div>
			<div class="formfill">
				<p class="hide">{{trans('experiences.payment.keep_your_guests_in_the_loop_add_email_to_send_itinerary')}}
				</p>
				<div class="formlist">
					<div class="listfor">
						<label>{{trans('messages.login.first_name')}}
						</label>
						<input type="text" name="guest_details[@{{$index}}][first_name]" ng-model="payment_data.guest_details[$index].first_name" class="required">
					</div>
					<div class="listfor1">
						<label>{{trans('messages.login.last_name')}}
						</label>
						<input type="text" name="guest_details[@{{$index}}][last_name]" ng-model="payment_data.guest_details[$index].last_name" class="required">
					</div>
					<div class="listfor2">
						<label>{{trans('messages.login.email')}} ({{trans('experiences.payment.optional')}})
						</label>
						<input type="text" name="guest_details[@{{$index}}][email]" ng-model="payment_data.guest_details[$index].email" class="email">
					</div>
				</div>
			</div>
		</div>
		<a href="javascript:void(0)" class="formore" ng-click="add_guest();" ng-show="(payment_data.guest_details.length+1) < payment_data.spots_left">
			<span class="adduse">{{trans('experiences.payment.add_another_guest')}}
			</span>
			<span class="plus">
				<span>+
				</span>
			</span>
		</a>
		<p class="space-top-3" ng-hide="(payment_data.guest_details.length+1) < payment_data.spots_left">{{trans('experiences.payment.there_are_only_spots_left_book_soon', ['count' => $payment_data['spots_left']])}}
		</p>
	</div>
</div>
<div class="whocan reguest">
	<div class="butget">
		<button type="button" ng-click="next_step()">{{trans('experiences.manage.next')}}
		</button>
	</div>
</div>
