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
                                    <a href="#" onclick="history.go(-1);" class="btn btn-primary mt15 mb15">
                                        <i class="fas fa-fw fa-users"></i>Back
                                    </a>
                                    <a href="modules/stats_user/?view=list" class="btn btn-primary mt15 mb15">
                                        <i class="fas fa-fw fa-users"></i> Stats Users
                                    </a>
                            </div>
                        </div>
                        

<button onclick="history.go(-1);">Back </button>


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
                                   <form method="get" action="index.php" style="display:none;">

                                    <div class="dash_form">
                                   
                                          <div class="form-group">
                                            <label class="sr-only" for="from"></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><?php echo $texts['DATE']; ?></div>
                                                <input type="text" class="form-control datepicker" id="from_picker" rel="to_picker" name="from_date" value="<?php echo $from_date; ?>">
                                            </div>
                                            <div class="field-notice" rel="from_date"></div>
                                        </div>
                                         <input type="hidden"  id="to_picker" rel="from_picker" name="to_date" value="<?php echo $to_date; ?>">
                                    
                                  
                                    <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="booklabel"> Destination </span></div>
                                              
                                                <?php 
                                                     if(in_array($_SESSION['ghs']['type'], array('hotel','manager'))){
                                                          //$result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  FIND_IN_SET('.$_SESSION['ghs']['id'].', users) AND lang = '.LANG_ID); 
                                                           $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = '.LANG_ID);
                                                     }else{
                                                           $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = '.LANG_ID); 
                                                       }  
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
                        	     $query_latest_booking = "SELECT * FROM  pm_booking AS pb LEFT JOIN pm_booking_room AS br ON br.id_booking = pb.id  WHERE pb.status = 4 AND pb.add_date >= $from_time  AND pb.add_date <= $time_1d_after";
                        	     if($id_hotel>0 ){ 
                        	          $query_latest_booking .= " AND pb.id_hotel =$id_hotel  "; 
                        	     }
                        	     $query_latest_booking .= " GROUP BY pb.id ORDER BY pb.id DESC ";
                        	     $result_booking = $db->query($query_latest_booking);
                             	?>
                        			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        				<div class="dshb_booking_ltst">
                        					
                        					<table class="table" id="myTable"> 
											<thead> 
    											<tr> 
    											<th>Booking ID</th>
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
											if(!empty($result_booking)){
											foreach($result_booking as $lb){
											     switch($lb['status']){
                                                case 1: $status = '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                                                case 2: $status = '<span class="label label-danger">'.$texts['CANCELLED'].'</span>'; break;
                                                case 3: $status = '<span class="label label-danger">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                                                case 4: $status = '<span class="label label-success">'.$texts['PAID'].'</span>'; break;
                                                default: $status = '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                                            }
											?>
											<tr> 
											<td> <a href="modules/booking/booking/popup-details.php" data-params="id=<?php echo $lb['id_booking']; ?>" title="Booking details" class="dropdown-item ajax-popup-link tips"><?php echo $lb['id_booking']; ?></a></td>
											<td><?php echo $lb['title']; ?></td> 
											<td><?php echo $lb['firstname'].' '.$lb['lastname']; ?></td> 
											<td><?php echo date('d-m-Y', $lb['from_date']); ?></td> 
											<td><?php echo date('d-m-Y', $lb['to_date']); ?></td> 
											<td><?php echo date('d-m-Y', $lb['add_date']); ?></td>
											<td><?php echo $status; ?></td>
											<td><?php echo formatPrice($lb['total']*CURRENCY_RATE); ?></td>
											
											</tr>  
    											<?php
    										  } 
											}else{ ?>
											<tr><td colspan="8" class="text-center">Data Not Available.</td></tr>
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
		
		var kpi_url_redirect = function(id){
		      var url = $('#'+id).attr('data-url');
		      var id_destination = $("#id_destination").val();
		      var id_hotel = $("#id_hotel").val();
		      var from_picker = $("#from_picker").val();
		        $("#dest_id").val(id_destination);
		        $("#htel_id").val(id_hotel);
		        $("#hid").val(id_hotel);
		        $("#frmdate").val(from_picker);
		        $("#dshfiter").attr("action", url);
		        //$("#dshfiter").submit();
		        $("#search").click();
		    //window.location.href = url;
		}
		
		var kpi_hotel_redirect = function(id){
		      var url = $('#'+id).attr('data-url');
		      var id_destination = $("#id_destination").val();
		      var id_hotel = $("#id_hotel").val();
		      var from_picker = $("#from_picker").val();
		        $("#hkdest_id").val(id_destination);
		        if(id_hotel>0){
		         $("#hid").val(id_hotel);   
		        }else{
		            $("#hid").val('');
		        }
		        
		        $("#hotelfiter").attr("action", url);
		        //$("#dshfiter").submit();
		        $("#hksearch").click();
		    //window.location.href = url;
		}
		$('#myTable').DataTable({
           "pageLength": 25,
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
            "initComplete": function(settings, json) {
                    var select = '<div class="booklabel"> Booking Status: <select class="input-sm" name="status" id="status" onchange="status_filter(this.value);"><option value="">All</option><option value="Paid">Paid</option><option value="Pending">Pending</option><option value="Cancelled">Cancelled</option><option value="Rejected payment">Rejected payment</option></select></div>';
                    //$('#myTable_filter').append(select);
                    //$('#myTable_filter label').hide();
                  }       
            } );

        var status_filter = function(val){
            //alert(val);
            $('#myTable_filter input').val(val).keyup();
           }
  </script>

</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
