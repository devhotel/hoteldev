<?php
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
} ?>
<!DOCTYPE html>
<head>
    <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_header_common.php'); ?>
    
    <link rel="stylesheet" href="<?php echo DOCBASE.ADMIN_FOLDER.'/css/pms.css'; ?>">
    
    <script>
        $(function(){
            
            $('#from_picker, #start_picker').datepicker({
                dateFormat: 'dd/mm/yy',
                //minDate: 0,
                onClose: function (selectedDate, instance){
                    if(selectedDate != '') {
                        var relPicker = $('#'+$(this).attr('rel'));
                        relPicker.datepicker('option', 'minDate', selectedDate);
                        var date = $.datepicker.parseDate(instance.settings.dateFormat, selectedDate, instance.settings);
                        date.setMonth(date.getMonth() + 1);
                        relPicker.datepicker('option', 'minDate', selectedDate);
                        relPicker.datepicker('option', 'maxDate', date);
                    }
                }
            });
            $('#to_picker, #end_picker').datepicker({
                dateFormat: 'dd/mm/yy',
                defaultDate: '+1w',
                onClose: function (selectedDate){
                    var relPicker = $('#'+$(this).attr('rel'));
                    relPicker.datepicker('option', 'maxDate', selectedDate);
                }
            });
            <?php
            if(isset($field_notice) && !empty($field_notice))
                foreach($field_notice as $field => $notice) echo '$(\'.field-notice[rel="'.$field.'"]\').html(\''.$notice.'\').fadeIn(\'slow\').parent().addClass(\'alert alert-danger\');'."\n"; ?>
        });
    </script>
