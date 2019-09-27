@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="site_settings">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Site Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="site_settings">Site Settings</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row" >
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Site Settings Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/site_settings', 'class' => 'form-horizontal', 'files' => true]) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Site Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('site_name', $result[0]->value, ['class' => 'form-control', 'id' => 'input_site_name', 'placeholder' => 'Site Name']) !!}
                    <span class="text-danger">{{ $errors->first('site_name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Add code to the < head >(for tracking codes such as google analytics)</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('head_code', $result[1]->value, ['class' => 'form-control', 'id' => 'input_head_code', 'placeholder' => 'Head Code']) !!}
                    <span class="text-danger">{{ $errors->first('head_code') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_logo" class="col-sm-3 control-label">Logo</label>
                  <em>Size: 140x80</em>
                  <div class="col-sm-6">
                    {!! Form::file('logo', ['class' => 'form-control', 'id' => 'input_logo']) !!}
                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                    <img src="{{ $logo }}" style="margin-top: 10px; max-width: 50%; width: 130px; height: 80px; object-fit: cover;">
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label for="input_home_logo" class="col-sm-3 control-label">Home Page Logo</label>
                  <em>Size: 143x45</em>
                  <div class="col-sm-6">
                    {!! Form::file('home_logo', ['class' => 'form-control', 'id' => 'input_home_logo']) !!}
                    <span class="text-danger">{{ $errors->first('home_logo') }}</span>
                    <img src="{{ $home_logo }}">
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="input_email_logo" class="col-sm-3 control-label">Email Logo</label>
                  <em>Size: 123x55</em>
                  <div class="col-sm-6">
                    {!! Form::file('email_logo', ['class' => 'form-control', 'id' => 'input_email_logo']) !!}
                    <span class="text-danger">{{ $errors->first('email_logo') }}</span>
                    <img src="{{ $email_logo }}" style="margin-top: 10px; max-width: 50%; width: 130px; height: 80px; object-fit: cover;">
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_favicon" class="col-sm-3 control-label">Favicon</label>
                  <em>Size: 16x16</em>
                  <div class="col-sm-6">
                    {!! Form::file('favicon', ['class' => 'form-control', 'id' => 'input_favicon']) !!}
                    <span class="text-danger">{{ $errors->first('favicon') }}</span>
                    <img src="{{ $favicon }}" style="margin-top: 10px; max-width: 50%; width: 25px; height: 25px; object-fit: cover;">
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_home_video" class="col-sm-3 control-label">Home Page Video</label>
                  <em>Type: mp4</em>
                  <div class="col-sm-6">
                    {!! Form::file('home_video', ['class' => 'form-control', 'id' => 'input_home_video']) !!}
                    <span class="text-danger">{{ $errors->first('home_video') }}</span>
                    @if($result[4]->value)
                      <em>{{$result[4]->value}}</em>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_home_video_webm" class="col-sm-3 control-label">Home Page Video</label>
                  <em>Type: webm</em>
                  <div class="col-sm-6">
                    {!! Form::file('home_video_webm', ['class' => 'form-control', 'id' => 'input_home_video_webm']) !!}
                    <span class="text-danger">{{ $errors->first('home_video_webm') }}</span>
                    @if($result[8]->value)
                      <em>{{$result[8]->value}}</em>
                    @endif
                  </div>
                </div>
                  <div class="form-group">
                  <label for="input_home_video_webm" class="col-sm-3 control-label">Default Home Page</label>
                 
                  <div class="col-sm-6">
                    <select class="form-control" name="default_home">
                    <option value="home_one" {{ $home_page_type[0]->value == 'home_one' ? 'selected="selected"' : '' }} >Home Page 1</option>
                    <option value="home_two" {{ $home_page_type[0]->value == 'home_two' ? 'selected="selected"' : '' }} >Home Page 2</option>
                  </select>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="input_default_currency" class="col-sm-3 control-label">Default Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('default_currency', $currency, $default_currency, ['class' => 'form-control', 'id' => 'input_default_currency']) !!}
                    <span class="text-danger">{{ $errors->first('default_currency') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_default_currency" class="col-sm-3 control-label">PayPal Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('paypal_currency', $paypal_currency, $result[12]->value, ['class' => 'form-control', 'id' => 'input_paypal_currency']) !!}
                    <span class="text-danger">{{ $errors->first('paypal_currency') }}</span>
                  </div>
                </div>

               
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Minimum Price<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('minimum_price', $result[19]->value, ['class' => 'form-control', 'id' => 'input_minium_price','autocomplete' => 'off', 'placeholder' => 'Minimum Price']) !!}
                    <span class="text-danger">{{ $errors->first('minimum_price') }}</span>
                  </div>
                </div>


                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Maximum Price<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('maximum_price', $result[20]->value, ['class' => 'form-control', 'id' => 'input_maximum_price','autocomplete' => 'off', 'placeholder' => 'Maximum Price']) !!} 
                    <span class="text-danger">{{ $errors->first('maximum_price') }}</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="input_default_language" class="col-sm-3 control-label">Default Language</label>
                  <div class="col-sm-6">
                    {!! Form::select('default_language', $language, $default_language, ['class' => 'form-control', 'id' => 'input_default_language']) !!}
                    <span class="text-danger">{{ $errors->first('default_language') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_default_language" class="col-sm-3 control-label">File Upload Driver</label>
                  <div class="col-sm-6">
                    {!! Form::select('upload_driver',['php' => 'Local', 'cloudinary' => 'Cloudinary'],$default_upload_driver, ['class' => 'form-control', 'id' => 'input_default_language']) !!}
                    <p class="note_text">For Cloudinary driver, free package maximum upload size is 10MB. To increase the upload size, please check the Cloudinary purchase plans.</p>
                    <span class="text-danger">{{ $errors->first('upload_driver') }}</span>
                    
                  </div>
                </div>
                <div class="form-group hide">
                  <label for="input_currency_provider" class="col-sm-3 control-label">Currency Rate Provider</label>
                  <div class="col-sm-6">
                    {!! Form::select('currency_provider', ['google_finance' => 'Google Finance', 'yahoo_finance' => 'Yahoo Finance'], $result[6]->value, ['class' => 'form-control', 'id' => 'input_currency_provider']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_maintenance_mode" class="col-sm-3 control-label">Maintenance Mode</label>
                  <div class="col-sm-6">
                    {!! Form::select('maintenance_mode', ['up' => 'No', 'down' => 'Yes'], $maintenance_mode, ['class' => 'form-control', 'id' => 'input_maintenance_mode']) !!}
                  </div>
                </div>

                 <!-- <div class="form-group">
                  <label for="input_home_page_mode" class="col-sm-3 control-label">Default home page Mode</label>
                  <div class="col-sm-6">
                    {!! Form::select('home_page_mode', ['up' => 'No', 'down' => 'Yes'],['class' => 'form-control', 'id' => 'input_home_page_mode']) !!}
                  </div>
                </div> -->
               
                 <div class="form-group">
                  <label for="input_favicon" class="col-sm-3 control-label">Footer Cover Image</label>
                  <em>Size: 600x600</em>
                  <div class="col-sm-6">
                    {!! Form::file('footer_cover_image', ['class' => 'form-control', 'id' => 'input_footer_cover_image']) !!}
                    <span class="text-danger">{{ $errors->first('footer_cover_image') }}</span>
                    <img src="{{ $footer_cover_image }}" style="width:120px; margin-top: 10px; height: 120px; object-fit: cover;" >
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_favicon" class="col-sm-3 control-label">Help Page Cover Image</label>
                  <em>Size: 1970x1300</em>
                  <div class="col-sm-6">
                    {!! Form::file('help_page_cover_image', ['class' => 'form-control', 'id' => 'input_help_page_cover_image']) !!}
                    <span class="text-danger">{{ $errors->first('help_page_cover_image') }}</span>
                    <img src="{{ $help_page_cover_image }}" style="width:150px; margin-top: 10px; height: 120px; object-fit: cover;"  >
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_favicon" class="col-sm-3 control-label">Site Date Format</label>
                  <div class="col-sm-6">
                    
                    {!! Form::select('site_date_format', $dateformats, $result[11]->value, ['class' => 'form-control', 'id' => 'input_default_currency']) !!}
                    
                    <span class="text-danger">{{ $errors->first('site_date_format') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_favicon" class="col-sm-3 control-label">Home Page Media</label>
                  <div class="col-sm-6">
                    {!! Form::select('home_page_header_media', ['Video' => 'Video', 'Slider' => 'Image Slider'], $result[13]->value, ['class' => 'form-control', 'id' => 'input_home_page_header_media']) !!}
                    <span class="text-danger">{{ $errors->first('home_page_header_media') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Version</label>
                  <div class="col-sm-6">
                    {!! Form::text('version', $result[16]->value, ['class' => 'form-control', 'id' => 'input_version', 'placeholder' => 'Version']) !!}
                    <span class="text-danger">{{ $errors->first('version') }}</span>
                  </div>
                </div>

                 <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Admin Prefix<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('admin_url', $result[17]->value, ['class' => 'form-control', 'id' => 'input_url', 'placeholder' => 'url']) !!}
                    <span class="text-danger">{{ $errors->first('admin_url') }}</span>
                  </div><div class="col-sm-3">
                  <em class="text-muted">Default: admin</em>
                  </div>
                </div>
                  <div class="form-group">
                  <label for="input_number" class="col-sm-3 control-label">Customer Support Number</label>
                  <div class="col-sm-6">
                    {!! Form::text('customer_number', $result[21]->value, ['class' => 'form-control', 'id' => 'input_number', 'placeholder' => 'Customer support number']) !!}
                    <span class="text-danger">{{ $errors->first('customer_number') }}</span>
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