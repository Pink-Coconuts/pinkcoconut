@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Ngo fee
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="ngofees">Ngo fee</a></li>
        <li class="active">Add</li>
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
              <h3 class="box-title">Add Ngo fee Form </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/insert_ngofee', 'class' => 'form-horizontal']) !!}

            <div id='ngofee'>
              <div class="box-body" id='form_1'>
                <!-- <span class="text-danger">(*)Fields are Mandatory</span> -->
                <div class="form-group">
                  <label for="input_ngo_name" class="col-sm-3 control-label">Ngo Name</label>
                  <div class="col-sm-6">
                    {!! Form::text('name', '', ['class' => 'form-control','placeholder' => 'Ngo Name']) !!}
                     <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                <label for="input_long_name" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    {!! Form::text('description', '', ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_iso3" class="col-sm-3 control-label">Donation Amount</label>
                  <div class="col-sm-6">
                    {!! Form::number('amount', '', ['class' => 'form-control', 'placeholder' => 'Amount','min'=>1]) !!}   
                    <span class="text-danger">{{ $errors->first('amount') }}</span>                 
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_num_code" class="col-sm-3 control-label">Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('currency',$currency,'', ['class' => 'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_phone_code" class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    {!! Form::select('status',['Active','Inactive'],'', ['class' => 'form-control']) !!}                    
                  </div>
                </div>
              </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit" onclick="return validate();">Submit</button>
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
