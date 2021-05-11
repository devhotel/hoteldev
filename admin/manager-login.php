<?php
define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/define.php');
define('TITLE_ELEMENT', $texts['HOTEL'].' - '.$texts['LOGIN']);

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if($action == 'logout' && isset($_SESSION['admin'])) unset($_SESSION['admin']);

if(isset($_SESSION['admin'])){
    if($_SESSION['admin']['type'] != 'registered'){
        header('Location: index.php');
        exit();
    }else
        unset($_SESSION['admin']);
}

if($db !== false && isset($_POST['login'])){
    $user = htmlentities($_POST['user'], ENT_COMPAT, 'UTF-8');
    $password = $_POST['password'];
    
    if(check_token('/'.ADMIN_FOLDER.'/manager-login.php', 'login', 'post')){
        
        $result_user = $db->query('SELECT * FROM pm_user WHERE login = '.$db->quote($user).' AND pass = '.$db->quote(md5($password)).' AND type= "manager"  AND checked = 1');
        if($result_user !== false && $db->last_row_count() > 0){
            $row = $result_user->fetch();
            $_SESSION['admin']['id'] = $row['id'];
            $_SESSION['admin']['login'] = $user;
            $_SESSION['admin']['email'] = $row['email'];
            $_SESSION['admin']['name'] = $row['firstname'];
            $_SESSION['admin']['type'] = $row['type'];
            header('Location: index.php');
            exit();
        }else
            $_SESSION['msg_error'][] = $texts['LOGIN_FAILED'];
    }else
        $_SESSION['msg_error'][] = $texts['BAD_TOKEN2'];
}

if($db !== false && isset($_POST['reset'])){
    
    if(defined('DEMO') && DEMO == 1)
        $_SESSION['msg_error'][] = 'This action is disabled in the demo mode';
    else{
        $email = htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');

        if(check_token('/'.ADMIN_FOLDER.'/manager-login.php', 'login', 'post')){

            $result_user = $db->query('SELECT * FROM pm_user WHERE email = '.$db->quote($email).' AND checked = 1');
            if($result_user !== false && $db->last_row_count() > 0){
                $row = $result_user->fetch();
                $url = getUrl();
                $new_pass = genPass(6);
                $mailContent = '
                <p>Hi,<br>You requested a new password from <a href=\"'.$url.'\" target=\"_blank\">'.$url.'</a><br>
                Bellow, your new connection informations<br>
                Username: '.$row['login'].'<br>
                Password: <b>'.$new_pass.'</b><br>
                You can modify this random password in the settings via the manager.</p>';
                if(sendMail($email, @$row['name'], 'Your new password', $mailContent) !== false)
                    $db->query('UPDATE pm_user SET pass = '.$db->quote(md5($new_pass)).' WHERE id = '.$row['id']);
                    $_SESSION['msg_success'][] = 'A new password has been sent to your e-mail.';
            }else{
                $_SESSION['msg_error'][] = 'Incorrect email';
            }
            
        }else
            $_SESSION['msg_error'][] = 'Bad token! Thank you for re-trying by clicking on "New password".';
    }
}

$csrf_token = get_token('login'); ?>
<!DOCTYPE html>
<head>
    <?php include('includes/inc_header_common.php'); ?>
</head>
<body class="body_hotel_login_new">
    <div class="loginwrapper">
    <div class="loginleft">
        <div class="loginleft_layer"></div>
        <div class="logininfo">
                <a href="<?php echo DOCBASE; ?>"><img src="<?php echo DOCBASE.ADMIN_FOLDER; ?>/images/logo.png" alt="" /></a>
                <h1>Welcome to <span><?php echo SITE_TITLE;?></span></h1>
                <!-- <p>Logging is as Property Manager / Staff</p> -->
            </div>
    </div>
    <div class="loginright">
        <div class="loginright_layer"></div>
        <div class="logininfo">
        <?php
        if($action == 'reset'){ ?>
        <form id="form" class="form-horizontal" role="form" action="manager-login.php?action=reset" method="post">
            <?php }
            else {?>
            <form id="form" class="form-horizontal" role="form" action="manager-login.php" method="post">
            <?php }?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
           
                 
                 <div class="alert-container">
                        <div class="alert alert-success alert-dismissable"></div>
                        <div class="alert alert-warning alert-dismissable"></div>
                        <div class="alert alert-danger alert-dismissable"></div>
                </div>

                
                         <?php if($action == 'reset'){ ?>
                        <p>Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.</p>
                       
                        <span class="labelbox">
                            <label>E-mail</label>
                            <input  type="text" value="" placeholder="Enter Email" name="email">
                        </span>
                       
                        <span class="forgot">
                            <a href="login.php"><i class="fas fa-fw fa-power-off"></i>Login</a>
                        </span>
                        <span class="submit">
                            <button  type="submit" value="" name="reset"><i class="fas fa-fw fa-sync"></i> New password</button>
                        </span>
                        
                        <?php
                    }else{ ?>
                        
                       <span class="labelbox">
                         <label> <?php echo $texts['USERNAME']; ?></label>
                                <input  type="text" value="" id="user" placeholder="Enter Email or Mobile Number" name="user">
                        </span>
                        
                        <span class="labelbox">
                        <label>  <?php echo $texts['PASSWORD']; ?></label>
                                <input  type="password" id="pass" value="" placeholder="Enter your Password" name="password">
                                <a href="javascript:void(0);" class="eyeicon" id="showpass"><img src="images/eye-icon.png" alt="" /></a>
                                <a href="javascript:void(0);" class="eyeicon" id="hidepass" style="display:none;"><img src="images/eye-icon-cross.png" alt="" /></a>
                        </span>
                       
                        <span class="forgot">
                                <a href="login.php?action=reset">Forgot Password&nbsp;?</a>
                        </span>
                        <span class="submit">
                                <button  type="submit" value="" name="login"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOGIN']; ?></button>
                        </span>
                       <p>By Logging into the account you are agreeing with our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a></p>
                        <?php
                    } ?>
                    
        </form>
          </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
        $('#showpass').click(function() {
           $('#pass').prop('type', 'text'); 
           $('#showpass').hide();
           $('#hidepass').show();
        });
        $('#hidepass').click(function() {
           $('#pass').prop('type', 'password');
           $('#hidepass').hide();
           $('#showpass').show();
        });  
    });
</script>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
