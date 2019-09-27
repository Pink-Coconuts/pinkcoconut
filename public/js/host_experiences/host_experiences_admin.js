app.controller('host_experiences_admin', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
  $scope.v = $("#host_experience_form").validate({
    ignore: ':hidden:not(.do-not-ignore)',
    rules: {
      city : { required: true },
      language : { required: true },
      category : {required: true},
      title : {required: true},
      start_time : {required: true},
      end_time : {required: true},
      tagline : {required: true},
      what_will_do : {required: true},
      where_will_be : {required: true},
      'location[location_name]' : {required: true},
      'location[country]' : {required: true},
      'location[address_line_1]' : {required: true},
      'location[city]' : {required: true},
      'location[latitude]' : {
        required:{ 
          depends: function(element){
            address_line_1 = $("#input_address_line_1").val();
            if($scope.step_id == '10' && address_line_1){
              return true;
            }
            else{
              return false;
            }
          }
        }
      },
      notes : {
        required:{ 
          depends: function(element){
            if($scope.need_notes != 'No'){
              return true;
            }
            else{
              return false;
            }
          }
        }
      },
      about_you : {required: true},
      minimum_age : {required: true},
      number_of_guests : {required: true},
      price_per_guest : {required: true},
      preparation_hours : {required: true},
      user_id : {required: true},
    },
    messages: {
      'location[latitude]' : {
        required : "Please choose the address from the google results.",
      }
    },
    errorElement: "span",
    errorClass: "text-danger",
    errorPlacement: function( label, element ) {
      if(element.attr( "data-error-placement" ) === "parent" ){
        element.parent().append( label ); 
      } else if(element.attr( "data-error-placement" ) === "next2" ){
        label.insertAfter( element.next() ); 
      } else if(element.attr( "data-error-placement" ) === "container" ){
        container = element.attr('data-error-container');
        $(container).append(label);
      } else {
        label.insertAfter( element ); 
      }
    }
  });
  jQuery.extend(jQuery.validator.messages, {
    min: jQuery.validator.format("Please enter a value greater than 0")
  });
  $.validator.addMethod("extension", function(value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
  }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));
  $.validator.addMethod("images_size_check", function(value, element, params) {
    files = element.files;
    if(files.length > 0)
    {
      var file=files[0]
      var _URL = window.URL || window.webkitURL;
      var img = new Image();
      var valid = true;
      
      var method = 'remote';
      var previous = this.previousValue(element, method);
      var validator = this;
      if (!this.settings.messages[element.name]) {
          this.settings.messages[element.name] = {};
      }
      previous.originalMessage = previous.originalMessage || this.settings.messages[element.name][method];
      this.settings.messages[element.name][method] = previous.message;
      var optionDataString = $.param({data: value});
      if (previous.old === optionDataString) {
          return previous.valid;
      }
      previous.old = optionDataString;
      this.startRequest(element);
      new Promise(function (fulfill) {
        img.onload = function () {
          if(this.width < params[0] || this.height < params[1])
          {
            valid =  false;
          }
          else
          {
            valid = true;
          }
          fulfill(valid);
        };
        img.src = _URL.createObjectURL(file);
      }).then(function(valid) {
        validator.settings.messages[ element.name ][ method ] = previous.originalMessage;
        if ( valid ) {
            submitted = validator.formSubmitted;
            validator.toHide = validator.errorsFor( element );
            validator.formSubmitted = submitted;
            validator.successList.push( element );
            validator.invalid[ element.name ] = false;
            validator.showErrors();
        } else {
            errors = {};
            message = validator.defaultMessage( element, { method: method, parameters: value } );
            errors[ element.name ] = previous.message = "Photos must be at least "+params[0]+"x"+params[1]+" pixels. Please upload a photo of higher quality.";
            validator.invalid[ element.name ] = true;
            validator.showErrors( errors );
            }
            previous.valid = valid;
            validator.stopRequest( element, valid );
        });
        return "pending";
      }
      else
      {
        return true;
      }
  }, $.validator.format("Photos must be at least {0}x{1} pixels. Please upload a photo of higher quality."));
  
  $.validator.addMethod("provide_count_check", function(value, element, param) {
    if($scope.need_provides != 'No' && $scope.provides.length <= 0)
    {
      return false;
    }
    else
    {
      return true;
    }
  }, $.validator.format("Atleast one provide item is required"));
  $.validator.addMethod("packing_list_count_check", function(value, element, param) {
    if($scope.need_packing_lists != 'No' && $scope.packing_lists.length <= 0)
    {
      return false;
    }
    else
    {
      return true;
    }
  }, $.validator.format("Atleast one packing list item is required"));
  $.validator.addClassRules({
      photos_check: {
          required: 
          { 
            depends: function(element){
              if($('#js-photo-grid li').length == 0){
                return true;
              }
              else{
                return false;
              }
            }
          },
          extension: "png|jpg|jpeg|gif",
          images_size_check : [480,720],
      },
      provide_count_check : {
        provide_count_check : true
      },
      packing_list_count_check : {
        packing_list_count_check : true
      },
  });
  function isNumberValidate(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }
  $(document).on('keypress', '.numeric-values', function(event){
    return isNumberValidate(event);
  });

  $scope.steps = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'];
  $scope.step_name = ""; 
  $scope.step = 0;
  $scope.edit_steps = function()
  {
    $scope.steps = ['20', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18'];
  }
  $scope.go_to_step = function(step)
  {
    step_id = $scope.steps[step];
    $scope.step_id = step_id; 
    $(".frm").hide();
    $("#sf"+step_id).show();
    $scope.step_name = $("#sf"+step_id).attr('data-step-name');
    $scope.step = step;
    $('#input_current_step_id').val(step_id);
    $('#input_current_step').val(step);
    if(step_id == '10')
    {
      $scope.initialize_autocomplete();
      $scope.initialize_map();
    }
  }
  $scope.go_to_step($scope.step);
  $scope.next_step =function(step)
  {
    current_step = $scope.steps[step];
    if($scope.v.form())
    {
      if(current_step != '19')
      {
        $scope.step = next_step = (step+1);
        $scope.go_to_step(next_step);
      }
      else
      {
        $('#host_experience_form').submit();
      }
    }
  }
  $scope.back_step = function(step)
  {
      $scope.step = next_step = (step-1); 
      $scope.go_to_step(next_step);
  }
  $scope.get_step_name = function(step)
  {
    step_id = $scope.steps[step]; 
    step_name = $("#sf"+step_id).attr('data-step-name');
    return step_name;
  }
  $scope.http_post = function(url, data, callback)
  {
    if(!data)
    {
      data = {};
    }
    $http.post(url, data).then(function(response){
      if(response.data.status == 300)
      {
        $scope.form_errors = response.data.errors;
      }
      else
      {
        $scope.form_errors = {};
      }
      if(response.data.status == 200)
      {
        if(response.data.host_experience_steps)
        {
          $scope.host_experience_steps = response.data.host_experience_steps;
        }
        if(callback)
        {
          callback(response.data);
        }
      }
    });
  }
  $scope.city_changed = function()
  {
    city_selected_element = $('#input_city > option:selected');
    $scope.currency_code = city_selected_element.attr('data-currency_code');
    $scope.timezone_abbr = city_selected_element.attr('data-timezone_abbr');
    $scope.currency_symbol = city_selected_element.attr('data-currency_symbol');
  }
  $scope.category_changed = function()
  {
    if($scope.category == $scope.secondary_category)
    {
      $scope.secondary_category = '';
    }
  }
  $scope.start_time_changed = function()
  {
    start_time = $scope.start_time;
    if(start_time){
      $scope.minimum_end_time = moment.utc(start_time,'HH:mm:ss').add(1,'hour').format('HH:mm:ss');
      if($scope.end_time < $scope.minimum_end_time)
        $scope.end_time = $scope.minimum_end_time;
    }
    else
    {
      $scope.minimum_end_time = '00:00:00';
    }
  }
  $scope.initialize_autocomplete = function()
  {
    autocomplete_elem = document.getElementById('input_address_line_1');
    $scope.autocomplete = new google.maps.places.Autocomplete(autocomplete_elem, { types: ['address']});
    $scope.autocomplete.addListener('place_changed', $scope.fillInAddress);
  }
  $scope.fillInAddress = function()
  {
    place = $scope.autocomplete.getPlace();
    $scope.fetchMapAddress(place);
  }
  $scope.fetchMapAddress = function(data) {
    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      sublocality_level_1: 'long_name',
      sublocality: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'
    };
    var street_number = '';
    var place = data;
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        if (addressType == 'street_number')
          street_number = val;
        if (addressType == 'route')
          $scope.address_line_1 = street_number + ' ' + val;
        if (addressType == 'postal_code')
          $scope.postal_code = val;
        if (addressType == 'locality')
          $scope.location_city = val;
        if (addressType == 'administrative_area_level_1')
          $scope.state = val;
        if (addressType == 'country')
          $scope.country = val;
      }
    }
    $scope.latitude = place.geometry.location.lat();
    $scope.longitude = place.geometry.location.lng();

    $('#input_latitude').val($scope.latitude);
    $('#input_longitude').val($scope.longitude);

    $scope.$apply();
    $("#input_latitude").valid();
    $scope.initialize_map();
  }
  $(document).on('change', "#input_address_line_1", function(){
    $scope.latitude = '';
    $scope.longitude = '';
    $("#input_latitude").val('');
    $("#input_longitude").val('');
    $scope.$apply();
    $("#input_latitude").valid();
  })
  $scope.initialize_map = function ()
  {
    var map_element = document.getElementById('host_experience_location_map');
    if(!$scope.latitude || !$scope.longitude || !map_element)
    {
      return false;
    }
    $scope.map = new google.maps.Map(map_element, {
      center: {
        lat: parseFloat($scope.latitude),
        lng: parseFloat($scope.longitude)
      },
      zoom: 15,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      }
    });
    $scope.initialize_marker();
  }
  $scope.initialize_marker = function()
  {
    var location_position = new google.maps.LatLng($scope.latitude, $scope.longitude);
    $scope.location_marker = new google.maps.Marker({
      map:$scope.map,
      draggable:true,
      // animation: google.maps.Animation.DROP,
      position: location_position,
      icon:new google.maps.MarkerImage(
        APP_URL+'/images/host_experiences/map_pin.png',
        new google.maps.Size(34, 50),
        new google.maps.Point(0, 0),
        new google.maps.Point(17, 50)
      )
    });
    google.maps.event.addListener($scope.location_marker, 'dragend', function() 
    {
      marker_location = $scope.location_marker.getPosition();
      $scope.latitude = marker_location.lat();
      $scope.longitude = marker_location.lng();
      console.log($scope.latitude);
      $scope.$apply();
    });
  }
  $scope.add_provide = function()
  {
    $scope.provides.push({'id': ''});
  }
  $scope.remove_provide = function(index)
  {
    provide = $scope.provides[index];
    if(provide.id)
    {
      delete_url = APP_URL+'/'+ADMIN_URL+'/host_experiences/provide_item_delete/'+provide.id;
      $scope.http_post(delete_url, {}, function(response_data){
      });
    }
    $scope.provides.splice(index, 1);
  }
  $scope.provide_item_available = function(host_experience_provide_item_id, current_index)
  {
    var available = true;
    for(var i = 0; i < $scope.provides.length; i++) {
        if ($scope.provides[i].host_experience_provide_item_id == host_experience_provide_item_id && i != current_index) {
            available = false;
            break;
        }
    }
    return available;
  }
  $scope.add_packing_list = function()
  {
    $scope.packing_lists.push({'id': ''});
  }
  $scope.remove_packing_list = function(index)
  {
    packing_list = $scope.packing_lists[index];
    if(packing_list.id)
    {
      delete_url = APP_URL+'/'+ADMIN_URL+'/host_experiences/packing_list_delete/'+packing_list.id;
      $scope.http_post(delete_url, {}, function(response_data){
      });
    }
    $scope.packing_lists.splice(index, 1);
  }
  $scope.delete_photo = function(id)
  {
    delete_url = APP_URL+'/'+ADMIN_URL+'/host_experiences/photo_delete/'+id;
    if($('[id^="photo_li_"]').size() > 1)
    {
      $scope.http_post(delete_url, {}, function(response_data){
      });
      $('#photo_li_'+id).remove();
    }
    else
    {
      alert('You cannnot delete last photo. Please upload alternate photos and delete this photo.');
    }
  }
  $scope.refresh_calendar = function(year, month)
  {
    refresh_calendar_url = APP_URL+'/'+ADMIN_URL+'/host_experiences/refresh_calendar/'+$scope.host_experience_id;
    $("#calendar").addClass('dot-loading');
    $scope.http_post(refresh_calendar_url, {year : year, month : month}, function(response_data){
      $("#calendar").html($compile(response_data.content)($scope));
      $("#calendar").removeClass('dot-loading');
    });
  }

  $(document).on('click', ".refresh_calendar", function(){
    var year = $(this).attr('data-year');
    var month = $(this).attr('data-month');
    $scope.refresh_calendar(year, month);
  });
  $(document).on('change', "#calendar_months_dropdown", function(){
    var year_month = $(this).val();
    var year = year_month.split('-')[0];
    var month = year_month.split('-')[1];
    $scope.refresh_calendar(year, month);
  });
}]);