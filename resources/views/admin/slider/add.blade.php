@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Slider
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="slider">Sliders</a></li>
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
              <h3 class="box-title">Add Slider Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/add_slider', 'class' => 'form-horizontal', 'files' => true]) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
                    <p class="note_text">(Note: Upload size minimum 1360px * 600px)</p>
                    <span class="text-danger">{{ $errors->first('image') }}</span>

                  </div>
                </div>

                <div class="form-group">
                  <label for="input_position" class="col-sm-3 control-label">Order<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('order', 0, ['class' => 'form-control', 'id' => 'input_position']) !!}
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], '', ['class' => 'form-control', 'id' => 'input_status']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_front_end" class="col-sm-3 control-label">Slider For<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('front_end', ['Homepage' => 'Homepage', 'Adminpage' => 'Adminpage'], '', ['class' => 'form-control', 'id' => 'input_front_end']) !!}
                    <span class="text-danger">{{ $errors->first('front_end') }}</span>
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