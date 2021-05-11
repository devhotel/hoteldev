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
$db = false;
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
$type =  $_GET['type'];
$msg = '';
if (isset($_POST['submit_image'])) :
    $files = $_FILES;
    if ($_POST['imageId'] == '') :
        $image =  $files['image']['name']; 
        $imageArr = explode('.',$image);
        $expensions = array("jpeg","jpg","png");
        if(in_array($imageArr[1], $expensions)=== false) :
            $msg = '<div class="alert alert-danger alert-dismissable" style="display: block;">
                        <span style="color:red;">extension not allowed, please choose a JPEG or PNG file !!!</span>
                    </div>';
        else :
            $newImageName = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789'), 0, 20).'.'.$imageArr[1];
            $uploadPath = SYSBASE . 'galleryUploads/';
            if (!file_exists($uploadPath)) :
                mkdir($uploadPath, 0777, true);
            endif;
            $uploadPath .= $newImageName;
            $isUploaded = move_uploaded_file($files["image"]["tmp_name"], $uploadPath);
            $db->query("INSERT INTO `pm_gallery`(`image`) VALUES ('" . $newImageName . "')");
            if($isUploaded) :
                $msg = '<div class="alert alert-success alert-dismissable" style="display: block;">
                            <span style="color:green;">Image uploaded Successfully !!!</span>
                        </div>';
            else :
                $msg = '<div class="alert alert-danger alert-dismissable" style="display: block;">
                            <span style="color:red;">Something went wrong !!!</span>
                        </div>';
            endif;
        endif;
    else :
        $allow = true;
        if (!empty($files) && $files['image']['name'] != '') {
            $image = $files['image']['name'];
            $imageArr = explode('.',$image);
            $expensions = array("jpeg","jpg","png");
            if(in_array($imageArr[1], $expensions)=== false) :
                $msg = '<div class="alert alert-danger alert-dismissable" style="display: block;">
                            <span style="color:red;">extension not allowed, please choose a JPEG or PNG file !!!</span>
                        </div>';
            $allow = false;
            else :
                $newImageName = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789'), 0, 20).'.'.$imageArr[1];
                $uploadPath = SYSBASE . 'galleryUploads/';
                if (!file_exists($uploadPath)) :
                    mkdir($uploadPath, 0777, true);
                endif;
                if (file_exists($uploadPath . $_POST['oldImage'])) :
                    unlink($uploadPath . $_POST['oldImage']);
                endif;
                $uploadPath .= $newImageName;
                $isUploaded = move_uploaded_file($files["image"]["tmp_name"], $uploadPath);
                if(!$isUploaded) :
                    $msg = '<div class="alert alert-danger alert-dismissable" style="display: block;">
                                <span style="color:red;">Something went wrong !!!</span>
                            </div>';
                    $allow = false;
                endif;
            endif;
        }else{
            $newImageName = $_POST['oldImage'];
        }
        if($allow) :
            $db->query("UPDATE `pm_gallery` SET `image`='" . $newImageName . "',`status`='" . $_POST['status'] . "' WHERE id = '" . $_POST['imageId'] . "'");
            $msg = '<div class="alert alert-success alert-dismissable" style="display: block;">
                        <span style="color:green;">Image Details Changed Successfully !!!</span>
                    </div>';
        endif;
    endif;
