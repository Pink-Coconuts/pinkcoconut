@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Permission
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../permissions">Permissions</a></li>
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
              <h3 class="box-title">Edit Permission Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="{{ url(ADMIN_URL.'/edit_permission/'.$result->id) }}">
              {!! Form::token() !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_name" class="col-sm-3 control-label">Name</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" id="input_name" placeholder="Name" value="{{ $result->name }}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_display_name" class="col-sm-3 control-label">Display Name</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="display_name" id="input_display_name" placeholder="Display Name" value="{{ $result->display_name }}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Description</label>

                  <div class="col-sm-6">
                    <textarea name="description" class="form-control" placeholder="Description">{{ $result->description }}</textarea>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                 <button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
              </div>
              <!-- /.box-footer -->
            </form>
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