@extends('admin.template')
@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dispute Details
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="../dashboard">
                    <i class="fa fa-dashboard">
                    </i> Home
                </a>
            </li>
            <li>
                <a href="../../disputes">Disputes
                </a>
            </li>
            <li class="active">Details
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-10 col-sm-offset-1">
                <!-- Horizontal Form -->
                <div class="box box-info" ng-controller="dispute_details">
                    <div class="box-header with-border">
                        <h3 class="box-title">Disputes Details
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open(['url' => ADMIN_URL.'/dispute/detail/'.$dispute->id, 'class' => 'form-horizontal', 'style' => 'word-wrap: break-word']) !!}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                @if($dispute->admin_status == 'Open' && $dispute->status == 'Closed')
                                <div class="row row-space-4">
                                    <div class="col-sm-12">
                                        <div class="box panel panel-success">
                                            <div class="panel-header">
                                                <div class="col-sm-12">
                                                    <h4>The final dispute amount the users concluded {{$dispute->currency->symbol}} {{$dispute->dispute_amount}} will be paid to {{$dispute->user->first_name}}</h4>
                                                </div>
                                            </div>
                                            <div class=" panel-body">
                                                <div class="row">
                                                    <div class="col-sm-6 text-right">
                                                        <a href="{{url(ADMIN_URL.'/dispute_confirm_amount/'.$dispute->id)}}" class="btn btn-primary" ng-click="confirm_dispute();">Confirm Dispute</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row row-space-4">
                                    <div class="col-sm-12">
                                        <div class="box panel panel-success">
                                            <div class="panel-header">
                                                <div class="col-sm-12">
                                                    <h4>Send message to users</h4>
                                                </div>
                                            </div>
                                            <div class=" panel-body">
                                                <input type="hidden" name="dispute_id" value="{{$dispute->id}}" id="dispute_id">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea name="message" class="form-control" id="input_message" ng-model="admin_message_data.message" style="min-width: 100%; max-width: 100%;"></textarea>
                                                        <p class="text-danger">@{{admin_message_form_errors.message[0]}}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <label class="col-sm-5 text-left" for="input_message_for">Message for:</label>
                                                            <div class="col-sm-7">
                                                                <select  name="message_for" id="input_message_for_host" class="form-control" ng-model="admin_message_data.message_for">
                                                                    <option value="">Select</option>
                                                                    <option value="Host" >{{$dispute->reservation->host_users->first_name}} (Host)</option>
                                                                    <option value="Guest" >{{$dispute->reservation->users->first_name}} (Guest)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <p class="text-danger">@{{admin_message_form_errors.message_for[0]}}</p>
                                                    </div>
                                                    <div class="col-sm-6 text-right">
                                                        <button type="button" class="btn btn-primary" ng-click="admin_message();">Send message</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="overlay hide">
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12" id="thread-list">
                                        @foreach($dispute->dispute_messages->sortByDesc('id') as $message)
                                        @include('admin/disputes/thread_list_item', ['message' => $message])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                @if($dispute->status == 'Open' || $dispute->status == 'Processing')
                                <div class="row row-space-2">
                                    <div class="col-sm-12">
                                        <a class="btn btn-primary" href="{{url(ADMIN_URL.'/dispute/close/'.$dispute->id)}}">Close</a>
                                    </div>
                                </div>
                                @endif
                                <div class="row row-space-2">
                                    <div class="col-sm-12">
                                        <h4>Dispute Reason</h4>
                                        <p>{{$dispute->subject}}</p>
                                    </div>
                                </div>
                                <div class="row row-space-2">
                                    <div class="col-sm-12">
                                        <h4>Reservation Information</h4>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Room name:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->rooms->name}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Host name:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->host_users->first_name}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Guest name:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->users->first_name}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Checkin:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->checkin_formatted}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Checkout:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->checkout_formatted}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Number of guests:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->number_of_guests}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Status:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->reservation->status}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Security deposit:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->currency->symbol.$dispute->reservation->security}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <p>Host payout:</p>
                                            </div>
                                            <div class="col-sm-7">
                                                <p>{{$dispute->currency->symbol.$dispute->reservation->currency_convert($dispute->reservation->currency_code, $dispute->currency_code, $dispute->reservation->admin_host_payout)}}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row row-space-2">
                                    <div class="col-sm-12">
                                        <h4>Documents</h4>
                                    </div>
                                    @foreach($dispute->dispute_documents as $document)
                                    <div class="col-sm-12">
                                        <a href="{{$document->file_url}}" target="_blank"> 
                                            <img class="img-thumbnail" src="{{$document->file_url}}">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    {!! Form::close() !!}
                    <div class="box-footer text-center">
                        <a class="btn btn-default" href="{{ url(ADMIN_URL.'/disputes') }}">Back
                        </a>
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
<style type="text/css">
.usermessage{
  background-color:#afa0a0;
  border: none;
  border-radius: 10px 40px; 
  padding:2% 8% 2% 5%; 
  font-size:16px;
}
.hostmessage{
  background-color: #afa0a0;
  border: none;
  border-radius: 40px 10px; 
  padding:2% 8% 2% 5%; 
  font-size:16px;   
}
@media (min-width: 300px) and (max-width: 1200px){
  .usermessage, .hostmessage{padding: 9% 11% 10% 12% !important;}
}
</style>
@push('scripts')
    <script src="{{ url('admin_assets/dist/js/disputes.js') }}"></script>
@endpush
@stop