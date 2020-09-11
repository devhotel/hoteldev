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
<body class="white body_hotel_login">
    <div class="container hotel_login_wrapper">
        <?php
        if($action == 'reset'){ ?>
        <form id="form" class="form-horizontal" role="form" action="manager-login.php?action=reset" method="post">
            <?php }
            else {?>
            <form id="form" class="form-horizontal" role="form" action="manager-login.php" method="post">
            <?php }?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="col-sm-3 col-md-2"></div>
            <div class="col-sm-6 col-md-8" id="loginWrapper">
                <div id="logo">
                    <a href="<?php echo DOCBASE.trim(LANG_ALIAS, "/"); ?>"><img src="images/logo-admin.png"></a>
                </div>
                 <div class="alert-container">
                        <div class="alert alert-success alert-dismissable"></div>
                        <div class="alert alert-warning alert-dismissable"></div>
                        <div class="alert alert-danger alert-dismissable"></div>
                    </div>
                <div class="row" id="login">
                     <div class="col-sm-4 col-md-5">
                        <div class="hmd_login">
                           <img src="images/secure.png">
                        </div> 
                     </div>
                     <div class="col-sm-8 col-md-7">
                       <div>
                        <?php
                        if($action == 'reset'){ ?>
                            <p>Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.</p>
                            <div class="row">
                                <label class="col-sm-12">
                                    E-mail
                                </label>
                            </div>
                            <div class="row mb10">
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" value="" name="email">
                                </div>
                            </div>
                            <div class="row mb10">
                                <div class="col-xs-3 text-left">
                                    <a href="manager-login.php"><i class="fas fa-fw fa-power-off"></i> Login</a>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <button class="btn btn-default" type="submit" value="" name="reset"><i class="fas fa-fw fa-sync"></i> New password</button>
                                </div>
                            </div>
                            <?php
                        }else{ ?>
                            <div class="row">
                                <label class="col-sm-12">
                                    <?php echo $texts['USERNAME']; ?>
                                </label>
                            </div>
                            <div class="row mb10">
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" value="" name="user">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-12">
                                    <?php echo $texts['PASSWORD']; ?>
                                </label>
                            </div>
                            <div class="row mb10">
                                <div class="col-sm-12">
                                    <input class="form-control" type="password" value="" name="password">
                                </div>
                            </div>
                            <div class="row mb10">
                                <div class="col-sm-7 text-left">
                                    <a href="manager-login.php?action=reset">Forgot Password&nbsp;?</a>
                                </div>
                                <div class="col-sm-5 text-right">
                                    <button class="btn btn-default" type="submit" value="" name="login"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOGIN']; ?></button>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>
                     </div>
                </div>
                

            </div>
            <div class="col-sm-3 col-md-2"></div>
        </form>
    </div>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
