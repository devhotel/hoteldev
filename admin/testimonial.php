<?php

/**
 * This form serves you to modify the basic configuration of your installation
 */
session_start();
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
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
} elseif ($_SESSION['admin']['type'] == 'registered') {
    unset($_SESSION['admin']);
    $_SESSION['msg_error'][] = 'Access denied.<br/>';
    header('Location: login.php');
    exit();
}
$email = $_SESSION['admin']['email'];
$user = $_SESSION['admin']['login'];
$password = '';
$type =  $_GET['type'];
$testiId = '';
if (isset($_POST['submit_testimonial'])) {
    if ($_POST['testi_id'] == '') {
        $db->query("INSERT INTO `pm_testimonial`(`name`, `comment`) VALUES ('" . $_POST['testi_name'] . "', '" . $_POST['testi_comment'] . "')");
    } else {
        $db->query("UPDATE `pm_testimonial` SET `name`='" . $_POST['testi_name'] . "',`comment`='" . $_POST['testi_comment'] . "',`status`='" . $_POST['testi_status'] . "',`updated_at`='" . date('Y-m-d H:i:s') . "' WHERE id = '" . $_POST['testi_id'] . "'");
    }
}
if ($type == 'list') {
    $list = $db->query('SELECT * FROM pm_testimonial ORDER BY id DESC ')->fetchAll(PDO::FETCH_ASSOC);
}
if ($type == 'edit') {
    $testiId = $_GET['id'];
    $detail = $db->query("SELECT * FROM pm_testimonial where id = '" . $testiId . "'  ORDER BY id DESC ")->fetch(PDO::FETCH_ASSOC);
}
define('TITLE_ELEMENT', 'Testimonial');
require_once('includes/fn_module.php');
$csrf_token = get_token('testimonial'); ?>
<!DOCTYPE html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <?php include('includes/inc_header_common.php'); ?>
    <script>
        $(function() {
            $('#db_name').bind('blur keyup', function() {
                $('#db_user').val($(this).val());
            });
            <?php foreach ($field_notice as $field => $notice) echo '$(\'.field-notice[rel="' . $field . '"]\').html(\'' . addslashes($notice) . '\').fadeIn(\'slow\').parent().addClass(\'alert alert-danger\');' . "\n"; ?>
        });
    </script>
</head>

<body>
    <div id="overlay">
        <div id="loading"></div>
    </div>
    <div id="wrapper">
        <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_top.php'); ?>
        <form id="form" class="form-horizontal" role="form" action="testimonial.php?type=list" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div id="page-wrapper">
                <div class="page-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-6 col-md-6 col-sm-8 clearfix">
                                <h1 class="pull-left">Testimonial</h1>
                            </div>
                            <div class="col-xs-6 col-md-6 col-sm-4 clearfix pb15 text-right">
                                <?php if ($type == 'list') { ?>
                                    <a href="<?= DOCBASE . ADMIN_FOLDER . "/testimonial.php?type=add" ?>">
                                        <button type="button" class="btn btn-success mt15">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?= DOCBASE . ADMIN_FOLDER . "/testimonial.php?type=list" ?>">
                                        <button type="button" class="btn btn-danger mt15">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </button>
                                    </a>
                                    <button type="submit" name="submit_testimonial" class="btn btn-success mt15">
                                        <i class="fas fa-fw fa-save"></i> <?php echo $texts['SAVE']; ?>
                                    </button>
                                <?php } ?>
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
                            <?php if ($type == 'list') { ?>
                                <div class="tab-content">
                                    <table class="table table-bordered dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($list)) {
                                                $cnt = 1;
                                                foreach ($list as $val) {
                                            ?>
                                                    <tr>
                                                        <td><?= $cnt ?></td>
                                                        <td><?= $val['name'] ?></td>
                                                        <td><?= $val['comment'] ?></td>
                                                        <td><? if ($val['status'] == 1) {
                                                                echo 'Active';
                                                            } else {
                                                                echo 'In-Active';
                                                            } ?></td>
                                                        <td>
                                                            <a href="<?= DOCBASE . ADMIN_FOLDER . "/testimonial.php?type=edit&id=" . $val['id'] ?>" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php $cnt++; }
                                            } else { ?>
                                                <tr style="text-align: center;">
                                                    <td colspan="5">No Testimonial Found !!!</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                            <?php if ($type == 'add' || $type == 'edit') { ?>
                                <div class="tab-content">
                                    <?php
                                    if ($_SESSION['admin']['type'] == 'administrator') { ?>
                                        <div id="general" class="tab-pane fade in active">
                                            <div class="row mb10">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <label class="col-md-3 control-label">Name</label>
                                                        <div class="col-md-8">
                                                            <input type="hidden" name="testi_id" value="<?php if ($type == 'edit') {
                                                                                                            echo $detail['id'];
                                                                                                        } ?>">
                                                            <input class="form-control" type="text" value="<?php if ($type == 'edit') {
                                                                                                                echo $detail['name'];
                                                                                                            } ?>" name="testi_name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb10">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <label class="col-md-3 control-label">Coment</label>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" rows="15" name="testi_comment"><?php if ($type == 'edit') {
                                                                                                                                echo $detail['comment'];
                                                                                                                            } ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($type == 'edit') { ?>
                                                <div class="row mb10">
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <label class="col-md-3 control-label">Status</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="testi_status">
                                                                    <option value="1" <?php if ($detail['status'] == 1) {
                                                                                            echo 'selected';
                                                                                        } ?>>Active</option>
                                                                    <option value="0" <?php if ($detail['status'] == 0) {
                                                                                            echo 'selected';
                                                                                        } ?>>In-Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include(SYSBASE . ADMIN_FOLDER . '/modules/default/nav_script.php'); ?>
</body>

</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>