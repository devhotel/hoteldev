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

if($db !== false && isset($_POST['id_hotel']) && is_numeric($_POST['id_hotel'])){
    $result_room = $db->query('SELECT id FROM pm_tax WHERE (SELECT id_accommodation FROM pm_hotel WHERE id = '.$_POST['id_hotel'].' AND lang = '.DEFAULT_LANG.') IN (id_accommodation)');
	if($result_room !== false && $db->last_row_count() > 0){
		$response = $result_room->fetch(PDO::FETCH_ASSOC);
		
	}
}

/*
if($db !== false && isset($_POST['id_hotel']) && is_numeric($_POST['id_hotel'])){
    $result_room = $db->query('SELECT id_accommodation FROM pm_hotel WHERE id = '.$_POST['id_hotel'].' AND lang = '.DEFAULT_LANG);
	if($result_room !== false && $db->last_row_count() > 0){
		$res = $result_room->fetch(PDO::FETCH_ASSOC);
		$result_room_2 = $db->query('SELECT id FROM pm_tax WHERE '.$res["id_accommodation"].' IN (id_accommodation)');
		if($result_room_2 !== false && $db->last_row_count() > 0){
		    $response = $result_room_2->fetch(PDO::FETCH_ASSOC);
		}
	}
}
*/
echo json_encode($response);
