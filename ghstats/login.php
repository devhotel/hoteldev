<?php
define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/define.php');
define('TITLE_ELEMENT', 'Hotel Status Stats  - '.$texts['LOGIN']);

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if($action == 'logout' && isset($_SESSION['ghs'])) unset($_SESSION['ghs']);

if(isset($_SESSION['ghs'])){
        header('Location: index.php');
  }

if($db !== false && isset($_POST['login'])){
    $user = htmlentities($_POST['user'], ENT_COMPAT, 'UTF-8');
    $password = $_POST['password'];
    
    if(check_token('/ghstats/login.php', 'login', 'post')){
        
        $result_user = $db->query('SELECT * FROM pm_stats_user WHERE login = '.$db->quote($user).' AND pass = '.$db->quote(md5($password)).'  AND checked = 1');
        if($result_user !== false && $db->last_row_count() > 0){
            $row = $result_user->fetch();
            $_SESSION['ghs']['id'] = $row['id'];
            $_SESSION['ghs']['login'] = $user;
            $_SESSION['ghs']['email'] = $row['email'];
            $_SESSION['ghs']['name'] = $row['firstname'];
            $_SESSION['ghs']['type'] = $row['type'];
            header('Location: index.php');
            exit();
        }else
            $_SESSION['msg_error'][] = $texts['LOGIN_FAILED'];
    }else
        $_SESSION['msg_error'][] = $texts['BAD_TOKEN2'];
}

$csrf_token = get_token('login'); ?>
<!DOCTYPE html>
<head>
    <?php include('includes/inc_header_common.php'); ?>
</head>
<body class="white">
    <div class="container admin_login_wrapper">
        <?php
        if($action == 'reset'){ ?>
        <form id="form" class="form-horizontal" role="form" action="login.php?action=reset" method="post">
            <?php }
            else {?>
            <form id="form" class="form-horizontal" role="form" action="login.php" method="post">
            <?php }?>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="col-sm-3 col-md-4"></div>
            <div class="col-sm-6 col-md-4" id="loginWrapper">
                <div id="logo">
                    <a href="<?php echo DOCBASE.trim(LANG_ALIAS, "/"); ?>"><img src="images/logo-admin.png"></a>
                </div>
                <div id="login">
                    <div class="alert-container">
                        <div class="alert alert-success alert-dismissable"></div>
                        <div class="alert alert-warning alert-dismissable"></div>
                        <div class="alert alert-danger alert-dismissable"></div>
                    </div>
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
                                <a href="login.php"><i class="fas fa-fw fa-power-off"></i> Login</a>
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
                               <!-- <a href="login.php?action=reset">Forgot Password&nbsp;?</a>-->
                            </div>
                            <div class="col-sm-5 text-right">
                                <button class="btn btn-default" type="submit" value="" name="login"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOGIN']; ?></button>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
            <div class="col-sm-3 col-md-4"></div>
        </form>
    </div>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
