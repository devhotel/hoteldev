<?php
/**
 * Script called on Country List API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$result_exists = $db->query('SELECT name as country_name FROM pm_country');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$rowCountry = $result_exists->fetchAll();
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array("country_list" => $rowCountry));
	displayOutput($response);
	
} else {
	
	$response=array('status'=>array('error_code'=>1,'message'=>'No Country Found'));
	displayOutput($response);
	
}