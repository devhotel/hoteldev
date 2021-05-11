<?php
ob_start();
define('ADMIN', true);

define('SYSBASE', str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../../').'/'));

require_once(SYSBASE.'common/lib.php');
require_once(SYSBASE.'common/define.php');

if(!isset($_SESSION['admin'])){
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/login.php');
    exit();
}elseif($_SESSION['admin']['type'] == 'registered'){
    unset($_SESSION['admin']);
    $_SESSION['msg_error'][] = 'Access denied.';
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/login.php');
    exit();
}

if(!isset($_SESSION['redirect'])) $_SESSION['redirect'] = false;

require_once(SYSBASE.ADMIN_FOLDER.'/includes/fn_module.php');

if(in_array('no_access', $permissions) || empty($permissions)){
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/index.php');
    exit();
}

require_once(SYSBASE.ADMIN_FOLDER.'/includes/fn_actions.php');

$_SESSION['module_referer'] = MODULE;
$csrf_token = get_token('list');
                    
$from_time = time();
$to_time = time()+(86400*31);

$from_date = gmdate('d/m/Y', $from_time);
$to_date = gmdate('d/m/Y', $to_time);

if(isset($_POST['from_date'])) $from_date = htmlentities($_POST['from_date'], ENT_QUOTES, 'UTF-8');
if(isset($_POST['to_date'])) $to_date = htmlentities($_POST['to_date'], ENT_QUOTES, 'UTF-8');

if($from_date == '') $field_notice['from_date'] = $texts['REQUIRED_FIELD'];
else{
    $time = explode('/', $from_date);
    if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
    if(!is_numeric($time)) $field_notice['from_date'] = $texts['REQUIRED_FIELD'];
    else $from_time = $time;
}
if($to_date == '') $field_notice['to_date'] = $texts['REQUIRED_FIELD'];
else{
    $time = explode('/', $to_date);
    if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
    if(!is_numeric($time)) $field_notice['to_date'] = $texts['REQUIRED_FIELD'];
    else $to_time = $time;
}

$start_date = '';
$end_date = '';
$id_room = '';
$price = '';
$num_rooms = 1;
$field_notice = array();
$id_hotel = 0;
$status = '';
$payment_mode = '';
  if(isset($_POST['id_hotel'])){
    $id_hotel=@$_POST['id_hotel'];
  }
  if(isset($_POST['status'])){
    $status=@$_POST['status'];
  }
   if(isset($_POST['payment_mode'])){
    $payment_mode=@$_POST['payment_mode'];
  }
if(isset($_POST['add_prices']) && (in_array('add', $permissions) || in_array('all', $permissions))){
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $id_room = $_POST['room_id'];
    $price = $_POST['price'];
    $num_rooms = $_POST['num_rooms'];
    
    if($start_date == '') $field_notice['start_date'] = $texts['REQUIRED_FIELD'];
    else{
        $time = explode('/', $from_date);
        if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
        if(!is_numeric($time)) $field_notice['start_date'] = $texts['REQUIRED_FIELD'];
        else $start_time = $time;
    }
    if($end_date == '') $field_notice['end_date'] = $texts['REQUIRED_FIELD'];
    else{
        $time = explode('/', $end_date);
        if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
        if(!is_numeric($time)) $field_notice['end_date'] = $texts['REQUIRED_FIELD'];
        else $end_time = $time;
    }
    
    if(!is_numeric($id_room)) $field_notice['room_id'] = $texts['REQUIRED_FIELD'];
    if(!is_numeric($price) || $price <= 0) $field_notice['price'] = $texts['REQUIRED_FIELD'];
    if(!is_numeric($num_rooms) || $num_rooms <= 0) $field_notice['num_rooms'] = $texts['REQUIRED_FIELD'];
    
    if(count($field_notice) == 0){
        
        $dst = gmdate('I', $start_time);
        $error = false;
        for($date = $start_time; $date <= $end_time; $date += 86400){

            $start_d = $date;
            $end_d = gm_strtotime(gmdate('Y-m-d H:i:s', $start_d).' + 1 day');
            $data = array();
            $data['stock'] = $num_rooms;
            $data['price'] = number_format($price, 2, '.', '');
            $result_exist = $db->query('SELECT * FROM pm_rate WHERE id_room = '.$id_room.' AND start_date = '.$start_d.' AND end_date = '.$end_d.' LIMIT 1');
            if($result_exist !== false){
                if($db->last_row_count() > 0){
                    $row = $result_exist->fetch();
                    $data['id'] = $row['id'];
                    $result = db_prepareUpgmdate($db, 'pm_rate', $data);
                }else{
                    $data['id_room'] = $id_room;
                    $data['start_date'] = $start_d;
                    $data['end_date'] = $end_d;
                    $data['id_package'] = 2;
                    $data['people'] = 10;
                    $result = db_prepareInsert($db, 'pm_rate', $data);
                }
                if($result->execute() === false){
                    $error = true;
                    break;
                }
            }else{
                $error = true;
                break;
            }
        }
        if($error) $_SESSION['msg_error'][] = 'Fatal error durring database update';
        
    }else $_SESSION['msg_error'][] = $texts['FORM_ERRORS'];
} 
            $delimiter = ",";
                $filename = "booking_report_" . date('Y-m-d') . ".csv";
                $f = fopen('php://memory', 'w');
                $fields = array('ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Hotel/Room', 'From Date', 'To Date', 'Nights', 'Adults', 'Children', 'Amount', 'Discount', 'Exclude Tax', 'Tax', 'Total', 'Payment Method', 'Payment Mode', 'Transaction ID', 'Status');
                fputcsv($f, $fields, $delimiter);
            if($db !== false){
                if(in_array('all', $permissions) || $_SESSION['admin']['type'] == 'hotel'){ 
                    if($to_time > $from_time){
                        
                        if(($to_time-$from_time+86400) > (86400*31)) $to_time = $from_time+(86400*30);
                        $width = (($to_time-$from_time+86400)/86400)*50;
                        
                        $time_1d_before = $from_time-86400;
                        $time_1d_before = gm_strtotime(gmdate('Y', $time_1d_before).'-'.gmdate('n', $time_1d_before).'-'.gmdate('j', $time_1d_before).' 00:00:00');
                        
                        $time_1d_after = $to_time+86400;
                        $time_1d_after = gm_strtotime(gmdate('Y', $time_1d_after).'-'.gmdate('n', $time_1d_after).'-'.gmdate('j', $time_1d_after).' 00:00:00');
                        
                        $from_time = gm_strtotime(gmdate('Y', $from_time).'-'.gmdate('n', $from_time).'-'.gmdate('j', $from_time).' 00:00:00');
                        $to_time = gm_strtotime(gmdate('Y', $to_time).'-'.gmdate('n', $to_time).'-'.gmdate('j', $to_time).' 00:00:00');
                        
                        $today = gm_strtotime(gmdate('Y').'-'.gmdate('n').'-'.gmdate('j').' 00:00:00');
                            
                        $room_id = 0;
                        
                         if($status !=""){
                            $statu_query = ' AND b.status='.$status;
                        }else{
                            $statu_query = '';
                        }
                        
                        if($payment_mode !=""){
                            $apyment_query = ' AND b.payment_mode="'.$payment_mode.'"';
                        }else{
                            $apyment_query = '';
                        }
                       $result_book = $db->prepare('
                            SELECT DISTINCT(b.id) as bookid, status, from_date, to_date, firstname, lastname, total, b.nights, b.adults, b.children, b.amount, b.discount, b.ex_tax, b.tax_amount, br.title, b.email, b.phone, b.trans, b.payment_option, b.payment_mode 
                            FROM pm_booking as b, pm_booking_room as br
                            WHERE
                                br.id_booking = b.id
                                AND from_date <= '.$to_time.'
                                AND to_date >= '.$time_1d_before.$statu_query.$apyment_query.'
                                AND id_room = :room_id
                            ORDER BY bookid');
                        $result_book->bindParam(':room_id', $room_id);
                        
                        
                        $result_closing = $db->prepare('
                            SELECT stock, from_date, to_date
                            FROM pm_room_closing
                            WHERE
                                from_date <= '.$to_time.'
                                AND to_date >= '.$time_1d_before.'
                                AND id_room = :room_id
                            ORDER BY from_date');
                        $result_closing->bindParam(':room_id', $room_id);
                        
                        $date = 0;
                        $day = '(^|,)0(,|$)';
                        $result_rate = $db->prepare('
                        SELECT DISTINCT(price), r.id as rate_id, start_date, end_date
                        FROM pm_rate as r, pm_package as p
                        WHERE id_package = p.id
                            AND min_nights IN(0,1)
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
                                AND r.lang = '.DEFAULT_LANG;
                        
                        if($_SESSION['admin']['type'] == 'hotel') $query_room .= ' AND users REGEXP \'[[:<:]]'.$_SESSION['admin']['id'].'[[:>:]]\'';
                        if($id_hotel > 0) $query_room .= ' AND id_hotel REGEXP \'[[:<:]]'.$id_hotel.'[[:>:]]\'';
                        
                        $query_room .= ' ORDER BY room_title';
                                
                        $result_room = $db->query($query_room);
                        
                        if($result_room !== false){
                            
                            $rooms = array();
                            $bookings = array();
                            $closing = array();
                            
                            foreach($result_room as $j => $row){
                                 
                                $room_id = $row['room_id'];
                                $room_title = $row['room_title'];
                                $stock = $row['stock'];
                                $min_price = $row['price'];
                                $start_lock = $row['start_lock'];
                                $end_lock = $row['end_lock'];
                                
                                $rooms[$room_id] = $row;
                                
                                $max_n = $stock-1;
                                
                                if($result_closing->execute() !== false){
                                    foreach($result_closing as $i => $row){
                                        $start_date = $row['from_date'];
                                        $end_date = $row['to_date'];
                                        $stock = $row['stock'];
                                        
                                        $start_date = gm_strtotime(gmdate('Y', $start_date).'-'.gmdate('n', $start_date).'-'.gmdate('j', $start_date).' 00:00:00');
                                        $end_date = gm_strtotime(gmdate('Y', $end_date).'-'.gmdate('n', $end_date).'-'.gmdate('j', $end_date).' 00:00:00');
                                        
                                        $start = ($start_date < $time_1d_before) ? $time_1d_before : $start_date;
                                        $end = ($end_date > $time_1d_after) ? $time_1d_after : $end_date;
                                        
										for($s = 0; $s < $stock; $s++){
											$n = 0;
											for($date = $start; $date < $end; $date += 86400){
												
												$k = null;
												$c = 0;
												while(is_null($k)){
													if(!isset($closing[$room_id][$date][$c])) $k = $c;
													else $c++;
												}
												if($c > $n) $n = $c;
											}
											for($date = $start; $date < $end; $date += 86400)
												$closing[$room_id][$date][$n] = $row;
											
											if($n > $max_n) $max_n = $n;
										}
                                    }
                                }
                                
                                if($result_book->execute() !== false){
                                    foreach($result_book as $i => $row){
                                        $start_date = date('d-m-Y', $row['from_date']);
                                        $end_date = date('d-m-Y', $row['to_date']);
                                        switch($row['status']){
                                                case 1: $status = $texts['AWAITING']; break;
                                                case 2: $status = $texts['CANCELLED']; break;
                                                case 3: $status = $texts['REJECTED_PAYMENT']; break;
                                                case 4: $status = $texts['PAID']; break;
                                                default: $status = $texts['AWAITING']; break;
                                        }
                                        $lineData = array($row['bookid'], $row['firstname'], $row['lastname'], $row['email'], $row['phone'], $row['title'], $start_date, $end_date,$row['nights'], $row['adults'], $row['children'], $row['amount'], $row['discount'], $row['ex_tax'], $row['tax_amount'],$row['total'],$row['payment_option'], $row['payment_mode'], $row['trans'], $status);
                                        fputcsv($f, $lineData, $delimiter);
                                    }
                                   
                                }
                            
                            } 
                            fseek($f, 0);
                            header('Content-Type: text/csv');
                            header('Content-Disposition: attachment; filename="' . $filename . '";');
                            fpassthru($f);
                        } 
           
                    }
                }else echo '<p>'.$texts['ACCESS_DENIED'].'</p>';
            } 
      
$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array();
ob_end_flush();
?>
