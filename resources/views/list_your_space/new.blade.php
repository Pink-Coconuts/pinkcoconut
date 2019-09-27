@extends('template')

@section('main')

<main id="site-content" role="main" ng-controller="rooms_new">
      
<div class="page-container-full" ng-init="country_list = {{json_encode($country_list)}}">


  <div class="panel lys-panel-header">
    <div class="panel-light become_new">

      
      
          <div class="becom_head">
            <h1 class="h2">{{ trans('messages.header.list_your_space') }}</h1>
            <p class="text-lead">
              {{ $site_name }} {{ trans('messages.lys.make_money_renting') }}
            </p>
          </div>
        
     
    </div>

    <div class="panel-body panel-medium back-change">
      <div class="page-container-responsive">
          <div class="row">
  <div class="col-lg-12 col-md-12 list-space">
    <div class="become_body">
<div id="alert-row" class="">
  <div id="alert-status" class="col-lg-10 col-md-11 lys-alert"></div>
</div>

{!! Form::open(['url' => 'rooms/create', 'class' => 'host-onboarding-form', 'accept-charset' => 'UTF-8' , 'name' => 'lys_new']) !!}

  {!! Form::hidden('hosting[property_type_id]', '', ['id' => 'property_type_id', 'ng-model' => 'property_type_id', 'required' => 'required', 'ng-value' => 'property_type_id']) !!}

  {!! Form::hidden('hosting[person_capacity]', '', ['id' => 'person_capacity', 'ng-model' => 'selected_accommodates', 'required' => 'required', 'ng-value' => 'selected_accommodates']) !!}

  {!! Form::hidden('hosting[room_type]', '', ['id' => 'room_type', 'ng-model' => 'room_type_id', 'required' => 'required', 'ng-value' => 'room_type_id']) !!}

  {!! Form::hidden('hosting[address]', '', ['id' => 'address', 'ng-model' => 'address', 'ng-value' => 'address']) !!}

  {!! Form::hidden('hosting[street_number]', '', ['id' => 'street_number', 'ng-model' => 'street_number', 'ng-value' => 'street_number']) !!}
  {!! Form::hidden('hosting[route]', '', ['id' => 'route', 'ng-model' => 'route', 'ng-value' => 'route']) !!}
  {!! Form::hidden('hosting[postal_code]', '', ['id' => 'postal_code', 'ng-model' => 'postal_code', 'ng-value' => 'postal_code']) !!}
  
  {!! Form::hidden('hosting[city]', '', ['id' => 'city', 'ng-model' => 'city', 'ng-value' => 'city']) !!}
  {!! Form::hidden('hosting[state]', '', ['id' => 'state', 'ng-model' => 'state', 'ng-value' => 'state']) !!}
  {!! Form::hidden('hosting[country]', '', ['id' => 'country', 'ng-model' => 'country', 'ng-value' => 'country']) !!}
  {!! Form::hidden('hosting[latitude]', '', ['id' => 'latitude', 'ng-model' => 'latitude', 'ng-value' => 'latitude']) !!}
  {!! Form::hidden('hosting[longitude]', '', ['id' => 'longitude', 'ng-model' => 'longitude', 'ng-value' => 'longitude']) !!}
    

  <div class="space-top-4 space-1">
    <div id="property-type-id-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.home_type') }}
      </strong>
    </div>
  </div>
  <div class="fieldset fieldset_property_type_id">
    <div class="btn-group" ng-hide="property_type_id">
    @for($i = 0; $i < 3; $i++)
      @if(isset($property_type[$i]))
      <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $property_type[$i]->name }}" data-type-id="{{ $property_type[$i]->id }}" data-icon-class="icon-apartment" data-behavior="tooltip" type="button" data-position="bottom" aria-label="Your space is an apartment, flat, or unit in a multi-unit building." ng-click="property_type('{{ $property_type[$i]->id }}', '{{ $property_type[$i]->name }}', '{{$property_type[$i]->image_name}}')">
        <img src="{{$property_type[$i]->image_name}}">
        {{ $property_type[$i]->name }}
      </button>
      @endif
    @endfor
  <div class="select select-large other-select" id="property-select">
    <select class="alert-highlighted-element hover-select-highlight" id="property_type_dropdown" ng-model="change_property_value" ng-change="property_change(change_property_value)">
      <option disabled="" selected="" value="">{{ trans('messages.profile.other') }}</option>
      @php $i = 0 @endphp
        @foreach($property_type as $row)
          @if($i > 2)
          <option data-icon-class="{{$property_type[$i]->image_name}}" data-name="{{ $row->name }}" data-type-id="{{ $row->id }}" value="{{ $row->id }}">
          {{ $row->name }}
          </option>
          @endif
          {{ $i++ }}
        @endforeach
    </select>
  </div>
