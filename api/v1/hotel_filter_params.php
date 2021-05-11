<?php
/**
 * Script called on Hotel Filter Params API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$result_exists = $db->query('SELECT class as name FROM pm_hotel WHERE class <> 0 GROUP BY class');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$result = array();
	$row = array(array('id' => '1', 'name' => '1-2000'), array('id' => '2', 'name' => '2001-4000'), array('id' => '3', 'name' => '4001-8000'), array('id' => '4', 'name' => '8000>'));
	
	//$row1 = $result_exists->fetchAll();
	$row1 = array(array('id' => '1', 'name' => '1'), array('id' => '2', 'name' => '2'), array('id' => '3', 'name' => '3'), array('id' => '4', 'name' => '4'), array('id' => '5', 'name' => '5'));
	
	$result_accommodation = $db->query('SELECT a.id_accommodation as id, b.name FROM pm_hotel a INNER JOIN pm_accommodation b ON a.id_accommodation = b.id GROUP BY a.id_accommodation');
	$row2 = $result_accommodation->fetchAll();
	
	$result[0] = array('header'=>'Price Range', 'child' =>$row);
	$result[1] = array('header'=>'Hotel Class', 'child' =>$row1);
	$result[2] = array('header'=>'Accommodation Type', 'child' =>$row2);
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response' =>array('filterList' =>$result));
	displayOutput($response);
	
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'Hotel Not Found!'));
	displayOutput($response);
	
}
displayOutput($response);