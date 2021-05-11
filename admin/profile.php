<?php
/**
 * This form serves you to modify the basic configuration of your installation
 */
define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/setenv.php');
        
$config_file = '../common/config.php';
$htaccess_file = '../.htaccess';
$field_notice = array();
$config_tmp = array();
$db = false;
$action = '';

require_once('../common/define.php');

if(!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit();
}elseif($_SESSION['admin']['type'] == 'registered'){
    unset($_SESSION['admin']);
    $_SESSION['msg_error'][] = 'Access denied.<br/>';
    header('Location: login.php');
    exit();
}
if(isset($_POST['edit_profile'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    if($password !="" && $password2 !=""){
        if($password == $password2){
            $data = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => $password,
                'id' => $_SESSION['admin']['id']
            ];
            $sql = "UPDATE pm_user SET firstname=:firstname, lastname=:lastname, password=:password WHERE id=:id";
            $stmt= $db->prepare($sql);
            $stmt->execute($data);
            $_SESSION['msg_success'][] = "Profile updated successfully";
        }else{
            $_SESSION['msg_error'][] = "Password mismatch";
        }
    }else{
        $data = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'id' => $_SESSION['admin']['id']
            ];
            $sql = "UPDATE pm_user SET firstname=:firstname, lastname=:lastname WHERE id=:id";
            $stmt= $db->prepare($sql);
            $stmt->execute($data);
            $_SESSION['msg_success'][] = "Profile updated successfully";
    }
}
$email = $_SESSION['admin']['email'];
$user = $_SESSION['admin']['login'];
$password = '';
$user_sql = $db->query("SELECT * FROM pm_user WHERE `id`=".$_SESSION['admin']['id']);
$user_data=$user_sql->fetch();
define('TITLE_ELEMENT', $texts['EDIT'].' Profile');
if($action != '' && defined('DEMO') && DEMO == 1){
    $action = '';
    $_SESSION['msg_error'][] = 'This action is disabled in the demo mode';
} 
require_once('includes/fn_module.php');
$csrf_token = get_token('settings'); ?>
<!DOCTYPE html>
<head>
    <?php include('includes/inc_header_common.php'); ?>
</head>
<body>
    <div id="overlay"><div id="loading"></div></div>
    <div id="wrapper">
        <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_top.php'); ?>
        <form id="form" class="form-horizontal" role="form" action="profile.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div id="page-wrapper">
                <div class="page-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-6 col-md-6 col-sm-8 clearfix">
                                <h1 class="pull-left"><i class="fas fa-fw fa-edit"></i> <?php echo TITLE_ELEMENT; ?></h1>
                            </div>
                            <div class="col-xs-6 col-md-6 col-sm-4 clearfix pb15 text-right">
                                <button type="submit" name="edit_profile" class="btn btn-success mt15">
                                    <i class="fas fa-fw fa-save"></i> <?php echo $texts['SAVE']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="alert-container">
                        <div class="alert alert-success alert-dismissable"></div>
                        <div class="alert alert-warning alert-dismissable"></div>
                        <div class="alert alert-danger alert-dismissable"></div>
                    </div>
                    <div class="panel panel-default">
                                                                <div class="panel-body">
                                    <div class="tab-content">
                                <div class="row mb10">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="col-md-3 control-label">
                                                    <?php echo $texts['FIRSTNAME']; ?> <span class="red">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="<?php echo $user_data['firstname']; ?>" name="firstname">
                                                    <div class="field-notice" rel="firstname"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb10">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="col-md-3 control-label">
                                                    <?php echo $texts['LASTNAME']; ?> <span class="red">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="<?php echo $user_data['lastname']; ?>" name="lastname">
                                                    <div class="field-notice" rel="lastname"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  <div class="row mb10">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="col-md-3 control-label">
                                                    <?php echo $texts['USER']; ?> <span class="red">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="<?php echo $user; ?>" name="user" readonly>
                                                    <div class="field-notice" rel="user"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb10">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="col-md-3 control-label">
                                                    <?php echo $texts['PASSWORD']; ?>
                                                </label>
                                                <div class="col-md-4">
                                                    <input class="form-control" type="password" value="<?php echo $password; ?>" name="password" placeholder="<?php echo $texts['PASSWORD_NOTICE']; ?>">
                                                    <div class="field-notice" rel="password"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input class="form-control" type="password" value="" name="password2" placeholder="<?php echo $texts['PASSWORD_CONFIRM']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb10">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <label class="col-md-3 control-label">
                                                    <?php echo $texts['EMAIL']; ?> <span class="red">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="<?php echo $email; ?>" name="email" readonly>
                                                    <div class="field-notice" rel="email"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    </div>
                </div>

                </div>
            </div>
        </form>
    </div>
</body>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
        


