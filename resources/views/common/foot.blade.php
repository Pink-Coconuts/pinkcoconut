
<div id="gmap-preload" class="hide"></div>

<div class="ipad-interstitial-wrapper"><span data-reactid=".1"></span></div>

<div id="fb-root"></div>
<!-- remove for console error -   &sensor=false -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&libraries=places&language={{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"></script>

{!! Html::script('js/jquery-1.11.3.js') !!}
{!! Html::script('js/jquery-ui.js') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
{!! Html::script('js/mcustom.js') !!}

<script src="{{url('/')}}/js/jquery.ui.touch-punch.js"></script>
<script>

$(document).ready(function () {
    var isTouchDevice = 'ontouchstart' in document.documentElement;
    
    $("#calendar_selection").mousedown(function(event) {
            // alert();
        if (isTouchDevice == false) {   
            pushed();   
            alert();
        }
    });
    $("#calendar_selection").mouseup(function(event) {
            // alert();
        if (isTouchDevice == false) {   
            released(); 
            alert();
        }
    });
    $("#calendar_selection").on('touchstart', function(){
            alert();
        if (isTouchDevice)  {   
            pushed();   
        }
    });
    $("#calendar_selection").on('touchend', function(){
            // alert();
        if (isTouchDevice)  {   
            released(); 
            alert();
        }
    });
});
</script>

@if(Session::get('language') != 'en')
{!! Html::script('js/i18n/datepicker-'.Session::get('language').'.js') !!}
@endif
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/angular.js') !!}
{!! Html::script('js/angular-sanitize.js') !!}

{!! Html::script('js/responsiveslides.min.js') !!}
{!! Html::script('js/jquery.sticky-sidebar-scroll.min.js') !!}
{!! Html::script('js/jquery.selectBoxIt.js') !!}

{!! Html::script('js/moment.js') !!}
{!! Html::script('js/daterangepicker.js') !!}
{!! Html::script('js/lightgallery-all.min.js') !!}
{!! Html::script('js/lightslider.min.js') !!}
<script type="text/javascript">
  $(document).ready(function() {
    $('.login_popup_head, .bck_btn, .login_popup_open').click(function(e){
      e.preventDefault();
      $("body").addClass("pos-fix");
      $(".sidebar").addClass("overflow-control");
      $(".login_popup").show();
      $(".signup_popup").hide();
      $(".signup_popup2").hide(); 
      $(".forgot-passward").hide();
      $(".verification").hide();
    });
    $('.login-close, .rm_lg').click(function(event){ 
      $("body").removeClass("pos-fix");
      $(".sidebar").removeClass("overflow-control");
      $(".login_popup, .forgot-passward, .verification, .signup_popup, .signup_popup2").hide(); 
    });
    $('.top-home').click(function(event){
      event.stopPropagation();
    });
  });
</script>

<script type="text/javascript">

  $(document).ready(function() {
    $('.forgot-password-popup').click(function(e){
      e.preventDefault();
      $("body").addClass("pos-fix");
      $(".login_popup").hide();
      $(".forgot-passward").show();
    });
    $('.login-close').click(function(event){
      $("body").removeClass("pos-fix");
      $(".forgot-passward").hide(); 
    });
    $('.top-home').click(function(event){
      event.stopPropagation();
    });
  });


</script>

<script type="text/javascript">

 $(document).ready(function() {
    $('.hostverification').click(function(e){
      e.preventDefault();
      var hostverification = $('#host_verification').val();
      if(hostverification =='Connect' || hostverification =='Pending' || hostverification =='Resubmit'){
            verification(hostverification,'host');
            $('.become_dropdown').css('display','none');
            return false;
        }else{
          $('.become_dropdown').css('display','block');
        }
    });
  });


  function verification(data,type){

      $("body").addClass("pos-fix");
      $(".login_popup").hide();
      $(".verification").show();
      
      if(type=='host'){
        var name = $('#hostname').val();
        var reason = $('#hostreason').val();
        var connect = "<h3>Hi "+name+"</h3><p>Please do upload your ID proof to become a host.</p>";
        var pending = "<h3>Hi "+name+"</h3><p>Your ID proof is waiting for admin approval until that you can't proceed to become a host.</p>";
      }else{
        var name = $('#username').val();
        var reason = $('#reason').val();
        var connect = "<h3>Hi "+name+"</h3><p>Please do Upload your ID proof to proceed with the Booking of this Room.</p>";
        var pending = "<h3>Hi "+name+"</h3><p>Your ID proof is waiting for admin approval until that you can't proceed with booking.</p>";
      }      

      if(data=='Connect'){
        $('#verify_details').text('');
        $('#verify_details').append(connect);
      }

      if(data=='Pending'){
        $('#verify_details').text('');
        $('#verify_details').append(pending);
        $("#verification-btn").hide();
      }

      if(data=='Resubmit'){
        $('#verify_details').text('');
        var res = '<h3>Hi '+name+'</h3>';
        res +='<p>Kindly Re-Upload the Documents.</p><p>(Reason:'+reason+')</p>';
        $('#verify_details').append(res);
      }
  }

  $(document).ready(function() {
    
    $('.verification-close, .rm_lg').click(function(event){
      $("body").removeClass("pos-fix");
      $(".verification").hide(); 
    });
    $('.top-home').click(function(event){
      event.stopPropagation();
    });
  });
</script>


<script type="text/javascript">
  $(document).ready(function() {
    $('.signup_popup_head').click(function(e){
     e.preventDefault();
     $("body").addClass("pos-fix");
     $(".sidebar").addClass("overflow-control");
     $(".signup_popup").show();
     $(".login_popup").hide();  
   });
    $('.login-close').click(function(){
     $("body").removeClass("pos-fix");
     $(".sidebar").removeClass("overflow-control");
     $(".signup_popup").hide(); 
   });
    $('.top-home').click(function(event){
      event.stopPropagation();
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.signup_popup_head2').click(function(e){
     e.preventDefault();
     $("body").addClass("pos-fix");
     $(".signup_popup2").show(); 
     $(".signup_popup").hide(); 
   });
    $('.login-close').click(function(){
     $("body").removeClass("pos-fix");
     $(".signup_popup2").hide(); 
   });
    $('.top-home').click(function(event){
      event.stopPropagation();
    });
    $(function() {

      var selectBox = $("select.footer-select").selectBoxIt();

    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('ul.menu-group li a').click(function()
    {
      $('.nav--sm').css('visibility','hidden');
    });
    $('.burger--sm').click(function()
    {
      $('.header--sm .nav--sm').css('visibility','visible');
      $('.makent-header .header--sm .nav-content--sm').css('left','0', 'important');
    });

    $('.nav-mask--sm').click(function()
    {
      $('.header--sm .nav--sm').css('visibility','hidden');
      $('.makent-header .header--sm .nav-content--sm').css('left','-285px');
    });

// if(document.getElementById('header-search-form'))
//     {
//         header_autocomplete= new google.maps.places.Autocomplete(document.getElementById('header-search-form'));
//         google.maps.event.addListener(header_autocomplete, 'place_changed', function() {
//             $('#header-search-settings').addClass('shown');
//             // $("#header-search-checkin").datepicker("show");
//         });
//     }
    // if(document.getElementById('search-modal--sm'))
    // {
    //     sm_autocomplete= new google.maps.places.Autocomplete(document.getElementById('header-location--sm'));
    //     google.maps.event.addListener(sm_autocomplete, 'place_changed', function() {
    //         $("#modal_checkin").datepicker("show");
    //     });
    // }
//     $("#modal_checkin").datepicker({
//     dateFormat: "dd-mm-yy",
//     minDate: 0,
//     beforeShow: function(input, inst) {
//         setTimeout(function() {
//                 inst.dpDiv.find('a.ui-state-highlight').removeClass('ui-state-highlight');
//             }, 100);
//     },
//     onSelect: function (date) 
//     {
//         var modal_checkout = $('#modal_checkin').datepicker('getDate');
//         modal_checkout.setDate(modal_checkout.getDate() + 1);
//         $('#modal_checkout').datepicker('setDate', modal_checkout);
//         $('#modal_checkout').datepicker('option', 'minDate', modal_checkout);
//         setTimeout(function(){
//             $("#modal_checkout").datepicker("show");
//         },20);
//     }
// });

// $('#modal_checkout').datepicker({
//     dateFormat: "dd-mm-yy",
//     minDate: 1,
//     onClose: function () 
//     {
//         var modal_checkin = $('#checkin').datepicker('getDate');
//         var modal_checkout = $('#modal_checkout').datepicker('getDate');
//         if (modal_checkout <= modal_checkin) 
//         {
//             var minDate = $('#modal_checkout').datepicker('option', 'minDate');
//             $('#modal_checkout').datepicker('setDate', minDate);
//         }
//     }
// });
// function trigger_checkin()
// {
//     $("#checkin").datepicker("show");
// }

// $("#checkin").datepicker({
//     dateFormat: "dd-mm-yy",
//     minDate: 0,
//     beforeShow: function(input, inst) {
//         setTimeout(function() {
//                 inst.dpDiv.find('a.ui-state-highlight').removeClass('ui-state-highlight');
//             }, 100);
//     },
//     onSelect: function (date) 
//     {
//         var checkout = $('#checkin').datepicker('getDate');
//         checkout.setDate(checkout.getDate() + 1);
//         $('#checkout').datepicker('setDate', checkout);
//         $('#checkout').datepicker('option', 'minDate', checkout);
//         setTimeout(function(){
//             $("#checkout").datepicker("show");
//         },20);
//     }
// });

// $('#checkout').datepicker({ 
//     dateFormat: "dd-mm-yy",
//     minDate: 1,
//     onClose: function () 
//     {
//         var checkin = $('#checkin').datepicker('getDate');
//         var checkout = $('#checkout').datepicker('getDate');
//         if (checkout <= checkin) 
//         {
//             var minDate = $('#checkout').datepicker('option', 'minDate');
//             $('#checkout').datepicker('setDate', minDate);
//         }
//         if($('#checkin').val()=='')
//         {
//          var checkin = $('#checkout').datepicker('getDate');
//         checkin.setDate(checkin.getDate() -1 );
//         $('#checkin').datepicker('setDate',  new Date());
//         $('#checkout').datepicker('option', 'minDate', checkout);
//         setTimeout(function(){
//             $("#checkin").datepicker("show");
//         },20);
//         }
//     }
// });
$('.nav-mask--sm').click(function()
{
  $('.header--sm .nav--sm').css('visibility','hidden');
  $('.makent-header .header--sm .nav-content--sm').css('left','-285px');
});

// $('.search-modal-trigger, #sm-search-field').click(function()
// {
//     $('#search-modal--sm').removeClass('hide');
//     $('#search-modal--sm').attr('aria-hidden','false');
// });

// $('.search-modal-container .modal-close').click(function()
// {
//     $('#search-modal--sm').addClass('hide');
//     $('#search-modal--sm').attr('aria-hidden','true');
// });
// $('#search-form--sm-btn').click(function(event)
// {
//     var location  = $("#header-search-form-mob").val();
//     if(location == '') {
//         $('.searchbar__location-error').removeClass('hide');
//         return false;
//     }
//     else
//         $('.searchbar__location-error').addClass('hide');

//     var sm_checkin = $('#modal_checkin').val();
//     var sm_checkout = $('#modal_checkout').val();
//     var sm_guests = $('#modal_guests').val();
//     var sm_room_type = '';

//     $('[id^="room-type-"]').each(function()
//     {
//         if($(this).is(':checked'))
//             sm_room_type += $(this).val()+',';
//     });
//     sm_room_type = sm_room_type.slice(0,-1);
//     if(location){ var locations = location.replace(" ", "+"); }
//     window.location.href = APP_URL+'/s?location='+locations+'&checkin='+sm_checkin+'&checkout='+sm_checkout+'&guests='+sm_guests+'&room_type='+sm_room_type;
//     event.preventDefault();
// });
$(document).on('change','#user_profile_pic', function() {
  $('#ajax_upload_form').submit();
});
});
</script>
<script> 
  var app = angular.module('App', ['ngSanitize']);
  var APP_URL = {!! json_encode(url('/')) !!};
  var google_client_id = '{{ $google_client_id }}';
  var LANGUAGE_CODE = "{!! (Session::get('language')) ? Session::get('language') : $default_language[0]->value !!}";
  var USER_ID = {!! @Auth::user()->id ? @Auth::user()->id : json_encode([]) !!};
    // set Inbox Count Globally 
    var inbox_count = {!! @Auth::user()->id ? @Auth::user()->inbox_count() : 0 !!};
    var more_text_lang = "{{trans('messages.profile.more')}}";
    var validation_messages  = {!! json_encode(trans('validation')) !!};
    var please_set_location = "{{trans('messages.home.please_set_location')}}";
    $.datepicker.setDefaults($.datepicker.regional[ "{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}" ])
  </script>

  {!! $head_code !!}

  {!! Html::script('js/common.js?v='.$version) !!}

  {!! Html::script('js/nouislider.min.js?v='.$version) !!}
  {!! Html::script('js/jquery.textfill.min.js?v='.$version) !!}  
  {!! Html::script('js/mcustom.js?v='.$version) !!}  
  {!! Html::script('js/jquery.bxslider.js') !!}  
  @if (!isset($exception))
  @if (Route::current()->uri() == '/')
  {!! Html::script('js/jquery.bxslider.min.js?v='.$version) !!}
  @if(@$default_home == 'two')
  {!! Html::script('js/home_two.js') !!}
  @endif
  @endif       

  @if (Route::current()->uri() == 'rooms/new')
  {!! Html::script('js/rooms_new.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif

  @if (Route::current()->uri() == 'manage-listing/{id}/{page}')
  {!! Html::script('js/manage_listing.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif

  @if (Route::current()->uri() == 's')
  {!! Html::script('js/home_two.js?v='.$version) !!}
  {!! Html::script('js/infobubble.js') !!}
  @endif
  @if (Route::current()->uri() == 'home_two')
  {!! Html::script('js/jquery.bxslider.min.js') !!}  
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif
  @if (Route::current()->uri() == 'trips/current')   
  {!! Html::script('js/home_two.js?v='.$version) !!} 
  @endif
  @if (Route::current()->uri() == 'trips/previous')   
  {!! Html::script('js/home_two.js?v='.$version) !!} 
  @endif
  @if (Route::current()->uri() == 'users/transaction_history')   
  {!! Html::script('js/home_two.js?v='.$version) !!} 
  @endif
  @if (Route::current()->uri() == 'users/security')   
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif
  @if (Route::current()->uri() == 'rooms/{id}')
  {!! Html::script('js/rooms.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  {!! Html::script('js/jquery.bxslider.min.js') !!}
  @endif

  @if (Route::current()->uri() == 'reservation/change')
  {!! Html::script('js/home_two.js?v='.$version) !!}
  {!! Html::script('js/reservation.js?v='.$version) !!}
  @endif

  @if (Route::current()->uri() == 'wishlists/popular' || Route::current()->uri() == 'wishlists/my' || Route::current()->uri() == 'wishlists/picks' || Route::current()->uri() == 'wishlists/{id}' || Route::current()->uri() == 'users/{id}/wishlists')
  {!! Html::script('js/wishlists.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif

  @if (Route::current()->uri() == 'inbox' || Route::current()->uri() == 'z/q/{id}' || Route::current()->uri() == 'messaging/qt_with/{id}')
  {!! Html::script('js/inbox.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif

  @if(Route::current()->uri() == 'disputes' || Route::current()->uri() == 'dispute_details/{id}' )
  {!! Html::script('js/disputes.js?v='.$version) !!}
  @endif

  @if (Route::current()->uri() == 'dashboard')   
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif
  @if (Route::current()->uri() == 'reservation/{id}')
  {!! Html::script('js/reservation.js?v='.$version) !!}
  {!! Html::script('js/home_two.js?v='.$version) !!}
  @endif

  @endif

  @if (Request::segment(1) == 'host' || Request::segment(1) == 'experiences')
  {!! Html::script('js/host_experiences/owl.carousel.js?v='.$version) !!}
  {!! Html::script('js/host_experiences/host_experience.js?v='.$version) !!}
  @endif

    <script src="https://apis.google.com/js/api:client.js"></script>   
    <script src="{{url('js/googleapilogin.js?v1=1.0')}}"></script>

  @stack('scripts')
  @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
  <script>
    $(function() {
      $('.login_popup').show();
      $('.signup_popup').hide();
      $('.signup_popup2').hide();
      $('.forgot-passward').hide();
    });
  </script>
  @endif
  @if(!empty(Session::get('error_code')) && Session::get('error_code') == 1)
  <script>
    $(function() {  
      $('.login_popup').hide();
      $('.signup_popup2').show();
      $('.signup_popup').hide();
      $('.forgot-passward').hide();
    });
  </script>
  @endif

  @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
  <script>
    $(function() {
     $('.login_popup').hide();
     $('.signup_popup').hide();
     $('.signup_popup2').hide();
     $('.forgot-passward').show();
   });
 </script>
 @endif

 <script>
  function height1() {
    var width5=$(window).width();
    if(width5 > 767){
      var a = $("#js-manage-listing-footer").outerHeight();
    }
    else{
      var a = 0;
    }
    if($('.tespri').hasClass('fixed'))
      var b = 0;
    else
      var b = $("#header").outerHeight();
    var c = $("#ajax_header").outerHeight();
    var d = $(".publish-actions").outerHeight();
    var e = $(window).height();
    var f = e - (a+b+c+d);
    $(".height_adj").css("cssText", "height : "+f+ "px !important");
  }
  $(window).scroll(function(){
    height1();
  });
  $(window).resize(function(){
    height1();
  });
  $(document).ready(function(){
    height1();
  });




  $(document).ready(function(){
    $(".search-modal-trigger, #photos, .photo-gallery1, .mob_photo-gallery, .button_1b5aaxl, .link-reset.burger--sm.header-logo, .vid_pop").click(function(e){
      $("body, html").addClass("non_scrl");
    });
    $(document).on('click', ".modal-close, #header .nav-mask--sm, .popup", function(){
      $("body, html").removeClass("non_scrl");
    });    
  });

/*$(document).ready(function(){
    $(".sidebar").scroll(function(){
      $("body").trigger('mousedown');
    });
    $(window).scroll(function(){
      $("body").trigger('mousedown');
    });
});
*/
$(document).ready(function(){
  $(".subnav-item.show-collapsed-nav-link").click(function(){
    $("body").toggleClass("non_scrl");
  });  
});

</script>

<!-- ver. 87c23752f8dfbd60bf83837d2c8b2dcd0ec660a9 -->
<div class="tooltip tooltip-bottom-middle" role="tooltip" aria-hidden="true">  
	<p class="panel-body">To sign up, you must be 18 or older. Other people won’t see your birthday.</p>
</div></body></html>

<div class="alert cookie-alert alert-dismissible">
  <i class="icon-remove-1 rm_lg" data-dismiss="alert"></i>
  <p>
    {{trans('messages.footer.using_cookies',['site_name'=>$site_name])}} <a href="{{url('/')}}/privacy_policy">{{trans('messages.login.privacy_policy')}}.</a>
  </p>
</div>
@if (!isset($exception))
@if (Route::current()->uri() != 'reservation/receipt' && Route::current()->uri() != 'reservation/itinerary')  
<!--Start of Tawk.to Script-->
<!-- <script>
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/57223b859f07e97d0da57cae/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
  })();
</script> -->
<!--End of Tawk.to Script-->
@endif
@endif

<script type="text/javascript">

 $(document).on('click','.cookie-alert .icon-remove-1',function()
 {
  writeCookie('status','1',10);
})

 var getCookiebyName = function(){
  var pair = document.cookie.match(new RegExp('status' + '=([^;]+)'));
  var result = pair ? pair[1] : 0;  
  $('.cookie-alert').show();
  if(result)
  {
   $('.cookie-alert').hide();
   return false;
 }
};

var url = window.location.href;
var arr = url.split("/");
var result = arr[0] + "//" + arr[2];
var domain =  result.replace(/(^\w+:|^)\/\//, '');

writeCookie = function(cname, cvalue, days) {
  var dt, expires;
  dt = new Date();
  dt.setTime(dt.getTime()+(days*24*60*60*1000));
  expires = "; expires="+dt.toGMTString();
  document.cookie = cname+"="+cvalue+expires+'; domain='+domain+ "; path=/"; 
}
getCookiebyName();

</script>