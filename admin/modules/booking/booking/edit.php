<?php
define('ADMIN', true);
$max_adults_search = 30;
$max_children_search = 10;
define('SYSBASE', str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../../').'/'));

require_once(SYSBASE.'/common/lib.php');
require_once(SYSBASE.'/common/define.php');

if(!isset($_SESSION['user'])){
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/login.php');
    exit();
}elseif($_SESSION['user']['type'] == 'registered'){
    unset($_SESSION['user']);
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
$field_notice = array();
$msg_error = '';
$msg_success = '';
$room_stock = 1;
$max_people = 30;
$search_limit = 8;
$search_offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;
unset($_SESSION['bt']);
if(!isset($_SESSION['num_adults']))
    $_SESSION['num_adults'] = (isset($_SESSION['bt']['adults'])) ? $_SESSION['bt']['adults'] : 1;
if(!isset($_SESSION['num_children']))
    $_SESSION['num_children'] = (isset($_SESSION['bt']['children'])) ? $_SESSION['bt']['children'] : 0;
    
$from_date = (isset($_SESSION['from_date'])) ? $_SESSION['from_date'] : '';
$to_date = (isset($_SESSION['to_date'])) ? $_SESSION['to_date'] : ''; 

$result_book = $db->prepare('SELECT *  FROM pm_booking   WHERE id ='.$_GET['id']);
                     
?>
<!DOCTYPE html>
<head>
    <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_header_common.php'); ?>
    <link rel="stylesheet" href="<?php echo DOCBASE.ADMIN_FOLDER.'/css/pms.css'; ?>">
    
    <style>
        .input-group {
           width: 100%;
         }
        .input-group-addon{width:100px;}
    </style>
    
    <script>
        $(function(){
            var lastDate = new Date();
            
            $('#from_picker').datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: 0,
            onClose: function(selectedDate){
                var a = selectedDate.split('/');
                var d = new Date(a[2]+'/'+a[1]+'/'+a[0]);
                var t = new Date(d.getTime()+86400000);
                var date = t.getDate()+'/'+(t.getMonth()+1)+'/'+t.getFullYear();
                $('#to_picker').datepicker('option', {
                    minDate:date
                });
                $('#to_picker').val(date);
                showDays();
                get_rooms()
            }
        });
        $('#to_picker').datepicker({
            dateFormat: 'dd/mm/yy',
            onClose: function(selectedDate){
                 showDays();
                 get_rooms()
            }
        });
        $("#from_picker").datepicker("setDate" , lastDate);
        lastDate.setDate(lastDate.getDate() + 1);
        $("#to_picker").datepicker("setDate" , lastDate);
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
   <script src="custom.js"></script>
  
    <style>
     .sidenav {
      height: 100%; /* 100% Full-height */
      width: 0; /* 0 width - change this with JavaScript */
      position: fixed; /* Stay in place */
      z-index: 99; /* Stay on top */
      top: 0; /* Stay at the top */
      right: 0;
      background-color: #d6ad39; /* Black*/
      overflow-x: hidden; /* Disable horizontal scroll */
      padding-top: 60px; /* Place content 60px from the top */
      transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    }
    
    /* The navigation menu links */
    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }
    
    /* When you mouse over the navigation links, change their color */
    .sidenav a:hover {
      color: #f1f1f1;
    }
    
    /* Position and style the close button (top right corner) */
    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }
     #coupon_msg{
         height:30px;
         width: 100%;
     }
    #coupon_msg .error {
      color: #a94442;
      font-size: 13px;
      background-color: #f2dede;
      border-color: #ebccd1;
      padding : 5px 10px;
      margin-bottom:10px;
    }
    #coupon_msg .success {
      color: #3c763d;
      font-size: 13px;
      background-color: #dff0d8;
      border-color: #d6e9c6;
      padding : 5px 10px;
      margin-bottom:10px;
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

                                <a href="index.php?view=list">
                                    <button type="button" class="btn btn-default mt15" data-toggle="tooltip" data-placement="bottom" title="<?php echo $texts['BACK_TO_LIST']; ?>">
                                        <i class="fas fa-fw fa-reply"></i><span class="hidden-sm hidden-xs"> <?php echo $texts['BACK_TO_LIST']; ?></span>
                                    </button>
                                </a>
                                
                                <a href="new.php?view=form">
                                    <button type="button" class="btn btn-primary mt15" data-toggle="tooltip" data-placement="bottom" title="<?php echo $texts['BACK_TO_LIST']; ?>">
                                        <i class="fas fa-fw  fa-plus-circle"></i><span class="hidden-sm hidden-xs"><?php echo $texts['NEW']; ?></span>
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
                            <legend>Reschedule Booking</legend>
                            <form method="post" action="new.php"  class="ajax-form" id="create_booking">
                                <input type="hidden" name="book" value="1">
                                <div class="row">
                                    <input type="hidden" name="destination_id" id="destination_id" value="0">
                                     <div class="col-md-1"></div>
                                     <div class="col-md-7">
                                        <div class="row">
                                        
                                        <div class="row">
                                           <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                            <label class="sr-only" for="Users"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-user"></i><span class="booklabel"> User </span></div>
                                                  <?php 
                                                  if($_SESSION['user']['type']=='administrator'){
                                                       $result_user = $db->query('SELECT * FROM pm_user WHERE checked = 1'); 
                                                  }else{
                                                     $result_user = $db->query('SELECT * FROM pm_user WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['user']['id'].', users)');   
                                                  }
                                                  ?>
                                                    <select class="form-control" id="select_user" name="id_user">
                                                          <?php foreach($result_user as $key=>$user){
                                                                echo '<option value="'.$user['id'].'">'.$user['firstname'].' '.$user['lastname'].'</option>';
                                                           }?>
                                                    </select>
                                               </div>
                                            <div class="field-notice" rel="from_date"></div>
                                            
                                        </div>
                                        </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                           <div class="form-group">
                                            <div class="input-group">
                                               <div style="float:right;"><span class=" btn btn-default btn-sm" onclick="openNav()">Add new user</span></div> 
                                            </div>
                                        </div>
                                         </div>
                                        </div>    
                                            
                                        <div class="form-group">
                                            <label class="sr-only" for="Hotels"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-hotel"></i><span class="booklabel"> Hotels </span></div>
                                                  <?php 
                                                     if(in_array($_SESSION['user']['type'], array('hotel','manager'))){
                                                           $result_hotels = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['user']['id'].', users) AND lang = '.LANG_ID); 
                                                       }else{
                                                          $result_hotels = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND  lang = '.LANG_ID); 
                                                       }  
                                                    ?>
                                                  
                                                    <select class="form-control" name="id_hotel" id="id_hotel" onchange="get_rooms();">
                                                        <?php
                                                            foreach($result_hotels as $key=>$hotel){
                                                                echo '<option value="'.$hotel['id'].'">'.$hotel['title'].'</option>';
                                                             }
                                                        ?>
                                                    </select>
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-calendar"></i><span class="booklabel"><?php echo $texts['FROM_DATE']; ?></span></div>
                                                <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" value="<?php echo $from_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-calendar"></i><span class="booklabel"> <?php echo $texts['TO_DATE']; ?></span></div>
                                                <input type="text" class="form-control datepicker" id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="to_date"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-sun"></i> <span class="booklabel">Nights</span></div>
                                                <input type="text" class="form-control" id="nights" rel="nights" name="nights" value="">
                                            </div>
                                            <div class="field-notice" rel="nights"></div>
                                        </div>
                                        <div class="row">
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="booklabel"><?php echo $texts['ADULTS']; ?></span></div>
                                                    <select name="num_adults" id="num_adults" class="selectpicker form-control" onchange="get_rooms();">
                                                        <?php
                                                        for($i = 1; $i <= $max_adults_search; $i++){
                                                            $select = ($_SESSION['num_adults'] == $i) ? ' selected="selected"' : '';
                                                            echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span><?php echo $texts['CHILDREN']; ?></span></div>
                                                        <select name="num_children" id="num_children" class="selectpicker form-control" onchange="get_rooms();">
                                                            <?php for($i = 0; $i <= $max_children_search; $i++){
                                                                $select = ($_SESSION['num_children'] == $i) ? ' selected="selected"' : '';
                                                                echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
                                                              } 
                                                             ?>
                                                        </select>
                                                </div>
                                            </div>
                                         </div>
                                        </div>

                                        <div class="form-group" class="rooms">
                                            <div class="input-group">
                                                <div id="rooms">Room not found!!</div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="float:right;">
                                            <a  class="sendAjaxForm" id="update_price"  data-action="update_booking.php" data-target="#total_booking" data-extratarget="#booking-amount" style="display:none"> </a>
                                            <a  class="btn btn-default " id="update_process" onclick="process();">Process booking</a>
                                         </div>
                                    </div>
                                    </div>
                                     <div class="col-md-4">
                                         <div class="booking_left">
                                              <div class="form-group" class="services">
                                                <div class="input-group">
                                                <div id="book_services">Service not found!!</div> 
                                              </div>
                                           </div>
                                           <fieldset class="mb20">
                                                <div id="coupon_msg"></div>
                                                <legend>DO YOU HAVE A COUPON?</legend>
                                                <div class="form-group form-inline">
                                                    <input class="form-control" type="text" value="" name="coupon_code">
                                                    <a id="chk-coupn" class="btn btn-primary sendAjaxForm" data-action="update_booking.php" data-target="#total_booking" onclick="check_coupon();"><i class="fas fa-fw fa-check"></i></a>
                                                </div>
                                            </fieldset>
                                            <div id="total_booking" class="mb15">
                                         </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                          
                        </fieldset>
                    </div>
                    
                    <?php
                   
                }else echo '<p>'.$texts['ACCESS_DENIED'].'</p>';
            } ?>
        </div>
    </div>
 <div id="mySidenav" class="sidenav">
     <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
     <div class="panel-body">
          <div class="tab-content">
              <h2>Add user</h2>
               <form name="add_user_form" id="add_user_form" method="post" action="create_user.php">
                <div id="lang_0" class="">
                    <div class="row mb10">
                        <label class="col-lg-3 control-label">
                             Firstname&nbsp;<span class="red">*</span>
                        </label>
                        <div class="col-lg-9">
                               <div class="">
                                    <input type="text" name="user_firstname" id="user_firstname" value="" rel="user_firstname" class="form-control">
                                </div>
                        </div>
                    </div>
                    <div class="row mb10">
                            <label class="col-lg-3 control-label">Lastname<span class="red">*</span> </label>
                             <div class="col-lg-9">
                               <div class="">
                                    <input type="text" name="user_lastname" id="user_lastname" value="" rel="user_lastname" class="form-control">
                                </div>
                             </div>
                     </div>     
                    <div class="row mb10">
                             <label class="col-lg-3 control-label">E-mail&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                <div class="">
                                        <input type="text" name="user_email" id="user_email" value="" rel="user_email" class="form-control">
                                </div>
                             </div>
                    </div>
       
                    <div class="row mb10">
                            <label class="col-lg-3 control-label">Company</label>
                             <div class="col-lg-9">
                                      <div class="">
                                            <input type="text" name="user_company" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>
                   <div class="row mb10">
                            <label class="col-lg-3 control-label">Address&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                            <input type="text" name="user_address" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>
                   <div class="row mb10">
                            <label class="col-lg-3 control-label">Postcode&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                            <input type="text" name="user_postcode" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>
                   <div class="row mb10">
                           <label class="col-lg-3 control-label">City&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                                <input type="text" name="user_city" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>
                   
                      <div class="row mb10">
                           <label class="col-lg-3 control-label">Country&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                           <?php $result_countries = $db->query('SELECT * FROM pm_country ');  ?>
                                            <select class="form-control" id="user_country" name="user_country" class="form-control">
                                                        <option>Select Country</option>
                                                          <?php foreach($result_countries as $key=>$country){
                                                                echo '<option value="'.$country['name'].'">'.$country['name'].'</option>';
                                                           }
                                                          ?>
                                            </select>
                                     </div>
                            </div>
                   </div>
                   <div class="row mb10">
                           <label class="col-lg-3 control-label">Phone&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                            <input type="text" name="user_phone" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>
                         
                    <div class="row mb10">
                           <label class="col-lg-3 control-label">Mobile&nbsp;<span class="red">*</span></label>
                             <div class="col-lg-9">
                                      <div class="">
                                            <input type="text" name="user_mobile" value="" size="30" class="form-control">
                                     </div>
                            </div>
                   </div>                                                                                                           
               
                     <div class="row mb10">
                           <label class="col-lg-6 control-label"></label>
                             <div class="col-lg-4">
                                      <div class="">
                                            <button type="button" class="btn btn-default" name="create_user" id="create_user" >
                                                <i id="spin" class="fa fa-spinner fa-spin"></i>Create user</button>
                                     </div>
                            </div>
                   </div> 
             </div>
             </form>
        </div>
    </div>    
     
