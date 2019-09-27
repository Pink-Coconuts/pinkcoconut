@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Coupon Code
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../coupon_code">Coupon Code</a></li>
        <li class="active">Edit</li>
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
              <h3 class="box-title">Edit Coupon Code Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/edit_coupon_code/'.$result->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
               <div class="form-group">
                  <label for="input_name" class="col-sm-3 control-label">Coupon Code<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('coupon_code',$result->coupon_code, ['class' => 'form-control', 'id' => 'input_coupon_code', 'placeholder' => 'Coupon Code']) !!}
                    <span class="text-danger">{{ $errors->first('coupon_code') }}</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="input_symbol" class="col-sm-3 control-label">Amount<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('amount',$result->amount, ['class' => 'form-control', 'id' => 'input_amount', 'placeholder' => 'Amount']) !!}
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="input_coupon_currency" class="col-sm-3 control-label">Coupon Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('coupon_currency',$currency,$coupon_currency, ['class' => 'form-control', 'id' => 'input_coupon_currency']) !!}
                    <span class="text-danger">{{ $errors->first('coupon_currency') }}</span>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="input_expired_at" class="col-sm-3 control-label">Expire Date<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('expired_at',$result->expired_at_dmy, ['class' => 'form-control', 'id' => 'input_expired_at', 'placeholder' => 'Expire Date', 'autocomplete' => 'off']) !!}
                    <span class="text-danger">{{ $errors->first('expired_at') }}</span>
                  </div>
                </div>
               <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'),$result->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                 <button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
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
var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');
$('#input_expired_at').datepicker({ 'dateFormat': datepicker_format, minDate: new Date()});
</script>
@endpush
@stop