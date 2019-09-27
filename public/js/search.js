var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var php_date_format = $('meta[name="php_date_format"]').attr('content');
$(".header_refinement").removeClass("active");
$('.header_refinement[data = "Homes"]').addClass('active');
$(".header_refinement_modal").removeClass("active");
$('.header_refinement_modal[data = "Homes"]').addClass('active');
$(".home_pro").show();
$(".exp_cat").hide();

guests_select_option("#modal_guests", 'Homes');
guests_select_option("#header-search-guests", 'Homes');
//These variables are used to during cancel process
var prop_app_fil = '',
    amen_app_fil = '',
    broom_app_fil = '',
    bed_app_fil = '',
    bath_app_fil = '',
    search_on_map=''
    map_search_first='';

$('.map.hide-sm-view').hide();
$('.search-results').show();
$('body').addClass('pos-fixed');
// $('.button_ipunk').hide();
// $('.button_ipunk-result').show();

$('.filter-div').hide();
$('.sidebar').scroll(function() {
    var scroll = $('.sidebar').scrollTop();
    var height = $('.filters.collapse').height();
    if (scroll >= height) {
        $('.sidebar-header').addClass('fixed-header');
    } else {
        $('.sidebar-header').removeClass('fixed-header');
    }

});
$(window).resize(function() {
    $('.sidebar-header').removeClass('fixed-header');
});

$('.sidebar').scroll(function() {
    $('#ui-datepicker-div').hide();
});

