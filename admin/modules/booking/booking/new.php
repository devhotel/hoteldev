<?php
define('ADMIN', true);
$max_adults_search = 30;
$max_children_search = 10;
define('SYSBASE', str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../../').'/'));

require_once(SYSBASE.'/common/lib.php');
require_once(SYSBASE.'/common/define.php');

if(!isset($_SESSION['admin'])){
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/login.php');
    exit();
}

/*var_dump($permissions);
if(empty($permissions) || $permissions[0]==NULL){
   echo '<script> window.location.href = "'.DOCBASE.ADMIN_FOLDER.'/no-access"; </script>';
}*/
unset($_SESSION['rprice']);
unset($_SESSION['rid']);
if(!isset($_SESSION['redirect'])) $_SESSION['redirect'] = false;

require_once(SYSBASE.ADMIN_FOLDER.'/includes/fn_module.php');

if(in_array('no_access', $permissions) || empty($permissions)){
    header('Location: '.DOCBASE.ADMIN_FOLDER.'/index.php');
    //echo '<script> window.location.href = "'.DOCBASE.ADMIN_FOLDER.'/no-access"; </script>';
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
?>
<!DOCTYPE html>
<head>
    <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_header_common.php'); ?>
    <link rel="stylesheet" href="<?php echo DOCBASE.ADMIN_FOLDER.'/css/pms.css'; ?>">
    
    <style>
        .input-group {
           width: 100%;
         }
        .input-group-addon{width:124px;}
.booking-right {
    position: absolute;
    top: 48px;
    right: 0;

}
.panel-body.mb15 {

    position: relative;

}
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
<body class="new_booking">
    <div id="wrapper">
        <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_top.php'); ?>
        <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h1 class="pull-left"><i class="fas fa-fw fa-<?php echo ICON; ?>"></i> New Booking</h1>
                            <div class="pull-left text-right">
                                &nbsp;&nbsp;
                               
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
        
        <div class="container-fluid">
            <div class="alert-container">
                <div class="alert alert-success alert-dismissable"></div>
                <div class="alert alert-warning alert-dismissable"></div>
                <div class="alert alert-danger alert-dismissable"></div>
            </div>
            <?php
            if($db !== false){
                if(in_array('all', $permissions) || in_array('add', $permissions)  ){ ?>
                    <div class="panel-body mb15">
                        <fieldset>
                            <!-- <legend>New Booking</legend> -->
                            
                                <div class="row">
                                    <form name="add_user_form" id="add_user_form" method="post" action="create_user.php">
                                       
                                        <div class="col-md-12">
                                         <input type="hidden" name="id_user" id="user_select" value="0">
                                          <div id="lang_0" class="">
                                            <div class="new_contact_detail contact_detail">
                                            <h2>Contact Details </h2>

                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel">Mobile &nbsp;<span class="red">*</span></span></div>
                                                <input type="text" name="user_mobile" id="user_mobile" value="" size="30" class="form-control" onkeyup="get_user_data(this.value)";>
                                               </div>
                                              <div class="field-notice" rel="user_mobile"></div>
                                           </div>

                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">First Name &nbsp;<span class="red">*</span></span></div>
                                                <input type="text" name="user_firstname" id="user_firstname" value="" rel="user_firstname"  size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_firstname"></div>
                                           </div>
                                            
                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">Last Name&nbsp;<span class="red">*</span></span></div>
                                                <input type="text" name="user_lastname" id="user_lastname" value="" rel="user_lastname" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_lastname"></div>
                                            </div>
                                           
                               
                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">E-mail&nbsp;<span class="red">*</span></span></div>
                                                <input type="text" name="user_email" id="user_email" value="" rel="user_email" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_email"></div>
                                           </div>

                                           </div>

                                           <div class="new_contact_detail billing_details">
                                            <h2>Billing Details </h2>

                                           <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">Address&nbsp;</span></div>
                                                <input type="text" name="user_address" id="user_address" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_address"></div>
                                           </div>
                                           <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">Postcode&nbsp;</span></div>
                                                <input type="text" name="user_postcode" id="user_postcode" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_postcode"></div>
                                           </div>
                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">City&nbsp;</span></div>
                                                <input type="text" name="user_city" id="user_city" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_city"></div>
                                           </div>
                                           <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">Country&nbsp;</span></div>
                                                <div class="">
                                                        <?php $result_countries = $db->query('SELECT * FROM pm_country ');  ?>
                                                        <select class="form-control" id="user_country" name="user_country" class="form-control">
                                                            <option>Select Country</option>
                                                                <?php foreach($result_countries as $key=>$country){
                                                                     $selected = ($country['name']=='India')?'selected="selected"':'';
                                                                     echo '<option value="'.$country['name'].'" '.$selected.'>'.$country['name'].'</option>';
                                                                    }
                                                                 ?>
                                                                    </select>
                                                             </div>
                                               </div>
                                              <div class="field-notice" rel="user_country"></div>
                                           </div>
                                           
                                           <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel">Company&nbsp;</span></div>
                                                <input type="text" name="user_company" id="user_company" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_company"></div>
                                           </div>
                                           
                                           <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel"><?php echo $texts['GOVID_TYPE']; ?><span class="red">*</span></span></div>
                                                <input type="text" name="user_govidtype" id="user_govidtype" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_govidtype"></div>
                                           </div>
                                           
                                            <div class="form-group">
                                              <div class="input-group">
                                                <div class="input-group-addon"> <span class="booklabel"><?php echo $texts['GOVID']; ?><span class="red">*</span></span></div>
                                                <input type="text" name="user_govid" id="user_govid" value="" size="30" class="form-control">
                                               </div>
                                              <div class="field-notice" rel="user_govid"></div>
                                           </div>

                                           <div class="form-group">
                                                <div style="display:none">
                                                    <button type="button" class="btn btn-default" name="create_user" id="create_user" >
                                                    <i id="spin" class="fa fa-spinner fa-spin"></i>Create user</button>
                                                </div>
                                                  
                                             </div>
                                          </div>
                                         </div>
                                        </div>
                                     </form>
                                     <form method="post" action="new.php"  class="ajax-form" id="create_booking">
                                      <div class="col-md-12">
                                        <input type="hidden" name="book" value="1">
                                        <input type="hidden" name="step" id="step" value="">
                                        <input type="hidden" name="destination_id" id="destination_id" value="0">
                                        <input type="hidden" name="id_user" id="select_user" value="0">
                                         <div class="new_contact_detail stay_details">
                                            <h2>Stay Details </h2>
                                         <div class="form-group">
                                            <label class="sr-only" for="Hotels"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel"> Hotels </span></div>
                                                  <?php 
                                                     if(in_array($_SESSION['admin']['type'], array('hotel','manager'))){
                                                           $result_hotels = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['admin']['id'].', users) AND lang = '.LANG_ID); 
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
                                                <div class="input-group-addon"><span class="booklabel"><?php echo $texts['FROM_DATE']; ?></span></div>
                                                <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" value="<?php echo $from_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel"> <?php echo $texts['TO_DATE']; ?></span></div>
                                                <input type="text" class="form-control datepicker" id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="to_date"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel">Nights</span></div>
                                                <input type="text" class="form-control" id="nights" rel="nights" name="nights" value="">
                                            </div>
                                            <div class="field-notice" rel="nights"></div>
                                        </div>
                                        <div class="row" style="display:none;">
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
                                       

                                        <div class="form-group rooms stay_details_rooms">
                                            <div class="input-group">
                                                <div id="rooms">Room not found!!</div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="float:right;">
                                            <a  class="sendAjaxForm" id="update_price"  data-action="update_booking.php" data-target="#total_booking" data-extratarget="#booking-amount" style="display:none"> </a>
                                            <a  class="btn btn-default " id="update_process" onclick="process(1);"><i id="spin3" class="fa fa-spinner fa-spin" style="display:none;"></i><span>Process booking</a>
                                         </div>
                                     </div>
                                    </div>
                                     <div class="booking-right" id="float_cart" style="display: none;">
                                          <span class="close" id="pclose">&times;</span>
                                         <div class="booking_left">
                                              <div class="form-group" class="services">
                                                <div class="input-group">
                                                <div id="book_services">Service not found!!</div> 
                                              </div>
                                           </div>
                                           <fieldset class="mb20">
                                                <div id="coupon_msg"></div>
                                                <legend>Do you have a coupon?</legend>
                                                <div class="form-group form-inline">
                                                    <input class="form-control" type="text" value="" name="coupon_code">
                                                    <a id="chk-coupn" class="btn btn-primary sendAjaxForm" data-action="update_booking.php" data-target="#total_booking" onclick="check_coupon();"><i class="fas fa-fw fa-check"></i></a>
                                                </div>
                                            </fieldset>
                                            <div id="total_booking" class="mb15">
                                         </div>
                                        </div>
                                    </div>
                               
                               </form>
                            </div> 
                          
                        </fieldset>
                    </div>
                    
                    <?php
                   
                }else echo '<p>'.$texts['ACCESS_DENIED'].'</p>';
            } ?>
        </div>
        </div>
    </div>
 
  <!-- Modal -->
   <div class="modal fade" id="xprice_modal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Your Price </h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12" id="pricediv" style="display:none;"></div>
            <div class="col-md-12" id="securitydiv">
                <div class="row">
                    <div class="col-md-12"><label for="title">Please Enter Security Password</label> <span id="sec_msg"></span></div>
                    <div class="col-md-12"><span id="sec_msg"></span></div>
                    <div class="col-md-10">
                        <div class="form-group">
                           <input class="form-control" type="password" id="usecurity" name="pass">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0)" class="btn btn-default " onclick="check_security();">Check</a>
                    </div>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
  
</body>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php include(SYSBASE.ADMIN_FOLDER.'/modules/default/nav_script.php');?> 
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
    }, "<br />Please specify a valid Mobile number");
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
              
              if (data == "true"){
                   var uid =$('#user_select').val();
                   if(uid==0){
                    return "This email already exist please try another one!!";
                   }else{
                       return 'true'; 
                   }
                }else {
                  return 'true';
                }
               }
              }
            },

           user_mobile:{
                required: true,
                phoneno: true
            },
           //user_address: 'required',
           user_govidtype: 'required',
           user_govid: 'required',

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
 
        user_mobile: 'Please Enter mobile!', 
        //user_address: 'Please Enter Address!',  
        user_govidtype: 'Please Enter Govt. ID!',  
        user_govid: 'Please Enter Govt. ID No.!',  
        
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
                        if(response>0){
                            $("#user_select").val(response);
                            $("#select_user").val(response);
                          }
                    }
                });
        
           }
  });
  
  $('#user_mobile').keypress(function(event){

       if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
           event.preventDefault(); //stop character from entering input
       }

   });
  var get_user_data = function(val){
      //alert(val);
       $.ajax({  
             url: 'get_user.php',
                    type: 'POST',
                    dataType: "json",
                    data: {mobile: val},
                    success: function(data){
                        if(data.id){
                          $("#select_user").val(data.id);
                          $("#user_select").val(data.id);
                          
                          $("#user_firstname").val(data.firstname);  
                          $("#user_lastname").val(data.lastname);  
                          $("#user_email").val(data.email);  
                          $("#user_address").val(data.address);  
                          $("#user_postcode").val(data.postcode);
                          $("#user_city").val(data.city);
                          $("#user_country").val(data.country);
                          $("#user_company").val(data.company);
                          $("#user_govidtype").val(data.govid_type);
                          $("#user_govid").val(data.govid);
                          $("#user_company").val(data.company);
                        }else{
                          $("#select_user").val(0);
                          $("#user_select").val(0);
                          
                          $("#user_firstname").val('');  
                          $("#user_lastname").val('');  
                          $("#user_email").val('');  
                          $("#user_address").val('');  
                          $("#user_postcode").val('');
                          $("#user_city").val('');
                          $("#user_country").val('');
                          $("#user_company").val('');
                          $("#user_govidtype").val('');
                          $("#user_govid").val('');
                          $("#user_company").val('');
                        }
                         //$("#rooms").html(html);
                         
                    }
         });
   }
   
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
        //$('#update_price').trigger("click");
             process(0);
          
        
    }
  
  var process = function(st){
      
      var id_user=$('#select_user').val();
      $("#total_booking").css('display','none');
      $("#spin3").css('display','block');
      var check = 0;

        $("#step").val('process');
        $("#rooms input[name='item[]']:checked").each(function (){
             check =  check +1;
        });
        if(id_user>=0){
            $('#add_user_form').submit();
        }

        setTimeout(function(){
        var id_user=$('#select_user').val();
        if(id_user>0){
           if(check>0){
              if(selectRoomNum()) {
                  if(selectAdultCount()) {
                       var form = $('form#create_booking');
                         setTimeout(function(){
                         $('#update_price').trigger("click");
                         $("#spin3").css('display','none');
                         $("#total_booking").css('display','block');
                         $("#float_cart").css('display','block');
                        },2000);
              }else {
                    //alert("Sorry, at least one to-do must be closed.");
                    if(st==1){
                       swal("Error", "Sorry, at least one adult.","error");
                    }
                }    
            }else {
                if(st==1){
                    //alert("Sorry, at least one to-do must be closed.");
                    swal("Error", "Sorry, at least one number of room.","error");
                  }
                }    
        }else{
               $("#step").val('');
               $("#spin3").css('display','none');
               if(st==1){
                 swal("Error", "Please check at least one room","error");
               }
         }
        }else{
            swal("Error", "Sorry, User data not found!","error");
        }
      },1500);
   }
   
    var check_coupon = function(){
        $('#coupon_msg').html('');
        var form = $('form#create_booking');
        var check = 0;
         $("#rooms input[name='item[]']:checked").each(function (){
             check =  check +1;
          });
        if(check>0){
            if(selectRoomNum()) {
                  if(selectAdultCount()) {
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
                   }else {
                    //alert("Sorry, at least one to-do must be closed.");
                    swal("Error", "Sorry, at least one adult.");
                }
               }else {
                    //alert("Sorry, at least one to-do must be closed.");
                    swal("Error", "Sorry, at least one number of room.","error");
                }
             }else{
               swal("Error", "Please check at least one room","error");
           }    
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
              if(selectRoomNum()) {
                  if(selectAdultCount()) {
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
                  }else {
                    //alert("Sorry, at least one to-do must be closed.");
                    swal("Error", "Sorry, at least one adult.", "error");
                }
              }else {
                    //alert("Sorry, at least one to-do must be closed.");
                    swal("Error", "Sorry, at least one number of room.","error");
                }
           }else{
               swal("Error", "Please check at least one room","error");
           }
  }


