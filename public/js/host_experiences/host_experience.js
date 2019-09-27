app.service('fileUploadService', function ($http, $q) {
  var fileUploadService = this;

  this.process_responses = function(response)
  {
    if(response.data)
    {
      response_data = response.data;
    }
    else
    {
      response_data = response;
    }
    if(response.status == 300)
    {
      $('#btnSubmit').prop('disabled', true);
      $(".login_popup_head").trigger("click");
    }
    if(response_data.status == 503) 
    {
      window.location.href = response_data.location;
    }
  }

  this.uploadFileToUrl = function (file, uploadUrl, data) {
    var fileFormData = new FormData();
    fileFormData.append('file', file);
    if(data){
      $.each(data, function(i, v){
        fileFormData.append(i, v);
      })
    }
    var deffered = $q.defer();
    var getProgressListener = function(deffered) {
      return function(event) {
        eventLoaded = event.loaded;
        eventTotal = event.total;
        percentageLoaded = ((eventLoaded/eventTotal)*100);
        deffered.notify(Math.round(percentageLoaded));
      };
    };
    $.ajax({
      type: 'POST',
      url: uploadUrl,
      data: fileFormData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(response, textStatus, jqXHR) {
        deffered.resolve(response);
        fileUploadService.process_responses(response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        //deffered.reject(errorThrown);
        deffered.resolve(errorThrown);
      },
      xhr: function() {
        var myXhr = $.ajaxSettings.xhr();
        if (myXhr.upload) {
         myXhr.upload.addEventListener(
          'progress', getProgressListener(deffered), false);
       }
       return myXhr;
     }
   });
   return deffered.promise;
  };
});
app.controller('host_experiences', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
  
  $(document).on('click', '.js-delete-photo-btn', function() {
  $scope.id = $(this).attr('id');
  });

  $scope.delete_experience = function(delete_experience, delete_message) {
       
        $('#js-error .panel-header').text(delete_experience);
        $('#js-error .panel-body').text(delete_message);
        $('.js-delete-photo-confirm').removeClass('hide');
        $('#js-error').attr('aria-hidden', false);
    };

    $(document).on('click', '.js-delete-photo-confirm', function() {
        var host_experience_id = $scope.id;
        window.location.href = 'delete_host_experience/'+host_experience_id;
    });

    $(document).on('click', '.modal-close, [data-behavior="modal-close"]', function() {
        $('.modal').fadeOut();
        $('.modal').attr('aria-hidden', 'true');
    });
  $scope.new_experience_navigate = function()
  {
    scrollTop = $("#create_host_experience_div").offset().top;
    $('html,body').animate({
      scrollTop: scrollTop},
    'slow');
  }
  $("#new_host_experience").submit(function(event) {
      if (typeof USER_ID == 'object') {
          $(".login_popup_head").trigger("click");
          city = $("#input_city").val();
          $http.get(APP_URL+'/host/experiences/set_city?city='+city);
          event.preventDefault();
          return false;
      }
  })
}]);
app.controller('host_experiences_payment', ['$scope', '$http', '$compile', 'fileUploadService', function($scope, $http, $compile, fileUploadService) {
  $scope.locale_string = function(string, data)
  {
    if(!data)
    {
      data = {};
    }
    $.each(data, function(i, v)
    {
      string = string.replace(':'+i, v);
    });
    return string;
  }
  $scope.http_post = function(url, data, callback)
  {
    if(!data)
    {
      data = {};
    }
    $http.post(url, data).then(function(response){
      fileUploadService.process_responses(response);
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
  $(document).ready(function(){
  });
  $.validator.messages.required = $scope.locale_string(validation_messages.filled, {'attribute' : '' });
  $.validator.messages.email = $scope.locale_string(validation_messages.email, {'attribute' : '' });
  $scope.v = $("#host_experience_payment_form").validate({
    ignore: ':hidden:not(.do-not-ignore)',
    rules: {},
    messages: {},
    errorElement: "span",
    errorClass: "text-danger",
    // errorPlacement: function( label, element ) {}
  });
  $scope.get_query_string = function()
  {
    query_string = '';
    query_string += '?scheduled_id='+$scope.scheduled_id;
    if($scope.token != ''){
      query_string += '&token='+$scope.token;
    }
    return query_string;
  }
  $scope.go_to_tab = function(tab_index)
  {
    tab = $scope.payment_tabs[tab_index];
    $scope.current_tab = tab.tab;
    $scope.current_tab_index = tab_index;
    $(".tab-content").hide();
    $("#tab-content-"+tab.tab).show();

    url = $scope.base_url+'/'+tab.tab+$scope.get_query_string();
    if($scope.is_mobile=='')
      window.history.replaceState({path: url}, '', url);
  }
  $scope.next_step =function()
  {
    tab = $scope.payment_tabs[$scope.current_tab_index];
    if($scope.v.form())
    {
      if($scope.current_tab_index == 1)
      {
        $scope.guest_details_changed();
      }
      if($scope.current_tab_index == 2)
      {
        $('#host_experience_payment_form').submit();
        $('#btnSubmit').prop('disabled', true);
      }
      next_tab_index = $scope.current_tab_index+1;
      $scope.go_to_tab(next_tab_index);
    }
  }
  $scope.add_guest = function()
  {
    $("#price_data").addClass('dot-loading');
    $(".reguest .foradd").addClass('dot-loading');
    $scope.payment_data.guest_details.push({first_name: '' , last_name: '', email : ''});
    $scope.guest_details_changed();
  }
  $scope.remove_guest = function(index)
  {
    $("#price_data").addClass('dot-loading');
    $(".reguest .foradd").addClass('dot-loading');
    $scope.payment_data.guest_details.splice(index, 1);
    $scope.guest_details_changed();
  }
  $scope.update_payment_data = function(callback)
  { 
    url = $scope.base_url+'/update_payment_data'+$scope.get_query_string();
    $scope.http_post(url, {payment_data : $scope.payment_data}, function(response_data){
      $scope.payment_data = response_data.payment_data;
      callback(response_data);
    });
  }
  $scope.guest_details_changed = function()
  {
    $scope.update_payment_data(function(response_data){
      $("#price_data").removeClass('dot-loading');
      $(".reguest .foradd").removeClass('dot-loading');
    });
  }
  $scope.apply_coupon_code = function()
  {
    $('#price_data').addClass('dot-loading');
    $scope.update_payment_data(function(response_data){
      if($scope.payment_data['total'] == 0){
        $scope.paymode = 'Coupon_code';
      }
      $('#price_data').removeClass('dot-loading');
    });
  }
  $scope.coupon_code_changed = function()
  {
    $scope.payment_data.coupon_code_applied = false;
  }
  $scope.remove_coupon_code = function()
  {
    $scope.payment_data.coupon_code = '';
    $scope.payment_data.coupon_code_error= '';
    $scope.payment_data.is_coupon_code = false;
    $("#price_data").addClass('dot-loading');
    $scope.update_payment_data(function(response_data){
      $("#price_data").removeClass('dot-loading');
    });
  }
  $scope.format_date =function(date, format)
  {
    format = daterangepicker_format;
    return moment(date,'YYYY-MM-DD').format(format);
  }
  $scope.format_time =function(time, format)
  {
    return moment.utc(time,'HH:mm:ss').format(format);
  }
}]);
app.controller('host_experience_details', ['$scope', '$http', '$compile', 'fileUploadService','$filter', function($scope, $http, $compile, fileUploadService,$filter) {
  $(document).on('click','.share-copy-label',function(){
    var $temp = $("<input>");
    $("#copy_div").append($temp);
    $temp.val($(this).attr('data-copy')).select();
    document.execCommand("copy");
    $temp.remove();
    $scope.link_copied=1; 
    $scope.$digest();
  });
  $(document).on('click', '.wishlist_save', function() {
    if(typeof USER_ID == 'object') {
      window.location.href = APP_URL+'/login';
      return false;
    }

    $('.wl-modal__modal').removeClass('hide');
    $('.wl-modal__col:nth-child(2)').addClass('hide');
    $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
    $http.get(APP_URL+"/wishlist_list?id="+$scope.host_experience_id+'&type=Experiences', {  }).then(function(response) 
    {
      $('#wish-list-signup-container').remove();
      $('.wl-modal__col:nth-child(2)').removeClass('hide');
      $scope.wishlist_list = response.data;
    });
  });
  $scope.wishlist_row_select = function(index) 
  {
    $http.post(APP_URL+"/save_wishlist_experience", { data: $scope.host_experience_id, wishlist_id: $scope.wishlist_list[index].id, saved_id: $scope.wishlist_list[index].saved_id }).then(function(response) 
    {
      if(response.data == 'null')
        $scope.wishlist_list[index].saved_id = null;
      else
        $scope.wishlist_list[index].saved_id = response.data;
    });

    if($('#wishlist_row_'+index).hasClass('text-dark-gray'))
      $scope.wishlist_list[index].saved_id = null;
    else
      $scope.wishlist_list[index].saved_id = 1;
  };

  $(document).on('submit', '.wl-modal-footer__form', function(event) {
      event.preventDefault();
      $('.wl-modal__col:nth-child(2)').addClass('hide');
      $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
      $http.post(APP_URL+"/wishlist_create", { data: $('.wl-modal-footer__input').val(), id: $scope.room_id }).then(function(response) 
      {
        $('.wl-modal-footer__form').addClass('hide');
        $('#wish-list-signup-container').remove();
        $('.wl-modal__col:nth-child(2)').removeClass('hide');
        $scope.wishlist_list = response.data;
        event.preventDefault();
      });
      event.preventDefault();
  });

  $('.wl-modal__modal-close').click(function()
  {
    var null_count = $filter('filter')($scope.wishlist_list, {saved_id : null});
    
    if(null_count.length == $scope.wishlist_list.length)
      $scope.wishlisted=0;
    else
      $scope.wishlisted=1;

    $scope.$apply();
    $('.wl-modal__modal').addClass('hide');
  });

  $scope.http_post = function(url, data, callback)
  {
    if(!data)
    {
      data = {};
    }
    $http.post(url, data).then(function(response){
      fileUploadService.process_responses(response);
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
  $(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 200;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = more_text_lang;
    var lesstext = "";
    $('.forpro p,.review1 p').each(function() {
      var content = $(this).html();
      if(content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
        $(this).html(html);
      }
    });
    $(".morelink").click(function(){
      if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
      } else {
        $(this).addClass("less");
        $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });
    $('.cate1').owlCarousel({
      loop:false,
      margin:15,
      responsiveClass:true,
      responsive:{
        0:{
          items:1,
          nav:true
        },
        425:{
          items:2,
          nav:true
        },
        736:{
          items:3,
          nav:true
        },
        992:{
          items:3,
          nav:true
        },
        1200:{
          items:4,
          nav:true
        }
      }
    });
    // $('#host_experience_photos_slider').owlCarousel({
    //   loop:false,
    //   responsive:{
    //     0:{
    //       items:1,
    //       nav:true
    //     }
    //   }
    // });

    
    function sticky_relocate2() {
      var window_top = $(window).scrollTop();
      var footer_top = $(".newa").offset().top;
      var div_top = $('.exphole').offset().top;

      window_height = $(window).height();
      window_bottom  = $(window).scrollTop() + $(window).height()
      document_height = $(document).height();

      cancellation_policy_div = $('.ntsbn');
      cancellation_policy_div_top = cancellation_policy_div.offset().top;
      cancellation_policy_div_height = cancellation_policy_div.height();
      cancellation_policy_div_bottom = cancellation_policy_div_top+cancellation_policy_div_height;

      sticky_div = $("#sticky");
      sticky_div_top = sticky_div.offset().top;
      sticky_div_height = sticky_div.height();
      sticky_div_bottom = sticky_div_top + sticky_div_height;

      stick_height = $("#sticky").height();
      window_height = $(window).height();
      var div_height =  stick_height < window_height ? stick_height : window_height;
      var window_width = $(window).width();

      var padding = 10;  
      
      if ((window_top + div_height > footer_top +padding) && window_width >= 768){
        top_position = (cancellation_policy_div_bottom - sticky_div_height);
        $('#sticky').css({top: (top_position - 200 ), position : 'absolute'})
      }
      else if ((window_top > div_top) &&  window_width >= 768) {
        $('#sticky').addClass('stick');
        $('#sticky').css({top: 0, position: 'fixed'})
        
      } else {
        $('#sticky').removeClass('stick');
        $('#sticky').css({position : 'relative'});
      }
    }
    $(function () {
      $(document).scroll(function(e){
        e.preventDefault();
        sticky_relocate2();
      });
      sticky_relocate2();
    });



/*
* Fix sidebar at some point and remove
* fixed position at content bottom
*/












    $(".available_dates_popup_btn").magnificPopup({
      delegate: 'a',
      removalDelay: 500, //delay removal by X to allow out-animation
      callbacks: {
        beforeOpen: function() {
          this.st.mainClass = this.st.el.attr('data-effect');
          $(this.wrap[0]).on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight -100) {
              $scope.get_available_dates();
            }
          });
        }
      },
      midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    $("#host_experience_details_map_popup_btn").magnificPopup({
      delegate: 'a',
      removalDelay: 500, //delay removal by X to allow out-animation
      callbacks: {
        open: function() {
          $scope.initialize_popup_map();
        }
      },
      midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    $(".all_reviews_popup_btn").magnificPopup({
      delegate: 'a',
      removalDelay: 500, //delay removal by X to allow out-animation
      callbacks: {
        beforeOpen: function() {
          this.st.mainClass = this.st.el.attr('data-effect');
          $(this.wrap[0]).on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight -100) {
              $scope.get_all_reviews();
            }
          });
        }
      },
      midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });
     $('#inline-popups1,#inline-popups2,#share-popups,#newshare').magnificPopup({
      delegate: 'a',
      removalDelay: 500, //delay removal by X to allow out-animation
      callbacks: {
        beforeOpen: function() {
          this.st.mainClass = this.st.el.attr('data-effect');
        }
      },
      midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });
    $scope.base_url = APP_URL+'/experiences/'+$scope.host_experience_id;
    $scope.initialize_map();
    $scope.get_available_dates();
    $scope.get_all_reviews();
  });
  $('.rating.stars3_5 > span:nth-of-type(n+2)').addClass('star-active');
  $('.rating.stars3_5 > span:nth-of-type(2  )').addClass('star-active-half');

  $scope.initialize_map = function()
  {
    var he_location = $scope.host_experience_location;
    var map_element = document.getElementById('host_experience_details_map');
    if(!he_location.latitude || !he_location.longitude || !map_element)
    {
      return false;
    }
    $scope.mobile_map = new google.maps.Map(map_element, {
      center: {
        lat: parseFloat(he_location.latitude),
        lng: parseFloat(he_location.longitude)
      },
      zoom: 16,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true,
      zoomControl: false,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      }
    });
    $scope.initialize_city_circle();
  }
  $scope.initialize_city_circle = function()
  {
    var he_location = $scope.host_experience_location;
    var location_position = new google.maps.LatLng(he_location.latitude, he_location.longitude);
    var cityCircle = {
      path: google.maps.SymbolPath.CIRCLE,
      fillColor: '#008489',
      fillOpacity: 0.5,
      scale: 42,
      strokeColor: '#008489',
      strokeWeight: 2
    };
    $scope.city_circle = new google.maps.Marker({
      icon : cityCircle,
      map: $scope.mobile_map,
      position: location_position,
    });
  }
  $scope.initialize_popup_map = function()
  {
    var he_location = $scope.host_experience_location;
    var map_element = document.getElementById('host_experience_details_popup_map');
    if(!he_location.latitude || !he_location.longitude || !map_element)
    {
      return false;
    }
    $scope.popup_map = new google.maps.Map(map_element, {
      center: {
        lat: parseFloat(he_location.latitude),
        lng: parseFloat(he_location.longitude)
      },
      zoom: 16,
      maxZoom: 17,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL,
        position: google.maps.ControlPosition.TOP_RIGHT
      }
    });
    $scope.initialize_popup_city_circle();
  }
  $scope.initialize_popup_city_circle = function()
  {
    var he_location = $scope.host_experience_location;
    var location_position = new google.maps.LatLng(he_location.latitude, he_location.longitude);
    var cityCircle = {
      path: google.maps.SymbolPath.CIRCLE,
      fillColor: '#008489',
      fillOpacity: 0.5,
      scale: 42,
      strokeColor: '#008489',
      strokeWeight: 2
    };
    $scope.popup_city_circle = new google.maps.Marker({
      icon : cityCircle,
      map: $scope.popup_map,
      position: location_position,
    });
  }

  $scope.available_dates_loading = false;
  $scope.available_dates_loaded = false;
  $scope.get_available_dates = function()
  {
    if($scope.available_dates_loading == true || $scope.available_dates_loaded == true)
    {
      return;
    }
    $scope.available_dates_loading = true;
    page = $scope.available_dates_page;
    url = $scope.base_url+'/get_available_dates';
    $scope.http_post(url, {page : page}, function(response_data){
      if(response_data.available_dates.length == 0)
      {
        $scope.available_dates_loaded = true;
      }
      $scope.available_dates = $scope.available_dates.concat(response_data.available_dates);
      $scope.available_dates_page = $scope.available_dates_page +1;
      $scope.available_dates_loading = false;
    });
  }

  $scope.all_review_loading = false;
  $scope.all_review_loaded = false;
  $scope.all_reviews=[];
  $scope.get_all_reviews = function()
  {
    if($scope.all_review_loading == true  || $scope.all_review_loaded == true)
    {
      return;
    }
    $scope.all_review_loading = true;
    page = $scope.all_review_page;
    url = $scope.base_url+'/get_all_reviews';
    $http.post(url+'?page='+page, {}).then(function(response) 
    {
      if(response.data.current_page == response.data.last_page)
      {
        $scope.all_review_loaded = true;
      }
      angular.forEach(response.data.data, function(value, key){
        $scope.all_reviews.push(value);
      });
      $scope.all_review_page = response.data.current_page +1;
      $scope.all_review_loading = false;
    });
  }
  $scope.upcoming_available_dates = function()
  {
    available_dates = angular.copy($scope.available_dates);
    if(available_dates)
      return available_dates.splice(0, 3);
    else
      return {};
  }
  $scope.format_date =function(date, format)
  {
    format = daterangepicker_format;
    return moment(date,'YYYY-MM-DD').format(format);
  }
  $scope.format_time =function(time, format)
  {
    return moment.utc(time,'HH:mm:ss').format(format);
  }
  function elementInViewport2(el) {
    var top = el.offsetTop;
    var left = el.offsetLeft;
    var width = el.offsetWidth;
    var height = el.offsetHeight;

    while(el.offsetParent) {
      el = el.offsetParent;
      top += el.offsetTop;
      left += el.offsetLeft;
    }

    return (
      top < (window.pageYOffset + window.innerHeight) &&
      left < (window.pageXOffset + window.innerWidth) &&
      (top + height) > window.pageYOffset &&
      (left + width) > window.pageXOffset
    );
  }
  $scope.start_booking = function(index, date, user_id)
  {
    $("#js_choose_btn_"+index).addClass('dot-loading');
    url = $scope.base_url+'/choose_date';
    $scope.http_post(url, {date: date}, function(response_data){
      scheduled_id = response_data.scheduled_id;
      if(user_id == '')
      {
        var magnificPopup = $.magnificPopup.instance; 
        magnificPopup.close();
        $("body").addClass("pos-fix");
        $(".sidebar").addClass("overflow-control");
        $(".login_popup").show();
        $(".signup_popup").hide();
        $(".signup_popup2").hide(); 
        $(".forgot-passward").hide(); 
        $("#js_choose_btn_"+index).removeClass('dot-loading');
        return false;
      }
      window.location.href=$scope.base_url+'/book/guest-requirements?scheduled_id='+scheduled_id;
    });
  }

  $(document).on('click', '#contact_host_form_submit', function(){
    $scope.contact_host_form_submit();
  })
  $scope.contact_host_form_submit = function()
  { 
    contact_host_message = $scope.contact_host_message;
    if(contact_host_message === '')
    {
      $("#contact_host_message_error").show();
      return false;
    }
    else
    {
      $("#contact_host_message_error").hide();
      $("#available_dates_popup1").addClass('dot-loading');
      url = $scope.base_url+'/contact_host';
      $scope.http_post(url, {message: contact_host_message}, function(response_data){
        
      });
    }
  }
}]);
app.controller('manage_experiences', ['$scope', '$http', '$compile', 'fileUploadService', '$sce', function($scope, $http, $compile, fileUploadService, $sce) {
  $scope.autocomplete;
  $scope.map;
  $scope.mobile_map;
  $scope.geocoder = new google.maps.Geocoder();
  $scope.location_marker;
  $scope.city_circle;
  $scope.empty_photo_url = APP_URL+'/images/host_experiences/empty_photo.png';
  $(document).ready(function(){
    $scope.base_url = $scope.ajax_base_url.replace('ajax_', '');
    $scope.update_url = APP_URL+'/host/manage_experience/'+$scope.host_experience_id+'/update_experience';
    $scope.upload_url = APP_URL+'/host/manage_experience/'+$scope.host_experience_id+'/upload_photo';
    $scope.delete_photo_url = APP_URL+'/host/manage_experience/'+$scope.host_experience_id+'/delete_photo';
    $scope.exit_url = APP_URL+'/host/experiences';
    $scope.form_errors = {};
    $scope.host_experience_saved = angular.copy($scope.host_experience);
    $scope.host_experience_provides_saved = angular.copy($scope.host_experience_provides);
    $scope.host_experience_packing_lists_saved = angular.copy($scope.host_experience_packing_lists);
    $scope.form_modified = false;
    $scope.steps_status = [];
    $scope.removed_provides = [];
    $scope.removed_packing_lists = [];
    $scope.update_steps_status();
    $scope.$apply();
    $scope.scroll_mobile_view($scope.step);
    $scope.initialize_mobile_map();
  });
  $scope.http_post = function(url, data, callback)
  {
    if(!data)
    {
      data = {};
    }
    $http.post(url, data).then(function(response){
      fileUploadService.process_responses(response);
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
    }, function(response){
      fileUploadService.process_responses(response);
    });
  }
  $scope.pending_step = null;
  $scope.refresh_main_content = function(step_num)
  {
    if($scope.form_modified == true)
    {
      $("#control_btns_popup").removeClass('hide1');
      $scope.pending_step = step_num;
      return '';
    }
    else
    {
      $scope.pending_step = null;
      $("#control_btns_popup").addClass('hide1');
    }
    $("#manage_experience_main_content div.main_bar").addClass('dot-loading');
    $scope.http_post($scope.ajax_base_url+'?step_num='+step_num, {}, function(response_data){
      $("#manage_experience_main_content").html($compile(response_data.content)($scope));
      $scope.step_num = response_data.step_num;
      $scope.step     = response_data.step;
      window.history.pushState({path: $scope.base_url+'?step_num='+$scope.step_num}, '', $scope.base_url+'?step_num='+$scope.step_num);
      if($scope.step == 'where_will_meet')
      {
        $scope.initialize_autocomplete();
        $scope.initialize_map();
      }
      $('.main_bar').removeClass('newmain');
      $('.het').removeClass('het1');
      $scope.initialize_mobile_map();
      $scope.scroll_mobile_view($scope.step);
      if(response_data.menu_content )
      {
        $("#manage_experience_menu").html($compile(response_data.menu_content)($scope));
      }
      if(!$scope.$$phase){
        $scope.$apply();
      }
    });
  }
  $scope.save_in_progress = false;
  $scope.update_data = function(step_num, data, callback)
  {
    if(!data)
    {
      data = {};
    }
    $scope.save_in_progress = true;
    $('.save_loading').addClass('dot-loading dense-back');
    $scope.http_post($scope.update_url+'?step_num='+step_num, data, function(response_data){
      $scope.form_modified = false;
      $scope.host_experience_saved = angular.copy(response_data.host_experience);
      $scope.host_experience_provides_saved = angular.copy(response_data.host_experience_provides);
      $scope.host_experience_provides = angular.copy(response_data.host_experience_provides);
      /*$.each($scope.host_experience_provides_saved, function(i, v){
        $scope.host_experience_provides[i].id= v.id;
      });*/
      $scope.host_experience_packing_lists_saved = angular.copy(response_data.host_experience_packing_lists);
      $scope.host_experience.changes_saved = $scope.host_experience_saved.changes_saved;
      $scope.host_experience.status = $scope.host_experience_saved.status;
      $scope.changes_saved_text = $scope.host_experience_saved.changes_saved;
      $scope.save_in_progress = false;
      $('.save_loading').removeClass('dot-loading dense-back');
      callback(response_data);
      if($scope.step == 'what_will_provide')
      {
        $scope.check_single_provide();
      }
    });
  }
  $scope.get_step_data = function(step_num)
  {
    var data = {};
    if(!$scope.host_experience.is_reviewed)
    {
      if(step_num == 1)
      {
        data.hosting_standards_reviewed = $scope.host_experience.hosting_standards_reviewed == 'Yes';
      }
      if(step_num == 2)
      {
        data.experience_standards_reviewed = $scope.host_experience.experience_standards_reviewed == 'Yes';
      }
    }
    else
    {
      step = $scope.host_experience_steps[step_num].step;
      if(step == 'language')
      {
        data.language = $scope.host_experience.language;
      }
      if(step == 'category')
      {
        data.category = $scope.host_experience.category;
        data.secondary_category = $scope.host_experience.secondary_category == '' ? null : $scope.host_experience.secondary_category;
      }
      if(step == 'title')
      {
        data.title = $scope.host_experience.title;
      }
      if(step == 'time')
      {
        data.start_time = $scope.host_experience.start_time;
        data.end_time = $scope.host_experience.end_time;
      }
      if(step == 'tagline')
      {
        data.tagline = $scope.host_experience.tagline;
      }
      if(step == 'what_will_do')
      {
        data.what_will_do = $scope.host_experience.what_will_do;
      }
      if(step == 'where_will_be')
      {
        data.where_will_be = $scope.host_experience.where_will_be;
      }
      if(step == 'where_will_meet')
      {
        data.host_experience_location = $scope.host_experience.host_experience_location;
      }
      if(step == 'what_will_provide')
      {
        data.host_experience_provides = $scope.host_experience_provides;
        data.removed_provides = $scope.removed_provides;
        data.need_provides = $scope.host_experience.need_provides;
      }
      if(step == 'notes')
      {
        data.notes = $scope.host_experience.notes;
        data.need_notes = $scope.host_experience.need_notes;
      }
      if(step == 'about_you')
      {
        data.about_you = $scope.host_experience.about_you;
      }
      if(step == 'guest_requirements')
      {
        data.guest_requirements = $scope.host_experience.guest_requirements;
      }
      if(step == 'group_size')
      {
        data.number_of_guests = $scope.host_experience.number_of_guests;
      }
      if(step == 'price')
      {
        data.price_per_guest = $scope.host_experience.price_per_guest;
        data.is_free_under_2 = $scope.host_experience.is_free_under_2;
      }
      if(step == 'preparation_time')
      {
        data.preparation_hours = $scope.host_experience.preparation_hours;
        data.last_minute_guests = $scope.host_experience.last_minute_guests;
        data.cutoff_time = $scope.host_experience.cutoff_time;
      }
      if(step == 'packing_list')
      {
        data.host_experience_packing_lists = $scope.host_experience_packing_lists;
        data.removed_packing_lists = $scope.removed_packing_lists;
        data.need_packing_lists = $scope.host_experience.need_packing_lists;
      }
      if(step == 'review_submit')
      {
        data.quality_standards_reviewed = $scope.host_experience.quality_standards_reviewed;
        data.local_laws_reviewed = $scope.host_experience.local_laws_reviewed;
        data.terms_service_reviewed = $scope.host_experience.terms_service_reviewed;
      }
    }
    return data;
  }
  $scope.update_steps_status = function()
  {
    if($scope.host_experience.is_reviewed)
    {
      provide_values = $scope.host_experience_provides_count_check();    
      packing_list_values = $scope.host_experience_packing_lists_count_check();    
      photos_values = $scope.host_experience_photos_count_check();

      host_experience_location = $scope.host_experience.host_experience_location;

      $scope.steps_status['language']            = ($scope.host_experience.language) ? true : false;
      $scope.steps_status['category']            = ($scope.host_experience.category) ? true : false;
      $scope.steps_status['title']               = ($scope.host_experience.title.length >= 10 && $scope.host_experience.title.length <= 38) ? true : false;
      $scope.steps_status['time']                = ($scope.host_experience.start_time && $scope.host_experience.end_time) ? true : false;
      $scope.steps_status['tagline']             = ($scope.host_experience.tagline.length > 0 && $scope.host_experience.tagline.length <= 60) ? true : false;
      $scope.steps_status['photos']              = (photos_values.photos_count > 0) ? true : false;
      $scope.steps_status['what_will_do']        = ($scope.host_experience.what_will_do.length >= 200 && $scope.host_experience.what_will_do.length <= 1200) ? true : false;
      $scope.steps_status['where_will_be']       = ($scope.host_experience.where_will_be.length >= 100 && $scope.host_experience.where_will_be.length <= 450) ? true : false;
      $scope.steps_status['where_will_meet']     = (host_experience_location.location_name && host_experience_location.country && host_experience_location.address_line_1 && host_experience_location.city && host_experience_location.latitude && host_experience_location.longitude) ? true : false;
      $scope.steps_status['what_will_provide']   = (provide_values.provides_status > 0) ? true : false;
      $scope.steps_status['notes']               = (($scope.host_experience.notes.length > 0 && $scope.host_experience.notes.length <= 200) || $scope.host_experience.need_notes == 'No') ? true : false;
      $scope.steps_status['about_you']           = ($scope.host_experience.about_you.length >= 150 && $scope.host_experience.about_you.length <= 600) ? true : false;
      $scope.steps_status['guest_requirements']  = ($scope.host_experience.guest_requirements.minimum_age > 0) ? true : false;
      $scope.steps_status['group_size']          = ($scope.host_experience.number_of_guests > 0) ? true : false;
      $scope.steps_status['price']               = ($scope.host_experience.price_per_guest >= $scope.host_experience.minimum_price) ? true : false;
      $scope.steps_status['preparation_time']    = ($scope.host_experience.preparation_hours > 0) ? true : false;
      $scope.steps_status['packing_list']        = (packing_list_values.packing_lists_status > 0) ? true : false;

      $scope.host_experience.provides_count = provide_values.provides_count;
      $scope.provide_can_add_more = provide_values.can_add_more;

      $scope.host_experience.packing_lists_count = packing_list_values.packing_lists_count;
      $scope.packing_list_can_add_more = packing_list_values.can_add_more;

      if(!$scope.$$phase) {
        $scope.$apply();
      }
    }
  }
  $scope.character_length_validation = function(min, max, length)
  {
    if(length < min)
    {
      character = min-length;
      if(character == 1)
        message = $scope.field_validations.character_needed;
      else
        message = $scope.field_validations.characters_needed;
    }
    else if(length > max)
    {
      character = length-max;
      if(character == 1)
        message = $scope.field_validations.character_over;
      else
        message = $scope.field_validations.characters_over;
    }
    else
    {
      character = (max-length);
      if(character == 1)
        message = $scope.field_validations.character_remaining;
      else
        message = $scope.field_validations.characters_remaining;      
    }
    return character+message;

  }
  $scope.photo_style = function(index)
  {
    photo = $scope.host_experience_photos[index];
    if(photo.name)
    {
      return {'background-image' : 'url('+photo.image_url+')'}
    }
    else
    {
      return {};
    }
  }
  $scope.host_experience_photos_count_check = function()
  {
    photos = $scope.host_experience_photos;
    var photos_count = 0;
    var empty_count = 0;
    for(var i = 0; i < photos.length; i++)
    {
      if(photos[i].name)
      {
        photos_count++;
      }
      else
      {
        empty_count++;
      }
    }
    if(empty_count ==0 )
    {
      $scope.add_photo();
    }

    return {photos_count : photos_count};
  } 
  $scope.host_experience_photos_changed = function(){
    if($scope.host_experience_saved)
    {
      $scope.update_steps_status();
      $scope.check_single_photo();
    }
  };
  $scope.check_single_photo = function() 
  {
    min_require = 3 - $scope.host_experience_photos.length;
    for(var i=0; i < min_require; i++)
    {
      $scope.add_photo();
    }
  }
  $scope.add_photo = function()
  {
    $scope.host_experience_photos.push({name:''});
    $scope.host_experience_photos_changed();
  }
  $scope.remove_photo = function(index)
  {
    photo = $scope.host_experience_photos[index];
    if(photo.id)
    {
      data = {'photo_id' : photo.id};
      $scope.http_post($scope.delete_photo_url, data, function(response_data){
        $scope.host_experience_photos.splice(index, 1);
        $scope.host_experience_steps = angular.copy(response_data.host_experience_steps);
        $scope.host_experience_photos_changed();
        if(!$scope.$$phase) {
          $scope.$apply();
        }
      });
    }
    else
    {
      $scope.host_experience_photos.splice(index, 1);
      $scope.host_experience_photos_changed();
    }
  }
  $scope.upload_photos = function(photo, index)
  {
    upload = fileUploadService.uploadFileToUrl(photo, $scope.upload_url);
    $('.browse#photo_div_'+index).addClass('dot-loading dense-back');
    upload.then(
      function(response){
        if(response == 'Internal Server Error')
        {
          title = 'File upload error! Please try uploading a different image!!!'
          $('#photo_error_popup #title').html(title);
          $('#photo_error_popup #description').html("");
          $('#photo_error_popup #choose_another_photo_btn').attr('data-index', index);
          $('#photo_error_popup').removeClass('hide');
          file_element.value='';
        }else if(response.status == 200)
        {
          $scope.host_experience_photos = response.host_experience_photos;
          $scope.host_experience_steps = angular.copy(response_data.host_experience_steps);
          $scope.host_experience_photos_changed(); 
        }
        else if(response.status == 300)
        {
          title = "Cloudinary key is invalid";
          $('#photo_image_popup #title').html(title);
          $('#photo_image_popup').removeClass('hide');
          $('.close_photo_error_popup').on('click', function(){
          $('#photo_image_popup').addClass('hide');
          });
          file_element.value='';
        }
        $('.browse#photo_div_'+index).removeClass('dot-loading dense-back');
      },
      function(response){
      },
      function(response){
      }
    );
  }
  $scope.check_photo = function(index)
  {
    file_element = document.getElementById('host_experience_photo_'+index);
    files = file_element.files;
    if(files.length > 0)
    {
      var _URL = window.URL || window.webkitURL;
      photo = files[0];
      if(photo.type != 'image/jpeg' && photo.type != 'image/png')
      {
        title = $scope.field_validations.photo_format_error_title;
        description = $scope.field_validations.photo_format_error_description;
        $('#photo_error_popup #title').html(title);
        $('#photo_error_popup #description').html(description);
        $('#photo_error_popup #choose_another_photo_btn').attr('data-index', index);
        $('#photo_error_popup').removeClass('hide');
        file_element.value='';
        return;
      }
      img = new Image();
      img.onload = function () {

          /*if(this.width > 853 || this.height > 1280)
          {
            description = $scope.locale_string($scope.field_validations.photo_max_resolution_error_message, {'required_pixels' : '853 x 1280', 'original_pixels' : this.width+' x '+this.height});
            title = $scope.field_validations.photo_max_resolution_error_title;
            $('#photo_error_popup #title').html(title);
            $('#photo_error_popup #description').html(description);
            $('#photo_error_popup #choose_another_photo_btn').attr('data-index', index);
            $('#photo_error_popup').removeClass('hide');
            file_element.value='';
            return;
          }*/

          if(this.width < 480 || this.height < 720)
          {
            description = $scope.locale_string($scope.field_validations.photo_resolution_error_message, {'required_pixels' : '480 x 720', 'original_pixels' : this.width+' x '+this.height});
            title = $scope.field_validations.photo_resolution_error_title;
            $('#photo_error_popup #title').html(title);
            $('#photo_error_popup #description').html(description);
            $('#photo_error_popup #choose_another_photo_btn').attr('data-index', index);
            $('#photo_error_popup').removeClass('hide');
            file_element.value='';
          }
          else
          {
            $scope.upload_photos(photo, index);
          }
      };
      img.src = _URL.createObjectURL(photo);
    }
  }
  $scope.price_filter = function(price)
  {
    price = !price ? 0 : price;
    return price;
  }
  $(document).on('change', '.host_experience_photos_element', function(){
    if($(this).parent().hasClass('dot-loading'))
    {
      return false;
    }
    index = $(this).attr('data-index');
    $scope.check_photo(index);
  });
  $("#choose_another_photo_btn").on('click', function(){
    index = $(this).attr('data-index');
    $('#host_experience_photo_'+index).trigger('click');
    $("#photo_error_popup").addClass('hide');
  });
  $('.close_photo_error_popup').on('click', function(){
    $('#photo_error_popup').addClass('hide');
  });
  $scope.minimum_end_time_check = function()
  {
    start_time = $scope.host_experience.start_time;
    if(start_time){
      $scope.minimum_end_time = moment.utc(start_time,'HH:mm:ss').add(1,'hour').format('HH:mm:ss');
      if($scope.host_experience.end_time < $scope.minimum_end_time)
        $scope.host_experience.end_time = $scope.minimum_end_time;
    }
    else
    {
      $scope.minimum_end_time = '00:00:00';
    }
  }
  $scope.initialize_autocomplete = function()
  {
    autocomplete_elem = document.getElementById('host_experience_location_address_line_1');
    $scope.autocomplete = new google.maps.places.Autocomplete(autocomplete_elem, { types: ['address']});
    $scope.autocomplete.addListener('place_changed', $scope.fillInAddress);
  }
  $scope.fillInAddress = function()
  {
    place = $scope.autocomplete.getPlace();
    $scope.fetchMapAddress(place);
  }
  $scope.fetchMapAddress = function(data) {
    var he_location = $scope.host_experience.host_experience_location;
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
          he_location.address_line_1 = street_number + ' ' + val;
        if (addressType == 'postal_code')
          he_location.postal_code = val;
        if (addressType == 'locality')
          he_location.city = val;
        if (addressType == 'administrative_area_level_1')
          he_location.state = val;
        if (addressType == 'country')
          he_location.country = val;
      }
    }
    he_location.latitude = place.geometry.location.lat();
    he_location.longitude = place.geometry.location.lng();

    $scope.host_experience.host_experience_location = he_location;
    $scope.$apply();
  }
  $scope.initialize_map = function ()
  {
    var he_location = $scope.host_experience.host_experience_location;
    var map_element = document.getElementById('host_experience_location_map');
    if(!he_location.latitude || !he_location.longitude || !map_element)
    {
      return false;
    }
    $scope.map = new google.maps.Map(map_element, {
      center: {
        lat: parseFloat(he_location.latitude),
        lng: parseFloat(he_location.longitude)
      },
      zoom: 16,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      }
    });
    $scope.initialize_marker();
    $scope.initialize_mobile_map();
  }
  $scope.initialize_mobile_map = function()
  {
    var he_location = $scope.host_experience.host_experience_location;
    var map_element = document.getElementById('host_experience_location_mobile_map');
    if(!he_location.latitude || !he_location.longitude || !map_element)
    {
      return false;
    }
    $scope.mobile_map = new google.maps.Map(map_element, {
      center: {
        lat: parseFloat(he_location.latitude),
        lng: parseFloat(he_location.longitude)
      },
      zoom: 15,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true,
      zoomControl: false,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      }
    });
    $scope.initialize_city_circle();
  }
  $scope.initialize_marker = function()
  {
    var he_location = $scope.host_experience.host_experience_location;
    var location_position = new google.maps.LatLng(he_location.latitude, he_location.longitude);
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
      $scope.host_experience.host_experience_location.latitude = marker_location.lat();
      $scope.host_experience.host_experience_location.longitude = marker_location.lng();
      $scope.$apply();
    });
  }
  $scope.initialize_city_circle = function()
  {
    var he_location = $scope.host_experience.host_experience_location;
    var location_position = new google.maps.LatLng(he_location.latitude, he_location.longitude);
    var cityCircle = {
      path: google.maps.SymbolPath.CIRCLE,
      fillColor: '#008489',
      fillOpacity: 0.5,
      scale: 40,
      strokeColor: '#008489',
      strokeWeight: 2
    };
    $scope.city_circle = new google.maps.Marker({
      icon : cityCircle,
      map: $scope.mobile_map,
      position: location_position,
    });
  }
  $scope.host_experience_provides_count_check = function()
  {
    var provides_count = 0;
    var provides_status = 1;
    var can_add_more = 1;
    var provides = $scope.host_experience_provides;
    for(var i = 0; i < provides.length; i++)
    {
      if(provides[i].name.length > 0 && provides[i].name.length <= 25 && provides[i].additional_details.length <= 125 && provides[i].host_experience_provide_item_id > 0)
      {
        provides_count++;
      }
      else
      {
        if(provides[i].host_experience_provide_item_id > 0)
          provides_status = 0;
      }
      if(provides[i].name.length <= 0)
      {
        can_add_more = 0
      }
    }
    if(provides_status != 0 && provides_count <= 0 && $scope.host_experience.need_provides != 'No')
    {
      provides_status = 0;
    }
    return {provides_count : provides_count, provides_status : provides_status, can_add_more : can_add_more};
  }
  $scope.add_provide = function()
  {
    $scope.host_experience_provides.push({host_experience_provide_item_id : 0 , name:'', additional_details: ''});
    $scope.host_experience_provides_changed();
  }
  $scope.remove_provide = function(index)
  {
    provide = $scope.host_experience_provides[index];
    if(provide !== undefined)
    {
      if(provide.id)
      {
        $scope.removed_provides.push({'id': provide.id});
      }
    }
    $scope.host_experience_provides.splice(index, 1);
    $scope.host_experience_provides_changed();
    $scope.check_single_provide();
  }  
  $scope.host_experience_provides_changed = function(){
    if($scope.host_experience_saved)
    {
      $scope.form_modified = true;
      $scope.update_steps_status();
    }
  };
  $scope.check_single_provide = function() 
  {
    if($scope.host_experience_provides.length <= 0)
    {
      $scope.add_provide();
      $scope.form_modified = false;
      if(!$scope.$$phase) {
        $scope.$apply();
      }
    }
  }
  $scope.need_provides_change = function()
  {
    if($scope.host_experience.need_provides == null)
    {
      $scope.host_experience.need_provides = $scope.host_experience.provides_count > 0 ? 'Yes' : 'No';
    }
  }
  $scope.check_provide_item_available = function(host_experience_provide_item_id, current_index)
  {
    var available = true;
    for(var i = 0; i < $scope.host_experience_provides.length; i++) {
        if ($scope.host_experience_provides[i].host_experience_provide_item_id == host_experience_provide_item_id && i != current_index) {
            available = false;
            break;
        }
    }
    return available;
  }
  $scope.need_notes_change = function()
  {
    if($scope.host_experience.need_notes == null)
      $scope.host_experience.need_notes = $scope.host_experience.notes.length > 0 ? 'Yes' : 'No';
  }
  $scope.host_experience_packing_lists_count_check = function()
  {
    var packing_lists_count = 0;
    var packing_lists_status = 1;
    var can_add_more = 1;
    var packing_lists = $scope.host_experience_packing_lists; 
    for(var i = 0; i < packing_lists.length; i++)
    {
      if(packing_lists[i].item.length > 0)
      {
        packing_lists_count++;
      }
      if(packing_lists[i].item.length <= 0)
      {
        can_add_more = 0
        packing_lists_status = 0;
      }
    }
    if(packing_lists_status != 0 && packing_lists_count <= 0 && $scope.host_experience.need_packing_lists != 'No')
    {
      packing_lists_status = 0;
    }
    return {packing_lists_count : packing_lists_count, packing_lists_status : packing_lists_status, can_add_more : can_add_more};
  }
  $scope.add_packing_list = function()
  {
    $scope.host_experience_packing_lists.push({item:''});
    $scope.host_experience_packing_lists_changed();
  }
  $scope.remove_packing_list = function(index)
  {
    packing_list = $scope.host_experience_packing_lists[index];
    if(packing_list.id)
    {
      $scope.removed_packing_lists.push({'id': packing_list.id});
    }
    $scope.host_experience_packing_lists.splice(index, 1);
    $scope.host_experience_packing_lists_changed();
  }  
  $scope.host_experience_packing_lists_changed = function(){
    if($scope.host_experience_saved)
    {
      $scope.form_modified = true;
      $scope.update_steps_status();
    }
  };
  $scope.check_single_packing_list = function() 
  {
    if($scope.host_experience_packing_lists.length <= 0)
    {
      // $scope.add_packing_list();
    }
  }
  $scope.need_packing_lists_change = function()
  {
    if($scope.host_experience.need_packing_lists == null)
    {
      $scope.host_experience.need_packing_lists = $scope.host_experience.packing_lists_count > 0 ? 'Yes' : 'No';
    }
  }
  $scope.total_hours = function()
  {
    start_time = $scope.host_experience.start_time;
    end_time = $scope.host_experience.end_time;
    var total_hours = 0;
    if(start_time && end_time){
      moment_start_time = moment.utc(start_time,'HH:mm:ss');
      moment_end_time = moment.utc(end_time,'HH:mm:ss');
      var duration = moment.duration(moment_end_time.diff(moment_start_time));
      total_hours = duration.asHours();
    }
    else
    {
      total_hours = 0;
    } 
    return total_hours;
  }
  $scope.show_element = function(elem)
  {
    $(elem).show();
  }
  $scope.hide_element = function(elem)
  {
    $(elem).hide();
  }
  $scope.get_mobile_photo_elem_style = function()
  {
    photo_placeholder = $scope.host_experience_photos[0] ? $scope.host_experience_photos[0].image_url : $scope.empty_photo_url;
    mobile_phone_photo_style = {'background-image':'url('+photo_placeholder+')'};
    return mobile_phone_photo_style;
  }
  $scope.scroll_mobile_view = function(to)
  {
    to_element = $('.over_scroll1 [data-step="'+to+'"]');
    if(to_element.length)
    {
      setTimeout(function(){
        var a = to_element.offset().top;
        var diff_top = $(".over_scroll1").offset().top;
        $('.over_scroll1').animate({
          scrollTop: (a-diff_top)
        }, 1000);
      }, 1000);
    }
  }
  $scope.language_progress_start = function()
  {
    setTimeout(function(){
      $( ".progress_val" ).removeClass( "anim" );
    }, 1000); 
  }
  $scope.locale_string = function(string, data)
  {
    if(!data)
    {
      data = {};
    }
    $.each(data, function(i, v)
    {
      string = string.replace(':'+i, v);
    });
    return string;
  }
  $scope.get_provide_image = function(host_experience_provide_item_id)
  {
    provide_details = $.grep($scope.provide_items, function(e){ return e.id == host_experience_provide_item_id; });
    if(provide_details.length)
    {
      return provide_details[0].image_url;
    }
  }
  $scope.text_more_content = function(text, min, max, index)
  {
    var ellipsestext = "...";
    var moretext = "+ "+more_text_lang;
    var lesstext = "";
    if(text.length > max && $scope.more_link_status[index] == false) 
    {
      var c = text.substr(0, min);
      var h = text.substr(min, text.length - min);
      var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<p class="morelink" data-index="0">' + moretext + '</p></span>';
      return $scope.to_trusted(html);
    }
    else
    {
      return text;
    }
  }
  $scope.to_trusted = function(html_code) {
    return $sce.trustAsHtml(html_code);
  }
  $(document).on('click', '.refresh_main_content', function(){
    step_num = $(this).attr('data-step-num');
    $scope.refresh_main_content(step_num);
  });
  $(document).on('click', '.close_control_btns_popup', function(){
    $scope.pending_step = null;
    $('#control_btns_popup').addClass('hide1');
  });
  $(document).on('click', '.refresh_main_content_step', function(e){
    if(e.originalEvent)
    {
      if(e.originalEvent.target.className == 'morelink')
      {
        if($(e.originalEvent.target).hasClass("less")) {
          $(e.originalEvent.target).removeClass("less");
          $(e.originalEvent.target).html(moretext);
        } else {
          $(e.originalEvent.target).addClass("less");
          $(e.originalEvent.target).html("");
        }
        $(e.originalEvent.target).parent().prev().toggle();
        $(e.originalEvent.target).prev().toggle();
        index = $(e.originalEvent.target).attr('data-index');
        $scope.more_link_status[index] = true;
        return '';
      }
    }
    step = $(this).attr('data-step');
    step_details = $.grep($scope.host_experience_steps, function(e){ return e.step == step; });
    if(step_details.length)
    {
      step_num = step_details[0].step_num;
      $scope.refresh_main_content(step_num);
    }
  });
  $(document).on('click', '.next_step', function(){
    step_num = $(this).attr('data-step-num');
    step_data = $scope.get_step_data($scope.step_num);
    $scope.refresh_main_content(step_num);
  });
  $(document).on('click', '.save_next_step', function(){
    step_num = $(this).attr('data-step-num');
    step_data = $scope.get_step_data($scope.step_num);
    $scope.update_data($scope.step_num, step_data, function(response_data){
      $scope.refresh_main_content(step_num);
    });
  });
  $(document).on('click', '.save_step', function(){
    step_data = $scope.get_step_data($scope.step_num);
    $scope.update_data($scope.step_num, step_data, function(response_data){
      if($scope.pending_step)
      {
        $scope.refresh_main_content($scope.pending_step)
      }
    });
  });
  $(document).on('click', '.save_exit', function(){
    if($scope.step_num==21)
    {
      window.location.href=$scope.exit_url
    }
    step_data = $scope.get_step_data($scope.step_num);
    if($scope.steps_status[$scope.step])
    {
      $scope.update_data($scope.step_num, step_data, function(response_data){
        window.location.href=$scope.exit_url
      });
    }
    else
    {
      window.location.href=$scope.exit_url
    }
  });
  $(document).on('click', '.undo_step', function(){
    $scope.host_experience = angular.copy($scope.host_experience_saved);
    $scope.host_experience_provides = angular.copy($scope.host_experience_provides_saved);
    $scope.removed_provides = [];
    $scope.host_experience_packing_lists = angular.copy($scope.host_experience_packing_lists_saved);
    $scope.removed_packing_lists = [];
    $scope.host_experience.category == null ? '' : $scope.host_experience.category;
    $scope.host_experience.secondary_category == null ? '' : $scope.host_experience.secondary_category;
    $scope.$apply();
    if($scope.step == 'where_will_meet')
    {
      $scope.initialize_map();
    }
    else if($scope.step == 'what_will_provide')
    {
      $scope.check_single_provide();
    }
    if($scope.pending_step)
    {
      $scope.refresh_main_content($scope.pending_step)
    }
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
  $scope.$watchCollection('host_experience', function(new_value, old_value){
    new_value.category = new_value.category-0;
    new_value.secondary_category = new_value.secondary_category;
    if($scope.host_experience_saved)
    {
      if(JSON.stringify( (new_value) ) == JSON.stringify( ($scope.host_experience_saved) ) )
      {
        $scope.form_modified = false;
      }
      else
      {
        $scope.form_modified = true;
      }
      $scope.update_steps_status();
    }
    $scope.minimum_end_time_check();
  });
  $scope.$watchCollection('host_experience.host_experience_location', function(new_value, old_value){
    if($scope.host_experience_saved)
    {
      if(JSON.stringify( (new_value) ) == JSON.stringify( ($scope.host_experience_saved.host_experience_location) ) )
      {
        $scope.form_modified = false;
      }
      else
      {
        $scope.form_modified = true;
      }
      $scope.update_steps_status();
    }
  });
  $scope.$watch('host_experience.host_experience_location.latitude', function(new_value, old_value){
    $scope.initialize_map();    
  });
  $scope.$watch('host_experience.host_experience_location.longitude', function(new_value, old_value){
    $scope.initialize_map();    
  });
  $scope.$watch('host_experience.category', function(new_value, old_value){
    if(new_value != '' && $scope.host_experience.secondary_category != '' && new_value == $scope.host_experience.secondary_category)
    {
      $scope.host_experience.secondary_category = old_value;
    }
  });
  $scope.$watch('host_experience.secondary_category', function(new_value, old_value){
    if(new_value > 0)
    {
      $scope.is_secondary = true;
    }
    else
    {
      $scope.is_secondary = false;
    }
  });
  $scope.$watchCollection('host_experience.guest_requirements', function(new_value, old_value){
    if($scope.host_experience_saved)
    {
      if(JSON.stringify( (new_value) ) == JSON.stringify( ($scope.host_experience_saved.guest_requirements) ) )
      {
        $scope.form_modified = false;
      }
      else
      {
        $scope.form_modified = true;
      }
      $scope.update_steps_status();
    }
  });
  /* Edit Calendar JS Starts*/
  $scope.refresh_calendar = function(year, month)
  {
    refresh_calendar_url = APP_URL+'/host/manage_experience/'+$scope.host_experience_id+'/refresh_calendar';
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
  $(document).on('submit', '#update_host_experience', function(e){
      e.preventDefault;
      return false;
  });


  $scope.calendar_edit_form = function(){
    $('.calendar-edit-form').removeClass('hide');

    if ($('.selected').length > 1) {
      $('.date_fields').show();
    } else {
      $('.date_fields').hide();
    }

    if ($('.selected').hasClass('status-b')) {
      $scope.segment_status = 'not available';
      $('#unavi').addClass("segmented-control__option--selected");
      $('#avi').removeClass("segmented-control__option--selected");
      $('#avi').removeClass("segmented-control__option--selected");
    } else {
      $scope.segment_status = 'available';
      $('#avi').addClass("segmented-control__option--selected");
      $('#unavi').removeClass("segmented-control__option--selected");
      $('#unavi').removeClass("segmented-control__option--selected");
    }

    $('#calendar-edit-end').val('');
    $('#calendar-edit-start').val('');
    
    var start_date = $('.first-day-selected').first().attr('id');
    var end_date = $('.last-day-selected').last().attr('id');
    $scope.calendar_edit_price = $('#' + start_date).find('.price > span:last').text() - 0;

    var s_date = new Date(start_date);
    var e_date = new Date(end_date);

    $('#calendar-edit-start').val(change_format(start_date));
    $('#calendar-edit-end').val(change_format(end_date));
    $('#calendar-start').val(change_format(start_date));
    $('#calendar-end').val(change_format(end_date));

    $scope.$apply();
  }
  function change_format(date) {
    if (date != undefined) {
        var split_date = date.split('-');
        return split_date[2] + '-' + split_date[1] + '-' + split_date[0];
    }
  }
  $scope.calendar_edit_form_save = function()
  {
    var start_date = $("#calendar-start").val();
    var end_date = $("#calendar-end").val();

    data  = {'status' : $scope.segment_status, 'start_date' : start_date, 'end_date' : end_date, 'price' : $scope.calendar_edit_price};
    
    update_calendar_url = APP_URL+'/host/manage_experience/'+$scope.host_experience_id+'/update_calendar';
    $(".calendar_ctrl_btn_area").addClass('dot-loading');
    $scope.http_post(update_calendar_url, data, function(response_data){
      $scope.calendar_edit_cancel();
      $(".calendar_ctrl_btn_area").removeClass('dot-loading');
    });
  }
  $scope.calendar_edit_cancel = function()
  {
    $('.calendar-edit-form').addClass('hide');
    current_month = $('.current-month-selection').attr('data-month');
    current_year = $('.current-month-selection').attr('data-year');
    $scope.refresh_calendar(current_year, current_month);
  }
  $(document).on('click', '#calendar_edit_form_save', function(){
    $scope.calendar_edit_form_save();
  });
  $(document).on('click', '#calendar_edit_cancel', function(){
    $scope.calendar_edit_cancel();
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

      $('.days-container').append('<div><div style="left:' + start_left + 'px;top:' + start_top + 'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: ' + end_left + 'px; top: ' + end_top + 'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');

      $('.tile').each(function() {
        if (current_tile != $(this).attr('id'))
          $(this).addClass('other-day-selected');
      });

      $scope.calendar_edit_form();
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

      $scope.calendar_edit_form();
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
  /* Edit Calendar JS Ends*/
}]);

function menu_clk(){
    $('.side_bar').css("margin-left", "0"); 
    $('.main_bar').removeClass("full_wid"); 
}


 $( ".op_lw1" ).click(function() {
    location.reload();
});





// phone sample height set
function phone_tp(){
var wh = $(window).height();
$(".phone_eff").css("height" , wh - 70); 
}

// clone new
$(document).ready(function () {  
   $('.clone_trg').click(function(){
     $(this).prev('.clone_elem').clone().insertBefore(".clone_trg").removeClass("hide");
   });
   $(document).on('click', '.clone_close', function(){
     $(this).parent('.clone_elem').remove();
   });
});

// radio checked
// $(document).ready(function () {  
//     $('.radio1').click(function () {
//        $(this).parent("label").parent(".radio_grp").next(".radio_show").removeClass("hide");       
//     });
//     $('.radio2').click(function () {
//        $(this).parent("label").parent(".radio_grp").next(".radio_show").addClass("hide"); 
//     });    
// });

// multiple item show
$(document).ready(function(){
  $("#itme_pro").change(function(){
    $(this).parent(".drp_dwn_cng1").next(".focus_txt").children(".mul_input").removeClass("hide");
    $(this).parent(".drp_dwn_cng1").prev(".clearfix ").children(".mul_rm").removeClass("hide");
  });
});
/*
$(document).ready(function(){
  $(".mul_input").keyup(function(){

    var a = $(".mul_input").val().length;
    if (a >0){
      $(this).next(".mul_textarea").removeClass("hide");
      $(this).parent(".focus_txt").parent(".clone_sec").next(".mul_add").removeClass("hide");
    }
    else{
      $(this).next(".mul_textarea").addClass("hide");
      $(this).parent(".focus_txt").parent(".clone_sec").next(".mul_add").addClass("hide");
    }

    });
});

$(document).ready(function(){
  $(".mul_add").click(function(){
    $(this).prev(".clone_sec").clone().insertBefore(".mul_add");
    });
});*/



// popup common
function com_pop(){
  $(".pop_link").click(function(){
      var a = $(this).attr("data-id");
      $("[data-id='"+a+"']").parent(".popup").removeClass("hide1");
      $("body").addClass("non_scrl");
    });
}


// mobile scroll
// $(document).ready(function(){
//   if($('#scrl_ttl').length > 0 ){
//     var a = $("#scrl_ttl").offset().top;
//     $('.over_scroll1').animate({
//       scrollTop: (a-130)
//     }, 1000);
//   }  
// });

// clone category field
$(document).ready(function() {    
 $(".add_categ1, .add_categ3").click(function(){
        $(".add_categ2").toggleClass("hide");  
        $(".add_categ1").toggleClass("hide");        
    });
});


// 2 checkbox category page
$(document).ready(function() {    
 $(".check_detail_tri1").click(function(){
        $(".check_detail1, .check_detail1-1").toggleClass("hide");        
    });
});

// checkbox category page
$(document).ready(function() {    
 $(".check_detail_tri").click(function(){
        $(".check_detail").toggleClass("hide");
    });
});

// progress effect
// $(document).ready(function() {    
//   setTimeout(function(){
//     $( ".progress_val" ).removeClass( "anim" );
//   }, 1000);
// });

// tooltip
$(document).ready(function(){
    $(".tooltip_cover").click(function(){
        $(this).children(".tooltip2").toggleClass("hide");
    });
});

// basic popup
$(".close_pop").click(function(){    
  vid_pop_off();
});
$(".popup_frame").click(function(e){
  // return false;
});      


// dropdown change popup
$('#lang1, #Categ1').change(vid_pop);

function vid_pop(){
   $(".popup").removeClass("hide1");
}
function vid_pop_off(){
   $(".popup").addClass("hide1");
}

// page scroll off when popup show
    $(document).on('click', ".popup", function(){
        $("body").removeClass("non_scrl");
    });    



// new slider
$(document).ready(function(){
 slider();
 com_pop();
 phone_tp();
});

$(window).resize(function(){
  slider();
  phone_tp();
  menu_clk();
  myFuncCalls = 1;
});

function slider() {
      var a = $('.outer_slider').children('.item_sli').length;      
      var c = $(window).width();
      var con = $('.page-container').innerWidth(); 
      var paddL = $(".page-container").css('padding-left');
      var y = parseInt(paddL, 10); //remove px
      var gap = c -con;  
      var slid =  c-(gap/2);
      $(".item_sli").css("cssText", "width : "+ (slid-y) + "px");
      var b = $('.item_sli').outerWidth();
      var d = a*b;
      var e = a*5;//margin right slider
      $(".outer_slider").css("cssText", "width : "+(d+e)+ "px;");
      if (gap > 0){
        $(".outer_slider").css("left" , (gap/2)+y);
      }else
      {
        $(".outer_slider").css("left" , y);
      }    
}
$(".right_nav").click(function(){
     right_mv();
}); 
$(".left_nav").click(function(){
     left_mv();
});   
$(document).keydown(function(e) {
    switch(e.which) {
        case 37: // left
        left_mv();
        break;

        case 39: // right
        right_mv();
        break;

        default: return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
});

var myFuncCalls = 1;
function right_mv() {
 
  var a = $(".item_sli").outerWidth();
  var b = $('.outer_slider').children('.item_sli').length; 
    if (myFuncCalls < b) {
      $(".outer_slider").animate({
            left: '-=' + (a+10)
        });  
         myFuncCalls++;                   
    }   
    if(myFuncCalls > 1)
    {
      $('.left_nav').show();
    } 
    if(myFuncCalls == b)
    {
      $('.right_nav').hide();
    }
 }

function left_mv() {
  var a = $(".item_sli").outerWidth();
  var b = $('.outer_slider').children('.item_sli').length; 
  if (myFuncCalls > 1) {
  $(".outer_slider").animate({
            left: '+=' + (a+10)
        });
    myFuncCalls--; 
  }
  if(myFuncCalls < b)
  {
    $('.right_nav').show();
  }
  if(myFuncCalls == 1)
  {
    $('.left_nav').hide();
  }
}


// carosel slide new
$(function() {
    var slideCount = $('#slider_own ul li').length;
    var slideWid = $('#slider_own').outerWidth();
    var sliderUlWidth = slideCount * slideWid;

    $('#slider_own ul').css({
        width: sliderUlWidth,
        marginLeft: -slideWid
    });

    $('#slider_own ul li').css({
        width: slideWid
    });

    $('#slider_own ul li:last-child').prependTo('#slider_own ul');

    function moveLeft() {
        $('#slider_own ul').animate({
            left: +slideWid
        }, 0, function () {
            $('#slider_own ul li:last-child').prependTo('#slider_own ul');
            $('#slider_own ul').css('left', '');
        });
    };

    function moveRight() {
        $('#slider_own ul').animate({
            left: -slideWid
        }, 0, function () {
            $('#slider_own ul li:first-child').appendTo('#slider_own ul');
            $('#slider_own ul').css('left', '');
        });
    };

    $('.control_prev').click(function () {
        moveLeft();
    });

    $('.control_next').click(function () {
        moveRight();
    });
});   

$(document).ready(function(){
$(".net_gt").scroll(function(){
        $('.pac-container.pac-logo').hide();
        
});
});




  function sticky_relocate() {
    if(!document.getElementById('create_host_experience_div'))
    {
      return ;
    }
    var width2=$(window).width();
      var window_top = $(window).scrollTop();
      var footer_top = $("#create_host_experience_div").offset().top;
      var div_top = $('.ft_bug').offset().top;
      var div_height = $("#gthight").height();
      var window_width  = $(window).width();
      var window_height  = $(window).height();

      var padding = 20;  // tweak here or get from margins etc
      
      if (window_top + div_height > footer_top - padding && window_width > 1025)
          $('#gthight').css({top: (window_top + div_height - footer_top + padding) * -1})
      else if(window_top+window_height - padding > footer_top &&  window_width < 1025 )
          $('#gthight').removeClass('stht1');
      else if (window_top > div_top) {
        $('#gthight').addClass('stht1');
        if(width2 > 1025){
          $('#gthight').css({top: 0})
        }
        else{
        $('#gthight').css({bottom: 0})
  }
      } else {
        $('#gthight').removeClass('stht1');
      }
    }
    $(function () {
      $(window).scroll(sticky_relocate);
      sticky_relocate();

    });



    $(document).ready(function(){
    $(".height2,.hecls").click(function(){
        $(".hdsrc").toggle();
    });
});


    