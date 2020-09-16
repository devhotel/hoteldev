<?php
/**
 * Script called (Ajax) on reset password
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");
 if(isset($_POST['mobile'])){
     $mobile = htmlentities($_POST['mobile'], ENT_COMPAT, "UTF-8");
     if(preg_match('/([0-9\-\s\+\(\)\.]+)/i', $mobile) !== 1){
       $response['error'] ='Please Enter Valid Mobile No.';
     }else{  
     //(login = ".$db->quote($user)." OR email = ".$db->quote($user).")
     $result_user = $db->query("SELECT * FROM pm_user WHERE (mobile = ".$db->quote($mobile).") AND type='registered' AND checked = 1");
     if($result_user !== false && $db->last_row_count() > 0){
            $new_pass = getRandonOTP(4);
            $row = $result_user->fetch();
            if($row['id']){
                $message = "Hi, ".$new_pass." is your one time password(OTP) for login";
                if(httpPost($loguser,$message)){
                  $db->query("UPDATE pm_user SET token = '', pass = '".md5($new_pass)."' WHERE id = ".$row['id']);
                  $response['user_id'] = $row['id'];  
                  $response['success'] = "New OTP has been sent to your Mobile.";  
                }
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