function selectRoomNum() {
    var flag = false;
    $('.num_room :selected').each(function() {
        if($(this).val() > 0) {
            flag = true;
            return false;
        }
    });
    return flag;
}

function selectAdultCount() {
    var flag = false;
    $('.adlt_count :selected').each(function() {
        if($(this).val() > 0) {
            flag = true;
            return false;
        }
    });
    return flag;
}
var get_modal_price = function(id){
    //alert(id);
    $('#usecurity').val('');
    $('#securitydiv').show(); 
    $('#pricediv').hide(); 
    var room_id = $('#'+id).attr('data-room');
    var room_price = $('#'+id).attr('data-price');
    $("#pricediv").html('');   
    $("#pricediv").html('<div class="form-group"><label for="title">Price (<?php echo CURRENCY_SIGN;?>)</label>' +
                        '<input id="manual_room_id" type="hidden"  name="price" value="'+room_id+'"/><input class="form-control" id="manual_price" type="text" placeholder="0.00" name="price" value="'+room_price+'"/><a href="javascript:void(0)" class="btn btn-default " onclick="chage_xprice()">Change</a>' +
                        '</div>'); //add input box
                  
    $("#xprice_modal").modal();
}
var check_security = function(){
        var pass = $('#usecurity').val();
        $.ajax({  
                url: 'check_security.php',
                type: 'POST',
                data: {pass: pass},
                success: function(res){
                //$('#coupon_msg').html(msg);
                setTimeout(function(){
                     if(res=='true'){
                       $('#securitydiv').hide(); 
                       $('#pricediv').show(); 
                     }else{
                       $('#sec_msg').html('<span style="color:red;">Invalid Security !! Please Enter Valied security! <span>')  
                     }
                    },1500);
                }
           });
}
var chage_xprice = function(){
    var room_id = $('#manual_room_id').val();
    var xprice = $('#manual_price').val();
          $.ajax({  
                url: 'change_xprice.php',
                type: 'POST',
                data: {room_id: room_id,price:xprice},
                success: function(res){
                setTimeout(function(){
                    if(res=='true'){
                       get_rooms();
                       $("#xprice_modal").modal('hide');
                     }
                    },1500);
                }
           });
}
$(document).ready(function() {
   
    $('#pclose').click(function(event) {
        $("#float_cart").css('display','none');
    });

    $('input[id="manual_price"]').keydown(function(event) {

     if(event.shiftKey && ((event.keyCode >=48 && event.keyCode <=57) 
             || (event.keyCode >=186 &&  event.keyCode <=222))){
        // Ensure that it is a number and stop the Special chars
         event.preventDefault();
     }
     else if ((event.shiftKey || event.ctrlKey) && (event.keyCode > 34 && event.keyCode < 40)){     
          // let it happen, don't do anything
     }      
     else{
        // Allow only backspace , delete, numbers               
        if (event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 39 ||event.keyCode == 37 
                || (event.keyCode >=48 && event.keyCode <=57)) {
            // let it happen, don't do anything
        }
        else {
           // Ensure that it is a number and stop the key press
                event.preventDefault();                   
        }
     }
    });
    $('#pricediv input#manual_price').keydown(function(event) {
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 ){

            }else{
                if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )){
                       event.preventDefault();

                    }
                }
        });

    $("div#rooms").on('click', '.plus', function () {
        //alert('tr');
      var id = $(this).attr("data-p");
      var valId=$('div#rooms #nrmv_'+id).val();
      var rooms=parseInt(valId)+1;
      var maxRoom = findMaxValue($('div#rooms select#num_chk_room_'+id));
      if(rooms>maxRoom){
        $('div#rooms #nrmv_'+id).val(maxRoom);
        $('div#rooms select#num_chk_room_'+id).val(maxRoom);
       }else{
        $('div#rooms #nrmv_'+id).val(rooms);
        $('div#rooms select#num_chk_room_'+id).val(rooms);
       }
       $('div#rooms select#num_chk_room_'+id).change();
      });

     $("div#rooms").on('click', '.minus', function () {
      var id = $(this).attr("data-m");
       var valId=$('div#rooms #nrmv_'+id).val();
       var rooms=parseInt(valId)-1;
       var maxRoom = findMaxValue($('div#rooms select#num_chk_room_'+id));
       if(rooms>=0){
          $('div#rooms #nrmv_'+id).val(rooms);
          $('div#rooms select#num_chk_room_'+id).val(rooms);
        }
       $('div#rooms select#num_chk_room_'+id).change();
     });
});

 function findMaxValue(element) {
      var maxValue = undefined;
      $('option', element).each(function() {
          var val = $(this).attr('value');
          val = parseInt(val, 10);
          if (maxValue === undefined || maxValue < val) {
              maxValue = val;
          }
      });
    return maxValue;
    }
</script>
</html>
<?php
$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
