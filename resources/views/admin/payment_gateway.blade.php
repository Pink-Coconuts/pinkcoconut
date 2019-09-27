@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payment Gateway
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="payment_gateway">Payment Gateway</a></li>
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
              <h3 class="box-title">Payment Gateway Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/payment_gateway', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_paypal_username" class="col-sm-3 control-label">PayPal Username<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('paypal_username', $result[0]->value, ['class' => 'form-control', 'id' => 'input_paypal_username', 'placeholder' => 'PayPal Username']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_username') }}</span>
                  </div>
                </div>
              
                <div class="form-group">
                  <label for="input_paypal_password" class="col-sm-3 control-label">PayPal Password<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    <!-- {!! Form::text('paypal_password', $result[1]->value, ['class' => 'form-control', 'id' => 'input_paypal_password', 'placeholder' => 'PayPal Password']) !!} -->
                    <input type="password" value="{{$result[1]->value}}" name="paypal_password" placeholder="PayPal Password" id="input_paypal_password" class="form-control">
                    <span class="text-danger">{{ $errors->first('paypal_password') }}</span>
                  </div>
                </div>
              
              
                <div class="form-group">
                  <label for="input_paypal_signature" class="col-sm-3 control-label">PayPal Signature<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('paypal_signature', $result[2]->value, ['class' => 'form-control', 'id' => 'input_paypal_signature', 'placeholder' => 'PayPal Signature']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_signature') }}</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="input_paypal_mode" class="col-sm-3 control-label">PayPal Mode</label>

                  <div class="col-sm-6">
                    {!! Form::select('paypal_mode', array('sandbox' => 'Sandbox', 'live' => 'Live'), $result[3]->value, ['class' => 'form-control', 'id' => 'input_paypal_mode']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_mode') }}</span>
                  </div>
                </div>
              

              <div class="form-group">
                  <label for="input_paypal_mode" class="col-sm-3 control-label">PayPal Client ID</label>

                  <div class="col-sm-6">
                    {!! Form::text('paypal_client', $result[4]->value, ['class' => 'form-control', 'id' => 'input_paypal_client']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_client') }}</span>
                  </div>
                </div>
             
              <div class="form-group">
                  <label for="input_paypal_mode" class="col-sm-3 control-label">PayPal Secret</label>

                  <div class="col-sm-6">
                    {!! Form::text('paypal_secret', $result[5]->value, ['class' => 'form-control', 'id' => 'input_paypal_secret']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_secret') }}</span>
                  </div>
                </div>
                <legend></legend>
              <div class="form-group">
                  <label for="input_stripe_publish" class="col-sm-3 control-label">Stripe Publishable Key</label>

                  <div class="col-sm-6">
                    {!! Form::text('stripe_publish', $result[6]->value, ['class' => 'form-control', 'id' => 'input_stripe_publish']) !!}
                    <span class="text-danger">{{ $errors->first('stripe_publish') }}</span>
                  </div>
                </div>
              
              <div class="form-group">
                  <label for="input_stripe_secret" class="col-sm-3 control-label">Stripe Secret Key</label>

                  <div class="col-sm-6">
                    {!! Form::text('stripe_secret', $result[7]->value, ['class' => 'form-control', 'id' => 'input_stripe_secret']) !!}
                    <span class="text-danger">{{ $errors->first('stripe_secret') }}</span>
                  </div>
                </div>

              <div class="form-group">
                  <label for="input_stripe_client_id" class="col-sm-3 control-label">Stripe Client Id</label>

                  <div class="col-sm-6">
                    {!! Form::text('stripe_client_id', $result[8]->value, ['class' => 'form-control', 'id' => 'input_stripe_client_id']) !!}
                    <span class="text-danger">{{ $errors->first('stripe_client_id') }}</span>
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