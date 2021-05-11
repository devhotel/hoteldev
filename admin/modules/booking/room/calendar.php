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
  if(isset($_POST['id_hotel'])){
    $id_hotel=@$_POST['id_hotel'];
  }
  $rid = 0;
  if(isset($_GET['id'])){
    echo $rid=@$_GET['id'];
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
    <script>
        $(function(){
            
            var curr_start_id = '';
            var prev_class = '';
            var curr_room = 0;
            var curr_line = -1;
            var curr_date = null;
            var end_clicked = false;
            var start_clicked = false;
            $('.timeline-default:not(.start-d):not(.start-end-d):not(.full)').on('click', function(e){
                if(!$(e.target).closest('a').length){
                    var arr_id = $(this).attr('id').split('-');
                    var hotel = parseInt(arr_id[1]);
                    var room = parseInt(arr_id[2]);
                    var line = parseInt(arr_id[3]);
                    var date = parseInt(arr_id[4]);
                    // set start date
                    if((curr_room == 0 || (curr_room > 0 && curr_room != room))
                    || (curr_line == -1 || (curr_line > -1 && curr_line != line))
                    || (curr_date == null || (curr_date > 0 && curr_date > date))
                    || end_clicked){
                        
                        if(!end_clicked && prev_class != '' && curr_start_id != '') $('#'+curr_start_id).attr('class', '').addClass(prev_class);
                        
                        prev_class = $(this).attr('class');
                        
                        var class_attr = ($(this).hasClass('end-d')) ? 'start-end-d' : 'start-d';
                        $(this).removeClass('end-d').addClass(class_attr+' booked pending').append('<a class="pending"></a>');
                        
                        curr_start_id = $(this).attr('id');
                        curr_room = room;
                        curr_line = line;
                        curr_date = date;
                        end_clicked = false;
                        start_clicked = true;
                    }
                }
            });
            $('.timeline-default:not(.end-d):not(.start-end-d):not(.full)').on('click', function(e){
                if(!$(e.target).closest('a').length){
                    var arr_id = $(this).attr('id').split('-');
                    var hotel = parseInt(arr_id[1]);
                    var room = parseInt(arr_id[2]);
                    var line = parseInt(arr_id[3]);
                    var date = parseInt(arr_id[4]);
                    // set end date
                    if(curr_room > 0 && curr_room == room
                    && curr_line > -1 && curr_line == line
                    && curr_date > 0 && curr_date < date
                    && start_clicked){
                        
                        var booked = false;
                        var limit = 0;
                        var end_id = $(this).attr('id');
                        var next_elm = $('#'+curr_start_id).next();
                        var next_id = next_elm.attr('id');
                        while(next_id != end_id && limit < 31){
                            if($('#'+next_id).hasClass('booked')){
                                booked = true;
                                break;
                            }
                            next_elm = next_elm.next();
                            next_id = next_elm.attr('id');
                            limit++;
                        }
                        
                        if(!booked){
                        
                            end_id = $(this).attr('id');
                            end_clicked = true;
                            start_clicked = false;
                            var class_attr = ($(this).hasClass('start-d')) ? 'start-end-d' : 'end-d';
                            $(this).removeClass('start-d').addClass(class_attr).prepend('<a class="pending"></a>');
                            
                            limit = 0;
                            next_elm = $('#'+curr_start_id).next();
                            next_id = next_elm.attr('id');
                            while(next_id != end_id && limit < 31){
                                next_elm.addClass('booked full pending').append('<a class="pending"></a>');
                                next_elm = next_elm.next();
                                next_id = next_elm.attr('id');
                                limit++;
                            }
                            /*var from_time = new Date(curr_date*1000);
                            var from_date = from_time.getUTCFullYear()+'-'+(from_time.getUTCMonth()+1)+'-'+from_time.getUTCDate();
                            var to_time = new Date(date*1000);
                            var to_date = to_time.getUTCFullYear()+'-'+(to_time.getUTCMonth()+1)+'-'+to_time.getUTCDate();*/
                            
                            var nnights = (date-curr_date)/86400;
                            var room_title = $('#room-title-'+room).html().trim();
                            //var room_num = $('#room-num-'+room+'-'+line).html().trim().replace('#', '%23');
                            
                            if($('#context-menu').length == 0){
                                $('body').append('<div id="context-menu"></div>').on('blur', function(){
                                    $(this).hide();
                                });
                            }
                            
                            $('#context-menu').html('<a href="index.php?view=form&id=0&booking_id_hotel_0='+hotel+'&booking_from_date_0='+curr_date+'&booking_to_date_0='+date+'&booking_nights_0='+nnights+'&booking_status_0=1&booking_room_id_hotel_0='+hotel+'&booking_room_id_room_0='+room+'&booking_room_title_0='+room_title+'">New booking</a>'+
                            '<a href="../room/index.php?view=form&id='+room+'&room_closing_from_date_0='+curr_date+'&room_closing_to_date_0='+date+'">New closing date</a>');
                            
                            $('#context-menu').css({
                                'left': e.pageX + 'px',
                                'top': e.pageY + 'px'
                            }).slideDown();
                        }
                    }
                }
            });
            
            var saved_price = 0;
            $('.price-input').on('focus', function(e){
                var price = $(this).val().replace(/[^\d.-]/g, '');
                $(this).val(price);
                saved_price = price;
            });
            $('.stock-input').on('focus', function(e){
                var stock = $(this).val().replace(/[^\d.-]/g, '');
                $(this).val(stock);
                saved_stock = stock;
            });
            $('.ajax-input').on('blur', function(e){
                e.defaultPrevented;
                
                var input = $(this);
                var val = input.val();
                var form = input.parents('form.ajax-form');
                var action = input.data('action');
                
                $.ajax({
                    url: action,
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function(response){
                        var response = $.parseJSON(response);
                                
                        if(response.error != ''){
                            if(input.hasClass('price-input')) val = '<?php echo DEFAULT_CURRENCY_SIGN; ?> '+saved_price;
                            else val = saved_stock;
                            input.removeClass('text-success').addClass('text-danger');
                            setTimeout(function(){
                                input.removeClass('text-danger').val(val);
                            }, 1000);
                        }
                        if(response.success != ''){
                            if(response.html != '') $('[name="rate_id"]', form).val(response.html);
                            if(input.hasClass('price-input')) val = '<?php echo DEFAULT_CURRENCY_SIGN; ?> '+val;
                            else{
                                var remain = val - $('[name="num_bookings"]', form).val();
                                if(remain < 0) remain = 0;
                                $('.remain', form).html(remain);
                                if(remain == 0) form.parents('.timeline-info').addClass('full');
                                else form.parents('.timeline-info').removeClass('full');
                            }
                            input.removeClass('noprice text-danger').addClass('text-success').val(val);
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .timeline-row {
            width: auto;
        }
        .timeline-cel {
            float: left;
            width: 128px;
            height: 50px;
            line-height: 17px;
            font-size: 12px;
            text-align: center;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            position: relative;
            overflow: hidden;
        }
        .timeline-cel.timeline-d {
            height: 40px;
            padding: 6px 3px;
            background : #4286f4;
            color: #FFF;
            border-top: 1px solid #4286f4;
        }
        .timeline-cel.timeline-d:not(.bg-warning) {
                background : #4286f4;
                color: #FFF;
        }
        .timeline-cel.timeline-price {
            height: 80px;
            padding: 5px 3px;
        }
        .timeline-cel.today {
            background-color: #d6ad39 !important;
        }
        .btn-sm, .btn-group-sm > .btn {
            padding: 1px 4px;
        }
        .room-color{
            color: #007bff;
        }
        .room-color > .price{
            font-weight:bold;
        }
        .date-color{
            color: #343a40 !important;
            font-size: 12px;
            font-weight:bold;
        }
  </style>
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
                                    <a href="index.php?view=form&id=0" class="btn btn-primary mt15">
                                        <i class="fas fa-fw fa-plus-circle"></i> <?php echo $texts['NEW']; ?>
                                    </a>
                                    <?php
                                } ?>
                                <a href="index.php?view=list">
                                    <button type="button" class="btn btn-default mt15" data-toggle="tooltip" data-placement="bottom" title="<?php echo $texts['BACK_TO_LIST']; ?>">
                                        <i class="fas fa-fw fa-reply"></i><span class="hidden-sm hidden-xs"> <?php echo $texts['BACK_TO_LIST']; ?></span>
                                    </button>
                                </a>
                            </div>
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
            if($db !== false){
                if(in_array('all', $permissions) || $_SESSION['admin']['type'] == 'hotel'){ ?>
                    <div class="panel-body mb15">
                        <fieldset>
                            <legend>Price calender</legend>
                            <form method="post" action="">
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
                                            <button type="submit" class="btn btn-default" name="change_date">GO</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    
                    <?php
                    if($to_time > $from_time){
                        
                        if(($to_time-$from_time+86400) > (86400*31)) $to_time = $from_time+(86400*30);
                        $width = (($to_time-$from_time+86400)/86400)*50;
                        
                        $time_1d_before = $from_time-86400;
                        $time_1d_before = gm_strtotime(gmdate('Y', $time_1d_before).'-'.gmdate('n', $time_1d_before).'-'.gmdate('j', $time_1d_before).' 00:00:00');
                        
                         $time_1d_after = $to_time+86400;
                        $time_1d_after = gm_strtotime(gmdate('Y', $time_1d_after).'-'.gmdate('n', $time_1d_after).'-'.gmdate('j', $time_1d_after).' 00:00:00');
                        
                        $from_time = gm_strtotime(gmdate('Y', $from_time).'-'.gmdate('n', $from_time).'-'.gmdate('j', $from_time).' 00:00:00');
                        $to_time = gm_strtotime(gmdate('Y', $to_time).'-'.gmdate('n', $to_time).'-'.gmdate('j', $to_time).' 00:00:00');
                        $to_time =$time_1d_after;
                        $today = gm_strtotime(gmdate('Y').'-'.gmdate('n').'-'.gmdate('j').' 00:00:00');
                            
                        $room_id = 0;
                        
                        
                       $result_book = $db->prepare('
                            SELECT DISTINCT(b.id) as bookid, status, from_date, to_date, firstname, lastname, total
                            FROM pm_booking as b, pm_booking_room as br
                            WHERE
                                br.id_booking = b.id
                                AND (status = 4 OR (status = 1 AND (add_date > '.(time()-900).' OR payment_option IN(\'arrival\',\'check\'))))
                                AND from_date <= '.$to_time.'
                                AND to_date >= '.$time_1d_before.'
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
                        //if($id_hotel > 0) $query_room .= ' AND id_hotel REGEXP \'[[:<:]]'.$id_hotel.'[[:>:]]\'';
                        if($rid > 0) $query_room .= ' AND r.id ='.$rid;
                        
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
                                        
                                        $start = ($start_date < $time_1d_before) ? $time_1d_before : $start_date;
                                        $end = ($end_date > $time_1d_after) ? $time_1d_after : $end_date;
                                        
                                        $n = 0;
                                        for($date = $start; $date < $end; $date += 86400){
                                            
                                            $k = null;
                                            $c = 0;
                                            while(is_null($k)){
                                                if(!isset($bookings[$room_id][$date][$c]) && !isset($closing[$room_id][$date][$c])) $k = $c;
                                                else $c++;
                                            }
                                            if($c > $n) $n = $c;
                                        }
                                        for($date = $start; $date < $end; $date += 86400)
                                            $bookings[$room_id][$date][$n] = $row;
                                        
                                        if($n > $max_n) $max_n = $n;
                                        
                                    }
                                }
                                $rooms[$room_id]['n'] = $max_n;
                            } ?>
                        
                            <div class="panel-body mb15">
                                <div class="container-fluid">
                                    <div class="row">
                                       
                                        <div class="col-lg-12 col-md-12 col-sm-12 timeline-wrapper">
                                            <div class="room-row">
                                                <div class="timeline-row">
                                                </div>
                                            </div>        
                                            <div class="timeline-row">
                                                <?php
                                                $stock = array_sum(array_column($rooms, 'stock'));
                                                
                                                $prev_date = $time_1d_before;
                                                $day=0;
                                                for($date = $from_time; $date <= $to_time; $date += 86400){
                                                    
                                                    $checkin = 0;
                                                    $checkout = 0;
                                                    $booked = 0;
                                                    $date = gm_strtotime(gmdate('Y', $date).'-'.gmdate('n', $date).'-'.gmdate('j', $date).' 00:00:00');
                                                    $d = gmdate('N', $date);
                                                    if($day <7){
                                                      // echo  $day;
                                                    ?>
                                                    <div class="timeline-cel timeline-d<?php if($d == 6 || $d == 7) echo ' bg-warning'; ?><?php if($date == $today) echo ' '; ?>">
                                                        <?php echo mb_strtoupper(strftime('<b>%a</b>', $date)); ?><br>
                                                    </div>
                                                    <?php
                                                    }
                                                    $day++;
                                                    $prev_date = $date;
                                                } ?>
                                            </div>
                                            <?php
                                            //var_dump($rooms);
                                            foreach($rooms as $room_id => $row){
                                                //var_dump($row);
                                                $room_title = $row['room_title'];
                                                $hotel_id = $row['id_hotel'];
                                                $stock = $row['stock'];
                                                $min_price = $row['price'];
                                                $start_lock = $row['start_lock'];
                                                $end_lock = $row['end_lock'];
                                                $max_n = $row['n']; ?>
                                                
                                              <?php 
                                               $week = $from_time;
                                               $day_start = $from_time;
                                               for($dates = $from_time; $dates <= $to_time; $dates +=(86400*7)){
                                               $week +=(86400*7);
                                               if($week >= $to_time ){
                                                  $week = $to_time;
                                               }
                                               //echo $week . ' = ' .$to_time . '-' .$dates;
                                               ?>
                                                <div class="room-row">
                                                    <div class="timeline-row">
                                                        <?php
                                                        for($date = $dates; $date <$week; $date += 86400){
                                                            $d = gmdate('N', $date);
                                                            $day = '(^|,)'.$d.'(,|$)';
                                                            
                                                            // price
                                                            $price = 0;
                                                            if($result_rate->execute() !== false && $db->last_row_count() > 0){
                                                                $row1 = $result_rate->fetch();
                                                                $price = $row1['price'];
                                                            }
                                                            
                                                            // remaining rooms
                                                            $remain = $stock;
                                                            if(isset($bookings[$room_id][$date])){
                                                                $num_bookings = count($bookings[$room_id][$date]);
                                                                $remain = ($stock <= $num_bookings) ? 0 : $stock-$num_bookings;
                                                            } 
                                                            
                                                            ?>
                                                            <div class="timeline-cel timeline-price<?php if($d == 6 || $d == 7) echo ' bg-warning'; ?><?php if($date == $today) echo ' today'; ?>">
                                                                <div class="date-color"><?php echo mb_strtoupper(strftime('%d/%m', $date)); ?></div>
                                                                <div class="room-color"><?php echo $room_title; ?> - <span class="price"><?php if($price > 0) echo formatPrice($price*CURRENCY_RATE); ?></span></div>
                                                                <?php if($remain > 0) {?>
                                                                    <span class="btn-success btn-sm"><?php echo $remain; ?> / <?php echo $stock; ?> available</span>
                                                                <?php }else{?>
                                                                    <span class="btn-danger btn-sm"><?php echo $remain; ?> / <?php echo $stock; ?> Not available</span>
                                                                <?php }?>
                                                            </div>
                                                            <?php } 
                                                        ?>
                                                        
                                                    </div>
                                                </div>
                                                
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <?php
                        } ?>
                    <?php
                    }
                }else echo '<p>'.$texts['ACCESS_DENIED'].'</p>';
            } ?>
        </div>
    </div>
</body>
</html>
<?php
$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
