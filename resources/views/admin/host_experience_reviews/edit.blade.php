@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Experience Review
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../../host_experiences"> Experience Reviews</a></li>
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
              <h3 class="box-title">Edit Experience Review Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/exp_edit_review/'.$result[0]->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_reservation_id" class="col-sm-3 control-label">Reservation Id</label>
                  <div class="col-sm-6">
                    {!! Form::text('reservation_id', $result[0]->reservation_id, ['class' => 'form-control', 'id' => 'input_reservation_id', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_room_name" class="col-sm-3 control-label">Experience Title</label>
                  <div class="col-sm-6">
                    {!! Form::text('room_name', $result[0]->room_name, ['class' => 'form-control', 'id' => 'input_room_name', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_user_from" class="col-sm-3 control-label">User From</label>
                  <div class="col-sm-6">
                    {!! Form::text('user_from', $result[0]->user_from, ['class' => 'form-control', 'id' => 'input_user_from', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_user_to" class="col-sm-3 control-label">User To</label>
                  <div class="col-sm-6">
                    {!! Form::text('user_to', $result[0]->user_to, ['class' => 'form-control', 'id' => 'input_user_to', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_review_by" class="col-sm-3 control-label">Review By</label>
                  <div class="col-sm-6">
                    {!! Form::text('review_by', ucfirst($result[0]->review_by), ['class' => 'form-control', 'id' => 'input_review_by', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_comments" class="col-sm-3 control-label">Comments<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('comments', $result[0]->comments, ['class' => 'form-control', 'id' => 'input_comments', 'placeholder' => 'Comments']) !!}
                    <span class="text-danger">{{ $errors->first('comments') }}</span>
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
  @push('scripts')
<script>
  $('#input_dob').datepicker({ 'format': 'dd-mm-yyyy'});
</script>
@endpush
@stop