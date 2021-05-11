<?php
/**
 * Script called (Ajax) on reset password
 */
require_once("../../common/lib.php");
require_once("../../common/define.php");


$data=json_decode(file_get_contents('php://input'), true);

$mobile = $data['mobile'];

if(empty($mobile)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Mobile Number is required'));
  displayOutput($response);
}


$result_user = $db->query("SELECT * FROM pm_user WHERE (mobile = ".$db->quote($mobile).") AND type='registered' AND checked = 1");
if($result_user !== false && $db->last_row_count() > 0){
	
	$new_pass = getRandonOTP(4);
    $row = $result_user->fetch();
	
	if($row['id']){
		$message = "Hi, ".$new_pass." is your one time password(OTP) for login";
		if(httpPost($mobile,$message)){
		  //$db->query("UPDATE pm_user SET token = '', pass = '".md5($new_pass)."' WHERE id = ".$row['id']);
		  $response['user_id'] = $row['id']; 
		  $response['otp'] = $new_pass;	  
		  $response['success'] = "New OTP has been sent to your Mobile.";  
		  
		  $response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>$response);
		  displayOutput($response);
		} else {
			$response=array('status'=>array('error_code'=>1,'message'=>'Something is wrong. OTP not sent!'));
		    displayOutput($response);
		}
	}
	
} else {
	$response=array('status'=>array('error_code'=>1,'message'=>'No User Found!'));
	displayOutput($response);
}