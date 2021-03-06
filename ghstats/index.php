<?php
define("ADMIN", true);
require_once("../common/lib.php");
require_once("../common/define.php");
define("TITLE_ELEMENT", $texts['DASHBOARD']);
$csrf_token = get_token('list'); 
if(!isset($_SESSION['ghs'])){
    header("Location: login.php");
    exit();
}elseif($_SESSION['ghs']['type'] == "registered"){
    unset($_SESSION['ghs']);
    $_SESSION['msg_error'][] = "Access denied.";
    header("Location: login.php");
    exit();

}
require_once("includes/fn_module.php"); ?>
<!DOCTYPE html>
<head>
    <?php include("includes/inc_header_common.php"); ?>
      <script>
        $(function(){
            $('#from_picker, #start_picker').datepicker({
                dateFormat: 'dd/mm/yy',
                //minDate: 0,
                onClose: function (selectedDate, instance){
                    if(selectedDate != '') {
                        var relPicker = $('#'+$(this).attr('rel'));
                        var date2 = $('#from_picker').datepicker('getDate'); 
                          date2.setDate(date2.getDate()); 
                          $('#to_picker').datepicker('setDate', date2);
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
        <?php include("includes/inc_top.php"); ?>
          <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="pull-left"><i class="fas fa-fw fa-tachometer-alt"></i> <?php echo $texts['DASHBOARD']; ?></h1>
                             <div class="pull-left text-right">
                                    <a href="modules/stats_user/?view=list" class="btn btn-primary mt15 mb15">
                                        <i class="fas fa-fw fa-users"></i> Stats Users
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if(isset($_SESSION['ghs'])){
                $user_id = $_SESSION['ghs']['id'];
            }else{
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
                    if(isset($_GET['id_hotel'])){
                      $id_hotel=@$_GET['id_hotel'];
                    }
                    $id_destination = 0;
                    
                    $losnid = array();
                    if(isset($_GET['id_destination'])){
                      $id_destination=@$_GET['id_destination'];
                        if( is_numeric($_GET['id_destination']) && $_GET['id_destination'] > 0){
                           
                            $result_hotels = $db->query('SELECT id, title FROM pm_hotel WHERE  id_destination= '.$_GET['id_destination'].'  AND lang = '.DEFAULT_LANG);
                        	if($result_hotels !== false && $db->last_row_count() > 0){
                            		foreach($result_hotels as $key=>$hotel){
                            		   $losnid[]=$hotel['id'];
                            		
                            	 } 
                        	}
                        }
                     }
                     $book_ids=array();
                    $occupancy = 0;
                    $remain = 0;                   
                    $ard =0;
                    $revpar =0;
                    $aor=0;
                    $avhotel=0;
                    $from_time = time();
                    $to_time = time()+(3600);
                    
                    $from_date = gmdate('d/m/Y', $from_time);
                    $to_date = gmdate('d/m/Y', $to_time);
                    
                    if(isset($_GET['from_date'])) $from_date = htmlentities($_GET['from_date'], ENT_QUOTES, 'UTF-8');
                    if(isset($_GET['to_date'])) $to_date = htmlentities($_GET['to_date'], ENT_QUOTES, 'UTF-8');                    
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
                ?>

                <div class="project-tab" id="dashboard">
                     <!-- Nav tabs -->
                     <ul class="nav nav-tabs" role="tablist">
                        <!--<li role="presentation" ><a href="#home1" aria-controls="home1" role="tab" data-toggle="tab">Dashboard</a></li>-->
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Booking</a></li>
                     </ul>
                      <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane " id="home1">
                             <?php
                                if( $from_time){
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
                                 } ?>
                               
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="home">
                                   <form method="get" action="index.php">

                                    <div class="dash_form">
                                   
                                          <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><?php echo $texts['FROM_DATE']; ?></div>
                                                <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" value="<?php echo $from_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><?php echo $texts['TO_DATE']; ?></div>
                                                <input type="text" class="form-control datepicker" id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="to_picker"></div>
                                        </div>
                                         <!--<input type="hidden"  id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">-->
                                    
                                  
                                       <div class="form-group" style="display:none;">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel"> Destination </span></div>
                                                   <?php 
                                                     
                                                           $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = '.LANG_ID); 
                                                        
                                                    ?>
                                                  
                                                    <select class="form-control" name="id_destination" id="id_destination">
                                                        <option value="0">All</option>
                                                        <?php
                                                            foreach($result_location as $key=>$location){
                                                                
                                                                if($id_destination>0 && $location['id']==$id_destination ){ $select='selected="selected"'; }else{ $select='';}
                                                                echo '<option value="'.$location['id'].'" '.$select.'>'.$location['name'].'</option>';
                                                             }
                                                        ?>
                                                    </select>
                                            </div>
                                            <div class="field-notice" rel="id_hotel"></div>
                                        </div>
                                   
                                    
                                         <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel"> Hotels </span></div>
                                              
                                                  <?php 
                                                     if(in_array($_SESSION['ghs']['type'], array('hotel','manager'))){
                                                         $sql = 'SELECT * FROM pm_hotel WHERE checked = 1';
                                                         if($id_destination > 0){
                                                              $sql .= ' AND id_destination='.$id_destination;
                                                            }
                                                         $sql .= ' AND  FIND_IN_SET('.$_SESSION['ghs']['id'].', users) AND lang = '.LANG_ID;
                                                       }else{
                                                           $sql = 'SELECT * FROM pm_hotel WHERE checked = 1 AND  lang = '.LANG_ID;
                                                           if($id_destination > 0){
                                                              $sql .= ' AND id_destination='.$id_destination;
                                                            }
                                                           
                                                       }  
                                                       $result_hotels = $db->query($sql); 
                                                     ?>
                                                  
                                                    <select class="form-control" name="id_hotel" id="id_hotel">
                                                        <option  value="0">All</option>
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
                                              <button type="submit" class="btn btn-default" name="change_date">GO</button>
                                         </div>
                                         
                                    </div>
                                
                             </form>         
                        <div class="">
                            
                          <div class="row">
                           
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                   
                            <div class="row">
                                
                              
                                    
                                <!-----------------Latest Booking-------------------->
                        	<?php
                                 //$query_latest_booking = "SELECT *, id as id_booking, COUNT(*) as no_book, sum(case when `status` = 4 then 1 else 0 end) as paid_books  FROM `pm_booking` WHERE `status` = 4 AND  `add_date` >= $from_time  AND add_date <= $time_1d_after";
                                $tmpFD = explode('/', $from_date);
                                $tmpTD = explode('/', $to_date);
                                $newFd = gm_strtotime($tmpFD[2].'-'.$tmpFD[1].'-'.$tmpFD[0]. ' 00:00:00');
                                $newTd = gm_strtotime($tmpTD[2].'-'.$tmpTD[1].'-'.$tmpTD[0]. ' 23:59:59');
                                //  echo $newFd . ' '. $newTd; 
                                 
                                //  die;
                                // $query_latest_booking = "SELECT *, id as id_booking, COUNT(*) as no_book, 
                                // sum(case when `status` = 4 then 1 else 0 end) as paid_books  FROM `pm_booking` 
                                // WHERE `status` = 4 
                                // AND  `from_date` >= $from_time  AND from_date < $time_1d_after";

                                $query_latest_booking = "SELECT *, id as id_booking, COUNT(*) as no_book, 
                                sum(case when `status` = 4 then 1 else 0 end) as paid_books  FROM `pm_booking` 
                                WHERE `status` = 4 
                                AND  `from_date` >= $newFd  AND from_date <= $newTd";
                                if($id_hotel>0 ){ 
                                    $query_latest_booking .= " AND id_hotel =$id_hotel  "; 
                                }
                                $query_latest_booking .= " GROUP BY id_hotel ORDER BY `add_date` ASC ";
                                //echo $query_latest_booking; die;


                        	    $result_booking = $db->query($query_latest_booking);
                             	?>
                        			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        				<div class="dshb_booking_ltst">
                        					<h2>Bookings</h2>
                        					<table class="table table-hover" id="myTable"> 
											<thead> 
											<tr> 
											  <th>SL</th>
											  <!--<th>Booking Date</th>-->
											  <th>Hotel</th> 
											  <th>No. of Booking</th>
											  <th>Commission</th>
											 </tr> 
											</thead> 
											<tbody> 
											<?php 
											 if($result_booking !== false){
											    
    											foreach($result_booking as $key=>$lb){
    											    $id_hotel=$lb['id_hotel'];
    											    //$url=DOCBASE.'ghstats/all_booking.php?id_hotel='.$id_hotel.'&id_destination=0&form_date='.$from_date.'&to_date='.$to_date.'&change_date=';
    											 ?>
        											<tr onclick="booking_url(<?php echo $id_hotel;?>);"> 
            											 <td><?php echo $key+1; ?></td>
            											 <!--<td><?php //echo date('d-m-Y', $lb['add_date']); ?></td>-->
            											 <td><?php echo db_getFieldValue($db, 'pm_hotel', 'title', $id_hotel, $lang = 0); ?></td> 
            											 <td><?php echo  $lb['no_book']; ?></td>
            											 <td><?php  echo formatPrice(($lb['paid_books']*100)*CURRENCY_RATE);  ?></td>
        											</tr> 
    											<?php
    										  } ?> 
    										  
											<?php }else{ ?>
											<tr><td colspan="8" class="text-center">Data Not Available.</td></tr>
											<?php } ?>
											</tbody> 
											  <tfoot> <tr><th colspan="2"></th><th>Total Commission:</th> <th></th></tr> </tfoot> 
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
    
    <form method="get" id="bookfrm" action="">
      <input type="hidden" id="dest_id" name="id_destination" value="0" >
      <input type="hidden" id="htel_id" name="id_hotel" >
      <input type="hidden" id="frmdate"  name="from_date" >
      <input type="hidden" id="t2date"  name="to_date" >
      
    </form>
 
  <!--<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>-->
  <script>
  $( function() {
    $( "#from_picker" ).datepicker();
  } );
   var destination  = $('[name="id_destination"]');
   var hotels  = $('[name="id_hotel"]');

		function get_hotels_by_destination(){
		    var id_location = destination.val();
		    hotels.empty();
		    $.ajax({  
             url: 'get_hotel.php',
                    type: 'POST',
                    data: {id_location:id_location},
                    dataType :'json',
                    success: function(data){
                         var html ='';
                       $.each(data, function (key, entry) {
                            //html += '<option value="${entry.id}">${entry.title}</option>'
                             hotels.append($('<option></option>').attr('value', entry.id).text(entry.title));
                             //hotels.append(html);
                             
                          })
                    }
           });
		 }
		destination.on('change keyup', function(){
			get_hotels_by_destination();
		});
		
	
		
		
		var booking_url = function(hid){
		      var url = "<?php echo DOCBASE.'ghstats/all_booking.php'?>";
		      var from_picker = $("#from_picker").val();
		      var to_picker = $("#to_picker").val();
		        $("#dest_id").val(0);
		        $("#htel_id").val(hid);
		        $("#frmdate").val(from_picker);;
		        $("#t2date").val(to_picker);;
		        $("#bookfrm").attr("action", url);
		       $("#bookfrm").submit();
		    //window.location.href = url;
		}
		$('#myTable').DataTable( {
		    "pageLength": 50,
             dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        footer: true,
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    }
                ],
            "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
         
                    // Total over all pages
                   
                    total = api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0 );
         
                    // Total over this page
                    pageTotal = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0 );
         
                    // Update footer
                    /*$( api.column( 3 ).footer() ).html(
                        'Rs'+pageTotal +' ( Rs'+ total +' total)'
                    );*/
                    $( api.column( 3 ).footer() ).html('Rs '+api.column( 3,{page:'current'} ).data().sum());
                     
                }    
            } );
  </script>

</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
