<div class="form-group status">
  	<label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
  	<div class="col-sm-6">
    	{!! Form::text('name', @$host_experience_city->name, ['class' => 'form-control', 'id' => 'input_name', 'placeholder' => 'Name']) !!}                    
  		<span class="text-danger">{{ $errors->first('name') }}</span>
  	</div>
</div>
<div class="form-group status">
    <label for="input_address" class="col-sm-3 control-label">City Address<em class="text-danger">*</em></label>
    <div class="col-sm-6">
      {!! Form::text('address', @$host_experience_city->address, ['class' => 'form-control', 'id' => 'input_address', 'placeholder' => 'City Address', 'autocomplete' => 'off']) !!}                    
      <span class="text-danger" id="input_address_error">{{ $errors->first('address') }}</span>
      <input type="hidden" name="latitude" id="input_latitude" value="{{@$host_experience_city->latitude}}">
      <input type="hidden" name="longitude" id="input_longitude" value="{{@$host_experience_city->longitude}}">
    </div>
</div>
<div class="form-group status">
    <label for="input_timezone" class="col-sm-3 control-label">Timezone<em class="text-danger">*</em></label>
    <div class="col-sm-6">
      {!! Form::select('timezone', $timezone_array, @$host_experience_city->timezone, ['class' => 'form-control', 'id' => 'input_timezone', 'placeholder' => 'Select Timezone']) !!}                    
      <span class="text-danger">{{ $errors->first('timezone') }}</span>
    </div>
</div>
<div class="form-group status">
    <label for="input_currency" class="col-sm-3 control-label">Currency<em class="text-danger">*</em></label>
    <div class="col-sm-6">
      {!! Form::select('currency_code', $currency_array, @$host_experience_city->currency_code, ['class' => 'form-control', 'id' => 'input_currency', 'placeholder' => 'Select Currency']) !!}                    
      <span class="text-danger">{{ $errors->first('currency_code') }}</span>
    </div>
</div>
<div class="form-group status">
  	<label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
  	<div class="col-sm-6">
    	{!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), @$host_experience_city->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}                    
  		<span class="text-danger">{{ $errors->first('status') }}</span>
  	</div>
</div>
@push('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>
<script type="text/javascript">
  var latitude = $("#input_latitude").val();
  var longitude = $("#input_longitude").val();
  function initAutocomplete()
  {
    autocomplete_elem = document.getElementById('input_address');
    autocomplete = new google.maps.places.Autocomplete(autocomplete_elem, { types: ['(cities)']});
    autocomplete.addListener('place_changed', function(){
      place = autocomplete.getPlace();
      latitude = place.geometry.location.lat();
      longitude = place.geometry.location.lng();

      $("#input_latitude").val(latitude);
      $("#input_longitude").val(longitude);
      //validate_lat_long();
    });
  }
  initAutocomplete();
  /*function validate_lat_long()
  {
    if(latitude == '' || longitude == '')
    {
      $("#input_address_error").text('Please select address from the Google Autocomplete');
      return false;
    }
    else
    {
      $("#input_address_error").text('');
      return true;
    }
  }
  $("#form").submit(function(){
    result =  validate_lat_long();
    return result;
  });*/
</script>
@endpush
