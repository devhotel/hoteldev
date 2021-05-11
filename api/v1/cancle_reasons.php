<?php
/**
 * Script called on Cancle Reasons List API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$reasonList = array(array("reson_name" => "Change plan"),array("reson_name" => "Got a better deal"),array("reson_name" => "Want to book different hotel"),array("reson_name" => "Booking created by mistake"),array("reson_name" => "Others"));

$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array("reason_list" => $reasonList));
displayOutput($response);