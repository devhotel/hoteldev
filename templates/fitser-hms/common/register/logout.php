<?php
/**
 * Script called (Ajax) on logout
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
    
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");
if(isset($_SESSION['page'])){
   $_SESSION['page'];
   if($_SESSION['page']=='page_11'){
     $response["redirect"] = DOCBASE;  
   }else{
      $response["redirect"] = DOCBASE; 
   }
}else{
      $response["redirect"] = DOCBASE;  
}
if(isset($_SESSION['user'])){
    setcookie("stored_user_name", $_SESSION['user']['login'], time() + 604800, "/");
    $_COOKIE["stored_user_name"] = $_SESSION['user']['login'];
    unset($_SESSION['user']);
    
}

echo json_encode($response);