</head>
<body>
   
    <div id="wrapper">
        <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_top.php'); ?>
        <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h1 class="pull-left"><i class="fas fa-fw fa-<?php echo ICON; ?>"></i> <?php echo TITLE_ELEMENT; ?></h1>
                            <div class="pull-left text-right">
                                &nbsp;&nbsp;
                                <?php
                                if(in_array('add', $permissions) || in_array('all', $permissions)){ ?>
                                    <a href="new.php?view=form&id=0" class="btn btn-primary mt15">
                                        <i class="fas fa-fw fa-plus-circle"></i> <?php echo $texts['NEW']; ?>
                                    </a>
                                    <?php
                                } ?>
                                <a href="index.php?view=list">
                                    <button type="button" class="btn btn-default mt15" data-toggle="tooltip" data-placement="bottom" title="<?php echo $texts['BACK_TO_LIST']; ?>">
                                        <i class="fas fa-fw fa-reply"></i><span class="hidden-sm hidden-xs"> <?php echo $texts['BACK_TO_LIST']; ?></span>
                                    </button>
                                </a>
                                <a href="report.php" class="btn btn-success mt15" id="">
                                    <i class="fa fa-list"></i> Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        <div class="form_wrapper reportpage_panel">
       <div class="panel-default">
            <div class="alert-container">
                <div class="alert alert-success alert-dismissable"></div>
                <div class="alert alert-warning alert-dismissable"></div>
                <div class="alert alert-danger alert-dismissable"></div>
            </div>
            <?php
            if($db !== false){
                if(in_array('all', $permissions) || $_SESSION['admin']['type'] == 'hotel'){ ?>    
            <fieldset>
                            <legend>Booking Reports</legend>
                            <form method="post" action="report.php" id="report_form">
                                <div class="row">
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-calendar"></i> <?php echo $texts['FROM_DATE']; ?></div>
                                                <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" value="<?php echo $from_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-calendar"></i> <?php echo $texts['TO_DATE']; ?></div>
                                                <input type="text" class="form-control datepicker" id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="to_date"></div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-hotel"></i><span class="booklabel"> Hotels </span></div>
                                              
                                                <?php 
                                                     if(in_array($_SESSION['admin']['type'], array('hotel','manager'))){
                                                           $result_hotels = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['admin']['id'].', users) AND lang = '.LANG_ID); 
                                                       }else{
                                                          $result_hotels = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND  lang = '.LANG_ID); 
                                                       }  
                                                    ?>
                                                  
                                                    <select class="form-control" name="id_hotel" id="id_hotel">
                                                        <option>All</option>
                                                        <?php
                                                            foreach($result_hotels as $key=>$hotel){
                                                                if($id_hotel>0 && $hotel['id']==$id_hotel ){ $select='selected="selected"'; }else{ $select='';}
                                                                echo '<option value="'.$hotel['id'].'" '.$select.'>'.$hotel['title'].'</option>';
                                                             }
                                                        ?>
                                                    </select>
                                            </div>
                                            <div class="field-notice" rel="id_hotel"></div>
                                        </div>
                                        
                                          <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-hotel"></i><span class="booklabel"> Status </span></div>
                                                    <select class="form-control" name="status" id="status">
                                                        <option value="">All</option>
                                                        <option value="1" <?php echo (isset($_POST['status']) && $_POST['status'] == 1) ? 'SELECTED="SELECTED"' : ''; ?>><?php echo $texts['AWAITING']; ?></option>
                                                        <option value="2" <?php echo (isset($_POST['status']) && $_POST['status'] == 2) ? 'SELECTED="SELECTED"' : ''; ?>><?php echo $texts['CANCELLED']; ?></option>
                                                        <option value="3" <?php echo (isset($_POST['status']) && $_POST['status'] == 3) ? 'SELECTED="SELECTED"' : ''; ?>><?php echo $texts['REJECTED_PAYMENT']; ?></option>
                                                        <option value="4" <?php echo (isset($_POST['status']) && $_POST['status'] == 4) ? 'SELECTED="SELECTED"' : ''; ?>><?php echo $texts['PAID']; ?></option>
                                                       
                                                    </select>
                                            </div>
                                            <div class="field-notice" rel="status"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-hotel"></i><span class="booklabel"> Payment Mode </span></div>
                                                    <select class="form-control" name="payment_mode" id="payment_mode">
                                                        <option value="">All</option>
                                                        <option value="cash_pay" <?php echo (isset($_POST['payment_mode']) && $_POST['payment_mode'] == 'cash_pay') ? 'SELECTED="SELECTED"' : ''; ?>>Cash Pay</option>
                                                        <option value="credit_card" <?php echo (isset($_POST['payment_mode']) && $_POST['payment_mode'] == 'credit_card') ? 'SELECTED="SELECTED"' : ''; ?>>Credit card</option>
                                                        <option value="debit_card" <?php echo (isset($_POST['payment_mode']) && $_POST['payment_mode'] == 'debit_card') ? 'SELECTED="SELECTED"' : ''; ?>>Debit card</option>
                                                        <option value="net_banking" <?php echo (isset($_POST['payment_mode']) && $_POST['payment_mode'] == 'net_banking') ? 'SELECTED="SELECTED"' : ''; ?>>Net banking</option>
                                                      
                                                       
                                                    </select>
                                            </div>
                                            <div class="field-notice" rel="payment_mode"></div>
                                        </div>
                                
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-default" name="change_date">View</button>
                                            <a href="javascript:void(0);" id="export_data" class="btn btn-default">Export</a>
                                           
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
 			<?php
                        $table = '<table class="table table-bordered id_table" id="listing_base"><thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Hotel/Room</th>
                                <th scope="col">From Date</th>
                                <th scope="col">To Date</th>
                                <th scope="col">Nights</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>';
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
                                AND id_room = :room_id ORDER BY bookid');
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
                                        $start_date = $row['from_date'];
                                        $end_date = $row['to_date'];
                                        
                                        $start_date = gm_strtotime(gmdate('Y', $start_date).'-'.gmdate('n', $start_date).'-'.gmdate('j', $start_date).' 00:00:00');
                                        $end_date = gm_strtotime(gmdate('Y', $end_date).'-'.gmdate('n', $end_date).'-'.gmdate('j', $end_date).' 00:00:00');
                                         $start_date = date('d-m-Y', $row['from_date']);
                                        $end_date = date('d-m-Y', $row['to_date']);
                                        switch($row['status']){
                                                case 1: $status = '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                                                case 2: $status = '<span class="label label-danger">'.$texts['CANCELLED'].'</span>'; break;
                                                case 3: $status = '<span class="label label-danger">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                                                case 4: $status = '<span class="label label-success">'.$texts['PAID'].'</span>'; break;
                                                default: $status = '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                                        }
                                        
                                 

    $table .= '<tr>
      <th scope="row">'.$row['bookid'].'</th>
      <td>'.$row['firstname'].'</td>
      <td>'.$row['lastname'].'</td>
      <td>'.$row['email'].'</td>
      <td>'.$row['title'].'</td>
      <td>'.$start_date.'</td>
      <td>'.$end_date.'</td>
      <td>'.$row['nights'].'</td>
      <td>'.formatPrice($row['total']*CURRENCY_RATE).'</td>
      <td>'.$status.'</td>
    </tr>';


                                    }
                                }

                              
                            } ?>
                        
  
                            <?php
                        } ?>
                    <?php
                    }
                                               $table .= '  </tbody>
</table> </div> </div>'; 
echo $table;    
                    
                }else echo '<p>'.$texts['ACCESS_DENIED'].'</p>';
            } ?>
		</div>
		</div>
		</div>
    
    <script>
        $('#export_data').on('click', function(){
            $('#report_form').attr('action', 'csv_generate.php');
            $('#report_form').attr('target', '_blank');
            $('#report_form').submit();
           location.reload();
        });
    </script>
</body>
</html>
<?php
$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array();
exit();
?>
