<?php
define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/setenv.php');
$config_file = '../common/config.php';
$htaccess_file = '../.htaccess';
$db = false;
require_once('../common/define.php');
if($_POST){
    $db->query("DELETE FROM `pm_gallery` WHERE `id`='" . $_POST['id'] . "'");
    $uploadPath = SYSBASE . 'galleUploads/';
    if (file_exists($uploadPath . $_POST['image'])) :
        unlink($uploadPath . $_POST['image']);
    endif;
    print ' <div class="alert alert-success alert-dismissable" style="display: block;">
                <span style="color:green;">Image Deleted Successfully !!!</span>
            </div>';
}