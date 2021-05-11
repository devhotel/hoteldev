<?php
/**
 * Script called (Ajax) on login
 */
require_once("../../../common/lib.php");
require_once("../../../common/define.php");
    
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");

$otp = htmlentities($_POST['otp'], ENT_COMPAT, "UTF-8");
if($otp=='') $response['error'] = 'Enter OTP';
//if($otp == '' || preg_match('/([0-9\-\s\+\(\)\.]+)/i', $mobile) !== 1) $response['notices']['mobile'] ='Enter valid mobile';
$user_id=$_POST['user_id'];
$result_user = $db->query("SELECT * FROM pm_user WHERE id='".$user_id."' AND otp = '".$otp."' AND checked = 1");
if($otp!= ''){
    if($result_user !== false && $db->last_row_count() > 0){
        $row = $result_user->fetch();
        $_SESSION['user']['id'] = $row['id'];
        $_SESSION['user']['login'] = $row['login'];
        $_SESSION['user']['email'] = $row['email'];
        $_SESSION['user']['name'] = $row['firstname'];
        $_SESSION['user']['type'] = $row['type'];
        $response['redirect'] = DOCBASE;
        $response['success'] = "You are Successfully Logged in."; 
    }else{
        $response['error'] = 'Please enter a valid OTP.';
    }
}else{
   $response['error'] = $texts['EMPTY_LOGIN']; 
}
echo json_encode($response);
