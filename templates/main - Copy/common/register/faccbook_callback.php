<?php 
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
require_once('social_login/core.inc.php');


$user = $facebook->getUser();

if ($user) { // IF FACEBOOK
  try { 
     $user_profile = $facebook->api('/me');
     var_dump($user_profile);
	     $logoutUrl = $facebook->getLogoutUrl();
	     $_SESSION['oauth_type'] ="facebook";
     }catch (FacebookApiException $e) {
     error_log($e);
     $user = null;
  }
}


?>