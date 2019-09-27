@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Referral Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="referrals">Referrals</a></li>
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
              <h3 class="box-title">Referral Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/referral_details/'.$result[0]->user_id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <div class="col-sm-12">
                <div class="col-sm-7">
                  <label class="col-sm-5 control-label">
                    Referrer name
                  </label>
                  <div class="col-sm-7 form-control-static">
                    {{ $result[0]->users->full_name }}
                  </div>
                </div>
                </div>
                @php $i = 1; @endphp
                @foreach($result as $row)
                <div class="col-sm-12">
                <div class="col-sm-6">
                  <label class="col-sm-6 control-label">
                    Referee name {{ $i }}
                  </label>
                  <div class="col-sm-6 form-control-static">
                    {{ $row->friend_users->full_name }}
                  </div>
                  </div>
                  <div class="col-sm-3">
                   <label class="control-label col-sm-7 p-l-r-0">Booking:</label> <span class="form-control-static col-sm-1">{{ $row->booking_status($row->friend_id) }}</span>
                  </div>
                  <div class="col-sm-2">
                   <label class="control-label col-sm-7 p-l-r-0">Listing:</label><span class="form-control-static col-sm-1"> {{ $row->listing_status($row->friend_id) }}</span>
                  </div>
                </div>
                @php $i++ @endphp
                @endforeach
              </div>
            {!! Form::close() !!}
              <!-- /.box-body -->
              <div class="box-footer text-center">
                <a class="btn btn-default" href="{{ url(ADMIN_URL.'/referrals') }}">Back</a>
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
<style type="text/css">
  .p-l-r-0{
    padding-left: 0 !important;
    padding-right: 0 !important;
  }
</style>