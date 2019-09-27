var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: google_client_id,
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('google_login'),'');
      attachSignin(document.getElementById('pop_google_login'),'');
      attachSignin(document.getElementById('pop_google_signup_login'),'');
      attachSignin(document.getElementById('google_connect'),'yes');
      attachSignin(document.getElementById('google_email_login'),'yes');

    });
  };

  function attachSignin(element,value="") {
    // console.log(element.id);

    auth2.attachClickHandler(element, {},
        function(googleUser) {  

        var id_token = googleUser.getAuthResponse().id_token;

        window.location = APP_URL+'/googleAuthenticate?idtoken='+id_token+'&connect='+value;

        }, function(error) {
          // alert(JSON.stringify(error, undefined, 2));
        });
  }

//   function google_login(id_token) 
// {
// 	var xhr = new XMLHttpRequest();
// 	xhr.open('POST', 'http://localhost/product/makent/public/googleAuthenticate');
// 	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
// 	xhr.onload = function() {
// 	window.location.reload();
// 	};
// 	xhr.send('idtoken=' + id_token);

// }

startApp();