<?php
/**
 * Script called on Notification List API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];

if(empty($userId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Id is required'));
  displayOutput($response);
}


$result_noti = $db->query('SELECT * FROM pm_notification WHERE user_id = "'.$userId.'" ORDER BY id DESC');

if($result_noti !== false && $db->last_row_count() > 0){
	
	$notirow = $result_noti->fetchAll();
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response' => array('notification_list'=>$notirow));
	displayOutput($response);
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'Notification Not Found!'));
	displayOutput($response);
	
}