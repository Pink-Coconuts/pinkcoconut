@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Join Us
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="join_us">Join Us</a></li>
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
              <h3 class="box-title">Join Us Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/join_us', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_facebook" class="col-sm-3 control-label">Facebook</label>
                  <div class="col-sm-6">
                    {!! Form::text('facebook', $result[0]->value, ['class' => 'form-control', 'id' => 'input_facebook', 'placeholder' => 'Facebook']) !!}
                    <span class="text-danger">{{ $errors->first('facebook') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_google_plus" class="col-sm-3 control-label">Google Plus</label>
                  <div class="col-sm-6">
                    {!! Form::text('google_plus', $result[1]->value, ['class' => 'form-control', 'id' => 'input_google_plus', 'placeholder' => 'Google Plus']) !!}
                    <span class="text-danger">{{ $errors->first('google_plus') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_twitter" class="col-sm-3 control-label">Twitter</label>
                  <div class="col-sm-6">
                    {!! Form::text('twitter', $result[2]->value, ['class' => 'form-control', 'id' => 'input_twitter', 'placeholder' => 'Twitter']) !!}
                    <span class="text-danger">{{ $errors->first('twitter') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_linkedin" class="col-sm-3 control-label">Linkedin</label>
                  <div class="col-sm-6">
                    {!! Form::text('linkedin', $result[3]->value, ['class' => 'form-control', 'id' => 'input_linkedin', 'placeholder' => 'Linkedin']) !!}
                    <span class="text-danger">{{ $errors->first('linkedin') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_pinterest" class="col-sm-3 control-label">Pinterest</label>
                  <div class="col-sm-6">
                    {!! Form::text('pinterest', $result[4]->value, ['class' => 'form-control', 'id' => 'input_pinterest', 'placeholder' => 'Pinterest']) !!}
                    <span class="text-danger">{{ $errors->first('pinterest') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_youtube" class="col-sm-3 control-label">Youtube</label>
                  <div class="col-sm-6">
                    {!! Form::text('youtube', $result[5]->value, ['class' => 'form-control', 'id' => 'input_youtube', 'placeholder' => 'Youtube']) !!}
                    <span class="text-danger">{{ $errors->first('youtube') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_instagram" class="col-sm-3 control-label">Instagram</label>
                  <div class="col-sm-6">
                    {!! Form::text('instagram', $result[6]->value, ['class' => 'form-control', 'id' => 'input_instagram', 'placeholder' => 'Instagram']) !!}
                    <span class="text-danger">{{ $errors->first('instagram') }}</span>
                  </div>
                </div>
                <!-- Start App Link -->
                <div class="box-body">
                  <div class="form-group">
                    <label for="input_cloud_secret" class="col-sm-3 control-label">Play Store</label>
                    <div class="col-sm-6">
                      {!! Form::text('play_store', $result[7]->value, ['class' => 'form-control', 'id' => 'input_play_store_link', 'placeholder' => 'Google Play Store Link']) !!}
                      <span class="text-danger">{{ $errors->first('play_store') }}</span>
                    </div>
                  </div>
                </div>

                <div class="box-body">
                  <div class="form-group">
                    <label for="input_cloud_secret" class="col-sm-3 control-label">App Store </label>
                    <div class="col-sm-6">
                      {!! Form::text('app_store', $result[8]->value, ['class' => 'form-control', 'id' => 'input_app_store_link', 'placeholder' => 'App Store Link']) !!}
                      <span class="text-danger">{{ $errors->first('app_store') }}</span>
                    </div>
                  </div>
                </div>
                <!-- End App Link -->
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