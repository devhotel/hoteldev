<?php
/**
 * Script called (Ajax) on customer update
 * fills the customer fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

if(!isset($_POST['create_user'])){
        $data = array();
        $data['id'] = ($_POST['id_user'] > 0) ? $_POST['id_user'] : null;
        $data['firstname'] = $_POST['user_firstname'];
        $data['lastname'] = $_POST['user_lastname'];
        $data['email'] = $_POST['user_email'];
        $data['login'] = $_POST['user_email'];
        $data['company'] = $_POST['user_company'];
        $data['govid_type'] = $_POST['user_govidtype'];
        $data['govid'] = $_POST['user_govid'];
        $data['address'] = $_POST['user_address'];
        $data['postcode'] = $_POST['user_postcode'];
        $data['city'] = $_POST['user_city'];
        $data['users'] = $_SESSION['admin']['id'];
        $data['mobile'] = $_POST['user_mobile'];
        $data['country'] = $_POST['user_country'];
        $data['checked'] = '1';
            if($_POST['id_user']==0){
                $data['type'] = 'registered';
                $result_user = db_prepareInsert($db, 'pm_user', $data);
                if($result_user->execute() !== false){
                   $user_id = $db->lastInsertId();
                     $purpose = 'New user '.$_POST['user_firstname']. ' '.$_POST['user_lastname'].' is created' ;
                      // Add activity log
                    add_activity_log($_SESSION['admin']['id'], $user_id, 'user' , 'add', $purpose);
                    echo  $user_id;

                }
           }else{
                $result_user = db_prepareUpdate($db, 'pm_user', $data); 
                 if($result_user->execute() !== false){
                    $purpose = 'New user '.$_POST['user_firstname']. ' '.$_POST['user_lastname'].' is updated' ;
                      // Add activity log
                    add_activity_log($_SESSION['admin']['id'], $data['id'], 'user' , 'edit', $purpose);
                   echo  $_POST['id_user'];
                }
                
            }    
   }
exit();

