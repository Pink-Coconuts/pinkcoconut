app.controller('manage_listing', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {

    $scope.text_length_calc = function(text) {
        tag_free_text = text ? String(text).replace(/<[^>]+>/gm, '') : '';
        return tag_free_text.length;
    }

    $(document).on('click', '#add_language', function()
{
    $('#add_language_des').show();
    $('.description_form').hide();
    $('.tab-item').attr('aria-selected', 'false');
    $('#write-description-button').prop('disabled', true);

    $http.post('get_all_language', { }).then(function(response) 
    {
        $scope.all_language = response.data;
    });

});

$(document).on('click', '#delete_language', function()
{
    var current_tab = $('#current_tab_code').val();


    $http.post('delete_language', {current_tab:current_tab }).then(function(response) 
{

    $http.post('lan_description', { }).then(function(response) 
{
    $scope.lan_description = response.data;

    current_tab = $('#current_tab_code').val('en');
});
    $scope.getdescription('en');

});


    
});

$(document).on('change', '#language-select', function()
{

    $('#write-description-button').prop('disabled', false);
});


$http.post('lan_description', { }).then(function(response) 
{
    $scope.lan_description = response.data;

});

$http.post('get_all_language', { }).then(function(response) 
{
    $scope.all_language = response.data;
});


$http.post('get_description', { lan_code : 'en'}).then(function(response) 
{
     $scope.name    = response.data[0].name;
     $scope.summary = response.data[0].summary;
     $scope.space   = response.data[0].rooms_description.space;
     $scope.access   = response.data[0].rooms_description.access;
     $scope.interaction   = response.data[0].rooms_description.interaction;
     $scope.notes   = response.data[0].rooms_description.notes;
     $scope.house_rules   = response.data[0].rooms_description.house_rules;
     $scope.neighborhood_overview   = response.data[0].rooms_description.neighborhood_overview;
     $scope.transit   = response.data[0].rooms_description.transit;    
});

$scope.getdescription = function(lan_code) {
 var lan_code = lan_code;
$http.post('get_description', {lan_code :lan_code }).then(function(response) 
{
    if(lan_code != 'en'){
     $scope.name    = response.data[0].name;
     $scope.summary = response.data[0].summary;
     $scope.space   = response.data[0].space;
     $scope.access   = response.data[0].access;
     $scope.interaction   = response.data[0].interaction;
     $scope.notes   = response.data[0].notes;
     $scope.house_rules   = response.data[0].house_rules;
     $scope.neighborhood_overview   = response.data[0].neighborhood_overview;
     $scope.transit   = response.data[0].transit;
     }else{
     $scope.name    = response.data[0].name;
     $scope.summary = response.data[0].summary;
     $scope.space   = response.data[0].rooms_description.space;
     $scope.access   = response.data[0].rooms_description.access;
     $scope.interaction   = response.data[0].rooms_description.interaction;
     $scope.notes   = response.data[0].rooms_description.notes;
     $scope.house_rules   = response.data[0].rooms_description.house_rules;
     $scope.neighborhood_overview   = response.data[0].rooms_description.neighborhood_overview;
     $scope.transit   = response.data[0].rooms_description.transit; }

     if(response.data[0].lang_code)
     {
        var tab_selected = $("#"+response.data[0].lang_code).attr('aria-selected');
        $('#current_tab_code').val(response.data[0].lang_code);
        $('#delete_language').show();
     }
     else
     {
        var tab_selected = $("#en").attr('aria-selected');
        response.data[0].lang_code = 'en';
        $('#current_tab_code').val(response.data[0].lang_code);
        $('#delete_language').hide();

     }
      

    if(tab_selected == 'false')
    {
        $('.tab-item').attr('aria-selected', 'false');
        $("#"+response.data[0].lang_code).attr('aria-selected', 'true');
        
    }

    $('#add_language_des').hide();
    $('.description_form').show();


});
    
}

$scope.addlanguageRow = function() {

 var lan_code = $('#language-select').val();

 $('#current_tab_code').val(lan_code);

$http.post('add_description', {lan_code :lan_code }).then(function(response) 
{
    
     $scope.name    = response.data[0].name;
     $scope.summary = response.data[0].summary;     
     $scope.space   = response.data[0].space;
     $scope.access   = response.data[0].access;
     $scope.interaction   = response.data[0].interaction;
     $scope.notes   = response.data[0].notes;
     $scope.house_rules   = response.data[0].house_rules;
     $scope.neighborhood_overview   = response.data[0].neighborhood_overview;
     $scope.transit   = response.data[0].transit;
     $('#write-description-button').prop('disabled', true);
     
    $http.post('lan_description', { }).then(function(response) 
    {
        $scope.lan_description = response.data;

        $('#add_language_des').hide();
        $('.description_form').show();
        $('.multiple-description-tabs').show();
        $('#delete_language').show();

        var count = (response.data[0].lan_id - 1 );
        
        $('.tab-item').attr('aria-selected', 'false');

        setTimeout(function(){ 
            $("#"+response.data[count].lan_code).attr('aria-selected', 'true');
         }, 100);
            
    });

    $('#language-select').prop('selectedIndex',0);

});
    
}

    // if(name){
    //      $scope.name = name;
    //  }else{

    //      $scope.name = '';
    //  }
    //  if(summary){
    //      console.log(summary);
    //      $scope.summary = summary;
    //  }else{
    //      $scope.summary = '';
    //  }

    // browser back button click previous page
    jQuery(document).ready(function($) {

        if (window.history) {

            $(window).on('popstate', function() {

                var ex_pathname = (window.location.href).split('/');
                var cur_step = $(ex_pathname).get(-1);

                $('#href_' + cur_step).attr('href', window.location.href);
                $('#href_' + cur_step).trigger('click');
            });

        }
    });

    $(document).on('click', '[data-track="welcome_modal_finish_listing"]', function() {
        var data_params = {};

        data_params['started'] = 'Yes';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            $('body').removeClass('pos-fix3');   
            $('.welcome-new-host-modal').attr('aria-hidden', 'true');
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    });

    $(document).on('change', '[id^="basics-select-"], [id^="select-"]', function() {

        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
            }
            if (response.data.redirect != '' && response.data.redirect != undefined) {
                window.location = response.data.redirect;
            }
            if ($scope.beds != '' && $scope.bedrooms != '' && $scope.bathrooms != '' && $scope.bed_type != '') {
                var track = saving_class.substring(0, saving_class.length - 1);
                $('[data-track="' + track + '"] a div div .transition').removeClass('visible');
                $('[data-track="' + track + '"] a div div .transition').addClass('hide');
                $('[data-track="' + track + '"] a div div .pull-right .nav-icon').removeClass('hide');
            }
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });

        if ($(this).attr('name') == 'beds') {
            if ($(this).val() != '')
                $('#beds_show').show();
        }

    });

    $(document).on('blur', '#video', function() {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        var data = JSON.stringify(data_params);

        $('#video_error').fadeOut();
        $('.saving-progress h5').text('Saving...');
        $('.saving-progress').fadeIn();

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('.saving-progress h5').text('Saved!');
                $('.saving-progress').fadeOut();
                $scope.video = response.data.video;
                $('#rooms_video_preview').parent().removeClass('hide');
                $('#rooms_video_preview').attr('src', response.data.video);
            } else {
                $('.saving-progress').fadeOut();
                $('#video_error').fadeIn();
            }
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    });
    $(document).on('click', '#remove_rooms_video', function() {


        var saving_class = $(this).attr('data-saving');
        $('.saving-progress h5').text('Removing...');
        $('.saving-progress').fadeIn();

        $http.post('remove_video').then(function(response) {
            if (response.data.success == 'true') {
                console.log(response.data);
                $('.saving-progress h5').text('Removed!');
                $('.saving-progress').fadeOut();
                $('#video').val('');
                $('#rooms_video_preview').parent().addClass('hide');
                $('#rooms_video_preview').attr('src', '');
                $scope.video = response.data.video;

            }
        });
    });
    $(document).on('blur', '[class^="overview-"]', function() {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        var current_tab = $('#current_tab_code').val();
        var data = JSON.stringify(data_params);
        if ($(this).val() != '') {
            $('.saving-progress h5').text('Saving...');
            $('.saving-progress').fadeIn();
if(current_tab=='en'){
            $('.name_required_msg').addClass('hide');
            $('.summary_required_msg').addClass('hide');
            $('.name_required').removeClass('invalid');
            $('.summary_required').removeClass('invalid'); }

            $http.post('update_rooms', {
                data: data , current_tab:current_tab
            }).then(function(response) {
                if (response.data.success == 'true') {
                    $('.saving-progress h5').text('Saved!');
                    $('.saving-progress').fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                }
                if ($scope.name != '' && $scope.summary != '' && current_tab=='en') {
                    $('[data-track="description"] a div div .transition').removeClass('visible');
                    $('[data-track="description"] a div div .transition').addClass('hide');
                    $('[data-track="description"] a div div div .icon-ok-alt').removeClass('hide');
                }
            }, function(response) {
                if (response.status == '300'){
                    window.location = APP_URL + '/login';
                } else if (response.status == '500'){
                    window.location.reload();
                }
            });
        } else {
            if ($(this).attr('name') == 'name') {
                $http.post('update_rooms', {
                    data: data, current_tab:current_tab
                }).then(function(response) {
                    if (response.data.success == 'true') {
                       if(current_tab=='en'){

                        $('.name_required').addClass('invalid');
                        $('.name_required_msg').removeClass('hide');
}
                        $('#steps_count').text(response.data.steps_count);
                        $scope.steps_count = response.data.steps_count;
                    }
                }, function(response) {
                    if (response.status == '300'){
                        window.location = APP_URL + '/login';
                    } else if (response.status == '500'){
                        window.location.reload();
                    }
                });
            } else {
                $http.post('update_rooms', {
                    data: data, current_tab:current_tab
                }).then(function(response) {
                    if (response.data.success == 'true') {
                        if(current_tab=='en'){
                        $('.summary_required').addClass('invalid');
                        $('.summary_required_msg').removeClass('hide');
}
                        $('#steps_count').text(response.data.steps_count);
                        $scope.steps_count = response.data.steps_count;
                    }
                }, function(response) {
                    if (response.status == '300'){
                        window.location = APP_URL + '/login';
                    } else if (response.status == '500'){
                        window.location.reload();
                    }
                });

            }
              if(current_tab=='en'){
            $('[data-track="description"] a div div .transition').removeClass('hide');
            $('[data-track="description"] a div div .transition .icon').removeClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').addClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').removeClass('visible');
            }
        }
        if(current_tab == 'en')
        {
            $scope.rooms_default_description.name = $scope.name;
            $scope.rooms_default_description.summary = $scope.summary;    
        }
    });

    $(document).on('click', '#js-write-more', function() {
        $('.write_more_p').hide();
        $('#js-section-details').show();
        $('#js-section-details_2').show();
    });

    $(document).on('click', '.nav-item a, .next_step a, #calendar_edit_cancel', function() {
        if ($(this).attr('href') != '') {
            var data_params = {};
            var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

            $("#ajax_container").html(loading);

            $http.post($(this).attr('href').replace('manage-listing', 'ajax-manage-listing'), {
                data: data_params
            }).then(function(response) {                  
                     if(response.data.success_303 == "false"){
                                    window.location = APP_URL + '/login';
                                    return false;
                     }            

                    $("#ajax_container").html($compile(response.data)($scope));

                    // $http.post('get_description', { lan_code : 'en'}).then(function(response) 
                    // {
                         $scope.name    = $scope.rooms_default_description.name;
                         $scope.summary = $scope.rooms_default_description.summary;    
                         $scope.notes = '';
                    // });
                },
                function(response) {                    
                    if (response.status == '300')
                        window.location = APP_URL + '/login';
                }

                );

            var ex_pathname = (window.location.href).split('/');
            var cur_step = $(ex_pathname).get(-1);

            $('#href_' + cur_step).attr('href', window.location.href);

            var ex_pathname = $(this).attr('href').split('/');
            var next_step = $(ex_pathname).get(-1);

            // if (next_step != 'calendar') {
            //     $('.manage-listing-row-container').removeClass('has-collapsed-nav');
            // } else {
            //     if ($('#room_status').val() != '') {
            //         $('.manage-listing-row-container').addClass('has-collapsed-nav');
            //         $('#js-manage-listing-nav').addClass('collapsed');
            //     }
            // }

            // if (cur_step == 'calendar' || next_step == 'calendar') {
            //     $http.post($(this).attr('href').replace('manage-listing', 'ajax-header'), {}).then(function(response) {
            //             $("#ajax_header").html($compile(response.data)($scope));
            //         },
            //         function(response) {
            //             if (response.status == '300')
            //                 window.location = APP_URL + '/login';
            //         });
            // }

            $scope.step = next_step;
            
            window.history.pushState({
                path: $(this).attr('href')
            }, '', $(this).attr('href'));

            return false;
        }
    });

    $(document).on('click', '#show_long_term', function() {
        $('#js-long-term-prices').removeClass('hide');
        $('#js-set-long-term-prices').addClass('hide');
    });

    $(document).on('click', '#js-add-address, #js-edit-address', function() {
        var data_params = {};

        $scope.autocomplete_used = false;

        data_params['country'] = $scope.country;
        data_params['address_line_1'] = $scope.address_line_1;
        data_params['address_line_2'] = $scope.address_line_2;
        data_params['city'] = $scope.city;
        data_params['state'] = $scope.state;
        data_params['postal_code'] = $scope.postal_code;
        data_params['latitude'] = $scope.latitude;
        data_params['longitude'] = $scope.longitude;

        var data = JSON.stringify(data_params);

        $('#js-address-container').addClass('enter_address');
        $('#address-flow-view .modal').fadeIn();
        $('#address-flow-view .modal').attr('aria-hidden', 'false');
        $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
            data: data
        }).then(function(response) {
            $("#js-address-container").html($compile(response.data)($scope));
            initAutocomplete();
        });
    });

    $(document).on('click', '#js-next-btn', function() {
        var data_params = {};

        data_params['country'] = $scope.country = $('#country').val();
        data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
        data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
        data_params['city'] = $scope.city = $('#city').val();
        data_params['state'] = $scope.state = $('#state').val();
        data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
        data_params['latitude'] = $scope.latitude;
        data_params['longitude'] = $scope.longitude;

        if(!data_params['country']) {
            $("#location_country_field_error").removeClass("hide");
            return false;
        }
        $("#location_country_field_error").addClass("hide")

        var data = JSON.stringify(data_params);
        if (!$scope.autocomplete_used)
            $scope.location_found = true;
        $('#js-address-container .panel').addClass('loading');
        // if($scope.autocomplete_used){
        var geocoder = new google.maps.Geocoder();
        address = $scope.address_line_1 + ', ' + $scope.address_line_2 + ', ' + $scope.city + ', ' + $scope.state + ', ' + $scope.country + ', ' + $scope.postal_code;

        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $scope.latitude = results[0].geometry.location.lat();
                $scope.longitude = results[0].geometry.location.lng();
                result = results[0];
                if (result['types'] == "street_address" || result['types'] == "premise") {
                    $scope.location_found = true;
                    $scope.autocomplete_used = true;
                } else {
                    $scope.location_found = false;
                    $scope.autocomplete_used = false;
                }
            }
            $http.post((window.location.href).replace('manage-listing', 'location_not_found'), {
                data: data
            }).then(function(response) {
                if (response.data.status == "country_error") {
                    $("#location_country_field_error").removeClass("hide");
                    $('#js-address-container .panel').removeClass('loading');
                    return false;
                }
                $('#js-address-container .panel').removeClass('loading');
                $('#js-address-container').addClass('location_not_found');
                $("#js-address-container").html($compile(response.data)($scope));
            });
        });
        // }
    });

    $(document).on('click', '#js-next-btn2', function() {
        var data_params = {};

        /*if(!$scope.autocomplete_used) {
        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': $scope.city+', '+$scope.state+', '+$scope.country }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            $scope.latitude = results[0].geometry.location.lat();
            $scope.longitude = results[0].geometry.location.lng(); 
          } else {
          }
        });
        $scope.location_found = false;
    }*/

        data_params['country'] = $scope.country;
        data_params['address_line_1'] = $scope.address_line_1;
        data_params['address_line_2'] = $scope.address_line_2;
        data_params['city'] = $scope.city;
        data_params['state'] = $scope.state;
        data_params['postal_code'] = $scope.postal_code;
        data_params['latitude'] = $scope.latitude;
        data_params['longitude'] = $scope.longitude;

        var data = JSON.stringify(data_params);
        $('#js-address-container .panel').addClass('loading');
        $http.post((window.location.href).replace('manage-listing', 'verify_location'), {
            data: data
        }).then(function(response) {
            if (response.data.status == "country_error") {
                $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
                    data:data
                }).then(function(response) {
                    $("#js-address-container").html($compile(response.data)($scope));
                    initAutocomplete();
                    $scope.country = data_params['country'];
                    $("#location_country_field_error").removeClass("hide");
                });
                $('#js-address-container .panel').removeClass('loading');
                return false;
            }
            $('#js-address-container .panel').removeClass('loading');
            $('#js-address-container').addClass('location_not_found');
            $("#js-address-container").html($compile(response.data)($scope));
            setTimeout(function() {
                initMap();
            }, 100);
        });
    });
    //amenity tooltip show
    $(document).on('mouseover', '[id^="amenity-tooltip"]', function() {
        var id = $(this).data('id');
        $('#ame-tooltip-' + id).show();
    });
    $(document).on('mouseout', '[id^="amenity-tooltip"]', function() {
        $('[id^="ame-tooltip"]').hide();
    });

    $(document).on('click', '#js-next-btn3', function() {
        var data_params = {};

        data_params['country'] = $scope.country = $('#country').val();
        data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
        data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
        data_params['city'] = $scope.city = $('#city').val();
        data_params['state'] = $scope.state = $('#state').val();
        data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
        data_params['latitude'] = $scope.latitude;
        data_params['longitude'] = $scope.longitude;

        var data = JSON.stringify(data_params);

        $('#js-address-container .panel:first').addClass('loading');
        $http.post((window.location.href).replace('manage-listing', 'finish_address'), {
            data: data
        }).then(function(response) {
            if (response.data.status == "country_error") {
                $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
                    data:data
                }).then(function(response) {
                    $("#js-address-container").html($compile(response.data)($scope));
                    initAutocomplete();
                    $scope.country = data_params['country'];
                    $("#location_country_field_error").removeClass("hide");
                });
                $('#js-address-container .panel').removeClass('loading');
                return false;
            }
            $('#js-address-container .panel').removeClass('loading');

            $('.location-map-container-v2').removeClass('empty-map');
            // $('.location-map-container-v2').addClass('map-spotlight-v2');

            $('.location-map-pin-v2').removeClass('moving');
            $('.location-map-pin-v2').addClass('set');
            $('.address-static-map img').remove();
            $('.address-static-map').append('<img style="width:100%; height:275px;" src="https://maps.googleapis.com/maps/api/staticmap?size=570x275&amp;center=' + response.data.latitude + ',' + response.data.longitude + '&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key=' + map_key + '&amp;markers=icon:' + APP_URL + '/images/map-pin-set-3460214b477748232858bedae3955d81.png%7C' + response.data.latitude + ',' + response.data.longitude + '">');

            $('.panel-body .text-center').remove();

            $('.panel-body address').removeClass('hide');
            $('.panel-body .js-edit-address-link').removeClass('hide');
            var address_line_2 = (response.data.address_line_2 != '') ? ' / ' + response.data.address_line_2 : '';
            $('.panel-body address span:nth-child(1)').text(response.data.address_line_1 + address_line_2);
            $('.panel-body address span:nth-child(2)').text(response.data.city + ' ' + response.data.state);
            $('.panel-body address span:nth-child(3)').text(response.data.postal_code);
            $('.panel-body address span:nth-child(4)').text(response.data.country_name);

            $('[data-track="location"] a div div .transition').removeClass('visible');
            $('[data-track="location"] a div div .transition').addClass('hide');
            $('[data-track="location"] a div div div .icon-ok-alt').removeClass('hide');

            $('#address-flow-view .modal').fadeOut();
            $('#address-flow-view .modal').attr('aria-hidden', 'true');
            $('#steps_count').text(response.data.steps_count);
            $scope.steps_count = response.data.steps_count;
            $scope.location_found = false;
        });
    });

    $(document).on('click', '.modal-close, [data-behavior="modal-close"], .panel-close', function() {
        $('.modal').fadeOut();
        $('.tooltip').css('opacity', '0');
        $('.tooltip').attr('aria-hidden', 'true');
        $('.modal').attr('aria-hidden', 'true');
    });

    initAutocomplete(); // Call Google Autocomplete Initialize Function

    // Google Place Autocomplete Code
    $scope.location_found = false;
    $scope.autocomplete_used = false;
    var autocomplete;

    function initAutocomplete() {
        var ex_pathname = (window.location.href).split('/');
        var cur_step = $(ex_pathname).get(-1);

        if (cur_step == 'location') {
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_line_1'),{types: ['geocode']});
            autocomplete.addListener('place_changed', fillInAddress);
        }
        /*if($('#address_line_1').val() == '' || $('#state').val() == '' || $('#city').val() == '' )
        {
            $('#js-next-btn').prop('disabled', true);
        }*/

    }

    $("#address-flow-view .modal").scroll(function() {
        $(".pac-container").hide();
    });

    function fillInAddress() {
        $scope.autocomplete_used = true;
        fetchMapAddress(autocomplete.getPlace());
    }

    if ($('#state').val() || $('#city').val() == '') {
        $('#js-next-btn').prop('disabled', true);
    }

    $(document).on('keyup', '#state', function() {
        if ($(this).val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);
    });

    $(document).on('keyup', '#city', function() {
        if ($(this).val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);
    });
    /*$(document).on('click', '#address_line_1', function()
    {
        if($(this).val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);
    });*/


    var map, geocoder;

    function initMap() {

        geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: parseFloat($scope.latitude),
                lng: parseFloat($scope.longitude)
            },
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        $('<div/>').addClass('verify-map-pin').appendTo(map.getDiv()).click(function() {});

        map.addListener('dragend', function() {
            geocoder.geocode({
                'latLng': map.getCenter()
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        fetchMapAddress(results[0],map.getCenter());
                        $('#js-next-btn3').prop('disabled', false);
                    }
                }
            });
            $('.verify-map-pin').removeClass('moving');
            $('.verify-map-pin').addClass('unset');
        });

        map.addListener('zoom_changed', function() {
            geocoder.geocode({
                'latLng': map.getCenter()
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        fetchMapAddress(results[0],map.getCenter());
                    }
                }
            });
        });

        map.addListener('drag', function() {
            $('.verify-map-pin').removeClass('unset');
            $('.verify-map-pin').addClass('moving');
        });

    }

    function fetchMapAddress(data,map_center = '')
    {
        if (data['types'] == 'street_address')
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
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];

                if (addressType == 'street_number')
                    $scope.street_number = val;
                if (addressType == 'route') {
                    var street_address = $scope.street_number + ' ' + val;
                    $('#address_line_1').val($.trim(street_address));
                }
                if (addressType == 'postal_code')
                    $('#postal_code').val(val);
                if (addressType == 'locality')
                    $('#city').val(val);
                if (addressType == 'administrative_area_level_1')
                    $('#state').val(val);
                if (addressType == 'country')
                    $('#country').val(val);
            }
        }

        var address = $('#address_line_1').val();
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        /*if($('#address_line_1').val() == '')
            $('#address_line_1').val($('#city').val());*/

        if ($('#state').val() == '' || $('#city').val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);
        if(map_center != '') {
            $scope.latitude = map_center.lat();
            $scope.longitude = map_center.lng();
        }
        else {
            $scope.latitude = latitude;
            $scope.longitude = longitude;
        }
    }

    $(document).on('click', '[name="amenities"]', function() {
        var value = '';
        $('[name="amenities"]').each(function() {
            if ($(this).prop('checked') == true) {
                value = value + $(this).val() + ',';
            }
        });

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_amenities', {
            data: value
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();
            } else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
            }
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    });

    $(document).on('click', '#photo-uploader', function() {
        $('#upload_photos').trigger('click');
    });

    $(document).on('click', '#js-photo-grid-placeholder', function() {
        $('#upload_photos2').trigger('click');
    });

    $scope.featured_image = function(index, photo_id) {
        $http.post('featured_image', {
            id: $('#room_id').val(),
            photo_id: photo_id
        }).then(function(response) {
            $scope.photos_list = response.data;


        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    };

    function upload() {
        upload2();
        $(document).on("change", '#upload_photos', function() {
            $('#js-photo-grid').append('<li class="col-lg-4 col-md-6 row-space-4"><div class=" photo-item"><div class=="photo-size photo-drag-target js-photo-link"></div></div></li>');
            var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';
            $("#js-photo-grid li").last().append(loading);
            jQuery.ajaxFileUpload({
                url: "../../add_photos/" + $('#room_id').val(),
                secureuri: false,
                fileElementId: "upload_photos",
                dataType: "json",
                async: false,
                success: function(response) {
                    // response = JSON.parse(response);
                    //console.log(response.error['error_title']);
                    //console.log(response.succresult);
                    $("#js-photo-grid #js-manage-listing-content-container").remove();
                    $('#js-photo-grid li:last-child').remove();

                    if (response.error['error_title']) {
                        $('#js-error .panel-header').text(response.error['error_title']);
                        $('#js-error .panel-body').text(response.error['error_description']);
                        $('.js-delete-photo-confirm').addClass('hide');
                        $('#js-error').attr('aria-hidden', false);
                    }
                    if (response.succresult && response.succresult != '') {
                        $scope.$apply(function() {
                            $scope.photos_list = response.succresult;
                            $('#photo_count').css('display', 'block');
                            $('#steps_count').text(response.succresult[0].steps_count);
                            $scope.steps_count = response.succresult[0].steps_count;
                        });
                        document.getElementById('upload_photos').value='';
                    }
                    // upload();
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if(textStatus == 'error') {
                        $("#js-photo-grid #js-manage-listing-content-container").remove();
                        $('#js-photo-grid li:last-child').remove();

                        $('#js-error .panel-header').text('Photo Error');
                        $('#js-error .panel-body').text('Error uploading image! Please try uploading a different image!!!');
                        $('.js-delete-photo-confirm').addClass('hide');
                        $('#js-error').attr('aria-hidden', false);
                    }
                }
            });
        });
    }


    function upload2() {
        $(document).on("change", '#upload_photos2', function() {
            var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';
            $("#js-photo-grid").append(loading);
            jQuery.ajaxFileUpload({
                url: "../../add_photos/" + $('#room_id').val(),
                secureuri: false,
                fileElementId: "upload_photos2",
                dataType: "json",
                async: false,
                success: function(response) {
                    $("#js-photo-grid #js-manage-listing-content-container").remove();
                    if (response.error_title) {
                        $('#js-error .panel-header').text(response.error_title);
                        $('#js-error .panel-body').text(response.error_description);
                        $('.js-delete-photo-confirm').addClass('hide');
                        $('#js-error').attr('aria-hidden', false);

                    } else {
                        $scope.$apply(function() {
                            $scope.photos_list = response;
                            $('#photo_count').css('display', 'block');
                            $('#steps_count').text(response[0].steps_count);
                            $scope.steps_count = response[0].steps_count;
                        });

                        $('#upload_photos2').reset();
                    }
                    upload2();
                }
            });
        });
    }

    function photos_list() {
        $http.get('photos_list', {}).then(function(response) {
            $scope.photos_list = response.data;
            if (response.data.length > 0) {
                $('#photo_count').css('display', 'block');
            }
        });
    }

    upload();

    /* ajaxfileupload */
    jQuery.extend({
        handleError: function(s, xhr, status, e) {
            if (s.error) s.error(xhr, status, e);
            else if (xhr.responseText) console.log(xhr.responseText);
        }
    });
    jQuery.extend({
        createUploadIframe: function(e, t) {
            var r = "jUploadFrame" + e;
            if (window.ActiveXObject) {
                var n = document.createElement("iframe");
                n.id = n.name = r, "boolean" == typeof t ? n.src = "javascript:false" : "string" == typeof t && (n.src = t)
            } else {
                var n = document.createElement("iframe");
                n.id = r, n.name = r
            }
            return n.style.position = "absolute", n.style.top = "-1000px", n.style.left = "-1000px", document.body.appendChild(n), n
        },
        createUploadForm: function(e, t) {
            var r = "jUploadForm" + e,
                n = "jUploadFile" + e,
                o = jQuery('<form  action="" method="POST" name="' + r + '" id="' + r + '" enctype="multipart/form-data"></form>'),
                a = jQuery("#" + t),
                u = jQuery(a).clone();
            return jQuery(a).attr("id", n), jQuery(a).before(u), jQuery(a).appendTo(o), jQuery(o).css("position", "absolute"), jQuery(o).css("top", "-1200px"), jQuery(o).css("left", "-1200px"), jQuery(o).appendTo("body"), o
        },
        ajaxFileUpload: function(e) {
            e = jQuery.extend({}, jQuery.ajaxSettings, e);
            var t = (new Date).getTime(),
                r = jQuery.createUploadForm(t, e.fileElementId),
                n = (jQuery.createUploadIframe(t, e.secureuri), "jUploadFrame" + t),
                o = "jUploadForm" + t;
            e.global && !jQuery.active++ && jQuery.event.trigger("ajaxStart");
            var a = !1,
                u = {};
            e.global && jQuery.event.trigger("ajaxSend", [u, e]);
            var c = function(t) {
                var o = document.getElementById(n);
                try {
                    o.contentWindow ? (u.responseText = o.contentWindow.document.body ? o.contentWindow.document.body.innerHTML : null, u.responseXML = o.contentWindow.document.XMLDocument ? o.contentWindow.document.XMLDocument : o.contentWindow.document) : o.contentDocument && (u.responseText = o.contentDocument.document.body ? o.contentDocument.document.body.innerHTML : null, u.responseXML = o.contentDocument.document.XMLDocument ? o.contentDocument.document.XMLDocument : o.contentDocument.document)
                } catch (c) {
                    jQuery.handleError(e, u, null, c)
                }
                if (u || "timeout" == t) {
                    a = !0;
                    var d;
                    try {
                        if (d = "timeout" != t ? "success" : "error", "error" != d) {
                            var l = jQuery.uploadHttpData(u, e.dataType);
                            e.success && e.success(l, d), e.global && jQuery.event.trigger("ajaxSuccess", [u, e])
                        } else jQuery.handleError(e, u, d)
                    } catch (c) {
                        d = "error", jQuery.handleError(e, u, d, c)
                    }
                    e.global && jQuery.event.trigger("ajaxComplete", [u, e]), e.global && !--jQuery.active && jQuery.event.trigger("ajaxStop"), e.complete && e.complete(u, d), jQuery(o).unbind(), setTimeout(function() {
                        try {
                            jQuery(o).remove(), jQuery(r).remove()
                        } catch (t) {
                            jQuery.handleError(e, u, null, t)
                        }
                    }, 100), u = null
                }
            };
            e.timeout > 0 && setTimeout(function() {
                a || c("timeout")
            }, e.timeout);
            try {
                var r = jQuery("#" + o);
                jQuery(r).attr("action", e.url), jQuery(r).attr("method", "POST"), jQuery(r).attr("target", n), r.encoding ? r.encoding = "multipart/form-data" : r.enctype = "multipart/form-data", jQuery(r).submit()
            } catch (d) {
                jQuery.handleError(e, u, null, d)
            }
            return window.attachEvent ? document.getElementById(n).attachEvent("onload", c) : document.getElementById(n).addEventListener("load", c, !1), {
                abort: function() {}
            }
        },
        uploadHttpData: function(r, type) {
            var data = !type;
            return data = "xml" == type || data ? r.responseXML : r.responseText, "script" == type && jQuery.globalEval(data), "json" == type && eval("data = " + data), "html" == type && jQuery("<div>").html(data).evalScripts(), data
        }
    });

    $scope.delete_photo = function(item, id, delete_photo, delete_message) {
        //$('#js-error .panel-header').text(delete_descrip);
        $('#js-error .panel-header').text(delete_photo);
        $('#js-error .panel-body').text(delete_message);
        $('.js-delete-photo-confirm').removeClass('hide');
        $('#js-error').attr('aria-hidden', false);
        $('.js-delete-photo-confirm').attr('data-id', id);
        var index = $scope.photos_list.indexOf(item);
        $('.js-delete-photo-confirm').attr('data-index', index);
    };

    $(document).on('click', '.js-delete-photo-confirm', function() {
        var index = $(this).attr('data-index');
        $http.post('delete_photo', {
            photo_id: $(this).attr('data-id')
        }).then(function(response) {
            if (response.data.success == 'true') {
                $scope.photos_list.splice(index, 1);
                $('#js-error').attr('aria-hidden', true);
                // photos_list();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;

            } else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
            }
            
            if ($scope.photos_list != undefined) {
                if ($scope.photos_list.length != 0) {
                    $('[data-track="photos"] a div div .transition').removeClass('visible');
                    $('[data-track="photos"] a div div .transition').addClass('hide');
                    $('[data-track="photos"] a div div div .icon-ok-alt').removeClass('hide');
                } else {
                    $('[data-track="photos"] a div div .transition').removeClass('hide');
                    $('[data-track="photos"] a div div div .icon-ok-alt').addClass('hide');
                }
            }
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    });

    $scope.$watch('photos_list', function(value) {

        if ($scope.photos_list != undefined) {
            if ($scope.photos_list.length != 0) {
                $('[data-track="photos"] a div div .transition').removeClass('visible');
                $('[data-track="photos"] a div div .transition').addClass('hide');
                $('[data-track="photos"] a div div div .icon-ok-alt').removeClass('hide');
            } else {
                $('[data-track="photos"] a div div .transition').removeClass('hide');
                $('[data-track="photos"] a div div div .icon-ok-alt').addClass('hide');
            }
        }
    });

    $scope.$watch('steps_count', function(value) {

        if ($scope.steps_count != undefined) {
            rooms_status = $('#room_status').val();
            if ($scope.steps_count == 0) {
                $('#finish_step').hide();
                $('.js-steps-remaining').addClass('hide');
                $('.js-steps-remaining').removeClass('show');
                if(!rooms_status)
                {
                    $('.listing-nav-sm').addClass('collapsed');
                    $('body').addClass('non_scrl');
                }

                $('#js-list-space-button').css('display', '');
                $('#js-list-space-tooltip').attr('aria-hidden', 'false');
                setTimeout(function() {
                    $('#js-list-space-tooltip').attr('aria-hidden', 'true');
                }, 3000);
                $('#js-list-space-tooltip').css({
                    'opacity': '1'
                });
                $('#js-list-space-tooltip').removeClass("animated").addClass("animated");
            } else {
                $('#finish_step').show();
                $('.js-steps-remaining').removeClass('hide');
                $('.js-steps-remaining').addClass('show');
                if(!rooms_status)
                {
                    $('.listing-nav-sm').removeClass('collapsed');
                    $('body').removeClass('non_scrl');
                }

                $('#js-list-space-button').css('display', 'none');
                $('#js-list-space-tooltip').attr('aria-hidden', 'true');
                $('#js-list-space-tooltip').css({
                    'opacity': '0'
                    //'top': '470px',
                    //'bottom': '175px',
                   // 'left': '80px'
                });
            }
        }
    });

    /*$scope.over_first_photo = function(index)
    {
        if(index == 0)
        $('#js-first-photo-text').removeClass('invisible');
    };

    $scope.out_first_photo = function(index)
    {
        if(index == 0)
        $('#js-first-photo-text').addClass('invisible');
    };
    */
    $scope.keyup_highlights = function(id, value) {
        $http.post('photo_highlights', {
            photo_id: id,
            data: value
        }).then(function(response) {
            if (response.data.redirect != '' && response.data.redirect != undefined) {
                window.location = response.data.redirect;
            }

        });
    };

    $('#href_photos').click(function() {
        photos_list();
        $('#js-manage-listing-nav').removeClass('manage-listing-nav');
        $('#js-manage-listing-nav').addClass('pos-abs');

        $('#ajax_container').addClass('mar-left-cont');
    });

    $(document).on('change', '[id^="price-select-"]', function() {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        data_params['night'] = $('#price-night').val();
        data_params['currency_code'] = $('#price-select-currency_code').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_price', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                if(response.data.night_price){
                    $('#price-night').val(response.data.night_price);
                }    
                $('[data-error="price"]').text('');
                if ($('#price-week').val() < response.data.min_amt) {
                    $('[data-error="week"]').removeClass('hide');
                    $('[data-error="week"]').html(response.data.msg);
                } else {
                    $('[data-error="week"]').addClass('hide');
                    $('[data-error="week"]').text('');
                }
                if ($('#price-month').val() < response.data.min_amt) {
                    $('[data-error="month"]').removeClass('hide');
                    $('[data-error="month"]').html(response.data.msg);
                } else {
                    $('[data-error="month"]').addClass('hide');
                    $('[data-error="month"]').text('');
                }
                $('[data-error="weekly_price"]').text('');
                $('[data-error="monthly_price"]').text('');
                $('.input-prefix').html(response.data.currency_symbol);
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
            } else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
                $('.input-prefix').html(response.data.currency_symbol);
                $('[data-error="price"]').html(response.data.msg);
                if ($('#price-week').val() < response.data.min_amt) {
                    $('[data-error="week"]').removeClass('hide');
                    $('[data-error="week"]').html(response.data.msg);
                } else {
                    $('[data-error="week"]').addClass('hide');
                    $('[data-error="week"]').text('');
                }
                if ($('#price-month').val() < response.data.min_amt) {
                    $('[data-error="month"]').removeClass('hide');
                    $('[data-error="month"]').html(response.data.msg);
                } else {
                    $('[data-error="month"]').addClass('hide');
                    $('[data-error="month"]').text('');
                }
                $('.' + saving_class).fadeOut();
            }
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    });

    $(document).on('blur', '.autosubmit-text[id^="price-"]', function() {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        this_val = Math.round($(this).val());
        $(this).val(this_val);
        data_params['currency_code'] = $('#price-select-currency_code').val();
        if ($(this).attr('name') == 'additional_guest') {
            data_params['guests'] = $('#price-select-guests_included').val();
        }
        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');
        var error_class = 'price';
        if ($(this).attr('name') != 'night') {
            error_class = $(this).attr('name');
        }
        $('.' + saving_class + ' h5').text('Saving...');

        if ($('#price-night').val() != 0) {
            $('.' + saving_class).fadeIn();
            $http.post('update_price', {
                data: data
            }).then(function(response) {
                if (response.data.success == 'true') {
                    $('[data-error="' + error_class + '"]').text('');
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.' + saving_class + ' h5').text('Saved!');
                    $('.' + saving_class).fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                } else {
                    if (response.data.redirect != '' && response.data.redirect != undefined) {
                        window.location = response.data.redirect;
                    }
                    if (response.data.attribute != '' && response.data.attribute != undefined) {
                        $('[data-error="' + response.data.attribute + '"]').removeClass('hide');
                        $('[data-error="' + response.data.attribute + '"]').html(response.data.msg);
                        $('.input-prefix').html(response.data.currency_symbol);
                    } else {
                        $('[data-error="price"]').html(response.data.msg);
                    }
                    $('.' + saving_class).fadeOut();
                }
                if ($('#price-night').val() != 0) {
                    $('#price-night-old').val($('#price-night').val());
                    if (!response.data.msg) {
                        $('[data-track="pricing"] a div div .transition').removeClass('visible');
                        $('[data-track="pricing"] a div div .transition').addClass('hide');
                        $('[data-track="pricing"] a div div div .icon-ok-alt').removeClass('hide');
                    }
                }
            }, function(response) {
                if (response.status == '300')
                    window.location = APP_URL + '/login';
            });
        } else {
            if ($('#price-night-old').val() == 0) {
                $('#price-night').val($('#price-night-old').val());
                $('[data-track="pricing"] a div div .transition').removeClass('hide');
                $('[data-track="pricing"] a div div div .icon-ok-alt').addClass('hide');
            } else {
                $('#price-night').val($('#price-night-old').val());
                $('[data-track="pricing"] a div div .transition').removeClass('visible');
                $('[data-track="pricing"] a div div .transition').addClass('hide');
                $('[data-track="pricing"] a div div div .icon-ok-alt').removeClass('hide');
            }
            if ($('#price-night').val() == 0) {
            $('.' + saving_class).fadeIn();
            $http.post('update_price', {
                data: data
            }).then(function(response) {
                if (response.data.success == 'true') {
                    $('[data-error="' + error_class + '"]').text('');
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.' + saving_class + ' h5').text('Saved!');
                    $('.' + saving_class).fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                } else {
                    if (response.data.redirect != '' && response.data.redirect != undefined) {
                        window.location = response.data.redirect;
                    }
                    if (response.data.attribute != '' && response.data.attribute != undefined) {
                        $('[data-error="' + response.data.attribute + '"]').removeClass('hide');
                        $('[data-error="' + response.data.attribute + '"]').html(response.data.msg);
                        $('.input-prefix').html(response.data.currency_symbol);
                    } else {
                        $('[data-error="price"]').html(response.data.msg);
                    }
                    $('.' + saving_class).fadeOut();
                }
            });
        }
        }
    });

    $(document).on('change', '[id$="_checkbox"]', function() {
        if ($(this).prop('checked') == false) {
            var data_params = {};

            var id = $(this).attr('id');
            var selector = '[data-checkbox-id="' + id + '"] > div > div > div > input';

            $(selector).val('');

            if (id == 'price_for_extra_person_checkbox') {
                $('[data-checkbox-id="' + id + '"] > div > div > #guests-included-select > div > select').val(1);

                data_params[$('[data-checkbox-id="' + id + '"] > div > div > #guests-included-select > div > select').attr('name')] = 0;
            }

            data_params[$(selector).attr('name')] = $(selector).val();
            data_params['currency_code'] = $('#price-select-currency_code').val();

            var data = JSON.stringify(data_params);

            var saving_class = $(selector).attr('data-saving');

            $('.' + saving_class + ' h5').text('Saving...');
            $('.' + saving_class).fadeIn();

            $http.post('update_price', {
                data: data
            }).then(function(response) {
                if (response.data.success == 'true') {
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.' + saving_class + ' h5').text('Saved!');
                    $('.' + saving_class).fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                }
            }, function(response) {
                if (response.status == '300')
                    window.location = APP_URL + '/login';
            });
        }
    });

    $(document).on('click', '[id^="available-"]', function() {
        var data_params = {};

        var value = $(this).attr('data-slug');

        data_params['calendar_type'] = value.charAt(0).toUpperCase() + value.slice(1);;

        var data = JSON.stringify(data_params);

        $('.saving-progress h5').text('Saving...');

        $('.saving-progress').fadeIn();

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                $scope.selected_calendar = value;
                $('[data-slug="' + value + '"]').addClass('selected');
                $('.saving-progress h5').text('Saved!');
                $('.saving-progress').fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
            } else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
            }
            $('[data-track="calendar"] a div div .transition').removeClass('visible');
            $('[data-track="calendar"] a div div .transition').addClass('hide');
            $('[data-track="calendar"] a div div .pull-right .nav-icon').removeClass('hide');
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    });

    $(document).on('mouseover', '[id^="available-"]', function() {
        $('[id^="available-"]').removeClass('selected');
    });

    $(document).on('mouseout', '[id^="available-"]', function() {
        $('[id="available-' + $scope.selected_calendar + '"]').addClass('selected');
    });

    var ex_pathname = (window.location.href).split('/');
    $scope.step = $(ex_pathname).get(-1);
    photos_list();

    $(document).on('click', '#finish_step', function() {
        $http.get('rooms_steps_status', {}).then(function(response) {
            for (var key in response.data) {
                if (response.data[key] == '0') {
                    angular.element('#href_' + key).trigger('click');
                    //$('#href_'+key).trigger('click');
                    return false;
                }
            }
        });
    });

    $(document).on('click', '#js-list-space-button', function() {
        var data_params = {};

        data_params['status'] = 'Listed';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            $http.get('rooms_data', {}).then(function(response) {
                $('#symbol_finish').html(response.data.symbol);
                $scope.popup_photo_name = response.data.photo_name;
                $scope.popup_night = response.data.night;
                $scope.popup_room_name = response.data.name;
                $scope.popup_room_type_name = response.data.room_type_name;
                $scope.popup_property_type_name = response.data.property_type_name;
                $scope.popup_state = response.data.state;
                $scope.popup_country = response.data.country_name;
                $scope.popup_price = response.data.popup_price;
                $('.finish-modal').attr('aria-hidden', 'false');
                $('.finish-modal').removeClass('hide');
            });
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    });

    $(document).on('blur', '[id^="help-panel"] > textarea', function() {
        var data_params = {};

        var input_name = $(this).attr('name');
        var current_tab = $('#current_tab_code').val();
        data_params[input_name] = $(this).val();  
        var data = JSON.stringify(data_params);
        $('.saving-progress h5').text('Saving...');

        if (input_name != 'neighborhood_overview' && input_name != 'transit')
            $('.help-panel-saving').fadeIn();
        else
            $('.help-panel-neigh-saving').fadeIn();

        $http.post('update_description', {
            data: data , current_tab:current_tab
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('.saving-progress h5').text('Saved!');

                if (input_name != 'neighborhood_overview' && input_name != 'transit')
                    $('.help-panel-saving').fadeOut();
                else
                    $('.help-panel-neigh-saving').fadeOut();
            } else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
            }
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    });

    $(document).on('click', '#collapsed-nav', function() {
        if ($('#js-manage-listing-nav').hasClass('collapsed'))
            $('#js-manage-listing-nav').removeClass('collapsed');
        else
            $('#js-manage-listing-nav').addClass('collapsed');
    });

    $(document).on('click', '.month-nav', function() {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;

        var data = JSON.stringify(data_params);

        $('.ui-datepicker-backdrop').removeClass('hide');
        $('.spinner-next-to-month-nav').addClass('loading');

        $http.post($(this).attr('href').replace('manage-listing', 'ajax-manage-listing'), {
            data: data
        }).then(function(response) {
            $("#ajax_container").html($compile(response.data)($scope));
            $('.spinner-next-to-month-nav').removeClass('loading');
            $('.ui-datepicker-backdrop').addClass('hide');
        });
        return false;
    });

    $(document).on('change', '#calendar_dropdown', function() {
        var year_month = $(this).val();
        var year = year_month.split('-')[0];
        var month = year_month.split('-')[1];

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;

        var data = JSON.stringify(data_params);

        $('.ui-datepicker-backdrop').removeClass('hide');
        $('.spinner-next-to-month-nav').addClass('loading');

        $http.post($(this).attr('data-href').replace('manage-listing', 'ajax-manage-listing'), {
            data: data
        }).then(function(response) {
            $('.ui-datepicker-backdrop').addClass('hide');
            $('.spinner-next-to-month-nav').removeClass('loading');
            $("#ajax_container").html($compile(response.data)($scope));
            init_daterangepicker();
        });
        return false;
    });

    /*Start - Calendar Date Selection*/

    $(document).on('click', '.tile', function() {
        if (!$(this).hasClass('other-day-selected') && !$(this).hasClass('selected') && !$(this).hasClass('tile-previous')) {
            var current_tile = $(this).attr('id');

            $('#' + current_tile).addClass('first-day-selected last-day-selected selected');
            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + current_tile + '> .date');

            var clicked_li = $(this).index();

            var start_top = $(this).position().top + 36,
                start_left = $(this).position().left - 5,
                end_top = start_top + 5,
                end_left = start_left + 85;

            //$('.tile-selection-handle').removeClass('hide');
            $('.days-container').append('<div><div style="left:' + start_left + 'px;top:' + start_top + 'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: ' + end_left + 'px; top: ' + end_top + 'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');


            $('.tile').each(function() {
                if (current_tile != $(this).attr('id'))
                    $(this).addClass('other-day-selected');
            });

            calendar_edit_form();
        } else {
            if (!$(this).hasClass('selected')) {

                $('.first-day-selected').removeClass('first-day-selected')
                $('.last-day-selected').removeClass('last-day-selected');
                $('.selected').removeClass('selected');
                $('.tile-selection-container').remove();
                $('.tile-selection-handle').parent().remove();
                $('.other-day-selected').removeClass('other-day-selected');
                $('.calendar-edit-form').addClass('hide');

            }
        }
    });
    $(document).on('mouseup', '.tile-selection-handle, .tile', function() {
        selected_li_status = 0;

        var last_id = $('.selected').last().attr('id');
        var first_id = $('.selected').first().attr('id');

        $('*').removeClass('first-day-selected last-day-selected');
        $('.selected').first().addClass('first-day-selected');
        $('.selected').last().addClass('last-day-selected');

        var position = $('#' + last_id).position();
        var first_position = $('#' + first_id).position();

        if (position != undefined && first_position != undefined) {
            var start_top = first_position.top + 35,
                start_left = first_position.left - 5,
                end_top = position.top + 40,
                end_left = position.left + 80;
        


        $('.days-container > div > .tile-selection-handle:last').remove();
        $('.days-container > div > .tile-selection-handle:first').remove();

        $('.days-container').append('<div><div style="left:' + start_left + 'px;top:' + start_top + 'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: ' + end_left + 'px; top: ' + end_top + 'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');

        calendar_edit_form();
        }
    });


    var selected_li_status = 0;
    var direction = '';

    $(document).on('mousedown', '.tile-selection-handle', function() {
        selected_li_status = 1;
        if ($(this).hasClass('tile-handle-left'))
            direction = 'left';
        else
            direction = 'right';
    });

    var oldx = 0;
    var oldy = 0;

    $(document).on('mouseover', '.tile', function(e) {
        if (e.pageX > oldx && direction == 'right') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container')) {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++) {
                    var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                    if (classd.includes("tile-previous") == false) {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                        }
                    } else
                        return false;
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        } else if (e.pageX < oldx && direction == 'right') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#' + id).removeClass('selected');
                $('#' + id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#' + id2 + ' > div.tile-selection-container').remove();
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }

            if ($('.selected').length == 0) {
                selected_li_status = 0;
                $('.tile').each(function() {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if (e.pageX > oldx && direction == 'left') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#' + id).removeClass('selected');
                $('#' + id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#' + id2 + ' > div.tile-selection-container').remove();
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        } else if (e.pageX < oldx && direction == 'left') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().attr('id');

                $('#' + id).addClass('selected');
                $('#' + id).removeClass('other-day-selected');
                $(this).addClass('selected');
                $(this).removeClass('other-day-selected');

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                for (var i = (first_index + 1); i <= last_index; i++) {
                    var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                    if (classd.includes("tile-previous") == false) {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                        }
                        $('#' + id).removeClass('first-day-selected last-day-selected');
                    } else
                        return false;

                }

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }

            if ($('.selected').length == 0) {
                selected_li_status = 0;
                $('.tile').each(function() {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if (e.pageY > oldy && direction == 'right') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container')) {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++) {
                    var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                    if (classd.includes("tile-previous") == false) {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                        }
                    } else
                        return false;
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        }

        if (e.pageY < oldy && direction == 'right') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                if (!$(this).hasClass('selected')) {
                    var id = $(this).attr('id');
                    var last_index = $(this).index();
                    var first_index = $('.selected').first().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for (var i = (first_index + 1); i <= (last_index + 1); i++) {
                        var idsd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                        console.log(idsd);
                        for (var i = (first_index + 1); i <= (last_index + 1); i++) {
                            var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                            if (classd.includes("tile-previous") == false) {
                                var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                                $('#' + id).addClass('selected');
                                $('#' + id).removeClass('other-day-selected');

                                if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                                }
                            } else
                                return false;
                        }
                    }
                    $('*').removeClass('first-day-selected last-day-selected');
                    $('.selected').first().addClass('first-day-selected');
                    $('.selected').last().addClass('last-day-selected');
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        }

        if (e.pageY < oldy && direction == 'left') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container')) {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++) {
                    var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                    if (classd.includes("tile-previous") == false) {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                        }
                    } else
                        return false;
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        }

        if (e.pageY > oldy && direction == 'left') {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous')) {
                if (!$(this).hasClass('selected')) {
                    var id = $(this).attr('id');
                    var first_index = $(this).index();
                    var last_index = $('.selected').last().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for (var i = (first_index + 1); i <= (last_index + 1); i++) {
                        var classd = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('class');
                        if (classd.includes("tile-previous") == false) {
                            var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                            $('#' + id).addClass('selected');
                            $('#' + id).removeClass('other-day-selected');

                            if (!$('#' + id + ' > div').hasClass('tile-selection-container')) {
                                $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                            }
                        } else
                            return false;
                    }
                    $('*').removeClass('first-day-selected last-day-selected');
                    $('.selected').first().addClass('first-day-selected');
                    $('.selected').last().addClass('last-day-selected');
                }
            } else if ($(this).hasClass('tile-previous')) {
                $(this).trigger('mouseup');
            }
        }
        oldx = e.pageX;
        oldy = e.pageY;
    });


    function calendar_edit_form() {
        $('.calendar-edit-form').removeClass('hide');

        if ($('.selected').length > 1) {
            $('.calendar-edit-form > form > .panel-body').first().show();
        } else {
            $('.calendar-edit-form > form > .panel-body').first().hide();
        }

        if ($('.selected').hasClass('status-b')) {
            $scope.$apply(function() {
                $scope.segment_status = 'not available';
            });
            // $scope.segment_status = 'not available';
            $('#unavi').addClass("segmented-control__option--selected");
            $('#avi').removeClass("segmented-control__option--selected");
            $('#avi').removeClass("segmented-control__option--selected");
        } else {
            $scope.$apply(function() {
                $scope.segment_status = 'available';
            });
            // $scope.segment_status = 'available';
            $('#avi').addClass("segmented-control__option--selected");
            $('#unavi').removeClass("segmented-control__option--selected");
            $('#unavi').removeClass("segmented-control__option--selected");
        }

        $('#calendar-edit-end').val('');
        $('#calendar-edit-start').val('');
        var start_date = $('.first-day-selected').first().attr('id');
        var end_date = $('.last-day-selected').last().attr('id');
        //alert(start_date); alert(end_date);
        $scope.calendar_edit_price = $('#' + start_date).find('.price > span:last').text() - 0;
        $('.sidebar-price').val($scope.calendar_edit_price);
        if($('#' + start_date).find('.tile-notes-text').text() == null){
            $scope.notes = '';
        } else {
            $scope.notes = $('#' + start_date).find('.tile-notes-text').text();
        }
        $scope.isAddNote = ($scope.notes != '') ? true : false;
        if (start_date != end_date) {
            $("#calendar-edit-start").datepicker({
                 dateFormat: datepicker_format,
                 minDate: 0,
                 onSelect: function (date,obj) 
                 {
                    var selected_month = obj.selectedMonth + 1;
                    var start_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('#calendar-start').val(start_formatted_date);
                    var checkout = $("#calendar-edit-start").datepicker('getDate');
                    $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                    // $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                    setTimeout(function(){
                        $('#calendar-edit-end').datepicker("show");
                    },20);
                 }
            });

            $('#calendar-edit-end').datepicker({
                 dateFormat: datepicker_format,
                 minDate: 1,
                 onClose: function () 
                 {
                     var checkin = $("#calendar-edit-start").datepicker('getDate');
                     var checkout = $('#calendar-edit-end').datepicker('getDate');
                     $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                     // $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                     if (checkout <= checkin) 
                     {
                         var minDate = $('#calendar-edit-end').datepicker('option', 'minDate');
                         $('#calendar-edit-end').datepicker('setDate', minDate);
                     }
                 },
                 onSelect: function (date,obj) 
                 {
                    var selected_month = obj.selectedMonth + 1;
                    var end_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('#calendar-end').val(end_formatted_date);
                    var checkout = $("#calendar-edit-start").datepicker('getDate');
                    $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                    // $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                    setTimeout(function(){
                        // $('#calendar-edit-end').datepicker("show");
                    },20);
                 }
            });
            var s_date = new Date(start_date);
            var e_date = new Date(end_date);

            $("#calendar-edit-start").datepicker('setDate',s_date);
            $("#calendar-edit-end").datepicker('setDate',e_date);
            $('#calendar-start').val(change_format(start_date));
            $('#calendar-end').val(change_format(end_date));
        } else {
            
            var s_date = new Date(start_date);
            var e_date = new Date(end_date);

            $("#calendar-edit-start").datepicker('setDate',s_date);
            $("#calendar-edit-end").datepicker('setDate',e_date);
            $('#calendar-start').val(change_format(start_date));
            $('#calendar-end').val(change_format(end_date));
        }
    }

    function change_format(date) {
        if (date != undefined) {
            var split_date = date.split('-');
            return split_date[2] + '-' + split_date[1] + '-' + split_date[0];
        }
    }

    $scope.calendar_edit_submit = function(href) {
        $scope.calendar_edit_price = $(".get_price ").val() - 0;

        $http.post('calendar_edit', {
            status: $scope.segment_status,
            start_date: $('#calendar-start').val(),
            end_date: $('#calendar-end').val(),
            price: $scope.calendar_edit_price,
            notes: $scope.notes
        }).then(function(response) {
            $scope.notes = '';
            var year_month = $('#calendar_dropdown').val();
            var year = year_month.split('-')[0];
            var month = year_month.split('-')[1];

            var data_params = {};

            data_params['month'] = month;
            data_params['year'] = year;

            var data = JSON.stringify(data_params);

            $('.ui-datepicker-backdrop').removeClass('hide');
            $('.spinner-next-to-month-nav').addClass('loading');

            $http.post(href.replace('manage-listing', 'ajax-manage-listing'), {
                data: data
            }).then(function(response) {
                $('.ui-datepicker-backdrop').addClass('hide');
                $('.spinner-next-to-month-nav').removeClass('loading');
                $("#ajax_container").html($compile(response.data)($scope));
            });
            return false;
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    };

    /*End - Calendar Date Selection*/

    $(document).on('change', '#availability-dropdown > div > select', function() {
        var data_params = {};

        data_params['status'] = $(this).val();

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (data_params['status'] == 'Unlisted') {
                $('#availability-dropdown > i').addClass('dot-danger');
                $('#availability-dropdown > i').removeClass('dot-success');
            } else if (data_params['status'] == 'Listed') {
                $('#availability-dropdown > i').removeClass('dot-danger');
                $('#availability-dropdown > i').addClass('dot-success');
            }
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    });

    $(document).on('click', '#export_button', function() {
        $('#export_popup').attr('aria-hidden', 'false');
    });

    $(document).on('click', '#import_button', function() {
        $('#import_popup').attr('aria-hidden', 'false');
    });

    $(document).on('click', '.remove_sync_button', function() {
        $('#remove_sync_popup').attr('aria-hidden', 'false');
        $('.remove_sync_cal_container').addClass('loading');

        $http.post(APP_URL+'/get_sync_calendar', {
            room_id: $('#room_id').val()
        }).then(function(response) {
            $scope.sync_cal_details = response.data;
            $('.remove_sync_cal_container').removeClass('loading');
        });
    });

    $scope.show_confirm_popup = function(ical_id) {
        $('#remove_sync_popup').attr('aria-hidden', 'true');
        $('#remove_sync_confirm_popup').attr('aria-hidden', 'false');
        $('.remove_ical_link').attr('data-ical_id', ical_id);
    }

    $scope.remove_sync_cal = function() {
        $('.remove_sync_confirm_panel').addClass('loading');
        var ical_id = $('.remove_ical_link').attr("data-ical_id");
        $http.post(APP_URL+'/remove_sync_calendar', {
            ical_id: ical_id
        }).then(function(response) {
            $('.remove_sync_confirm_panel').removeClass('loading');
            $('.modal').attr('aria-hidden', 'true');
        });
    };

    $scope.booking_select = function(value) {
        var data_params = {};

        data_params['booking_type'] = value;

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('#before_select').addClass('hide');
                $('#' + value).removeClass('hide');
            }
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    }

    $scope.booking_change = function(value) {
        var data_params = {};

        data_params['booking_type'] = '';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {
            data: data
        }).then(function(response) {
            if (response.data.success == 'true') {
                $('#before_select').removeClass('hide');
                $('#' + value).addClass('hide');
            }
        }, function(response) {
            if (response.status == '300'){
                window.location = APP_URL + '/login';
            } else if (response.status == '500'){
                window.location.reload();
            }
        });
    }

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
            errors = $scope.ls_errors;
        }
        else if(type == 'early_bird') {
            item =$scope.early_bird_items[index];
            $scope.early_bird_items.splice(index, 1);
            errors = $scope.eb_errors;
        }
        else if(type == 'last_min') {
            item =$scope.last_min_items[index];
            $scope.last_min_items.splice(index, 1);
            errors = $scope.lm_errors;
        }
        errors[index] = [];
        if(item.id != '' && item.id) {
            $('#js-'+type+'_wrapper').addClass('loading');
            $http.post('delete_price_rule/'+item.id, {}).then(function(response){
                $('#js-'+type+'_wrapper').removeClass('loading');
            })
        }
    }
    $scope.length_of_stay_option_avaialble = function(option) {
        var found = $filter('filter')($scope.length_of_stay_items, {'period': option}, true);
        var found_text = $filter('filter')($scope.length_of_stay_items, {'period': ''+option}, true);
        return !found.length && !found_text.length;
    }
    $(document).on('change', '.ls_period, .ls_discount', function(){
        index = $(this).attr('data-index');
        $scope.update_price_rules('length_of_stay', index);
    });
    $(document).on('change', '.eb_period, .eb_discount', function(){
        index = $(this).attr('data-index');
        $scope.update_price_rules('early_bird', index);
    });
    $(document).on('change', '.lm_period, .lm_discount', function(){
        index = $(this).attr('data-index');
        $scope.update_price_rules('last_min', index);
    });
    $scope.update_price_rules = function(type, index) {
        
        if(type == 'length_of_stay') {
            rules = $scope.length_of_stay_items;
            errors = $scope.ls_errors;
        }
        else if(type == 'early_bird') {
            rules = $scope.early_bird_items;
            errors = $scope.eb_errors;
        }
        else if(type == 'last_min') {
            rules = $scope.last_min_items;
            errors = $scope.lm_errors;
        }
        data = rules[index];

        if(data.discount == undefined) {
            return false;
        }
        $('#js-'+type+'-rm-btn-'+index).attr('disabled', 'disabled');
        $('.price_rules-'+type+'-saving h5').text($scope.saving_text);
        $('.price_rules-'+type+'-saving').fadeIn();

        $http.post('update_price_rules/'+type, {data: data}).then(function(response){
            if(response.data.success != 'true') {
                errors[index] = response.data.errors;
            }
            else {
                errors[index] = [];
                rules[index].id = response.data.id;
            }
            $('.price_rules-'+type+'-saving h5').text($scope.saved_text);
            $('.price_rules-'+type+'-saving').fadeOut();
            $('#js-'+type+'-rm-btn-'+index).removeAttr('disabled');
        })
    }

    $scope.remove_availability_rule = function(index) {
        item = $scope.availability_rules[index];
        type = 'availability_rules';
        if(item.id != '' && item.id) {
            $('#'+type+'_wrapper').addClass('loading');
            $http.post('delete_availability_rule/'+item.id, {}).then(function(response){
                $('#'+type+'_wrapper').removeClass('loading');
            })
        }
        $scope.availability_rules.splice(index, 1); 
    }
    $scope.edit_availability_rule = function(index) {
        item = $scope.availability_rules[index];
        $("#calendar-rules-custom").removeClass('hide');
        $("#calendar-rules-custom").addClass('show');
        $scope.availability_rule_item = angular.copy(item);
        $scope.availability_rule_item.type ='prev';
        $scope.availability_rule_item.start_date =$scope.availability_rule_item.start_date_formatted;
        $scope.availability_rule_item.end_date   =$scope.availability_rule_item.end_date_formatted
        if(!$scope.$$phase){
            $scope.$apply();
        }
        $scope.availability_datepickers();
    }
    $scope.availability_rules_type_change = function() {
        rule = $scope.availability_rule_item;
        if(rule.type != 'custom')
        {
            this_elem = $("#availability_rule_item_type option:selected");
            start_date = this_elem.attr('data-start_date');
            end_date = this_elem.attr('data-end_date');
            $scope.availability_rule_item.start_date = start_date;
            $scope.availability_rule_item.end_date = end_date;
        }
    }
    $scope.availability_datepickers = function() {
        var start_date_element = $("#availability_rules_start_date");
        var end_date_element = $("#availability_rules_end_date");
        start_date_element.datepicker({
            'minDate':0,
            'dateFormat': datepicker_format,
            onSelect: function(date, obj){
                var start_date = start_date_element.datepicker('getDate'); 
                start_date.setDate(start_date.getDate() + 1); 
                end_date_element.datepicker('option', 'minDate',start_date );
                // end_date_element.trigger('focus');
                $scope.availability_rule_item.start_date = start_date_element.val();
            }
        })
        end_date_element.datepicker({
            'minDate':1,
            'dateFormat': datepicker_format,
            onSelect: function(date, obj){
                var end_date = end_date_element.datepicker('getDate'); 
                // end_date_element.trigger('focus');
                $scope.availability_rule_item.end_date = end_date_element.val();
            }
        });
    }
    $scope.copy_data =function(data) {
        return angular.copy(data);
    }
    $(document).ready(function(){
        $scope.availability_datepickers();
    });

    $(document).on('click', '#js-calendar-settings-btn', function(){
        $("#calendar-rules").addClass('show');
    });
    $(document).on('click', '#js-close-calendar-settings-btn', function(){
        $("#calendar-rules").removeClass('show');
    });
    $(document).on('click', '#js-add-availability-rule-link', function(){
        $("#calendar-rules-custom").removeClass('hide');
        $("#calendar-rules-custom").addClass('show');
        $scope.availability_rule_item = {type : '', start_date: '', end_date: '', start_date_formatted:'', end_date_formatted:''};
        if(!$scope.$$phase){
            $scope.$apply();
        }
        $scope.availability_datepickers();
    });
    $(document).on('click', '#js-close-availability-rule-btn, #js-cancel-availability-rule-btn', function(){
        $("#calendar-rules-custom").removeClass('show');
        $("#calendar-rules-custom").addClass('hide');
    });


    $(document).on('change', '.reservation_settings_inputs', function(){
        data =  {};
        $(".reservation_settings_inputs").each(function(i, elem){
            field = $(elem);
            data[field.attr('name')] = field.val();
        })
        $('.reservation_settings-saving h5').text($scope.saving_text);
        $('.reservation_settings-saving').fadeIn();

        $http.post('update_reservation_settings', data).then(function(response){
            if(response.data.success != 'true') {
                $scope.rs_errors = response.data.errors;
            }
            else{
                $scope.rs_errors = [];
            }
            $('.reservation_settings-saving h5').text($scope.saved_text);
            $('.reservation_settings-saving').fadeOut();
        });
    });
    $scope.update_availability_rule = function(){
        data = {'availability_rule_item':$scope.availability_rule_item};
        $("#availability_rule_item_wrapper, #availability_rules_wrapper").addClass('loading');
        $http.post('update_availability_rule', data).then(function(response){
            if(response.data.success != 'true') {
                $scope.ar_errors = response.data.errors;
            }
            else{
                $scope.ar_errors = [];
                $scope.availability_rules = response.data.availability_rules;
                $("#js-close-availability-rule-btn").trigger('click');
                $("#calendar-rules-custom").addClass('hide');
            }
            $("#availability_rule_item_wrapper, #availability_rules_wrapper").removeClass('loading');
        });
    }
}]);

