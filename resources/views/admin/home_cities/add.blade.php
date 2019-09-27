@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Home City
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="home_cities">Home Cities</a></li>
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
              <h3 class="box-title">Add Home City Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/add_home_city', 'class' => 'form-horizontal', 'files' => true,'id'=>'form']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>

              <div class="multiple_lang" >
               
                <div class="form-group">
                <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                  @foreach($language as $lang)
                  @php $val[$lang->value]= $lang->name; @endphp 
                  @endforeach
                           
                           
                 {!! Form::select('lang_code[]', $val, 'en', ['class' => 'form-control go','id'=>'lang_1']) !!}
                </div>
                </div>

                <div class="form-group">
                  <label for="input_name" class="col-sm-3 control-label">City Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('name[]', '', ['class' => 'form-control name-input', 'id' => 'input_name_1', 'placeholder' => 'City Name']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>
                </div>

              </div>
              <div class="multiple_lang_add">

                </div>
                <input type="hidden" id="increment" value="1">
                <div class="form-group" style="float:right;margin-right: 10px;">
                <button type="button" class="btn btn-primary add_lang_city" >Add</button>
               
                </div>
                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image']) !!}
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                 <button type="submit" class="btn btn-default pull-left cancel" name="cancel" value="cancel">Cancel</button>
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