$(document).on("keypress", "#location_input", function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
});
app.controller('rooms_new', ['$scope', function($scope) {

    $scope.submitbtn = true;
    $scope.accommodates_value = 1;
    $scope.city_show = false;
    $scope.submitDisable = false;
    var i = 0;

    $scope.city_rm = function() {
        $scope.submitDisable = false;
        $scope.city_show = false;
    };

    $scope.property_type = function(id, name, icon) {
        $scope.property_type_id = id;
        $scope.selected_property_type = name;
        $scope.property_type_icon = icon;
        $('.fieldset_property_type_id .active-selection').css('display', 'block');
    };

    $scope.property_type_rm = function() {
        $scope.submitDisable = false;
        $scope.property_type_id = '';
        $scope.selected_property_type = '';
        $scope.property_type_icon = '';
    };

    $scope.property_change = function(value) {
        $scope.property_type_id = value;
        $scope.selected_property_type = $('#property_type_dropdown option:selected').text();
        $scope.property_type_icon = $('#property_type_dropdown option:selected').attr('data-icon-class');
        $('.fieldset_property_type_id .active-selection').css('display', 'block');
    };

    $scope.room_type = function(id, name, icon, is_shared) {
        $scope.room_type_id = id;
        $scope.selected_room_type = name;
        $scope.room_type_icon = icon;
        $scope.is_shared = is_shared;
        $('.fieldset_room_type .active-selection').css('display', 'block');
    };

    $scope.room_type_rm = function() {
        $scope.submitDisable = false;
        $scope.room_type_id = '';
        $scope.selected_room_type = '';
        $scope.room_type_icon = '';
        $scope.is_shared = '';
    };
    $scope.room_change = function(value) {
        $scope.room_type_id = value;
        $scope.selected_room_type = $('#room_type_dropdown option:selected').text();
        $scope.room_type_icon = $('#room_type_dropdown option:selected').attr('data-icon-class');
        $scope.is_shared = $('#room_type_dropdown option:selected').attr('data-is_shared');
        $('.fieldset_room_type .active-selection').css('display', 'block');
    };

    $scope.change_accommodates = function(value) {
        $scope.selected_accommodates = value;
        $('.fieldset_person_capacity .active-selection').css('display', 'block');
        i = 1;
    };

    $scope.accommodates_rm = function() {
        $scope.submitDisable = false;
        $scope.selected_accommodates = '';
    };

    $scope.city_click = function() {
        $scope.submitDisable = false;
        if (i == 0)
            $scope.change_accommodates(1);
    };

    initAutocomplete(); // Call Google Autocomplete Initialize Function

    // Google Place Autocomplete Code

    var autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'short_name',
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('location_input'),{types: ['geocode']}); //, { types: ['(cities)'] }
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        $scope.city = '';
        $scope.state = '';
        $scope.country = '';

        var place = autocomplete.getPlace();

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];

                if (addressType == 'street_number')
                    $scope.street_number = val;
                if (addressType == 'route')
                    $scope.route = val;
                if (addressType == 'postal_code')
                    $scope.postal_code = val;
                if (addressType == 'locality')
                    $scope.city = val;
                if (addressType == 'administrative_area_level_1')
                    $scope.state = val;
                if (addressType == 'country') {
                    if ($scope.country_list.indexOf(val) !== -1) {
                        $scope.country = val;
                        $("#location_country_error_message").addClass('hide');
                    } else {
                        $("#location_country_error_message").removeClass('hide');
                        return false;
                    }
                }
            }
        }
        var address = $('#location_input').val();
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        $scope.address = address;
        $scope.city_show = true;
        $scope.latitude = latitude;
        $scope.longitude = longitude;
        $scope.$apply();
        $('.fieldset_city .active-selection').css('display', 'block');
    }

    $scope.disableButton = function() {
        $("form[name='lys_new']").submit();
        $scope.submitDisable = true;
    }
}]);