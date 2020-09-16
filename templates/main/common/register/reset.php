<?php
/**
 * Script called (Ajax) on reset password
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
 $response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");



if(isset($_GET['token']) && isset($_GET['id']) && is_numeric($_GET['id'])){
    $result_token = $db->query("SELECT * FROM pm_user WHERE token = ".$db->quote(htmlentities($_GET['token'], ENT_COMPAT, "UTF-8"))." AND id = ".$_GET['id']." AND checked = 1");
    if($result_token !== false && $db->last_row_count() > 0){
        $row = $result_token->fetch();
        $new_pass = genPass(6);
        $mailContent = "
        <p><br>You requested a new password<br>
        Bellow, your new connection informations<br>
        Username: ".$row['login']."<br>
        Password: <b>".$new_pass."</b><br>
        You can modify this random password in the settings via the manager.</p>";
        if(sendMail($row['email'], $row['lastname']." ".$row['firstname'], "Your new password", $mailContent) !== false){
            $db->query("UPDATE pm_user SET token = '', pass = '".md5($new_pass)."' WHERE id = ".$row['id']);
            header("Location: ".DOCBASE.LANG_ALIAS);
            exit();
        }
    }else{
        header("Location: ".DOCBASE.LANG_ALIAS);
    }
}elseif(isset($_POST['email']) && $_POST['email']!=""){
    $email = htmlentities($_POST['email'], ENT_COMPAT, "UTF-8");
    $result_user = $db->query("SELECT * FROM pm_user WHERE email = ".$db->quote($email)." AND checked = 1");
    if($result_user !== false && $db->last_row_count() > 0){
        $row = $result_user->fetch();
        $token = md5(uniqid($email, true));
         $new_pass = genPass(6);
         $mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
            			<p style="margin: 0 0 10px;">
                        Click on the link below to login with New Password: <b>'.$new_pass.'</b></p>
            			<p style="margin: 0 0 10px;">Kindly <a href="'.getUrl(true).DOCBASE.'login" style="color: #00767b;text-align: center;font-weight: bold;display: inline-block;">log-in</a> to continue </p>
            	     </td></tr>';
        if(sendMail($email, $row['firstname']." ".$row['lastname'], " New password request ", $mailContent) !== false){
            //$db->query("UPDATE pm_user SET token = '".$token."' WHERE id = ".$row['id']);
            $db->query("UPDATE pm_user SET token = '', pass = '".md5($new_pass)."' WHERE id = ".$row['id']);
            $response['success'] = "New password has been sent to your e-mail.";
        }
    }else{
        
        $response['error'] = $texts['NOT_REGISTERED_EMAIL'];
    }
}else{
      $email = htmlentities($_POST['email'], ENT_COMPAT, "UTF-8");
    if($email == '') $response['notices']['user'] = 'Enter valid email';
      
      $response['error'] = 'The email field is required';
}
echo json_encode($response);
