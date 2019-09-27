@extends('admin.template')
@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-controller="rooms_admin">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Room
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="../dashboard">
                    <i class="fa fa-dashboard">
                    </i>
                    Home
                </a>
            </li>
            <li>
                <a href="../rooms">
                    Rooms
                </a>
            </li>
            <li class="active">
                Edit
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
                        <h3 class="box-title">
                            Edit Room Form
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-header with-border">
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_1" ng-click="go_to_edit_step(1)" disabled>
                            Calendar
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_2" ng-click="go_to_edit_step(2)">
                            Basics
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_3" ng-click="go_to_edit_step(3)">
                            Description
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_4" ng-click="go_to_edit_step(4)">
                            Location
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_5" ng-click="go_to_edit_step(5)">
                            Amenities
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_6" ng-click="go_to_edit_step(6)">
                            Photos
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_7" ng-click="go_to_edit_step(7)">
                            Video
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_8" ng-click="go_to_edit_step(8)">
                            Pricing
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_11" ng-click="go_to_edit_step(11)">
                            Price Rules
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_12" ng-click="go_to_edit_step(12)">
                            Availability Rules
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_9" ng-click="go_to_edit_step(9)">
                            Booking Type
                        </a>
                        <a href="javascript:void(0);" class="btn btn-warning tab_btn" id="tab_btn_10" ng-click="go_to_edit_step(10)">
                            Terms
                        </a>
                    </div>
                    {!! Form::open(['url' => ADMIN_URL.'/edit_room/'.$room_id, 'class' => 'form-horizontal', 'id' => 'add_room_form', 'files' => true]) !!}   
                    <input type="hidden" value="{{ $room_id }}" name="room_id" id="room_id">
                    <div id="sf1" class="frm">
                        <fieldset class="box-body">
                            <div id="ajax_container" class="iccon">
                                {!! $calendar !!}
                            </div>
                        </fieldset>
                    </div>
                    <div id="sf2" class="frm">
                        <p class="text-danger">
                            (*)Fields are Mandatory
                        </p>
                        <fieldset class="box-body">
                            <p class="text-success text-bold">
                                Rooms and Beds
                            </p>
                            <div class="form-group">
                                <label for="bedrooms" class="col-sm-3 control-label">
                                    Bedrooms
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('bedrooms', $bedrooms, $result->bedrooms, ['class' => 'form-control', 'id' => 'bedrooms', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="beds" class="col-sm-3 control-label">
                                    Beds
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('beds', $beds, $result->beds, ['class' => 'form-control', 'id' => 'beds', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bed_type" class="col-sm-3 control-label">
                                    Bed Type
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('bed_type', $bed_type, $result->bed_type, ['class' => 'form-control', 'id' => 'bed_type', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bathrooms" class="col-sm-3 control-label">
                                    Bathrooms
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('bathrooms', $bathrooms, $result->bathrooms, ['class' => 'form-control', 'id' => 'bathrooms', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <p class="text-success text-bold">
                                Listing
                            </p>
                            <div class="form-group">
                                <label for="property_type" class="col-sm-3 control-label">
                                    Property Type
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('property_type', $property_type, $result->property_type, ['class' => 'form-control', 'id' => 'property_type', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="room_type" class="col-sm-3 control-label">
                                    Room Type
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('room_type', $room_type, $result->room_type, ['class' => 'form-control', 'id' => 'room_type', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accommodates" class="col-sm-3 control-label">
                                    Accommodates
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('accommodates', $accommodates, $result->accommodates, ['class' => 'form-control', 'id' => 'accommodates', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="basics">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf3" class="frm">
                        <p class="text-danger">
                            (*)Fields are Mandatory
                        </p>
                        <fieldset class="box-body">
                            <!--change-->
                            <div class="form-group" >
                                <label for="language" class="col-sm-3 control-label">
                                    Language
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('language', $language, 'en', ['class' => 'form-control go', 'id' => 'language','disabled']) !!}
                                </div>
                            </div>
                            <!--end change-->
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">
                                    Listing Name
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('name[]', $result->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Be clear and descriptive']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="summary" class="col-sm-3 control-label">
                                    Summary
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('summary[]', $result->summary, ['class' => 'form-control', 'id' => 'summary', 'placeholder' => 'Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood.', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="space" class="col-sm-3 control-label">
                                    Space
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('space[]', $result->rooms_description->space, ['class' => 'form-control', 'id' => 'space', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="access" class="col-sm-3 control-label">
                                    Guest Access
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('access[]', $result->rooms_description->access, ['class' => 'form-control', 'id' => 'access', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="interaction" class="col-sm-3 control-label">
                                    Interaction with Guests
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('interaction[]', $result->rooms_description->interaction, ['class' => 'form-control', 'id' => 'interaction', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes" class="col-sm-3 control-label">
                                    Other Things to Note
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('notes[]', $result->rooms_description->notes, ['class' => 'form-control', 'id' => 'notes', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="house_rules" class="col-sm-3 control-label">
                                    House Rules
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('house_rules[]', $result->rooms_description->house_rules, ['class' => 'form-control', 'id' => 'house_rules', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="neighborhood_overview" class="col-sm-3 control-label">
                                    Overview
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('neighborhood_overview[]', $result->rooms_description->neighborhood_overview, ['class' => 'form-control', 'id' => 'neighborhood_overview', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="transit" class="col-sm-3 control-label">
                                    Getting Around
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('transit[]', $result->rooms_description->transit, ['class' => 'form-control', 'id' => 'transit', 'rows' => 5]) !!}
                                </div>
                            </div>
                            <!--change-->
                            <div ng-repeat="choice_check in rows">
                                <div class="form-group" >
                                    <label for="language" class="col-sm-3 control-label">
                                        Language
                                    </label>
                                    <div class="col-sm-6">
                                        <select class="go" ng-model="choice_check.language" name="language[]" id="language@{{ $index }}" data-index="@{{ $index }}">
                                            <option value="">
                                                Select
                                            </option>
                                            <option ng-repeat="item in lang_list" value="@{{ item.value }}" ng-selected="item.value == choice_check.lang_code" >
                                                @{{ item.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">
                                        Listing Name
                                        <em class="text-danger">
                                            *
                                        </em>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"  class="form-control" id="name" name="name[]" ng-model="choice_check.name" placeholder="Be clear and descriptive" data-index="@{{ $index }}" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="summary" class="col-sm-3 control-label">
                                        Summary
                                        <em class="text-danger">
                                            *
                                        </em>
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="summary[]"  class="form-control" id="summary" placeholder="Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood." rows="5" ng-model="choice_check.summary" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="space" class="col-sm-3 control-label">
                                        Space
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="space[]"  class="form-control" id="space" rows="5" ng-model="choice_check.space" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="access" class="col-sm-3 control-label">
                                        Guest Access
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="access[]"  class="form-control" id="space" rows="5" ng-model="choice_check.access" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="interaction" class="col-sm-3 control-label">
                                        Interaction with Guests
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="interaction[]"  class="form-control" id="interaction" rows="5" ng-model="choice_check.interaction" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="notes" class="col-sm-3 control-label">
                                        Other Things to Note
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="notes[]"  class="form-control" id="notes" rows="5" ng-model="choice_check.notes" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="house_rules" class="col-sm-3 control-label">
                                        House Rules
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="house_rules[]"  class="form-control" id="house_rules" rows="5" ng-model="choice_check.house_rules" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="neighborhood_overview" class="col-sm-3 control-label">
                                        Overview
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="neighborhood_overview[]"  class="form-control" id="neighborhood_overview" rows="5" ng-model="choice_check.neighborhood_overview" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="transit" class="col-sm-3 control-label">
                                        Getting Around
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="transit[]"  class="form-control" id="transit" rows="5" ng-model="choice_check.transit" data-index="@{{ $index }}">
                                        </textarea>
                                    </div>
                                </div>
                                <a class="pull-right" href="javascript:void(0);" ng-click="removeRow($index)">
                                    <span class="text-danger">
                                        Remove
                                    </span>
                                </a>
                                <br>
                            </div>
                            <a class="pull-right"  href="javascript:void(0);" ng-click="addNewRow()">
                                Add
                            </a>
                            <!--end change-->
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="description">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf4" class="frm">
                        <p class="text-danger">
                            (*)Fields are Mandatory
                        </p>
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="country" class="col-sm-3 control-label">
                                    Country
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('country', $country, $result->rooms_address->country, ['class' => 'form-control', 'id' => 'country', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_line_1" class="col-sm-3 control-label">
                                    Address Line 1
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('address_line_1', $result->rooms_address->address_line_1, ['class' => 'form-control', 'id' => 'address_line_1', 'placeholder' => 'House name/number + street/road', 'autocomplete' => 'off']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_line_2" class="col-sm-3 control-label">
                                    Address Line 2
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('address_line_2', $result->rooms_address->address_line_2, ['class' => 'form-control', 'id' => 'address_line_2', 'placeholder' => 'Apt., suite, building access code']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-3 control-label">
                                    City / Town / District
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('city', $result->rooms_address->city, ['class' => 'form-control', 'id' => 'city', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-sm-3 control-label">
                                    State / Province / County / Region
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('state', $result->rooms_address->state, ['class' => 'form-control', 'id' => 'state', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="postal_code" class="col-sm-3 control-label">
                                    ZIP / Postal Code
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('postal_code', $result->rooms_address->postal_code, ['class' => 'form-control', 'id' => 'postal_code', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" class="do-not-ignore">
                            <input type="hidden" name="longitude" id="longitude">
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel">
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="location">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf5" class="frm">
                        <fieldset class="box-body">
                            @foreach($amenities as $amenity_type)
                                <h4>{{$amenity_type[0]->amenities_type->name}}</h4>
                                <ul class="list-unstyled" id="triple">
                                    @foreach($amenity_type as $row)
                                        <li>
                                            <label class="label-large label-inline amenity-label pull-left" style="width:100% !important;">
                                                <input class="pull-left" type="checkbox" value="{{ $row->id }}" name="amenities[]" {{ in_array($row->id, $prev_amenities) ? 'checked' : '' }}>
                                                <span class="pull-left" style="margin-left:8px;width:85%;white-space:normal;">
                                                    {{ $row->name }}
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="amenities">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf6" class="frm">
                        <p class="text-danger">
                            (*)Fields are Mandatory
                        </p>
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="night" class="col-sm-3 control-label">
                                    Photos
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    <input type="file" name="photos[]" multiple="true" id="upload_photos" >
                                </div>
                                <span class="text-success text-bold" style="display:none;" id="saved_message">
                                    Saved..
                                </span>
                            </div>
                            <ul class="row list-unstyled sortable" id="js-photo-grid">
                                @foreach($rooms_photos as $row)
                                <li style="display: list-item;width:200px;" id="photo_li_{{ $row->id }}" class="col-4 row-space-4 ng-scope">
                                    <div class="panel photo-item">
                                        <div class="first-photo-ribbon" style="z-index:9; background:none; margin:0; padding:0; " >
                                            <input type="radio" @if($row->featured == 'Yes') checked @endif name="featured_image" class="featured-photo-btn" data-featured-id="{{ $row->id }}">
                                        </div>
                                        <div id="photo-5" class="photo-size photo-drag-target js-photo-link">
                                        </div>
                                        <a href="#" class="media-photo media-photo-block text-center photo-size">
                                            <img alt="" class="img-responsive-height" src="{{ $row->name }}">
                                        </a>
                                        <button class="delete-photo-btn overlay-btn js-delete-photo-btn" data-photo-id="{{ $row->id }}" type="button">
                                            <i class="fa fa-trash" style="color:white;">
                                            </i>
                                        </button>
                                        <div class="panel-body panel-condensed">
                                            <textarea tabindex="1" class="input-large highlights ng-pristine ng-untouched ng-valid" data-photo-id="{{ $row->id }}" placeholder="What are the highlights of this photo?" rows="3" name="5">{{ $row->highlights }}
                                            </textarea>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="photos">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf7" class="frm">
                        <div class="js-saving-progress saving-progress" style="display: none;">
                            <h5>
                                {{ trans('messages.lys.saving') }}...
                            </h5>
                        </div>
                        <div class="js-saving-progress icon-rausch error-value-required row-space-top-1" id="video_error" style="display: none;float:right">
                            <h5>
                                {{ trans('messages.lys.video_error_msg') }}
                            </h5>
                        </div>
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="video" class="col-sm-3 control-label">
                                    YouTube URL
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('video', $result->video, ['class' => 'form-control', 'id' => 'video']) !!}
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-8 @if($result->video == '') hide @endif">
                                    <iframe src="{{$result->video}}?showinfo=0" style="width:100%; height:250px;" id="rooms_video_preview"   allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen">
                                    </iframe>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="video">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf8" class="frm">
                        <p class="text-danger">
                            (*)Fields are Mandatory
                        </p>
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="night" class="col-sm-3 control-label">
                                    Night
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('night', $result->rooms_price->original_night, ['class' => 'form-control', 'id' => 'night', 'placeholder' => '']) !!}
                                    <span id="price_wrong_message" class="text-danger">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="currency_code" class="col-sm-3 control-label">
                                    Currency Code
                                    <em class="text-danger">
                                        *
                                    </em>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('currency_code', $currency, $result->rooms_price->currency_code, ['class' => 'form-control', 'id' => 'currency_code', 'placeholder' => 'Select...']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cleaning" class="col-sm-3 control-label">
                                    Cleaning
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('cleaning', $result->rooms_price->original_cleaning, ['class' => 'form-control', 'id' => 'cleaning', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="additional_guest" class="col-sm-3 control-label">
                                    Additional Guest Charge
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('additional_guest', $result->rooms_price->original_additional_guest, ['class' => 'form-control', 'id' => 'additional_guest', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group additional_guest_form_group">
                                <label for="guests" class="col-sm-3 control-label">
                                    Additional Guests
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('guests', $accommodates, $result->rooms_price->guests, ['class' => 'form-control', 'id' => 'guests']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="security" class="col-sm-3 control-label">
                                    Security
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('security', $result->rooms_price->original_security, ['class' => 'form-control', 'id' => 'security', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weekend" class="col-sm-3 control-label">
                                    Weekend
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('weekend', $result->rooms_price->original_weekend, ['class' => 'form-control', 'id' => 'weekend', 'placeholder' => '']) !!}
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="pricing">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf9" class="frm">
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="booking_type" class="col-sm-3 control-label">
                                    Booking Type
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('booking_type', ['request_to_book'=>'Request To Book', 'instant_book'=>'Instant Book'], $result->booking_type, ['class' => 'form-control', 'id' => 'booking_type']) !!}
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="booking_type">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf10" class="frm">
                        <fieldset class="box-body">
                            <div class="form-group">
                                <label for="cancel_policy" class="col-sm-3 control-label">
                                    Cancellation Policy
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::select('cancel_policy', ['Flexible'=>'Flexible', 'Moderate'=>'Moderate','Strict'=>'Strict'], $result->cancel_policy, ['class' => 'form-control', 'id' => 'cancel_policy']) !!}
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="terms">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf11" class="frm">
                        <fieldset class="box-body">
                            <div class="row price_rules">
                                <div class="col-md-8 col-md-offset-2 length_of_stay_wrapper" ng-init="length_of_stay_items = {{json_encode($result->length_of_stay_rules)}};">
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
                                <div class="col-md-8 col-md-offset-2 early_bird_wrapper" ng-init="early_bird_items = {{json_encode($result->early_bird_rules->count() ? $result->early_bird_rules : [])}};">
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
                                <div class="col-md-8 col-md-offset-2 last_min_wrapper" ng-init="last_min_items = {{json_encode($result->last_min_rules->count() ? $result->last_min_rules : [])}};">
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
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="price_rules">
                                Submit
                            </button>
                        </div>
                    </div>
                    <div id="sf12" class="frm">
                        <fieldset class="box-body">
                            <div class="row availability_rules">
                                <div class="col-md-8 col-md-offset-2" ng-init="availability_rules = {{json_encode($result->availability_rules->count() ? $result->availability_rules : [] )}};">
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
                                                                <input type="text" value="{{$result->rooms_price->minimum_stay}}" name="minimum_stay" class="form-control minimum_stay" id="minimum_stay" placeholder="Minimum Stay" data-error-placement="container" data-error-container="#minimum_stay_error_container" >
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
                                                                <input type="text" value="{{$result->rooms_price->maximum_stay}}" name="maximum_stay" class="form-control maximum_stay" id="maximum_stay" data-minimum_stay="#minimum_stay" placeholder="Maximum Stay" data-error-placement="container" data-error-container="#maximum_stay_error_container" >
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
                                            <div class="row" ng-init="is_custom_exits = {{$result->availability_rules->count() > 0 ? 'true' : 'false'}}">
                                                <div class="col-md-12 text-center">
                                                    <a href="javascript:void(0)" class="btn btn-success" ng-click="is_custom_exits = true; add_availability_rule();">
                                                        Add custom Rule
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info availability_rules_wrapper" ng-show="is_custom_exits">
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
                                                                    <option value="prev" data-start_date="@{{saved_item.start_date_formatted}}" data-end_date="@{{saved_item.end_date_formatted}}" ng-if="item.id">
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
                                                            <div class="col-md-3" ng-init="availability_rules[$index]['start_date'] = availability_rules[$index]['start_date_formatted']">
                                                                <input type="text" readonly name="availability_rules[@{{$index}}][start_date]" class="form-control required" id="availability_rules_@{{$index}}_start_date" placeholder="Start Date" ng-model="availability_rules[$index]['start_date']" >
                                                            </div>
                                                            <div class="col-md-3" ng-init="availability_rules[$index]['end_date'] = availability_rules[$index]['end_date_formatted']">
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
                        <div class="box-footer">
                            <a href="{{url(ADMIN_URL.'/rooms')}}" class="btn btn-default cancel" >
                                Cancel
                            </a>
                            <button class="btn btn-info pull-right" type="submit" name="submit" value="availability_rules">
                                Submit
                            </button>
                        </div>
                    </div>
                    <!-- /.box-body -->
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
<style type="text/css">
ul.list-unstyled {
    width:100%;
    margin-bottom:20px;
    overflow:hidden;
}
.list-unstyled > li{
    line-height:1.5em;
    float:left;
    display:inline;
}
.price_rules input, .availability_rules input {
    margin-bottom: 0px;
}
.input-suffix {
    padding: 6px 10px;
}
#double li  { width:50%;}
#triple li  { width:33.333%; }
#quad li    { width:25%; }
#six li     { width:16.666%; }
@media (max-width: 760px){
    #triple li{width: 100% !important;}
}
@media (min-width: 765px) and (max-width: 1000px){
    #triple li{width: 50% !important;}
}
@media (min-width: 1280px) and (max-width: 2000px)
{
    .sidebar { position: relative !important; top: 0px !important;}
}
#ajax_container {
    float: none !important; 
}
.btn-warning{margin-bottom: 10px;}
</style>
@endsection 