@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reservation Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../../reservations">Reservations</a></li>
        <li class="active">Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Reservation Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/reservation/detail/'.$result->id, 'class' => 'form-horizontal', 'style' => 'word-wrap: break-word']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Room name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->rooms->name }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->rooms->users->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Guest name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->users->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Checkin
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ date($php_format_date,strtotime($result->checkin)) }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Checkout
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ date($php_format_date,strtotime($result->checkout)) }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Number of guests
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->number_of_guests }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancellation Policy
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->cancellation }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Total nights
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->nights }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    {{ $result->currency->symbol }}{{$result->base_per_night}} x {{$result->nights}}
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->base_per_night * $result->nights }}
                   </div>
                </div>
                @foreach($result->discounts_list as $list)
                @if($list['price'])
                  <div class="form-group">
                    <label class="col-sm-3 control-label">
                      {{$list['text']}}
                    </label>
                    <div class="col-sm-6 col-sm-offset-1 form-control-static">
                      -{{ $result->currency->symbol }}{{ $list['price'] }}
                     </div>
                  </div>
                @endif
                @endforeach
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cleaning fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->cleaning }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Additional guest fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->additional_guest }}
                   </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Subtotal amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->subtotal }}
                   </div>
                </div>
                
                @if($result->security > 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Security fee <i id="service-fee-tooltip" rel="tooltip" class="glyphicon  glyphicon-info-sign" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i>
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->security }}
                   </div>
                </div>
                @endif
                @if($result->airportfee > 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Airport fee 
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->airportfee }}
                   </div>
                </div>
                @endif

                @if($result->ngo_amount > 0)
                  @foreach(json_decode($result->ngo_list) as $ngolist)
                  <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Ngo -  {{ $ngolist->name }}
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $ngolist->amount }}
                   </div>
                   </div>
                  @endforeach
                @endif
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Service fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->service }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->host_fee }}
                   </div>
                </div>
                @if($result->coupon_amount)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    @if($result->coupon_code == 'Travel_Credit')
                    Travel Credit
                    @else
                    Coupon Amount
                    @endif
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    - {{ $result->currency->symbol }}{{ $result->coupon_amount }}
                   </div>
                </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Total amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->total }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Currency
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency_code }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    @if(@$result->host_payout_preferences->payout_method == 'Stripe')
                    Host Payout Stripe Account
                    @else
                    Host payout email id
                    @endif
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->host_payout_email_id }}
                   </div>
                </div> 
                 <div class="form-group">
                  <label class="col-sm-3 control-label">
                    @if(@$result->guest_payout_preferences->payout_method == 'Stripe')
                    Guest Payout Stripe Account
                    @else
                    Guest payout email id
                    @endif
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->guest_payout_email_id }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Paymode
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->formatted_paymode }}
                   </div>
                </div>
                <!-- <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Status
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->status }}
                   </div>
                </div> -->
                @if($result->status == 'Pre-Accepted' || $result->status == 'Inquiry')  
                  @if($result->checkin < date("Y-m-d"))              
                  <div class="form-group">
                    <label class="col-sm-3 control-label">
                      Status
                    </label>
                    <div class="col-sm-6 col-sm-offset-1">
                      {{trans('messages.dashboard.Expired')}}
                     </div>
                  </div>
                  @else
                  <div class="form-group">
                    <label class="col-sm-3 control-label">
                      Status
                    </label>
                    <div class="col-sm-6 col-sm-offset-1">
                      {{ $result->status }}
                      
                     </div>
                  </div>
                  @endif
                @else
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                      Status
                    </label>
                    <div class="col-sm-6 col-sm-offset-1">
                      {{ $result->status }}
                      
                     </div>
                  </div>
                @endif 
                @if($result->status == "Declined")
                  <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Declined Reason
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->decline_reason }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Declined Message
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $decline_message }}
                   </div>
                </div>
                @endif
                @if($result->status == "Cancelled")
                  <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled Reason
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->cancelled_reason }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled Message
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $cancel_message }}
                   </div>
                </div>

                 <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled By
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->cancelled_by }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled Date
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->cancelled_at }}
                   </div>
                </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Transaction ID
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->transaction_id }}
                   </div>
                </div>
                @if($result->paymode == 'Credit Card')
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    First name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Last name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->last_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Postal code
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->postal_code }}
                   </div>
                </div>
                @endif
                @if($result->country != '')
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Country
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->country }}
                   </div>
                </div>
                @endif

                @if($result->pphone_number != '')
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Guest contact number
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->pphone_number }}
                   </div>
                </div>
                @endif


                @if(@$result->host_penalty->amount != 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Applied Penalty Amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->host_penalty->converted_amount }}
                   </div>
                </div>
                @endif
                @if(@$result->hostPayouts->total_penalty_amount != 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Subtracted Penalty Amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $result->currency->symbol }}{{ $result->hostPayouts->total_penalty_amount }}
                   </div>
                </div>
                @endif

               <!--  @if(@$payouts->penalty_id != 0)
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                     Amount subracted (From Host Penalty id's)
                  </label>
                  <div class="col-sm-6 col-sm-offset-1 form-control-static">
                    {{ $payouts->penalty_id }}
                   </div>
                </div>
                @endif -->
              </div>
              <!-- /.box-body -->
            </form> 
              @if($result->can_payout_button_show())
              @if((($result->status == 'Accepted' &&  $result->checkin_cross == 0 ) || $result->status == 'Cancelled') && $result->check_host_payout != 'yes' && $result->admin_host_payout != 0)
                @if($result->host_payout_email_id)
                   @if(@$result->status != 'Expired')
                  <form action="{{ url(ADMIN_URL.'/reservation/payout') }}" method="post">
                    {!! Form::token() !!}
                    <input type="hidden" name="reservation_id" value="{{ $result->id }}">
                    <input type="hidden" name="host_payout_id" value="{{ $result->host_payout_id }}">
                    <input type="hidden" name="user_type" value="host">
                    <div class="text-center"> 
                      <button type="submit" class="btn btn-primary">Payout to Host({{ $result->currency->symbol }}{{ $result->admin_host_payout }})</button>
                    </div>
                  </form>
                  @endif
                @else
                  <div class="text-bold text-danger text-center">Yet, host doesn't enter his/her Payout preferences. <a href="{{ url(ADMIN_URL.'/reservation/need_payout_info/'.$result->id.'/host/'.$result->list_type) }}">Send Email to Host</a></div>
                @endif
              @endif

              @if(($result->status == 'Declined' || $result->status == 'Cancelled' || ($result->status == 'Accepted' &&  $result->checkin_cross == 0 )) && $result->check_guest_payout != 'yes' && $result->paymode != '' && $result->admin_guest_payout != 0)
                
                  <br>
                  <form action="{{ url(ADMIN_URL.'/reservation/payout') }}" method="post">
                    {!! Form::token() !!}
                    <input type="hidden" name="reservation_id" value="{{ $result->id }}">
                    <input type="hidden" name="guest_payout_id" value="{{ $result->guest_payout_id }}">
                    <input type="hidden" name="user_type" value="guest">
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Refund to Guest({{ $result->currency->symbol }}{{ $result->admin_guest_payout }})</button>
                    </div>
                  </form>
                {{--
                @if($result->guest_payout_email_id)
                @else
                  <div class="text-bold text-danger text-center">Yet, guest doesn't enter his/her Payout preferences. <a href="{{ url(ADMIN_URL.'/reservation/need_payout_info/'.$result->id.'/guest/'.$result->list_type) }}">Send Email to Guest</a></div>
                @endif
                --}}
              @endif
              @endif
              
              @if($result->check_host_payout == 'yes')
                <div class="text-bold text-success text-center">Host Payout amount ({{ $result->currency->symbol.$result->admin_host_payout }}) transferred.</div>
              @endif
              @if($result->check_guest_payout == 'yes')
                <div class="text-bold text-success text-center">Guest refund amount ({{ $result->currency->symbol.$result->admin_guest_payout }}) transferred.</div>
              @endif
              <div class="box-footer text-center">
                <a class="btn btn-default" href="{{ url(ADMIN_URL.'/reservations') }}">Back</a>
              </div>
              <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @push('scripts')
<script>
  $('#input_dob').datepicker({ 'format': 'dd-mm-yyyy'});
</script>
@endpush
@stop