</div>
</body>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
  if($result_book->execute() !== false){
      $row = $result_book->fetch();
      $result_room = $db->prepare('SELECT *  FROM pm_booking_room   WHERE id_booking ='.$_GET['id']);
      
     //var_dump($row);
     $select_user = $row["id_user"];
     $id_hotel = $row["id_hotel"];
     $d1 = date('d/m/Y',$row["from_date"]);
     $d2 = date('d/m/Y',$row["to_date"]);
     $nights = $row["nights"];
     $adults = $row["adults"];
     $children = $row["children"];
     ?>
     <script>
       $(function(){
             $("#select_user" ).val('<?php echo $select_user; ?>');
             $("#id_hotel" ).val('<?php echo $id_hotel; ?>');
             $("#from_picker" ).val('<?php echo $d1; ?>');
             $('#to_picker').val('<?php echo $d2; ?>');
             $('#nights').val('<?php echo $nights; ?>');
             $('#$adults').val('<?php echo $adults; ?>');
             $('#children').val('<?php echo $children; ?>');
     
<?php }
 if($result_room->execute() !== false){
   foreach($result_room as $key=>$room){ 
               echo 'alert("sssss");';
  
      } 
     
 } ?>
      
     });  
</script>
<script>
$(document).ready(function(){
     showDays();
     get_rooms();
     //get_services();
    $("#spin").css('display','none');
    $("#spin2").css('display','none');
    
    $("#create_user").click(function(){
    $( "#add_user_form" ).submit();
  });

});

