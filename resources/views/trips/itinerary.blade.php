@extends('template')
{!! Html::style('css/shared_itinerary.css') !!}
{!! Html::style('css/shared_itinerary_print.css',array('media' => 'print')) !!}
@section('main')
<main role="main" id="site-content">
	<div class="shared-itinerary-container">
		<div class="page-container-responsive">
			<div class="itinerary-card_page">
		<!-- 	<div class="show-sm space-4 listing-photo-main-sm">
				<div class="hide-print">
					<img src="{{$reservation_details->rooms->photo_name}}" class="media-photo media-photo-block img-responsive listing-large-photo">
				</div>
			</div> -->
			<section class="space-top-8 space-top-sm-4 space-4 seaction_view">
				
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h2 class="text-center-on-sm">{{ trans('messages.your_trips.you_are_gonna') }}
							@if($reservation_details->rooms->rooms_address->city != '')
							{{ $reservation_details->rooms->rooms_address->city }}!
							@else
							{{ $reservation_details->rooms->rooms_address->state }}!
							@endif
						</h2>
						<div class="text-center-on-sm">
							<span>{{ trans('messages.your_trips.reservation_code') }}:
							</span>
							<span> 
							</span>
							<span>{{ $reservation_details->code }}
							</span>
							<span>. 
							</span>
							<br class="show-sm" />
							<span class="hide-print">
								<span>
									<span> 
										<a href="{{ url('/') }}/reservation/receipt?code={{ $reservation_details->code }}">
											<span>{{ trans('messages.your_trips.view_receipt') }}
											</span>
										</a>
									</span>
									<span>.
									</span>
								</span>
							</span>
						</div>
					</div>
				
			</section>
			<div class="hide-md show-sm">
				<div class="hide-print">
					<img src="{{ $reservation_details->rooms->photo_name }}" class="media-photo media-photo-block img-responsive listing-large-photo">
				</div>
			</div>
			<div class="space-8">

				<div class="col-md-6 nt_arr1 itinerary_page">
					<div class="show-md">
						<div class="hide-print">
							<img src="{{ $reservation_details->rooms->photo_name }}" class="media-photo media-photo-block img-responsive listing-large-photo">
						</div>
					</div>
					<section class="space-6">
						<div class="listing-info-text">
						</div>
					</section>
				</div>


				<div class="col-md-6 nt_arr itinerary_page1">
					<div class="itinerary-card">
						<div class="panel-white panel">
							<div class="panel-body text-center-on-sm">
								<div class="row row-table checkin-checkout">
									<!-- <div class="show-sm">
										<div class="col-md-12">
											<strong>{{ trans('messages.home.checkin') }}
											</strong>
											<br>
											<span>{{ $reservation_details->checkin_dmd }}
											</span>
											<br>
											@if($reservation_details->list_type == 'Rooms')
											<span>{{ trans('messages.your_reservations.flexible_checkin_time') }}
											</span>
											@endif
										</div>
										<div class="col-sm-6">
											<strong>{{ trans('messages.home.checkout') }}
											</strong>
											<br>
											<span>{{ $reservation_details->checkout_dmd }}
											</span>
											<br>
											@if($reservation_details->list_type == 'Rooms')
											<span>{{ trans('messages.your_reservations.flexible_checkout_time') }}
											</span>
											@endif
										</div>
									</div> -->
									<div class="itinerary_checkin">
										<div class="col-md-3">
											<strong class="billing_head">{{ trans('messages.home.checkin') }}
											</strong>
										</div>
										<div class="col-md-9">
											<span>{{ $reservation_details->checkin_dmd }}
											</span>
											<br>
											@if($reservation_details->list_type == 'Rooms')
											<span>{{ trans('messages.your_reservations.flexible_checkin_time') }}
											</span>
											@endif
										</div>

									</div>
								</div>
							</div>
							<div class="panel-body text-center-on-sm">
								<div class="row row-table">	
									<div class="itinerary_checkout">
										<div class="col-md-3">
											<strong class="billing_head">{{ trans('messages.home.checkout') }}
											</strong>
										</div>
										<div class="col-md-9">
											<span>{{ $reservation_details->checkout_dmd }}
											</span>
											<br>
											@if($reservation_details->list_type == 'Rooms')
											<span>{{ trans('messages.your_reservations.flexible_checkout_time') }}
											</span>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body text-center-on-sm">
								<div class="row row-table">	
									<div class="itinerary_checkout">
										<div class="col-md-3">
											<strong class="billing_head">{{ trans('messages.your_trips.guest_count') }}
											</strong>
										</div>
										<div class="col-md-9">
											<span>{{ $reservation_details->number_of_guests }}
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body text-center-on-sm">
								<div class="row row-table">
									<div class="col-md-3">
										<strong class="billing_head">{{ trans('messages.account.address') }}
										</strong>
									</div>
									<div class="col-md-9">
										<div>
											@if($reservation_details->rooms->rooms_address->address_line_1 != '')
											{{ $reservation_details->rooms->rooms_address->address_line_1 }}<br>
											@endif
											@if($reservation_details->rooms->rooms_address->city != '')
											{{ $reservation_details->rooms->rooms_address->city }},
											@endif
											@if($reservation_details->rooms->rooms_address->state != '')
											{{ $reservation_details->rooms->rooms_address->state }} 
											@endif
											@if($reservation_details->rooms->rooms_address->postal_code != '')
											{{ $reservation_details->rooms->rooms_address->postal_code }}<br>
											@endif
											@if($reservation_details->rooms->rooms_address->country_name != '')
											{{ $reservation_details->rooms->rooms_address->country_name }}<br>
											@endif
										</div>
										<a class="hide-print" target="_blank" href="http://google.com/maps/place/{{ str_replace(' ','+',$reservation_details->rooms->rooms_address->address_line_1.' '.$reservation_details->rooms->rooms_address->city.', '.$reservation_details->rooms->rooms_address->state.' '.$reservation_details->rooms->rooms_address->postal_code.' '.$reservation_details->rooms->rooms_address->country_name) }}">{{ trans('messages.your_trips.get_directions') }}
										</a>
										<span class="mini-divider">
										</span>
										<a href="{{ $reservation_details->rooms->link }}">
											@if($reservation_details->list_type == 'Experiences')
											{{ trans('experiences.details.view_experience') }}
											@else
											{{ trans('messages.your_trips.view_listing') }}
											@endif
										</a>
									</div>
								</div>
							</div>
							<div class="panel-body text-center-on-sm">
								<div class="row row-table">
									<div class="col-md-3">
										<strong class="billing_head">{{ trans('messages.your_trips.host') }}
										</strong>
									</div>
									<!-- <div class="col-md-9 show-sm">
										<a class="show-sm" href="{{ url('/') }}/users/show/{{ $reservation_details->host_id }}">
											<img src="{{ $reservation_details->rooms->users->profile_picture->header_src }}" alt="{{ $reservation_details->rooms->users->full_name }}" width="50" height="50" class="media-photo media-round host-photo">
										</a>
									</div> -->
									<div class="col-md-3">
										<a href="{{ url('/') }}/users/show/{{ $reservation_details->host_id }}">
											<img src="{{ $reservation_details->rooms->users->profile_picture->header_src }}" alt="{{ $reservation_details->rooms->users->full_name }}" width="50" height="50" class="media-photo media-round ">
										</a>
									</div>
									<div class="col-md-6">
										<span>{{ $reservation_details->rooms->users->full_name }}
										</span>
										<br>

										<div class="hide-print">
											<a href="{{ url('/') }}/z/q/{{ $reservation_details->id }}">{{ trans('messages.your_trips.msg_host') }}
											</a>
											@If($reservation_details->status == 'Accepted')
											<br><a href="mailto:{{$reservation_details->host_users->email}}">{{ trans('messages.your_reservations.contact_by_email') }}
											</a>
											<br>{{$reservation_details->host_users->primary_phone_number}}
											@endif
										</div>
										<div class="show-print">{{ $reservation_details->rooms->users->email }}
										</div>
									</div>
									
								</div>
							</div>
							@if($reservation_details->ngo_amount > 0)
								@if(count(json_decode($reservation_details->ngo_list)) > 0)
							<div class="panel-body text-center-on-sm">
								<div class="row row-table">	
									<div class="itinerary_checkout">
										<div class="col-md-3">
											<strong class="billing_head">{{ trans('messages.rooms.ngo_fee') }}
											</strong>
										</div>
										<div class="col-md-9">
						                  @foreach(json_decode($reservation_details->ngo_list) as $ngolist)
						                      <p>{{ $ngolist->name }} - {{ $reservation_details->currency->symbol.$ngolist->amount }}</p>
						                  @endforeach
										</div>
									</div>
								</div>
							</div>
								@endif
						    @endif


							<div class="panel-body text-center-on-sm billing_table">
								<div class="row row-table">
									<div class="col-md-3">
										<strong class="billing_head">{{ trans('messages.your_trips.billing') }}
										</strong>
									</div>
									<div class="col-md-9">
										<table>
											<tbody>
												<tr>
													<td class="billing-table-cell">{{ $reservation_details->duration_text }} 
													</td>
													<td>{{ $reservation_details->currency->symbol.$reservation_details->total }}
													</td>
												</tr>
											</tbody>
										</table>
										<div class="hide-print">
											<a href="{{ url('/') }}/reservation/receipt?code={{ $reservation_details->code }}">{{ trans('messages.your_trips.detailed_receipt') }}
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</div>
		</div>
	</div>
</main>
<script>
	function print_itinerary()
	{
		window.print();
	}
</script>
@stop