endif;
if ($type == 'list') {
    $list = $db->query('SELECT * FROM pm_gallery ORDER BY id DESC ')->fetchAll(PDO::FETCH_ASSOC);
}
if ($type == 'edit') {
    $imageId = $_GET['id'];
    $detail = $db->query("SELECT * FROM pm_gallery where id = '" . $imageId . "'  ORDER BY id DESC ")->fetch(PDO::FETCH_ASSOC);
}
define('TITLE_ELEMENT', 'Gallery');
require_once('includes/fn_module.php');
$csrf_token = get_token('gallery'); ?>
<!DOCTYPE html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php include('includes/inc_header_common.php'); ?>
</head>
<body>
    <div id="overlay">
        <div id="loading"></div>
    </div>
    <div id="wrapper">
        <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_top.php'); ?>
        <form id="form" class="form-horizontal" role="form" action="gallery.php?type=list" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div id="page-wrapper">
                <div class="page-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-6 col-md-6 col-sm-8 clearfix">
                                <h1 class="pull-left">Gallery</h1>
                            </div>
                            <div class="col-xs-6 col-md-6 col-sm-4 clearfix pb15 text-right">
                                <?php if ($type == 'list') { ?>
                                    <a href="<?= DOCBASE . ADMIN_FOLDER . "/gallery.php?type=add" ?>">
                                        <button type="button" class="btn btn-success mt15">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?= DOCBASE . ADMIN_FOLDER . "/gallery.php?type=list" ?>">
                                        <button type="button" class="btn btn-danger mt15">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </button>
                                    </a>
                                    <button type="submit" name="submit_image" class="btn btn-success mt15">
                                        <i class="fas fa-fw fa-save"></i> <?php echo $texts['SAVE']; ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="alert-container"><?=$msg?></div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php if ($type == 'list') { ?>
                                <div class="tab-content">
                                    <table class="table table-bordered dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($list)) :
                                                foreach ($list as $key => $val) :
                                            ?>
                                                    <tr class="imageRow<?=$val['id']?>">
                                                        <td><?= $key + 1 ?></td>
                                                        <td><img src="<?=base_url('/galleryUploads/' . $val['image'])?>" style="height: 150px; width: auto;"></td>
                                                        <td><?=($val['status'] == 1) ? 'Active' : 'In-Active'?></td>
                                                        <td>
                                                            <a href="<?= DOCBASE . ADMIN_FOLDER . "/gallery.php?type=edit&id=" . $val['id'] ?>" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a> | 
                                                            <a href="javascript:void(0);" class="deleteImage" data-id="<?=$val['id']?>" data-image="<?=$val['image']?>" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;
                                            else :  ?>
                                                <tr style="text-align: center;">
                                                    <td colspan="4">No Image Found !!!</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                            <?php if ($type == 'add' || $type == 'edit') { ?>
                                <div class="tab-content">
                                    <?php
                                    if ($_SESSION['admin']['type'] == 'administrator') { ?>
                                        <div id="general" class="tab-pane fade in active">
                                            <input type="hidden" name="imageId" value="<?=($type == 'edit') ? $detail['id'] : ''?>">
                                            <?php if ($type == 'edit') { ?>
                                                <div class="row mb10">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <label class="col-md-3 control-label"></label>
                                                            <div class="col-md-9">
                                                                <img src="<?=base_url('/galleryUploads/' . $detail['image'])?>" style="height: 200px; width: auto;">
                                                                <input type="hidden" name="oldImage" value="<?=($type == 'edit') ? $detail['image'] : ''?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="row mb10">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <label class="col-md-3 control-label">Image <?=($type == 'add') ? '*' : ''?></label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="image" <?=($type == 'add') ? 'required' : ''?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($type == 'edit') { ?>
                                                <div class="row mb10">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <label class="col-md-3 control-label">Status</label>
                                                            <div class="col-md-9">
                                                                <select class="form-control" name="status">
                                                                    <option value="1" <?=($detail['status'] == 1) ? 'selected' : ''?>>Active</option>
                                                                    <option value="0" <?=($detail['status'] == 0) ? 'selected' : ''?>>In-Active</option>
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
    <script>
        $(window).bind("load", function () {
            window.setTimeout(function () {
                $(".alert-container")
                    .fadeTo(500, 0)
                    .slideUp(500, function () {
                        $(this).empty();
                    });
            }, 2000);
        });
        $(document).on("click", ".deleteImage", function () {
            if(confirm("Are you sure want to delete image?")) {
                let id      = $(this).attr("data-id");
                let image   = $(this).attr("data-image");
                $.ajax({
                    type: "POST",
                    url: 'gallery-ajax.php',
                    data: {
                        id: id,
                        image: image,
                    },
                    success: function (resultData) {
                    $('.imageRow' + id).remove();
                    $('.alert-container').html(resultData);
                    },
                });
            }
        });
    </script>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>