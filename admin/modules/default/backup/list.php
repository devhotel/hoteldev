<?php
/**
 * Template of the module listing
 */
debug_backtrace() || die('Direct access not permitted');
// Action to perform
$action = (isset($_GET['action'])) ? htmlentities($_GET['action'], ENT_QUOTES, 'UTF-8') : '';
if ($action != '' && defined('DEMO') && DEMO == 1) {
    $action = '';
    $_SESSION['msg_error'][] = 'This action is disabled in the demo mode';
}
// Item ID
$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
// current page
if (isset($_GET['offset']) && is_numeric($_GET['offset'])) $offset = $_GET['offset'];
elseif (isset($_SESSION['offset']) && isset($_SESSION['module_referer']) && $_SESSION['module_referer'] == MODULE) $offset = $_SESSION['offset'];
else $offset = 0;
// Items per page
if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $limit = $_GET['limit'];
    $offset = 0;
} elseif (isset($_SESSION['limit']) && isset($_SESSION['module_referer']) && $_SESSION['module_referer'] == MODULE) $limit = $_SESSION['limit'];
else $limit = 2000;
$_SESSION['limit'] = $limit;
$_SESSION['offset'] = $offset;
if (empty($permissions) || $permissions[0] == NULL) {
    //echo '<script> window.location.href = "'.DOCBASE.ADMIN_FOLDER.'/no-access"; </script>';
    header('Location: ' . DOCBASE . ADMIN_FOLDER . '/index.php');
    exit;
}
$id_hotel = 0;
if (isset($_REQUEST['id'])) {
    $id_hotel = @$_REQUEST['id'];
}
$id_destination = 0;
if (isset($_REQUEST['id_destination'])) {
    $id_destination = @$_REQUEST['id_destination'];
}
//if (is_numeric($_REQUEST['id_destination']) && $_REQUEST['id_destination'] > 0) {
// Inclusions DOCBASE.ADMIN_FOLDER
require_once(SYSBASE . ADMIN_FOLDER . '/includes/fn_list.php');

