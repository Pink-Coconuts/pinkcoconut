$('#butn').click(function(){
    $('#butn').hide();
});
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $(".confirm-delete").on('click',function(){
      $(".confirm-delete").attr("disabled", "disabled")
     });
});

app.controller('help', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

$scope.change_category = function(value) {
    $http.post(APP_URL+'/'+ADMIN_URL+'/ajax_help_subcategory/'+value).then(function(response) {
        $scope.subcategory = response.data;
        $timeout(function() { $('#input_subcategory_id').val($('#hidden_subcategory_id').val()); $('#hidden_subcategory_id').val('') }, 10);
    });
};

$timeout(function() { $scope.change_category($scope.category_id); }, 10);
$scope.multiple_editors = function(index) {
     setTimeout(function() {
            $("#editor_"+index).Editor();
            $("#editor_"+index).parent().find('.Editor-editor').html($('#content_'+index).val());
        }, 100);
    }
    $("[name='submit']").click(function(e){
        $scope.content_update();
    });
    // $(document).on('blur', '.Editor-container .Editor-editor', function(){
    //     i = $(this).parent().parent().children('.editors').attr('data-index');
    //     $('#content_'+i).text($('#editor_'+i).Editor("getText"));
    //     $('#content_'+i).valid();
    // });
    $scope.content_update = function() {
        $.each($scope.translations,function(i, val) {
            $('#content_'+i).text($('#editor_'+i).Editor("getText"));
        })
        return  false;
    }
    // var v = $("#admin_page_form").validate({
    //     ignore: '',
    // });

}]);
app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {
  return function(value, key, stack) {
    var found = $filter('filter')(stack, {locale: value});
    var found_text = $filter('filter')(stack, {key: ''+value}, true);
    return !found.length && !found_text.length;
  };
}])

app.filter('checkActiveTranslation', ["$filter", function($filter) {
  return function(translations, languages) {
    var filtered =[];
    $.each(translations, function(i, translation){
        if(languages.hasOwnProperty(translation.locale))
        {
            filtered.push(translation);
        }
    });
    return filtered;
  };
}])

app.controller('navigation', ['$filter','$scope', '$http', '$compile', '$timeout', function($filter,$scope, $http, $compile, $timeout) {
    $(document).ready(function(){
    var format = $scope.format;
    $scope.formats;
    var a = format.split("/");
    if(a[1]){
        if(a[0]=='d')
        {
           $scope.formats = "d/M/y";
        }
        else if(a[0]=='m')
        {
            $scope.formats = "M/d/y";
        }
        else if(a[0]=='Y')
        {
            $scope.formats = "y/M/d";
        }
    }
    else{
    var a = format.split("-");
        if(a[0]=='d')
        {
            $scope.formats = "d-M-y";
        }
        else if(a[0]=='m')
        {
           $scope.formats = "M-d-y";
        }
        else if(a[0]=='Y')
        {
            $scope.formats = "y-M-d";
        }
    }

var currenttime = $('#current_time').val();

var serverdate=new Date(currenttime)

 function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

     function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
$scope.formattedDate =   $filter('date')(serverdate, $scope.formats);
var datestring=$scope.formattedDate;
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("show_date_time").innerHTML="<b>"+datestring+"</b>"+"&nbsp;<b>"+timestring+"</b>";
}

window.onload=function(){
setInterval( function(){displaytime()},1000);
}

});
}]);

if($('#input_driver').val()=='mailgun')
{
    $('#hide_show').show();
    $('#show_hide').hide();
}
else
{
    $('#hide_show').hide();
    $('#show_hide').show();
}
$(document).on('keyup','#input_driver',function(){
    if($('#input_driver').val()=='mailgun')
    {
        saved_domain = $("#saved_domain").val();
        saved_secret = $("#saved_secret").val();
        $("#input_domain").val(saved_domain);
        $("#input_secret").val(saved_secret);
        
        $('#hide_show').show();
        $('#show_hide').hide();
    }
    else
    {   
        smtp_username = $('#smtp_username').val();
        smtp_password = $('#smtp_password').val();

        $('#input_username').val(smtp_username);
        $('#input_password').val(smtp_password);
        
        $('#hide_show').hide();
        $('#show_hide').show();
    }

 });

   
$(document).on('change','select.go',function(event) {
    var cI = $(this); 
   var id = $(this).attr("id");
    var others=$('select.go').not(cI);  
    $('#'+id).next('p').remove();
    $.each(others,function(){
         if($(cI).val()==$(this).val() && $(cI).val()!="")//check if value has been 
         {
           $(cI).val('');//empty the value
        
        $('#'+id).after('<p class="text-danger remove-danger">Already selected this language</p>');         
         $("label[for='"+id +"']").addClass('hide');
          
       }
    });
});
$('#lang_1 > option').attr('disabled','disabled');
$('#lang_1 > option[value="en"]').removeAttr('disabled');

var count=$('#increment').val();

