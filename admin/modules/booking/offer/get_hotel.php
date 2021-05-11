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

if($db !== false ){
     if(isset($_POST['id_hotel']) && is_numeric($_POST['id_hotel'])){    
        $result_room = $db->query('SELECT id, title, (SELECT title FROM pm_hotel WHERE id = '.$_POST['id_hotel'].' AND lang = '.DEFAULT_LANG.') as hotel  FROM pm_room WHERE id_hotel  = '.$_POST['id_hotel'].' AND lang = '.DEFAULT_LANG);
    	if($result_room !== false && $db->last_row_count() > 0){
    		//$response = $result_room->fetch(PDO::FETCH_ASSOC);
    		foreach($result_room as $key=>$room){
    		    array_push($response, $room);
    		}
    	}
     }else{
        $result_room = $db->query('SELECT id, title, (SELECT title FROM pm_hotel WHERE id = pr.id_hotel AND lang = '.DEFAULT_LANG.') as hotel  FROM pm_room as pr WHERE checked  = 1 AND lang = '.DEFAULT_LANG);
    	if($result_room !== false && $db->last_row_count() > 0){
    		//$response = $result_room->fetch(PDO::FETCH_ASSOC);
    		foreach($result_room as $key=>$room){
    		    array_push($response, $room);
    		}
    	} 
    }
}

echo json_encode($response);
