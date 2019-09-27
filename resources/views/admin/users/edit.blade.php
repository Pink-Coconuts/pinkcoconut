@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit User
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../users">Users</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="users">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit User Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/edit_user/'.$result->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_first_name" class="col-sm-3 control-label">First Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('first_name', $result->first_name, ['class' => 'form-control', 'id' => 'input_first_name', 'placeholder' => 'First Name']) !!}
                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_last_name" class="col-sm-3 control-label">Last Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('last_name', $result->last_name, ['class' => 'form-control', 'id' => 'input_last_name', 'placeholder' => 'Last Name']) !!}
                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_email" class="col-sm-3 control-label">Email<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('email', $result->email, ['class' => 'form-control', 'id' => 'input_email', 'placeholder' => 'Email']) !!}
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_password" class="col-sm-3 control-label">Password</label>
                  <div class="col-sm-6">
                    {!! Form::text('password', '', ['class' => 'form-control', 'id' => 'input_password', 'placeholder' => 'Password']) !!}
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_dob" class="col-sm-3 control-label">D.O.B<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('dob', $result->dob_dmy, ['class' => 'form-control', 'id' => 'yearDate', 'placeholder' => 'DOB', 'autocomplete' => 'off','readonly'=>'true']) !!}
                    <span class="text-danger">{{ $errors->first('dob') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), $result->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
                <div class=" col-md-12"  ng-init="id_documents={{ $id_documents }}">
              <div class="form-group add_prof" >
                <label for="input_status" class="col-sm-3 control-label">Id Documents</label>            
                <div class="col-sm-12 form-group" ng-if="id_documents.length > 0">
                  
                  <div class="id_document_slider1">
                    <div ng-repeat="item in id_documents" class="col-md-4 item users_photos" data-id="@{{ item.id }}" >
                      <a href="@{{item.download_src }}"  download="@{{item.name }}" class="delete_but_edit">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                      <img alt="" class="img-responsive-height" ng-src="@{{ item.src }}" >
                    </div>
                  </div>
              
                       </div>


                   <div class="col-sm-9 form-group"  ng-if="!id_documents.length" style="margin-left: -5px;padding-top: 7px;">
                       No Documents Available
                  </div>


                  <div class="verification_status_dropdown">
                    <div class="row">
                    <label for="input_status" class="col-sm-3 control-label">Id Verification status<em class="text-danger">*</em></label> 
                    <div class="col-sm-6">
                      @if($result->original_verification_status == 'No')
                        {!! Form::select('id_document_verification_status', array('Select'=>'Select'), $result->id_document_verification_status == 'No' ? '' : $result->id_document_verification_status, ['class' => 'id_document_verification_status form-control', 'id' => 'input_status']) !!}
                      @else
                        {!! Form::select('id_document_verification_status', array('Pending' => 'Pending','Verified' => 'Verified','Resubmit'=>'Resubmit'), $result->id_document_verification_status == 'No' ? '' : $result->id_document_verification_status, ['class' => 'id_document_verification_status form-control', 'id' => 'input_status']) !!}
                      @endif
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            

              <div class="form-group {{ old('id_document_verification_status') == 'Resubmit' ? '' : 'hide' }} id_resubmit_reason_div">
                <label for="input_status" class="col-sm-3 control-label">Resubmit Reason<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                  {!! Form::textarea('id_resubmit_reason',$result->id_resubmit_reason, ['class' => 'form-control', 'id' => 'input_resubmit_reason', 'placeholder' => 'Resubmit Reason']) !!}
                  <span class="text-danger">{{ $errors->first('id_resubmit_reason') }}</span>
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
var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');
$('#input_dob').datepicker({ 'dateFormat': datepicker_format, maxDate: new Date()});
$(function () {
    $("#yearDate").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:' + new Date().getFullYear().toString(),
        dateFormat: '{{$datepicker_format}}',
    });
    $('.ui-datepicker').addClass('notranslate');
});
</script>
@endpush
@stop