var option ='';
$("#lang_1 > option").each(function() {  
    option+='<option value='+this.value+'>'+this.text+'</option>';
});

 $(document).on('click','.add_lang',function(){	

count++;

$('.multiple_lang_add:last').append('<div class="multiple_lang"> <div class="form-group"> <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label> <div class="col-sm-6"><select class="form-control go" name="lang_code[]" id="lang_'+count+'"><option value="">Select</option>'+option+'</select></div></div><div class="form-group"> <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label> <div class="col-sm-6"> <input class="form-control"  placeholder="Name" name="name[]" type="text" value="" id="input-name_'+count+'"> </div></div><div class="form-group"> <label for="input_description_'+count+'" class="col-sm-3 control-label">Description</label> <div class="col-sm-6"> <textarea class="form-control" id="input_description" placeholder="Description" rows="3" name="description[]" cols="50"></textarea></div></div><button type="button" class="btn btn-danger remove_lang" style="float:right;">Remove</button></div>');


});

 $(document).on('click','.add_lang_bed',function(){	

 count++;

$('.multiple_lang_add:last').append('<div class="multiple_lang"> <div class="form-group"> <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label> <div class="col-sm-6"><select class="form-control go" name="lang_code[]" id="lang_'+count+'"><option value="">Select</option>'+option+'</select></div></div><div class="form-group"> <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label> <div class="col-sm-6"> <input class="form-control"  placeholder="Name" name="name[]" type="text" value="" id="input-name_'+count+'"> </div></div><button type="button" class="btn btn-danger remove_lang" style="float:right;">Remove</button></div>');


});
 $(document).on('click','.add_lang_city',function(){ 

 count++;

$('.multiple_lang_add:last').append('<div class="multiple_lang"> <div class="form-group"> <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label> <div class="col-sm-6"><select class="form-control go" name="lang_code[]" id="lang_'+count+'"><option value="">Select</option>'+option+'</select></div></div><button type="button" class="btn btn-danger remove_lang" style="float:right;">Remove</button><div class="form-group"> <label for="input_name" class="col-sm-3 control-label">City Name<em class="text-danger">*</em></label> <div class="col-sm-6"> <input class="form-control"  placeholder="Name" name="name[]" type="text" value="" id="input-name_'+count+'"> </div></div></div>');


});




$(document).on('click','.remove_lang',function(){
    $(this).closest(".multiple_lang").remove();
});

$(document).on('click','.pull-right',function(){
    $('.remove-danger').remove();
    $('.hide').remove();
});

/* Datatable exception handler  */
if($.fn.dataTable){
    $.fn.dataTable.ext.errMode = function () { 
        window.location.reload();
    };
}


/* Sitesettings home page toggle inputs  */

$(document).ready(function(){

    var home_type = $('select[name="default_home"]').val();
        toogle_home_settings(home_type);

    $('select[name="default_home"]').change(function(){
        var home_type = $(this).val()
        toogle_home_settings(home_type);
    });

    function toogle_home_settings(home_type){ 
        if(home_type == 'home_two'){
            $('select[name="home_page_header_media"], input[name="footer_cover_image"], input[name="home_video"], input[name="home_video_webm"]').parents('.form-group').hide();
        } else {
            $('select[name="home_page_header_media"], input[name="footer_cover_image"], input[name="home_video"], input[name="home_video_webm"]').parents('.form-group').show();
        }
    }

});
app.controller('category_language', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

/*$scope.add_catgory = function(){
      $scope.help_category.push({'name' : ''});
}
$scope.remove_category = function(index){
    alert(index);
     $scope.help_category.splice(index, 1);    
}*/


}]);
app.controller('page', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {
    $scope.multiple_editors = function(index) {
        setTimeout(function() {
            $("#editor_"+index).Editor();
            $("#editor_"+index).parent().find('.Editor-editor').html($('#content_'+index).val());
        }, 100);
    }
    $("[name='submit']").click(function(e){
        $scope.content_update();
    });
    // $(document).on('blur', '.Editor-container .Editor-editor', function(){
    //     i = $(this).parent().parent().children('.editors').attr('data-index');
    //     $('#content_'+i).text($('#editor_'+i).Editor("getText"));
    //     $('#content_'+i).valid();
    // });
    $scope.content_update = function() {
        $.each($scope.translations,function(i, val) {
            $('#content_'+i).text($('#editor_'+i).Editor("getText"));
        })
        return  false;
    }
    // var v = $("#admin_page_form").validate({
    //     ignore: '',
    // });
}]);

app.controller('users', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
    $(document).on('change', '.id_document_verification_status', function(){  
        if($(this).val() == 'Resubmit') {
            $('.id_resubmit_reason_div').removeClass('hide');
        }
        else {
            $('.id_resubmit_reason_div').addClass('hide');
        }
    });

    $(document).ready(function() {
        if($('.id_document_verification_status').val() == 'Resubmit') {
            $('.id_resubmit_reason_div').removeClass('hide');
        }
        else {
            $('.id_resubmit_reason_div').addClass('hide');
        }
    });

}]);

app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {
  return function(value, key, stack) {
    var found = $filter('filter')(stack, {locale: value});
    var found_text = $filter('filter')(stack, {key: ''+value}, true);
    return !found.length && !found_text.length;
  };
}])

app.filter('checkActiveTranslation', ["$filter", function($filter) {
  return function(translations, languages) {
    var filtered =[];
    $.each(translations, function(i, translation){
        if(languages.hasOwnProperty(translation.locale))
        {
            filtered.push(translation);
        }
    });
    return filtered;
  };
}])

$(window).scroll(function() {
 $('.pac-container.pac-logo').hide();
});

app.controller('site_settings', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {
    $(document).ready(function(){
        $http.get(APP_URL+'/currency_cron');
    });
}]);
