<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once("../common/lib.php");
require_once("../common/define.php");



$response = array(array('id'=>0,'title'=>'All'));

if($db !== false && isset($_POST['id_location']) && is_numeric($_POST['id_location'])){
    if($_POST['id_location']>0){
    $result_hotels = $db->query('SELECT id, title FROM pm_hotel WHERE    FIND_IN_SET('.$_SESSION['admin']['id'].', users) AND id_destination= '.$_POST['id_location'].'  AND lang = '.DEFAULT_LANG);
	if($result_hotels !== false && $db->last_row_count() > 0){
		//$response = $result_hotels->fetch(PDO::FETCH_ASSOC);
    		//$response = $result_room->fetch(PDO::FETCH_ASSOC);
    		foreach($result_hotels as $key=>$hotel){
    		    array_push($response, $hotel);
    		
    	 } 
	  }
    }else{
        $result_hotels = $db->query('SELECT id, title FROM pm_hotel WHERE FIND_IN_SET('.$_SESSION['admin']['id'].', users) AND checked = 1 AND  lang = '.DEFAULT_LANG);
	    if($result_hotels !== false && $db->last_row_count() > 0){
		//$response = $result_hotels->fetch(PDO::FETCH_ASSOC);
    		//$response = $result_room->fetch(PDO::FETCH_ASSOC);
    		foreach($result_hotels as $key=>$hotel){
    		    array_push($response, $hotel);
    		
    	 } 
	  }
    }
}


echo json_encode($response);
