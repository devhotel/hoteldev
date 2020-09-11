window.fbAsyncInit = function() {
  //FB JavaScript SDK configuration and setup
  FB.init({
    appId      : '1376685939160319', // FB App ID
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true,  // parse XFBML
    version    : 'v2.8' // use graph api version 2.8
  });
  
  // Check whether the user already logged in the website using Facebook login
  FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
      //If user already logged in the Facebook then check the 
      //Get the Facebook user's info
      verify_user();
    }
  });
};
// Load the SDK asynchronously
(function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
}(document));
function FBLogin() {
  FB.login(function (response) {
    if (response.authResponse) {
      verify_user();
    } else {
      alert('User cancelled login or did not fully authorize.');
    }
  }, {scope: 'email'});
}
function verify_user() {
  FB.api('/me', {locale: 'en_US', fields: 'id, name, first_name, last_name,email, link, gender,locale, picture'},
  function (response) {
    var html = '<table width="100%">';
      html += '<tr><th rowspan="3" scope="row"><img src="'+ response.picture.data.url +'" /></th><td>Name</td><td>:</td><td>'+ response.name +'</td></tr>';
      html += '<tr><td>First Name</td><td>:</td><td>'+ response.first_name +'</td></tr>';
      html += '<tr><td>Last Name </td><td>:</td><td>'+ response.last_name +'</td></tr>';
      html += '<tr><th scope="row"> </th><td>Email</td><td>:</td><td>'+ response.email +'</td></tr>';
      html += '<tr><th scope="row"> </th><td>Gender</td><td>:</td><td>'+ response.gender +'</td></tr>';
      html += '<tr><th scope="row"> </th><td>Facebook Link</td><td>:</td><td><a href="'+ response.link +'" target="_blank">Link</a></td> </tr>';
      html += '<tr><th scope="row"> </th><td>Logout Link</td><td>:</td><td><a href="void:javascript(0);" onclick="FBLogout();">Logout</a></td> </tr>';
      html += '</table>';
      $("#status").html(html);
      $.ajax({
      url: "oauth-user.php",
      data: {userinfo : JSON.stringify(response)},
      type: "POST",
      success: function(resp) {
        //Your cutom code after successful login 
      }
    });
  });  
}
