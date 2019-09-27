<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="{{ url('admin_assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ url('admin_assets/plugins/jQueryUI/jquery-ui.min.js') }}"></script>


<script src="{{ url('js/angular.js') }}"></script>
<script src="{{ url('js/angular-sanitize.js') }}"></script>

<script> 
var app = angular.module('App', ['ngSanitize']);
var APP_URL = {!! json_encode(url('/')) !!}; 
var ADMIN_URL =  '{!! ADMIN_URL  !!}';
</script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
  $(document).ready(function(){
    $('.ui-datepicker').addClass('notranslate');
  })
</script>

<!-- Bootstrap 3.3.5 -->
<script src="{{ url('admin_assets/bootstrap/js/bootstrap.min.js') }}"></script>

@if (!isset($exception))

    @if (Route::current()->uri() == '{ADMIN_URL}/dashboard')
    	<!-- Morris.js charts -->
      <script src="{{ url('admin_assets/plugins/morris/raphael-min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/morris/morris.min.js') }}"></script>
      <!-- datepicker -->
      <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		  <script src="{{ url('admin_assets/dist/js/dashboard.js') }}"></script>
    @endif

     @if (Route::current()->uri() == '{ADMIN_URL}/add_user' || Route::current()->uri() == '{ADMIN_URL}/edit_user/{id}')
      <!-- <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script> -->
    @endif

    @if (Route::current()->uri() == '{ADMIN_URL}/add_coupon_code' || Route::current()->uri() == '{ADMIN_URL}/edit_coupon_code/{id}')
      <!-- <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script> -->
    @endif

    @if (Route::current()->uri() == '{ADMIN_URL}/users' || Route::current()->uri() == '{ADMIN_URL}/reservations' || Route::current()->uri() == '{ADMIN_URL}/host_penalty' || Route::current()->uri() == '{ADMIN_URL}/admin_users' || Route::current()->uri() == '{ADMIN_URL}/roles' || Route::current()->uri() == '{ADMIN_URL}/permissions' || Route::current()->uri() == '{ADMIN_URL}/amenities' || Route::current()->uri() == '{ADMIN_URL}/amenities_type' || Route::current()->uri() == '{ADMIN_URL}/property_type' || Route::current()->uri() == '{ADMIN_URL}/room_type' || Route::current()->uri() == '{ADMIN_URL}/bed_type' || Route::current()->uri() == '{ADMIN_URL}/currency' || Route::current()->uri() == '{ADMIN_URL}/language' || Route::current()->uri() == '{ADMIN_URL}/country' || Route::current()->uri() == '{ADMIN_URL}/api_credentials' || Route::current()->uri() == '{ADMIN_URL}/payment_gateway' || Route::current()->uri() == '{ADMIN_URL}/site_settings' || Route::current()->uri() == '{ADMIN_URL}/rooms' || Route::current()->uri() == '{ADMIN_URL}/pages' || Route::current()->uri() == '{ADMIN_URL}/metas' || Route::current()->uri() == '{ADMIN_URL}/home_cities' || Route::current()->uri() == '{ADMIN_URL}/reviews' || Route::current()->uri() == '{ADMIN_URL}/help_category' || Route::current()->uri() == '{ADMIN_URL}/help_subcategory' || Route::current()->uri() == '{ADMIN_URL}/help' || Route::current()->uri() == '{ADMIN_URL}/coupon_code' || Route::current()->uri() == '{ADMIN_URL}/wishlists' || Route::current()->uri() == '{ADMIN_URL}/referrals'  || Route::current()->uri() == '{ADMIN_URL}/slider' || Route::current()->uri() == '{ADMIN_URL}/our_community_banners' || Route::current()->uri() == '{ADMIN_URL}/host_banners' || Route::current()->uri() == '{ADMIN_URL}/bottom_slider' || Route::current()->uri() == '{ADMIN_URL}/host_experiences_reservation' || Route::current()->uri() == '{ADMIN_URL}/host_experiences_reviews' || Route::current()->uri() == '{ADMIN_URL}/disputes' || Route::current()->uri() == '{ADMIN_URL}/payouts' || Route::current()->uri() == '{ADMIN_URL}/host_experiences_inquiries' || Route::current()->uri() == '{ADMIN_URL}/ngofees' || Route::current()->uri() == '{ADMIN_URL}/features')
      <script src="{{ url('admin_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    @endif

    @if (Route::current()->uri() == '{ADMIN_URL}/add_room' || Route::current()->uri() == '{ADMIN_URL}/edit_room/{id}')
      <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>
      <!-- admin rooms add/edit form array method validation -->
      <!-- <script src="{{ url('admin_assets/plugins/jQuery/jquery.validate.js') }}"></script> -->      
      <script src="{{ url('admin_assets/dist/js/jquery.validate.js') }}"></script>
      <script src="{{ url('admin_assets/dist/js/rooms.js') }}"></script>
      <style type="text/css">
        .ui-datepicker-prev, .ui-datepicker-next {
          padding: 0 !important;
          margin: 0 !important;
        }
        .ui-datepicker-calendar tr td span, .ui-datepicker-calendar tr th span, .ui-datepicker-calendar tr td a {
          -webkit-box-sizing: border-box;
          box-sizing: border-box;
        }
        a.ui-state-default.ui-state-hover, a.ui-state-default.ui-state-active, a.ui-state-default.ui-state-highlight {
          border: 1px solid #ff5a5f !important;
          background: #fbf9ee url(images/ui-bg_glass_55_fbf9ee_1x400.png) 50% 50% repeat-x !important;
          color: #363636 !important;
        }
      </style>
    @endif

    @if (Route::current()->uri() == '{ADMIN_URL}/reports')
    <script src="{{ url('admin_assets/dist/js/reports.js') }}"></script>
    @endif

    @if (Route::current()->uri() == '{ADMIN_URL}/add_page' || Route::current()->uri() == '{ADMIN_URL}/edit_page/{id}' || Route::current()->uri() == '{ADMIN_URL}/send_email' || Route::current()->uri() == '{ADMIN_URL}/add_help' || Route::current()->uri() == '{ADMIN_URL}/edit_help/{id}')
    <script src="{{ url('admin_assets/plugins/editor/editor.js') }}"></script>
      <script type="text/javascript"> 
        $("[name='submit']").click(function(){
          $('#content').text($('#txtEditor').Editor("getText"));
          $('#message').text($('#txtEditor').Editor("getText"));
          $('#answer').text($('#txtEditor').Editor("getText"));
        });
      </script>
    @endif

    @if(Route::current()->uri() == '{ADMIN_URL}/add_property_type' || Route::current()->uri() == '{ADMIN_URL}/edit_property_type/{id}' || Route::current()->uri() == '{ADMIN_URL}/add_room_type' || Route::current()->uri() == '{ADMIN_URL}/edit_room_type/{id}'|| Route::current()->uri() == '{ADMIN_URL}/add_bed_type' || Route::current()->uri() == '{ADMIN_URL}/edit_bed_type/{id}' || Route::current()->uri() == '{ADMIN_URL}/add_amenities_type' || Route::current()->uri() == '{ADMIN_URL}/edit_amenities_type/{id}'|| Route::current()->uri() == '{ADMIN_URL}/add_amenity' || Route::current()->uri() == '{ADMIN_URL}/edit_amenity/{id}'|| Route::current()->uri() == '{ADMIN_URL}/add_home_city' || Route::current()->uri() == '{ADMIN_URL}/edit_home_city/{id}' )
    <script src="{{ url('admin_assets/dist/js/jquery.validate.js') }}"></script>
    <!-- form validation admin side (amenity,property_type,room_type,bed_type)-->
    <script type="text/javascript">
      $(document).ready(function() {
    // validate the comment form when it is submitted
    $("#form").validate({
        focusInvalid: false,
        rules: {
          "lang_code[]": "required",
            "name[]": "required",           
            "status":"required",
            "type_id":"required",
            "icon":"required",
            'image': { 
              required : true,
              extension:"png|jpg|jpeg|gif"
            },
            "images": { 
              extension:"png|jpg|jpeg|gif"
            },
        },
        messages: {
          "lang_code[]":"The Language field is required",
            "name[]": "The Name field is required",           
            "status": "The status field is required",
            "type_id":"The Type field is required",
            "icon":"The Icon field is required",
            "image":"The Image field is required",
        }

    });
});
      $.validator.addMethod("extension", function(value, element, param) {
      param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
      return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
      }, $.validator.format("Please upload the images like JPG,JPEG,PNG,GIF File Only."));
    </script>
    <!-- end script -->
    @endif
   @endif
<!-- AdminLTE App -->
<script src="{{ url('admin_assets/dist/js/app.js') }}"></script>
<script src="{{ url('admin_assets/dist/js/common.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ url('admin_assets/dist/js/demo.js') }}"></script>

@stack('scripts')

<script type="text/javascript">
  $('#dataTableBuilder_length').addClass('dt-buttons');
  $('#dataTableBuilder_wrapper > div:not("#dataTableBuilder_length").dt-buttons').css('margin-left','20%');
</script>

</body>
</html>