</div>

<div class="active-selection" ng-show="property_type_id"  style="display:none;">
  <div data-type="property_type_id" class="selected-item property_type_id">

    <div class="active-panel" ng-click="property_type_rm()">
      <div class="active-title active-col">
        <div class="h4 title-value">
         <img src="@{{property_type_icon}}">
          @{{ selected_property_type }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ $site_name }} {{ trans('messages.lys.home_type_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>
  <div class="space-top-3 space-1" ng-init="is_shared == 'No'">
    <div id="room-type-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.room_type') }}
      </strong>
    </div>
  </div>
  <div class="fieldset fieldset_room_type">
    <div class="btn-group" ng-hide="room_type_id">
    @for($i = 0; $i < 3; $i++)
      @if(isset($room_type[$i]))
          <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $room_type[$i]->name }}" data-slug="entire_home_apt" data-type="{{ $room_type[$i]->name }}" data-type-id="{{ $room_type[$i]->id }}" data-icon-class="icon-entire-place" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You&#39;re renting out an entire home." ng-click="room_type('{{ $room_type[$i]->id }}', '{{ $room_type[$i]->name }}','{{ $room_type[$i]->image_name }}','{{$room_type[$i]->is_shared}}')">
           <img src="{{ $room_type[$i]->image_name }}"/>
            {{ $room_type[$i]->name }}
          </button>
      @endif
    @endfor

    <div class="select select-large other-select" id="room-select">
    <select class="alert-highlighted-element hover-select-highlight" id="room_type_dropdown" ng-model="change_room_value" ng-change="room_change(change_room_value)">
      <option disabled="" selected="" value="">{{ trans('messages.profile.other') }}</option>
      @php $i = 0 @endphp
        @foreach($room_type as $row)
          @if($i > 2)
          <option data-icon-class="{{$room_type[$i]->image_name}}" data-name="{{ $row->name }}" data-type-id="{{ $row->id }}" data-is_shared="{{$row->is_shared}}" value="{{ $row->id }}">
          {{ $row->name }}
          </option>
          @endif
          {{ $i++ }}
        @endforeach
    </select>
  </div>
</div>

<div class="active-selection" ng-show="room_type_id"  style="display:none;">
  <div data-type="room_type_id" class="selected-item room_type_id">

    <div class="active-panel" ng-click="room_type_rm()">
      <div class="active-title active-col">
        <div class="h4 title-value">
        <img src="@{{ room_type_icon }}">
          @{{ selected_room_type }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.room_type_desc',['site_name'=>$site_name]) }}
      </div>
    </div>
    <em class="text-beach" ng-show="is_shared == 'Yes'">{{trans('messages.shared_rooms.shared_room_notes')}}</em>
  </div>
</div>

  </div>
  <div class="space-top-3 space-1">
    <div id="person-capacity-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.accommodates') }}
      </strong>
    </div>
  </div>
  <div class="fieldset fieldset_person_capacity wmpw-spacing">
<div class="unselected row row-condensed" ng-hide="selected_accommodates">
  <div class="col-sm-3">
    <div class="panel accommodates-panel">
      <div class="panel-body panel-light alert-highlighted-element hover-select-highlight" style="overflow:hidden;">
        
  
          <div class="col-sm-12" style="padding:0px !important;">
            <div class="select select-large" style="width:100%;">
             <i class="icon icon-group h4 icon-kazan va-middle icons-accommodates"></i>

              <select id="accomodates-select"  style="width:100% !important;" class="hover-select-highlight" ng-model="accommodates_value" ng-change="change_accommodates(accommodates_value)">
                @for($i=1;$i<=16;$i++)
                  <option class="accommodates" data-accommodates="{{ ($i == '16') ? $i.'+' : $i }}" value="{{ ($i == '16') ? $i.'+' : $i }}">
                    {{ $i }}
                  </option>
                @endfor
              </select>
            </div>
          </div>
       
      </div>
    </div>
  </div>
</div>

<div class="active-selection" ng-show="selected_accommodates" ng-click="accommodates_rm()" style="display:none;">
  <div data-type="person_capacity" class="selected-item person_capacity">
    <div class="active-panel">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 icon-group"></i>
          @{{ selected_accommodates }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.accommodates_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>
  <div class="space-top-3 space-1">
    <div id="city-header" class="h5 text-light">
      <strong>
              {{ trans('messages.account.city') }}
      </strong>
    </div>
  </div>

  <div class="fieldset fieldset_city">
    
<div class="row" ng-hide="city_show">
  <div class="col-sm-12">
    <div class="panel location lys-location alert-highlighted-element">
      <div class="panel-body panel-light panel-border location-panel-body clearfix">
        <div class="col location-input-frame" style="padding:0px !important;">
          <i class="icon icon icon-map-marker icon-kazan h4 pull-left"></i>
          <input class="pull-left alert-highlighted-element geocomplete" name="location_input" type="text" value="" placeholder="{{ trans('messages.header.enter_a_location') }}" autocomplete="off" ng-click="city_click()" ng-model="address" id="location_input">
        </div>
        <p class="text-danger hide" id="location_country_error_message">{{trans('messages.lys.service_not_available_country')}}</p>
      </div>
    </div>
  </div>
</div>

<div class="active-selection" ng-show="city_show" style="display:none;">
  <div class="selected-item city" data-type="city">
    <div class="active-panel" ng-click="city_rm()">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 icon-map-marker"></i>
          @{{ address }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.city_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>

  <div class="text-left space-top-3">
    <div class="" id="js-submit-button"><div class="lys-continue-button-wrapper col-3">
  <button type="submit" class="btn btn-primary btn-large btn-block submit" ng-disabled="((city_show == false) || lys_new.$invalid ) || submitDisable" ng-click="disableButton()">
    {{ trans('messages.lys.continue') }}
  </button>
</div>
</div>
  </div>

  <div id="cohosting-signup-widget-banner" class="hide-sm hide-md"></div>
{!! Form::close() !!}
  </div>
</div>
</div>
          <div class="space-5 row"> </div>
      </div>
    </div>

      <div class="panel-medium back-change">
  <div class="page-container-responsive col-center">
    <div class="row space-5">
      <div class="col-md-4 text-center hand-icn">
        <i class="icon icon-handshake icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.trust_safety') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.trust_safety_desc') }}
        </div>
      </div>
      <div class="col-md-4 text-center hand-icn">
        <i class="icon icon-host-guarantee icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.host_guarantee') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.host_guarantee_desc1') }} <a href="{{ url('host_guarantee') }}" target="_blank">{{ trans('messages.lys.eligible') }}</a> {{ trans('messages.lys.host_guarantee_desc2',['site_name'=>$site_name]) }}
        </div>
      </div>
      <div class="col-md-4 text-center hand-icn">
        <i class="icon icon-lock icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.secure_payments') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.secure_payments_desc') }}
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
</div>
    </main>
 @stop