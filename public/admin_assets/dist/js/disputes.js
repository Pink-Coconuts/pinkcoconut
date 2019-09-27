var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');

app.filter('range', function() {
  return function(input, total, from) {
    if(from == undefined)
    {
        from = 0;
    }
    total = parseInt(total);

    for (var i=from; i<=total; i++) {
      input.push(i);
    }

    return input;
  };
}).controller('dispute_details',['$scope', '$http', function($scope, $http) {
    $('.tabs li > .tab-item').click(function() {
        tab_target = $(this).attr('data-target');
        $('.tabs .tab-item').attr('aria-selected', "false");
        $('.tabs-content div').attr('aria-hidden', "true");

        $(this).attr('aria-selected', "true");
        $('.tabs-content div[data-tab_content="'+tab_target+'"]').attr('aria-hidden', "false");
    });

    $scope.admin_message_form_errors = [];
    $scope.admin_message = function(){
        $(".box .overlay").removeClass('hide');
        dispute_id = $('#dispute_id').val();

        $http.post(APP_URL+'/'+ADMIN_URL+'/dispute_admin_message/'+dispute_id, $scope.admin_message_data).then(function(response){
            if(response.data.status == 'danger')
            {
                window.location.reload();
            }
            else if(response.data.status == 'error'){
                $scope.admin_message_form_errors = response.data.errors;
            }
            else if(response.data.status == 'success'){
                $('#thread-list').prepend(response.data.content);
                $scope.admin_message_data = {'message':'', 'amount': ''};
                $scope.admin_message_form_errors = [];
            }

            $(".box .overlay").addClass('hide');
        })
    }
}]);

$(document).on('contextmenu', 'a[data-method="post"]', function() {
    return false;
});
$(document).on('click', 'a[data-method="post"]', function() {
    $('a[data-method="post"]').attr('disabled', 'disabled');
});