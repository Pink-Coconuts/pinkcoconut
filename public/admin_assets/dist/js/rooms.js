var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');

$('#input_dob').datepicker({ 'dateFormat': 'dd-mm-yy'});
         var night_value    = $('#night').val();
         var cleaning_value = $('#cleaning').val();
         var additional     = $('#additional_guest').val();
         var guests         = $('#guests').val();
         var security_fee   = $('#security').val();
         var weekend_price  = $('#weekend').val();
         var week           = $('#week').val();
         var month          = $('#month').val();
         var currency_code  = $("#currency_code").find( "option:selected" ).prop("value");
         
   function step(step)
   {
      $(".frm").hide();
      $("#sf"+step).show();
      $('.tab_btn').removeAttr('disabled');
      $('.tab_btn#tab_btn_'+step).attr('disabled', 'disabled')
   }
app.controller('rooms_admin', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
  var v = $("#add_room_form").validate({
      ignore: ':hidden:not(.do-not-ignore)',
      rules: {
        calendar: { required: true },
        bedrooms: { required: true },
        beds: { required: true },
        bed_type: { required: true },
        bathrooms: { required: true },
        property_type: { required: true },
        room_type: { required: true },
        accommodates: { required: true },
        "name[]": { required: true },
        "summary[]": { required: true },        
        "language[]": { required: true },        
        country: { required: true },
        address_line_1: { required: true },
        city: { required: true },
        state: { required: true },
        latitude : {
            required:{ 
              depends: function(element){
                address_line_1 = $("#address_line_1").val();
                if($scope.step_id == '4' && address_line_1){
                  return true;
                }
                else{
                  return false;
                }
              }
            }
          },
        //night validation
        night: { 
                 required: true,
                 digits: true,
                 min: 1,
                },
        //cleaning validation
        cleaning: {
                    /*required: false,*/
                    digits: true,
        },
        //Additional Guest Charge validation
        additional_guest:{
                    /*required: false,*/
                    digits: true,
        },
        //Additional Guests validation
        guests: {
                  /*required: false,*/
                  digits: true,
        },
        //Security Fee validation
        security: {
                    /*required: false,*/
                    digits: true,
        },
        //weekend price validation
        weekend: {
                  /*required: false,*/
                  digits:true,
        },
           //week validation
          week: { 
                 /*required: true,*/
                 digits: true,
        
          },
             //month validation
          month: { 
                 /*required: true,*/
                 digits: true,
         
          },        
        video: { youtube: true },
        'photos[]': { required: { depends: function(element){
          if($('#js-photo-grid li').length == 0){
            return true;
          }
          else{
            return false;
          }
        } } ,extension:"png|jpg|jpeg|gif"},
        cancel_policy: { required: true },
        user_id: { required: true },
      },
      messages: {
        night : {
          min : jQuery.validator.format("Please enter a value greater than 0")
        },
        latitude : {
            required : "Please choose the address from the google results.",
        }
      },
      errorElement: "span",
      errorClass: "text-danger",
      errorPlacement: function( label, element ) {
        if(element.attr( "data-error-placement" ) === "container" ){
          container = element.attr('data-error-container');
          $(container).append(label);
        } else {
          label.insertAfter( element ); 
        }
      },
      extension:"Only png file is allowed!"
    });

   $.validator.addMethod("extension", function(value, element, param) {
  param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
  return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
  }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));
   $('.frm').hide();
   $('.frm#sf1').show();


   function next(step)
   {
    if(v.form())
    {
      if(step != 11)
      {
        $(".frm").hide();
        $("#sf"+(step+1)).show();
      }
      else
      {
        document.getElementById("add_room_form").submit();
      }
    }
   }

   function back(step)
   {
    $(".frm").hide();
    $("#sf"+(step-1)).show();
   }



  $scope.steps = ['1', '2', '3', '4', '5', '6', '7', '8', '12', '13', '9', '10', '11'];
  $scope.add_steps = ['2', '3', '4', '5', '6', '7', '8', '12', '13', '9', '10', '11'];
  $scope.step_name = ""; 
  $scope.step = 0;
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
  }
    $scope.go_to_edit_step = function(step)
   {
      $(".frm").hide();
      $("#sf"+step).show();
      $scope.step_id = step;
      $('.tab_btn').removeAttr('disabled');
      $('.tab_btn#tab_btn_'+step).attr('disabled', 'disabled')
   }
  $scope.go_to_step($scope.step);
  $scope.add_room_steps = function()
  {
    $scope.steps = $scope.add_steps;
    $scope.go_to_step($scope.step);
  }
  $scope.next_step =function(step)
  {
    current_step = $scope.steps[step];
    if(v.form())
    {
      if(current_step != '11')
      {
        $scope.step = next_step = (step+1);
        $scope.go_to_step(next_step);
      }
      else
      {
        $('#add_room_form').submit();
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

initAutocomplete(); // Call Google Autocomplete Initialize Function

$scope.rows = [];
//$(document).on('click', '#check', function()
  $(document).ready(function(){
  var value=$('#room_id').val();
  $http.post(APP_URL+'/get_lang_details/'+value, { }).then(function(response) {
    $scope.rows = response.data;
  $http.post(APP_URL+'/get_lang', { }).then(function(response) {
    $scope.lang_list = response.data;
  });
});
});


// Google Place Autocomplete Code
$scope.location_found = false;
$scope.autocomplete_used = false;
var autocomplete;

function initAutocomplete()
{
  autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_line_1'),{types: ['geocode']});
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() 
{
    $scope.autocomplete_used = true;
    fetchMapAddress(autocomplete.getPlace());
}

$scope.addNewRow = function() {
    var newItemNo = $scope.rows.length+1;
    $scope.rows.push({'id':'rows'+newItemNo});
  };

  $scope.removeRow = function(name) {       
    var index = name;   
    var comArr = eval( $scope.rows );
    for( var i = 0; i < comArr.length; i++ ) {
      if( comArr[i].name === name ) {
        index = i;
        break;
      }
    }
      $scope.rows.splice( index, 1 );   
  };

function fetchMapAddress(data)
{ //console.log(data);
  if(data['types'] == 'street_address')
    $scope.location_found = true;
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

    $('#city').val('');
    $('#state').val('');
    $('#country').val('');
    $('#address_line_1').val('');
    $('#address_line_2').val('');
    $('#postal_code').val('');

    var place = data;
    $scope.street_number = '';
    for (var i = 0; i < place.address_components.length; i++) 
    {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) 
      {
        var val = place.address_components[i][componentForm[addressType]];
      
      if(addressType       == 'street_number')
        $scope.street_number = val;
      if(addressType       == 'route')
        var street_address = $scope.street_number+' '+val;
        $('#address_line_1').val($.trim(street_address));
        //$('#address_line_1').val(val);
      if(addressType       == 'postal_code')
        $('#postal_code').val(val);
      if(addressType       == 'locality')
        $('#city').val(val);
      if(addressType       == 'administrative_area_level_1')
        $('#state').val(val);
      if(addressType       == 'country')
        $('#country').val(val);
      }
    }

  var address   = $('#address_line_1').val();

  var latitude  = place.geometry.location.lat();
  var longitude = place.geometry.location.lng();

    if($('#address_line_1').val() == '')
      $('#address_line_1').val($('#city').val());

    if($('#city').val() == '')
      $('#city').val('');
    if($('#state').val() == '')
      $('#state').val('');
    if($('#postal_code').val() == '')
      $('#postal_code').val('');

  $('#latitude').val(latitude);
  $('#longitude').val(longitude);
}   

$( "#username" ).autocomplete({
  source: APP_URL+'/'+ADMIN_URL+'/rooms/users_list',
  select: function(event, ui)
  {
    $('#user_id').val(ui.item.id);
  }
});

$(document).on('click', '.month-nav', function()
{
  var month = $(this).attr('data-month');
  var year = $(this).attr('data-year');

  var data_params = {};

  data_params['month'] = month;
  data_params['year'] = year;

  var data = JSON.stringify(data_params);

  $http.post(APP_URL+'/'+ADMIN_URL+'/ajax_calendar/'+$('#room_id').val(), { data:data }).then(function(response) {
    $( "#ajax_container" ).html( $compile(response.data)($scope) );
    });
  return false;
});


$(document).on('change', '#calendar_dropdown', function()
{
  var year_month = $(this).val();
  var year = year_month.split('-')[0];
  var month = year_month.split('-')[1];
  
  var data_params = {};

  data_params['month'] = month;
  data_params['year'] = year;

  var data = JSON.stringify(data_params);

  $http.post(APP_URL+'/'+ADMIN_URL+'/ajax_calendar/'+$('#room_id').val(), { data:data }).then(function(response) {
    $( "#ajax_container" ).html( $compile(response.data)($scope) );
    });
  return false;
});

$(document).on('click', '.delete-photo-btn', function()
{
  var id = $(this).attr('data-photo-id');
  var room_id = $('#room_id').val();
  
  if($('[id^="photo_li_"]').size() > 1)
  {
  $http.post(APP_URL+'/'+ADMIN_URL+'/delete_photo', { photo_id : id,room_id : room_id }).then(function(response) 
  {
    if(response.data.success == 'true')
    {
      $('#photo_li_'+id).remove();
    }
  });
  }
  else
  {
    alert('You cannnot delete last photo. Please upload alternate photos and delete this photo.');
  }
});

$(document).on('click', '.featured-photo-btn', function()
{
  var id = $(this).attr('data-featured-id');
  var room_id = $("input[id=room_id]").val();
  //alert(id + "" + room_id); 
  
  $http.post(APP_URL+'/'+ADMIN_URL+'/featured_image', { id : room_id ,photo_id  : id}).then(function(response) 
  {
    if(response.data.success == 'true')
    {
      alert('success');
    }
  });
 
});

$(document).on('keyup', '.highlights', function()
{
  var value = $(this).val();
  var id = $(this).attr('data-photo-id');
  $('#saved_message').fadeIn();
  $http.post(APP_URL+'/'+ADMIN_URL+'/photo_highlights', { photo_id : id, data : value }).then(function(response)
  {
    $('#saved_message').fadeOut();
  });
});

$(document).on('change', '#additional_guest', function() {
  disableAdditionalGuestCharge();
});
disableAdditionalGuestCharge();
function disableAdditionalGuestCharge() {
  if ($('#additional_guest').val() == "0")
    $('#guests').prop('disabled', true);
  else
    $('#guests').prop('disabled', false);
}

  $.validator.addMethod("youtube", function(value, element) {
    if (value != undefined && value.length > 0) {
      var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
      var match = value.match(regExp);
      if (match && match[2].length == 11) {
        return true
      }
      else {
          return false;
      }
    }
    return true;
  }, 'Please select a valid youtube url.');
  $.validator.addMethod("maximum_stay_value", function(value, element, param) {
    min_elem = $(element).attr('data-minimum_stay');
    min_value = $(min_elem).val();
    if((min_value-0) > (value-0) && min_value != '' && value != '')
    {
      return false;
    }
    else
    {
      return true;
    }
  }, $.validator.format("Maximum stay must be greater than Minimum stay"));
  $.validator.addClassRules({
    discount: {
      digits : true,
      required : true,
      min: 1,
      max: 99,
    },
    early_bird_period: {
      digits: true,
      required: true,
      min: 30,
      max : 1080,
    },
    last_min_period: {
      digits: true,
      required: true,
      min: 1,
      max : 28,
    },
    minimum_stay: {
      digits: true,
      min: 1,
    },
    maximum_stay: {
      digits: true,
      min: 1,
      maximum_stay_value:true,
    },
    availability_minimum_stay: {
      digits: true,
      min: 1,
    },
    availability_maximum_stay: {
      required: {
        depends: function(element){
          min_elem = $(element).attr('data-minimum_stay');
          min_value = $(min_elem).val();
          return min_value == '';
        }
      },
      digits: true,
      min: 1,
      maximum_stay_value:true,
    }
  });
  $scope.add_price_rule = function(type) {
    if(type == 'length_of_stay')
    {
      new_period = $scope.length_of_stay_period_select;
      $scope.length_of_stay_items.push({'period' : new_period-0});
      $scope.length_of_stay_period_select = '';
    }
    else if(type== 'early_bird') 
    {
      $scope.early_bird_items.push({'period' : ''});
    }
    else if(type== 'last_min') 
    {
      $scope.last_min_items.push({'period' : ''});
    }
  }
  $scope.remove_price_rule = function(type, index) {
    if(type == 'length_of_stay') {
      item =$scope.length_of_stay_items[index];
      $scope.length_of_stay_items.splice(index, 1);
    }
    else if(type == 'early_bird') {
      item =$scope.early_bird_items[index];
      $scope.early_bird_items.splice(index, 1);
    }
    else if(type == 'last_min') {
      item =$scope.last_min_items[index];
      $scope.last_min_items.splice(index, 1);
    }
    if(item.id != '' && item.id) {
      $('.'+type+'_wrapper').addClass('loading');
      $('button[type="submit"]').attr('disabled', true);
      $http.post(APP_URL+'/'+ADMIN_URL+'/rooms/delete_price_rule/'+item.id, {}).then(function(response){
        $('.'+type+'_wrapper').removeClass('loading');
        $('button[type="submit"]').removeAttr('disabled');
      })
    }
  }
  $scope.length_of_stay_option_avaialble = function(option) {
    var found = $filter('filter')($scope.length_of_stay_items, {'period': option}, true);
    var found_text = $filter('filter')($scope.length_of_stay_items, {'period': ''+option}, true);
    return !found.length && !found_text.length;
  }
  $scope.add_availability_rule = function() {
    $scope.availability_rules.push({'type' : ''});
    setTimeout(function(){
      $scope.availability_datepickers();
    }, 20);
  }
  $scope.remove_availability_rule = function(index) {
    item = $scope.availability_rules[index];
    type = 'availability_rules';
    if(item.id != '' && item.id) {
      $('.'+type+'_wrapper').addClass('loading');
      $('button[type="submit"]').attr('disabled', true);
      $http.post(APP_URL+'/'+ADMIN_URL+'/rooms/delete_availability_rule/'+item.id, {}).then(function(response){
        $('.'+type+'_wrapper').removeClass('loading');
        $('button[type="submit"]').removeAttr('disabled');
      })
    }
    $scope.availability_rules.splice(index, 1); 
  }
  $scope.availability_rules_type_change = function(index) {
    rule = $scope.availability_rules[index];
    if(rule.type != 'custom')
    {
      this_elem = $("#availability_rules_"+index+"_type option:selected");
      start_date = this_elem.attr('data-start_date');
      end_date = this_elem.attr('data-end_date');
      $scope.availability_rules[index].start_date = start_date;
      $scope.availability_rules[index].end_date = end_date;
    }
  }
  $scope.availability_datepickers = function() {
    if(!$scope.availability_rules)
    {
      return;
    }
    $.each($scope.availability_rules, function(i, data){
      var start_date_element = $("#availability_rules_"+i+"_start_date");
      var end_date_element = $("#availability_rules_"+i+"_end_date");
      start_date_element.datepicker({
        'minDate':0,
        'dateFormat': datepicker_format,
        onSelect: function(date, obj){
          var start_date = start_date_element.datepicker('getDate'); 
          start_date.setDate(start_date.getDate() + 1); 
          end_date_element.datepicker('option', 'minDate',start_date );
          // end_date_element.trigger('focus');
        }
      })
      end_date_element.datepicker({
        'minDate':1,
        'dateFormat': datepicker_format
      })
      
    });
  }
  $scope.copy_data =function(data) {
    return angular.copy(data);
  }
  $(document).ready(function(){
    $scope.availability_datepickers();
  });
}]);