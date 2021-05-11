<?php
/**
 * Script called (Ajax) on login
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
    
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");
$pass = htmlentities($_POST['pass'], ENT_COMPAT, "UTF-8");
if($pass=='') $response['error'] = 'Enter OTP';
//if($otp == '' || preg_match('/([0-9\-\s\+\(\)\.]+)/i', $mobile) !== 1) $response['notices']['mobile'] ='Enter valid mobile';
$user_id=$_POST['user_id'];
$result_user = $db->query("SELECT * FROM pm_user WHERE id='".$user_id."' AND checked = 1");
    if($result_user !== false && $db->last_row_count() > 0){
        $row = $result_user->fetch();
        $db->query("UPDATE pm_user SET token = '', pass = '".md5($pass)."' WHERE id = ".$user_id);
        $response['success'] = "Please verify OTP for reset  password.";
        $response['user_id'] = $row['id']; 
        $response['code'] ='2';
        $response['error']='';
        $response['success'] = "Your Password successfully reset"; 
    }else{
        $response['error'] = 'Enter valid Password';
    }
echo json_encode($response);
exit;