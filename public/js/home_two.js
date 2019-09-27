 var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
 var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
//alert(date_format);

$('.foryou').click(function() {
 $('.foryou').addClass('current');
 $('.homes').removeClass('current');
 $('.experiences').removeClass('current');
});
$('.homes').click(function() {
 $('.foryou').removeClass('current');
 $('.homes').addClass('current');
 $('.experiences').removeClass('current');
});
$('.home-menu .experiences').click(function() {
 $('.foryou').removeClass('current');
 $('.homes').removeClass('current');
 $('.experiences').addClass('current');
});
 //$('#header-avatar-trigger').click(function(e){
 //e.preventDefault();
 //  e.stopPropagation();
 //$(this).unbind('mouseenter mouseleave');
 // $('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').toggleClass('show');
 //   $('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').toggleClass('hide');

 // });

 $('body').click(function() {
     //$('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').addClass('hide');
     $('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').removeClass('show');
     $('.panel-drop-down').addClass('hide-drop-down');
   });
 $('.button-sm-search').click(function(e) {
   e.stopPropagation();
     // $('.panel-drop-down').toggleClass('hide-drop-down');
     $('#search-modal--sm').removeClass('hide');
     $('#search-modal--sm').attr('aria-hidden', 'false');
   });
 $('.arrow-button').click(function(e) {
   e.stopPropagation();
   $('.panel-drop-down').toggleClass('hide-drop-down');
 });
 $(document).ready(function() {
     //$('#header-avatar-trigger').unbind('mouseenter mouseleave');

   });
 $('.home-bx-slider .bxslider:not(.host_experience_bxslider)').bxSlider({
   infiniteLoop: false,
   hideControlOnEnd: true,
   minSlides: 1,
   maxSlides: 3,
   slideWidth: 320,
   slideMargin: 20,
   moveSlides: 1,
   onSliderLoad: function() {
     setTimeout(function() {
       $("#lazy_load_slider").removeClass('lazy-load');
     }, 2000);
   }
 });
 start = moment();
 $('.webcot-lg-datepicker button').daterangepicker({
   startDate: start,
   minDate: start,
   dateLimitMin:{
    "days": 1
  },
  autoApply: true,
  autoUpdateInput: false,
  locale: {
   format: daterangepicker_format
 },
});
 $('.webcot-lg-datepicker button').on('show.daterangepicker', function(ev, picker) {
   $(this).parent().css('opacity', 0);
   $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 1 !important');
 });
 $('.webcot-lg-datepicker button').on('apply.daterangepicker', function(ev, picker) {
   startDateInput = $('[name="checkin"]');
   endDateInput = $('[name="checkout"]');

   startDate = picker.startDate;
   endDate = picker.endDate;

   startDateInput.val(startDate.format(daterangepicker_format));
   startDateInput.next().html(startDate.format(daterangepicker_format));
   endDateInput.val(endDate.format(daterangepicker_format));
   endDateInput.next().html(endDate.format(daterangepicker_format));
 });
 $(".webcot-lg-datepicker button").on('hide.daterangepicker', function(ev, picker) {
   if (!picker.startDate || !picker.endDate) {
     $(this).parent().css('opacity', 1);
     $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 0 !important');
   }
 });

 $(document).ready(function(){  

  $('.searchButton_n8fchz').click(function(e)
  {   
    $('p.set_location_error').remove();

    if($('#header-search-form').val()=='')
    {
      e.preventDefault();
      $('.container_e4p5a8').before('<p class="set_location_error">'+please_set_location+'</p>');
    }

  })
  $('#header-search-form').change(function(){
   $('p.set_location_error').remove();

 })

  $('[data-toggle="tooltip"]').tooltip(); 

})

// bx slider modified
$(document).ready(function(){

 $('.home-bx-slider .bxslider').each(function(i, slider){
  var a = $(slider).children('li').length;
  li_width = 320/*li width static*/ + 30 /*li margin right*/;
  slider_width = (a*li_width);
  $(slider).css('width', slider_width+'px');
});  
});

$('.container_puzkdo').click(function() {
  $("html").addClass("pos-fixing");

});
$('.modal-close').click(function() {
  $("html").removeClass("pos-fixing");

});  