if ($db !== false) {
    // Initializations
    $cols = getCols();
    $filters = getFilters($db);
    if (is_null($cols)) $cols = array();
    if (is_null($filters)) $filters = array();
    $total = 0;
    $record = 0;
    $total_page = 0;
    $q_search = '';
    $result_lang = false;
    $total_lang = 1;
    $result = false;
    $referer = DIR . 'index.php?view=list';
    // Sort order
    if (isset($_GET['order'])) $order = htmlentities($_GET['order'], ENT_QUOTES, 'UTF-8');
    elseif (isset($_SESSION['order']) && $_SESSION['order'] != '' && isset($_SESSION['module_referer']) && $_SESSION['module_referer'] == MODULE) $order = $_SESSION['order'];
    else $order = getOrder();
    if (isset($_GET['sort'])) $sort = htmlentities($_GET['sort'], ENT_QUOTES, 'UTF-8');
    elseif (isset($_SESSION['sort']) && $_SESSION['sort'] != '' && isset($_SESSION['module_referer']) && $_SESSION['module_referer'] == MODULE) $sort = $_SESSION['sort'];
    else $sort = 'desc';
    if (strpos($order, ' ') !== false) {
        $sort = strtolower(substr($order, strpos($order, ' ') + 1));
        $order = substr($order, 0, strpos($order, ' '));
    }
    $sort_class = ($sort == 'asc') ? 'up' : 'down';
    if (MODULE == 'booking') {
        $order = 'id';
        $sort  = 'desc';
    }
    $_SESSION['order'] = $order;
    $_SESSION['sort'] = $sort;
    $rsort = ($sort == 'asc') ? 'desc' : 'asc';
    // Getting languages
    if (MULTILINGUAL) {
        $result_lang = $db->query('SELECT id, title FROM pm_lang WHERE id != ' . DEFAULT_LANG . ' AND checked = 1');
        if ($result_lang !== false)
            $total_lang = $db->last_row_count();
    }
    //resticted module array
    $rsmodule = array('hotel', 'room', 'booking', 'service', 'tax', 'payment');
    // Getting filters values
    if (isset($_SESSION['module_referer']) && $_SESSION['module_referer'] !== MODULE) {
        unset($_SESSION['filters']);
        unset($_SESSION['q_search']);
    }
    if (isset($_REQUEST['search'])) {
        foreach ($filters as $filter) {
            $fieldName = $filter->getName();
            $value = (isset($_REQUEST[$fieldName])) ? htmlentities($_REQUEST[$fieldName], ENT_QUOTES, 'UTF-8') : '';
            $filter->setValue($value);
        }
        $q_search = htmlentities($_REQUEST['q_search'], ENT_QUOTES, 'UTF-8');
        $_SESSION['filters'] = serialize($filters);
        $_SESSION['q_search'] = $q_search;
        $offset = 0;
        $_SESSION['offset'] = $offset;
    } else {
        if (isset($_SESSION['filters'])) $filters = unserialize($_SESSION['filters']);
        if (isset($_SESSION['q_search'])) $q_search = $_SESSION['q_search'];
    }
    // Getting items in the database
    $condition = '';
    if ($id_destination > 0) {
        $condition .= ' id_destination = ' . $id_destination . ' AND ';
    }
    if ($id_hotel > 0) {
        $condition .= 'id = ' . $id_hotel . ' AND ';
    }
    if (MULTILINGUAL) $condition .= ' lang = ' . DEFAULT_LANG;
    foreach ($filters as $filter) {
        $fieldName = $filter->getName();
        $fieldValue = $filter->getValue();
        if ($fieldValue != '') {
            if ($condition != '') $condition .= ' AND';
            $condition .= ' ' . $fieldName . ' = ' . $db->quote($fieldValue);
        }
    }
    if (in_array($_SESSION['admin']['type'], array('administrator', 'manager', 'editor')) && db_column_exists($db, 'pm_' . MODULE, 'users')) {
        if ($condition != '') $condition .= ' AND';
        $condition .= ' users REGEXP \'(^|,)' . $_SESSION['admin']['id'] . '(,|$)\'';
    }
    $query_search = db_getRequestSelect($db, 'pm_' . MODULE, getSearchFieldsList($cols), $q_search, $condition, $order . ' ' . $sort);
    $result_total = $db->query($query_search);
    if ($result_total !== false)
        $total = $db->last_row_count();
    if ($limit > 0) $query_search .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    $result = $db->query($query_search);
    if ($result !== false)
        $total_page = $db->last_row_count();
    if (empty($_SESSION['msg_error'])) {
        if (in_array('edit', $permissions) || in_array('all', $permissions)) {
            // Setting main item
            if ($action == 'define_main' && $id > 0 && check_token($referer, 'list', 'get'))
                define_main($db, 'pm_' . MODULE, $id, 1);
            if ($action == 'remove_main' && $id > 0 && check_token($referer, 'list', 'get'))
                define_main($db, 'pm_' . MODULE, $id, 0);
            // Items displayed in homepage
            if ($action == 'display_home' && $id > 0 && check_token($referer, 'list', 'get'))
                display_home($db, 'pm_' . MODULE, $id, 1);
            if ($action == 'remove_home' && $id > 0 && check_token($referer, 'list', 'get'))
                display_home($db, 'pm_' . MODULE, $id, 0);
            if ($action == 'display_home_multi' && isset($_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                display_home_multi($db, 'pm_' . MODULE, 1, $_REQUEST['multiple_item']);
            if ($action == 'remove_home_multi' && isset($_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                display_home_multi($db, 'pm_' . MODULE, 0, $_REQUEST['multiple_item']);
            // Item activation/deactivation
            if ($action == 'check' && $id > 0)
                check($db, 'pm_' . MODULE, $id, 1);
            if ($action == 'uncheck' && $id > 0)
                check($db, 'pm_' . MODULE, $id, 2);
            if ($action == 'archive' && $id > 0 && check_token($referer, 'list', 'get'))
                check($db, 'pm_' . MODULE, $id, 3);
            if ($action == 'check_multi' && isset($_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                check_multi($db, 'pm_' . MODULE, 1, $_REQUEST['multiple_item']);
            if ($action == 'uncheck_multi' && isset($_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                check_multi($db, 'pm_' . MODULE, 2, $_REQUEST['multiple_item']);
            if ($action == 'archive_multi' && isset($_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                check_multi($db, 'pm_' . MODULE, 3, $_REQUEST['multiple_item']);
        }
        if (in_array('delete', $permissions) || in_array('all', $permissions)) {
            // Item deletion
            if ($action == 'delete' && $id > 0 && check_token($referer, 'list', 'get'))
                delete_item($db, $id);
            if ($action == 'delete_multi' && isset($_REQUEST_REQUEST['multiple_item']) && check_token($referer, 'list', 'get'))
                delete_multi($db, $_REQUEST['multiple_item']);
        }
        if (in_array('all', $permissions)) {
            // Languages completion
            if (MULTILINGUAL && isset($_REQUEST['complete_lang']) && isset($_REQUEST['languages']) && check_token($referer, 'list', 'post')) {
                foreach ($_REQUEST['languages'] as $id_lang) {
                    complete_lang_module($db, 'pm_' . MODULE, $id_lang);
                    if (NB_FILES > 0) complete_lang_module($db, 'pm_' . MODULE . '_file', $id_lang, true);
                }
            }
        }
    }
}
$_SESSION['module_referer'] = MODULE;
$csrf_token = get_token('list'); ?>
<!DOCTYPE html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_header_list.php'); ?>
</head>
<body>
    <div id="wrapper">
        <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_top.php'); ?>
        <div id="page-wrapper">
            
            <div class="page-header">

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-md-12 clearfix">

                            <?php if (ICON == 'wallet') { ?>

                                <h1 class="pull-left"><span class="icon icon-<?php echo ICON; ?>-black"></span> <?php echo TITLE_ELEMENT; ?></h1>

                            <?php } else { ?>

                                <h1 class="pull-left"><i class="fas fa-fw fa-<?php echo ICON; ?>"></i> <?php echo TITLE_ELEMENT; ?></h1>

                            <?php } ?>



                            <div class="pull-left text-right">

                                &nbsp;&nbsp;

                                <?php

                                $btn = array('booking');

                                if ((in_array('add', $permissions) || in_array('all', $permissions)) && MODULE != 'booking') {

                                    if (!in_array(MODULE, $btn)) { ?>

                                        <a href="index.php?view=form&id=0" class="btn btn-primary mt15 mb15">

                                            <i class="fas fa-fw fa-plus-circle"></i> <?php echo $texts['NEW']; ?>

                                        </a>

                                <?php

                                    }
                                }

                                if (is_file('custom_nav.php')) include('custom_nav.php'); ?>

                            </div>

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

                <?php

                if ($db !== false) {
                    if (!in_array('no_access', $permissions)) { ?>
                        <div class="form_wrapper">
                            <form id="form" action="index.php?view=list" method="get">
                                <input type="hidden" name="view" value="list" />
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
                                <div class="panel panel-default">
                                    <div class="panel-heading form-inline clearfix">
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <label for="q_search"><?php echo $texts['SEARCH']; ?></label>
                                                        <input type="text" id="list_q_search" name="q_search" value="<?php echo $q_search; ?>" class="form-control input-sm" placeholder="<?php echo $texts['SEARCH']; ?>..." />
                                                    </div>
                                                    <?php displayFilters($filters, $id_destination, $id_hotel); ?>
                                                    <button class="btn btn-default btn-sm" type="submit" id="search" name="search">
                                                        <!-- <i class="fas fa-fw fa-search"></i> --> GO <?php //echo $texts['SEARCH']; 
                                                                                                        ?></button>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3 text-right">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-th-list"></i> <?php echo $texts['DISPLAY']; ?></div>
                                                <select class="select-url form-control input-sm">
                                                    <?php
                                                    echo ($limit != 20) ? '<option value="index.php?view=list&limit=20">20</option>' : '<option selected="selected">20</option>';
                                                    echo ($limit != 50) ? '<option value="index.php?view=list&limit=50">50</option>' : '<option selected="selected">50</option>';
                                                    echo ($limit != 100) ? '<option value="index.php?view=list&limit=100">100</option>' : '<option selected="selected">100</option>'; ?>
                                                </select>
                                            </div>
                                            <?php
                                            if ($limit > 0) {
                                                $nb_pages = ceil($total / $limit);
                                                if ($nb_pages > 1) { ?>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><?php echo $texts['PAGE']; ?></div>
                                                        <select class="select-url form-control input-sm">
                                                            <?php
                                                            for ($i = 1; $i <= $nb_pages; $i++) {
                                                                $offset2 = ($i - 1) * $limit;
                                                                if ($offset2 == $offset)
                                                                    echo '<option value="" selected="selected">' . $i . '</option>';
                                                                else
                                                                    echo '<option value="index.php?view=list&offset=' . $offset2 . '">' . $i . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                        </div> -->
                                        </div>
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="listing_base">

                                                <thead>

                                                    <tr class="nodrop nodrag">

                                                        <th>

                                                            <span class="selectspan"><input type="checkbox" class="selectall" />

                                                                <?php //echo $texts['SELECT_ALL']; 
                                                                ?>

                                                            </span>

                                                            <?php

                                                            if (RANKING) { ?>

                                                                <!--<a href="index.php?view=list&order=rank&sort=<?php echo ($order == 'rank') ? $rsort : 'asc'; ?>">

                                                                # <i class="fas fa-fw fa-sort<?php if ($order == 'rank') echo '-' . $sort_class; ?>"></i>

                                                            </a>-->

                                                            <?php

                                                            } ?>

                                                        </th>

                                                        <?php if (MODULE == 'booking') { ?>



                                                            <th>

                                                                <a href="index.php?view=list&order=id&sort=<?php echo ($order == 'id') ? $rsort : 'asc'; ?>">

                                                                    Booking ID <i class="fas fa-fw fa-sort<?php if ($order == 'id') echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>



                                                        <?php } else { ?>



                                                            <th>

                                                                <a href="index.php?view=list&order=id&sort=<?php echo ($order == 'id') ? $rsort : 'asc'; ?>">

                                                                    ID <i class="fas fa-fw fa-sort<?php if ($order == 'id') echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>



                                                        <?php } ?>

                                                        <?php



                                                        if (MODULE != 'hotel') {
                                                            if (NB_FILES > 0) echo '<th width="160">' . $texts['IMAGE'] . '</th>';
                                                        }

                                                        foreach ($cols as $col) { ?>

                                                            <th>

                                                                <a href="index.php?view=list&order=<?php echo $col->getName(); ?>&sort=<?php echo ($order == $col->getName()) ? $rsort : 'asc'; ?>">

                                                                    <?php

                                                                    if ($col->getLabel() == "Source") {

                                                                        echo "Booking Info";
                                                                    } else {

                                                                        echo $col->getLabel();
                                                                    }

                                                                    ?>

                                                                    <i class="fas fa-fw fa-sort<?php if ($order == $col->getName()) echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>

                                                            <?php

                                                        }

                                                        if (MODULE != 'hotel') {

                                                            if (count($cols) == 0) {

                                                                $type_module = 'file';

                                                                if (NB_FILES > 0) { ?>

                                                                    <th><?php echo $texts['FILE']; ?></th>

                                                                    <th><?php echo $texts['LABEL']; ?></th>

                                                            <?php

                                                                }
                                                            }
                                                        }

                                                        if (MODULE == 'booking') { ?>



                                                            <th>

                                                                <a href="">

                                                                    Payments

                                                                </a>

                                                            </th>



                                                            <th>

                                                                <a href="">

                                                                    Payment Status

                                                                </a>

                                                            </th>

                                                            <th>

                                                                <a href="">

                                                                    Checked IN/OUT

                                                                </a>

                                                            </th>

                                                        <?php }



                                                        if (DATES) { ?>

                                                            <!--  <th>

                                                            <a href="index.php?view=list&order=add_date&sort=<?php echo ($order == 'add_date') ? $rsort : 'asc'; ?>">

                                                                <?php echo $texts['ADDED_ON']; ?> <i class="fas fa-fw fa-sort<?php if ($order == 'add_date') echo '-' . $sort_class; ?>"></i>

                                                            </a>

                                                        </th> -->

                                                            <!-- <th>

                                                            <a href="index.php?view=list&order=edit_date&sort=<?php echo ($order == 'edit_date') ? $rsort : 'asc'; ?>">

                                                                <?php echo $texts['UPDATED_ON']; ?> <i class="fas fa-fw fa-sort<?php if ($order == 'edit_date') echo '-' . $sort_class; ?>"></i>

                                                            </a>

                                                        </th> -->

                                                        <?php

                                                        }

                                                        if (MAIN) { ?>

                                                            <th>

                                                                <a href="index.php?view=list&order=main&sort=<?php echo ($order == 'main') ? $rsort : 'asc'; ?>">

                                                                    <?php echo $texts['MAIN']; ?> <i class="fas fa-fw fa-sort<?php if ($order == 'main') echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>

                                                        <?php

                                                        }

                                                        if (VALIDATION) { ?>

                                                            <th>

                                                                <a href="index.php?view=list&order=checked&sort=<?php echo ($order == 'checked') ? $rsort : 'asc'; ?>">

                                                                    <?php echo $texts['STATUS']; ?> <i class="fas fa-fw fa-sort<?php if ($order == 'checked') echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>

                                                        <?php

                                                        }

                                                        if (MODULE == 'room') { ?>

                                                            <th>

                                                                <a href="">

                                                                    Rate

                                                                </a>

                                                            </th>

                                                        <?php }

                                                        if (HOME) { ?>

                                                            <th>

                                                                <a href="index.php?view=list&order=home&sort=<?php echo ($order == 'home') ? $rsort : 'asc'; ?>">

                                                                    <?php echo $texts['HOME']; ?> <i class="fas fa-fw fa-sort<?php if ($order == 'home') echo '-' . $sort_class; ?>"></i>

                                                                </a>

                                                            </th>

                                                        <?php

                                                        }



                                                        ?>

                                                        <th><?php echo $texts['ACTIONS']; ?></th>

                                                    </tr>

                                                </thead>
                                                   
                                                <tbody>

                                                    <?php



                                                    if ($result !== false) {

                                                        $idk = 0;
                                                        $cnt = 0;
                                                        foreach ($result as $i => $row) {
                                                            $idk++;
                                                            $record = ($offset + $idk);

                                                            if (MODULE == 'user' && $row['id'] == 1) continue;

                                                            $id = $row['id'];

                                                            $cols = getColsValues($db, $row, $i, $cols);
                                                            

                                                            if (MODULE == 'booking') {

                                                                $status = array('1' => 'alert-info', '2' => 'alert-danger', '3' => 'alert-warning', '4' => 'alert-success');

                                                                $st = db_getFieldValue($db, 'pm_booking', 'status', $id, $lang = 0);

                                                                $class = $status[$st];
                                                            } else {

                                                                $class = '';
                                                            }

                                                            if (isset($preview_path)) unset($preview_path); ?>
                                                            <tr id="item_<?php echo $id ?>" class="<?php echo $class; ?>">



                                                                <td class="text-left">

                                                                    <input type="checkbox" class="checkitem" name="multiple_item[]" value="<?php echo $id; ?>" />

                                                                    <?php //if(RANKING) echo $row['rank']; 
                                                                    ?>

                                                                </td>

                                                                

                                                                <td class="text-center">

                                                                    <?php

                                                                    if (in_array('all', $permissions) || in_array('view', $permissions)) {

                                                                        if (MODULE == 'booking') { ?>

                                                                            <a href="popup-details.php" data-params="id=<?php echo $id; ?>" title="Booking details" class="dropdown-item ajax-popup-link tips"> <?php echo $id; ?></a><br>

                                                                            <?php
                                                                            $checkIn = date('M d', $row['from_date']);
                                                                            $checkOut = date('M d', $row['to_date']);
                                                                            echo "<span class='down'>" . $checkIn . " - " . $checkOut . "</span>";
                                                                            ?>
                                                                        <?php

                                                                        } else if (MODULE == 'hotel') {

                                                                            echo $row['hotelid'];
                                                                        } else { ?>

                                                                            <?php echo ($offset + $idk); ?>

                                                                    <?php }
                                                                    }

                                                                    ?>

                                                                </td>





                                                                <?php

                                                                if (MODULE != 'hotel') {

                                                                    if (NB_FILES > 0) {

                                                                        $query_img = 'SELECT * FROM pm_' . MODULE . '_file WHERE type = \'image\' AND id_item = ' . $id . ' AND file != \'\'';

                                                                        if (MULTILINGUAL) $query_img .= ' AND lang = ' . DEFAULT_LANG;

                                                                        $query_img .= ' ORDER BY rank LIMIT 1';

                                                                        $result_img = $db->query($query_img);



                                                                        if ($result_img !== false && $db->last_row_count() > 0) {

                                                                            $row_img = $result_img->fetch();



                                                                            $filename_img = $row_img['file'];

                                                                            $id_img_file = $row_img['id'];

                                                                            $label = $row_img['label'];



                                                                            $big_path = 'medias/' . MODULE . '/big/' . $id_img_file . '/' . $filename_img;

                                                                            $medium_path = 'medias/' . MODULE . '/medium/' . $id_img_file . '/' . $filename_img;

                                                                            $small_path = 'medias/' . MODULE . '/small/' . $id_img_file . '/' . $filename_img;



                                                                            if (RESIZING == 0 && is_file(SYSBASE . $big_path)) $preview_path = $big_path;

                                                                            elseif (RESIZING == 1 && is_file(SYSBASE . $medium_path)) $preview_path = $medium_path;

                                                                            elseif (is_file(SYSBASE . $small_path)) $preview_path = $small_path;

                                                                            elseif (is_file(SYSBASE . $medium_path)) $preview_path = $medium_path;

                                                                            elseif (is_file(SYSBASE . $big_path)) $preview_path = $big_path;

                                                                            else $preview_path = '';



                                                                            if (is_file(SYSBASE . $big_path)) $zoom_path = $big_path;

                                                                            elseif (is_file(SYSBASE . $medium_path)) $zoom_path = $medium_path;

                                                                            elseif (is_file(SYSBASE . $small_path)) $zoom_path = $small_path;

                                                                            else $zoom_path = '';
                                                                        } ?>



                                                                        <td class="text-center wrap-img">

                                                                            <?php

                                                                            if (isset($preview_path) && is_file(SYSBASE . $preview_path)) {



                                                                                $max_w = 160;

                                                                                $max_h = 36;

                                                                                $dim = getimagesize(SYSBASE . $preview_path);

                                                                                $w = $dim[0];

                                                                                $h = $dim[1]; ?>



                                                                                <a href="<?php echo DOCBASE . $zoom_path; ?>" class="image-link" rel="<?php echo DOCBASE . $zoom_path; ?>">

                                                                                    <?php

                                                                                    if ($w < $max_w && $h < $max_h) {

                                                                                        $new_dim = getNewSize($w, $h, $max_w, $max_h);



                                                                                        $new_w = $new_dim[0];

                                                                                        $new_h = $new_dim[1];



                                                                                        $margin_w = round(($max_w - $new_w) / 2);

                                                                                        $margin_h = round(($max_h - $new_h) / 2);



                                                                                        echo '<img src="' . DOCBASE . $preview_path . '" width="' . $new_w . '" height="' . $new_h . '" style="margin:' . $margin_h . 'px ' . $margin_w . 'px;">';
                                                                                    } elseif (($w / $max_w) > ($h / $max_h))

                                                                                        echo '<img src="' . DOCBASE . $preview_path . '" height="' . $max_h . '" style="margin: 0px -' . ceil(((($w * $max_h) / $h) / 2) - ($max_w / 2)) . 'px;">';

                                                                                    else

                                                                                        echo '<img src="' . DOCBASE . $preview_path . '" width="' . $max_w . '" style="margin: -' . ceil(((($h * $max_w) / $w) / 2) - ($max_h / 2)) . 'px 0px;">'; ?>

                                                                                </a>

                                                                            <?php

                                                                            } ?>

                                                                        </td>

                                                                    <?php

                                                                    }

                                                                    if (isset($type_module) && $type_module == 'file') {



                                                                        $query_file = 'SELECT * FROM pm_' . MODULE . '_file WHERE id_item = ' . $id;

                                                                        if (MULTILINGUAL) $query_file .= ' AND lang = ' . DEFAULT_LANG;

                                                                        $query_file .= ' ORDER BY rank LIMIT 1';

                                                                        $result_file = $db->query($query_file);



                                                                        if ($result_file !== false && $db->last_row_count() > 0) {

                                                                            $row_file = $result_file->fetch();



                                                                            $label = $row_file['label'];

                                                                            $filename = $row_file['file'];
                                                                        } else {

                                                                            $label = '';

                                                                            $filename = '';
                                                                        }

                                                                        echo '<td>' . $filename . '</td>';

                                                                        echo '<td>' . $label . '</td>';
                                                                    }
                                                                }

                                                                foreach ($cols as $col) {

                                                                    echo '<td';

                                                                    $type = $col->getType();

                                                                    $name = $col->getName();

                                                                    if ($type == 'date' || $type == 'date') echo ' class="text-center"';

                                                                    if ($type == 'price') echo ' class="text-right"';

                                                                    echo '>';

                                                                    //echo strip_tags($col->getValue($i));

                                                                    if (MODULE == 'booking') {

                                                                        if ($name == 'firstname') {

                                                                            echo  $row['firstname'] . ' ' . $row['lastname'] . "<br>";

                                                                            $totalGuest = $row['adults'] + $row['children'];



                                                                            if ($totalGuest > 1) {

                                                                                echo "<span class='down'>" . $totalGuest . " Guests</span>";
                                                                            } else if ($totalGuest == 1) {

                                                                                echo "<span class='down'>" . $totalGuest . " Guest</span>";
                                                                            }
                                                                        } else {

                                                                            echo strip_tags(html_entity_decode($col->getValue($i)));
                                                                        }
                                                                    } else {

                                                                        echo strip_tags(html_entity_decode($col->getValue($i)));
                                                                    }

                                                                    echo '</td>';
                                                                }



                                                                if (MODULE == 'booking') {

                                                                    $status = $row['status'];



                                                                    if ($row['payment_option'] == "arrival") {

                                                                        $payments = "Pay at Hotel";
                                                                    } else {

                                                                        $payments = "<span style='color:#0C0;'>Pre-Paid</span>";
                                                                    }

                                                                    ?>

                                                                    <td class="text-center">

                                                                        <?php echo $payments . "<br><span class='down'>" . formatPrice($row['total']) . "</span>"; ?>

                                                                    </td>

                                                                    <td class="text-center status">

                                                                        <?php

                                                                        if ($status == 1) echo '<span class="label label-warning">' . $texts['AWAITING'] . '</span>';

                                                                        elseif ($status == 2) echo '<span class="label label-danger">' . $texts['CANCELLED'] . '</span>';

                                                                        elseif ($status == 3) echo '<span class="label label-default">' . $texts['REJECTED_PAYMENT'] . '</span>';

                                                                        elseif ($status == 4) echo '<span class="label label-success">' . $texts['PAYED'] . '</span>';

                                                                        else echo '<span class="label label-warning">' . $texts['AWAITING'] . '</span>'; ?>

                                                                    </td>
                                                                    
                                                                    <td class="text-center status">

                                                                        <?php



                                                                        if ($row['checked_out'] == "out") {

                                                                            echo '<span class="label label-success"> Checked out</span>';
                                                                        } else if ($row['checked_in'] == "in") {

                                                                            echo '<span class="label label-default"> Checked in</span>';
                                                                        } else {

                                                                            echo '--';
                                                                        }

                                                                        ?>

                                                                    </td>
                                                                    
                                                                <?php }

                                                            

                                                                if (DATES) {

                                                                    $add_date = (is_null($row['add_date'])) ? '-' : strftime(DATE_FORMAT . ' ' . TIME_FORMAT, $row['add_date']);

                                                                    $edit_date = (is_null($row['edit_date'])) ? '-' : strftime(DATE_FORMAT . ' ' . TIME_FORMAT, $row['edit_date']); ?>

                                                                    <!--  <td class="text-center">

                                                                    <?php //echo $add_date; 
                                                                    ?>

                                                                </td> -->

                                                                    <!--<td class="text-center">

                                                                    <?php //echo $edit_date; 
                                                                    ?>

                                                                </td>-->

                                                                <?php

                                                                }

                                                                if (MAIN) {

                                                                    $main = $row['main']; ?>

                                                                    <td class="text-center">

                                                                        <?php if ($main == 0) {

                                                                            if ((in_array('publish', $permissions) || in_array('all', $permissions))) { ?>

                                                                                <a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=define_main" title="<?php echo $texts['DEFINE_MAIN']; ?>"><i class="fas fa-fw fa-star text-muted"></i></a>

                                                                            <?php

                                                                            } else { ?>

                                                                                <i class="fas fa-fw fa-star text-muted"></i>

                                                                            <?php

                                                                            }
                                                                        } elseif ($main == 1) { ?>

                                                                            <i class="fas fa-fw fa-star text-primary"></i>

                                                                        <?php

                                                                        } ?>

                                                                    </td>

                                                                <?php

                                                                }



                                                                if (VALIDATION) {

                                                                    $checked = $row['checked']; ?>

                                                                    <td class="text-center status">

                                                                        <?php

                                                                        if ($checked == 0) echo '<span class="label label-warning">' . $texts['AWAITING'] . '</span>';

                                                                        elseif ($checked == 1) echo '<span class="label label-success">' . $texts['PUBLISHED'] . '</span>';

                                                                        elseif ($checked == 2) echo '<span class="label label-danger">' . $texts['NOT_PUBLISHED'] . '</span>';

                                                                        //elseif($checked == 3) echo '<span class="label label-default">'.$texts['ARCHIVED'].'</span>'; 
                                                                        ?>

                                                                    </td>

                                                                <?php

                                                                }

                                                                if (MODULE == 'room') { ?>

                                                                    <td class="text-center">

                                                                        <a class="tips tipss label label-primary" href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/rate/index.php?view=list&id_hotel=<?php echo $row['id_hotel']; ?>&id_room=<?php echo $id; ?>&search=&q_search=&csrf_token=<?php echo $csrf_token; ?>">
                                                                            <!-- <span class="label">Rates</span> -->
                                                                            Rates
                                                                        </a>
                                                                    </td>

                                                                <?php }



                                                                if (HOME) {

                                                                    $home = $row['home']; ?>

                                                                    <td class="text-center">

                                                                        <?php

                                                                        if ($home == 0) {

                                                                            if ((in_array('publish', $permissions) || in_array('all', $permissions))) { ?>

                                                                                <a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=display_home" title="<?php echo $texts['SHOW_HOMEPAGE']; ?>"><i class="fas fa-fw fa-home text-danger"></i></a>

                                                                            <?php

                                                                            } else { ?>

                                                                                <i class="fas fa-fw fa-home text-danger"></i>

                                                                            <?php

                                                                            }
                                                                        } elseif ($home == 1) {

                                                                            if ((in_array('publish', $permissions) || in_array('all', $permissions))) { ?>

                                                                                <a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=remove_home" title="<?php echo $texts['REMOVE_HOMEPAGE']; ?>"><i class="fas fa-fw fa-home text-success"></i></a>

                                                                            <?php

                                                                            } else { ?>

                                                                                <i class="fas fa-fw fa-home text-success"></i>

                                                                        <?php

                                                                            }
                                                                        } ?>

                                                                    </td>

                                                                <?php

                                                                }



                                                                ?>

                                                                <td class="text-center action_btn">

                                                                    <!--<div class="dropdown">

                                                                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $id; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>...</span></button>

                                                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $id; ?>"> -->





                                                                    <?php

                                                                    if (VALIDATION && (in_array('publish', $permissions) || in_array('all', $permissions))) {

                                                                        if ($checked == 0) { ?>

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=check" title="<?php echo $texts['PUBLISH']; ?>"><i class="fas fa-fw fa-check text-success"></i><?php //echo $texts['PUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=uncheck" title="<?php echo $texts['UNPUBLISH']; ?>"><i class="fas fa-fw fa-ban text-danger"></i><?php //echo $texts['UNPUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <!-- <a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=archive" title="<?php echo $texts['ARCHIVE']; ?>"><i class="fas fa-fw fa-archive text-warning"></i></a>-->

                                                                        <?php

                                                                        } elseif ($checked == 1) { ?>

                                                                            <!--<a class="tips dropdown-item" href="" title="<?php echo $texts['PUBLISH']; ?>"><i class="fas fa-fw fa-check text-muted"></i><?php echo $texts['PUBLISH']; ?></a> -->

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=uncheck" title="<?php echo $texts['UNPUBLISH']; ?>"><i class="fas fa-fw fa-ban text-danger"></i><?php //echo $texts['UNPUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <!-- <a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=archive" title="<?php echo $texts['ARCHIVE']; ?>"><i class="fas fa-fw fa-archive text-warning"></i></a>-->

                                                                        <?php

                                                                        } elseif ($checked == 2) { ?>

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=check" title="<?php echo $texts['PUBLISH']; ?>"><i class="fas fa-fw fa-check text-success"></i><?php //echo $texts['PUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <!--<a class="tips dropdown-item" href="" title="<?php echo $texts['UNPUBLISH']; ?>"><i class="fas fa-fw fa-ban text-muted"></i><?php echo $texts['UNPUBLISH']; ?></a> -->

                                                                            <!--<a class="tips" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=archive" title="<?php echo $texts['ARCHIVE']; ?>"><i class="fas fa-fw fa-archive text-warning"></i></a>-->

                                                                        <?php

                                                                        } elseif ($checked == 3) { ?>

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=check" title="<?php echo $texts['PUBLISH']; ?>"><i class="fas fa-fw fa-check text-success"></i><?php //echo $texts['PUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <a class="tips dropdown-item" href="index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=uncheck" title="<?php echo $texts['UNPUBLISH']; ?>"><i class="fas fa-fw fa-ban text-danger"></i><?php //echo $texts['UNPUBLISH']; 
                                                                                                                                                                                                                                                                                                        ?></a>

                                                                            <i class="fas fa-fw fa-archive text-muted"></i>

                                                                        <?php

                                                                        }
                                                                    }

                                                                    if (MODULE != 'wallet') {

                                                                        if (in_array('edit', $permissions) || in_array('all', $permissions)) { ?>
                                                                            <?php if (MODULE == "booking") { ?>
                                                                                <?php //if($row['status'] != 4 && $row['checked_out'] != "out"){ ?>
                                                                                <a class="tips dropdown-item" href="index.php?view=form&id=<?php echo $id; ?>" title="<?php echo $texts['EDIT']; ?>"><i class="fas fa-fw fa-edit"></i></a>
                                                                            <?php //}
                                                                            }else{ ?>
                                                                                <a class="tips dropdown-item" href="index.php?view=form&id=<?php echo $id; ?>" title="<?php echo $texts['EDIT']; ?>"><i class="fas fa-fw fa-edit"></i></a>
                                                                            <?php } ?>
                                                                        <?php
                                                                        }
                                                                    } else { ?>

                                                                        <a href="wallet.php" data-params="id=<?php echo $id; ?>" title="Wallet history" class="ajax-popup-link tips  dropdown-item"><i class="fas fa-fw fa-eye"></i> View</a>

                                                                        <?php }



                                                                    if (in_array(MODULE, $rsmodule)) {

                                                                        if (MODULE == 'hotel') {

                                                                            $query_items = 'SELECT id FROM pm_booking WHERE id_hotel=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        } else if (MODULE == 'room') {

                                                                            $query_items = 'SELECT id FROM pm_booking_room WHERE id_room=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        } else if (MODULE == 'booking') {

                                                                            $query_items = 'SELECT id FROM pm_booking WHERE id=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        } else if (MODULE == 'tax') {

                                                                            $query_items = 'SELECT id FROM pm_booking_tax WHERE id_tax=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        } else if (MODULE == 'service') {

                                                                            $query_items = 'SELECT id FROM pm_booking_service WHERE id_service=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        } else if (MODULE == 'payment') {

                                                                            $query_items = 'SELECT id FROM pm_booking_payment WHERE id_service=' . $id;

                                                                            $result_items = $db->query($query_items);
                                                                        }

                                                                        if (in_array('delete', $permissions) || in_array('all', $permissions)) {

                                                                            if ($result_items !== false && $db->last_row_count() > 0) { ?>

                                                                                <!-- <a class="tips dropdown-item" href="javascript:if(confirm('<?php echo $texts['DELETE_CONFIRM3']; ?>')) window.location = 'index.php?view=list&csrf_token=<?php echo $csrf_token; ?>&action=delete';" title="<?php echo $texts['DELETE']; ?>"><i class="fas fa-fw fa-trash-alt text-danger"></i><?php echo $texts['DELETE']; ?></a> -->

                                                                            <?php } else { ?>

                                                                                <!--<a class="tips dropdown-item" href="javascript:if(confirm('<?php echo $texts['DELETE_CONFIRM2']; ?>')) window.location = 'index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=delete';" title="<?php echo $texts['DELETE']; ?>"><i class="fas fa-fw fa-trash-alt text-danger"></i><?php echo $texts['DELETE']; ?></a> -->

                                                                            <?php }
                                                                        }
                                                                    } else {

                                                                        if (in_array('delete', $permissions) || in_array('all', $permissions)) { ?>

                                                                            <!--<a class="tips dropdown-item" href="javascript:if(confirm('<?php echo $texts['DELETE_CONFIRM2']; ?>')) window.location = 'index.php?view=list&id=<?php echo $id; ?>&csrf_token=<?php echo $csrf_token; ?>&action=delete';" title="<?php echo $texts['DELETE']; ?>"><i class="fas fa-fw fa-trash-alt text-danger"></i><?php echo $texts['DELETE']; ?></a> -->

                                                                        <?php }
                                                                    }



                                                                    if (in_array('edit', $permissions) || in_array('all', $permissions) || in_array('view', $permissions)) {

                                                                        if (MODULE == 'room') { ?>

                                                                            <!--<a  class="tips dropdown-item" href="calendar.php?view=calendar&id=<?php echo $id; ?>" title="Price calendar"><i class="fas fa-fw fa-calendar"></i></a>-->



                                                                        <?php }

                                                                        if (MODULE == 'user') { ?>

                                                                            <a href="chat-pop.php" data-params="id=<?php echo $id; ?>" title="Chat log" class="dropdown-item ajax-popup-link tips"><i class="fas fa-fw fa-chat"></i>Chat</a>

                                                                        <?php

                                                                        }

                                                                        if (MODULE == 'booking') { ?>

                                                                            <!-- <a href="popup-details.php" data-params="id=<?php echo $id; ?>" title="Booking details" class="dropdown-item ajax-popup-link tips"><i class="fas fa-fw fa-eye"></i></a> -->

                                                                            <?php if ($_SESSION['admin']['type'] == 'administrator') { ?>

                                                                                <a href="popup-log.php" data-params="id=<?php echo $id; ?>" title="Booking Activity log" class="dropdown-item ajax-popup-link tips"><i class="fas fa-fw fa-book"></i></a>

                                                                            <?php } ?>

                                                                            <?php $today = time();

                                                                            $query_items = 'SELECT from_date, to_date, status FROM pm_booking WHERE id=' . $id;

                                                                            $result_items = $db->prepare($query_items);

                                                                            if ($result_items->execute() !== false) {

                                                                                $row = $result_items->fetch();

                                                                                if ($row['status'] != 2 && $row['status'] != 4) { ?>

                                                                                    <a href="cancel_popup.php" data-params="id=<?php echo $id; ?>" title="Cancel Booking" class="dropdown-item ajax-popup-link tips "><i class="fas fa-fw fa-times"></i></a>

                                                                    <?php }
                                                                            }
                                                                        }
                                                                    }

                                                                    ?>

                                                                    <?php /*?><?php

                                                               if(MODULE=='hotel'){

																?>

																	<a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/report.php?hotel_id=<?php echo $id; ?>">Report</a>

																<?php    

																}

																?><?php */ ?>

                                                                    <!--</div>

                                                             </div> -->

                                                                </td>
                                                            </tr>
                                                    <?php
                                                            
                                                            $cnt++;
                                                            if($cnt == 696){
                                                                break;
                                                            }
                                                        }
                                                    } ?>

                                                </tbody>

                                            </table>

                                        </div>

                                        <?php

                                        if ($total == 0) { ?>

                                            <div class="text-center mt20 mb20">- <?php echo $texts['NO_ELEMENT']; ?> -</div>

                                        <?php

                                        } ?>

                                    </div>

                                    <div class="panel-footer form-inline clearfix" style="display:none;">

                                        <div class="row">

                                            <div class="col-md-6 text-left col_foot_left">

                                                <div class="input-group">

                                                    <div class="input-group-addon"><i class="fas fa-fw fa-th-list"></i> Displaying </div>

                                                    <span class="form-control input-sm"><?php echo $offset . ' to ' . $record . ' out of  ' . $total; ?></span>

                                                </div>



                                                <?php if ($total > 0) { ?>

                                                    <!-- <span class="selectspan"><input type="checkbox" class="selectall"/>

                                                <?php echo $texts['SELECT_ALL']; ?>

                                                </span>

                                                <select name="multiple_actions" class="form-control input-sm">

                                                    <option value="">- <?php echo $texts['ACTIONS']; ?> -</option>

                                                    <?php

                                                    if (in_array('publish', $permissions) || in_array('all', $permissions)) {

                                                        if (VALIDATION) { ?>

                                                            <option value="check_multi"><?php echo $texts['PUBLISH']; ?></option>

                                                            <option value="uncheck_multi"><?php echo $texts['UNPUBLISH']; ?></option>

                                                            <?php

                                                        }

                                                        if (HOME) { ?>

                                                            <option value="display_home_multi"><?php echo $texts['SHOW_HOMEPAGE']; ?></option>

                                                            <option value="remove_home_multi"><?php echo $texts['REMOVE_HOMEPAGE']; ?></option>

                                                            <?php

                                                        }
                                                    }

                                                    if (in_array('delete', $permissions) || in_array('all', $permissions)) { ?>

                                                        <option value="delete_multi"><?php echo $texts['DELETE']; ?></option>

                                                        <?php

                                                    } ?>

                                                </select> -->

                                                <?php

                                                } ?>

                                            </div>

                                            <div class="col-md-6 text-right col_foot_right">

                                                <div class="input-group">

                                                    <div class="input-group-addon"><i class="fas fa-fw fa-th-list"></i> <?php echo $texts['DISPLAY']; ?></div>

                                                    <select class="select-url form-control input-sm">

                                                        <?php

                                                        echo ($limit != 20) ? '<option value="index.php?view=list&limit=20">20</option>' : '<option selected="selected">20</option>';

                                                        echo ($limit != 50) ? '<option value="index.php?view=list&limit=50">50</option>' : '<option selected="selected">50</option>';

                                                        echo ($limit != 100) ? '<option value="index.php?view=list&limit=100">100</option>' : '<option selected="selected">100</option>';

                                                        ?>

                                                    </select>

                                                </div>



                                                <?php

                                                if ($limit > 0) {

                                                    $nb_pages = ceil($total / $limit);

                                                    if ($nb_pages > 1) { ?>

                                                        <div class="input-group">

                                                            <div class="input-group-addon"><?php echo $texts['PAGE']; ?></div>

                                                            <select class="select-url form-control input-sm">

                                                                <?php



                                                                for ($i = 1; $i <= $nb_pages; $i++) {

                                                                    $offset2 = ($i - 1) * $limit;



                                                                    if ($offset2 == $offset)

                                                                        echo '<option value="" selected="selected">' . $i . '</option>';

                                                                    else

                                                                        echo '<option value="index.php?view=list&offset=' . $offset2 . '">' . $i . '</option>';
                                                                } ?>

                                                            </select>

                                                        </div>

                                                <?php

                                                    }
                                                } ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <?php

                                if (in_array('all', $permissions)) {

                                    if ($db != false && MULTILINGUAL && $total_lang > 0) { ?>

                                        <div class="well">

                                            <div id="translation">

                                                <p><?php echo $texts['COMPLETE_LANGUAGE']; ?></p>

                                                <?php

                                                foreach ($result_lang as $row_lang) {

                                                    $id_lang = $row_lang['id'];

                                                    $title_lang = $row_lang['title']; ?>



                                                    <input type="checkbox" name="languages[]" value="<?php echo $id_lang; ?>">

                                                <?php

                                                    $result_img_lang = $db->query('SELECT * FROM pm_lang_file WHERE id_item = ' . $id_lang . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');

                                                    if ($result_img_lang !== false && $db->last_row_count() > 0) {

                                                        $row_img_lang = $result_img_lang->fetch();



                                                        $id_img_lang = $row_img_lang['id'];

                                                        $file_img_lang = $row_img_lang['file'];



                                                        if (is_file(SYSBASE . 'medias/lang/big/' . $id_img_lang . '/' . $file_img_lang))

                                                            echo '<img src="' . DOCBASE . 'medias/lang/big/' . $id_img_lang . '/' . $file_img_lang . '" alt="" border="0" class="flag"> ';
                                                    }

                                                    echo $title_lang . '<br>';
                                                } ?>

                                                <button type="submit" name="complete_lang" class="btn btn-default mt10" data-toggle="tooltip" data-placement="right" title="<?php echo $texts['COMPLETE_LANG_NOTICE']; ?>"><i class="fas fa-fw fa-magic"></i> <?php echo $texts['APPLY_LANGUAGE']; ?></button>

                                            </div>

                                        </div>

                                <?php

                                    }
                                }

                                ?>

                            </form>

                        </div>

                <?php

                    } else echo '<p>' . $texts['ACCESS_DENIED'] . '</p>';
                } ?>

            </div>

        </div>

    </div>





    <?php if (is_file('custom_list.php')) include('custom_list.php'); ?>

    <?php include(SYSBASE . ADMIN_FOLDER . '/modules/default/nav_script.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#listing_base').DataTable({

                "pageLength": 25,

                "searching": false,

                //"dom": '<"top"i>rt<"bottom"flp><"clear">'

            });



        });
    </script>



</body>

</html>

<?php

$_SESSION['redirect'] = false;

$_SESSION['msg_error'] = array();

$_SESSION['msg_success'] = array();

$_SESSION['msg_notice'] = array(); ?>