@extends('admin.template')
@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-controller="rooms_admin" ng-init="add_room_steps()">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Room
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard">
          <i class="fa fa-dashboard">
          </i> Home
        </a>
      </li>
      <li>
        <a href="rooms">Rooms
        </a>
      </li>
      <li class="active">Add
      </li>
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
            <h3 class="box-title">Add Room Form
            </h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          {!! Form::open(['url' => ADMIN_URL.'/add_room', 'class' => 'form-horizontal', 'id' => 'add_room_form', 'files' => true]) !!}
          <div class="box-body" ng-cloak>
            <div class="box-header with-border">
              <h4 class="box-title">Step @{{step+1}} of @{{steps.length}} - @{{step_name}}</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>
            <div id="sf1" class="frm hide" data-step-name="Calendar">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Calendar
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('calendar', ['Always'=>'Always'], '', ['class' => 'form-control', 'id' => 'calendar', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf2" class="frm" data-step-name="Basics">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="bedrooms" class="col-sm-3 control-label">Bedrooms
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('bedrooms', $bedrooms, '', ['class' => 'form-control', 'id' => 'bedrooms', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="beds" class="col-sm-3 control-label">Beds
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('beds', $beds, '', ['class' => 'form-control', 'id' => 'beds', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="bed_type" class="col-sm-3 control-label">Bed Type
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('bed_type', $bed_type, '', ['class' => 'form-control', 'id' => 'bed_type', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="bathrooms" class="col-sm-3 control-label">Bathrooms
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('bathrooms', $bathrooms, '', ['class' => 'form-control', 'id' => 'bathrooms', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <p class="text-success text-bold">Listing
                </p>
                <div class="form-group">
                  <label for="property_type" class="col-sm-3 control-label">Property Type
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('property_type', $property_type, '', ['class' => 'form-control', 'id' => 'property_type', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="room_type" class="col-sm-3 control-label">Room Type
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('room_type', $room_type, '', ['class' => 'form-control', 'id' => 'room_type', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="accommodates" class="col-sm-3 control-label">Accommodates
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('accommodates', $accommodates, '', ['class' => 'form-control', 'id' => 'accommodates', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf3" class="frm" data-step-name="Description">
              <fieldset class="box-body">
                <div class="form-group" >
                  <label for="language" class="col-sm-3 control-label">Language
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('language[]', $language, 'en', ['class' => 'form-control check go', 'id' => 'language','disabled']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label">Listing Name
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('name[]', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Be clear and descriptive']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="summary" class="col-sm-3 control-label">Summary
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('summary[]', '', ['class' => 'form-control', 'id' => 'summary', 'placeholder' => 'Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood.', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="space" class="col-sm-3 control-label">Space
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('space[]', '', ['class' => 'form-control', 'id' => 'space', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="access" class="col-sm-3 control-label">Guest Access
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('access[]', '', ['class' => 'form-control', 'id' => 'access', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="interaction" class="col-sm-3 control-label">Interaction with Guests
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('interaction[]', '', ['class' => 'form-control', 'id' => 'interaction', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="notes" class="col-sm-3 control-label">Other Things to Note
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('notes[]', '', ['class' => 'form-control', 'id' => 'notes', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="house_rules" class="col-sm-3 control-label">House Rules
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('house_rules[]', '', ['class' => 'form-control', 'id' => 'house_rules', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="neighborhood_overview" class="col-sm-3 control-label">Overview
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('neighborhood_overview[]', '', ['class' => 'form-control', 'id' => 'neighborhood_overview', 'rows' => 5]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="transit" class="col-sm-3 control-label">Getting Around
                  </label>
                  <div class="col-sm-6">
                    {!! Form::textarea('transit[]', '', ['class' => 'form-control', 'id' => 'transit', 'rows' => 5]) !!}
                  </div>
                </div>
                <div ng-repeat="choice in rows">
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="language" class="col-sm-3 control-label">Language
                    </label>
                    <div class="col-sm-6">
                      {!! Form::select('language[]', $language, '', ['class' => 'form-control go', 'id' => 'language@{{ $index }}','placeholder' => 'Select...']) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="name" class="col-sm-3 control-label">Listing Name
                      <em class="text-danger">*
                      </em>
                    </label>
                    <div class="col-sm-6">
                      {!! Form::text('name[]', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Be clear and descriptive']) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="summary" class="col-sm-3 control-label">Summary
                      <em class="text-danger">*
                      </em>
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('summary[]', '', ['class' => 'form-control', 'id' => 'summary', 'placeholder' => 'Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood.', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="space" class="col-sm-3 control-label">Space
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('space[]', '', ['class' => 'form-control', 'id' => 'space', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="access" class="col-sm-3 control-label">Guest Access
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('access[]', '', ['class' => 'form-control', 'id' => 'access', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="interaction" class="col-sm-3 control-label">Interaction with Guests
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('interaction[]', '', ['class' => 'form-control', 'id' => 'interaction', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="notes" class="col-sm-3 control-label">Other Things to Note
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('notes[]', '', ['class' => 'form-control', 'id' => 'notes', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="house_rules" class="col-sm-3 control-label">House Rules
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('house_rules[]', '', ['class' => 'form-control', 'id' => 'house_rules', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="neighborhood_overview" class="col-sm-3 control-label">Overview
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('neighborhood_overview[]', '', ['class' => 'form-control', 'id' => 'neighborhood_overview', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <div class="form-group" data-index="@{{ $index }}">
                    <label for="transit" class="col-sm-3 control-label">Getting Around
                    </label>
                    <div class="col-sm-6">
                      {!! Form::textarea('transit[]', '', ['class' => 'form-control', 'id' => 'transit', 'rows' => 5]) !!}
                    </div>
                  </div>
                  <a class="pull-right" href="javascript:void(0);" ng-click="removeRow($index)">
                    <span style="color:red;"> Remove
                    </span>
                  </a>
                  <br>
                </div>
                <a class="pull-right"  href="javascript:void(0);" ng-click="addNewRow()"> Add
                </a>
              </fieldset>
              <!-- change end -->
            </div>
            <div id="sf4" class="frm" data-step-name="Location">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="country" class="col-sm-3 control-label">Country
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('country', $country, '', ['class' => 'form-control', 'id' => 'country', 'placeholder' => 'Select...']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="address_line_1" class="col-sm-3 control-label">Address Line 1
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('address_line_1', '', ['class' => 'form-control', 'id' => 'address_line_1', 'placeholder' => 'House name/number + street/road', 'autocomplete' => 'off']) !!}
                  </div>
                </div>  
                <div class="form-group">
                  <label for="address_line_2" class="col-sm-3 control-label">Address Line 2
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('address_line_2', '', ['class' => 'form-control', 'id' => 'address_line_2', 'placeholder' => 'Apt., suite, building access code']) !!}
                  </div>
                </div>    
                <div class="form-group">
                  <label for="city" class="col-sm-3 control-label">City / Town / District
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('city', '', ['class' => 'form-control', 'id' => 'city', 'placeholder' => '']) !!}
                  </div>
                </div>     
                <div class="form-group">
                  <label for="state" class="col-sm-3 control-label">State / Province / County / Region
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('state', '', ['class' => 'form-control', 'id' => 'state', 'placeholder' => '']) !!}
                  </div>
                </div>     
                <div class="form-group">
                  <label for="postal_code" class="col-sm-3 control-label">ZIP / Postal Code
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('postal_code', '', ['class' => 'form-control', 'id' => 'postal_code', 'placeholder' => '']) !!}
                  </div>
                </div>  
                <input type="hidden" name="latitude" id="latitude" class="do-not-ignore">         
                <input type="hidden" name="longitude" id="longitude">         
              </fieldset>
            </div>
            <div id="sf5" class="frm" data-step-name="Amenities">
              <fieldset class="box-body">
                <ul class="list-unstyled col-xs-12 col-sm-12 col-md-12" id="triple" style="width:100% !important;">
                  @foreach($amenities as $row)
                  <li class="col-xs-12 col-sm-4 col-md-4">
                    <label class="label-large label-inline amenity-label pull-left" style="width:100% !important;">
                      <input class="pull-left" type="checkbox" value="{{ $row->id }}" name="amenities[]">
                      <span class="pull-left" style="margin-left:8px;width:85%;white-space:normal;"> {{ $row->name }}
                      </span>
                    </label>
                  </li>
                  @endforeach
                </ul>
              </fieldset>
            </div>
            <div id="sf6" class="frm" data-step-name="Photos">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="night" class="col-sm-3 control-label">Photos
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    <input type="file" name="photos[]" multiple="true" id="upload_photos" >
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf7" class="frm" data-step-name="Video">
              <fieldset class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">YouTube URL
                  </label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="video" id="video">
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf8" class="frm" data-step-name="Pricing">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="night" class="col-sm-3 control-label">Night
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('night', '', ['class' => 'form-control', 'id' => 'night', 'placeholder' => '']) !!}
                    <span id="price_wrong_message" class="text-danger">
                    </span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="currency_code" class="col-sm-3 control-label">Currency Code
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('currency_code', $currency, '', ['class' => 'form-control', 'id' => 'currency_code']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="cleaning" class="col-sm-3 control-label">Cleaning
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('cleaning', '', ['class' => 'form-control', 'id' => 'cleaning', 'placeholder' => '']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="additional_guest" class="col-sm-3 control-label">Additional Guest
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('additional_guest', '', ['class' => 'form-control', 'id' => 'additional_guest', 'placeholder' => '']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="guests" class="col-sm-3 control-label">Guests
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('guests', $accommodates, 1, ['class' => 'form-control', 'id' => 'guests']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="security" class="col-sm-3 control-label">Security
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('security', '', ['class' => 'form-control', 'id' => 'security', 'placeholder' => '']) !!}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="weekend" class="col-sm-3 control-label">Weekend
                  </label>
                  <div class="col-sm-6">
                    {!! Form::text('weekend', '', ['class' => 'form-control', 'id' => 'weekend', 'placeholder' => '']) !!}
                  </div>
                </div> 
              </fieldset>
            </div>
            <div id="sf9" class="frm" data-step-name="Booking Type">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="booking_type" class="col-sm-3 control-label">Booking Type
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('booking_type', ['request_to_book'=>'Request To Book', 'instant_book'=>'Instant Book'], 'request_to_book', ['class' => 'form-control', 'id' => 'booking_type']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf10" class="frm" data-step-name="Terms">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="cancel_policy" class="col-sm-3 control-label">Cancellation Policy
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('cancel_policy', ['Flexible'=>'Flexible', 'Moderate'=>'Moderate','Strict'=>'Strict'], '', ['class' => 'form-control', 'id' => 'cancel_policy', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf11" class="frm" data-step-name="User">
              <fieldset class="box-body">
                <div class="form-group">
                  <label for="user_id" class="col-sm-3 control-label">Username
                    <em class="text-danger">*
                    </em>
                  </label>
                  <div class="col-sm-6">
                    {!! Form::select('user_id', $users_list, '', ['class' => 'form-control', 'id' => 'user_id', 'placeholder' => 'Select...']) !!}
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf12" class="frm" data-step-name="Price Rules">
              <fieldset class="box-body">
                <div class="row price_rules">
                  <div class="col-md-8 col-md-offset-2" ng-init="length_of_stay_items = [];">
                    <div class="panel panel-info" ng-init="length_of_stay_options= {{json_encode($length_of_stay_options)}}">
                      <div class="panel-header">
                        <h4>
                          Length of Stay discounts
                        </h4>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12 row-space-top-1" ng-repeat="item in length_of_stay_items">
                            <div class="row">
                              <input type="hidden" name="length_of_stay[@{{$index}}][id]" value="@{{item.id}}">
                              <div class="col-md-4">
                                <select name="length_of_stay[@{{$index}}][period]" class="form-control" id="length_of_stay_period_@{{$index}}" ng-model="length_of_stay_items[$index].period">
                                  <option disabled>
                                    Select nights
                                  </option>
                                  <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights) || option.nights == item.period" ng-selected="item.period == option.nights" value="@{{option.nights}}">
                                    @{{option.text}}
                                  </option>
                                </select>
                              </div>
                              <div class="col-md-4" id="discount_error_container_@{{$index}}">
                                <div class="input-addon">
                                  <input type="text" name="length_of_stay[@{{$index}}][discount]" class="form-control discount" id="length_of_stay_discount_@{{$index}}" ng-model="length_of_stay_items[$index].discount" placeholder="Percentage of discount" data-error-placement="container" data-error-container="#discount_error_container_@{{$index}}">
                                  <span class="input-suffix">
                                    %
                                  </span>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" ng-click="remove_price_rule('length_of_stay', $index)">
                                  <span class="fa fa-trash">
                                  </span>
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12" ng-init="length_of_stay_period_select = ''" ng-show="length_of_stay_items.length < length_of_stay_options.length">
                            <div class="row">
                              <div class="col-md-4" >
                                <select name="" class="form-control" id="length_of_stay_period_select" ng-model="length_of_stay_period_select" ng-change="add_price_rule('length_of_stay')">
                                  <option value="">
                                    Select nights
                                  </option>
                                  <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights)" value="@{{option.nights}}">
                                    @{{option.text}}
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 col-md-offset-2" ng-init="early_bird_items = {{json_encode([])}};">
                    <div class="panel panel-info">
                      <div class="panel-header">
                        <h4>
                          Early Bird Discounts
                        </h4>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12 row-space-top-1" ng-repeat="item in early_bird_items">
                            <div class="row">
                              <input type="hidden" name="early_bird[@{{$index}}][id]" value="@{{item.id}}">
                              <div class="col-md-4" id="eb_period_error_container_@{{$index}}">
                                <div class="input-addon">
                                  <input type="text" name="early_bird[@{{$index}}][period]" class="form-control early_bird_period" id="early_bird_period_@{{$index}}" ng-model="early_bird_items[$index].period" placeholder="Number of days" data-error-placement="container" data-error-container="#eb_period_error_container_@{{$index}}">
                                  <span class="input-suffix">
                                    Days
                                  </span>
                                </div>
                              </div>
                              <div class="col-md-4" id="eb_discount_error_container_@{{$index}}">
                                <div class="input-addon">
                                  <input type="text" name="early_bird[@{{$index}}][discount]" class="form-control discount" id="early_bird_discount_@{{$index}}" ng-model="early_bird_items[$index].discount" placeholder="Percentage of discount" data-error-placement="container" data-error-container="#eb_discount_error_container_@{{$index}}">
                                  <span class="input-suffix">
                                    %
                                  </span>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" ng-click="remove_price_rule('early_bird', $index)">
                                  <span class="fa fa-trash">
                                  </span>
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12 row-space-top-2" >
                            <div class="row">
                              <div class="col-md-4" >
                                <a href="javascript:void(0)" class="btn btn-success btn-sm" ng-click="add_price_rule('early_bird')">
                                  <span class="fa fa-plus">
                                  </span>
                                  Add
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 col-md-offset-2" ng-init="last_min_items = {{json_encode([])}};">
                    <div class="panel panel-info">
                      <div class="panel-header">
                        <h4>
                          Last Min Discounts
                        </h4>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12 row-space-top-1" ng-repeat="item in last_min_items">
                            <div class="row">
                              <input type="hidden" name="last_min[@{{$index}}][id]" value="@{{item.id}}">
                              <div class="col-md-4" id="lm_period_error_container_@{{$index}}">
                                <div class="input-addon">
                                  <input type="text" name="last_min[@{{$index}}][period]" class="form-control last_min_period" id="last_min_period_@{{$index}}" ng-model="last_min_items[$index].period" placeholder="Number of days" data-error-placement="container" data-error-container="#lm_period_error_container_@{{$index}}">
                                  <span class="input-suffix">
                                    Days
                                  </span>
                                </div>
                              </div>
                              <div class="col-md-4" id="lm_discount_error_container_@{{$index}}">
                                <div class="input-addon">
                                  <input type="text" name="last_min[@{{$index}}][discount]" class="form-control discount" id="last_min_discount_@{{$index}}" ng-model="last_min_items[$index].discount" placeholder="Percentage of discount" data-error-placement="container" data-error-container="#lm_discount_error_container_@{{$index}}">
                                  <span class="input-suffix">
                                    %
                                  </span>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" ng-click="remove_price_rule('last_min', $index)">
                                  <span class="fa fa-trash">
                                  </span>
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12 row-space-top-2" >
                            <div class="row">
                              <div class="col-md-4" >
                                <a href="javascript:void(0)" class="btn btn-success btn-sm" ng-click="add_price_rule('last_min')">
                                  <span class="fa fa-plus">
                                  </span>
                                  Add
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div id="sf13" class="frm" data-step-name="Availability Rules">
              <fieldset class="box-body">
                <div class="row availability_rules">
                  <div class="col-md-8 col-md-offset-2" ng-init="availability_rules = {{json_encode([] )}};">
                    <div class="panel panel-info">
                      <div class="panel-header">
                        <h4>
                          Availability Rules
                        </h4>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label class="control-label col-md-4">
                                Minimum Stay
                              </label>
                              <div class="col-md-6" id="minimum_stay_error_container">
                                <div class="input-addon">
                                  <input type="text" value="" name="minimum_stay" class="form-control minimum_stay" id="minimum_stay" placeholder="Minimum Stay" data-error-placement="container" data-error-container="#minimum_stay_error_container" >
                                  <span class="input-suffix">
                                    Nights
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-4">
                                Maximum Stay
                              </label>
                              <div class="col-md-6" id="maximum_stay_error_container">
                                <div class="input-addon">
                                  <input type="text" value="" name="maximum_stay" class="form-control maximum_stay" id="maximum_stay" data-minimum_stay="#minimum_stay" placeholder="Maximum Stay" data-error-placement="container" data-error-container="#maximum_stay_error_container" >
                                  <span class="input-suffix">
                                    Nights
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer" ng-show="!is_custom_exits">
                        <div class="row" ng-init="is_custom_exits = false">
                          <div class="col-md-12 text-center">
                            <a href="javascript:void(0)" class="btn btn-success" ng-click="is_custom_exits = true; add_availability_rule()">
                              Add custom Rule
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-info" ng-show="is_custom_exits">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12 row-space-top-1" ng-repeat="item in availability_rules" ng-init="saved_item = copy_data(item)">
                            <input type="hidden" name="availability_rules[@{{$index}}][id]" value="@{{item.id}}">
                            <input type="hidden" name="availability_rules[@{{$index}}][edit]" value="@{{availability_rules[$index].edit}}">
                            <div class="row" ng-if="item.id" ng-init="availability_rules[$index].edit = item.id != '' ? true : false" ng-show="availability_rules[$index].edit">
                              <div class="col-md-12">
                                <div class="row">
                                  <div class="col-md-8 col-md-offset-2" style="border: 1px solid #cfcfcf; padding: 10px;">
                                    <div class="row" >
                                      <div class="col-md-8">
                                        <p>
                                          During @{{saved_item.during}},
                                        </p>
                                        <p ng-if="saved_item.minimum_stay">
                                          guests stay for minimum @{{saved_item.minimum_stay}} nights
                                        </p>
                                        <p ng-if="saved_item.maximum_stay">
                                          guests stay for maximum @{{saved_item.maximum_stay}} nights
                                        </p>
                                      </div>
                                      <div class="col-md-4">
                                        <a href="javascript:void(0)" class="btn btn-info btn-xs" ng-click="availability_rules[$index].edit = false">
                                          <span class="fa fa-edit">
                                          </span>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-xs" ng-click="remove_availability_rule($index)">
                                          <span class="fa fa-trash">
                                          </span>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row" ng-show="!availability_rules[$index].edit">
                              <div class="form-group">
                                <label class="control-label col-md-4">
                                  Select Dates
                                </label>
                                <div class="col-md-6" ng-init="item.type = item.id ? 'prev' : ''">
                                  <select name="availability_rules[@{{$index}}][type]" class="form-control required" id="availability_rules_@{{$index}}_type" ng-model="availability_rules[$index]['type']" ng-click="availability_rules_type_change($index);" >
                                    <option value="" ng-disabled="item.type != ''" ng-if="!item.id">
                                      Select Dates
                                    </option>
                                    <option value="prev" data-start_date="@{{saved_item.start_date}}" data-end_date="@{{saved_item.end_date}}" ng-if="item.id">
                                      @{{item.during}}
                                    </option>
                                    @foreach($availability_rules_months_options as $date => $option)
                                    <option value="month" data-start_date="{{$option['start_date']}}" data-end_date="{{$option['end_date']}}">
                                      {{$option['text']}}
                                    </option>
                                    @endforeach
                                    <option value="custom">
                                      Custom
                                    </option>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <a href="javascript:void(0)" class="btn btn-danger btn-xs" ng-click="remove_availability_rule($index)">
                                    <span class="fa fa-trash">
                                    </span>
                                  </a>
                                  <br>
                                  <a href="javascript:void(0)" class="btn btn-info btn-xs" ng-click="availability_rules[$index].edit = true" ng-if="item.id != '' && item.id ">
                                    <span class="fa fa-times">
                                    </span>
                                  </a>
                                </div>
                              </div>
                              <div class="form-group" ng-show="availability_rules[$index]['type'] == 'custom'">
                                <label class="col-md-4 control-label">
                                  Custom Dates
                                </label>
                                <div class="col-md-3">
                                  <input type="text" readonly name="availability_rules[@{{$index}}][start_date]" class="form-control required" id="availability_rules_@{{$index}}_start_date" placeholder="Start Date" ng-model="availability_rules[$index]['start_date']" >
                                </div>
                                <div class="col-md-3">
                                  <input type="text" readonly name="availability_rules[@{{$index}}][end_date]" class="form-control required" id="availability_rules_@{{$index}}_end_date" placeholder="End Date" ng-model="availability_rules[$index]['end_date']" >
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-4">
                                  Minimum Stay
                                </label>
                                <div class="col-md-6" id="availability_minimum_stay_error_container_@{{$index}}">
                                  <div class="input-addon">
                                    <input type="text" name="availability_rules[@{{$index}}][minimum_stay]" class="form-control availability_minimum_stay" id="availability_rules_@{{$index}}_minimum_stay" placeholder="Minimum Stay" ng-model="availability_rules[$index]['minimum_stay']" data-error-placement="container" data-error-container="#availability_minimum_stay_error_container_@{{$index}}" >
                                    <span class="input-suffix">
                                      Nights
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-4">
                                  Maximum Stay
                                </label>
                                <div class="col-md-6" id="availability_maximum_stay_error_container_@{{$index}}">
                                  <div class="input-addon">
                                    <input type="text" name="availability_rules[@{{$index}}][maximum_stay]" class="form-control availability_maximum_stay" id="availability_rules_@{{$index}}_maximum_stay" data-minimum_stay="#availability_rules_@{{$index}}_minimum_stay" placeholder="Maximum Stay" ng-model="availability_rules[$index]['maximum_stay']" data-error-placement="container" data-error-container="#availability_maximum_stay_error_container_@{{$index}}" >
                                    <span class="input-suffix">
                                      Nights
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <legend ng-if="$index+1 < availability_rules.length" class="row-space-top-2">
                            </legend>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <div class="row">
                          <div class="col-sm-12 text-center" >
                            <a href="javascript:void(0)" class="btn btn-success btn-sm" ng-click="add_availability_rule()">
                              <span class="fa fa-plus">
                              </span>
                              Add
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer" ng-cloak>
            <button ng-show="step > 0" class="btn btn-warning back2" type="button" ng-click="back_step(step)"><span class="fa fa-arrow-left"></span> Back</button>
            <button class="btn btn-primary open2 pull-right" type="button" ng-click="next_step(step);" >
              <span ng-if="step_name == 'User'">Submit
              </span> 
              <span ng-if="step_name != 'User'  ">Next
              </span>
              <span class="fa fa-arrow-right" ng-if="step_name != 'User'">
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
<!-- hide for admin side room_add page settings/skins problems -->
@stop
@push('scripts')
<style type="text/css">
  .price_rules input, .availability_rules input {
    margin-bottom: 0px;
  }
  .input-suffix {
    padding: 6px 10px;
  }
</style>
@endpush