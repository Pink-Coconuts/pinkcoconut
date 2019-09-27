@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="page">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Page
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="pages">Page</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Page Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/add_page', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_language" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('language', $languages, 'en', ['class' => 'form-control', 'id' => 'input_language', 'disabled' =>'disabled']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'input_name', 'placeholder' => 'Name']) !!}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_content" class="col-sm-3 control-label">Content<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <textarea id="txtEditor" name="txtEditor"></textarea>
                    <textarea id="content" name="content" hidden="true">{{ old('content') }}</textarea>
                    <span class="text-danger">{{ $errors->first('content') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_footer" class="col-sm-3 control-label">Footer<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('footer', array('yes' => 'Yes', 'no' => 'No'), '', ['class' => 'form-control', 'id' => 'input_footer', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('footer') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_under" class="col-sm-3 control-label">Under</label>
                  <div class="col-sm-6">
                    {!! Form::select('under', array('company' => 'Company', 'discover' => 'Discover', 'hosting' => 'Hosting'), '', ['class' => 'form-control', 'id' => 'input_under', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('under') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), '', ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
                
                <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: array())}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}};">
                  <div class="panel-header">
                    <h4 class="box-title text-center">Translations</h4>
                  </div>
                  <div class="panel-body">
                    <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                    <div class="row" ng-repeat="translation in translations">
                      <input type="hidden" name="translations[@{{$index}}][id]" value="@{{translation.id}}">
                      <div class="form-group">
                        <label for="input_language_@{{$index}}" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <select name="translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
                            <option value="" ng-if="translation.locale == ''">Select Language</option>
                            @foreach($languages as $key => $value)
                              <option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
                            @endforeach
                          </select>
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.locale'][0] }}</span>
                        </div>
                        <div class="col-sm-1">
                          <button class="btn btn-danger btn-xs" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="input_name_@{{$index}}" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          {!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Name']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.name'][0] }}</span>
                        </div>
                      </div>

                      <div class="form-group"  ng-init="multiple_editors($index)">
                        <label for="input_content_@{{$index}}" class="col-sm-3 control-label">Content<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <textarea class="editors" id="editor_@{{$index}}" name="translations[@{{$index}}][txtEditor]"></textarea>
                          <textarea class="contents" id="content_@{{$index}}" name="translations[@{{$index}}][content]" hidden="true">@{{translation.content}}</textarea>
                          {{--{!! Form::textarea('translations[@{{$index}}][content]', '@{{translation.content}}', ['class' => 'form-control', 'id' => 'input_content_@{{$index}}', 'placeholder' => 'Content', 'hidden' => true]) !!}--}}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.content'][0] }}</span>
                        </div>
                      </div>

                      <legend ng-if="$index+1 < translations.length"></legend>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <div class="row" ng-show="translations.length <  {{count($languages) - 1}}">
                      <div class="col-sm-12">
                        <button type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                          <i class="fa fa-plus"></i> Add Translation
                        </button>
                      </div>
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

@push('scripts')
<script type="text/javascript">
$("#txtEditor").Editor(); 
$('.Editor-editor').html($('#content').val());
</script>
@endpush