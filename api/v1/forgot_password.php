<?php
/**
 * Script called on Forgot Password API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$email = $data['email'];

if(empty($email)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Email Id is required'));
  displayOutput($response);
}

$result_exists = $db->query('SELECT * FROM pm_user WHERE email = "'.$email.'"');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
					<p style="margin: 20px 0 10px;">Password Reset Link</p>
					<p style="margin: 0 0 10px;">Kindly <a href="'.getUrl(true).DOCBASE.'forgot-password" style="color: #00767b;text-align: center;font-weight: bold;display: inline-block;">Click Here</a> to continue</p>
				 </td></tr>';
	$email = $email;
	$from_email ='welcome@guptahotels.com';
	$from_name = 'Gupta Hotels';
	
	if(sendMail($email, $name, 'Reset Password', $mailContent,'','',$from_email,$from_name) !== false){
		$response=array('status'=>array('error_code'=>0,'message'=>'Success'));
		displayOutput($response);
	} else{
		$response=array('status'=>array('error_code'=>1,'message'=>'Something is Wrong. Try Again!'));
		displayOutput($response);
	}
	
} else {
	$response=array('status'=>array('error_code'=>1,'message'=>'Email Id not exist in system!'));
	displayOutput($response);
}