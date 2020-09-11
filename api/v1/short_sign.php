<?php
/**
 * Script called (Ajax) on login
 */
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
    $response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "code" => "");
    $sign_user = htmlentities($_POST['sign_user'], ENT_COMPAT, 'UTF-8');
    $sign_pass = htmlentities($_POST['sign_pass'], ENT_COMPAT, 'UTF-8');

    if($sign_user == ''){
       $response['notices']['email'] = $texts['REQUIRED_FIELD'];  
    }else if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/i', $sign_user)){
       if(is_numeric($sign_user)){
            if(!preg_match('/^(\+){0,1}(91){0,1}(-|\s){0,1}[0-9]{10}$/i', $sign_user)){
              $response['notices']['mobile'] = "Invalid Mobile No."; 
              $response['error'] = $texts['INVALID_EMAIL'];
            }
       }else{
           $response['notices']['email'] = $texts['INVALID_EMAIL']; 
           $response['error'] = $texts['INVALID_EMAIL'];
       }  

    }
  if($sign_user != ''){
        $result_exists = $db->query('SELECT * FROM pm_user WHERE  (email = '.$db->quote($sign_user).' OR mobile = '.$db->quote($sign_user).')');
        if($result_exists !== false && $db->last_row_count() > 0){
            $row = $result_exists->fetch();
            if($sign_user == $row['email']){
                $response['notices']['email'] = $texts['ACCOUNT_EXISTS'];
                $response['error'] = 'An Account Already Exists with this e-mail';
            }
            if($sign_user == $row['mobile']){
                $response['notices']['mobile'] = $texts['USERNAME_EXISTS'];
                $response['error'] = 'An Account Already Exists with this Mobile Number!!';
            }
     }
  }

 if(count($response['notices']) == 0){
        $nuid = 0;
        $data = array();
        $data['id'] = null;
        $data['firstname'] = 'Traveler';
        $data['lastname'] = 'Traveler';
        $data['login'] = $sign_user;
        $data['pass'] = md5($sign_pass);
        $data['add_date'] = time();
        $data['checked'] = 1;
        $data['type'] = 'registered';
    if(preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/i', $sign_user)){
        $data['email'] = $sign_user;
        $data['mobile'] = '';
        $data['otp'] = '';
        $result_user = db_prepareInsert($db, 'pm_user', $data);
        if($result_user->execute() !== false){
                $nuid=$db->lastInsertId();
                $_SESSION['user']['id'] = $nuid;
                $_SESSION['user']['login'] = 'Traveler';
                $_SESSION['user']['email'] = $sign_user;
                $_SESSION['user']['name'] = 'Traveler';
                $_SESSION['user']['type'] = 'registered';
                $mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
                    <p style="margin: 20px 0 10px;">Your account has been created with email address '.$sign_user.' </p>
                    <p style="margin: 0 0 10px;">We hope to host you in the future.</p>
                    <p style="margin: 0 0 10px;">Kindly <a href="'.getUrl(true).DOCBASE.'login" style="color: #00767b;text-align: center;font-weight: bold;display: inline-block;">log-in</a> to continue</p>
                 </td></tr>';
                $email = $sign_user;
                $from_email ='welcome@hms.com';
                $from_name = 'HMS';
                if(sendMail($email, $name, 'Welcome to HMS', $mailContent,'','',$from_email,$from_name) !== false){
                    $response['success'] = $texts['ACCOUNT_CREATED'];
                    $response['user_id'] = $nuid; 
                    $response['code'] ='2';
                    $response['error']='';
                }else{
                    $response['error'] = $texts['ACCOUNT_CREATE_FAILURE'].'!!';
                }
        }else{
            $response['error'] = $texts['ACCOUNT_CREATE_FAILURE'].'!!';
        }
        
     }else if(preg_match('/^(\+){0,1}(91){0,1}(-|\s){0,1}[0-9]{10}$/i', $sign_user)){
        $otp_pass = getRandonOTP(4);
        $data['mobile'] = $sign_user;
        $data['otp'] = $otp_pass;
        $data['email'] = '';
              $message = $otp_pass. "  is the OTP to login into your  HMS account";
              if(httpPost($sign_user,$message)){
                    $response['success'] = 'OTP verification is required when account will be created using mobile number';
                    $_SESSION['mobile'] = $sign_user;
                    $_SESSION['pass'] = md5($sign_pass);
                    $_SESSION['otp'] = $otp_pass;
                    $response['user_id'] = 0;  
                    $response['code'] ='1';
                    $response['error']= '';
              }else{
                $response['error']='SMS GATWAY NOT WORKING';
              }
         
      } 
  }
  echo json_encode($response);
exit;