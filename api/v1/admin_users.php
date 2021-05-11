<?php
/**
 * Script called on Hotel Admin Users API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$hotelId = $data['hotelId'];

if(empty($hotelId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id is required'));
  displayOutput($response);
}

$result_exists = $db->query('SELECT users FROM pm_hotel WHERE id = "'.$hotelId.'"');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$rowUser = $result_exists->fetch();
	$getUsers = explode(',', $rowUser['users']);
	
	$result = array();
	$i = 0;
	foreach($getUsers as $getUser){
		
		$resultDetails = $db->query('SELECT id as emp_id, CONCAT(firstname," ",lastname) as emp_name, type as emp_type, CONCAT("/medias/users/", user_image) as emp_image FROM pm_user WHERE id = "'.$getUser.'"');
		$rowDetails = $resultDetails->fetch();
		
		$result[$i] = $rowDetails;
		
	$i++;
	}
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('admin_user'=>$result));
	displayOutput($response);
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'Users Not Found!'));
	displayOutput($response);
	
}