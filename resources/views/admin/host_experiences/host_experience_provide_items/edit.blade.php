@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit {{$main_title}}
    </h1>
    <ol class="breadcrumb">
      <li><a href="../../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="../../host_experience_provide_items">{{$main_title}}</a></li>
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
            <h3 class="box-title">Edit {{$main_title}} Form</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          {!! Form::open(['url' => $base_url.'/edit/'.$id, 'class' => 'form-horizontal','id'=>'form', 'files' => true]) !!}
          <div class="box-body">
            @include($base_view_path.'form')
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right" id="edt_btn" name="submit" value="submit">Submit</button>
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