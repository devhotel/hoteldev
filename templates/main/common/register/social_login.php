<?php 
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
require_once('social_login/config/facebook.config.php');
require_once('social_login/config/google.config.php');
require_once('social_login/google/src/google_auth.php');

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
$oauth2 = new Google_Oauth2Service($client);

$user = $facebook->getUser();

if ($user) { // IF FACEBOOK
  try { 
     $user_profile = $facebook->api('/me');
	 $logoutUrl = $facebook->getLogoutUrl();
	 $_SESSION['oauth_type'] ="facebook";
     }catch (FacebookApiException $e) {
     error_log($e);
     $user = null;
  }
}else { // NOT FACEBOOK
$loginUrl = $facebook->getLoginUrl(array('scope' => 'email'));
if (isset($_GET['code'])) { // IF GOOGLE
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $_SESSION['oauth_type'] ="google";
  $user_profile = $oauth2->userinfo->get();
  return;
  }else { // NOT GOOGLE
  $authUrl = $client->createAuthUrl(); 
  }
  
}

///------------LogOut Auth --------------//////
if (isset($_REQUEST['logout'])) {
unset($_SESSION['token']);
unset($_SESSION["U_NAME"]);
unset($_SESSION["A_USERNAME"]);
unset($_SESSION["P_USERID"]);
session_destroy();
if ($user) {
header("Location:".$logoutUrl);
}
else {
$client->revokeToken();
header('Location: '.$_SERVER['PHP_SELF']); 
}
}
?>