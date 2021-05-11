<?php
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
$fromDate = '';
$toDate = '';
$dateDifference = 20;
if(isset($_POST['fromDate']) && isset($_POST['toDate'])){
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
}
if($fromDate != '' && $toDate != ''){
    $dateDifference = (date_diff(date_create($toDate), date_create($fromDate))->format("%a")) + 1;
}
if($fromDate == ''){
    $toDay = date('Y-m-d');
}else{
    $toDay = date_format(date_create($fromDate), 'Y-m-d');
}
$finalArray = array();
$tempArray = array();
$query_rooms = 'SELECT r.id as room_id, r.id_hotel, h.title as hotel_name, r.title as room_title, r.stock, r.price,
pr.discount, pr.discount_type FROM pm_room as r 
LEFT JOIN pm_hotel h ON h.id = r.id_hotel 
LEFT JOIN pm_rate pr ON pr.id_room = r.id 
WHERE r.checked = 1 AND r.lang = ' . DEFAULT_LANG;
if ($_SESSION['admin']['type'] == 'hotel') $query_rooms .= ' AND users REGEXP \'[[:<:]]' . $_SESSION['admin']['id'] . '[[:>:]]\'';
$query_rooms .= ' ORDER BY r.id_hotel ASC';
$rooms = $db->query($query_rooms);
$finalRoomArray = array();
if ($rooms !== false && $db->last_row_count() > 0) {
    foreach ($rooms as $val) {
        $finalRoomArray[] = array(
            "id_hotel"          => $val['id_hotel'],
            "hotel_name"        => $val['hotel_name'],
            "room_id"           => $val['room_id'],
            "room_title"        => $val['room_title'],
            "stock"             => $val['stock'],
            "price"             => $val['price'],
            "discount"          => $val['discount'],
            "discount_type"     => $val['discount_type'],
        );
    }
}
define('TITLE_ELEMENT', 'Calendar');
require_once('includes/fn_module.php');
$csrf_token = get_token('calendar'); ?>
<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php include('includes/inc_header_common.php'); ?>
    <link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER . '/css/pms.css'; ?>">
    <link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER . '/css/selectables.css'; ?>">
    <link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER . '/css/jquery.loading.css'; ?>">
    <link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER . '/css/theme.css'; ?>">
</head>

