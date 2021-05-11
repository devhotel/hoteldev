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

if($db !== false && isset($_POST['id_room']) && is_numeric($_POST['id_room'])){
    $result_room = $db->query('SELECT * FROM pm_room WHERE id = '.$_POST['id_room'].' AND lang = '.DEFAULT_LANG);
	if($result_room !== false && $db->last_row_count() > 0){
		$response = $result_room->fetch(PDO::FETCH_ASSOC);
	}
}

echo json_encode($response);
