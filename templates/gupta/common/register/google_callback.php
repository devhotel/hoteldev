<?php
/**
 * Script called (Ajax) on login
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
    $response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "code" => "","redirect"=>"");
    $sign_user = htmlentities($_POST['sign_user'], ENT_COMPAT, 'UTF-8');
    
  if($sign_user != ''){
        $result_exists = $db->query('SELECT * FROM pm_user WHERE  (email = '.$db->quote($sign_user).' OR mobile = '.$db->quote($sign_user).')');
        if($result_exists !== false && $db->last_row_count() > 0){
        	$row = $result_exists->fetch();
        	$response['success'] = 'success';
            $response['user_id'] = $row['id']; 
            $response['code'] ='1';
            $response['error']='';
            $response['redirect'] = DOCBASE;
        	$_SESSION['user']['id'] = $row['id'];
	        $_SESSION['user']['login'] = ($row['login']!=""?$row['login']:'Traveler');
	        $_SESSION['user']['email'] = $row['email'];
	        $_SESSION['user']['name'] = ($row['firstname']!=""?$row['firstname']:'Traveler');
	        $_SESSION['user']['type'] = $row['type'];
     }else{
     	$sign_pass = 'dsad!@3sa#';
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
        $data['email'] = $sign_user;
        $data['mobile'] = '';
        $data['otp'] = '';
        $result_user = db_prepareInsert($db, 'pm_user', $data);
        if($result_user->execute() !== false){
                $nuid=$db->lastInsertId();
                /*$_SESSION['user']['id'] = $nuid;
                $_SESSION['user']['login'] = 'Traveler';
                $_SESSION['user']['email'] = $sign_user;
                $_SESSION['user']['name'] = 'Traveler';
                $_SESSION['user']['type'] = 'registered';*/
                $mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
                    <p style="margin: 20px 0 10px;">Your account has been created with email address '.$email.' </p>
                    <p style="margin: 0 0 10px;">We hope to host you in the future.</p>
                    <p style="margin: 0 0 10px;">Kindly <a href="'.getUrl(true).DOCBASE.'login" style="color: #00767b;text-align: center;font-weight: bold;display: inline-block;">log-in</a> to continue</p>
                 </td></tr>';
                $email = $sign_user;
                $from_email ='welcome@guptahotels.com';
                $from_name = 'Gupta Hotels';
                if(sendMail($email, $name, 'Welcome to '.SITE_TITLE , $mailContent,'','',$from_email,$from_name) !== false){
                    $response['success'] = 'success';
                    $response['user_id'] = $nuid; 
                    $response['code'] ='1';
                    $response['error']='';
                }
                $_SESSION['user']['id'] = $nuid;
		        $_SESSION['user']['login'] = ($data['login']!=""?$data['login']:'Traveler');
		        $_SESSION['user']['email'] = $data['email'];
		        $_SESSION['user']['name'] = ($data['firstname']!=""?$data['firstname']:'Traveler');
		        $_SESSION['user']['type'] = 'registered';
		        $response['redirect'] = DOCBASE;
         }
     }
  }

 echo json_encode($response);
exit;
?>