app.directive("limitTo", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);

            angular.element(elem).on("keypress", function(event) {
                var key = window.event ? event.keyCode : event.which;

                if (this.value.length == limit) {
                    if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
                        return true;
                    } else {
                        event.preventDefault();
                    }
                } else {
                    if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
                        return true;
                    }
                    if (key < 48 || key > 57) {
                        event.preventDefault();
                    }
                }
            });
        }
    }
}]);
//disable double click when add language
 $(document).on('click', '#write-description-button', function() {
       $('#write-description-button').attr("disabled", true); 
    });
var pathname = document.getElementById("href_calendar").href;
if ($(location).attr('href') == pathname) {
    $('.listing-nav-sm').removeClass('collapsed');
    $('body').removeClass('non_scrl');
}
$('.list-nav-link a').click(function() {
    $('.listing-nav-sm').removeClass('collapsed');
    $('body').removeClass('non_scrl');
});


window.setInterval(function(){
 if( $('.listing-nav-sm').hasClass('collapsed'))
{
$('body').addClass('non_scrl');
}
else{
    $('body').removeClass('non_scrl');
}
}, 10);

$('#href_pricing').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');

    $('#ajax_container').addClass('mar-left-cont');
});
$('#href_terms').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#remove-manage').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#href_booking').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#href_basics').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#href_description').click(function() {

    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');

    $('#ajax_container').addClass('mar-left-cont');
});
$('#href_location').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');

    $('#ajax_container').addClass('mar-left-cont');
});
$('#href_amenities').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});

$('#href_details').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#href_guidebook').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');

});
$('#href_calendar').click(function() {
    // $('#js-manage-listing-nav').addClass('manage-listing-nav');
    // $('#ajax_container').removeClass('mar-left-cont');
    // $('#js-manage-listing-nav').removeClass('pos-abs');
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
    $('#js-manage-listing-nav').addClass('pos-abs');
    $('#ajax_container').addClass('mar-left-cont');
});

$(document).ready(function() {
$('#js-edit-address').click(function() {
        $("body").addClass("pos-fix4");
       
    });
  $('.modal-close').click(function() {
        $("body").removeClass("pos-fix4");
      
    });  
});