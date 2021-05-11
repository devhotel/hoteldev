<?php
/**
 * Script called (Ajax) on reset password
 */
echo "ok"; die;
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "code" => "");
 if(isset($_POST['loguser'])){
    $otp_pass = getRandonOTP(4);
     $loguser = htmlentities($_POST['loguser'], ENT_COMPAT, "UTF-8");
        if(preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/i', $loguser)){
            $result_user = $db->query("SELECT * FROM pm_user WHERE email = ".$db->quote($loguser)." AND checked = 1");
            if($result_user !== false && $db->last_row_count() > 0){
                   $row = $result_user->fetch();
                    $mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
                    <p style="margin: 20px 0 10px;">Dear User</p>
                    <p style="margin: 0 0 10px;">OTP is '.$otp_pass.' to login into your '.SITE_TITLE.' account. Please do not share it with anyone.</p>
                 </td></tr>';
                $email = $loguser;
                $from_email ='welcome@hms.com';
                $from_name = 'HMS';
                if(isset($_POST['stat']) && $_POST['stat']=='otp'){
                  if(sendMail($email, $name, 'OTP for login '.SITE_TITLE, $mailContent,'','',$from_email,$from_name) !== false){
                    $db->query("UPDATE pm_user SET token = '', otp = '".$otp_pass."' WHERE id = ".$row['id']);
                    $response['success'] = "Please login with Existing  password.";
                    $response['user_id'] = $row['id']; 
                    $response['code'] ='2';
                        $response['code'] = '4';
                        $response['success'] = "OTP Successfully resend.";
                    }
                    $response['error']='';
                }else{
                    //$response['error'] = $texts['ACCOUNT_CREATE_FAILURE'].'!!';
                    $response['user_id'] = $row['id']; 
                    $response['code'] ='2';
                    $response['success'] = "Please login with Existing  password.";
                    $response['error']='';
                } 
            }else{
                $response['error'] = $texts['NOT_REGISTERED_EMAIL'];
            }
     }else if(preg_match('/^(\+){0,1}(91){0,1}(-|\s){0,1}[0-9]{10}$/i', $loguser)){  
       $result_user = $db->query("SELECT * FROM pm_user WHERE (mobile = ".$db->quote($loguser).") AND type='registered' AND checked = 1");
       if($result_user !== false && $db->last_row_count() > 0){
            $row = $result_user->fetch();
            if($row['id']){
                $message = $otp_pass."  is the OTP to login into your ".SITE_TITLE." account";
                if(httpPost($loguser,$message)){
                    $db->query("UPDATE pm_user SET token = '', otp = '".$otp_pass."' WHERE id = ".$row['id']);
                    $response['user_id'] = $row['id'];  
                    $response['success'] = "New OTP has been sent to your Mobile.";
                    $response['code'] = '1';
                    if(isset($_POST['stat']) && $_POST['stat']=='otp'){
                        $response['code'] = '4';
                        $response['success'] = "OTP Successfully resend.";
                    }
                }
            }
        }else{
            if(isset($_POST['stat']) && $_POST['stat']=='otpv'){
                $message = $otp_pass."  is the OTP to login into your ".SITE_TITLE." account";
                if(httpPost($loguser,$message)){
                    $_SESSION['otp'] = $otp_pass;
                    $response['success'] = "OTP Successfully resend.";
                    $response['code'] ='3';
                    $response['error']='';
                }
            }else{
               $response['error'] = 'Mobile No. Not Exist !!'; 
            }
            
         }
     }else{
        $response['error'] = 'Please enter a valid Email Id or Mobile Number.';
     }
  }else{
    $response['error'] = $texts['EMPTY_LOGIN']; 
 }
 
echo json_encode($response);
exit;