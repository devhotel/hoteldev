<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../common/lib.php');
require_once('../../../common/define.php');

$response = array();

if($db !== false && isset($_POST['item_type'])){
    if($_POST['item_type']=='hotel'){
       $result_room = $db->query('SELECT id, title FROM pm_hotel WHERE id = '.$_POST['id_item'].' AND lang = '.DEFAULT_LANG);
    	if($result_room !== false && $db->last_row_count() > 0){
    		$response = $result_room->fetch(PDO::FETCH_ASSOC);
    	} 
    }else if($_POST['item_type']=='article'){
      $result_room = $db->query('SELECT  id, title FROM pm_article WHERE id = '.$_POST['id_item'].' AND lang = '.DEFAULT_LANG);
    	if($result_room !== false && $db->last_row_count() > 0){
    		$response = $result_room->fetch(PDO::FETCH_ASSOC);
    	}  
    }
    
}


echo json_encode($response);