<body>
    <div id="overlay">
        <div id="loading"></div>
    </div>
    <div id="wrapper">
        <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_top.php'); ?>
        <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h1 class="pull-left"><i class="fas fa-fw fa-<?php echo ICON; ?>"></i> Calendar</h1>
                        </div>
                    </div>
					<div class="form_wrapper aviabalites_panel inventory_page_panel">
						<form id="dateForm" method="post" action="calendar.php">
                            <div class="form-inline">
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                    <div class="upform_left">
                                        <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <svg class="svg-inline--fa fa-calendar fa-w-14 fa-fw" aria-hidden="true" data-fa-processed="" data-prefix="fas" data-icon="calendar" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                        <path fill="currentColor" d="M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z"></path>
                                                    </svg>
                                                    From
                                                </div>
                                                <input type="text" class="form-control datepickerr fromDate" data-rel='toDate' rel="fromDate" autocomplete="off" value="<?=$fromDate? date_format(date_create($fromDate), 'd M'):''?>">
                                                <input type="hidden" id="fromDate" name="fromDate" value="<?=$fromDate?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <svg class="svg-inline--fa fa-calendar fa-w-14 fa-fw" aria-hidden="true" data-fa-processed="" data-prefix="fas" data-icon="calendar" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                        <path fill="currentColor" d="M12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm436-44v-36c0-26.5-21.5-48-48-48h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v36c0 6.6 5.4 12 12 12h424c6.6 0 12-5.4 12-12z"></path>
                                                    </svg>
                                                    To
                                                </div>
                                                <input type="text" class="form-control datepickerr toDate" data-rel='fromDate' rel="toDate" autocomplete="off" value="<?=$toDate? date_format(date_create($toDate), 'd M'):''?>">
                                                <input type="hidden" id="toDate" name="toDate" value="<?=$toDate?>">
                                            </div>
                                            <div class="field-notice" rel="to_date"></div>
                                        </div>
                                        <div class="form-group upformbutton">
                                            <button type="button" class="btnset dateFormSubmit">Set Period</button>
                                        </div>
                                        <div class="form-group upformbutton">
                                            <a href="<?=base_url('/admin/calendar.php')?>">
                                                <button type="button" class="btnreset">Reset</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="upform_right">
                                        <div class="form-group">
                                            <div class="upbox1"></div>
                                            <span>Unavailable</span>
                                        </div>
                                        <div class="form-group">
                                            <div class="upbox2"></div>
                                            <span>Available</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
					</div>
					<div class="bulkeditsec">
                                <button type="button" data-toggle="modal" data-target="#bulkEditModal">Bulk Edit</button>
                            </div>
					<div class="ph_bottom row">
						<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 gap-right">&nbsp;</div>
						<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 gap-left">
							<div class="top_calendar">
                                <div class="top_calendar_inner">
                                    <?php
                                    $month = '';
                                    for ($i = 0; $i < $dateDifference; $i++) {
                                        $showDate = date('Y-m-d', strtotime($toDay . ' +' . $i . ' day'));
                                        $weekDay = date('N', strtotime($showDate));
                                        print ($month == ''?'<ul><li><span class="month_name">'.date('F', strtotime($showDate)).'</span></li>':'');
                                        print ($month != '' && $month != date('F', strtotime($showDate)) ? '</ul><ul><li class="month_name_last"><span>'.date('F', strtotime($showDate)).'</span></li>' : '');
                                    ?>
                                        <li><?=($weekDay == 6 || $weekDay == 7 ? '<strong>' : '')?><?=date('D', strtotime($showDate))?> <span><?=date('d', strtotime($showDate))?></span><?=($weekDay == 6 || $weekDay == 7 ? '</strong>' : '')?></li>
                                        <?php
                                        $month = date('F', strtotime($showDate));
                                        if($i == ($dateDifference - 1)){
                                            print '</ul>';
                                        }
                                    } ?>
                                </div>
                            </div>
                            
						</div>
					</div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="form_wrapper aviabalites_panel inventory_page_panel">
                <div class="panel-default">
                    <section class="body_wrapper">
                        
                        <?php
                        if (!empty($finalRoomArray)) {
                            $serial = 0;
                            $oldHotel = '';
                            $oldRoom = '';
                            $eachwidth = 100 / $dateDifference;
                            foreach ($finalRoomArray as $key => $val) {
                                print ($oldHotel == ''?'<div class="main_loop">
                                <div class="main_heading">'.$val['hotel_name'].'</div>':'');
                                print ($oldHotel != '' && $oldHotel != $val['hotel_name'] ? '</div><div class="main_loop">
                                <div class="main_heading">'.$val['hotel_name'].'</div>' : '');
                            ?>
                        <div class="sub_loop">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 gap-right">
                                    <div class="inventory_left">
                                        <h3><?=$val['room_title']?></h3>
                                        <ul>
                                            <li>Room Status</li>
                                            <li>Stock</li>
                                            <li>Booked</li>
                                            <li>Standard Rate</li>
                                            <li>Discounted Rate</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 gap-left">
                                    <div class="inventory_right">
                                        <div class="inventory_right_inner">
                                            <?php
                                                    $printStock = '<div class="timeline-det'.$serial.'">';
                                                    $printBooked = '<div class="timeline-det-three'.$serial.'">';
                                                    $printPrice = '<div class="timeline-det-two'.$serial.'">';
                                                    $printDiscounted = '<div class="timeline-det-four'.$serial.'">';
                                                    $oldBlocked = '';
                                                    $headingHtml = '';
                                                    $headingHtmlTwo = '';
                                                    $finalWidth = '';
                                                    for ($i = 0; $i < $dateDifference; $i++) {
                                                        $showDate = date('Y-m-d', strtotime($toDay . ' +' . $i . ' day'));
                                                        $showDateNew = date('d M', strtotime($toDay . ' +' . $i . ' day'));
                                                        $weekDay = date('N', strtotime($showDate));
                                                        $check_rate_stock_query = 'SELECT nsr.new_stock, nsr.new_price, nsr.new_disc_price, nsr.is_blocked from pm_room_new_stock_rate nsr
                                                        WHERE nsr.id_hotel = ' . $val['id_hotel'] . ' AND nsr.id_room = ' . $val['room_id'] . ' AND nsr.date = \'' . $showDate . '\'';
                                                        $check_rate_stock = $db->query($check_rate_stock_query)->fetch(PDO::FETCH_ASSOC);
                                                        $blocked = 0;
                                                        $discRate = '';
                                                        if (!empty($check_rate_stock)) {
                                                            if ($check_rate_stock['new_stock'] != NULL || $check_rate_stock['new_stock'] != '') {
                                                                $stockVal = $check_rate_stock['new_stock'];
                                                            } else {
                                                                $stockVal = $val['stock'];
                                                            }
                                                            if ($check_rate_stock['new_price'] != NULL || $check_rate_stock['new_price'] != '') {
                                                                $priceVal = $check_rate_stock['new_price'];
                                                            } else {
                                                                $priceVal = $val['price'];
                                                            }
                                                            if ($check_rate_stock['new_disc_price'] != NULL || $check_rate_stock['new_disc_price'] != '') {
                                                                $discRate = $check_rate_stock['new_disc_price'];
                                                            }
                                                            $blocked = $check_rate_stock['is_blocked'];
                                                        } else {
                                                            $stockVal = $val['stock'];
                                                            $priceVal = $val['price'];
                                                        }
                                                        //$priceVal = number_format((float)$priceVal, 2, '.', '');
                                                        $bookedCntQuery = 'SELECT count(b.id) bookedCount from pm_booking b left join pm_booking_room br on br.id_booking = b.id
                                                        WHERE b.id_hotel = ' . $val['id_hotel'] . ' AND br.id_room = ' . $val['room_id'] . ' AND b.from_date = \'' . strtotime($showDate) . '\' 
                                                        AND b.status != 2';
                                                        $bookedCnt = $db->query($bookedCntQuery)->fetch(PDO::FETCH_ASSOC);
                                                        if($discRate == ''){
                                                            if(empty($val['discount']) || $val['discount'] == 0){
                                                                $discRate = $val['price'];
                                                            }else{
                                                                if($val['discount_type'] == 'rate'){
                                                                    $discRate = $val['price'] - (($val['price'] * $val['discount']) / 100);
                                                                }elseif($val['discount_type'] == 'fixed'){
                                                                    $discRate = $val['price'] - $val['discount'];
                                                                }else{
                                                                    $discRate = $val['price'];
                                                                }
                                                            }
                                                        }
                                                        $discRate = round($discRate);
                                                        //$discRate = number_format((float)$discRate, 2, '.', '');
                                                        if($i == 0){
                                                            $headingHtml = '<div class="'.($blocked ? 'cls' : 'bkl').'"';
                                                            $headingHtmlTwo = '><h4 class="'.($blocked ? 'closed' : 'bookable').'">'.($blocked ? 'Closed' : 'Bookable').'</h4></div>';
                                                            $finalWidth = $eachwidth;
                                                        }else{
                                                            if($i > 0 && $oldBlocked != $blocked){
                                                                //$headingHtml .= '<div class="'.($blocked ? 'cls' : 'bkl').'"><h4 class="'.($blocked ? 'closed' : 'bookable').'">'.($blocked ? 'Closed' : 'Bookable').'</h4></div>';
                                                                $headingHtml .= ' style="width: '.$finalWidth.'%;"'.$headingHtmlTwo.'<div class="'.($blocked ? 'cls' : 'bkl').'"';
                                                                $headingHtmlTwo = '><h4 class="'.($blocked ? 'closed' : 'bookable').'">'.($blocked ? 'Closed' : 'Bookable').'</h4></div>';
                                                                $finalWidth = $eachwidth;
                                                            }else{
                                                                $finalWidth += $eachwidth;
                                                            }
                                                        }
                                                        $printStock         .= '<li style="width: '.$eachwidth.'%;" class="'.($blocked ? 'blocked' : 'un-blocked').' commonBlockUnblock stockCount" data-show-date="'.$showDateNew.'" data-date="'.$showDate.'" data-name-hotel="'.$val['hotel_name'].'" data-name-room="'.$val['room_title'].'" data-id-hotel="'.$val['id_hotel'].'" data-id-room="'.$val['room_id'].'" data-is-blocked="'.$blocked.'"><span class="stockCount'.$showDate . $val['id_hotel'] . $val['room_id'].'">'. $stockVal .'</span></li>';
                                                        $printBooked        .= '<li style="width: '.$eachwidth.'%;" class="'.($blocked ? 'blocked' : 'un-blocked').' commonBlockUnblock stockBooked" data-show-date="'.$showDateNew.'" data-date="'.$showDate.'" data-name-hotel="'.$val['hotel_name'].'" data-name-room="'.$val['room_title'].'" data-id-hotel="'.$val['id_hotel'].'" data-id-room="'.$val['room_id'].'" data-is-blocked="'.$blocked.'"><span class="stockBooked'.$showDate . $val['id_hotel'] . $val['room_id'].'">'. $bookedCnt['bookedCount'] .'</li>';
                                                        $printPrice         .= '<li style="width: '.$eachwidth.'%;" class="'.($blocked ? 'blocked' : 'un-blocked').' commonBlockUnblock stockRate" data-show-date="'.$showDateNew.'" data-date="'.$showDate.'" data-name-hotel="'.$val['hotel_name'].'" data-name-room="'.$val['room_title'].'" data-id-hotel="'.$val['id_hotel'].'" data-id-room="'.$val['room_id'].'" data-is-blocked="'.$blocked.'"><span class="stockRate'.$showDate . $val['id_hotel'] . $val['room_id'].'">'. $priceVal .'</span></li>';
                                                        $printDiscounted    .= '<li style="width: '.$eachwidth.'%;" class="'.($blocked ? 'blocked' : 'un-blocked').' commonBlockUnblock stockDiscRate" data-show-date="'.$showDateNew.'" data-date="'.$showDate.'" data-name-hotel="'.$val['hotel_name'].'" data-name-room="'.$val['room_title'].'" data-id-hotel="'.$val['id_hotel'].'" data-id-room="'.$val['room_id'].'" data-is-blocked="'.$blocked.'"><span class="stockDiscRate'.$showDate . $val['id_hotel'] . $val['room_id'].'">'. $discRate .'</span></li>';
                                                        $oldBlocked = $blocked;
                                                    }
                                                    $printStock .= '</div>';
                                                    $printBooked .= '</div>';
                                                    $printPrice .= '</div>';
                                                    $printDiscounted .= '</div>';
                                                    $headingHtml .= ' style="width: '.$finalWidth.'%;"'.$headingHtmlTwo;
                                                    ?>
                                            <div class="status_heading">
                                                <?=$headingHtml?>
                                            </div>
                                            <ul class="bookable_listing">
                                                <?=$printStock.$printBooked.$printPrice.$printDiscounted?>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                $serial++;
                                $oldHotel = $val['hotel_name'];
                                $oldRoom = $val['room_title'];
                                print ($key == (count($finalRoomArray) - 1) ? '</div>' : '' );
                            }
                        }
                        ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="totRoomCount" value="<?= count($finalRoomArray) ?>">
    <div class="modal fade" id="editModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title modal-title-stock"></h4>
                </div>
                <div class="modal-body">
                    <label class="modalDateStock"></label>
                    <br />
                    <label>Rooms to sell : </label>
                    <input type="text" id="changedRoom" class="form-control" onkeypress="return isNumber(this.event)" placeholder="Enter Rooms To Sell">
                    <br>
                    <input type="radio" class="mt-20" id="openStock" name="isBlockedStock" value="0">
                    <label for="openStock">Open</label>
                    <input type="radio" class="mt-20" id="closeStock" name="isBlockedStock" value="1">
                    <label for="closeStock">Close</label>
                </div>
                <div class="modal-footer text-center-imp">
                    <button type="button" class="btn btn-success modalChange">Save</button>
                    <button type="button" class="btn btn-danger modalClose">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModalPrice" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title modal-title-rate"></h4>
                </div>
                <div class="modal-body">
                    <label class="modalDateRate"></label>
                    <br />
                    <label>Room Standard Rate : </label>
                    <input type="text" id="changedRate" class="form-control" onkeypress="return isNumber(this.event)" placeholder="Enter Room Standard Rate">
                    <br>
                    <input type="radio" class="mt-20" id="openRate" name="isBlockedRate" value="0">
                    <label for="openRate">Open</label>
                    <input type="radio" class="mt-20" id="closeRate" name="isBlockedRate" value="1">
                    <label for="closeRate">Close</label>
                </div>
                <div class="modal-footer text-center-imp">
                    <button type="button" class="btn btn-success modalChangeRate">Save</button>
                    <button type="button" class="btn btn-danger modalCloseRate">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModalDisc" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title modal-title-disc-rate"></h4>
                </div>
                <div class="modal-body">
                    <label class="modalDateDiscRate"></label>
                    <br />
                    <label>Room Discounted Rate : </label>
                    <input type="text" id="changedDiscRate" class="form-control" onkeypress="return isNumber(this.event)" placeholder="Enter Room Discounted Rate">
                    <br>
                    <input type="radio" class="mt-20" id="openDiscRate" name="isBlockedDiscRate" value="0">
                    <label for="openRate">Open</label>
                    <input type="radio" class="mt-20" id="closeDiscRate" name="isBlockedDiscRate" value="1">
                    <label for="closeRate">Close</label>
                </div>
                <div class="modal-footer text-center-imp">
                    <button type="button" class="btn btn-success modalChangeDiscRate">Save</button>
                    <button type="button" class="btn btn-danger modalCloseDiscRate">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bulkEditModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog-new">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bulk Edit Room & Rate</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <label>From Date *</label>
                        <input type="text" class="form-control datepicker" rel="bulkFromDate" placeholder="Select From Date" autocomplete="off">
                        <input type="hidden" id="bulkFromDate" name="bulkFromDate" value="">
                    </div>
                    <div class="col-md-6">
                        <label>To Date *</label>
                        <input type="text" class="form-control datepicker" rel="bulkToDate" placeholder="Select To Date" autocomplete="off">
                        <input type="hidden" id="bulkToDate" name="bulkToDate" value="">
                    </div>
                    <br>
                    <div class="col-md-12">
                        <label>Select Hotel *</label>
                        <select class="form-control" id="bulkHoteId" onchange="getHotels(this.value)">
                            <option value=""> Select Hotel </option>
                            <?php
                            $lastHotel = '';
                            if(!empty($finalRoomArray)){
                                foreach($finalRoomArray as $hotel){
                                    if($hotel['hotel_name'] != $lastHotel){
                            ?>
                            <option value="<?=$hotel['id_hotel']?>"><?=$hotel['hotel_name']?></option>
                            <?php
                                    }
                                    $lastHotel = $hotel['hotel_name'];
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="roomsDiv col-md-12"></div>
                    <br>
                    <div class="col-md-6">
                        <label>Rooms to sell : </label>
                        <input type="text" id="bulkStock" class="form-control" onkeypress="return isNumber(this.event)" placeholder="Enter Rooms to Sell">
                    </div>
                    <div class="col-md-6">
                        <label>Room Standard Rate : </label>
                        <input type="text" id="bulkRate" class="form-control" onkeypress="return isNumber(this.event)" placeholder="Enter Room Standard Rate">
                    </div>
                    <br>
                    <div class="col-md-12">
                        <label>Room Status : </label>
                        <br>
                        <input type="radio" class="mt-20" id="openBulk" name="bulkIsBlocked" value="0" checked>
                        <label for="openBulk">Open</label>
                        <input type="radio" class="mt-20" id="closeBulk" name="bulkIsBlocked" value="1">
                        <label for="closeBulk">Close</label>
                    </div>
                </div>
                <div class="modal-footer text-center-imp">
                    <button type="button" class="btn btn-success bulkEditChange">Save</button>
                    <button type="button" class="btn btn-danger bulkEditClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/selectables.js"></script>
    <script src="js/jquery.loading.js"></script>
    <script>
        $(function() {
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                onClose: function(selectedDate) {
                    if (selectedDate != '') {
                        $('#' + $(this).attr('rel')).val(selectedDate);
                        $(this).val($.datepicker.formatDate('d MM', new Date(selectedDate)));
                    }
                }
            });
            $('.fromDate').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                onClose: function(selectedDate) {
                    if (selectedDate != '') {
                        $('#' + $(this).attr('rel')).val(selectedDate);
                        $(this).val($.datepicker.formatDate('d MM', new Date(selectedDate)));
                    }
                    commonChangeDate($(this).attr('data-rel'), selectedDate);
                }
            });
            $('.toDate').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                minDate: +19,
                onClose: function(selectedDate) {
                    if (selectedDate != '') {
                        $('#' + $(this).attr('rel')).val(selectedDate);
                        $(this).val($.datepicker.formatDate('d MM', new Date(selectedDate)));
                    }
                    commonChangeDate($(this).attr('data-rel'), selectedDate);
                }
            });
        });
        function commonChangeDate(dataRel, selectedDate){
            let altDate = new Date(selectedDate);
            if(dataRel == 'fromDate'){
                altDate.setDate(altDate.getDate() - 19);
            }else if(dataRel == 'toDate'){
                altDate.setDate(altDate.getDate() + 19);
            }
            $('.' + dataRel).val($.datepicker.formatDate('d MM', new Date(altDate)));
            $('#' +dataRel).val($.datepicker.formatDate('yy-mm-dd', new Date(altDate)));
        }
        $(".dateFormSubmit").click(function() {
            if ($('#toDate').val() != '' && $('#fromDate').val() != '') {
                if ($('#toDate').val() >= $('#fromDate').val()) {
                    $("#dateForm").submit();
                } else {
                    alert("From Date must be lesser than or equals to To Date");
                }
            } else {
                alert("Please select From Date & To Date");
            }
        });
        function singleRoomStockRate(type, date, idHotel, idRroom, oldVal, currVal) {
            if(currVal.split('-').length > 1){
                alert("-ve value not allowed !!!");
                return false;
            }
            if(type == 'Count'){
                if(currVal < parseInt($('.stockBooked' + date + idHotel + idRroom).html())){
                    alert("Rooms to Sell Can't be less than Booked Room !!!");
                    return false;
                }
            }else if(type == 'Rate'){
                if(currVal < 1){
                    alert("Standard Rate Can't be 0 !!!");
                    return false;
                }
                if(currVal < parseInt($('.stockDiscRate' + date + idHotel + idRroom).html())){
                    alert("Standard rate can't be less than Discounted Rate !!!");
                    return false;
                }
            }else if(type == 'DiscRate'){
                if(currVal < 1){
                    alert("Discounted rate Can't be 0 !!!");
                    return false;
                }
                if(parseInt($('.stockRate' + date + idHotel + idRroom).html()) < currVal){
                    alert("Discounted rate can't be greater than Standard Rate !!!");
                    return false;
                }
            }
            $(".stock" + type).each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active')
                    return false;
                }
            });
            if (currVal == '') {
                $('.stock' + type + date + idHotel + idRroom).html(oldVal);
            } else {
                $('.stock' + type + date + idHotel + idRroom).html(currVal);
            }
            if (currVal != '' && currVal != oldVal) {
                $.ajax({
                    url: 'inventory_ajax.php',
                    type: 'post',
                    data: JSON.stringify({
                        singleCurrVal: currVal,
                        singleType: type,
                        singleDate: date,
                        singleIdHotel: idHotel,
                        singleIdRoom: idRroom
                    }),
                    contentType: "application/json",
                    dataType: 'json',
                    beforeSend: function() {
                        $(".room-row").loading();
                    },
                    success: function(json) {
                        $(".room-row").loading("stop");
                        $('.stock' + type + date + idHotel + idRroom).html(currVal);
                    }
                });
            }
        }
        $(document).keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                if ($("#singleRoomStockRate").length) {
                    singleRoomStockRate($("#singleRoomStockRate").attr('data-type'), $("#singleRoomStockRate").attr('data-date'), $("#singleRoomStockRate").attr('data-id-hotel'), $("#singleRoomStockRate").attr('data-id-room'), $("#singleRoomStockRate").attr('data-old-value'), $("#singleRoomStockRate").val());
                }
            }
        });
        if ($('#totRoomCount').val() != '') {
            document.addEventListener('DOMContentLoaded', function() {
                for (var i = 0; i < $('#totRoomCount').val(); i++) {
                    new Selectables({
                        elements: 'li',
                        selectedClass: 'active',
                        zone: '.timeline-det' + i,
                        start: function(e) {},
                        stop: function(e) {
                            if ($("#singleRoomStockRate").length) {
                                singleRoomStockRate($("#singleRoomStockRate").attr('data-type'), $("#singleRoomStockRate").attr('data-date'), $("#singleRoomStockRate").attr('data-id-hotel'), $("#singleRoomStockRate").attr('data-id-room'), $("#singleRoomStockRate").attr('data-old-value'), $("#singleRoomStockRate").val());
                            } else {
                                var chk = '';
                                var hotelName = '';
                                var roomName = '';
                                var fromDate = '';
                                var toDate = '';
                                var stockCnt = 0;
                                $(".stockCount").each(function() {
                                    if ($(this).hasClass('active')) {
                                        if (chk == '') {
                                            chk = $(this).attr('data-is-blocked');
                                        } else {
                                            if (chk != $(this).attr('data-is-blocked')) {
                                                chk = '0';
                                                return false;
                                            }
                                        }
                                        hotelName = $(this).attr('data-name-hotel');
                                        roomName = $(this).attr('data-name-room');
                                        if (fromDate == '') {
                                            fromDate = $(this).attr('data-show-date');
                                        } else {
                                            toDate = $(this).attr('data-show-date');
                                        }
                                        stockCnt++;
                                    }
                                });
                                if (stockCnt > 1) {
                                    if (chk == 1) {
                                        $('#openStock').prop('checked', false);
                                        $('#closeStock').prop('checked', true);
                                    } else if (chk == 0) {
                                        $('#closeStock').prop('checked', false);
                                        $('#openStock').prop('checked', true);
                                    }
                                    $('.modal-title-stock').text(hotelName + ' - ' + roomName);
                                    if (toDate == '') {
                                        $('.modalDateStock').text(fromDate);
                                    } else {
                                        $('.modalDateStock').text(fromDate + ' - ' + toDate);
                                    }
                                    $('#editModal').modal('show');
                                } else if (stockCnt == 1) {
                                    $(".stockCount").each(function() {
                                        if ($(this).hasClass('active')) {
                                            var dataDate = $(this).attr('data-date');
                                            var dataIdHotel = $(this).attr('data-id-hotel');
                                            var dataIdRoom = $(this).attr('data-id-room');
                                            var oldStock = $('.stockCount' + dataDate + dataIdHotel + dataIdRoom).text();
                                            $('.stockCount' + dataDate + dataIdHotel + dataIdRoom).html('');
                                            $('.stockCount' + dataDate + dataIdHotel + dataIdRoom).html('<input type="text" class="form-control" id="singleRoomStockRate" onkeypress="return isNumber(this.event)" data-date="' + dataDate + '" data-id-hotel="' + dataIdHotel + '" data-id-room="' + dataIdRoom + '" data-type="Count" data-old-value="' + oldStock + '" value="' + oldStock + '">');
                                            $('#singleRoomStockRate').focus();
                                            return false;
                                        }
                                    });
                                }
                            }
                        }
                    });
                    new Selectables({
                        elements: 'li',
                        selectedClass: 'active',
                        zone: '.timeline-det-two' + i,
                        start: function(e) {},
                        stop: function(e) {
                            if ($("#singleRoomStockRate").length) {
                                singleRoomStockRate($("#singleRoomStockRate").attr('data-type'), $("#singleRoomStockRate").attr('data-date'), $("#singleRoomStockRate").attr('data-id-hotel'), $("#singleRoomStockRate").attr('data-id-room'), $("#singleRoomStockRate").attr('data-old-value'), $("#singleRoomStockRate").val());
                            } else {
                                var chk = '';
                                var hotelName = '';
                                var roomName = '';
                                var fromDate = '';
                                var toDate = '';
                                var stockCnt = 0;
                                $(".stockRate").each(function() {
                                    if ($(this).hasClass('active')) {
                                        if (chk == '') {
                                            chk = $(this).attr('data-is-blocked');
                                        } else {
                                            if (chk != $(this).attr('data-is-blocked')) {
                                                chk = '0';
                                                return false;
                                            }
                                        }
                                        hotelName = $(this).attr('data-name-hotel');
                                        roomName = $(this).attr('data-name-room');
                                        if (fromDate == '') {
                                            fromDate = $(this).attr('data-show-date');
                                        } else {
                                            toDate = $(this).attr('data-show-date');
                                        }
                                        stockCnt++;
                                    }
                                });
                                if (stockCnt > 1) {
                                    if (chk == 1) {
                                        $('#openRate').prop('checked', false);
                                        $('#closeRate').prop('checked', true);
                                    } else if (chk == 0) {
                                        $('#closeRate').prop('checked', false);
                                        $('#openRate').prop('checked', true);
                                    }
                                    $('.modal-title-rate').text(hotelName + ' - ' + roomName);
                                    if (toDate == '') {
                                        $('.modalDateRate').text(fromDate);
                                    } else {
                                        $('.modalDateRate').text(fromDate + ' - ' + toDate);
                                    }
                                    $('#editModalPrice').modal('show');
                                } else if (stockCnt == 1) {
                                    $(".stockRate").each(function() {
                                        if ($(this).hasClass('active')) {
                                            var dataDate = $(this).attr('data-date');
                                            var dataIdHotel = $(this).attr('data-id-hotel');
                                            var dataIdRoom = $(this).attr('data-id-room');
                                            var oldStock = $('.stockRate' + dataDate + dataIdHotel + dataIdRoom).text();
                                            oldStock = oldStock.replace(" ", "")
                                            $('.stockRate' + dataDate + dataIdHotel + dataIdRoom).html('');
                                            $('.stockRate' + dataDate + dataIdHotel + dataIdRoom).html('<input type="text" class="form-control" id="singleRoomStockRate" onkeypress="return isNumber(this.event)" data-date="' + dataDate + '" data-id-hotel="' + dataIdHotel + '" data-id-room="' + dataIdRoom + '" data-type="Rate" data-old-value="' + oldStock + '" value="' + oldStock + '">');
                                            $('#singleRoomStockRate').focus();
                                            return false;
                                        }
                                    });
                                }
                            }
                        }
                    });
                    new Selectables({
                        elements: 'li',
                        selectedClass: 'active',
                        zone: '.timeline-det-four' + i,
                        start: function(e) {},
                        stop: function(e) {
                            if ($("#singleRoomStockRate").length) {
                                singleRoomStockRate($("#singleRoomStockRate").attr('data-type'), $("#singleRoomStockRate").attr('data-date'), $("#singleRoomStockRate").attr('data-id-hotel'), $("#singleRoomStockRate").attr('data-id-room'), $("#singleRoomStockRate").attr('data-old-value'), $("#singleRoomStockRate").val());
                            } else {
                                var chk = '';
                                var hotelName = '';
                                var roomName = '';
                                var fromDate = '';
                                var toDate = '';
                                var stockCnt = 0;
                                $(".stockDiscRate").each(function() {
                                    if ($(this).hasClass('active')) {
                                        if (chk == '') {
                                            chk = $(this).attr('data-is-blocked');
                                        } else {
                                            if (chk != $(this).attr('data-is-blocked')) {
                                                chk = '0';
                                                return false;
                                            }
                                        }
                                        hotelName = $(this).attr('data-name-hotel');
                                        roomName = $(this).attr('data-name-room');
                                        if (fromDate == '') {
                                            fromDate = $(this).attr('data-show-date');
                                        } else {
                                            toDate = $(this).attr('data-show-date');
                                        }
                                        stockCnt++;
                                    }
                                });
                                if (stockCnt > 1) {
                                    if (chk == 1) {
                                        $('#openDiscRate').prop('checked', false);
                                        $('#closeDiscRate').prop('checked', true);
                                    } else if (chk == 0) {
                                        $('#closeDiscRate').prop('checked', false);
                                        $('#openDiscRate').prop('checked', true);
                                    }
                                    $('.modal-title-disc-rate').text(hotelName + ' - ' + roomName);
                                    if (toDate == '') {
                                        $('.modalDateDiscRate').text(fromDate);
                                    } else {
                                        $('.modalDateDiscRate').text(fromDate + ' - ' + toDate);
                                    }
                                    $('#editModalDisc').modal('show');
                                } else if (stockCnt == 1) {
                                    $(".stockDiscRate").each(function() {
                                        if ($(this).hasClass('active')) {
                                            var dataDate = $(this).attr('data-date');
                                            var dataIdHotel = $(this).attr('data-id-hotel');
                                            var dataIdRoom = $(this).attr('data-id-room');
                                            var oldStock = $('.stockDiscRate' + dataDate + dataIdHotel + dataIdRoom).text();
                                            oldStock = oldStock.replace(" ", "")
                                            $('.stockDiscRate' + dataDate + dataIdHotel + dataIdRoom).html('');
                                            $('.stockDiscRate' + dataDate + dataIdHotel + dataIdRoom).html('<input type="text" class="form-control" id="singleRoomStockRate" onkeypress="return isNumber(this.event)" data-date="' + dataDate + '" data-id-hotel="' + dataIdHotel + '" data-id-room="' + dataIdRoom + '" data-type="DiscRate" data-old-value="' + oldStock + '" value="' + oldStock + '">');
                                            $('#singleRoomStockRate').focus();
                                            return false;
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
        }
        //Bulk Edit Modal Change Function
        $(document).on('click', '.bulkEditChange', function(e) {
            if ($('#bulkFromDate').val() == '') {
                alert("From Date is Mandatory !!!");
                return false;
            }
            if ($('#bulkToDate').val() == '') {
                alert("To Date is Mandatory !!!");
                return false;
            }
            if ($('#bulkToDate').val() < $('#bulkFromDate').val()) {
                alert("From Date must be lesser than or equals to To Date");
                return false;
            }
            if ($('#bulkHoteId').val() == '') {
                alert("Please Select Hotel !!!");
                return false;
            }
            let diffDays = parseInt((new Date($('#bulkToDate').val()) - new Date($('#bulkFromDate').val())) / (1000 * 60 * 60 * 24), 10);
            var openClose = $("input[name='bulkIsBlocked']:checked").val();
            let bulkRoomIds = [];
            let proceed = true;
            $.each($("#bulkRoomId option:selected"), function() {
                if ($(this).val() != '') {
                    if (openClose == 1) {
                        for (var i = 0; i <= diffDays; i++) {
                            let chkDT = new Date($('#bulkFromDate').val());
                            chkDT.setDate(chkDT.getDate() + i);
                            let dt = chkDT.getFullYear() + '-' + ((chkDT.getMonth() + 1) < 10 ? '0' + (chkDT.getMonth() + 1) : (chkDT.getMonth() + 1)) + '-' + chkDT.getDate();
                            if (parseInt($('.stockBooked' + dt + $('#bulkHoteId').val() + $(this).val()).html()) > 0) {
                                proceed = false;
                                alert("Already room booked !!! Can't Disable room of date " + dt);
                                return false;
                            }
                        }
                    }
                    if (!proceed) {
                        return false;
                    }
                    bulkRoomIds.push($(this).val());
                }
                if (!proceed) {
                    return false;
                }
            });
            if (!proceed) { return false; }
            if (!(bulkRoomIds.length > 0)) {
                alert("Please Select Room(s) !!!");
                return false;
            }
            if ($('#bulkStock').val() != '') {
                if ($('#bulkStock').val() < 0) {
                    proceed = false;
                    alert("Rooms to Sell Can't be -ve value !!!");
                    $('#bulkStock').val('');
                    return false;
                }
                bulkRoomIds.forEach(function(ri) {
                    for (var i = 0; i <= diffDays; i++) {
                        let chkDT = new Date($('#bulkFromDate').val());
                        chkDT.setDate(chkDT.getDate() + i);
                        let dt = chkDT.getFullYear() + '-' + ((chkDT.getMonth() + 1) < 10 ? '0' + (chkDT.getMonth() + 1) : (chkDT.getMonth() + 1)) + '-' + chkDT.getDate();
                        if (parseInt($('.stockBooked' + dt + $('#bulkHoteId').val() + ri).text()) > $('#bulkStock').val()) {
                            proceed = false;
                            alert("Room stock can't be lesser than booked room !!!");
                            return false;
                        }
                    }
                });
            }
            if (!proceed) { return false; }
            if ($('#bulkRate').val() != '') {
                if($('#bulkRate').val() < 1){
                    proceed = false;
                    alert("Standard Rate Can't be 0 or -ve value !!!");
                    $('#bulkRate').val('');
                    return false;
                }else{
                    bulkRoomIds.forEach(function(ri) {
                        for (var i = 0; i <= diffDays; i++) {
                            let chkDT = new Date($('#bulkFromDate').val());
                            chkDT.setDate(chkDT.getDate() + i);
                            let dt = chkDT.getFullYear() + '-' + ((chkDT.getMonth() + 1) < 10 ? '0' + (chkDT.getMonth() + 1) : (chkDT.getMonth() + 1)) + '-' + chkDT.getDate();
                            if ($('#bulkRate').val() < parseInt($('.stockDiscRate' + dt + $('#bulkHoteId').val() + ri).text())) {
                                proceed = false;
                                $('#bulkRate').val('');
                                alert("Standard rate can't be lesser than Discounted Rate !!!");
                                return false;
                            }
                        }
                    });
                }
            }
            if (!proceed) { return false; }
            $.ajax({
                url: 'inventory_ajax.php',
                type: 'post',
                data: JSON.stringify({
                    bulkFromDate: $('#bulkFromDate').val(),
                    bulkToDate: $('#bulkToDate').val(),
                    bulkHoteId: $('#bulkHoteId').val(),
                    bulkRoomIds: bulkRoomIds,
                    bulkStock: $('#bulkStock').val(),
                    bulkRate: $('#bulkRate').val(),
                    bulkIsBlocked: openClose
                }),
                contentType: "application/json",
                dataType: 'json',
                beforeSend: function() {
                    $("#bulkEditModal").loading();
                },
                success: function(json) {
                    $("#bulkEditModal").loading("stop");
                    if (json.success) {
                        var dates = json.dates.split(',');
                        var roomIds = json.roomIds.split(',');
                        var hotelId = $('#bulkHoteId').val();
                        for (var d = 0; d < dates.length; d++) {
                            var chkDate = dates[d];
                            for (var r = 0; r < roomIds.length; r++) {
                                var roomId = roomIds[r];
                                $(".stockCount").each(function() {
                                    if ($(this).attr('data-date') == chkDate && $(this).attr('data-id-hotel') == hotelId && $(this).attr('data-id-room') == roomId) {
                                        if ($('#bulkStock').val() != '') {
                                            $('.stockCount' + chkDate + hotelId + roomId).text($('#bulkStock').val());
                                        }
                                        $(this).attr('data-is-blocked', openClose);
                                        if (openClose == 0) {
                                            $(this).removeClass('blocked');
                                            $(this).addClass('un-blocked');
                                        } else {
                                            $(this).removeClass('un-blocked');
                                            $(this).addClass('blocked');
                                        }
                                    }
                                });
                                $(".stockRate").each(function() {
                                    if ($(this).attr('data-date') == chkDate && $(this).attr('data-id-hotel') == hotelId && $(this).attr('data-id-room') == roomId) {
                                        if ($('#bulkRate').val() != '') {
                                            $('.stockRate' + chkDate + hotelId + roomId).text($('#bulkRate').val());
                                        }
                                        $(this).attr('data-is-blocked', openClose);
                                        if (openClose == 0) {
                                            $(this).removeClass('blocked');
                                            $(this).addClass('un-blocked');
                                        } else {
                                            $(this).removeClass('un-blocked');
                                            $(this).addClass('blocked');
                                        }
                                    }
                                });
                                commonBlockUnblock('', 'stockDiscRate', 'stockBooked', chkDate, hotelId, roomId, openClose);
                            }
                        }
                        $('.bulkEditClose').click();
                        location.reload();
                    } else {
                        alert(json.message);
                    }
                }
            });
        });
        // Close Room Disc Rate Modal
        $(document).on('click', '.modalCloseDiscRate', function(e) {
            $('#editModalDisc').modal('hide');
            $(".stockDiscRate").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
        });
        // Close Room Rate Modal
        $(document).on('click', '.modalCloseRate', function(e) {
            $('#editModalPrice').modal('hide');
            $(".stockRate").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
        });
        // Close Room Stock Modal
        $(document).on('click', '.modalClose', function(e) {
            $('#editModal').modal('hide');
            $(".stockCount").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
        });
        // Change Room Discounted Rate
        $(document).on('click', '.modalChangeDiscRate', function(e) {
            let proceed = true;
            let newVal = $('#changedDiscRate').val();
            if(newVal.split('-').length > 1){
                alert("-ve value not allowed !!!");
                return false;
            }
            $('#changedDiscRate').val('');
            if(newVal < 1){
                proceed = false;
                alert("Discounted Rate Can't be 0 !!!");
                $('#changedDiscRate').val('');
                return false;
            }
            if(!proceed){ return false; }
            let dates = '';
            let hotelId = '';
            let roomId = '';
            var openClose = $("input[name='isBlockedDiscRate']:checked").val();
            $(".stockDiscRate").each(function() {
                if ($(this).hasClass('active')) {
                    if (openClose == 1) {
                        if (parseInt($('.stockBooked' + $(this).attr('data-date') + $(this).attr('data-id-hotel') + $(this).attr('data-id-room')).html()) > 0) {
                            proceed = false;
                            alert(" Already room booked !!! Can't Disable room of date " + $(this).attr('data-date'));
                            return false;
                        }
                    }
                    if (dates == '') {
                        dates = $(this).attr('data-date');
                    } else {
                        dates += ',' + $(this).attr('data-date');
                    }
                    hotelId = $(this).attr('data-id-hotel');
                    roomId = $(this).attr('data-id-room');
                    if(newVal > parseInt($('.stockRate' + $(this).attr('data-date') + hotelId + roomId).text())){
                        proceed = false;
                        alert("Discounted rate can't be greater than Standard Rate !!!");
                        return false;
                    }
                }
                if (!proceed) {
                    return false;
                }
            });
            if (!proceed) {
                // $(".stockDiscRate").each(function() {
                //     $(this).removeClass('active');
                // });
                return false;
            }
            $('#editModalDisc').modal('hide');
            $.ajax({
                url: 'inventory_ajax.php',
                type: 'post',
                data: JSON.stringify({
                    roomDiscRate: newVal,
                    hotelId: hotelId,
                    roomId: roomId,
                    dates: dates,
                    isBlocked: openClose
                }),
                contentType: "application/json",
                dataType: 'json',
                beforeSend: function() {
                    $(".inventory_right").loading();
                },
                success: function(json) {
                    $(".inventory_right").loading("stop");
                    $(".stockDiscRate").each(function() {
                        if ($(this).hasClass('active')) {
                            let attrOne = $(this).attr('data-date');
                            let attrTwo = $(this).attr('data-id-hotel');
                            let attrThree = $(this).attr('data-id-room');
                            if (newVal != '') {
                                $('.stockDiscRate' + attrOne + attrTwo + attrThree).text(newVal);
                            }
                            $(this).removeClass('active');
                            $(this).attr('data-is-blocked', openClose);
                            if (openClose == 0) {
                                $(this).removeClass('blocked');
                                $(this).addClass('un-blocked');
                            } else {
                                $(this).removeClass('un-blocked');
                                $(this).addClass('blocked');
                            }
                            commonBlockUnblock('stockCount', 'stockRate', 'stockBooked', attrOne, attrTwo, attrThree, openClose);
                        }
                    });
                    location.reload();
                }
            });
        });
        // Change Room Rate
        $(document).on('click', '.modalChangeRate', function(e) {
            let proceed = true;
            let newVal = $('#changedRate').val();
            if(newVal.split('-').length > 1){
                alert("-ve value not allowed !!!");
                return false;
            }
            $('#changedRate').val('');
            if(newVal < 1){
                proceed = false;
                alert("Standard Rate Can't be 0 !!!");
                return false;
            }
            if(!proceed){ return false; }
            let dates = '';
            let hotelId = '';
            let roomId = '';
            var openClose = $("input[name='isBlockedRate']:checked").val();
            $(".stockRate").each(function() {
                if ($(this).hasClass('active')) {
                    if (openClose == 1) {
                        if (parseInt($('.stockBooked' + $(this).attr('data-date') + $(this).attr('data-id-hotel') + $(this).attr('data-id-room')).html()) > 0) {
                            proceed = false;
                            alert(" Already room booked !!! Can't Disable room of date " + $(this).attr('data-date'));
                            return false;
                        }
                    }
                    if (dates == '') {
                        dates = $(this).attr('data-date');
                    } else {
                        dates += ',' + $(this).attr('data-date');
                    }
                    hotelId = $(this).attr('data-id-hotel');
                    roomId = $(this).attr('data-id-room');
                    if(newVal < parseInt($('.stockDiscRate' + $(this).attr('data-date') + hotelId + roomId).text())){
                        proceed = false;
                        alert("Standard rate can't be lesser than Discounted Rate !!!");
                        return false;
                    }
                }
                if (!proceed) {
                    return false;
                }
            });
            if (!proceed) {
                // $(".stockRate").each(function() {
                //     $(this).removeClass('active');
                // });
                return false;
            }
            $('#editModalPrice').modal('hide');
            $.ajax({
                url: 'inventory_ajax.php',
                type: 'post',
                data: JSON.stringify({
                    roomRate: newVal,
                    hotelId: hotelId,
                    roomId: roomId,
                    dates: dates,
                    isBlocked: openClose
                }),
                contentType: "application/json",
                dataType: 'json',
                beforeSend: function() {
                    $(".inventory_right").loading();
                },
                success: function(json) {
                    $(".inventory_right").loading("stop");
                    $(".stockRate").each(function() {
                        if ($(this).hasClass('active')) {
                            let attrOne = $(this).attr('data-date');
                            let attrTwo = $(this).attr('data-id-hotel');
                            let attrThree = $(this).attr('data-id-room');
                            if (newVal != '') {
                                $('.stockRate' + attrOne + attrTwo + attrThree).text(newVal);
                            }
                            $(this).removeClass('active');
                            $(this).attr('data-is-blocked', openClose);
                            if (openClose == 0) {
                                $(this).removeClass('blocked');
                                $(this).addClass('un-blocked');
                            } else {
                                $(this).removeClass('un-blocked');
                                $(this).addClass('blocked');
                            }
                            commonBlockUnblock('stockCount', 'stockDiscRate', 'stockBooked', attrOne, attrTwo, attrThree, openClose);
                        }
                    });
                    location.reload();
                }
            });
        });
        // Change Room Stock
        $(document).on('click', '.modalChange', function(e) {
            let proceed = true;
            let newVal = $('#changedRoom').val();
            if(newVal.split('-').length > 1){
                alert("-ve value not allowed !!!");
                return false;
            }
            $('#changedRoom').val('');
            if(newVal < 0){
                proceed = false;
                alert("Rooms to Sell Can't be -ve value !!!");
                $('#changedRoom').val('');
                return false;
            }
            if(!proceed){ return false; }
            let dates = '';
            let hotelId = '';
            let roomId = '';
            var openClose = $("input[name='isBlockedStock']:checked").val();
            $(".stockCount").each(function() {
                if ($(this).hasClass('active')) {
                    if (openClose == 1) {
                        if (parseInt($('.stockBooked' + $(this).attr('data-date') + $(this).attr('data-id-hotel') + $(this).attr('data-id-room')).html()) > 0) {
                            proceed = false;
                            alert(" Already " + bookedRoom + " room booked !!! Can't Disable room of date " + $(this).attr('data-date'));
                            return false;
                        }
                    }
                    if (newVal != '') {
                        if (parseInt($('.stockBooked' + $(this).attr('data-date') + $(this).attr('data-id-hotel') + $(this).attr('data-id-room')).html()) > newVal) {
                            proceed = false;
                            alert("Room stock can't be lesser than booked room !!!");
                            return false;
                        }
                    }
                    if (dates == '') {
                        dates = $(this).attr('data-date');
                    } else {
                        dates += ',' + $(this).attr('data-date');
                    }
                    hotelId = $(this).attr('data-id-hotel');
                    roomId = $(this).attr('data-id-room');
                }
                if (!proceed) {
                    return false;
                }
            });
            if(!proceed){ return false; }
            $('#editModal').modal('hide');
            // if (!proceed) {
            //     $(".stockCount").each(function() {
            //         $(this).removeClass('active');
            //     });
            //     return false;
            // }
            $.ajax({
                url: 'inventory_ajax.php',
                type: 'post',
                data: JSON.stringify({
                    roomStock: newVal,
                    hotelId: hotelId,
                    roomId: roomId,
                    dates: dates,
                    isBlocked: openClose
                }),
                contentType: "application/json",
                dataType: 'json',
                beforeSend: function() {
                    $(".inventory_right").loading();
                },
                success: function(json) {
                    $(".inventory_right").loading("stop");
                    if (json.success) {
                        $(".stockCount").each(function() {
                            if ($(this).hasClass('active')) {
                                let attrOne = $(this).attr('data-date');
                                let attrTwo = $(this).attr('data-id-hotel');
                                let attrThree = $(this).attr('data-id-room');
                                if (newVal != '') {
                                    $('.stockCount' + attrOne + attrTwo + attrThree).text(newVal);
                                }
                                $(this).removeClass('active');
                                $(this).attr('data-is-blocked', openClose);
                                if (openClose == 0) {
                                    $(this).removeClass('blocked');
                                    $(this).addClass('un-blocked');
                                } else {
                                    $(this).removeClass('un-blocked');
                                    $(this).addClass('blocked');
                                }
                                commonBlockUnblock('stockRate', 'stockDiscRate', 'stockBooked', attrOne, attrTwo, attrThree, openClose);
                            }
                        });
                        location.reload();
                    } else {
                        $(".stockCount").each(function() {
                            if ($(this).hasClass('active')) {
                                $(this).removeClass('active');
                            }
                        });
                        alert(json.message);
                    }
                }
            });
        });
        function getHotels(hotelId) {
            if (hotelId == '') {
                $('.roomsDiv').html('');
            } else {
                $.ajax({
                    url: 'inventory_ajax.php',
                    type: 'post',
                    data: JSON.stringify({
                        hotelIdForRooms: hotelId
                    }),
                    contentType: "application/json",
                    dataType: 'json',
                    beforeSend: function() {
                        $(".roomsDiv").loading();
                    },
                    success: function(json) {
                        $(".roomsDiv").loading("stop");
                        if (json.success) {
                            $('.roomsDiv').html(json.html);
                        }
                    }
                });
            }
        }
        function isNumber(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                if (charCode == 43 || charCode == 45 || charCode == 4) {
                    return true;
                }
                return false;
            }
            return true;
        }
        function commonBlockUnblock(class1, class2, class3, attrOne, attrTwo, attrThree, openClose) {
            var clss = '';
            for (var i = 1; i < 4; i++) {
                if (i == 1) {
                    clss = class1;
                } else if (i == 2) {
                    clss = class2;
                } else if (i == 3) {
                    clss = class3;
                }
                if (clss != '') {
                    $("." + clss).each(function() {
                        if ($(this).attr('data-date') == attrOne && $(this).attr('data-id-hotel') == attrTwo && $(this).attr('data-id-room') == attrThree) {
                            if (openClose == 0) {
                                $(this).removeClass('blocked');
                                $(this).addClass('un-blocked');
                            } else {
                                $(this).removeClass('un-blocked');
                                $(this).addClass('blocked');
                            }
                            $(this).attr('data-is-blocked', openClose);
                            return false;
                        }
                    });
                }
            }
        }
    </script>
    <?php include(SYSBASE . ADMIN_FOLDER . '/modules/default/nav_script.php'); ?>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
