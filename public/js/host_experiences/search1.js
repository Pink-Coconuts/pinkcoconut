

$(document).ready(function(){
    $(".guestbut").click(function(){
      target = $(this).attr('data-target-div');
    	$(".guestbut1:not([id='"+target+"'])").hide();
      $("#"+target).toggle();
      // $('.guestbut').not(this).removeClass('active');
      if($("#"+target).is(':visible'))
      {
        // $(this).addClass('active');
        $('.sidebar').addClass('newdp');
      }
      else
      {
        // $(this).removeClass('active');
        $('.sidebar').removeClass('newdp');
      }
      $(".morefit").hide();
      $('.map,.sidesear').removeClass('mapfil');
    });
});

$(document).ready(function(){
    $(".gut").click(function(){
      $(".morefit").toggle();
      $('.map,.sidesear').toggleClass('mapfil');
      $(".guestbut1").hide();
      $('.sidebar').removeClass('newdp');
    });
});  

$(document).ready(function(){
    $(".close_target").click(function(){
      $(".guestbut1").hide();
      // $('.guestbut').removeClass('active');
      $('.sidebar').removeClass('newdp');
    });
});  
	

$(document).ready(function(){ 
    $('.clsfa,.gut1').click(function(){
        $(".morefit1").toggle();
           
    });
});  

$(document).ready(function(){
    $('.clsfa2,.gut2').click(function(){
        $(".morefit2").toggle();
           
    });
});  

$(document).ready(function(){
    $('.clsfa3,.gut3').click(function(){
        $(".morefit3").toggle();
           
    });
}); 

$(document).ready(function(){
    $('.clsfa5,.gut5').click(function(){
        $(".morefit5").toggle();
           
    });
}); 

$(document).ready(function(){
     $(".lot").click(function(){
        $(".lotser").toggle();
    });

});
$(document).ready(function(){
     $(".lot1").click(function(){
        $(".lotser1").toggle();
    });

});

$(document).ready(function(){
     $(".button_ipunk").click(function(){
        $(".viewmap").toggle(600);
    });

});

$(document).ready(function(){
    $(".checkmore input").click(function(){
        // $('.checkmore .checkbox').toggleClass('sdcheck');
    });


});


$(document).ready(function(){
    $(".seead1").click(function(){
        $(".slidedow1").toggle();
         $('.seead1').toggleClass('newsed');
    });
    
});

$(document).ready(function(){
    $(".seead2").click(function(){
        $(".slidedow2").toggle();
         $('.seead2').toggleClass('newsed');
    });
    
});

$(document).ready(function(){
    $(".seead3").click(function(){
        $(".slidedow3").toggle();
         $('.seead3').toggleClass('newsed');
    });
    
});

$(document).ready(function(){
    $(".seead4").click(function(){
        $(".slidedow4").toggle();
         $('.seead4').toggleClass('newsed');
    });
    
});


function increaseValue() {
  var value = parseInt(document.getElementById('number').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  $(".guest-select").val(value);
}

function decreaseValue() {
  var value = parseInt(document.getElementById('number').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  $(".guest-select").val(value);
}

function increaseValue1() {
  var value = parseInt(document.getElementById('number1').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number1').value = value;
}

function decreaseValue1() {
  var value = parseInt(document.getElementById('number1').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number1').value = value;
}

function increaseValue2() {
  var value = parseInt(document.getElementById('number2').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number2').value = value;
}

function decreaseValue2() {
  var value = parseInt(document.getElementById('number2').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number2').value = value;
}

function increaseValue3() {
  var value = parseInt(document.getElementById('number3').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number3').value = value;
}

function decreaseValue3() {
  var value = parseInt(document.getElementById('number3').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number3').value = value;
}

function increaseValue4() {
  var value = parseInt(document.getElementById('number4').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number4').value = value;
}

function decreaseValue4() {
  var value = parseInt(document.getElementById('number4').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number4').value = value;
}


function increaseValue5() {
  var value = parseInt(document.getElementById('number5').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number5').value = value;
}

function decreaseValue5() {
  var value = parseInt(document.getElementById('number5').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number5').value = value;
}


function increaseValue3() {
  var value = parseInt(document.getElementById('number3').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number3').value = value;
}

function decreaseValue3() {
  var value = parseInt(document.getElementById('number3').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('number3').value = value;
}




(function($){
$(window).on("load",function(){

          
      $("#content-2").mCustomScrollbar({
          theme:"minimal"
      });
});
})(jQuery);

$(document).ready(function(){
    $(".search_header_form").submit(function(e){
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
});
