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

if(isset($_POST['room_id']) && isset($_POST['price'])){
       $_SESSION['rid']=$_POST['room_id'];
       $_SESSION['rprice']=$_POST['price'];
	 if(isset($_SESSION['rid']) && isset($_SESSION['rprice'])){
		echo 'true';
	 }else{
	    echo 'false';
	}
}

exit();