$(document).ready(function() {
     
    $("#rooms [data-labelfor]").click(function() {
        $('#' + $(this).attr("data-labelfor")).prop('checked',
       function(i, oldVal) { return !oldVal; });
    });
   <?php if(isset($_GET['hotel_id']) && isset($_GET['booking_from_date'])){?>
             var id_hotel = $('#id_hotel');
             var from_picker = $('#from_picker');
             var to_picker = $('#to_picker');
	         id_hotel.val(<?php echo @$_GET['hotel_id'];?>);
	          from_picker.datepicker("setDate" , <?php echo $_GET['booking_from_date'];?>);
	          to_picker.datepicker("setDate" , <?php echo $_GET['booking_to_date'];?>);
	          to_picker.change();
	         
   <?php  } ?>
});
</script>
<script>
    /* Set the width of the side navigation to 250px */
function openNav() {
  document.getElementById("mySidenav").style.width = "430px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
<script>
    jQuery.validator.addMethod("atLeastOneUppercaseLetter", function (value, element) {
      return this.optional(element) || /[A-Z]+/.test(value);
  }, "Must have at least one uppercase letter");
   
  /**
   * Custom validator for contains at least one number.
   */
  jQuery.validator.addMethod("atLeastOneNumber", function (value, element) {
      return this.optional(element) || /[0-9]+/.test(value);
  }, "Must have at least one number");
   
  /**
   * Custom validator for contains at least one symbol.
   */
  $.validator.addMethod("atLeastOneSymbol", function (value, element) {
    return this.optional(element) || /[!@#$%^&*()]+/.test(value);
  }, "Must have at least one symbol");

  jQuery.validator.addMethod("phoneno", function(phone_number, element) {
          phone_number = phone_number.replace(/\s+/g, "");
          return this.optional(element) || phone_number.length > 9 && 
          phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    }, "<br />Please specify a valid phone number");
    $('form[id="add_user_form"]').validate({
      rules: {
        user_login: 'required',
        user_firstname: 'required',
        user_lastname: 'required',
        user_email: {
            required: true,
            email: true,
            remote: {
                url: "check_email.php",
                type: 'GET',
                dataType: 'text',
                data: {
                    user_email: function() {
                        return $('#user_email').val();
                    }

                 },
                 dataFilter: function (data) {
              
              if (data == "true") {
                  return "This email already exist please try another one!!";
              }else {
                  return 'true';
                }
               }
              }
            },

           user_address: 'required',
           user_postcode: 'required',
           user_city: 'required',
           user_phone: 'required',
           user_mobile: 'required',

      },
      messages: {
        user_login: 'This username is required',
        user_firstname: 'This First name is required',
        user_lastname: 'This Last name is required',
        user_email: {
                required: "Please Enter Email!",
                email: "This is not a valid email!",
                remote: "Email already in use!"
            },
 
        user_address: 'Please Enter Address!',
        user_postcode: 'Please Enter postcode!',
        user_city: 'Please Enter City!',
        user_phone: 'Please Enter Phone!',
        user_mobile: 'Please Enter mobile!',    
        
      },
      submitHandler: function(e) {
                $("#spin").css('display','block');
                var form = $('form#add_user_form');
                var action = form.attr('action');
                $.ajax({
                    url: action,
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function(response){
                        //alert(response)
                         $("#select_user").append(response);
                         $('form#add_user_form')[0].reset()
                         document.getElementById("mySidenav").style.width = "0";
                         $("#spin").css('display','none');
                    }
                });
        
           }
  });
  
  var get_rooms = function(){
         
         var id_hotel=$('#id_hotel').val();
         var from_date=$('#from_picker').val();
         var to_date=$('#to_picker').val();
         var num_adults=$('#num_adults').val();
         var num_children=$('#num_children').val();
         var nights=$('#nights').val();
         var htl ='<span id="booking-amount_'+id_hotel+'">';
          
         $.ajax({  
             url: 'get_rooms.php',
                    type: 'POST',
                    data: {id_hotel: id_hotel, from_date: from_date, to_date: to_date, num_adults: num_adults, num_children: num_children,nights:nights},
                    success: function(html){
                         $("#rooms").html(html);
                    }
         });
      
  }
  
  var get_services = function(rid){
         
          if($('#'+rid).is(":checked")) {
            $('#view_'+rid).slideToggle();     
          }else{
            $('#view_'+rid).slideToggle();
            $('#num_'+rid+' option').prop('selected', function() {
                return this.defaultSelected;
            });
            $('#num_'+rid).change();
          }
          
         $("#book_services").html('');
         //var  rooms = new Array();
            var rooms = [];
            $("#rooms input[name='item[]']:checked").each(function (){ 
                rooms.push(parseInt($(this).val()));
                 var id_hotel=$('#id_hotel').val();
                 var from_date=$('#from_picker').val();
                 var to_date=$('#to_picker').val();
                 var num_adults=$('#num_adults').val();
                 var num_children=$('#num_children').val();
                 var nights=$('#nights').val();
                 $.ajax({  
                     url: 'get_sevices.php',
                            type: 'POST',
                            data: {id_hotel: id_hotel, from_date: from_date, to_date: to_date, num_adults: num_adults, num_children: num_children, nights:nights,rooms:rooms},
                            success: function(html){
                                 $("#book_services").html(html);
                            }
                 });
                
            });
         $('#update_price').trigger("click");
    }
  
  var process = function(){
      var check = 0;
        $("#rooms input[name='item[]']:checked").each(function (){
             check =  check +1;
        });
        if(check>0){
               var form = $('form#create_booking');
                 setTimeout(function(){
                 $('#update_price').trigger("click");
                },500);
         }else{
               swal("Error", "Please check at least one room");
         }
   }
   
    var check_coupon = function(){
        $('#coupon_msg').html('');
        var form = $('form#create_booking');
        $.ajax({  
                  url: 'check_coupon.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(msg){
                        $('#coupon_msg').html(msg);
                        setTimeout(function(){
                            $('#coupon_msg').html('');
                        },2500);
   
                    }
          });
   }
  
  function showDays(){
                    var start= $("#from_picker").datepicker("getDate");
                    var end= $("#to_picker").datepicker("getDate");
                    diff  = new Date(end - start),
                    days  = diff/1000/60/60/24;
                    $('#nights').val(days);
                }
              
var create_booking = function(){
            var form = $('form#create_booking');
            $("#spin2").css('display','block');
            var check = 0;
            $("#rooms input[name='item[]']:checked").each(function (){
                 check =  check +1;
            });
            if(check>0){
               $.ajax({  
                  url: 'create_booking.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(html){
                        swal("Thank you", html, "success");
                        setTimeout(function(){
                            $("#spin2").css('display','none');
                           location.reload();
                         },1000);
   
                    }
               });
           }else{
               swal("Error", "Please check at least one room");
           }
  }

</script>
</html>
<?php
$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
