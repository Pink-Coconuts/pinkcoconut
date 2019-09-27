@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Slider
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../slider">Sliders</a></li>
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
              <h3 class="box-title">Edit Slider Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/edit_slider/'.$result->id, 'class' => 'form-horizontal', 'files' => true]) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Image</label>
                  <div class="col-sm-6">
                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
                    <p class="note_text">(Note: Upload size minimum 1360px * 600px)</p>
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                    

                    <img src="{{ $result->image_url }}" height="100" width="200">
                  </div>
                </div>
              </div>

                <div class="form-group">
                  <label for="input_position" class="col-sm-3 control-label">Order<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('order', $result->order, ['class' => 'form-control', 'id' => 'input_position']) !!}
                    <span class="text-danger">{{ $errors->first('order') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], $result->status, ['class' => 'form-control', 'id' => 'input_status']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_front_end" class="col-sm-3 control-label">Slider For<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('front_end', ['Homepage' => 'Homepage', 'Adminpage' => 'Adminpage'], $result->front_end, ['class' => 'form-control', 'id' => 'input_front_end']) !!}
                    <span class="text-danger">{{ $errors->first('front_end') }}</span>
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