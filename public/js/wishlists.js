app.controller('wishlists', ['$scope', '$http', '$filter', function($scope, $http, $filter) {

    $scope.wishlists_homes=[];
    $scope.wishlists_experience=[];
    $scope.common_loading=0;
    $scope.active_wishlist="homes";
    $scope.wishlist_count=0;
    $scope.infowindow = new google.maps.InfoWindow();
    $scope.get_wishlists_home = function()
    {
        $('.tab-btn').prop('disabled', true);
        $scope.wishlists_homes=[];
        $scope.wishlists_experience=[];
        $scope.common_loading=1;
        setTimeout(function(){
        $http.post(APP_URL + '/get_wishlists_home', {id: $("#wl_id").val()}).then(function(response)
        {
            if(response.data[0].all_rooms_count)
            {
                $('.tab-btn').prop('disabled', false);
                $scope.wishlists_homes=response.data[0].saved_wishlists;
                $scope.wishlist_count=response.data[0].saved_wishlists.length;
                $scope.common_loading=0;
            }
            else
            {
                $scope.setwishlisttype("experience");
            }
        });
        }, 1000);
    }
    $scope.get_wishlists_experience = function()
    {
        $('.tab-btn').prop('disabled', true);
        $scope.wishlists_homes=[];
        $scope.wishlists_experience=[];
        $scope.common_loading=1;
        $http.post(APP_URL + '/get_wishlists_experience', {id: $("#wl_id").val()}).then(function(response)
        {
            $('.tab-btn').prop('disabled', false);
            $scope.wishlists_experience=response.data[0].saved_wishlists;
            $scope.wishlist_count=response.data[0].saved_wishlists.length;
            $scope.common_loading=0;
        });
    }
    $scope.setwishlisttype = function(menuItem) {
        
        if(menuItem=="homes")
        {
            $scope.get_wishlists_home();
            $scope.get_map_home();
        }
        else
        {
            $scope.get_wishlists_experience();
            $scope.get_map_experience();
        }
        $scope.active_wishlist = menuItem;
    }
    $scope.delete_wishlist_home=function(index,item)
    {
        var room_id = $scope.wishlists_homes[index].room_id;
        var s="#noteloader_"+index;
        $(s).show();
        $http.post(APP_URL + '/remove_saved_wishlist/' + $("#wl_id").val(), {
            room_id: room_id,type:'Rooms'
        }).then(function(response) {
            if(response.data.length)
            {
                $scope.wishlist_count=response.data[0].rooms_count;
                item.splice(index, 1);   
            }
            else
            {
                $scope.wishlist_count = 0;
                $scope.wishlists_homes=[];
            }
        });
    }
    $scope.delete_experience_home=function(index,item)
    {
        var room_id = $scope.wishlists_experience[index].room_id;
        var s="#noteloader_"+index;
        $(s).show();
        $http.post(APP_URL + '/remove_saved_wishlist/' + $("#wl_id").val(), {
            room_id: room_id,type:'Experiences'
        }).then(function(response) {
            if(response.data.length)
            {
                $scope.wishlist_count=response.data[0].host_experience_count;
                item.splice(index, 1);
            }
            else
            {
                $scope.wishlist_count = 0;
                $scope.wishlists_experience=[];
            }
        });
    }
    $scope.add_home_note=function(room_id,index)
    {
        var s="#noteloader_"+index;
        $(s).show();
        $http.post(APP_URL + '/add_note_wishlist/' + $("#wl_id").val(), {
            room_id: room_id,
            note: $('#note_' + room_id).val()
        }).then(function(response) {
            $(s).hide();
        });
    }
    $scope.get_wishlists_home();
    $scope.get_map_home = function()
    {
        $("#results_map").addClass("loading");
        $http.post(APP_URL + '/get_wishlists_home', {id: $("#wl_id").val()}).then(function(response)
        {
            initialize(response.data[0].saved_wishlists);
            $("#results_map").removeClass("loading");
        });
        
    }
    $scope.get_map_experience = function()
    {
        $("#results_map").addClass("loading");
        $http.post(APP_URL + '/get_wishlists_experience', {id: $("#wl_id").val()}).then(function(response)
        {
            initialize(response.data[0].saved_wishlists);
            $("#results_map").removeClass("loading");
        });
        
    }


    $('.create').click(function() {
        $('.modal-transitions').removeClass('hide');
    });

    $('.cancel').click(function(event) {
        event.preventDefault();
        $('.modal-transitions').addClass('hide');
        event.preventDefault();
    });

    $('#map').click(function() {
        $('.results-map').show();
        $('.results-list').hide();
        $('#map').prop('disabled', true);
        $('#list').prop('disabled', false);
        if($scope.active_wishlist=="homes")
        {
            $scope.get_map_home();
        }
        else
        {
            $scope.get_map_experience();
        }
    });

    $('#list').click(function() {
        $('.results-list').show();
        $('.results-map').hide();
        $('#list').prop('disabled', true);
        $('#map').prop('disabled', false);
    });

    function initialize(data) {
        var myOptions = {
            zoom: 10,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("results_map"), myOptions);
        if($scope.active_wishlist=="homes")
        {
            setMarkers(map, data);
        }
        else
        {
            sethostexperienceMarkers(map, data);    
        }

        google.maps.event.addListener(map, 'click', function() {
            if($scope.infowindow != '')
            {
                $scope.infowindow.close();   
            }
        });
    }
    function sethostexperienceMarkers(map, locations) {

        var marker, i;
        var bounds = new google.maps.LatLngBounds();
        // var infowindow = new google.maps.InfoWindow();

        for (i = 0; i < locations.length; i++) {
            var title = locations[i]['host_experiences']['title'];
            var lat = locations[i]['host_experiences']['host_experience_location']['latitude'];
            var long = locations[i]['host_experiences']['host_experience_location']['longitude'];
            var address = locations[i]['host_experiences']['host_experience_location']['city'];
            var image = locations[i]['host_experiences']['photo_name'];
            var list_id = locations[i]['host_experiences']['id'];
            var user_id = locations[i]['host_experiences']['user_id'];
            var price = locations[i]['host_experiences']['session_price'];
            var currency_code = locations[i]['host_experiences']['currency_code'];
            var currency_symbol = locations[i]['host_experiences']['currency']['symbol'];
            var wishlist_img = locations[i]['photo_name'];

            latlngset = new google.maps.LatLng(lat, long);

            var content = '<div id="info_window_' + list_id + '" class="listing listing-map-popover" data-price="' + currency_symbol + '" data-id="' + list_id + '" data-user="' + user_id + '" data-url="/experiences/' + list_id + '" data-name="' + title + '" data-lng="' + long + '" data-lat="' + lat + '"><div class="panel-image listing-img">';
            content += '<a class="media-photo media-cover" target="listing_' + list_id + '" href="' + APP_URL + '/experiences/' + list_id + '"><div class="listing-img-container media-cover text-center"><img id="experience_marker_image_' + list_id + '" rooms_image = "" alt="' + title + '" class="img-responsive-height" data-current="0" src="' + image + '"></div></a>';
            content += '<div class="target-prev target-control block-link experience_marker_slider"  data-room_id="' + list_id + '"><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + list_id + '" href="' + APP_URL + '/experiences/' + list_id + '"><div>';
            content += '<sup class="h6 text-contrast">' + currency_symbol + '</sup><span class="h3 text-contrast price-amount">' + price + '</span><sup class="h6 text-contrast"></sup></div></a><div class="target-next target-control experience_marker_slider block-link" data-room_id="' + list_id + '"><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div></div>';
            content += '<div class="panel-body panel-card-section wishlist-panel-card-section"><div class="media"><h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="' + title + '">' + title + '</a></h3>';

            var marker = new google.maps.Marker({
                map: map,
                title: title,
                content: content,
                position: latlngset,
                icon: getMarkerImage('normal'),
            });

            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', function() {
                $scope.infowindow.setContent(this.content);
                $scope.infowindow.open(map, this);
            });
        }

        var listener = google.maps.event.addListener(map, "idle", function() {
            map.setZoom(3);
            map.fitBounds(bounds);
            google.maps.event.removeListener(listener);
        });

    }
    function setMarkers(map, locations) {

        var marker, i;
        var bounds = new google.maps.LatLngBounds();
        // var infowindow = new google.maps.InfoWindow();

        for (i = 0; i < locations.length; i++) {
            var title = locations[i]['rooms']['name'];
            var lat = locations[i]['rooms']['rooms_address']['latitude'];
            var long = locations[i]['rooms']['rooms_address']['longitude'];
            var address = locations[i]['rooms']['rooms_address']['city'];
            var image = locations[i]['rooms']['photo_name'];
            var list_id = locations[i]['room_id'];
            var user_id = locations[i]['rooms']['user_id'];
            var price = locations[i]['rooms_price']['night'];
            var currency_code = locations[i]['rooms_price']['currency_code'];
            var currency_symbol = locations[i]['rooms_price']['currency']['symbol'];
            var wishlist_img = locations[i]['rooms']['photo_name'];
            var booking_type = locations[i]['rooms']['booking_type'];

            latlngset = new google.maps.LatLng(lat, long);

            var content = '<div id="info_window_' + list_id + '" class="listing listing-map-popover" data-price="' + currency_symbol + '" data-id="' + list_id + '" data-user="' + user_id + '" data-url="/rooms/' + list_id + '" data-name="' + title + '" data-lng="' + long + '" data-lat="' + lat + '"><div class="panel-image listing-img">';
            content += '<a class="media-photo media-cover" target="listing_' + list_id + '" href="' + APP_URL + '/rooms/' + list_id + '"><div class="listing-img-container media-cover text-center"><img id="marker_image_' + list_id + '" rooms_image = "" alt="' + title + '" class="img-responsive-height" data-current="0" src="' + image + '"></div></a>';
            content += '<div class="target-prev target-control block-link marker_slider"  data-room_id="' + list_id + '"><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + list_id + '" href="' + APP_URL + 'rooms/' + list_id + '"><div>';

            instant_book = '';

            if (booking_type == 'instant_book')
                instant_book = '<span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach"><i class="icon icon-instant-book icon-flush-sides"></i></span>';

            content += '<sup class="h6 text-contrast">' + currency_symbol + '</sup><span class="h3 text-contrast price-amount">' + price + '</span><sup class="h6 text-contrast"></sup>' + instant_book + '</div></a><div class="target-next target-control marker_slider block-link" data-room_id="' + list_id + '"><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div></div>';
            content += '<div class="panel-body panel-card-section wishlist-panel-card-section"><div class="media"><h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="' + title + '">' + title + '</a></h3>';

            var marker = new google.maps.Marker({
                map: map,
                title: title,
                content: content,
                position: latlngset,
                icon: getMarkerImage('normal'),
            });

            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', function() {
                $scope.infowindow.setContent(this.content);
                $scope.infowindow.open(map, this);
            });
        }

        var listener = google.maps.event.addListener(map, "idle", function() {
            map.setZoom(3);
            map.fitBounds(bounds);
            google.maps.event.removeListener(listener);
        });

    }

    function getMarkerImage(type) {
        var image = 'map-pin-set-3460214b477748232858bedae3955d81.png';

        if (type == 'hover')
            image = 'hover-map-pin-set-3460214b477748232858bedae3955d81.png';

        var gicons = new google.maps.MarkerImage(APP_URL + "/images/" + image,
            new google.maps.Size(50, 50),
            new google.maps.Point(0, 0),
            new google.maps.Point(9, 20));

        return gicons;

    }

    $(document).on('click', '.experience_marker_slider', function() {
        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#experience_marker_image_" + rooms_id + ",#experience_wishlist_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#experience_marker_image_" + rooms_id + ",#experience_wishlist_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '') {
            $(this).parent().addClass("loading");
            $http.post(APP_URL + '/host_experience_photos', {
                    rooms_id: rooms_id
                })
                .then(function(response) {
                    angular.forEach(response.data, function(obj) {
                        if ($.trim(dataurl) == '') {
                            dataurl = obj['image_url'];
                        } else
                            dataurl = dataurl + '^>' + obj['image_url'];
                    });

                    $("#experience_marker_image_" + rooms_id + ",#experience_wishlist_image_" + rooms_id).attr("rooms_image", dataurl);
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

                    $(".panel-image").removeClass("loading");
                    $('.listing_slideshow_thumb_view').removeClass("loading");
                    $("#experience_marker_image_" + rooms_id + ",#experience_wishlist_image_" + rooms_id).attr("src", set_img_url);
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
            var set_img_url = img;

            $(".panel-image").removeClass("loading");
            $('.listing_slideshow_thumb_view').removeClass("loading");
            $("#experience_marker_image_" + rooms_id + ",#experience_wishlist_image_" + rooms_id).attr("src", set_img_url);

        }

    });
    $(document).on('click', '.marker_slider', function() {
        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '') {
            $(this).parent().addClass("loading");
            $http.post(APP_URL + '/rooms_photos', {
                    rooms_id: rooms_id
                })
                .then(function(response) {
                    angular.forEach(response.data, function(obj) {
                        if ($.trim(dataurl) == '') {
                            dataurl = obj['name'];
                        } else
                            dataurl = dataurl + '^>' + obj['name'];
                    });

                    $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("rooms_image", dataurl);
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

                    $(".panel-image").removeClass("loading");
                    $('.listing_slideshow_thumb_view').removeClass("loading");
                    $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src", set_img_url);
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
            var set_img_url = img;

            $(".panel-image").removeClass("loading");
            $('.listing_slideshow_thumb_view').removeClass("loading");
            $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src", set_img_url);

        }

    });

    $('.edit-button').click(function() {
        $('.show_view').hide();
        $('.edit_view').show();
    });

    $('.edit_view .cancel').click(function() {
        $('.show_view').show();
        $('.edit_view').hide();
    });

    $('.edit_view .delete').click(function() {
        if (confirm("Are you sure?")) {
            window.location.href = APP_URL + "/delete_wishlist/" + wishlist_id;
        }
    });


    $('.share-envelope-btn').click(function() {
        $('.modal-container').show();
    });

    $('.modal-close').click(function() {
        $('.modal-container').hide();
    });

    // $(document).on('click', '[id^="wishlist-widget-icon-"]', function() {
    $('[id^="wishlist-widget-icon-"]').click(function() {
        if (typeof USER_ID == 'object') {
            window.location.href = APP_URL + '/login';
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
        $http.get(APP_URL + "/wishlist_list?id=" + $(this).data('room_id')+'&type=Rooms', {}).then(function(response) {
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
        $('.wl-modal__modal').show();
    });

}]);