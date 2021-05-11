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

if($db !== false && isset($_POST['pass'])){
    $password = $_POST['pass'];
      $user = (isset($_SESSION['admin']['id'])?$_SESSION['admin']['id']:0);
      $result_user = $db->query('SELECT * FROM pm_user WHERE id = '.$db->quote($user).' AND pass = '.$db->quote(md5($password)).' AND type != "registered" AND checked = 1');
	 if($result_user !== false && $db->last_row_count() > 0){
		echo 'true';
	 }else{
	    echo 'false';
	}
}

exit();