$('.customBox').hover(function() {
    $(mark).addClass('hover');
});
$(document).ready(function() {
    $('label.toggle .toggle__handle').click(function() {
        $('label.toggle').toggleClass('toggle--checked');
        var new_text = $('label.toggle .toggle__handle span').text() == 'Off' ? 'On' : 'Off';
        $('label.toggle .toggle__handle span').html(new_text);
    });
});
app.directive('postsPagination', function() {
    return {
        restrict: 'E',
        template: '<ul class="pagination">' +
            '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)">&laquo;</a></li>' +
            '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)">&lsaquo; ' + $('#pagin_prev').val() + '</a></li>' +
            '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
            '<a href="javascript:void(0)" ng-click="search_result(i)">{{i}}</a>' +
            '</li>' +
            '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)">' + $('#pagin_next').val() + ' &rsaquo;</a></li>' +
            '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)">&raquo;</a></li>' +
            '</ul>'
    };
}).controller('search-page', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
    $scope.first_search = 'Yes';
    $scope.current_date = new Date();

    $scope.totalPages = 0;
    $scope.currentPage = 1;
    $scope.range = [];
    $scope.marker_click = 0;
    $scope.map_lat_long = '';

    $scope.map_fit_bounds = '';

    $(window).resize(function() {

        if ($(window).width() < 1025) {
            $('#more_filter_submit').html('See Homes');
        } else {
            $('#more_filter_submit').html('Apply filters');
        }

        mobile_mode_functions();

    });
    $(window).ready(function() {

        if ($(window).width() < 1025) {
            $('#more_filter_submit').html('See Homes');
        } else {
            $('#more_filter_submit').html('Apply filters');
        }

        mobile_mode_functions();
    });

    function mobile_mode_functions() {
        if ($(window).width() < 1025) {

            $(document).on('click', '.button_ipunk', function() {
                $('.map.hide-sm-view').show();
                $('.search-results').hide();                
                $('body').addClass('pos-fixed');
                $('.button_ipunk').hide();
                $('.button_ipunk-result').show();
                $('.filter-div').hide();
                $(".hide-sm-view").addClass("loading");
                $scope.search_result();
            });
            $('.button_ipunk-result').click(function() {
                $('.button_ipunk').show();
                $('.button_ipunk-result').hide();
                $('.map.hide-sm-view').hide();
                $('.search-results').show();
                $('.filter-div').hide();
            });
            $('.button_ipunk4').click(function() {
                // $('.button_ipunk').hide();
                // $('.button_ipunk-result').hide();
                // $('.button_ipunk4').hide();
                // $('.map.hide-sm-view').hide();
                // $('.search-results').hide();
                // $('.filter-div').show();
            });
        } else {
            $('.search-results').show();
            $('body').removeClass('pos-fixed');

        }
    }

    $(window).ready(function() {
        mobile_search_mode_function();
    });

    function mobile_search_mode_function() {
        if ($(window).width() < 1025) {

            /*$('.amenities').change(function() {
                $scope.search_result();
            });
            $('.property_type').change(function() {
                $scope.search_result();
            });
            $('#map-search-min-bathrooms').change(function() {
                $scope.search_result();
            });
            $('#map-search-min-beds').change(function() {
                $scope.search_result();
            });
            $('#map-search-min-bedrooms').change(function() {
                $scope.search_result();
            });*/

        }
    }

    /*  start = moment();
      $('#checkin').daterangepicker({
        minDate : start,
        autoApply : true,
        autoUpdateInput : false,
        dateLimitMin : {"days" : 1},
        locale : {
          format : "MMM DD"
        },
      });

      $('#checkin').on('apply.daterangepicker', function(ev, picker) {
        startDateInput = $('#checkin');
        endDateInput = $('#checkout');

        startDate = picker.startDate;
        endDate = picker.endDate;

        startDateInput.val(startDate.format('DD-MM-YYYY'));
        endDateInput.val(endDate.format('DD-MM-YYYY'));

        $scope.search_result();
      });

    */
    var st_date = moment($('#checkin').val(),daterangepicker_format).toDate(); new Date($('#checkin').val()); 
    var end_date = moment($('#checkout').val(),daterangepicker_format).toDate(); new Date($('#checkout').val()); //alert(st_date); alert(end_date);
    var today = new Date();
    start = moment();
    $('.clear_date').daterangepicker({
      locale: { cancelLabel: 'Clear' }  
    });
    $(".dates_clear").click(function() {
        setGetParameter('checkin','');
        setGetParameter('checkout','');
        location.reload();
    });
    $('.dbdate').daterangepicker({
        // autoApply: true,
        autoUpdateInput: false,
        locale: {
            format: daterangepicker_format,
            cancelLabel: CLEAR_LANG,
            applyLabel: APPLY_LANG
        },
        minDate: today,
        dateLimitMin : {
            "days" :1
        },
    });

    $('.dbdate').on('cancel.daterangepicker', function() {
       
        if($scope.checkin)
        {
            $scope.checkin = "";
            $scope.checkout = "";
            $scope.search_result();
        }
        else
        {
            $scope.checkin = "";
            $scope.checkout = "";
        }

        $('.dbdate').removeClass('active')
        $('.gut5').removeClass('active')
        var picker = $('#checkin,.dbdate, .gut5').data('daterangepicker');
        picker.setStartDate(today);
        picker.setEndDate("");
    });

 

    $('.mobile_date_clear').click(function(ev, picker){

        var picker = $(".gut5").data('daterangepicker');
          picker.setStartDate(today);
        picker.setEndDate("");

       if($scope.checkin)
        {
            $scope.checkin = "";
            $scope.checkout = "";
            $scope.search_result();
        }
        else
        {
            $scope.checkin = "";
            $scope.checkout = "";
        }
        $('.gut5').removeClass('active')

$('#checkin').val('');
$('#checkout').val('');
         
setTimeout(function(){

    var picker = $(".gut5").data('daterangepicker');
    picker.setStartDate(today);
    picker.setEndDate("");

},2000) ;

});

    $('.gut5').daterangepicker({
        autoApply: true,
        minDate: today,
        dateLimitMin : {
            "days" :1
        },
        autoUpdateInput: false,
        locale: {
            format: daterangepicker_format
        },
        parentEl: '#daterangepicker_modal_div',
        alwaysShowCalendars: true,
        inline: true,
    });

    if(st_date !='Invalid Date' && end_date != 'Invalid Date')
    {
        var picker = $('#checkin,.dbdate, .gut5').data('daterangepicker');
        picker.setStartDate(st_date );
        picker.setEndDate(end_date );
    }
    $('#checkin,.dbdate').on('show.daterangepicker', function(ev, picker) {
        /* $(this).parent().css('opacity', 0);*/
        $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 1 !important');

          $(".morefit").hide();
          $('.map,.sidesear').removeClass('mapfil');
          $(".guestbut1").hide();
          $('.sidebar').removeClass('newdp');
    });
    $('#checkin,.dbdate').on('apply.daterangepicker', function(ev, picker) {
        //alert('applying');
        startDateInput = $('#checkin');
        endDateInput = $('#checkout');

        startDate = picker.startDate;
        endDate = picker.endDate;
        $scope.checkin = startDate.format(daterangepicker_format);
        $scope.checkout = endDate.format(daterangepicker_format);
        startDateInput.val(startDate.format(daterangepicker_format));
        endDateInput.val(endDate.format(daterangepicker_format));
        $scope.search_result();
    });
    $("#checkin,.dbdate").on('hide.daterangepicker', function(ev, picker) {
        if (!picker.startDate || !picker.endDate) {
            $(this).parent().css('opacity', 1);
            $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 0 !important');
        }
    });

    $('#checkout').daterangepicker({
        autoApply: true,
        minDate: today,
        dateLimitMin : {
            "days" :1
        },
        autoUpdateInput: false,
        locale: {
            format: daterangepicker_format
        }
    });
    $('#checkout').on('show.daterangepicker', function(ev, picker) {
        /* $(this).parent().css('opacity', 0);*/
        $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 1 !important');
    });
    $('#checkout').on('apply.daterangepicker', function(ev, picker) {

        startDateInput = $('#checkin');
        endDateInput = $('#checkout');

        startDate = picker.startDate;
        endDate = picker.endDate;
        $scope.checkin = startDate.format(daterangepicker_format);
        $scope.checkout = endDate.format(daterangepicker_format);
        startDateInput.val(startDate.format(daterangepicker_format));
        //startDateInput.next().html(startDate.format('MMM DD'));
        endDateInput.val(endDate.format(daterangepicker_format));
        //endDateInput.next().html(endDate.format('MMM DD'));
        $scope.search_result();
    });
    $("#checkout").on('hide.daterangepicker', function(ev, picker) {
        if (!picker.startDate || !picker.endDate) {
            $(this).parent().css('opacity', 1);
            $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 0 !important');
        }
    });

    $(document).on('click', '[id^="wishlist-widget-icon-"]', function() {
        
        if (typeof USER_ID == 'object') {

               $http.get(APP_URL + "/wishlist_list", {}).then(function(response) 
                {
                     window.location.href = APP_URL + '/login';
                });
            return false;
        }
        var name = $(this).data('name');
        var img = $(this).data('img');
        var address = $(this).data('address');
        var host_img = $(this).data('host_img');
        $scope.room_id = $(this).data('room_id');

        $('.background-listing-img').css('background-image', 'url(' + img + ')');
        $('.host-profile-img').attr('src', host_img);
        $('.wl-modal-listing__name').text(name);
        $('.wl-modal-listing__address').text(address);
        $('.wl-modal-footer__input').val(address);

        $('.wl-modal__col').removeClass('hide-md');
        $('.wl-modal__modal').removeClass('hide');
        $('.wl-modal__col:nth-child(2)').addClass('hide');
        $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
        $http.get(APP_URL + "/wishlist_list?id=" + $(this).data('room_id')+"&type="+$(this).data('what'), {}).then(function(response) {
            $('#wish-list-signup-container').remove();
            $('.wl-modal__col:nth-child(2)').removeClass('hide');
            $scope.wishlist_list = response.data;
        });
    });

    $scope.wishlist_row_select = function(index) {

        $http.post(APP_URL + "/save_wishlist", {
            data: $scope.room_id,
            wishlist_id: $scope.wishlist_list[index].id,
            saved_id: $scope.wishlist_list[index].saved_id
        }).then(function(response) {
            if (response.data == 'null')
                $scope.wishlist_list[index].saved_id = null;
            else
                $scope.wishlist_list[index].saved_id = response.data;
        });

        if ($('#wishlist_row_' + index).hasClass('text-dark-gray'))
            $scope.wishlist_list[index].saved_id = null;
        else
            $scope.wishlist_list[index].saved_id = 1;
    };

    $(document).on('submit', '.wl-modal-footer__form', function(event) {
        event.preventDefault();
        $('.wl-modal__col:nth-child(2)').addClass('hide');
        $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
        $http.post(APP_URL + "/wishlist_create", {
            data: $('.wl-modal-footer__input').val(),
            id: $scope.room_id
        }).then(function(response) {
            $('.wl-modal-footer__form').addClass('hide');
            $('#wish-list-signup-container').remove();
            $('.wl-modal__col:nth-child(2)').removeClass('hide');
            $scope.wishlist_list = response.data;
            event.preventDefault();
        });
        event.preventDefault();
    });

    $('.wl-modal__modal-close').click(function() {
        var null_count = $filter('filter')($scope.wishlist_list, {
            saved_id: null
        });

        if (null_count.length == $scope.wishlist_list.length)
            $('#wishlist-widget-' + $scope.room_id).prop('checked', false);
        else
            $('#wishlist-widget-' + $scope.room_id).prop('checked', true);

        $('.wl-modal__modal').addClass('hide');
    });

    $(document).ready(function() {
        $scope.map_lat_long = '';
        var room_type = [];
        $('.room-type:checked').each(function(i) {
            room_type[i] = $(this).val();
        });

        var property_type = [];
        $('.property_type:checked').each(function(i) {
            property_type[i] = $(this).val();
        });

        var amenities = [];
        $('.amenities:checked').each(function(i) {
            amenities[i] = $(this).val();
        });
        $('.search_tag').addClass('hide');

        if (room_type != '') {
            $('.room-type_tag').removeClass('hide');
        }
        if (amenities != '') {
            $('.amenities_tag').removeClass('hide');
        }
        if (property_type != '') {
            $('.property_type_tag').removeClass('hide');
        }

        var location_val = $("#location").val();
        $("#header-search-form").val(location_val);
        $("#modal-locations").val(location_val);

        var slider = document.getElementById('slider');

        var theLanguage = $('html').attr('lang');

        if (theLanguage == "ar") {
            var direct = 'rtl';
        } else {
            var direct = 'ltr';
        }

        noUiSlider.create(slider, {
            start: [min_slider_price_value, max_slider_price_value],
            connect: true,
            step: 1,
            margin: 2,
            direction: direct,
            range: {
                'min': min_slider_price,
                'max': max_slider_price
            }

        });


        var marginMin = document.getElementById('min_text'),
            marginMax = document.getElementById('max_text');

        slider.noUiSlider.on('update', function(values, handle) {
            if (handle) {
                $("#max_text").html(parseInt(values[handle]));
            } else {
                $("#min_text").html(parseInt(values[handle]));
            }

        });
        slider.noUiSlider.on('change', function(values, handle) {
            $("#min_value").val(parseInt(values[0]));
            $("#min_text").html(parseInt(values[0]));
            if (max_slider_price == parseInt(values[1])) {
                $("#max_text").html(parseInt(values[1]) + '+');
            } else {
                $("#max_text").html(parseInt(values[1]));
            }
            $("#max_value").val(parseInt(values[1]));
            
            $(".max_value").val(parseInt(values[1]));
            $(".min_value").val(parseInt(values[0]));

            $scope.update_filter_status();
        });



        var mobslider = document.getElementById('mobslider');

        var theLanguage = $('html').attr('lang');

        if (theLanguage == "ar") {
            var direct = 'rtl';
        } else {
            var direct = 'ltr';
        }

        noUiSlider.create(mobslider, {
            start: [min_slider_price_value, max_slider_price_value],
            connect: true,
            step: 1,
            margin: 2,
            direction: direct,
            range: {
                'min': min_slider_price,
                'max': max_slider_price
            }

        });


        var marginMin = document.getElementById('min_text1'),
            marginMax = document.getElementById('max_text1');

        mobslider.noUiSlider.on('update', function(values, handle) {
            if (handle) {
                $("#max_text1").text(parseInt(values[handle]));
            } else {
                $("#min_text1").text(parseInt(values[handle]));
            }

        });
        mobslider.noUiSlider.on('change', function(values, handle) {
            $("#min_value1").val(parseInt(values[0]));
            $("#min_text1").text(parseInt(values[0]));
            if (max_slider_price == parseInt(values[1])) {
                $("#max_text1").html(parseInt(values[1]) + '+');
            } else {
                $("#max_text1").html(parseInt(values[1]));
            }
            $("#max_value1").val(parseInt(values[1]));
            $(".max_value").val(parseInt(values[1]));
            $(".min_value").val(parseInt(values[0]));
            $scope.update_filter_status();
        });


        $('.show-more').click(function() {
            $(this).children('span').toggleClass('hide');
            $(this).parent().parent().children('div').children().toggleClass('filters-more');
        });


        $("#more_filters").click(function() {

            $(".toggle-group").css("display", "block");
            $(".toggle-hide").css("display", "none");
            $(".sidebar").css("height", "87%");
        });
    });

    function no_results() {
        if ($('.search-results').hasClass('loading'))
            $('#no_results').hide();
        else
            $('#no_results').show();
    }

    function map_loading() {
        if ($('.search-results').css('display') == 'none') {
            $('.map').addClass('loading');
        }
    }

    function map_loading_remove() {
        if ($scope.first_search == 'Yes') {
            $scope.first_search = 'No';
            $('.button_ipunk').show();
            $('.button_ipunk-result').hide();
            $('.map.hide-sm-view').hide();
            $('.search-results').show();
            $('.filter-div').hide();
        }
        $('.map').removeClass('loading');
    }

    var location1 = getParameterByName('location');

    var current_url = (window.location.href).replace('/s', '/searchResult');

    pageNumber = 1;

    if (pageNumber === undefined) {
        pageNumber = '1';
    }

    $('.search-results').addClass('loading');
    map_loading();
    no_results();
    $scope.on_mouse = function(index) {
        //markers[index].setIcon(getMarkerImage('hover'));
        if (markers[index] != undefined) {
            mark = markers[index].div_;
            $(mark).addClass('hover');
        }
    };
    $scope.out_mouse = function(index) {
        //markers[index].setIcon(getMarkerImage('normal'));
        if (markers[index] != undefined) {
            mark = markers[index].div_;
            $(mark).removeClass('hover');
        }
    };
    $scope.setParams = function() {
        var max_price = $("#max_value").val();
        var min_price = $("#min_value").val();
        setGetParameter('min_price', min_price);
        setGetParameter('max_price', max_price);
    }
    $scope.search_result = function(pageNumber) {
        if (pageNumber === undefined) {
            pageNumber = '1';
        }

        var max_price = $(".max_value").val();
        var min_price = $(".min_value").val();

        is_mobile = $(window).width() > 767 ? false : true;
        filter_location = is_mobile ? '.morefit1 ':'.home_filter ';

        var room_type = [];
        $(filter_location+'.room-type:checked').each(function(i) {
            room_type[i] = $(this).val();
        });
        if (room_type == '') {
            $('.room-type_tag').addClass('hide');
        }
        filter_location = is_mobile ? '.morefit1 ':'.morefit ';
        var property_type = [];
        $(filter_location+'.property_type:checked').each(function(i) {
            property_type[i] = $(this).val();
        });
        if (property_type == '') {
            $('.property_type_tag').addClass('hide');
        }

        var amenities = [];
        $(filter_location+'.amenities:checked').each(function(i) {
            amenities[i] = $(this).val();
        });
        if (amenities == '') {
            $('.amenities_tag').addClass('hide');
        }

        if(room_type.length)
        {
            room_type=jQuery.unique(room_type);    
        }
        if(property_type.length)
        {
            property_type=jQuery.unique(property_type);    
        }
        if(amenities.length)
        {
            amenities=jQuery.unique(amenities);    
        }

        var checkin = $scope.checkin;
        var checkout = $scope.checkout;
        /*var min_beds = $(".search_beds").val();
        var min_bathrooms = $(".search_bath").val();
        var min_bedrooms = $(".search_bedrooms").val();
        var guest_select = $(".guest-select").val();*/
        var min_bedrooms = $scope.search_bedrooms;
        var min_beds = $scope.search_beds;
        var min_bathrooms = $scope.search_bath;
        var guest_select =  $scope.search_guest;
        if(guest_select==null || guest_select=='') guest_select=1;
        if ($.trim($scope.map_lat_long) != '' && search_on_map != '') {
            var map_details = $scope.map_lat_long;
        } else {
            var map_details = "";
        }

        instant_book = $scope.instant_book;

        setGetParameter('room_type', room_type);
        setGetParameter('current_refinement', current_refinement);
        setGetParameter('property_type', property_type);
        setGetParameter('amenities', amenities);
        setGetParameter('checkin', checkin);
        setGetParameter('checkout', checkout);
        setGetParameter('guests', guest_select);
        setGetParameter('beds', min_beds);
        setGetParameter('bathrooms', min_bathrooms);
        setGetParameter('bedrooms', min_bedrooms);
        setGetParameter('min_price', min_price);
        setGetParameter('max_price', max_price);
        setGetParameter('page', pageNumber);
        setGetParameter('instant_book', instant_book);
        setGetParameter('php_date_format', php_date_format);

        $('.search_tag').addClass('hide');

        if (room_type != '') {
            $('.room-type_tag').removeClass('hide');
        }
        if (amenities != '') {
            $('.amenities_tag').removeClass('hide');
        }
        if (property_type != '') {
            $('.property_type_tag').removeClass('hide');
        }

        var location1 = getParameterByName('location');

        $('.search-results').addClass('loading');
        map_loading();
        no_results();
        $http.post('searchResult?page=' + pageNumber, {
                location: location1,
                min_price: min_price,
                max_price: max_price,
                amenities: amenities,
                property_type: property_type,
                room_type: room_type,
                beds: min_beds,
                bathrooms: min_bathrooms,
                bedrooms: min_bedrooms,
                checkin: checkin,
                checkout: checkout,
                guest: guest_select,
                map_details: map_details,
                instant_book: instant_book
            })
            .then(function(response) {
                $(".hide-sm-view").removeClass("loading");
                $scope.room_result = response.data;
                $scope.checkin = checkin;
                $scope.checkout = checkout;
                $scope.totalPages = response.data.last_page;
                $scope.currentPage = response.data.current_page;
                // Pagination Range
                var pages = [];

                for (var i = 1; i <= response.data.last_page; i++) {
                    pages.push(i);
                }
                var amenities_check = getParameterByName('amenities');

                var propertytype_check = getParameterByName('property_type');

                if ($(window).width() > 760) {
                    if (amenities_check == '' || propertytype_check == '') {
                        $('#more_filter_submit').attr('disabled', 'disabled');
                    }
                }
                $('.search-results').removeClass('loading');
                $scope.range = pages;
                var bounds = new google.maps.LatLngBounds();
                angular.forEach(response.data.data, function(value,key){
                    var lat = value["rooms_address"]["latitude"];
                    var lng = value["rooms_address"]["longitude"]; 
                    bounds.extend(new google.maps.LatLng(lat,lng));
                });

                $scope.map_fit_bounds = 'Yes';
                if($(window).width() > 760 || $scope.first_search != 'Yes'){
                    if(response.data.total>0 && search_on_map==''){
                        $scope.viewport = bounds;
                        $scope.cLat=response.data.data[0]["rooms_address"]["latitude"];
                        $scope.cLong=response.data.data[0]["rooms_address"]["longitude"];
                        map_search_first='Yes';

                        initialize(bounds);
                    }
                    else if(search_on_map=='')
                    {
                        $scope.viewport = $scope.locationViewport;
                        $scope.cLat=$scope.locationLat;
                        $scope.cLong=$scope.locationLong;

                        initialize();
                    }
                }
                $('.search-results').removeClass('loading');
                map_loading_remove();
                no_results();
                marker(response.data);
            });
    };

    $scope.apply_filter = function() {
        if ($(window).width() < 760) {
            $('.search-results').show();
            $('.filter-div').hide();
        }
        //save the values for uncheck the boxes during cancel
        else {
            $('[id^="map-search"]').each(function() {
                if ($(this).hasClass('property_type') && $(this).prop('checked') == true) {
                    if (prop_app_fil == '')
                        prop_app_fil = $(this).val();
                    else
                        prop_app_fil = prop_app_fil + ',' + $(this).val();
                } else if ($(this).hasClass('amenities') && $(this).prop('checked') == true) {
                    if (amen_app_fil == '')
                        amen_app_fil = $(this).val();
                    else
                        amen_app_fil = amen_app_fil + ',' + $(this).val();
                }
            })

            broom_app_fil = $('#map-search-min-bedrooms').val();
            bath_app_fil = $('#map-search-min-bathrooms').val();
            bed_app_fil = $('#map-search-min-beds').val();

            $(".toggle-hide").css("display", "block");
            $(".toggle-group").css("display", "none");
            $scope.search_result();
        }
    };
    $scope.remove_filter = function(parameter) {
        $('.' + parameter).removeAttr('checked');
        var paramName = parameter.replace('-', '_');
        var paramValue = '';
        setGetParameter(paramName, paramValue)
        $('.' + parameter + '_tag').addClass('hide');

        $scope.search_result();
    };

    $scope.format_date =function(date, format)
    {
        return moment(date,daterangepicker_format).format(daterangepicker_format);
    }
    $scope.filter_status = [];
    $scope.filter_text = [];
    $scope.update_filter_status= function()
    {
        room_types_length = $('[id^="room_type_"]:checked').length;
        min_price = $("#min_text").html()-0;
        max_price = $("#max_text").html()-0;
        min_price1 = $("#min_text1").html()-0;
        max_price1 = $("#max_text1").html()-0;

        more_filters_count = 0;
        $scope.search_bedrooms > 0 ? (more_filters_count++) : '';
        $scope.search_beds > 0 ? (more_filters_count++) : '';
        $scope.search_bath > 0 ? (more_filters_count++) : '';
        more_filters_amenities_length = $('[id^="amenities_"]:checked').length;
        more_filters_property_length = $('[id^="property_"]:checked').length;
        more_filters_count  += more_filters_amenities_length;
        more_filters_count  += more_filters_property_length;

        filters_count = 0;
        filters_count += $('[id^="mob_room_type_"]:checked').length;
        filters_count += $('[id^="mob_amenities_"]:checked').length;
        filters_count += $('[id^="mob_property_"]:checked').length;
        $scope.search_bedrooms > 0 ? (filters_count++) : '';
        $scope.search_beds > 0 ? (filters_count++) : '';
        $scope.search_bath > 0 ? (filters_count++) : '';
        (min_price1 > min_slider_price || max_price1 < max_slider_price) ? (filters_count++) : '';
        $scope.instant_book != '0' ? (filters_count++) : '';
        
        $scope.filter_status['dates'] = ($scope.checkin && $scope.checkout)? true: false;
        $scope.filter_status['guests'] = $scope.search_guest > 1 ? true: false;
        $scope.filter_status['room_types'] = (room_types_length > 0) ? true: false;
        $scope.filter_status['prices'] = (min_price > min_slider_price || max_price < max_slider_price) ? true: false;
        $scope.filter_status['instant_book'] = ($scope.instant_book != '0') ? true: false;
        $scope.filter_status['more_filters'] = (more_filters_count > 0) ? true: false;
        $scope.filter_status['filters'] = (filters_count > 0) ? true: false;

        price_text = '';
        if(min_price > min_slider_price && max_price < max_slider_price)
        {
            price_text = $scope.currency_symbol+min_price+' - '+$scope.currency_symbol+max_price;
        }
        else if(min_price > min_slider_price)
        {
            price_text = $scope.currency_symbol+min_price+'+ ';   
        }
        else if(max_price < max_slider_price)
        {
            price_text = 'Up to '+$scope.currency_symbol+max_price;
        }

        $scope.filter_text['room_types'] =  ' · '+room_types_length;
        $scope.filter_text['prices'] = price_text;
        $scope.filter_text['more_filters'] =  ' · '+more_filters_count;
        $scope.filter_text['filters'] =  ' · '+filters_count;
        $scope.filter_text['filters_count'] =  filters_count;

        if(!$scope.$$phase) {
            $scope.$apply();
        }

        $scope.guests = $scope.search_guest;
    }
    $(document).ready(function(){
        $scope.update_filter_status();
    });
    $('.guestbut').click(function(){
        $scope.update_filter_status();
    });
    $('.room-type, .property_type, .amenities').click(function(){
        $scope.update_filter_status();
    });
    $scope.filter_btn_text = function(filter)
    {
        $scope.update_filter_status();
        btn_text = $scope.filter_text[filter];
        return btn_text;
    }
    $scope.is_filter_active = function(filter)
    {
        $scope.update_filter_status();
        result = false;
        result = $scope.filter_status[filter];
        return result;
    }
    $scope.filter_active = function(filter)
    {
        is_active = ($scope.is_filter_active(filter) || $scope.opened_filter == filter);
        class_name = (is_active) ? 'active' : '';
        return class_name;
    }
    $scope.update_opened_filter = function(filter)
    {
        if($scope.opened_filter == filter)
        {
            $scope.reset_filters(filter);
        }
        else{
            $scope.opened_filter = filter;   
        }
    }
    $scope.apply_filters = function(filter)
    {
        $(".morefit5").hide();
        $(".morefit3").hide();
        $(".morefit2").hide();
        $(".morefit1").hide();
        $(".morefit").hide();
        $(".hide-sm-view").addClass("loading");
        $('.map,.sidesear').removeClass('mapfil');
        $(".guestbut1").hide();
        $('.sidebar').removeClass('newdp');
        $("body").removeClass("non_scrl");
        $("body").removeClass("pos-fix3");
        // if(!$scope.is_filter_active(filter))
            // $('.guestbut').removeClass('active');

        if(filter == 'dates')
        {
            picker = $(".gut5").data('daterangepicker');
            startDateInput = $('#checkin');
            endDateInput = $('#checkout');

            startDate = picker.startDate;
            endDate = picker.endDate;

            $scope.checkin = startDate.format(daterangepicker_format);
            $scope.checkout = endDate.format(daterangepicker_format);
            
            startDateInput.val(startDate.format(daterangepicker_format));
            endDateInput.val(endDate.format(daterangepicker_format));
        }

        if(filter == 'location_refinement')
        {
            var location = $('#header-search-form-mob').val();
            var locations="";
            if(location){ locations = location.replace("", "+"); }
            setGetParameter('location', locations);
            var url_current_refinement = getParameterByName("current_refinement");
            if(url_current_refinement != current_refinement) {
                setGetParameter('current_refinement', current_refinement);
                window.location.reload();
                return true;
            }
            $('#search-modal--sm').addClass('hide');
            $('#search-modal--sm').attr('aria-hidden', 'true');
        }

        $scope.search_result();
        $scope.opened_filter = '';
        $scope.update_filter_status();
    }
    $scope.reset_filters = function(filter)
    {
        if(filter == 'dates')
        {
            picker = $(".gut5").data('daterangepicker');
            startDateInput = $('#checkin');
            endDateInput = $('#checkout');

            startDate = startDateInput.val();
            endDate = endDateInput.val();
            
            startDateMoment = moment(startDate, daterangepicker_format);
            endDateMoment = moment(endDate, daterangepicker_format);
            if(startDateMoment.isValid() && endDateMoment.isValid())
            {
                picker.setStartDate(startDateMoment);
                picker.setEndDate(endDateMoment);                
            }
        }
        if(filter == 'guests')
        {
            guests = getParameterByName('guests');
            if(!guests)
            {
                guests = 1;
            }
            guests = guests-0;
            $scope.search_guest = guests;
        }
        if(filter == 'room_types')
        {
            $scope.room_type_reset();
        }
        if(filter == 'prices')
        {
            $scope.price_reset();
        }
        if(filter == 'instant_book')
        {
            instant_book = getParameterByName('instant_book');
            $scope.instant_book  =instant_book;
        }
        if(filter == 'more_filters')
        {
            bathrooms = getParameterByName('bathrooms');
            beds = getParameterByName('beds');
            bedrooms = getParameterByName('bedrooms');

            $scope.search_bath = bathrooms-0;
            $scope.search_beds = beds-0;
            $scope.search_bedrooms = bedrooms-0;

            $scope.amenities_reset();
            $scope.property_type_reset();
            
        }
        if(filter == 'filters')
        {
            bathrooms = getParameterByName('bathrooms');
            beds = getParameterByName('beds');
            bedrooms = getParameterByName('bedrooms');
            instant_book = getParameterByName('instant_book');

            $scope.search_bath = bathrooms-0;
            $scope.search_beds = beds-0;
            $scope.search_bedrooms = bedrooms-0;
            $scope.instant_book  =instant_book;

            $scope.price_reset();
            $scope.room_type_reset();
            $scope.amenities_reset();
            $scope.property_type_reset();
        }
        $scope.opened_filter = '';
        $scope.update_filter_status();
        if(!$scope.$$phase) {
            $scope.$apply();
        }
    }

    $scope.reset_filters('dates');
    $scope.price_reset = function()
    {
        var min_price_check = getParameterByName('min_price');
        var max_price_check = getParameterByName('max_price');

        var slider_check = document.getElementById('slider');
        slider_check.noUiSlider.set([min_price_check-0, max_price_check-0]);

        var mobslider_check = document.getElementById('mobslider');
        mobslider_check.noUiSlider.set([min_price_check-0, max_price_check-0]);
    }
    $scope.room_type_reset = function()
    {
        room_types = getParameterByName('room_type');
        room_types_array = room_types.split(',');
        $('.room-type').prop('checked', false);
        $.each(room_types_array, function(i, v){
            $("#room_type_"+v).prop('checked', true);
            $("#mob_room_type_"+v).prop('checked', true);
        });
    }
    $scope.property_type_reset = function()
    {
        property_type = getParameterByName('property_type');
        property_type_array = property_type.split(',');
        $('.property_type').prop('checked', false);
        $.each(property_type_array, function(i, v){
            $("#property_"+v).prop('checked', true);
            $("#mob_property_"+v).prop('checked', true);
        });
    }
    $scope.amenities_reset = function()
    {
        amenities = getParameterByName('amenities');
        amenities_array = amenities.split(',');
        $('.amenities').prop('checked', false);
        $.each(amenities_array, function(i, v){
            $("#amenities_"+v).prop('checked', true);
            $("#mob_amenities_"+v).prop('checked', true);
        });
    }

    function setGetParameter(paramName, paramValue) {
        var url = window.location.href;

        if (url.indexOf(paramName + "=") >= 0) {
            var prefix = url.substring(0, url.indexOf(paramName));
            var suffix = url.substring(url.indexOf(paramName));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        } else {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
        history.pushState(null, null, url);
    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    var viewport = $scope.locationViewport = JSON.parse($('#viewport').val());
    var lat0 = '';
    var long0 = '';
    var lat1 = '';
    var long1 = '';
    var infoBubble = new InfoBubble({
        maxWidth: 3000
    });
    var bounds;

    angular.forEach(viewport, function(key, value) {
        lat0 = viewport['southwest']['lat'];
        long0 = viewport['southwest']['lng'];
        lat1 = viewport['northeast']['lat'];
        long1 = viewport['northeast']['lng'];
    });

    var bounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(lat0, long0),
        new google.maps.LatLng(lat1, long1)
    );

    $scope.viewport = $scope.locationViewport = bounds;

    setTimeout(function() {

        initializeMap();

        $scope.map_lat_long = '';
    }, 1000);


    function initializeMap() {

        autocomplete = new google.maps.places.Autocomplete(document.getElementById('header-search-form'), {
            types: ['geocode']
        });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var location = $('#header-search-form').val();
            var locations = location.replace(" ", "+");
            setGetParameter('location', locations);
            var place = autocomplete.getPlace();
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            if (place && place.geometry && place.geometry.viewport)
                $scope.viewport  = $scope.locationViewport = place.geometry.viewport;

            $scope.cLat = $scope.locationLat = latitude;
            $scope.cLong = $scope.locationLong = longitude;

            $scope.map_lat_long = '';
            search_on_map = '';
            $(".search_new_header").show();
            /*HostExperiencePHPUnCommentStart
            $scope.search_result();
            initialize();
            HostExperiencePHPUnCommentEnd*/
        });

        sm_autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('header-search-form-mob'), {
            types: ['geocode']
        });
        google.maps.event.addListener(sm_autocomplete1, 'place_changed', function() {
            $("#header-search-form").val($("#header-search-form-mob").val());
            var location = $('#header-search-form-mob').val();
            var locations = location.replace(" ", "+");
            var place = sm_autocomplete1.getPlace();
            if(!place.geometry) {
                return false;
            }
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            if (place && place.geometry && place.geometry.viewport)
                $scope.viewport  = $scope.locationViewport= place.geometry.viewport;

            $scope.cLat = $scope.locationLat = latitude;
            $scope.cLong = $scope.locationLong = longitude;

            $scope.map_lat_long = '';
            search_on_map = '';
            // $scope.search_result();
            // initialize();
            setGetParameter('location', locations);
            $scope.apply_filters('location_refinement');
        });
    }
    $(document).on('click','.header_refinement',function() {
        var current_refinement = $(this).attr('data');
        $(".header_refinement_input").val(current_refinement);
        $(".header_refinement").removeClass("active");
        $(this).addClass("active");
        $(".search_new_header").hide();
        if(!$(this).hasClass('in_form'))
            window.location=APP_URL+'/s?location='+getParameterByName('location')+'&checkin='+getParameterByName('checkin')+'&checkout='+getParameterByName('checkout')+'&guests='+getParameterByName('guests')+'&current_refinement='+current_refinement;
    });
    $(document).on('click','.header_refinement_modal',function() {
        current_refinement = $(this).attr('data');
        $(".header_refinement_modal_input").val(current_refinement);
        $(".header_refinement_modal").removeClass("active");
        $(this).addClass("active");

        $scope.apply_filters('location_refinement');
    });
    $scope.zoom = '';
    $scope.cLat = '';
    $scope.cLong = '';
    $scope.locationLat = '';
    $scope.locationLong = '';
    var html = '';
    var markers = [];
    var map;
    var infowindow = new google.maps.InfoWindow({
        content: html
    });

    $(document).ready(function(){
        $scope.search_result();
    });
    // initialize();

    function initialize(value) {
        if(!value)
        {
            value = '';
        }
        // search_on_map='';

        if ($scope.zoom == '') {
            var zoom_set = 10;
        } else {
            var zoom_set = $scope.zoom;
        }
        if ($("#lat").val() == 0) {
            var zoom_set = 1;
        }
        if ($scope.cLat == '' && $scope.cLong == '') {
            var latitude = $("#lat").val();
            var longitude = $("#long").val();
        } else {
            var latitude = $scope.cLat;
            var longitude = $scope.cLong;
        }

        var myCenter = new google.maps.LatLng(latitude, longitude);
        
        var mapProp = {
            scrollwheel: false,
            center: myCenter,
            zoom: zoom_set,
            minZoom: 2,
            maxZoom: 18,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_TOP,
                style: google.maps.ZoomControlStyle.SMALL
            },
            mapTypeControl: false,
            streetViewControl: false,
            navigationControl: false,
            backgroundColor: '#a4ddf5',
            styles: [

                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#a4ddf5'
                    }]
                }
            ],
        }

        // map1 = new google.maps.Map(document.getElementById("map_canvas1"), mapProp);
        // if (latitude != 0 && longitude != 0) {
        //     map1.fitBounds($scope.viewport);
        // }
        // google.maps.event.addListener(map1, 'idle', function() {
        //     $scope.map_fit_bounds = '';
        //     $scope.zoom = map1.getZoom();

        //     var zoom = map1.getZoom();
        //     var bounds = map1.getBounds();
        //     var minLat = bounds.getSouthWest().lat();
        //     var minLong = bounds.getSouthWest().lng();
        //     var maxLat = bounds.getNorthEast().lat();
        //     var maxLong = bounds.getNorthEast().lng();
        //     var cLat = bounds.getCenter().lat();
        //     var cLong = bounds.getCenter().lng();

        //     $scope.cLat = bounds.getCenter().lat();
        //     $scope.cLong = bounds.getCenter().lng();

        //     map_display = $(".map1").css('display');
        //     if (map_display != 'none') {
        //         $scope.map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;
        //     } else {
        //         $scope.map_lat_long = '';
        //     }
        //     var redo_search = '';
        //     $('.map-auto-refresh-checkbox:checked').each(function(i) {
        //         redo_search = $(this).val();
        //     });
        //     //alert(redo_search);
        //     if (redo_search == 'true') {
        //         // if(map_search_first!='Yes')
        //         // $scope.search_result();
        //     } else {
        //         $(".map-auto-refresh").addClass('hide');
        //         $(".map-manual-refresh").removeClass('hide');
        //     }
        // });

         map = new google.maps.Map(document.getElementById("map_canvas"), mapProp);
        if (latitude != 0 && longitude != 0) {
            map.fitBounds($scope.viewport);
        }
        google.maps.event.addListener(map, 'idle', function() {
            $scope.map_fit_bounds = '';
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();

            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();

            map_display = $(".map").css('display');
            if (map_display != 'none') {
                $scope.map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;
            } else {
                $scope.map_lat_long = '';
            }
            var redo_search = '';
            $('.map-auto-refresh-checkbox:checked').each(function(i) {
                redo_search = $(this).val();
            });
            //alert(redo_search);
            if (redo_search == 'true') {
                // if(map_search_first!='Yes')
                // $scope.search_result();
            } else {
                $(".map-auto-refresh").addClass('hide');
                $(".map-manual-refresh").removeClass('hide');
            }
        });

        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, map);

        map.controls[google.maps.ControlPosition.LEFT_TOP].push(homeControlDiv);

        google.maps.event.addListener(map, 'dragend', function() {
            search_on_map='Yes';
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();

            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();

            var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;

            $scope.map_lat_long = map_lat_long;
            var redo_search = '';
            $('.map-auto-refresh-checkbox:checked').each(function(i) {
                redo_search = $(this).val();
            });

            if (redo_search == 'true') {
                $scope.search_result();
            } else {
                $(".map-auto-refresh").addClass('hide');
                $(".map-manual-refresh").removeClass('hide');
            }
        });
        $scope.infowindow = '';
        function fixInfoWindow() {
            //Here we redefine set() method.
            //If it is called for map option, we hide InfoWindow, if "noSupress" option isnt true.
            //As Google doesn't know about this option, its InfoWindows will not be opened.
            var set = google.maps.InfoWindow.prototype.set;
            google.maps.InfoWindow.prototype.set = function (key, val) {
                if (key === 'map') {
                    if (!this.get('noSupress')) {
                        // console.log('This InfoWindow is supressed. To enable it, set "noSupress" option to true');
                        $scope.infowindow = this;
                        // return;
                    }
                }
                set.apply(this, arguments);
            }
        }
        fixInfoWindow();

        google.maps.event.addListener(map, 'click', function() {

            if ($scope.marker_click > 0) {
                $scope.marker_click = 0;
                // return true;
            }

            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
            if($scope.infowindow != '')
            {
                $scope.infowindow.close();   
            }
        });
        google.maps.event.addListenerOnce(map, 'mousemove', function(){
        google.maps.event.addListener(map, 'zoom_changed', function() {
            search_on_map='Yes';
            
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();
            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();
            var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;

            $scope.map_lat_long = map_lat_long;
            var redo_search = '';
            $('.map-auto-refresh-checkbox:checked').each(function(i) {
                redo_search = $(this).val();
            });

            if (redo_search == 'true') {
                $scope.search_result();
            } else {
                $(".map-auto-refresh").addClass('hide');
                $(".map-manual-refresh").removeClass('hide');
            }
        });
    });

        //marker(response);
    }

    function HomeControl(controlDiv, map) {
        var controlText = document.createElement('div');
        controlText.style.position = 'relative';
        controlText.style.padding = '5px';
        controlText.style.margin = '-65px 0px 0px 50px';
        controlText.style.fontSize = '14px';
        controlText.innerHTML = '<div class="map-refresh-controls google"><a   class="map-manual-refresh btn btn-primary  hide" style="background-color:#ff5a5f;color: #ffffff;">' + $('#redo_search_value').val() + ' <i class="icon icon-refresh icon-space-left"></i></a><div class="panel map-auto-refresh"><label class="checkbox"><input type="checkbox" checked="checked" name="redo_search" value="true" class="map-auto-refresh-checkbox"><small>' + $('#current_language').val() + '</small></label></div></div>'
        controlDiv.appendChild(controlText);

        // Setup click-event listener: simply set the map to London
        google.maps.event.addDomListener(controlText, 'click', function() {});
    }
    /*Overlay Script*/
    function TxtOverlay(pos, txt, cls, map) {

        // Now initialize all properties.
        this.pos = pos;
        this.txt_ = txt;
        this.cls_ = cls;
        this.map_ = map;

        // We define a property to hold the image's
        // div. We'll actually create this div
        // upon receipt of the add() method so we'll
        // leave it null for now.
        this.div_ = null;

        // Explicitly call setMap() on this overlay
        this.setMap(map);
    }

    TxtOverlay.prototype = new google.maps.OverlayView();

    TxtOverlay.prototype.onAdd = function() {

        // Note: an overlay's receipt of onAdd() indicates that
        // the map's panes are now available for attaching
        // the overlay to the map via the DOM.

        // Create the DIV and set some basic attributes.
        var div = document.createElement('DIV');
        div.className = this.cls_;

        div.innerHTML = this.txt_;

        // Set the overlay's div_ property to this DIV
        this.div_ = div;
        var overlayProjection = this.getProjection();
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);
        div.style.left = position.x - 25 + 'px';
        div.style.top = position.y - 25 + 'px';
        // We add an overlay to a map via one of the map's panes.

        var panes = this.getPanes();
        panes.overlayMouseTarget.appendChild(div);

        var me = this;
        google.maps.event.addDomListener(div, 'click', function(event) {
            google.maps.event.trigger(me, 'click');
            event.stopPropagation();
        });
        google.maps.event.addDomListener(div, 'touchstart', function(event) {
            google.maps.event.trigger(me, 'click');
            event.stopPropagation();
        });
        google.maps.event.addDomListener(div, 'dblclick', function(event) {
            event.stopPropagation();
        });

    }
    TxtOverlay.prototype.draw = function() {


        var overlayProjection = this.getProjection();

        // Retrieve the southwest and northeast coordinates of this overlay
        // in latlngs and convert them to pixels coordinates.
        // We'll use these coordinates to resize the DIV.
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);

        var div = this.div_;
        div.style.left = position.x - 25 + 'px';
        div.style.top = position.y - 25 + 'px';
        div.style.position = 'absolute';
        div.style.cursor = 'pointer';


    }
    //Optional: helper methods for removing and toggling the text overlay.  
    TxtOverlay.prototype.onRemove = function() {
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
    }
    TxtOverlay.prototype.hide = function() {
        if (this.div_) {
            this.div_.style.visibility = "hidden";
        }
    }

    TxtOverlay.prototype.show = function() {
        if (this.div_) {
            this.div_.style.visibility = "visible";
        }
    }

    TxtOverlay.prototype.toggle = function() {
        if (this.div_) {
            if (this.div_.style.visibility == "hidden") {
                this.show();
            } else {
                this.hide();
            }
        }
    }

    TxtOverlay.prototype.toggleDOM = function() {
        if (this.getMap()) {
            this.setMap(null);
        } else {
            this.setMap(this.map_);
        }
    }
    /*Overlay Script*/
    function marker(response) {
        var checkout = $scope.checkout;
        var checkin = $scope.checkin;
        var guests = $scope.guests;     
      
        setAllMap(null);
        markers = [];
        angular.forEach(response.data, function(obj) {

            var html = '<div id="info_window_' + obj["id"] + '" class="listing listing-map-popover" data-price="' + obj["rooms_price"]["currency"]["symbol"] + '" data-id="' + obj["id"] + '" data-user="' + obj["user_id"] + '" data-url="/rooms/' + obj["id"] + '" data-name="' + obj["name"] + '" data-lng="' + obj['rooms_address']["longitude"] + '" data-lat="' + obj['rooms_address']["latitude"] + '"><div class="panel-image listing-img">';
            html += '<a class="media-photo media-cover" target="listing_' + obj["id"] + '" href="' + APP_URL + '/rooms/' + obj["id"] + '?checkin=' + checkin + '&checkout=' + checkout + '&guests=' + guests + '"><div class="listing-img-container search_page_map media-cover text-center"><img id="marker_image_' + obj["id"] + '" class="marker_image_' + obj["id"] + '" rooms_image = "" alt="' + obj["name"] + '" class="img-responsive-height" data-current="0" src="' + obj["photo_name"] + '"></div></a>';
            html += '<div class="target-prev target-control block-link marker_slider" ng-click="marker_slider($event)"  data-room_id="' + obj["id"] + '"><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + obj["id"] + '" href="' + APP_URL + '/rooms/' + obj["id"] + '?checkin=' + checkin + '&checkout=' + checkout + '&guests=' + guests + '"><div>';

            var instant_book = '';
            var bed_text = (obj['beds'] > 1) ? $scope.beds_text : $scope.bed_text;
            var per_night = $scope.per_night;

            if (obj["booking_type"] == 'instant_book')
                instant_book = '<span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach"><i class="icon icon-instant-book icon-flush-sides"></i></span>';

 
            html += '</div></a><div class="target-next target-control marker_slider block-link" ng-click="marker_slider($event)" data-room_id="' + obj["id"] + '"><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div></div>';
            html += '<div class="panel-body panel-card-section"><a class="text-normal link-reset pull-left"target="listing_' + obj["id"] + '" href="' + APP_URL + '/rooms/' + obj["id"] + '?checkin=' + checkin + '&checkout=' + checkout + '&guests=' + guests + '"><span class="category_city hm_cate"><span class="pull-left">'+obj["room_type_name"] +'</span><span class="pull-left dot-cont">·</span><span class="pull-left">'+obj["beds"] +' '+bed_text+'</span></span><br><div class="media"><h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="' + obj["name"] + '">' + obj["name"] + '</h3>';
            html += '<div class="exp_price">'+obj["rooms_price"]["currency"]["symbol"] + '<span ng-if="'+guests+'>1 && '+guests+'>'+obj["rooms_price"]["guests"]+'"ng-bind-html="'+obj["rooms_price"]["night"]+'+('+obj["rooms_price"]["additional_guest"]+'*('+guests+'-'+obj["rooms_price"]["guests"]+'))"></span><span ng-if="'+guests+'==1 || '+guests+'<='+obj["rooms_price"]["guests"]+'" ng-bind-html="'+obj["rooms_price"]["night"]+'"></span>'+' '+per_night+''; 
            if(obj.booking_type == 'instant_book')
            {
                html += '<span> <i class="icon icon-instant-book icon-beach h4"></i></span>';
            }
            html += '</div>';
            var star_rating = '';

            if (obj['overall_star_rating'] != '')
                star_rating = '' + obj['overall_star_rating'];

            var reviews_count = '';
            var review_seperator = '';
            var review_text = (obj['reviews_count'] > 1) ? $scope.reviews_text : $scope.review_text;

            if (obj['reviews_count'] != 0){
                reviews_count = ' ' + obj['reviews_count'] + ' ' + review_text;
                review_seperator = '.';
            }

            html += '<div class="text-muted listing-location text-truncate pull-left" itemprop="description">';
            html +='<span class="pull-left">' + star_rating + '</span><span class="pull-left dot-cont">'+ review_seperator +'</span><span class="pull-left">' + reviews_count + '</span></div></div></a></div></div>';
            var lat = obj["rooms_address"]["latitude"];
            var lng = obj["rooms_address"]["longitude"];
            var point = new google.maps.LatLng(lat, lng);
            var name = obj["name"];
            var currency_symbol = obj["rooms_price"]["currency"]["symbol"];
            if(guests>1 && guests>obj["rooms_price"]["guests"])
            {
            var currency_value = obj["rooms_price"]["night"]+(obj["rooms_price"]["additional_guest"]*(guests-obj["rooms_price"]["guests"]));
            }
            else{
            var currency_value = obj["rooms_price"]["night"];
            }
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                icon: getMarkerImage('normal'),
                title: name,
                zIndex: 1
            });
            customTxt = currency_symbol + currency_value;
            if (obj["booking_type"] == 'instant_book')
                customTxt = currency_symbol + currency_value + instant_book;
            txt = new TxtOverlay(point, customTxt, "customBox", map);
            //console.log(txt);
            markers.push(txt);
            google.maps.event.addListener(marker, "mouseover", function() {
                marker.setIcon(getMarkerImage('hover'));
            });
            google.maps.event.addListener(marker, "mouseout", function() {
                marker.setIcon(getMarkerImage('normal'));
            });
            createInfoWindow(txt, html);

        });

        angular.forEach($scope.place_result, function(obj) {
            var lat = obj["latitude"];
            var lng = obj["longitude"];
            var point = new google.maps.LatLng(lat, lng);

            var marker = new Marker({
                map: map,
                position: point,
                icon: ' ',
                map_icon_label: getMarkerLabel(obj['type'])
            });
            markers.push(marker);

            place_info = '<p>' + obj['name'] + '</p><p>' + obj['address_line_1'] + ' ' + obj['address_line_2'] + ', ' + obj['city'] + '</p><p>' + obj['state'] + ', ' + obj['country'] + '</p>';
            $scope.places_info.push(place_info);

            html = '<div style="font-size:16px"><h3 style="margin:0px; color:#000;">' + obj['name'] + '</h3><div class="popup-review">' + obj['reviews_star_rating_div'] + '</div><div class="address-align">' + obj['address_line_1'] + '</div><div class="address-align">' + obj['address_line_2'] + '</div><div class="address-align">' + obj['city'] + '</div><div class="address-align">' + obj['state'] + '</div><div class="address-align">' + obj['country_name'] + '</div><div class="address-align">' + obj['postal_code'] + '</div>';
            html += '<br><br><a class="review-btn-pop" href="' + APP_URL + '/add_place_reviews/place/' + obj['id'] + '" target="_blank" >Review</a>';
            html += '<div class="review-search-popup"><div onclick="reviews_popup(event, this)" class="close" >close</div>';
            angular.forEach(obj['reviews'], function(review) {
                html += '<div class="review-content flt-left">';
                html += '<div class="left-blk review-content-blk" ><img width="40" height="40" src="' + review.users_from.profile_picture.src + '" class="flt-left img-rnd" ></div>';
                html += '<div class="right-blk review-content-blk" ><div class="place_comments">' + review.place_comments + '</div><div class="place_stars" >' + review.place_review_stars_div + '</div></div>';
                html += '</div>';
            });
            html += '</div></div></div></div>';
            createPlaceInfoWindow(marker, html);

        });
    }

    function createInfoWindow(marker, popupContent) {
        infoBubble = new InfoBubble({
            maxWidth: 3000
        });

        var contentString = $compile(popupContent)($scope);
        google.maps.event.addListener(marker, 'click', function() {

            var useragent = navigator.userAgent;
            //console.log(useragent);
            //console.log(useragent.indexOf('iPhone'));

            if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('iPad') != -1 || useragent.indexOf('Android') != -1) {
                $scope.marker_click = 1;
            }
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }

            infoBubble.addTab('', contentString[0]);

            var borderRadius = 0;
            infoBubble.setBorderRadius(borderRadius);
            var maxWidth = 300;
            infoBubble.setMaxWidth(maxWidth);

            var maxHeight = 300;
            infoBubble.setMaxHeight(maxHeight);
            var minWidth = 282;
            infoBubble.setMinWidth(minWidth);

            var minHeight = 245;
            infoBubble.setMinHeight(minHeight);
            infoBubble.setPosition(marker.pos);
            infoBubble.open(map);
        });
    }

    function createPlaceInfoWindow(marker, popupContent) {
        infoBubble = new InfoBubble({
            maxWidth: 1500
        });

        var contentString = popupContent;
        google.maps.event.addListener(marker, 'click', function() {

            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 1500
                });
            }

            infoBubble.addTab('', contentString);

            var borderRadius = 0;
            infoBubble.setBorderRadius(borderRadius);
            var maxWidth = 300;
            infoBubble.setMaxWidth(maxWidth);

            var maxHeight = 250;
            infoBubble.setMaxHeight(maxHeight);
            var minWidth = 300;
            infoBubble.setMinWidth(minWidth);

            var minHeight = 250;
            infoBubble.setMinHeight(minHeight);

            infoBubble.open(map, marker);
        });
    }

    function getMarkerImage(type) {
        var image = '';

        if (type == 'hover')
            image = '';

        var gicons = new google.maps.MarkerImage("images/" + image,
            new google.maps.Size(50, 50),
            new google.maps.Point(0, 0),
            new google.maps.Point(9, 20));

        return gicons;

    }

    function setAllMap(map) {
        if (infoBubble != undefined) {
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
        }
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
    // var infoBubble;

    $('.footer-toggle').click(function() {
        $(".footer-container").slideToggle("fast", function() {
            if ($(".footer-container").is(":visible")) {
                $('.open-content').hide();
                $('.close-content').show();
            } else {
                $('.open-content').show();
                $('.close-content').hide();
            }
        });
    });

    $(document).on('click', '.map-manual-refresh', function() {
        $(".map-manual-refresh").addClass('hide');
        $(".map-auto-refresh").removeClass('hide');
        $scope.search_result();
    });
    $(document).on('click', '.rooms-slider', function() {

        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#rooms_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#rooms_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '') {
            // $(this).parent().addClass("loading");
            $http.post('rooms_photos', {
                    rooms_id: rooms_id
                })
                .then(function(response) {
                    angular.forEach(response.data, function(obj) {
                        if ($.trim(dataurl) == '') {
                            dataurl = obj['name'];
                        } else
                            dataurl = dataurl + '^>' + obj['name'];
                    });

                    $("#rooms_image_" + rooms_id).attr("rooms_image", dataurl);
                    var all_image = dataurl.split('^>');
                    var rooms_img_count = all_image.length;
                    var i = 0;
                    var set_img_no = '';
                    angular.forEach(all_image, function(img) {
                        if ($.trim(img) == $.trim(img_url)) {
                            set_img_no = i;
                        }
                        i++;
                    });
                    if ($(this).is(".target-prev") == true) {
                        var cur_img = set_img_no - 1;
                        var count = rooms_img_count - 1;
                    } else {
                        var cur_img = set_img_no + 1;
                        var count = 0;
                    }

                    if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
                        var img = all_image[cur_img];
                    } else {

                        var img = all_image[count];
                    }

                    var set_img_url = img;

                    // $(".panel-image").removeClass("loading");
                    $("#rooms_image_" + rooms_id).attr("src", set_img_url);
                });
        } else {
            $(this).parent().addClass("loading");
            var all_image = dataurl.split('^>');
            var rooms_img_count = all_image.length;
            var i = 0;
            var set_img_no = '';
            angular.forEach(all_image, function(img) {
                if ($.trim(img) == $.trim(img_url)) {
                    set_img_no = i;
                }
                i++;
            });
            if ($(this).is(".target-prev") == true) {
                var cur_img = set_img_no - 1;
                var count = rooms_img_count - 1;
            } else {
                var cur_img = set_img_no + 1;
                var count = 0;
            }

            if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
                var img = all_image[cur_img];
            } else {
                var img = all_image[count];
            }
            var set_img_url =img;

            $(".panel-image").removeClass("loading");
            $("#rooms_image_" + rooms_id).attr("src", set_img_url);

        }

    });

    $scope.marker_slider = function($event) {

        $event.stopPropagation();
        var this_elm = angular.element($event.currentTarget);
        var rooms_id = $($event.currentTarget).attr("data-room_id");
        var dataurl = $("#marker_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#marker_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '') {
            // $($event.currentTarget).parent().addClass("loading");
            $http.post('rooms_photos', {
                    rooms_id: rooms_id
                })
                .then(function(response) {
                    angular.forEach(response.data, function(obj) {
                        if ($.trim(dataurl) == '') {
                            dataurl = obj['name'];
                        } else
                            dataurl = dataurl + '^>' + obj['name'];
                    });

                    $("#marker_image_" + rooms_id).attr("rooms_image", dataurl);
                    var all_image = dataurl.split('^>');
                    var rooms_img_count = all_image.length;
                    var i = 0;
                    var set_img_no = '';
                    angular.forEach(all_image, function(img) {
                        if ($.trim(img) == $.trim(img_url)) {
                            set_img_no = i;
                        }
                        i++;
                    });
                    if ($($event.currentTarget).is(".target-prev") == true) {
                        var cur_img = set_img_no - 1;
                        var count = rooms_img_count - 1;
                    } else {
                        var cur_img = set_img_no + 1;
                        var count = 0;
                    }

                    if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
                        var img = all_image[cur_img];
                    } else {

                        var img = all_image[count];
                    }

                    var set_img_url = img;

                    // $(".panel-image").removeClass("loading");
                    $("#marker_image_" + rooms_id).attr("src", set_img_url);
                });
        } else {
            $($event.currentTarget).parent().addClass("loading");
            var all_image = dataurl.split('^>');
            var rooms_img_count = all_image.length;
            var i = 0;
            var set_img_no = '';
            angular.forEach(all_image, function(img) {
                if ($.trim(img) == $.trim(img_url)) {
                    set_img_no = i;
                }
                i++;
            });
            if ($($event.currentTarget).is(".target-prev") == true) {
                var cur_img = set_img_no - 1;
                var count = rooms_img_count - 1;
            } else {
                var cur_img = set_img_no + 1;
                var count = 0;
            }

            if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
                var img = all_image[cur_img];
            } else {
                var img = all_image[count];
            }
            var set_img_url = img;

            $(".panel-image").removeClass("loading");
            console.log(set_img_url,rooms_id);
            $(".marker_image_" + rooms_id).attr("src", set_img_url);

        }

    }
        var min_price_checks    = getParameterByName('min_price');
        var max_price_checks = getParameterByName('max_price');
        var room_type_checks = getParameterByName('room_type');
        var property_type_checks = getParameterByName('property_type');
        var amenities_checks = getParameterByName('amenities');
        var checkin_checks = getParameterByName('checkin');
        var checkout_checks = getParameterByName('checkout');
        var guests_checks = getParameterByName('guests');
        var beds_checks = getParameterByName('beds');
        var bathrooms_checks = getParameterByName('bathrooms');
        var bedrooms_checks = getParameterByName('bedrooms');

