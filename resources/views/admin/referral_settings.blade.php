@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Referral Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="referral_settings">Referral Settings</a></li>
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
              <h3 class="box-title">Referral Settings Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/referral_settings', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_per_user_limit" class="col-sm-3 control-label">Per User Credit Limit<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('per_user_limit', $result[0]->value, ['class' => 'form-control', 'id' => 'input_per_user_limit', 'placeholder' => 'Per User Credit Limit']) !!}
                    <span class="text-danger">{{ $errors->first('per_user_limit') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_if_friend_guest_credit" class="col-sm-3 control-label">When Friend Guest<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('if_friend_guest_credit', $result[1]->value, ['class' => 'form-control', 'id' => 'input_if_friend_guest_credit', 'placeholder' => 'When Friend Guest']) !!}
                    <span class="text-danger">{{ $errors->first('if_friend_guest_credit') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_if_friend_host_credit" class="col-sm-3 control-label">When Friend Host<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('if_friend_host_credit', $result[2]->value, ['class' => 'form-control', 'id' => 'input_if_friend_host_credit', 'placeholder' => 'When Friend Host']) !!}
                    <span class="text-danger">{{ $errors->first('if_friend_host_credit') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_new_referral_user_credit" class="col-sm-3 control-label">New User Travel Credit<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('new_referral_user_credit', $result[3]->value, ['class' => 'form-control', 'id' => 'input_new_referral_user_credit', 'placeholder' => 'New User Travel Credit']) !!}
                    <span class="text-danger">{{ $errors->first('new_referral_user_credit') }}</span>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="input_currency_code" class="col-sm-3 control-label">Currency<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('currency_code',$currency, $result[4]->value, ['class' => 'form-control', 'id' => 'input_currency_code']) !!}
                    <span class="text-danger">{{ $errors->first('currency_code') }}</span>
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