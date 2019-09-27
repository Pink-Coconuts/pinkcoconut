@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Features
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../features">Features</a></li>
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
              <h3 class="box-title">Edit Features Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/edit_features/'.$result->id, 'class' => 'form-horizontal', 'files' => true]) !!}

              <div class="box-body">

               <div class="form-group">
                  <label for="input_title" class="col-sm-3 control-label">Title<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('name', $result->name, ['class' => 'form-control', 'placeholder' => 'name']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Description<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('description',$result->description, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                  </div>
                </div>


                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                    <img src="{{ $result->image_url }}" height="100" width="200">
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_link_title" class="col-sm-3 control-label">URL for Redirect<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('link', $result->link, ['class' => 'form-control', 'placeholder' => 'URL for Redirect']) !!}
                    <span class="text-danger">{{ $errors->first('link') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_link" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status',['Active','Inactive'],$status, ['class' => 'form-control']) !!}                    
                    <span class="text-danger">{{ $errors->first('status') }}</span>
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