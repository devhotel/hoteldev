<?php
/**
 * Script called on Profile Details API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

//$uerId = htmlentities($_POST['uerId'], ENT_COMPAT, 'UTF-8');

$data=json_decode(file_get_contents('php://input'), true);

$uerId = $data['uerId'];

if(empty($uerId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Id is required'));
  displayOutput($response);
}

$result_exists = $db->query('SELECT id, email, firstname, lastname, address, postcode, city, state, country, company, country_code as countryCode, mobile, CONCAT("/medias/users/", user_image) as user_image FROM pm_user WHERE  (id = '.$uerId.')');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$row = $result_exists->fetch();
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response' => array('user' => $row));
	displayOutput($response);
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'User Not Registered!'));
	displayOutput($response);
	
}
displayOutput($response);
