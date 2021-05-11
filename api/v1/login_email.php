<?php
/**
 * Script called on Login With Email API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$email = htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');
$password = htmlentities($_POST['password'], ENT_COMPAT, 'UTF-8');
$fcm_token = $_POST['fcm_token'];
$device_type = $_POST['device_type'];


if(empty($email)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Email Id is required'));
  displayOutput($response);
}

if(empty($password)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Password is required'));
  displayOutput($response);
}

$pass = md5($password);

$result_exists = $db->query('SELECT id, email, firstname, lastname, address, postcode, city, state, country, company, country_code as countryCode, mobile FROM pm_user WHERE  (email = "'.$email.'" AND pass = "'.$pass.'")');

if($result_exists !== false && $db->last_row_count() > 0){
	
	//$result_user = $db->query('SELECT * FROM pm_user WHERE  (email = '.$db->quote($email).' AND pass = '.md5($password).')');
	$userrow = $result_exists->fetch();
				
	$_SESSION['user']['id'] = $userrow['id'];
	$_SESSION['user']['login'] = 'Traveler';
	$_SESSION['user']['email'] = $email;
	$_SESSION['user']['name'] = 'Traveler';
	$_SESSION['user']['type'] = 'registered';
	
	$checkSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "'.$userrow['id'].'" AND device_type = "'.$device_type.'"');
	
	if($checkSql !== false && $db->last_row_count() > 0){
		
		$updateDevice = $db->query('UPDATE pm_device SET fcm_token = "'.$fcm_token.'" WHERE user_id  = "'.$userrow['id'].'" AND device_type = "'.$device_type.'"');
		if($updateDevice){
			$response=array('status'=>array('error_code'=>0,'message'=>'Successfully Logged In'), 'response' => array('user'=>$userrow));
			displayOutput($response);
		} else {
			$response=array('status'=>array('error_code'=>1,'message'=>'Something is wrong while update device token'));
			displayOutput($response);
		}
		
	} else {
		
		//Add Device Token
		$data = array();
		$data['id'] = null;
		$data['user_id'] = $userrow['id'];
		$data['device_type'] = $device_type;
		$data['fcm_token'] = $fcm_token;
		
		$insertDevice = db_prepareInsert($db, 'pm_device', $data);
		$insertDevice->execute();
		//Add Device Token
		
		if($insertDevice->execute() !== false){
			
			$response=array('status'=>array('error_code'=>0,'message'=>'Successfully Logged In'), 'response' => array('user'=>$userrow));
			displayOutput($response);
			
		} else {
			
			$response=array('status'=>array('error_code'=>1,'message'=>'Something is wrong while Insert device token'));
			displayOutput($response);
			
		}
		
	}
	
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'Details is not Matched with System'));
	displayOutput($response);
	
}
displayOutput($response);

?>