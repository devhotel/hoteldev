<?php
/**
 * Script called (Ajax) on login
 */
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
    $response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "code" => "");
    $otp = htmlentities($_POST['otp'], ENT_COMPAT, 'UTF-8');
 if(isset($_SESSION['otp']) && $_SESSION['otp']==$otp){
        $nuid = 0;
        $data = array();
        $data['id'] = null;
        $data['firstname'] = 'Traveler';
        $data['lastname'] = 'Traveler';
        $data['login'] = $_SESSION['mobile'];
        $data['pass'] = $_SESSION['pass'];
        $data['add_date'] = time();
        $data['checked'] = 1;
        $data['type'] = 'registered';
        $data['mobile'] = $_SESSION['mobile'];
        $data['otp'] = $otp;
        $data['email'] = '';
        $result_user = db_prepareInsert($db, 'pm_user', $data);
        if($result_user->execute() !== false){
                $nuid=$db->lastInsertId();
                   $_SESSION['user']['id'] = $nuid;
                   $_SESSION['user']['login'] = 'Traveler';
                   $_SESSION['user']['email'] = $sign_user;
                   $_SESSION['user']['name'] = 'Traveler';
                   $_SESSION['user']['type'] = 'registered';
                   $response['success'] = $texts['ACCOUNT_CREATED'];
                   $response['user_id'] = $nuid;  
                   $response['code'] ='1';
                   $response['error']='';
                
        }else{
            $response['error'] = $texts['ACCOUNT_CREATE_FAILURE'].'!!';
        } 
     
  }else{
    $response['error'] = 'Please Enter Valid OTP';
  }
  echo json_encode($response);
exit;