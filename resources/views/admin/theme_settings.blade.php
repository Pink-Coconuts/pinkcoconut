@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Theme Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Theme Settings</a></li>
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
              <h3 class="box-title">Theme Settings Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/theme_settings', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Background Color<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="body_bg_color" value="{{$result[0]->value}}" class="form-control" id="input_body_bg_color", placeholder="Background Color">
                    <span class="text-danger">{{ $errors->first('body_bg_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #f5f5f5</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Font Color<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="body_font_color" value="{{$result[1]->value}}" class="form-control" id="input_body_font_color", placeholder="Font Color">
                    <span class="text-danger">{{ $errors->first('body_font_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #565a5c</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Font Size<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    {!! Form::text('body_font_size', $result[2]->value, ['class' => 'form-control', 'id' => 'input_body_font_size', 'placeholder' => 'Font Size']) !!}
                    <span class="text-danger">{{ $errors->first('body_font_size') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: 14px</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Header Color<em class="text-danger">*</em></label> 
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="header_bg_color" value="{{$result[3]->value}}" class="form-control" id="input_header_bg_color", placeholder="Header Color">
                    <span class="text-danger">{{ $errors->first('header_bg_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #ffffff</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Footer Color<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="footer_bg_color" value="{{$result[4]->value}}" class="form-control" id="input_footer_bg_color", placeholder="Footer Color">
                    <span class="text-danger">{{ $errors->first('footer_bg_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #2b2d2e</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Link Color<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="href_color" value="{{$result[5]->value}}" class="form-control" id="input_href_color", placeholder="Link Color">
                    <span class="text-danger">{{ $errors->first('href_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #ff5a5f</span>
                  </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Primary Button Color<em class="text-danger">*</em></label>
                  <div>
                  <div class="col-sm-6">
                    <input type="color" name="primary_btn_color" value="{{$result[6]->value}}" class="form-control" id="input_href_color", placeholder="Primary Button Color">
                    <span class="text-danger">{{ $errors->first('primary_btn_color') }}</span>
                  </div>
                  <div class="col-sm-3">
                  <span class="text-muted">Default: #ff5a5f</span>
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