@extends('admin.template')

@section('main')
<style type="text/css">
  [ng\:cloak], [ng-cloak], .ng-cloak {
    display: none !important;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit {{$main_title}}
    </h1>
    <ol class="breadcrumb">
      <li><a href="../../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="../../host_experiences">{{$main_title}}</a></li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- right column -->
      <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box box-info row" ng-controller="host_experiences_admin" ng-cloak>
          <div class="box-header with-border"  ng-init="edit_steps(); go_to_step('{{$tab}}')">
            <h3 class="box-title">Edit {{$main_title}} Form</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          {!! Form::open(['url' => $base_url.'/edit/'.$host_experience->id, 'class' => 'form-horizontal','id'=>'host_experience_form', 'files' => true]) !!}
          <div class="box-header with-border tabs-header col-sm-2">
            <ul class="list-unstyled margin-bot-5 list-nav-link">
              <li class="nav-item pre-listed" ng-repeat="step_id in steps" ng-class="step == $index ? 'nav-active' : '';">
                <a href="javascript:void(0);" class="tab-btn" ng-click="go_to_step($index)">@{{$index+1}}. @{{get_step_name($index)}}</a> 
              </li>
            </ul>
          </div>
          <div class="box-body col-sm-10">
            <p class="text-danger">(*)Fields are Mandatory</p>
            <input type="hidden" name="current_step_id" ng-value="steps[step]" id="input_current_step_id" >
            <input type="hidden" name="current_step" ng-value="step" id="input_current_step" >
            <div id="sf1" class="frm hide" data-step-name="Select City" ng-init="currency_code = '{{$host_experience->currency_code}}'; timezone_abbr = ''; currency_symbol = '{{$host_experience->currency->original_symbol}}'; host_experience_id='{{$host_experience->id}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_city" class="col-sm-3 control-label">City<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <select class="form-control" name="city" id="input_city" ng-model="city" ng-change="city_changed()">
                      <option value="">Select City</option>
                      @foreach($cities as $city)
                      <option value="{{$city->id}}" data-currency_code="{{$city->currency_code}}" data-timezone_abbr="{{$city->timezone_abbr}}" data-currency_symbol="{{$city->currency->original_symbol}}">{{$city->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf2" class="frm" data-step-name="Language" ng-init="language = '{{$host_experience->language}}'">
              <fieldset class="box-body">
                <div class="form-group" >
                  <label for="input_language" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('language', $languages, $host_experience->language, ['class' => 'form-control', 'id' => 'input_language', 'placeholder' => 'Select Language', 'ng-model' => 'language']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf3" class="frm" data-step-name="Category" ng-init="category = '{{$host_experience->category > 0 ? $host_experience->category : ''}}'; secondary_category = '{{$host_experience->secondary_category > 0 ? $host_experience->secondary_category : ''}}'; is_secondary = (secondary_category > 0 ? secondary_category : false);">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_category" class="col-sm-3 control-label">Category<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('category', $categories->pluck('name', 'id'), '', ['class' => 'form-control', 'id' => 'input_category', 'placeholder' => 'Select Category', 'ng-model' => 'category', 'ng-change' => 'category_changed()']) !!}
                    <p><a href="javascript:void(0);" ng-show="!is_secondary" ng-click="is_secondary = true;">Add secondary category (optional)</a></p>
                  </div>
                </div>
                <div class="form-group" ng-show="is_secondary">
                  <label for="input_secondary_category" class="col-sm-3 control-label">Secondary Category</label>
                  <div class="col-sm-6">
                    <select class="form-control" name="secondary_category" id="input_secondary_category" ng-model="secondary_category">
                      <option value="">Select Secondary Category</option>
                      @foreach($categories as $category)
                      <option value="{{$category->id}}" ng-if="'{{$category->id}}' != category">{{$category->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <a href="javascript:void(0);" ng-click="is_secondary = false; secondary_category=''">Remove</a>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf4" class="frm" data-step-name="Experience Title" ng-init="title = '{{addslashes($host_experience->title)}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_title" class="col-sm-3 control-label">Title<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'input_title', 'placeholder' => 'Enter experience name', 'ng-model' => 'title']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf5" class="frm" data-step-name="Time" ng-init="start_time = '{{$host_experience->start_time}}'; end_time = '{{$host_experience->end_time}}';">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_start_time" class="col-sm-3 control-label">Set Default Time<em class="text-danger">*</em></label>
                  <div class="col-sm-3">
                    <select name="start_time" class="form-control" id="input_start_time" ng-model="start_time" ng-change="start_time_changed();">
                      <option value="">Select Start Time</option>
                      @foreach($times_array as $k => $v)
                      <option value="{{$k}}" ng-if="'{{$k}}' < '23:00:00'">{{$v}}@{{timezone_abbr}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-3" ng-init="minimum_end_time='00:00:00';start_time_changed()">
                    <select name="end_time" class="form-control" id="input_end_time" ng-model="end_time">
                      <option value="">Select End Time</option>
                      @foreach($times_array as $k => $v)
                      <option value="{{$k}}" ng-if="'{{$k}}' >= minimum_end_time">{{$v}}@{{timezone_abbr}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf6" class="frm" data-step-name="Tagline" ng-init="tagline = '{{addslashes($host_experience->tagline)}}';">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_tagline" class="col-sm-3 control-label">Tagline<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('tagline', '', ['class' => 'form-control', 'id' => 'input_tagline', 'placeholder' => 'Write your tagline here', 'ng-model' => 'tagline']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf7" class="frm" data-step-name="Photos" ng-init="photos= [{name:''}]">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_photos" class="col-sm-3 control-label">Photos<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <div class="input-group photo_step" ng-repeat="photo in photos">
                      {!! Form::file('photos[@{{$index}}]', ['class' => 'form-control photos_check', 'id' => 'input_photos_@{{$index}}', 'ng-model' => 'photo[$index]', 'data-error-placement' => 'parent', 'style' => 'margin-top:10px']) !!}
                      <span class="input-group-addon choose_button_opt" style="margin: 15px 0 0;" ng-if="$index > 0" ng-click="photos.splice($index, 1)">
                        <i class="fa fa-close text-danger"></i>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-6 col-sm-offset-3">
                    <a href="javascript:void(0)" ng-click="photos.push({name:''})" class="btn btn-primary" style="margin-top: 10px">Add Photos</a>
                  </div>
                </div>
                <ul class="row list-unstyled sortable" id="js-photo-grid">
                  @foreach($host_experience->host_experience_photos as $row)
                  <li style="display: inline-block;width:200px;" id="photo_li_{{ $row->id }}" class="col-4 row-space-4 ng-scope">
                    <div class="panel photo-item"> 
                      <div id="photo-5" class="photo-size photo-drag-target js-photo-link">
                      </div>
                      <a href="javascript:void(0)" class="media-photo media-photo-block text-center photo-size">
                        <img alt="" class="img-responsive-height" src="{{ $row->image_url }}">
                      </a>
                      <button class="delete-photo-btn overlay-btn js-delete-photo-btn" data-photo-id="{{ $row->id }}" type="button" ng-click="delete_photo('{{$row->id}}')">
                        <i class="fa fa-trash" style="color:white;">
                        </i>
                      </button>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </fieldset>
            </div>
            <div id="sf8" class="frm" data-step-name="What you'll do" ng-init="what_will_do = '{{addslashes($host_experience->what_will_do)}}';">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_what_will_do" class="col-sm-3 control-label">What we'll do<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('what_will_do', '', ['class' => 'form-control', 'id' => 'input_what_will_do', 'placeholder' => 'What we\'ll do', 'ng-model' => 'what_will_do']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf9" class="frm" data-step-name="Where you'll be" ng-init="where_will_be = '{{addslashes($host_experience->where_will_be)}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_where_will_be" class="col-sm-3 control-label">Where we'll be<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('where_will_be', '', ['class' => 'form-control', 'id' => 'input_where_will_be', 'placeholder' => 'Where we\'ll be', 'ng-model' => 'where_will_be']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf10" class="frm" data-step-name="Where we'll meet" ng-init="initialize_autocomplete()">
              <fieldset class="box-body">
                <h5 class="text-bold">Step 1: Provide an address</h5>
                <div class="form-group" ng-init="location_name = '{{addslashes($host_experience->host_experience_location->location_name)}}'">
                  <label for="input_location_name" class="col-sm-3 control-label">Location Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('location[location_name]', '', ['class' => 'form-control', 'id' => 'location_name', 'placeholder' => 'Location name', 'ng-model' => 'location_name' ]) !!}
                  </div>
                </div> 
                <div class="form-group" ng-init="country = '{{$host_experience->host_experience_location->country}}'">
                  <label for="input_country" class="col-sm-3 control-label">Country<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('location[country]', $countries, '', ['class' => 'form-control', 'id' => 'input_country', 'placeholder' => 'Select country', 'ng-model' => 'country']) !!}
                  </div>
                </div> 
                <div class="form-group" ng-init="address_line_1 = '{{addslashes($host_experience->host_experience_location->address_line_1)}}'">
                  <label for="input_address_line_1" class="col-sm-3 control-label">Street address<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('location[address_line_1]', '', ['class' => 'form-control', 'id' => 'input_address_line_1', 'placeholder' => 'Enter a location', 'autocomplete' => 'off', 'ng-model' => 'address_line_1']) !!}
                    <p class="location_error"></p>
                  </div>
                </div>  
                <div class="form-group" ng-init="address_line_2 = '{{addslashes($host_experience->host_experience_location->address_line_2)}}'">
                  <label for="input_address_line_2" class="col-sm-3 control-label">Apt, Suite, Bldg. (optional)</label>
                  <div class="col-sm-6">
                    {!! Form::text('location[address_line_2]', '', ['class' => 'form-control', 'id' => 'input_address_line_2', 'placeholder' => '', 'ng-model' => 'address_line_2']) !!}
                  </div>
                </div>    
                <div class="form-group" ng-init="location_city = '{{addslashes($host_experience->host_experience_location->city)}}'">
                  <label for="input_city" class="col-sm-3 control-label">City<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('location[city]', '', ['class' => 'form-control', 'id' => 'input_city', 'placeholder' => '', 'ng-model' => 'location_city']) !!}
                  </div>
                </div>     
                <div class="form-group" ng-init="state = '{{addslashes($host_experience->host_experience_location->state)}}'">
                  <label for="input_state" class="col-sm-3 control-label">State</label>
                  <div class="col-sm-6">
                    {!! Form::text('location[state]', '', ['class' => 'form-control', 'id' => 'input_state', 'placeholder' => '', 'ng-model' => 'state']) !!}
                  </div>
                </div>     
                <div class="form-group" ng-init="postal_code = '{{$host_experience->host_experience_location->postal_code}}'; latitude = '{{$host_experience->host_experience_location->latitude}}'; longitude = '{{$host_experience->host_experience_location->longitude}}';">
                  <label for="input_postal_code" class="col-sm-3 control-label">ZIP Code</label>
                  <div class="col-sm-6">
                    {!! Form::text('location[postal_code]', '', ['class' => 'form-control', 'id' => 'input_postal_code', 'placeholder' => '', 'ng-model' => 'postal_code']) !!}
                  </div>
                </div>  
                <input type="hidden" name="location[latitude]" class="do-not-ignore" id="input_latitude" ng-model="latitude" ng-value="latitude" data-error-placement="container" data-error-container=".location_error">
                <input type="hidden" name="location[longitude]" class="do-not-ignore" id="input_longitude" ng-model="longitude" ng-value="longitude" data-error-placement="container" data-error-container=".location_error">
                <div class="step2" ng-show="latitude && longitude" ng-init="initialize_map()">
                  <h5 class="text-bold">Step 2: Drop a pin on the map</h5>
                  <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3" id="host_experience_location_map" style="height: 300px;">
                    </div>
                  </div>
                  <div class="form-group" ng-init="directions='{{addslashes($host_experience->host_experience_location->directions)}}'">
                    <label for="input_directions" class="col-sm-3 control-label">Directions (optional)</label>
                    <div class="col-sm-6">
                      {!! Form::textarea('location[directions]', '', ['class' => 'form-control', 'id' => 'input_directions', 'placeholder' => '', 'ng-model' => 'directions', 'rows' => 3]) !!}
                    </div>
                  </div>  
                </div>
              </fieldset>
            </div>
            <div id="sf11" class="frm" data-step-name="What you'll provide" ng-init="provides = {{json_encode($host_experience->host_experience_provides)}}; provide_items = {{json_encode($provide_items)}}">
              <fieldset class="box-body">
                <div class="col-sm-10 col-sm-offset-1">
                  <div class="panel panel-primary" ng-repeat="provide in provides">
                    <div class="panel-heading">
                      <h5 class="panel-title">Item @{{$index+1}}</h5>
                    </div>
                    <div class="panel-body">
                      <input type="hidden" name="provides[@{{$index}}][id]" ng-model="provides[$index].id" value="@{{provides[$index].id}}">
                      <div class="form-group">
                        <label class="control-label col-sm-3" for="provides_@{{$index}}_host_experience_provide_item_id">Provide Item<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <select name="provides[@{{$index}}][host_experience_provide_item_id]" class="form-control required" id="provides_@{{$index}}_host_experience_provide_item_id" ng-model="provides[$index].host_experience_provide_item_id" >
                            <option value="">Select item</option>
                            <option ng-repeat="item in provide_items" ng-selected="provide.host_experience_provide_item_id == item.id" value="@{{item.id}}" ng-if="provide_item_available(item.id, $parent.$index)">@{{item.name}}</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3" for="provides_@{{$index}}_name">Name Item<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <input name="provides[@{{$index}}][name]" class="form-control required" id="provides_@{{$index}}_name" ng-model="provides[$index].name" placeholder="Name item" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3" for="provides_@{{$index}}_additional_details">Additional details (optional)</label>
                        <div class="col-sm-6">
                          <textarea name="provides[@{{$index}}][additional_details]" class="form-control" id="provides_@{{$index}}_additional_details" ng-model="provides[$index].additional_details" placeholder="Additional details">
                          </textarea>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer text-right" ng-if="$index != 0 || need_provides=='No'">
                      <a href="javascript:void(0)" ng-click="remove_provide($index)" class="btn btn-danger">Remove Provide Item</a>
                    </div>
                  </div>
                  <div class="panel">
                    <a href="javascript:void(0)" ng-click="add_provide()" class="btn btn-primary">Add @{{provides.length > 0 ? 'Another' : ''}} Item</a>
                    <p class="need_provides_error"></p>
                  </div>
                  <div class="form-group" ng-init="need_provides = '{{$host_experience->need_provides}}'">
                    <label class="col-sm-9 col-sm-offset-3" for="input_need_provides">
                      Not providing anything for your guests?
                      <input type="checkbox" value="No" name="need_provides" class="provide_count_check" id="input_need_provides" ng-model="need_provides" ng-true-value="'No'" ng-false-value="'Yes'" data-error-placement="container" data-error-container=".need_provides_error" />
                    </label>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf12" class="frm" data-step-name="Notes" ng-init="notes = '{{addslashes($host_experience->notes)}}'; need_notes = '{{$host_experience->need_notes}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_notes" class="col-sm-3 control-label">Notes<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('notes', '', ['class' => 'form-control', 'id' => 'input_notes', 'placeholder' => 'What else should guests know?', 'ng-model' => 'notes', 'rows' => 4]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-9 col-sm-offset-3" for="input_need_notes">
                    I have no additional notes for my guests
                    <input type="checkbox" value="No" name="need_notes" class="provide_count_check" id="input_need_notes" ng-model="need_notes" ng-true-value="'No'" ng-false-value="'Yes'" />
                  </label>
                </div>
              </fieldset>
            </div>
            <div id="sf13" class="frm" data-step-name="About You" ng-init="about_you = '{{addslashes($host_experience->about_you)}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_about_you" class="col-sm-3 control-label">Write your bio<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('about_you', '', ['class' => 'form-control', 'id' => 'input_about_you', 'placeholder' => 'Write your bio', 'ng-model' => 'about_you', 'rows' => 4]) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf14" class="frm" data-step-name="Guest Requirements">
              <fieldset class="box-body">
                <h5 class="text-bold">Who can attend your experience?</h5>
                <div class="form-group" ng-init="includes_alcohol = '{{$host_experience->guest_requirements->includes_alcohol}}'">
                  <label for="input_includes_alcohol" class="col-sm-3 control-label">Alcohol</label>
                  <div class="col-sm-6">
                    <label for="input_includes_alcohol" style="padding-top: 5px;">
                      {!! Form::checkbox('includes_alcohol', 'Yes', null, ['class' => '', 'id' => 'input_includes_alcohol', 'ng-model' => 'includes_alcohol', 'ng-true-value' => '"Yes"', 'ng-false-value' => '"No"']) !!}
                      My experience includes alcohol. Only guests that meet the legal drinking age will be served.
                    </label>
                  </div>
                </div>
                <div class="form-group" ng-init="minimum_age = '{{$host_experience->guest_requirements->minimum_age}}'">
                  <label for="input_minimum_age" class="col-sm-3 control-label">Minimum Age<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('minimum_age', $minimum_age_array, '', ['class' => 'form-control', 'id' => 'input_minimum_age', 'placeholder' => 'Select minimum age', 'ng-model' => 'minimum_age']) !!}
                  </div>
                </div>
                <div class="form-group" ng-init="allowed_under_2 = '{{$host_experience->guest_requirements->allowed_under_2}}'">
                  <label for="input_allowed_under_2" class="col-sm-3 control-label"></label>
                  <div class="col-sm-6">
                    <label for="input_allowed_under_2">
                      {!! Form::checkbox('allowed_under_2', 'Yes', null, ['class' => '', 'id' => 'input_allowed_under_2', 'ng-model' => 'allowed_under_2', 'ng-true-value' => '"Yes"', 'ng-false-value' => '"No"']) !!}
                      Parents can bring kids under 2 years
                    </label>
                  </div>
                </div>
                <div class="form-group" ng-init="special_certifications = '{{addslashes($host_experience->guest_requirements->special_certifications)}}'">
                  <label for="input_special_certifications" class="col-sm-3 control-label">Special certifications</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('special_certifications', '', ['class' => 'form-control', 'id' => 'input_special_certifications', 'placeholder' => 'Special certifications', 'ng-model' => 'special_certifications', 'rows' => 4]) !!}
                  </div>
                </div>
                <div class="form-group" ng-init="additional_requirements = '{{addslashes($host_experience->guest_requirements->additional_requirements)}}'">
                  <label for="input_additional_requirements" class="col-sm-3 control-label">Additional requirements</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('additional_requirements', '', ['class' => 'form-control', 'id' => 'input_additional_requirements', 'placeholder' => 'Additional requirements', 'ng-model' => 'additional_requirements', 'rows' => 4]) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf15" class="frm" data-step-name="Group size" ng-init="number_of_guests = '{{$host_experience->number_of_guests}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_number_of_guests" class="col-sm-3 control-label">Maximum number of guests<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('number_of_guests', $group_size_array, '', ['class' => 'form-control', 'id' => 'input_number_of_guests', 'placeholder' => 'Select maximum number of guests', 'ng-model' => 'number_of_guests']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf16" class="frm" data-step-name="Price" ng-init="price_per_guest = '{{$host_experience->price_per_guest}}'">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_price_per_guest" class="col-sm-3 control-label">Set a price per guest<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <div class="input-group"  >
                      <span class="input-group-addon text-bold" style="margin: 0">@{{currency_code}}</span>
                      {!! Form::text('price_per_guest', '', ['class' => 'form-control numeric-values', 'id' => 'input_price_per_guest', 'placeholder' => '@{{currency_symbol}}', 'ng-model' => 'price_per_guest']) !!}
                    </div>
                  </div>
                </div>     
                <div class="form-group" ng-init="is_free_under_2 = '{{$host_experience->is_free_under_2}}'" ng-show="allowed_under_2 == 'Yes'">
                  <label for="input_is_free_under_2" class="col-sm-3 control-label"></label>
                  <div class="col-sm-6">
                    <label for="input_is_free_under_2">
                      {!! Form::checkbox('is_free_under_2', 'Yes', null, ['class' => '', 'id' => 'input_is_free_under_2', 'ng-model' => 'is_free_under_2', 'ng-true-value' => '"Yes"', 'ng-false-value' => '"No"']) !!}
                      Free for guests under age 2
                    </label>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf17" class="frm" data-step-name="Preparation Time" ng-init="last_minute_guests = 'No'">
              <fieldset class="box-body">
                <h5 class="text-bold">How much time do you need to prepare?</h5>
                <div class="form-group" ng-init="preparation_hours = '{{$host_experience->preparation_hours}}'">
                  <label for="input_preparation_hours" class="col-sm-3 control-label">Preparation Time<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('preparation_hours', $preparation_times_array, '', ['class' => 'form-control', 'id' => 'input_preparation_hours', 'placeholder' => 'Select preparation time', 'ng-model' => 'preparation_hours']) !!}
                  </div>
                </div>
                <div class="form-group" ng-init="last_minute_guests = '{{$host_experience->last_minute_guests}}'">
                  <label for="input_last_minute_guests" class="col-sm-3 control-label">Can you accommodate last minute guests?</label>
                  <div class="col-sm-3">
                    <label>
                      {!! Form::radio('last_minute_guests', 'No', true, ['class' => '', 'id' => 'input_last_minute_guests', 'ng-model' => 'last_minute_guests']) !!}
                      No thanks
                    </label>
                  </div>
                  <div class="col-sm-3">
                    <label>
                      {!! Form::radio('last_minute_guests', 'Yes', false, ['class' => '', 'id' => 'input_last_minute_guests', 'ng-model' => 'last_minute_guests']) !!}
                      Yes, I’m flexible
                    </label>
                  </div>
                </div>
                <div class="form-group" ng-show="last_minute_guests == 'Yes'" ng-init="cutoff_time = '{{$host_experience->cutoff_time}}'">
                  <label for="input_cutoff_time" class="col-sm-3 control-label">Cutoff Time</label>
                  <div class="col-sm-6">
                    {!! Form::select('cutoff_time', $cutoff_times_array, $host_experience->cutoff_time, ['class' => 'form-control', 'id' => 'input_cutoff_time']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf18" class="frm" data-step-name="Packing List" ng-init="packing_lists = {{json_encode($host_experience->host_experience_packing_lists)}}; need_packing_lists = '{{$host_experience->need_packing_lists}}'">
              <fieldset class="box-body">
                <div class="col-sm-10 col-sm-offset-1">
                  <h4 style="font-size: 17px;">What should your guests bring?</h4>
                  <div class="panel panel-primary" ng-repeat="list in packing_lists">
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                          <div class="input-group">
                            <input type="hidden" name="packing_lists[@{{$index}}][id]" class="form-control" id="packing_lists_@{{$index}}_id" ng-value="packing_lists[$index].id" />
                            <input name="packing_lists[@{{$index}}][item]" class="form-control required" id="packing_lists_@{{$index}}_name" ng-model="packing_lists[$index].item" placeholder="Enter item here" style="width: 90%" data-error-placement="parent" />
                            <span class="input-group-addon" ng-click="remove_packing_list($index)" style="margin: 0px;"> <i class="fa fa-close text-danger"></i> </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel">
                    <a href="javascript:void(0)" ng-click="add_packing_list()" class="btn btn-primary">Add @{{packing_lists.length > 0 ? 'Another' : ''}} Item</a>
                    <p class="need_packing_lists_error"></p>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-9 col-sm-offset-3" for="input_need_packing_lists">
                      My guests don’t need to bring anything
                      <input type="checkbox" value="No" name="need_packing_lists" class="packing_list_count_check" id="input_need_packing_lists" ng-model="need_packing_lists" ng-true-value="'No'" ng-false-value="'Yes'" data-error-placement="container" data-error-container=".need_packing_lists_error" />
                    </label>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf19" class="frm" data-step-name="Host User">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="input_user_id" class="col-sm-3 control-label">Host User</label>
                  <div class="col-sm-6">
                    {!! Form::select('user_id', $users_array, '', ['class' => 'form-control', 'id' => 'input_user_id', 'placeholder' => 'Select host user', 'ng-model' => 'user_id']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf20" class="frm" data-step-name="Calendar">
              <fieldset class="box-body">
                <div class="row">
                  <div class="col-sm-9 " id="calendar">
                    @include($base_view_path.'calendar')
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer" ng-show="step != 0">
            <button class="btn btn-primary open2 pull-right hide" type="Submit" name="submit" value="submit" >
              <span>Update
              </span> 
            </button>
            <button class="btn btn-primary open2 pull-right" style="margin-right: 10px" type="Submit" name="submit" value="submit_exit" id="butn" >
              <span>Submit
              </span> 
            </button>
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
<link rel="stylesheet" type="text/css" href="{{url('css/manage_listing.css')}}">
<script src="{{ url('admin_assets/dist/js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{url('js/host_experiences/host_experiences_admin.js')}}"></script>
<script type="text/javascript" src="{{url('js/moment.js')}}"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>
<style type="text/css">
  #js-photo-grid li img {
    height: 150px;
    object-fit: cover;
  }
  .pac-container.pac-logo
  {
    position: absolute !important;
  }
  .tab-btn {
    display: block;
    font-size: 14px !important;
  }
  .tabs-header {
    border-right: 1px solid #afafaf;
    border-bottom: 0px !important;
    padding: 0px !important;
  }
</style>
@endpush