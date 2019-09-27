@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Property Type
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../property_type">Property Type</a></li>
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
              <h3 class="box-title">Edit Property Type Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/edit_property_type/'.$result->id, 'class' => 'form-horizontal','id'=>'form','files'=>true]) !!}
            <input type="hidden" value="{{ $result->id }}" id ="property_type_id" name="lang_id[]">
 

              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>


              <div class="multiple_lang">
             
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
                <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                {!! Form::text('name[]', $result->name, ['class' => 'form-control name-input', 'id' => 'input_name', 'placeholder' => 'Name','required']) !!}  
                           
                </div>
                </div>

                <div class="form-group">
                <label for="input_description" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-6">
                {!! Form::textarea('description[]', $result->description, ['class' => 'form-control', 'id' => 'input_description', 'placeholder' => 'Description', 'rows' => 3]) !!}
              
                </div>
                </div>                     
               </div>
               <div class="form-group">
                  <label for="input_icon" class="col-sm-3 control-label">Icon</label>

                  <div class="col-sm-6">
                    {!! Form::File('icons', ['class' => 'form-control', 'id' => 'input_icon_name_1', 'placeholder' => 'Icon', 'rows' => 3]) !!}
                    <span class="text-danger">{{ $errors->first('icons') }}</span>
                    <p class="note_text">(Note: Upload size maximum 24px * 24px)</p>
                    @if($result->icon)
                      <img width="40" height="40" src="{{$result->image_name}}">
                    @endif
                  </div>
                </div>


            

               <div class="multiple_lang_add">

@php $i=2; @endphp 
@foreach($langresult as $langs)

                <div class="multiple_lang">  
                <input type="hidden" value="{{ $langs->id }}" id ="property_type_id" name="lang_id[]"">
             
                <div class="form-group">
                <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                 @foreach($language as $lang)
                 @php $val[$lang->value]= $lang->name; @endphp 
              @endforeach
                           
                 {!! Form::select('lang_code[]', $val, $langs->lang_code, ['class' => 'form-control go','id'=>'lang_'.$i]) !!}
                </div>
                </div>

                <div class="form-group">
                <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                {!! Form::text('name[]',$langs->name, ['class' => 'form-control name-input', 'id' => 'input_name_'.$i, 'placeholder' => 'Name','required']) !!}  

                </div>
                </div>

                <div class="form-group">
                <label for="input_description" class="col-sm-3 control-label">Description</label>

                <div class="col-sm-6">
                {!! Form::textarea('description[]', $langs->description,['class' => 'form-control', 'id' => 'input_description_'.$i, 'placeholder' => 'Description', 'rows' => 3]) !!}
                <span class="text-danger">{{ $errors->first('description') }}</span>
                </div>
                </div> 
                <button type="button" class="btn btn-danger remove_lang" style="float:right;">Remove</button>
                 </div>
@php $i++; @endphp 
@endforeach
                
                </div>
                <input type="hidden" id="increment" value="{{ count($language) +1 }}"> 
                <div class="form-group" style="float:right;margin-right: 10px;">
                <button type="button" class="btn btn-primary add_lang" >Add</button>
                 </div>

              
                <div class="form-group status">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), $result->status,['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Update</button>
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
  <style>
  .error{   color: #a94442; }
  </style>
@stop