<?php
/**
 * Script called on Get Rating API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$hotelId = $data['hotelId'];
$commentType = "hotel";
$userId = $data['userId'];
$rating = $data['rating'];
$comment = $data['comment'];

if(empty($hotelId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id is required'));
  displayOutput($response);
}

if(empty($userId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Id is required'));
  displayOutput($response);
}

if(in_array('', array_column($rating, 'param_title'))) {
	$response=array('status'=>array('error_code'=>1,'message'=>'Param Title is required in Rating'));
  	displayOutput($response);
}

if(in_array('', array_column($rating, 'param_rating'))) {
	$response=array('status'=>array('error_code'=>1,'message'=>'Param Rating is required in Rating'));
  	displayOutput($response);
}

$totalrating = 0;
$rt=array();
foreach($rating as $key=>$value){	
	$totalrating += $value['rating_value'];
	$rt[$value['name']]=$value['rating_value'];	
}
//$jSon = json_encode($rt);
$totalCount = count($rating);
$avarageTotal = $totalrating / $totalCount;

$serializeArray = serialize($rt);


$result_user = $db->query('SELECT CONCAT(firstname," ",lastname) as name, email FROM pm_user WHERE id = "'.$userId.'"');

if($result_user !== false && $db->last_row_count() > 0){
	
	$rowUser = $result_user->fetch();
	
	//function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		//return $ipaddress;
	//}
	
	$data = array();
	$data['id_user'] = $userId;
	$data['item_type'] = $commentType;
	$data['id_item'] = $hotelId;
	$data['rating'] = $avarageTotal;
	$data['params'] = $serializeArray;
	$data['checked'] = 1;
	$data['add_date'] = time();
	$data['name'] = $rowUser['name'];
	$data['email'] = $rowUser['email'];
	$data['msg'] = $comment;
	$data['ip'] = $ipaddress;
	
	$result_comment = db_prepareInsert($db, 'pm_comment', $data);
	
	if($result_comment->execute() !== false){
	
		$response=array('status'=>array('error_code'=>0,'message'=>'Comment Successfully Posted.'));
		displayOutput($response);
	
	} else {
		
		$response=array('status'=>array('error_code'=>1,'message'=>'Something is wrong. Please try again.'));
		displayOutput($response);
		
	}
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'User Data Not Found!'));
	displayOutput($response);
	
}




