<?php
/**
 * Script called (Ajax) on reset password
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "code" => "");
 if(isset($_POST['loguser'])){
     $loguser = htmlentities($_POST['loguser'], ENT_COMPAT, "UTF-8");
     if(preg_match('/([0-9\-\s\+\(\)\.]+)/i', $loguser) !== 1){
            $result_user = $db->query("SELECT * FROM pm_user WHERE email = ".$db->quote($loguser)." AND checked = 1");
            if($result_user !== false && $db->last_row_count() > 0){
                  $row = $result_user->fetch();
                    $_SESSION['otp']['id'] = $row['id'];
                    $response['success'] = "Please login with Existing  password .";
                    $response['user_id'] = $row['id'];  
                    $response['code'] = '2';
            }else{
                $response['error'] = $texts['NOT_REGISTERED_EMAIL'];
            }

     }else{  
     $result_user = $db->query("SELECT * FROM pm_user WHERE (mobile = ".$db->quote($loguser).") AND type='registered' AND checked = 1");
     if($result_user !== false && $db->last_row_count() > 0){
            $new_pass = getRandonOTP(4);
            $row = $result_user->fetch();
            if($row['id']){
                $message = "Hi, ".$new_pass." is your one time password(OTP) for login";
                httpPost($loguser,$message);
                $_SESSION['otp']['id'] = $row['id'];
                $db->query("UPDATE pm_user SET token = '', otp = '".$new_pass."' WHERE id = ".$row['id']);
                $response['user_id'] = $row['id'];  
                $response['success'] = "New OTP has been sent to your Mobile.";
                $response['code'] = '1';
            }
     
        }else{
            $response['error'] = 'Mobile No. Not Exist !!';
         }
     }
  }else{
    $response['error'] = $texts['EMPTY_LOGIN']; 
 }
 
echo json_encode($response);
exit;