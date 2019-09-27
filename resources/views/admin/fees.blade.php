@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Fees
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="fees">Fees</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content fee-content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-md-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Fees Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/fees', 'class' => 'form-horizontal']) !!}
              <div class="box-body">

                <div class="form-group">
                  <label for="input_service_fee" class="col-sm-3 control-label">Service Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group input-addon"> 
                    {!! Form::text('service_fee', $result[0]->value, ['class' => 'form-control', 'id' => 'input_service_fee', 'placeholder' => 'Service Fee']) !!}
                    <div class="input-group-addon" style="background-color:#eee;">%</div>
                    <span class="text-danger">{{ $errors->first('service_fee') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_host_fee" class="col-sm-3 control-label">Host Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group input-addon"> 
                    {!! Form::text('host_fee', $result[1]->value, ['class' => 'form-control', 'id' => 'input_host_fee', 'placeholder' => 'Host Fee']) !!}
                    <div class="input-group-addon" style="background-color:#eee;">%</div>
                    <span class="text-danger">{{ $errors->first('host_fee') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_min_service_fee" class="col-sm-3 control-label">Minimum Service Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::number('min_service_fee', $result[7]->value, ['class' => 'form-control', 'id' => 'input_min_service_fee', 'placeholder' => 'Minimum Service Fee']) !!}
                    <span class="text-danger">{{ $errors->first('min_service_fee') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_currency_fee" class="col-sm-3 control-label">Currency</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::select('currency_fee',$currency,$currency_fee, ['class' => 'form-control', 'id' => 'input_currency_fee']) !!}
                    <span class="text-danger">{{ $errors->first('currency_fee') }}</span>
                  </div>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default" name="cancel" value="cancel">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
          </div>
          {{--HostExperienceBladeCommentStart--}}
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Host Experience Service Fee</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/host_service_fees', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_service_fee" class="col-sm-3 control-label">Service Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group input-addon">      
                    {!! Form::text('host_service_fees', $result[9]->value, ['class' => 'form-control', 'id' => 'host_service_fees', 'placeholder' => 'Host experience Service Fee']) !!}
                    <div class="input-group-addon" style="background-color:#eee;">%</div>
                    <span class="text-danger">{{ $errors->first('host_service_fees') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_expr_min_service_fee" class="col-sm-3 control-label">Minimum Service Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::number('expr_min_service_fee', $result[10]->value, ['class' => 'form-control', 'id' => 'input_expr_min_service_fee', 'placeholder' => 'Minimum Service Fee']) !!}
                    <span class="text-danger">{{ $errors->first('expr_min_service_fee') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_expr_currency_fee" class="col-sm-3 control-label">Currency</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::select('expr_currency_fee',$currency,$expr_currency_fee, ['class' => 'form-control', 'id' => 'input_expr_currency_fee']) !!}
                    <span class="text-danger">{{ $errors->first('expr_currency_fee') }}</span>
                  </div>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default" name="cancel" value="cancel">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
          </div>
          {{--HostExperienceBladeCommentEnd--}}
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Host Penalty Fees Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/fees/host_penalty_fees', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <em>Note: Host penalty will be applied for Expired reservations also.</em>
              <br> <br>
                 <div class="form-group">
                  <label for="input_penalty_mode" class="col-sm-3 control-label">Host Penalty</label>
                  <div class="col-sm-7">
                    {!! Form::select('penalty_mode', array('1' => 'Yes', '0' => 'No'), $result[2]->value, ['class' => 'form-control', 'id' => 'input_penalty_mode']) !!}
                    <span class="text-danger">{{ $errors->first('penalty_mode') }}</span>
                  </div>
                </div>
                <div id="hide_penalty">
                 <div class="form-group">
                  <label for="input_penalty_currency" class="col-sm-3 control-label"> Currency</label>
                  <div class="col-sm-7">
                    {!! Form::select('penalty_currency',$currency,$penalty_currency, ['class' => 'form-control', 'id' => 'input_penalty_currency']) !!}
                    <span class="text-danger">{{ $errors->first('penalty_currency') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_before_seven_days" class="col-sm-3 control-label">Cancel Before Seven Days Checkin</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::text('before_seven_days', $result[4]->value, ['class' => 'form-control', 'id' => 'input_before_seven_days', 'placeholder' => 'Before Seven Days']) !!}
                    <span class="text-danger">{{ $errors->first('before_seven_days') }}</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_after_seven_days" class="col-sm-3 control-label">Cancel Less than Seven Days Checkin</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::text('after_seven_days', $result[5]->value, ['class' => 'form-control', 'id' => 'input_after_seven_days', 'placeholder' => 'Less Than Seven Days']) !!}
                    <span class="text-danger">{{ $errors->first('after_seven_days') }}</span>
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_cancel_limit" class="col-sm-3 control-label">Free Cancelation Limit</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::text('cancel_limit', $result[6]->value, ['class' => 'form-control', 'id' => 'input_cancel_limit', 'placeholder' => 'Free Cancellation Limit']) !!}
                    <span class="text-danger">{{ $errors->first('cancel_limit') }}</span>
                  </div>
                  </div>
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



          {{--AirportFee--}}
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Airport Fee Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/fees/airport_fee', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_before_seven_days" class="col-sm-3 control-label">Airport Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::number('airport_fee', $result[12]->value, ['class' => 'form-control', 'id' => 'airport_fee', 'placeholder' => 'Airport Fee','min'=>1]) !!}
                    <span class="text-danger">{{ $errors->first('airport_fee') }}</span>
                  </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="airportfee_currency" class="col-sm-3 control-label"> Currency</label>
                  <div class="col-sm-7">
                    {!! Form::select('airportfee_currency',$currency,$airportfee_currency, ['class' => 'form-control', 'id' => 'airportfee_currency']) !!}
                    <span class="text-danger">{{ $errors->first('airportfee_currency') }}</span>
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
@stop

@push('scripts')
<script type="text/javascript">
$('#input_penalty_mode').change(function()
{
  if($(this).val() == 0)
    $('#hide_penalty').hide();
  else
    $('#hide_penalty').show();
});

if($('#input_penalty_mode').val() == 0)
  $('#hide_penalty').hide();
else
  $('#hide_penalty').show();
</script>
@endpush