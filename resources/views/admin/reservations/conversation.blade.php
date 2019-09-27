@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Conversation Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Conversation</a></li>
        <li class="active">Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info col-lg-12 col-sm-12 col-xs-12 col-md-12">
            <div class="box-header with-border">
              <h3 class="box-title">Conversation Details</h3>
                 
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if($reservation_info->list_type == 'Experiences')
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host Experience Id
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                   {!! $reservation_info->host_experiences->id !!}
                  </div>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host Experience Name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                   {!! $reservation_info->host_experiences->name !!}
                  </div>
                </div>
            </div>
            @else
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Room Id
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                   {!! $reservation_info->rooms->id !!}
                  </div>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Room Name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                   {!! $reservation_info->rooms->name !!}
                  </div>
                </div>
            </div>
            @endif
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                   Host Name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                   {!! $reservation_info->host_users->first_name !!}  {!! $reservation_info->host_users->last_name !!} 
                  </div>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                   Guest Name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                  {!! $reservation_info->users->first_name !!}  {!! $reservation_info->users->last_name !!}
                 
                  </div>
                </div>
            </div>

<div class="box-header with-border">
              <h3 class="box-title">Chat Details</h3>
            </div><br>
            
             @foreach($result as $row)
             @if($row->user_to==$reservation_info->host_id)
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:30px;padding:0px;">
             <div class="col-xs-4 col-md-2 col-sm-3 col-lg-2"  > 
             <center>
             <img src="{{ $reservation_info->users->profile_picture->src }}" class="avatar img-circle" alt="avatar" height="50px" /> <br>
              {!! $reservation_info->users->first_name !!}  {!! $reservation_info->users->last_name !!}</center>
           
             </div> 
            <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10" >
             @if($row->message_type=='12')
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.pre_accepted') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 9)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.contact_request_sent') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 2)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_confirmed') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 3)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_declined') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 4)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_expired') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 6)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $row->reservation->rooms->users->first_name }} {{ trans('messages.inbox.pre_approved_you') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 7)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $row->reservation->rooms->users->first_name }} {{ trans('messages.inbox.sent_special_offer') }} </span>
        <span>{{ $row->special_offer->currency->symbol.$row->special_offer->price }}/{{ ucfirst(trans_choice('messages.rooms.night',1)) }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 8)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.date_unavailable') }}</span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 11)
          <div class="inline-status text-branding space-6 text-center text-bold">
          <div class="horizontal-rule-text">
          <span class="horizontal-rule-wrapper">
          <span>
          <span>{{ trans('messages.inbox.reservation_declined') }} </span>
          <span>{{ $row->created_time }}</span>
          </span>
          </span>
          </div>
          </div>
        <br>
    @endif
    @if($row->message_type == 10)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_cancelled') }}</span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
             <div id="usermessage" > {{ $row->message }}</div>
             {{ $row->created_time }}
  <br>
  <div class="text-center text-bold">
  @if($row->message_type=='1')
  Reservation Request {!! $row->created_time !!}
  @endif
  </div>
  </div>
  
</div><br>
@endif
@if($row->user_to==$reservation_info->user_id)
<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12" style="margin-bottom:30px;padding:0px">
  <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10" style=" text-align:right;">
    @if($row->message_type=='12')
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.pre_accepted') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 9)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.contact_request_sent') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 2)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_confirmed') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 3)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_declined') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 4)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_expired') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 6)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $row->reservation->rooms->users->first_name }} {{ trans('messages.inbox.pre_approved_you') }} </span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 7)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $row->reservation->rooms->users->first_name }} {{ trans('messages.inbox.sent_special_offer') }} </span>
        <span>{{ $row->special_offer->currency->symbol.$row->special_offer->price }}/{{ ucfirst(trans_choice('messages.rooms.night',1)) }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 8)
        <div class="inline-status text-branding space-6 text-center text-bold">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.date_unavailable') }}</span>
        <span>{{ $row->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        <br>
        @endif
        @if($row->message_type == 11)
          <div class="inline-status text-branding space-6 text-center text-bold">
          <div class="horizontal-rule-text">
          <span class="horizontal-rule-wrapper">
          <span>
          <span>{{ trans('messages.inbox.reservation_declined') }} </span>
          <span>{{ $row->created_time }}</span>
          </span>
          </span>
          </div>
          </div>
        <br>
    @endif
  <div id="hostmessage" >{{ $row->message }}</div>
  {{ $row->created_time }}
  <br>
  <div class="text-center text-bold">
  @if($row->message_type=='1')
  Reservation Request {!! $row->created_time !!}
  @endif
  </div>
  </div>
  <div class="col-xs-4 col-md-2 col-sm-3 col-lg-2"  >
  <center>
  <img src="{{ $reservation_info->host_users->profile_picture->src }}" class="avatar img-circle" alt="avatar" height="50px"><br>
  {!! $reservation_info->host_users->first_name !!}  {!! $reservation_info->host_users->last_name !!} 
  </center>
   
  </div>
</div><br>
    @endif
    @endforeach
                
              </div> <!-- <showv>-->
              <!-- /.box-body -->
              <!-- /.box-footer -->
          </div>
          <!-- /.box -->
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
<style>
#usermessage{

  background-color:#afa0a0;
  border: none;
  border-radius: 10px 40px; 
  padding:2% 8% 2% 5%; 
  font-size:16px;
}
#hostmessage{
  background-color: #afa0a0;
  border: none;
  border-radius: 40px 10px; 
  padding:2% 8% 2% 5%; 
  font-size:16px;

   
}
@media (min-width: 300px) and (max-width: 1200px){
  #usermessage, #hostmessage{padding: 9% 11% 10% 12% !important;}
}
</style>
