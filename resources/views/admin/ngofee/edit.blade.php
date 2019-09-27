@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Ngo Fee
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../ngofees">Ngo fees</a></li>
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
              <h3 class="box-title">Edit Ngo Fee Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/update_ngo', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_short_name" class="col-sm-3 control-label">Ngo Name</label>
                  <div class="col-sm-6">
                    {!! Form::text('name', $result->name, ['class' => 'form-control', 'placeholder' => 'Ngo Name']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>
                </div>
                <input type="hidden" name="id" value="{{$result->id}}">
                <div class="form-group">
                  <label for="input_long_name" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    {!! Form::text('description', $result->description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_iso3" class="col-sm-3 control-label">Amount</label>
                  <div class="col-sm-6">
                    {!! Form::number('amount', $result->amount, ['class' => 'form-control', 'placeholder' => 'Amount','min'=>1]) !!}
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_num_code" class="col-sm-3 control-label">Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('currency',$currency,$result->currency, ['class' => 'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_phone_code" class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    {!! Form::select('status',['active','inactive'],$status, ['class' => 'form-control']) !!}                    
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
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