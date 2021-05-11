<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$response = array();

if($db !== false && isset($_GET['user_email'])){
   
    $result_user = $db->query('SELECT * FROM pm_user WHERE email = "'.$_GET['user_email'].'"');
   
	if($result_user !== false && $db->last_row_count() > 0){
	
		echo 'true';
	}else{
	    echo 'false';
	}
}

exit();