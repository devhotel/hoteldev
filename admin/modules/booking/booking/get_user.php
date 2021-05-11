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

if($db !== false && isset($_POST['mobile'])){
    $result_user = $db->query('SELECT * FROM pm_user WHERE id!=1 AND mobile = '.$_POST['mobile']);
	if($result_user !== false && $db->last_row_count() > 0){
		$response = $result_user->fetch(PDO::FETCH_ASSOC);

	}
}

echo json_encode($response);