$(document).ready(function(){
    $('#checkin,#checkout,#slider').click(function(){        
         if ($(window).width() < 760)
            {    if(min_price_checks != '' || max_price_checks != ''|| room_type_checks != ''|| property_type_checks != ''||
                   amenities_checks != ''|| checkin_checks != ''|| checkout_checks != ''|| guests_checks != ''||beds_checks != ''
                   || bathrooms_checks != ''|| bedrooms_checks != ''){
                    $('#more_filter_submit').removeAttr('disabled');
                   }else{
                    $('#more_filter_submit').attr('disabled', 'disabled');
                   }
            } 
    });
});
$(document).on('change','#room-options,#instant_book,#min_max_pricerange,#guest-select', function()
    {   
            if ($(window).width() < 760)
            {
                if(min_price_checks != '' || max_price_checks != ''|| room_type_checks != ''|| property_type_checks != ''||
                   amenities_checks != ''|| checkin_checks != ''|| checkout_checks != ''|| guests_checks != ''||beds_checks != ''
                   || bathrooms_checks != ''|| bedrooms_checks != ''){
                $('#more_filter_submit').removeAttr('disabled');
              }else{
                    $('#more_filter_submit').attr('disabled', 'disabled');
                   }
            }         
    });

    $(document).on('change', '[id^="map-search"]', function() {       
        var amenities_check = getParameterByName('amenities');
        var propertytype_check = getParameterByName('property_type');
        var i = 0;
        $('[id^="map-search"]').each(function() {
            if ($(this).is(':checkbox')) {
                if ($(this).is(':checked')) {
                    i++;
                } else if (amenities_check != '' || propertytype_check != '') {

                    i++;
                }
            } else if ($(this).val() != '') {
                i++
            }
        });

        if (i == 0) {
            if ($(window).width() < 760) {
                $('#more_filter_submit').removeAttr('disabled');
            } else {
                $('#more_filter_submit').attr('disabled', 'disabled');
            }
        } else {
            $('#more_filter_submit').removeAttr('disabled');
        }

    });

    $(document).on('click', '#cancel-filter', function() {

        var split_prop = prop_app_fil.split(',');
        var split_amen = amen_app_fil.split(',');


        $('[id^="map-search"]').each(function() {
            if ($(this).is(':checkbox')) {
                if ($(window).width() > 760) {
                    if (((jQuery.inArray($(this).val(), split_prop) != '-1' && $(this).hasClass('property_type')) || (jQuery.inArray($(this).val(), split_amen) != '-1' && $(this).hasClass('amenities')))) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                }
            } else {
                if ($(window).width() > 760) {
                    $(this).val('');
                }
            }
        });

        if ($(window).width() > 760) {

            $('#map-search-min-bedrooms').val(broom_app_fil);
            $('#map-search-min-bathrooms').val(bath_app_fil);
            $('#map-search-min-beds').val(bed_app_fil);

        }

        $('#more_filter_submit').attr('disabled', 'disabled');
        $(".toggle-hide").css("display", "block");
        $(".toggle-group").css("display", "none");
        $('.filter-div').hide();
        $('.button_ipunk').show();
        $('.button_ipunk4').show();
        $(".sidebar").css("height", "100%");
        $scope.search_result();
    });

}]);
$('.show-filters').click(function() {
    $('.sidebar').addClass('fixed-hieght');

});
$('#cancel-filter').click(function() {
    $('.sidebar').removeClass('fixed-hieght');

});
$('#more_filter_submit').click(function() {
    if ($(window).width() < 480) {
        $('.filter-div').hide();
        $('.button_ipunk').show();
        $('.button_ipunk4').show();
    }
});