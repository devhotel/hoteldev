<?php
session_start();
require_once('../common/lib.php');
require_once('../common/setenv.php');
$config_file = '../common/config.php';
$htaccess_file = '../.htaccess';
$db = false;
require_once('../common/define.php');
$json = array();
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if (strcasecmp($contentType, 'application/json') == 0) {
        $data = json_decode(file_get_contents('php://input'));
        $html = '';
        $head = '';
        $body = '';
        if (isset($data->hotelIdForRooms)) {
            $room_query = "select * from pm_room where id_hotel = '" . $data->hotelIdForRooms . "' AND checked = 1";
            $rooms = $db->query($room_query)->fetchAll(PDO::FETCH_ASSOC);
            $html = '<label>Select Rooms *</label>
                    <select class="form-control" id="bulkRoomId" multiple>';
            if(!empty($rooms)){
                foreach($rooms as $room){
                    $html .= '<option value="'.$room['id'].'"> '.$room['title'].' </option>';
                }
            }else{
                $html .= '<option value=""> No Rooms Available </option>';
            }
            $html .= '</select>';
        }else if (isset($data->bulkFromDate) && isset($data->bulkToDate)) {
            if (isset($data->bulkStock)) {
                foreach($data->bulkRoomIds as $roomId){
                    $check_stock_query = "select stock from pm_room where id = '" . $roomId . "' AND id_hotel = '" . $data->bulkHoteId . "'";
                    $check_stock = $db->query($check_stock_query)->fetch(PDO::FETCH_ASSOC);
                    if ($check_stock['stock'] < $data->bulkStock) {
                        $json['success'] = FALSE;
                        $json['message'] = 'New Stock Can\'t be Greater than Room Stock';
                        print json_encode($json);
                        die;
                    }
                }
            }
            $fromDate = date_format(date_create($data->bulkFromDate), 'Y-m-d');
            $toDate = date_format(date_create($data->bulkToDate), 'Y-m-d');
            $dates = '';
            $roomIds = '';
            $dateDiff = date_diff(date_create($fromDate), date_create($toDate))->format("%a");
            for($i = 0; $i <= $dateDiff; $i++){
                $checkDate = date('Y-m-d', strtotime($fromDate . ' +'.$i.' day'));
                if($dates == ''){
                    $dates = $checkDate;
                }else{
                    $dates .= ','.$checkDate;
                }
                foreach($data->bulkRoomIds as $roomId){
                    if($roomIds == ''){
                        $roomIds = $roomId;
                    }else{
                        $roomIds .= ','.$roomId;
                    }
                    $check_query = "select id from pm_room_new_stock_rate where id_hotel = '" . $data->bulkHoteId . "' AND
                        id_room = '" . $roomId . "' AND date = '" . $checkDate . "'";
                    $check = $db->query($check_query);
                    if ($check !== false && $db->last_row_count() > 0) {
                        $stockUpdate = '';
                        $rateUpdate = '';
                        if($data->bulkStock != ''){
                            $stockUpdate = "`new_stock`= '" . $data->bulkStock . "', ";
                        }
                        if($data->bulkRate != ''){
                            $rateUpdate = "`new_price`= '" . $data->bulkRate . "', ";
                        }
                        $query = "UPDATE `pm_room_new_stock_rate` SET " . $stockUpdate . $rateUpdate ." `is_blocked` = '" . $data->bulkIsBlocked . "',
                        `updated_at` = '" . date('Y-m-d H:i:s') . "' where id_hotel = '" . $data->bulkHoteId . "' 
                        AND id_room = '" . $roomId . "' AND date = '" . $checkDate . "'";
                    }else{
                        $head = '';
                        $val = '';
                        if($data->bulkStock != ''){
                            $head .= "`new_stock`, ";
                            $val .= "'" . $data->bulkStock . "', ";
                        }
                        if($data->bulkRate != ''){
                            $head .= "`new_price`, ";
                            $val .= "'" . $data->bulkRate . "', ";
                        }
                        $query = "INSERT INTO `pm_room_new_stock_rate`(`id_hotel`, `id_room`, " . $head . " `is_blocked`, `date`) 
                            VALUES ('" . $data->bulkHoteId . "', '" . $roomId . "', " . $val . " '" . $data->bulkIsBlocked . "', '" . $checkDate . "')";
                    }
                    $db->query($query);
                }
            }
            $json['dates'] = $dates;
            $json['roomIds'] = $roomIds;
        }else if (isset($data->singleCurrVal)) {
            $check_query = "select id from pm_room_new_stock_rate where id_hotel = '" . $data->singleIdHotel . "' AND id_room = '" . $data->singleIdRoom . "' AND date = '" . $data->singleDate . "'";
            $check = $db->query($check_query);
            if ($check !== false && $db->last_row_count() > 0) {
                $update = '';
                if ($data->singleType == 'Count') {
                    $update = "`new_stock`= '" . $data->singleCurrVal . "', ";
                }else if ($data->singleType == 'Rate') {
                    $update = "`new_price`= '" . $data->singleCurrVal . "', ";
                }else if ($data->singleType == 'DiscRate') {
                    $update = "`new_disc_price`= '" . $data->singleCurrVal . "', ";
                }
                $query = "UPDATE `pm_room_new_stock_rate` SET " . $update . "`updated_at` = '" . date('Y-m-d H:i:s') . "' where id_hotel = '" . $data->singleIdHotel . "' 
                AND id_room = '" . $data->singleIdRoom . "' AND date = '" . $data->singleDate . "'";
            }else{
                $colmn = '';
                if ($data->singleType == 'Count') {
                    $colmn = "`new_stock`";
                }else if ($data->singleType == 'Rate') {
                    $colmn = "`new_price`";
                }
                $query = "INSERT INTO `pm_room_new_stock_rate`(`id_hotel`, `id_room`, ".$colmn.", `date`) 
                        VALUES ('" . $data->singleIdHotel . "', '" . $data->singleIdRoom . "', '" . $data->singleCurrVal . "', '" . $data->singleDate . "')";
            }
            $db->query($query);
        }else{
            if (isset($data->roomStock)) {
                $check_stock_query = "select stock from pm_room where id = '" . $data->roomId . "' AND id_hotel = '" . $data->hotelId . "'";
                $check_stock = $db->query($check_stock_query)->fetch(PDO::FETCH_ASSOC);
                if ($check_stock['stock'] < $data->roomStock) {
                    $json['success'] = FALSE;
                    $json['message'] = 'New Stock Can\'t be Greater than Room Stock';
                    print json_encode($json);
                    die;
                }
            }
            $dates = explode(',', $data->dates);
            for ($i = 0; $i < count($dates); $i++) {
                $check_query = "select id from pm_room_new_stock_rate where id_hotel = '" . $data->hotelId . "' AND
                id_room = '" . $data->roomId . "' AND date = '" . $dates[$i] . "'";
                $check = $db->query($check_query);
                if (isset($data->roomStock)) {
                    if ($check !== false && $db->last_row_count() > 0) {
                        $stockUpdate = '';
                        if($data->roomStock != ''){
                            $stockUpdate = "`new_stock`= '" . $data->roomStock . "', ";
                        }
                        $query = "UPDATE `pm_room_new_stock_rate` SET " . $stockUpdate . " `is_blocked` = '" . $data->isBlocked . "',
                        `updated_at` = '" . date('Y-m-d H:i:s') . "' where id_hotel = '" . $data->hotelId . "' 
                        AND id_room = '" . $data->roomId . "' AND date = '" . $dates[$i] . "'";
                    } else {
                        if($data->roomStock != ''){
                            $head = "`new_stock`,";
                            $body = "'" . $data->roomStock . "',";
                        }
                        $query = "INSERT INTO `pm_room_new_stock_rate`(`id_hotel`, `id_room`, ".$head." `is_blocked`, `date`) 
                            VALUES ('" . $data->hotelId . "', '" . $data->roomId . "', ".$body." '" . $data->isBlocked . "', '" . $dates[$i] . "')";
                    }
                } else if (isset($data->roomRate)) {
                    if ($check !== false && $db->last_row_count() > 0) {
                        $rateUpdate = '';
                        if($data->roomRate != ''){
                            $rateUpdate = "`new_price`= '" . $data->roomRate . "', ";
                        }
                        $query = "UPDATE `pm_room_new_stock_rate` SET " . $rateUpdate . " `is_blocked` = '" . $data->isBlocked . "',
                        `updated_at` = '" . date('Y-m-d H:i:s') . "' where id_hotel = '" . $data->hotelId . "' 
                        AND id_room = '" . $data->roomId . "' AND date = '" . $dates[$i] . "'";
                    } else {
                        if($data->roomRate != ''){
                            $head = "`new_price`,";
                            $body = "'" . $data->roomRate . "',";
                        }
                        $query = "INSERT INTO `pm_room_new_stock_rate`(`id_hotel`, `id_room`, ".$head." `is_blocked`, `date`) 
                            VALUES ('" . $data->hotelId . "', '" . $data->roomId . "', ".$body." '" . $data->isBlocked . "', '" . $dates[$i] . "')";
                    }
                }else if (isset($data->roomDiscRate)) {
                    if ($check !== false && $db->last_row_count() > 0) {
                        $rateUpdate = '';
                        if($data->roomDiscRate != ''){
                            $rateUpdate = "`new_disc_price`= '" . $data->roomDiscRate . "', ";
                        }
                        $query = "UPDATE `pm_room_new_stock_rate` SET " . $rateUpdate . " `is_blocked` = '" . $data->isBlocked . "',
                        `updated_at` = '" . date('Y-m-d H:i:s') . "' where id_hotel = '" . $data->hotelId . "' 
                        AND id_room = '" . $data->roomId . "' AND date = '" . $dates[$i] . "'";
                    } else {
                        if($data->roomDiscRate != ''){
                            $head = "`new_disc_price`,";
                            $body = "'" . $data->roomDiscRate . "',";
                        }
                        $query = "INSERT INTO `pm_room_new_stock_rate`(`id_hotel`, `id_room`, ".$head." `is_blocked`, `date`) 
                            VALUES ('" . $data->hotelId . "', '" . $data->roomId . "', ".$body." '" . $data->isBlocked . "', '" . $dates[$i] . "')";
                    }
                }
                $db->query($query);
            }
        }
        $json['success'] = TRUE;
        $json['html'] = $html;
        print json_encode($json);
    }
}
