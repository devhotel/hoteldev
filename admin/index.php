<?php
define("ADMIN", true);
require_once("../common/lib.php");
require_once("../common/define.php");
define("TITLE_ELEMENT", $texts['DASHBOARD']);
$csrf_token = get_token('list');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
} elseif ($_SESSION['admin']['type'] == "registered") {
    unset($_SESSION['admin']);
    $_SESSION['msg_error'][] = "Access denied.";
    header("Location: login.php");
    exit();
}
//echo "ok";
require_once("includes/fn_module.php");
// echo "okkk";
// die;
?>
<!DOCTYPE html>

<head>
    <?php include("includes/inc_header_common.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <script>
        $(function() {
            // $('#from_picker, #start_picker').datepicker({
            //     dateFormat: 'dd/mm/yy',
            //     //minDate: 0,
            //     onClose: function(selectedDate, instance) {
            //         if (selectedDate != '') {
            //             var relPicker = $('#' + $(this).attr('rel'));
            //             var date2 = $('#from_picker').datepicker('getDate');
            //             date2.setDate(date2.getDate());
            //             $('#to_picker').datepicker('setDate', date2);
            //         }
            //     }
            // });
            // $('#to_picker, #end_picker').datepicker({
            //     dateFormat: 'dd/mm/yy',
            //     defaultDate: '+1w',
            //     onClose: function(selectedDate) {
            //         var relPicker = $('#' + $(this).attr('rel'));
            //         relPicker.datepicker('option', 'maxDate', selectedDate);
            //     }
            // });
            <?php
            if (isset($field_notice) && !empty($field_notice))
                foreach ($field_notice as $field => $notice) echo '$(\'.field-notice[rel="' . $field . '"]\').html(\'' . $notice . '\').fadeIn(\'slow\').parent().addClass(\'alert alert-danger\');' . "\n"; ?>
        });
    </script>
</head>

<body class="dashboard_mainpage">
    <div id="wrapper">
        <?php include("includes/inc_top.php"); ?>
        <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h1><i class="fas fa-fw fa-tachometer-alt"></i><?php echo $texts['DASHBOARD']; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_SESSION['admin'])) {
                $user_id = $_SESSION['admin']['id'];
            } else {
                $user_id = 0;
            }
            ?>
            <div class="container-fluid">
                <div class="alert-container">
                    <div class="alert alert-success alert-dismissable"></div>
                    <div class="alert alert-warning alert-dismissable"></div>
                    <div class="alert alert-danger alert-dismissable"></div>
                </div>
                <?php
                $start_date = '';
                $end_date = '';
                $id_room = '';
                $price = '';
                $num_rooms = 1;
                $field_notice = array();
                $id_hotel = 0;
                $hotel_ids = 0;
                $all_rooms = array();
                if (isset($_POST['id_hotel'])) {
                    $id_hotel = @$_POST['id_hotel'];
                }
                $id_destination = 0;

                $losnid = array();
                if (isset($_POST['id_destination'])) {
                    $id_destination = @$_POST['id_destination'];
                    if (is_numeric($_POST['id_destination']) && $_POST['id_destination'] > 0) {

                        $result_hotels = $db->query('SELECT id, title FROM pm_hotel WHERE  id_destination= ' . $_POST['id_destination'] . '  AND lang = ' . DEFAULT_LANG);
                        if ($result_hotels !== false && $db->last_row_count() > 0) {
                            foreach ($result_hotels as $key => $hotel) {
                                $losnid[] = $hotel['id'];
                            }
                        }
                    }
                }
                $book_ids = array();
                $occupancy = 0;
                $remain = 0;
                $ard = 0;
                $revpar = 0;
                $aor = 0;
                $avhotel = 0;
                $from_time = time();
                $to_time = time() + (3600);

                $from_date = gmdate('d/m/Y', $from_time);
                $to_date = gmdate('d/m/Y', $to_time);

                if (isset($_POST['from_date'])) $from_date = htmlentities($_POST['from_date'], ENT_QUOTES, 'UTF-8');
                if (isset($_POST['to_date'])) $to_date = htmlentities($_POST['to_date'], ENT_QUOTES, 'UTF-8');

                if ($from_date == '') $field_notice['from_date'] = $texts['REQUIRED_FIELD'];
                else {
                    $time = explode('/', $from_date);
                    if (count($time) == 3) $time = gm_strtotime($time[2] . '-' . $time[1] . '-' . $time[0] . ' 00:00:00');
                    if (!is_numeric($time)) $field_notice['from_date'] = $texts['REQUIRED_FIELD'];
                    else $from_time = $time;
                }
                if ($to_date == '') $field_notice['to_date'] = $texts['REQUIRED_FIELD'];
                else {
                    $time = explode('/', $to_date);
                    if (count($time) == 3) $time = gm_strtotime($time[2] . '-' . $time[1] . '-' . $time[0] . ' 00:00:00');
                    if (!is_numeric($time)) $field_notice['to_date'] = $texts['REQUIRED_FIELD'];
                    else $to_time = $time;
                }
                ?>

                <div class="project-tab" id="dashboard">
                    <!-- Nav tabs -->
                    <!--  <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Dashboard</a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Booking</a></li>
                     </ul> -->

                    <!-- Tab panes -->
                    <?php //echo httpPost('9475359786','this test message'); 
                    ?>
                    <div class="tab-content">
                        <div role="tabpanel-1" class="tab-panel " id="home1">
                            <form method="post" action="index.php">
                                <div class="dash_form">
                                    <div class="form-group dashfg-1">
                                        <label class="sr-only" for="from"></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><?php echo $texts['DATE']; ?></div>
                                            <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" required value="<?php echo $from_date; ?>">
                                        </div>
                                        <div class="field-notice" rel="from_date"></div>
                                    </div>
                                    <input type="hidden" id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                    <div class="form-group dashfg-2">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="booklabel"> Destination </span></div>
                                            <?php
                                            if (in_array($_SESSION['admin']['type'], array('hotel', 'manager'))) {
                                                //$result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['admin']['id'].', users) AND lang = '.LANG_ID); 
                                                $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = ' . LANG_ID);
                                            } else {
                                                $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = ' . LANG_ID);
                                            }
                                            ?>
                                            <select class="form-control" name="id_destination" id="id_destination">
                                                <option value="0">All</option>
                                                <?php
                                                foreach ($result_location as $key => $location) {
                                                    if ($id_destination > 0 && $location['id'] == $id_destination) {
                                                        $select = 'selected="selected"';
                                                    } else {
                                                        $select = '';
                                                    }
                                                    echo '<option value="' . $location['id'] . '" ' . $select . '>' . $location['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="field-notice" rel="id_hotel"></div>
                                    </div>
                                    <div class="form-group dashfg-3">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="booklabel"> Hotels </span></div>
                                            <?php
                                            if (in_array($_SESSION['admin']['type'], array('hotel', 'manager'))) {
                                                $sql = 'SELECT * FROM pm_hotel WHERE checked = 1';
                                                if ($id_destination > 0) {
                                                    $sql .= ' AND id_destination=' . $id_destination;
                                                }
                                                $sql .= ' AND  FIND_IN_SET(' . $_SESSION['admin']['id'] . ', users) AND lang = ' . LANG_ID;
                                            } else {
                                                $sql = 'SELECT * FROM pm_hotel WHERE checked = 1 AND  lang = ' . LANG_ID;
                                                if ($id_destination > 0) {
                                                    $sql .= ' AND id_destination=' . $id_destination;
                                                }
                                            }
                                            $result_hotels = $db->query($sql);
                                            ?>
                                            <select class="form-control" name="id_hotel" id="id_hotel">
                                                <option value="0">All</option>
                                                <?php
                                                foreach ($result_hotels as $key => $hotel) {
                                                    if ($id_hotel > 0 && $hotel['id'] == $id_hotel) {
                                                        $select = 'selected="selected"';
                                                    } else {
                                                        $select = '';
                                                    }
                                                    echo '<option value="' . $hotel['id'] . '" ' . $select . '>' . $hotel['title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="field-notice" rel="id_hotel"></div>
                                    </div>
                                    <div class="form-group dashfg-4">
                                        <button type="submit" class="btn btn-default" name="change_date">GO</button>
                                    </div>
                                </div>
                            </form>

                            <?php

                            if ($from_time) {

                                if (($to_time - $from_time + 86400) > (86400 * 31)) $to_time = $from_time + (86400 * 30);
                                $width = (($to_time - $from_time + 86400) / 86400) * 50;

                                $time_1d_before = $from_time - 86400;
                                $time_1d_before = gm_strtotime(gmdate('Y', $time_1d_before) . '-' . gmdate('n', $time_1d_before) . '-' . gmdate('j', $time_1d_before) . ' 00:00:00');

                                $time_1d_after = $to_time + 86400;
                                $time_1d_after = gm_strtotime(gmdate('Y', $time_1d_after) . '-' . gmdate('n', $time_1d_after) . '-' . gmdate('j', $time_1d_after) . ' 00:00:00');

                                $from_time = gm_strtotime(gmdate('Y', $from_time) . '-' . gmdate('n', $from_time) . '-' . gmdate('j', $from_time) . ' 00:00:00');
                                $to_time = gm_strtotime(gmdate('Y', $to_time) . '-' . gmdate('n', $to_time) . '-' . gmdate('j', $to_time) . ' 00:00:00');

                                $today = gm_strtotime(gmdate('Y') . '-' . gmdate('n') . '-' . gmdate('j') . ' 00:00:00');

                                $room_id = 0;
                                $total_check_in = 0;
                                $total_checked_in = 0;
                                $total_check_out = 0;
                                $total_checked_out = 0;
                                $bookig_gross_amount = 0;
                                $bookig_total_amount = 0;
                                $checked_in_rows = array();
                                $checked_out_rows = array();


                                $result_book = $db->prepare('
                                        SELECT b.id as bookid, total, status, from_date, to_date, firstname, lastname, total, checked_in, checked_out, source
                                        FROM pm_booking as b, pm_booking_room as br
                                        WHERE
                                            br.id_booking = b.id
                                            AND (status = 4 OR (status = 1 AND (add_date > ' . (time() - 900) . ' OR payment_option IN(\'arrival\',\'check\'))))
                                            AND from_date <= ' . $to_time . '
                                            AND to_date >= ' . $time_1d_before . '
                                            AND id_room = :room_id
                                        ORDER BY bookid');
                                $result_book->bindParam(':room_id', $room_id);


                                $result_book_row = $db->prepare('
                                        SELECT * FROM pm_booking AS pb 
                                        WHERE 
                                            pb.add_date <= ' . $to_time . '
                                            AND pb.add_date > ' . $time_1d_before . '
                                            AND pb.status !=2
                                            AND pb.id_hotel = :hotel_id
                                            ORDER BY id');
                                $result_book_row->bindParam(':hotel_id', $id_hotels);

                                $result_closing = $db->prepare('
                                        SELECT stock, from_date, to_date
                                        FROM pm_room_closing
                                        WHERE
                                            from_date <= ' . $to_time . '
                                            AND to_date >= ' . $time_1d_before . '
                                            AND id_room = :room_id
                                        ORDER BY from_date');
                                $result_closing->bindParam(':room_id', $room_id);

                                $date = 0;
                                $day = '(^|,)0(,|$)';
                                $result_rate = $db->prepare('
                                    SELECT DISTINCT(price), r.id as rate_id, start_date, end_date
                                    FROM pm_rate as r, pm_package as p
                                    WHERE id_package = p.id
                                        AND days REGEXP :day
                                        AND id_room = :room_id
                                        AND start_date <= :date AND end_date >= :date
                                    ORDER BY price DESC
                                    LIMIT 1');
                                $result_rate->bindParam(':room_id', $room_id);
                                $result_rate->bindParam(':date', $date);
                                $result_rate->bindParam(':day', $day);

                                $query_room = '
                                         SELECT DISTINCT(r.id) as room_id, id_hotel, r.title as room_title, stock, price, start_lock, end_lock
                                         FROM pm_room as r
                                         WHERE r.checked = 1
                                            AND r.lang = ' . DEFAULT_LANG;

                                if ($_SESSION['admin']['type'] == 'hotel') $query_room .= ' AND users REGEXP \'[[:<:]]' . $_SESSION['admin']['id'] . '[[:>:]]\'';
                                if ($id_hotel > 0) {
                                    $query_room .= ' AND id_hotel REGEXP \'[[:<:]]' . $id_hotel . '[[:>:]]\'';
                                } elseif ($id_destination > 0) {
                                    $query_room .= ' AND id_hotel IN(' . implode(',', $losnid) . ') ';
                                }

                                $query_room .= ' ORDER BY room_title';
                                $result_room = $db->query($query_room);
                                if ($result_room !== false) {

                                    $rooms = array();
                                    $bookings = array();
                                    $closing = array();
                                    $bookId = '';
                                    $oldBookId = '';
                                    foreach ($result_room as $j => $row) {
                                        if ($hotel_ids == 0) {
                                            $hotel_ids = $row['id_hotel'];
                                        }
                                        $room_id = $row['room_id'];
                                        $id_hotels = $row['id_hotel'];
                                        $room_title = $row['room_title'];
                                        $stock = $row['stock'];
                                        $min_price = $row['price'];
                                        $start_lock = $row['start_lock'];
                                        $end_lock = $row['end_lock'];

                                        $rooms[$room_id] = $row;

                                        $max_n = $stock - 1;

                                        if ($result_closing->execute() !== false) {
                                            foreach ($result_closing as $i => $row) {
                                                $start_date = $row['from_date'];
                                                $end_date = $row['to_date'];
                                                $stock = $row['stock'];

                                                $start_date = gm_strtotime(gmdate('Y', $start_date) . '-' . gmdate('n', $start_date) . '-' . gmdate('j', $start_date) . ' 00:00:00');
                                                $end_date = gm_strtotime(gmdate('Y', $end_date) . '-' . gmdate('n', $end_date) . '-' . gmdate('j', $end_date) . ' 00:00:00');

                                                $start = ($start_date < $time_1d_before) ? $time_1d_before : $start_date;
                                                $end = ($end_date > $time_1d_after) ? $time_1d_after : $end_date;

                                                for ($s = 0; $s < $stock; $s++) {
                                                    $n = 0;
                                                    for ($date = $start; $date < $end; $date += 86400) {

                                                        $k = null;
                                                        $c = 0;
                                                        while (is_null($k)) {
                                                            if (!isset($closing[$room_id][$date][$c])) $k = $c;
                                                            else $c++;
                                                        }
                                                        if ($c > $n) $n = $c;
                                                    }
                                                    for ($date = $start; $date < $end; $date += 86400)
                                                        $closing[$room_id][$date][$n] = $row;

                                                    if ($n > $max_n) $max_n = $n;
                                                }
                                            }
                                        }





                                        if ($result_book->execute() !== false) {
                                            foreach ($result_book as $i => $row) {
                                                $bookId = $row['bookid'];
                                                //var_dump($row);
                                                // echo '<pre>';
                                                // print_r($row);
                                                // die;
                                                // $row['checked_in'];
                                                // $row['checked_out'];
                                                $start_date = $row['from_date'];
                                                $end_date = $row['to_date'];

                                                if ($row['source'] == 'app') {
                                                    //$start_date += 19800;
                                                    //$end_date += 19800;
                                                }
                                                // echo $from_time;
                                                // die;
                                                //$start_date = gm_strtotime(gmdate('Y', $start_date) . '-' . gmdate('n', $start_date) . '-' . gmdate('j', $start_date) . ' 00:00:00');
                                                //$end_date = gm_strtotime(gmdate('Y', $end_date) . '-' . gmdate('n', $end_date) . '-' . gmdate('j', $end_date) . ' 00:00:00');
                                                //echo $start_date . '  ' . $end_date;
                                                // echo $from_time . ' ' . $end_date;
                                                // die;
                                                $start = ($start_date < $time_1d_before) ? $time_1d_before : $start_date;
                                                $end = ($end_date > $time_1d_after) ? $time_1d_after : $end_date;
                                                if ($from_time == $start_date) {
                                                    $total_check_in++;
                                                    $checked_in_rows[] = $row;
                                                    if ($row['checked_in'] == "in") {
                                                        $total_checked_in++;
                                                    }
                                                }
                                                //echo $from_time . ' == ' . $end_date . ' == ' . $row['bookid'] . '<br/>';
                                                //echo  gmdate("Y-m-d H:i:s", $from_time) . ' == ' . gmdate("Y-m-d H:i:s", $end_date)  . ' == ' . $row['bookid'] . '<br/>';
                                                if ($bookId != $oldBookId) {
                                                    if ($from_time == $end_date) {
                                                        //echo $from_time . ' == ' . $end_date . ' == ' . $row['bookid'] . '<br/>';
                                                        $total_check_out++;
                                                        $checked_out_rows[] = $row;
                                                        if ($row['checked_out'] == "out") {
                                                            $total_checked_out++;
                                                        }
                                                    }
                                                }
                                                $n = 0;
                                                for ($date = $start; $date < $end; $date += 86400) {

                                                    $k = null;
                                                    $c = 0;
                                                    while (is_null($k)) {
                                                        if (!isset($bookings[$room_id][$date][$c]) && !isset($closing[$room_id][$date][$c])) $k = $c;
                                                        else $c++;
                                                    }
                                                    if ($c > $n) $n = $c;
                                                }
                                                for ($date = $start; $date < $end; $date += 86400)
                                                    $bookings[$room_id][$date][$n] = $row;

                                                if ($n > $max_n) $max_n = $n;
                                                $oldBookId = $bookId;
                                            }
                                        }
                                        $rooms[$room_id]['n'] = $max_n;
                                        //var_dump($rooms);
                                        //$all_rooms[$room_id]=$rooms[$room_id];
                                    }
                                    $stock = array_sum(array_column($rooms, 'stock'));
                                    $prev_date = $time_1d_before;
                                    for ($date = $from_time; $date <= $to_time; $date += 86400) {

                                        $checkin = 0;
                                        $checkout = 0;
                                        $booked = 0;

                                        $date = gm_strtotime(gmdate('Y', $date) . '-' . gmdate('n', $date) . '-' . gmdate('j', $date) . ' 00:00:00');

                                        foreach ($bookings as $id_room => $dates) {

                                            if (isset($dates[$date])) {
                                                foreach ($dates[$date] as $n => $booking)
                                                    if ($booking['from_date'] == $date) $checkin++;
                                                $booked += ($n + 1 < $rooms[$id_room]['stock']) ? $n + 1 : $rooms[$id_room]['stock'];
                                            }
                                            if (isset($dates[$prev_date])) {
                                                foreach ($dates[$prev_date] as $n => $booking)
                                                    if ($booking['to_date'] == $date) $checkout++;
                                            }
                                        }
                                        $occupancy = ($stock > 0) ? round($booked * 100 / $stock, 2) : 0;
                                        $d = gmdate('N', $date);
                                        $prev_date = $date;
                                    }
                                    $remain =  0;
                                    $total_room_revn =  0;
                                    $total_room_occu =  0;
                                    foreach ($rooms as $room_id => $row) {
                                        $room_title = $row['room_title'];
                                        $hotel_id = $row['id_hotel'];
                                        $stock = $row['stock'];
                                        $min_price = $row['price'];
                                        $start_lock = $row['start_lock'];
                                        $end_lock = $row['end_lock'];
                                        $max_n = $row['n'];
                                        $all_rooms[$room_id] = $row;


                                        for ($date = $from_time; $date <= $to_time; $date += 86400) {

                                            $d = gmdate('N', $date);
                                            $day = '(^|,)' . $d . '(,|$)';

                                            // price
                                            $price = 0;
                                            if ($result_rate->execute() !== false && $db->last_row_count() > 0) {
                                                $row = $result_rate->fetch();
                                                $price = $row['price'];
                                            }

                                            // remaining rooms
                                            //$remain = $stock;
                                            $all_rooms[$room_id][$date]['main_stock'] = $stock;
                                            if (isset($bookings[$room_id][$date])) {
                                                $num_bookings = count($bookings[$room_id][$date]);
                                                $all_rooms[$room_id][$date]['main_stock'] = $stock;
                                                $all_rooms[$room_id][$date]['remain_stock'] = ($stock <= $num_bookings) ? 0 : $stock - $num_bookings;
                                                $remain += ($stock <= $num_bookings) ? 0 : $stock - $num_bookings;
                                                $total_room_occu += intval($num_bookings);
                                            } else {
                                                $all_rooms[$room_id][$date]['remain_stock'] = $stock;
                                                $remain += $stock;
                                            }
                                        }

                                        for ($n = 0; $n <= $max_n; $n++) {
                                            $prev_date = $time_1d_before;
                                            for ($date = $from_time; $date <= $to_time; $date += 86400) {
                                                $date = gm_strtotime(gmdate('Y', $date) . '-' . gmdate('n', $date) . '-' . gmdate('j', $date) . ' 00:00:00');
                                                $day = '(^|,)' . gmdate('N', $date) . '(,|$)';
                                                $result_rate->execute();

                                                $start_date = $date;
                                                $end_date = gm_strtotime(gmdate('Y-m-d H:i:s', $start_date) . ' + 1 day');

                                                // Is there a booking for today and the previous day
                                                $prev_booked = (isset($bookings[$room_id][$prev_date][$n]));
                                                $is_booked = (isset($bookings[$room_id][$date][$n]));

                                                // Is there a closed room for today and the previous day
                                                $prev_closed = (isset($closing[$room_id][$prev_date][$n]));
                                                $is_closed = (isset($closing[$room_id][$date][$n]));

                                                $class = '';
                                                $prev_status = '';
                                                $status = '';

                                                if ($is_booked) {
                                                    //echo formatPrice($bookings[$room_id][$date][$n]['total']);
                                                    if (!in_array($bookings[$room_id][$date][$n]['bookid'], $book_ids)) {
                                                        $book_ids[] = $bookings[$room_id][$date][$n]['bookid'];
                                                        $total_room_revn += intval($bookings[$room_id][$date][$n]['total']);
                                                        $ard = ($total_room_revn > 0) ? round($total_room_revn / $total_room_occu, 2) : 0;
                                                        $revpar = ($total_room_revn > 0) ? round($total_room_revn / $stock, 2) : 0;
                                                        $aor = ($total_room_occu > 0) ? round($total_room_occu / $remain, 2) : 100;
                                                    }
                                                }
                                                $prev_date = $date;
                                            }
                                        }
                                    }
                                } ?>
                            <?php
                            } ?>
                            <div class="row">
                                <div class="dashboard-entry dash-grd1 ">
                                    <a id='kpi1' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" onclick="kpi_url_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <div class="huge"><?php echo $remain; ?></div>
                                                    <h3 class="mt0">No. of Rooms available</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dashboard-entry dash-grd2">
                                    <a id='kpi2' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" onclick="kpi_url_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <div class="huge"><?php echo $occupancy; ?>%</div>
                                                    <h3 class="mt0">Occupancy Rate</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dashboard-entry dash-grd3" style="display:none;">
                                    <a id='kpi3' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" onclick="kpi_url_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <div class="huge"><?php echo $aor; ?>%</div>
                                                    <h3 class="mt0">AVG. OCCUPANCY RATE</h3>

                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dashboard-entry dash-grd4">
                                    <a id='kpi4' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" onclick="kpi_url_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">

                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <div class="huge"><?php echo formatPrice($ard * CURRENCY_RATE); ?></div>
                                                    <h3 class="mt0">Average Daily Rate</h3>
                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dashboard-entry dash-grd5">
                                    <a id='kpi5' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" onclick="kpi_url_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <div class="huge"><?php echo formatPrice($revpar * CURRENCY_RATE); ?></div>
                                                    <h3 class="mt0">REVPAR</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dashboard-entry dash-grd6">
                                    <a id='kpi6' data-url="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/hotel/index.php?view=list" onclick="kpi_hotel_redirect(this.id);">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="huge_pic"><i class="fas fa-fw fa-calendar-check"></i></div>
                                                <div class="con-item">
                                                    <?php
                                                    if (in_array($_SESSION['admin']['type'], array('hotel', 'manager'))) {

                                                        $sql = 'SELECT * FROM pm_hotel WHERE checked = 1';
                                                        if ($id_destination > 0) {
                                                            $sql .= ' AND id_destination=' . $id_destination;
                                                        }
                                                        $sql .= ' AND  FIND_IN_SET(' . $_SESSION['admin']['id'] . ', users) AND lang = ' . LANG_ID;
                                                    } else {
                                                        $sql = 'SELECT * FROM pm_hotel WHERE checked = 1 AND  lang = ' . LANG_ID;
                                                        if ($id_destination > 0) {
                                                            $sql .= ' AND id_destination=' . $id_destination;
                                                        }
                                                    }
                                                    $result_hotels = $db->query($sql);
                                                    if ($id_hotel > 0) {
                                                        $avhotel++;
                                                    } else {
                                                        foreach ($result_hotels as $key => $hotel) {
                                                            $avhotel++;
                                                        }
                                                    }
                                                    ?>
                                                    <div class="huge"><?php echo $avhotel; ?></div>
                                                    <h3 class="mt0">No. of Hotels</h3>

                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php $from_time;
                        $hotel_ids;
                        $today = gm_strtotime(gmdate('Y') . '-' . gmdate('n') . '-' . gmdate('j') . ' 00:00:00');
                        if ($from_time == $today) {
                            $sdate = $today;
                            $date_text = 'Today';
                        } else {
                            $sdate = $from_time;
                            $date_text = '(' . date('M d', $sdate) . ')';
                        }
                        //echo $total_check_in.'|';
                        //echo $total_checked_in.'|';
                        //echo $total_check_out.'|';
                        //echo $total_checked_out.'|';
                        $tmp_sql_date = explode('/', $from_date);
                        $sql_from_date = strtotime($tmp_sql_date['2'] . '-' . $tmp_sql_date['1'] . '-' . $tmp_sql_date['0'] . '00:00:00');
                        $sql_to_date = strtotime($tmp_sql_date['2'] . '-' . $tmp_sql_date['1'] . '-' . $tmp_sql_date['0'] . '23:59:59');
                        $_SESSION['session_from_date'] = $sql_from_date;
                        $_SESSION['session_to_date'] = $sql_to_date;
                        $_SESSION['session_id_destination'] = $id_destination;
                        $_SESSION['session_id_hotel'] = $id_hotel;
                        ?>
                        <div role="tabpanel-1" class="tab-panel" id="profile1">

                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="dsh_nw_left">
                                                    <h2>Booking's Status - <?php echo $date_text; ?></h2>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>Today</th>
                                                                <th>Tomorrow</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2"> <a data-toggle="modal" data-target="#CheckinToday"><i class="fas fa-hand-point-right"></i></a> Check-ins Left </td>
                                                                <td><?php echo $total_checked_in; ?> / <?php echo $total_check_in; ?></td>
                                                                <!--  <td><?php //echo $total_arrival_tomorrow['total_id']; 
                                                                            ?></td> -->
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><a data-toggle="modal" data-target="#CheckoutToday"><i class="fas fa-hand-point-left"></i></a> Check-outs Left</td>
                                                                <td><?php echo $total_checked_out; ?> / <?php echo $total_check_out; ?></td>
                                                                <!-- <td><?php //echo $total_deprature_tomorrow['total_id'];
                                                                            ?></td>  -->
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <?php
                                                // try{
                                                //     if($user_id  > 1){
                                                //         $query_total = "SELECT SUM(pb.total) as total_amount FROM pm_booking AS pb WHERE FIND_IN_SET(".$user_id.", pb.users) AND pb.add_date <= ".strtotime("now")." AND pb.add_date > ".((strtotime("now"))-86000)." AND pb.status !=2";
                                                //     }else{
                                                //         $query_total = "SELECT SUM(pb.total) as total_amount FROM pm_booking AS pb WHERE pb.add_date <= NOW() AND pb.add_date > ".((strtotime("now"))-86000)." AND pb.status !=2";
                                                //     }
                                                //     $query_total = $db->query($query_total);
                                                //     $total_amount = $query_total->fetch();

                                                // }catch(PDOException  $e ){
                                                //     echo "Error: ".$e;
                                                // }
                                                ?>
                                                <?php
                                                try {
                                                    $condition = '';
                                                    if ($user_id  > 1) {
                                                        $condition .= " AND FIND_IN_SET(" . $user_id . ", pb.users)";
                                                    }
                                                    if ($id_destination != 0) {
                                                        $condition .= " AND pb.id_destination = '" . $id_destination . "'";
                                                    }
                                                    if ($id_hotel != 0) {
                                                        $condition .= " AND pb.id_hotel = '" . $id_hotel . "'";
                                                    }
                                                    $newQuery = "SELECT SUM(pb.total) total_amount, SUM(pbp.amount) paid_amount FROM pm_booking_payment pbp
                                            LEFT JOIN pm_booking pb ON pb.id = pbp.id_booking 
                                            WHERE pb.add_date <= " . $sql_to_date . " AND 
                                            pb.add_date >= " . $sql_from_date . " AND 
                                            pb.status != 2 " . $condition . ";";
                                                    $g_query_total = $db->query($newQuery);
                                                    $g_total_amount = $g_query_total->fetch();
                                                } catch (PDOException  $e) {
                                                    echo "Error: " . $e;
                                                }
                                                ?>
                                                <div class="dsh_nw_left">
                                                    <h2>Booking Value - <?= $date_text ?> </h2>
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td><i class="fas fa-hand-point-right"></i> Booking Amount Recived</td>
                                                                <td><?php echo formatPrice($g_total_amount['paid_amount'] * CURRENCY_RATE); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><i class="fas fa-hand-point-right"></i> Booking Amount Pending</td>
                                                                <td><?php echo formatPrice(($g_total_amount['total_amount'] - $g_total_amount['paid_amount']) * CURRENCY_RATE); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><i class="fas fa-hand-point-right"></i> Total Booking Amount</td>
                                                                <td><?php echo formatPrice($g_total_amount['total_amount'] * CURRENCY_RATE); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--    <span class="bkng_para"><i class="fa fa-briefcase" aria-hidden="true"></i> <?php echo formatPrice($total_amount['total_amount'] * CURRENCY_RATE); ?></span>-->
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                                <div class="dsh_nw_left dash_avl_left">

                                                    <h2>Room availability - <?= $date_text ?></h2>
                                                    <table class="table">
                                                        <tbody>
                                                            <?php
                                                            //var_dump($all_rooms);
                                                            if (!empty($all_rooms)) {
                                                                foreach ($all_rooms as $key => $rroms) {
                                                                    //var_dump($rroms[$today]["main_stock"]);
                                                                    //if($rroms['id_hotel']==$hotel_ids){ 
                                                                    $htitle = db_getFieldValue($db, 'pm_hotel', 'title', $rroms['id_hotel'], $lang = 0);
                                                            ?>
                                                                    <tr>
                                                                        <td><b><?php echo $htitle; ?></b> <?php echo $rroms['room_title']; ?> </td>
                                                                        <td><?php
                                                                            if ($rroms[$sdate]["remain_stock"] > 0) {
                                                                                //echo $rroms[$sdate]["remain_stock"] . ($rroms[$sdate]["remain_stock"]>1?' rooms left':' room left');
                                                                                echo $rroms[$sdate]["remain_stock"];
                                                                            } else {
                                                                                //echo ' No room left';
                                                                            }
                                                                            ?> /
                                                                            <?php echo ($rroms[$sdate]["main_stock"] ? $rroms[$sdate]["main_stock"] : 0); ?> </td>
                                                                    </tr>
                                                            <?php //}
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <!--    <span class="bkng_para"><i class="fa fa-briefcase" aria-hidden="true"></i> <?php echo formatPrice($total_amount['total_amount'] * CURRENCY_RATE); ?></span>-->
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php
                                try {
                                    if ($user_id  > 1) {
                                        $query_website = "SELECT COUNT(pb.id) AS website_count FROM  pm_booking AS pb WHERE FIND_IN_SET(" . $user_id . ", pb.users) AND pb.source = 'website' ORDER BY pb.id DESC";
                                    } else {
                                        $query_website = "SELECT COUNT(pb.id) AS website_count FROM  pm_booking AS pb WHERE pb.source = 'website' ORDER BY pb.id DESC";
                                    }

                                    $query_website = $db->query($query_website);
                                    $website_count = $query_website->fetch();
                                } catch (PDOException  $e) {
                                    echo "Error: " . $e;
                                }
                                try {
                                    if ($user_id  > 1) {
                                        $query_admin = "SELECT COUNT(pb.id) AS admin_count FROM  pm_booking AS pb WHERE FIND_IN_SET(" . $user_id . ", pb.users) AND pb.source = 'admin' ORDER BY pb.id DESC";
                                    } else {
                                        $query_admin = "SELECT COUNT(pb.id) AS admin_count FROM  pm_booking AS pb WHERE pb.source = 'admin' ORDER BY pb.id DESC";
                                    }

                                    $query_admin = $db->query($query_admin);
                                    $admin_count = $query_admin->fetch();
                                } catch (PDOException  $e) {
                                    echo "Error: " . $e;
                                }

                                $dataPoints2 = array(
                                    array("values" => array($website_count['website_count'])),
                                    array("values" => array($admin_count['admin_count'])),
                                );

                                ?>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                        <div class="maindsh_rht_box">
                                            <h2>Booking Source</h2>
                                            <div class="maindsh_rht_box_inner">
                                                <a href="#"><span></span>Online (<?php echo $website_count['website_count']; ?>)</a>
                                                <a href="#"><span></span>Offline (<?php echo $admin_count['admin_count']; ?>)</a>

                                                <div id='myChart'></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    try {
                                        if ($user_id  > 1) {
                                            $query_booking = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE FIND_IN_SET(" . $user_id . ", pb.users) ORDER BY pb.id DESC";
                                        } else {
                                            $query_booking = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id ORDER BY pb.id DESC";
                                        }

                                        $query_booking = $db->query($query_booking);
                                        $all_booking = $query_booking->fetchAll();
                                    } catch (PDOException  $e) {
                                        echo "Error: " . $e;
                                    }
                                    $mon = 0;
                                    $tue = 0;
                                    $wed = 0;
                                    $thu = 0;
                                    $fri = 0;
                                    $sat = 0;
                                    $sun = 0;


                                    foreach ($all_booking as $ab) {
                                        $day = date('D', $ab['add_date']);
                                        switch ($day) {
                                            case 'Mon':
                                                $mon += $ab['total'];
                                                break;
                                            case 'Tue':
                                                $tue += $ab['total'];
                                                break;
                                            case 'Wed':
                                                $wed += $ab['total'];
                                                break;
                                            case 'Thu':
                                                $thu += $ab['total'];
                                                break;
                                            case 'Fri':
                                                $fri += $ab['total'];
                                                break;
                                            case 'Sat':
                                                $sat += $ab['total'];
                                                break;
                                            case 'Sun':
                                                $sun += $ab['total'];
                                                break;
                                        }
                                    }
                                    $dataPoints1 = array(
                                        $mon, $tue, $wed, $thu, $fri, $sat, $sun
                                    );
                                    ?> <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                        <div class="maindsh_rht_box">
                                            <h2>Bookings</h2>
                                            <div class="dsh_barchart">
                                                <div id="chartContainer"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade latest_booking" id="LatestBooking" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Latest Bookings</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- .............Latest Booking................ -->
                                                    <?php try {
                                                        if ($user_id  > 1) {
                                                            $query_latest_booking = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE FIND_IN_SET(" . $user_id . ", users) GROUP BY pb.id  ORDER BY pb.id DESC LIMIT 10";
                                                        } else {
                                                            $query_latest_booking = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id GROUP BY pb.id  ORDER BY pb.id DESC LIMIT 10";
                                                        }

                                                        $q_latest_booking = $db->query($query_latest_booking);
                                                        $latest_booking = $q_latest_booking->fetchAll();
                                                    } catch (PDOException  $e) {
                                                        echo "Error: " . $e;
                                                    }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="dshb_booking_ltst">
                                                            <table class="table" id="ltbooki_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Room</th>
                                                                        <th>Name</th>
                                                                        <th>Check In</th>
                                                                        <th>Check Out</th>
                                                                        <th>Booking Date</th>
                                                                        <th>Booking Status</th>
                                                                        <th>Charges</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (!empty($latest_booking)) {
                                                                        foreach ($latest_booking as $lb) {
                                                                            switch ($lb['status']) {
                                                                                case 1:
                                                                                    $status = '<span class="label label-primary">' . $texts['AWAITING'] . '</span>';
                                                                                    break;
                                                                                case 2:
                                                                                    $status = '<span class="label label-danger">' . $texts['CANCELLED'] . '</span>';
                                                                                    break;
                                                                                case 3:
                                                                                    $status = '<span class="label label-danger">' . $texts['REJECTED_PAYMENT'] . '</span>';
                                                                                    break;
                                                                                case 4:
                                                                                    $status = '<span class="label label-success">' . $texts['PAID'] . '</span>';
                                                                                    break;
                                                                                default:
                                                                                    $status = '<span class="label label-primary">' . $texts['AWAITING'] . '</span>';
                                                                                    break;
                                                                            }
                                                                    ?>
                                                                            <tr>
                                                                                <td><?php echo $lb['id_booking']; ?></td>
                                                                                <td><?php echo $lb['title']; ?></td>
                                                                                <td><?php echo $lb['firstname'] . ' ' . $lb['lastname']; ?></td>
                                                                                <td><?php echo date('d-m-Y', $lb['from_date']); ?></td>
                                                                                <td><?php echo date('d-m-Y', $lb['to_date']); ?></td>
                                                                                <td><?php echo date('d-m-Y', $lb['add_date']); ?></td>
                                                                                <td><?php echo $status; ?></td>
                                                                                <td><?php echo formatPrice($lb['total'] * CURRENCY_RATE); ?></td>

                                                                            </tr>
                                                                        <?php }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="8" class="text-center">Data Not Available.</td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal fade latest_booking" id="CheckinToday" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Checkin Today</h4>
                                                </div>
                                                <div class="modal-body">

                                                    <!----------------------Arrivals---------------->
                                                    <?php
                                                    try {
                                                        $condition = '';
                                                        if ($user_id > 1) {
                                                            $condition = " AND FIND_IN_SET(" . $user_id . ", users)";
                                                        }
                                                        $query_arrivals = "SELECT * FROM  pm_booking AS pb 
                                                    LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id 
                                                    WHERE pb.from_date <= " . $sql_to_date . " 
                                                    AND pb.from_date >= " . $sql_from_date . " 
                                                    AND pb.status !=2 " . $condition . " GROUP BY pb.id  ORDER BY pb.id DESC ";
                                                        // if($user_id > 1){
                                                        //     $query_arrivals = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE FIND_IN_SET(".$user_id.", users) AND pb.from_date < ".strtotime("now")." AND pb.from_date > ".((strtotime("now"))-86000)." AND pb.status !=2 GROUP BY pb.id  ORDER BY pb.id DESC ";
                                                        // }else{
                                                        //     $query_arrivals = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE pb.from_date < ".strtotime("now")." AND pb.from_date > ".((strtotime("now"))-86000)." AND pb.status !=2 GROUP BY pb.id  ORDER BY pb.id DESC ";
                                                        // }
                                                        $query_arrivals = $db->query($query_arrivals);
                                                        $arrivals = $query_arrivals->fetchAll();
                                                    } catch (PDOException  $e) {
                                                        echo "Error: " . $e;
                                                    }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="dshb_booking_ltst">
                                                            <table class="table" id="CheckinTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Room</th>
                                                                        <th>Name</th>
                                                                        <th>Check Out</th>
                                                                        <th>Booking Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (!empty($arrivals)) {
                                                                        foreach ($arrivals as $ar) {
                                                                            $background = '';
                                                                            if ($ar['checked_in'] == 'in') {
                                                                                $background = '#6aa4d0';
                                                                            }
                                                                    ?>
                                                                            <tr style="background-color: <?= $background ?>;">

                                                                                <td><?php echo $ar['id_booking']; ?></td>
                                                                                <td><?php echo $ar['title']; ?></td>
                                                                                <td><?php echo $ar['firstname'] . ' ' . $ar['lastname']; ?></td>
                                                                                <td><?php echo date('d-m-Y', $ar['to_date']); ?></td>
                                                                                <td><?php echo date('d-m-Y', $ar['add_date']); ?></td>
                                                                            </tr>
                                                                        <?php }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="5" class="text-center">Data Not Available.</td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal fade latest_booking" id="CheckoutToday" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Checkout Today</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!----------------------Departures---------------->
                                                    <?php
                                                    try {
                                                        $condition = '';
                                                        if ($user_id > 1) {
                                                            $condition = " AND FIND_IN_SET(" . $user_id . ", users)";
                                                        }
                                                        $query_departure = "SELECT * FROM  pm_booking AS pb 
                                                        LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id 
                                                        WHERE pb.to_date <= " . $sql_to_date . " 
                                                        AND pb.to_date >= " . $sql_from_date . " 
                                                        AND pb.status !=2 " . $condition . " GROUP BY pb.id  ORDER BY pb.id DESC";
                                                        // if($user_id > 1){
                                                        //     $query_departure = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE FIND_IN_SET(".$user_id.", users) AND pb.to_date < ".strtotime("now")." AND pb.to_date > ".((strtotime("now"))-86000)." AND pb.status !=2 GROUP BY pb.id  ORDER BY pb.id DESC";
                                                        // }else{
                                                        //     $query_departure = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE pb.to_date < ".strtotime("now")." AND pb.to_date > ".((strtotime("now"))-86000)." AND pb.status !=2 GROUP BY pb.id  ORDER BY pb.id DESC";
                                                        // }
                                                        $query_departure = $db->query($query_departure);
                                                        $departure = $query_departure->fetchAll();
                                                    } catch (PDOException  $e) {
                                                        echo "Error: " . $e;
                                                    }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="dshb_booking_ltst">
                                                            <table class="table" id="CheckoutTable">
                                                                <thead>
                                                                    <tr style="background-color: <?= $background ?>;">
                                                                        <th>ID</th>
                                                                        <th>Room</th>
                                                                        <th>Name</th>
                                                                        <th>Check In</th>
                                                                        <th>Booking Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($departure)) { ?>
                                                                        <?php foreach ($departure as $d) {
                                                                            $background = '';
                                                                            //if ($ar['checked_out'] == 'out') {
                                                                            // echo $ar['checked_out'];
                                                                            // die;
                                                                            if ($ar['checked_out'] == 'out') {
                                                                                $background = '#6aa4d0';
                                                                            }
                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $d['id_booking']; ?></td>
                                                                                <td><?php echo $d['title']; ?></td>
                                                                                <td><?php echo $d['firstname'] . ' ' . $d['lastname']; ?></td>
                                                                                <td><?php echo date('d-m-Y', $d['from_date']); ?></td>
                                                                                <td><?php echo date('d-m-Y', $d['add_date']); ?></td>
                                                                            </tr>
                                                                        <?php }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="5" class="text-center">Data Not Available.</td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none;">
                                        <div class="row">




                                            <?php
                                            try {
                                                if ($user_id > 1) {
                                                    $query_stay = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE FIND_IN_SET(" . $user_id . ", users) AND pb.from_date < " . strtotime("now") . " AND pb.to_date > " . strtotime("now") . " AND pb.status !=2 ORDER BY pb.id DESC LIMIT 5";
                                                } else {
                                                    $query_stay = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id WHERE pb.from_date < " . strtotime("now") . " AND pb.to_date > " . (strtotime("now")) . " AND pb.status !=2 ORDER BY pb.id DESC LIMIT 5";
                                                }
                                                $query_stay = $db->query($query_stay);
                                                $stay = $query_stay->fetchAll();
                                            } catch (PDOException  $e) {
                                                echo "Error: " . $e;
                                            }
                                            ?>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="dshb_booking_ltst">
                                                    <h2>Current Stay</h2>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Room</th>
                                                                <th>Name</th>
                                                                <th>Check In</th>
                                                                <th>Check Out</th>
                                                                <th>Booking Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($stay)) { ?>
                                                                <?php foreach ($stay as $s) { ?>
                                                                    <tr>
                                                                        <td><?php echo $s['id_booking']; ?></td>
                                                                        <td><?php echo $s['title']; ?></td>
                                                                        <td><?php echo $s['firstname'] . ' ' . $s['lastname']; ?></td>
                                                                        <td><?php echo date('d-m-Y', $s['from_date']); ?></td>
                                                                        <td><?php echo date('d-m-Y', $s['to_date']); ?></td>
                                                                        <td><?php echo date('d-m-Y', $s['add_date']); ?></td>
                                                                    </tr>
                                                                <?php }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="6" class="text-center">Data Not Available.</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    </div>
    </div>
    </div>

    <form id="dshfiter" name="dshfiter" action="" method="get">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
        <input type="hidden" id="dest_id" name="id_destination" value="">
        <input type="hidden" id="htel_id" name="id_hotel">
        <input type="hidden" id="frmdate" name="from_date">
        <button class="btn btn-default btn-sm" type="submit" id="search" name="search"></button>
    </form>

    <form id="hotelfilter" name="hotelfiter" action="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/hotel/index.php" method="get">
        <input type="hidden" id="view" name="view" value="list">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
        <input type="hidden" id="q_search" name="q_search" value="">

        <input type="hidden" id="hkdest_id" name="id_destination" value="">
        <input type="hidden" id="hid" name="id">
        <input type="hidden" id="checked" name="checked" value="1">
        <button class="btn btn-default btn-sm" type="submit" id="hksearch" name="search"></button>
    </form>

    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script>
        ZC.LICENSE = ["b55b025e438fa8a98e32482b5f768ff5"];
        var myConfig = {
            "type": "pie",
            "plot": {
                "value-box": {
                    "visible": true,
                    "font-size": 12,
                    "placement": "out",
                }
            },
            "series": <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
        };

        zingchart.render({
            id: 'myChart',
            data: myConfig,
            height: 200,
            width: "100%"
        });
    </script>

    <script type="text/javascript">
        /*window.onload = function () {
     
    var chart = new CanvasJS.Chart("chartContainer", {
    	animationEnabled: true,
    	theme: "light2",
    	axisY: {
    		prefix: "<?php echo CURRENCY_SIGN; ?>",
    	},
    	data: [{
    		type: "column",
    		yValueFormatString: "<?php echo CURRENCY_SIGN; ?>#0.##",
    		showInLegend: false,
    		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
    	}]
    });
    chart.render();
     
    }*/
        var chart = Highcharts.chart('chartContainer', {

            title: {
                text: 'Booking Amount'
            },

            xAxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },

            series: [{
                type: 'column',
                colorByPoint: true,
                data: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>,
                showInLegend: false
            }]

        });
    </script>
    <!--<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>-->
    <script>
        $(function() {
            //$("#from_picker").datepicker();
            $('#from_picker, #start_picker').datepicker({
                dateFormat: 'dd/mm/yy',
                //minDate: 0,
                onClose: function(selectedDate, instance) {
                    if (selectedDate != '') {
                        var relPicker = $('#' + $(this).attr('rel'));
                        var date2 = $('#from_picker').datepicker('getDate');
                        date2.setDate(date2.getDate());
                        $('#to_picker').datepicker('setDate', date2);
                    }
                }
            });
        });
        $('#to_picker, #end_picker').datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: '+1w',
            onClose: function(selectedDate) {
                var relPicker = $('#' + $(this).attr('rel'));
                relPicker.datepicker('option', 'maxDate', selectedDate);
            }
        });
        var destination = $('[name="id_destination"]');
        var hotels = $('[name="id_hotel"]');

        function get_hotels_by_destination() {
            var id_location = destination.val();
            hotels.empty();
            $.ajax({
                url: 'get_hotel.php',
                type: 'POST',
                data: {
                    id_location: id_location
                },
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    $.each(data, function(key, entry) {
                        //html += '<option value="${entry.id}">${entry.title}</option>'
                        hotels.append($('<option></option>').attr('value', entry.id).text(entry.title));
                        //hotels.append(html);

                    })
                }
            });
        }
        destination.on('change keyup', function() {
            get_hotels_by_destination();
        });

        var kpi_url_redirect = function(id) {
            var url = $('#' + id).attr('data-url');
            var id_destination = $("#id_destination").val();
            var id_hotel = $("#id_hotel").val();
            var from_picker = $("#from_picker").val();

            $("#dest_id").val(id_destination);
            $("#htel_id").val(id_hotel);
            $("#hid").val(id_hotel);
            $("#frmdate").val(from_picker);
            $("#dshfiter").attr("action", url);
            //alert(url);
            $("#dshfiter").submit();
            //console.log(url);
            //$("#search").click();
            //window.location.href = url;
        }

        var kpi_hotel_redirect = function(id) {
            var url = $('#' + id).attr('data-url');
            var id_destination = $("#id_destination").val();
            var id_hotel = $("#id_hotel").val();
            var from_picker = $("#from_picker").val();
            $("#hkdest_id").val(id_destination);
            if (id_hotel > 0) {
                $("#hid").val(id_hotel);
            } else {
                $("#hid").val('');
            }
            $("#hotelfilter").attr("action", url);
            //alert(url);
            //console.log(url);
            $("#hotelfilter").submit();
            //$("#hksearch").click();
            //window.location.href = url;
        }

        $('#CheckinTable').DataTable({
            "pageLength": 10,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: 'Download',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'all'
                    }
                },
                action: function(e, dt, button, config) {
                    config.filename = 'checked-in-excel-67856235';
                    $.fn.dataTable.ext.buttons.excelHtml5.action(e, dt, button, config);
                }
            }],
        });

        $('#CheckoutTable').DataTable({
            "pageLength": 10,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: 'Download',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'all'
                    }
                },
                action: function(e, dt, button, config) {
                    config.filename = 'checked-out-excel-56856235';
                    $.fn.dataTable.ext.buttons.excelHtml5.action(e, dt, button, config);
                }
            }],
        });
    </script>
    <?php include(SYSBASE . ADMIN_FOLDER . '/modules/default/nav_script.php'); ?>
</body>

</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>