var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');

app.controller('reports', ['$scope', '$http', function($scope, $http) {
  $scope.formatted_from="";
  $scope.formatted_to="";
  $scope.report = function(from, to, category)
  {
    $scope.loading = true;
    $http.post(APP_URL+'/'+ADMIN_URL+'/reports', { from: from, to: to, category: category }).then(function( response ) {
      $scope.formatted_from=response.data.from;
      $scope.formatted_to=response.data.to;
      if(!$scope.category) {
      	$scope.users_report = response.data.result;
      	$scope.rooms_report = false;
      	$scope.reservations_report = false;
        $scope.experience_report = false;
        $scope.exp_reservations_report = false;
      }
      if($scope.category == 'rooms') {
      	$scope.users_report = false;
      	$scope.rooms_report = response.data.result;
      	$scope.reservations_report = false;
        $scope.experience_report = false;
        $scope.exp_reservations_report = false;
      }
      if($scope.category == 'reservations') {
      	$scope.users_report = false;
      	$scope.rooms_report = false;
        $scope.reservations_report = response.data.result;
        $scope.experience_report = false;
        $scope.exp_reservations_report = false;
      }

      if($scope.category == 'experience') {
        $scope.users_report = false;
        $scope.rooms_report = false;
        $scope.experience_report = response.data.result;
        $scope.exp_reservations_report = false;
        $scope.reservations_report = false;
      }

      if($scope.category == 'exp_reservations') {
        $scope.users_report = false;
        $scope.rooms_report = false;
        $scope.exp_reservations_report = response.data.result;
        $scope.experience_report = false;
        $scope.reservations_report = false;
      }
      $scope.loading = false;
    });
  };

  $scope.print = function(category)
  {
    category = (!category) ? 'users' : category;
    var prtContent = document.getElementById(category);
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
  };

  $('.date').datepicker({ 'dateFormat': datepicker_format, maxDate: new Date()});


   $( document ).ready(function() {
     $( "#from_to_disable").change(function()
       {
         var value = $("#from_to_disable option:selected").val();
         if(value =='reservations')
          {       
            // $('.date').datepicker('destroy');
            $('.date').datepicker('option', 'maxDate', '')
            $('.date').datepicker('refresh');
            } 
            else
            {
              $('.date').datepicker('option', 'maxDate', new Date())
              $('.date').datepicker('refresh');
            }         
        });
    });
  
  
}]);