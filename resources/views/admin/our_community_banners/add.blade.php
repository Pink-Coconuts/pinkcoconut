@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Our Community
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="our_community_banners">Our Community</a></li>
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
              <h3 class="box-title">Add Our Community Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => ADMIN_URL.'/add_our_community_banners', 'class' => 'form-horizontal', 'files' => true]) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_language" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('language', $languages, 'en', ['class' => 'form-control', 'id' => 'input_language', 'disabled' =>'disabled']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_title" class="col-sm-3 control-label">Title</label>
                  <div class="col-sm-6">
                    {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'input_title', 'placeholder' => 'Title']) !!}
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('description', '', ['class' => 'form-control', 'id' => 'input_description', 'placeholder' => 'Description']) !!}
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_link" class="col-sm-3 control-label">Link</label>
                  <div class="col-sm-6">
                    {!! Form::text('link', '', ['class' => 'form-control', 'id' => 'input_link', 'placeholder' => 'Link']) !!}
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_description" class="col-sm-3 control-label">Image </label>
                  <em>Size: 318x500</em>
                  <div class="col-sm-6">
                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
                    <p class="note_text">(Note: Upload size minimum 320px * 500px)</p>
                    <span class="text-danger">{{ $errors->first('image') }}</span>
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
                        <label for="input_title_@{{$index}}" class="col-sm-3 control-label">Title</label>
                        <div class="col-sm-6">
                          {!! Form::text('translations[@{{$index}}][title]', '@{{translation.title}}', ['class' => 'form-control', 'id' => 'input_title_@{{$index}}', 'placeholder' => 'Title']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.title'][0] }}</span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="input_description_@{{$index}}" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-6">
                          {!! Form::textarea('translations[@{{$index}}][description]', '@{{translation.description}}', ['class' => 'form-control', 'id' => 'input_description_@{{$index}}', 'placeholder' => 'Description']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.description'][0] }}</span>
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