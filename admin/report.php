<?php
session_start();

define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/setenv.php');
require_once ('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        
$config_file = '../common/config.php';
$htaccess_file = '../.htaccess';
$field_notice = array();
$config_tmp = array();
$db = false;
$action = '';

require_once('../common/define.php');

if(!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit();
}elseif($_SESSION['admin']['type'] == 'registered'){
    unset($_SESSION['admin']);
    $_SESSION['msg_error'][] = 'Access denied.<br/>';
    header('Location: login.php');
    exit();
}

define('TITLE_ELEMENT', 'Reports');

//require_once(SYSBASE.ADMIN_FOLDER.'/includes/fn_actions.php');

//$_SESSION['module_referer'] = MODULE;
//$csrf_token = get_token('list');
                    
$from_time = time();
$to_time = time()+(86400*31);

$from_date = gmdate('d/m/Y', $from_time);
$to_date = gmdate('d/m/Y', $to_time);
$_SESSION['msg_time'] = time();
$_SESSION['msg_report'] = '';
require_once('includes/fn_module.php');              
?>
<!DOCTYPE html>
<head>
    <?php //include(SYSBASE.ADMIN_FOLDER.'/includes/inc_header_common.php'); ?>
    <?php include('includes/inc_header_common.php'); ?>   
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
	
		/* Style the tab */
		.tab {
		  overflow: hidden;
		  border: 1px solid #ccc;
		  background-color: #f1f1f1;
		}
		
		/* Style the buttons inside the tab */
		.tab button {
		  background-color: inherit;
		  float: left;
		  border: none;
		  outline: none;
		  cursor: pointer;
		  padding: 14px 16px;
		  transition: 0.3s;
		  font-size: 17px;
		}
		
		/* Change background color of buttons on hover */
		.tab button:hover {
		  background-color: #ddd;
		}
		
		/* Create an active/current tablink class */
		.tab button.active {
		  background-color: #ccc;
		}
		
		/* Style the tab content */
		.tabcontent {
		  display: none;
		  padding: 6px 12px;
		  border: 1px solid #ccc;
		  border-top: none;
		}
		
	</style>
    
</head>
<body>
	<div id="overlay"><div id="loading"></div></div>
    <div id="wrapper">
        <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_top.php'); ?>
        <div id="page-wrapper">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <h1 class="pull-left"><i class="fas fa-fw fa-<?php echo ICON; ?>"></i> <?php echo "Reports"; ?></h1>                            
                                                        
                            <?php /*?><div class="pull-left text-right">
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
                            </div><?php */?>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="container-fluid">
                <div class="form_wrapper aviabalites_panel">
                    <div class="panel-default">
                        <div class="alert-container">
                            <div class="alert alert-success alert-dismissable"></div>
                            <div class="alert alert-warning alert-dismissable"></div>
                            <div class="alert alert-danger alert-dismissable"></div>
                        </div>
                        
                        <?php
                            
                            if(isset($_POST['report_submit'])){
                                
                                if(!empty($_POST['booking_col'])){ //Get Excel Colomn Headers
                                    $serialCol = $_POST['booking_col'];
                                    $serialColhidden = $_POST['booking_col_hidden']; //Get Select Params	for Booking	Details	
                                } else if(!empty($_POST['billing_col'])) {
                                    $serialCol = $_POST['billing_col'];
                                    $serialColhidden = $_POST['billing_col_hidden']; //Get Select Params	for Billing	Details
                                } else if(!empty($_POST['payment_col'])) {
                                    $serialCol = $_POST['payment_col'];
                                    $serialColhidden = $_POST['payment_col_hidden']; //Get Select Params	for Payment	Details	
                                }
                                
                                /*if(!empty($_POST['booking_col_hidden'])){ //Get Excel query Headers
                                    $serialColhidden = $_POST['booking_col_hidden'];
                                } else if(!empty($_POST['billing_col_hidden'])) {
                                    $serialColhidden = $_POST['billing_col_hidden'];
                                } else {
                                    $serialColhidden = $_POST['payment_col_hidden'];
                                }*/
                                                    
                                //echo "<pre>"; print_r($serialCol);
                                
                                if(!empty($_POST['report_name_booking'])){ //Get Execl Name
                                    $reportName = $_POST['report_name_booking'];
                                } else if(!empty($_POST['report_name_billing'])) {
                                    $reportName = $_POST['report_name_billing'];
                                } else {
                                    $reportName = $_POST['report_name_payment'];
                                }
                                $hotelId = $_POST['report_hotel_id']; //Get Hotel Id
								$fromDate = strtotime($_POST['report_from_date'])+86400;
								$toDate = strtotime($_POST['report_to_date'])+86400;
								$custReportname = $_POST['custom_report_name']; //Get Hotel Id
                                $selectValues = implode(', ', array_filter($serialColhidden));
                                if(!empty($_POST['booking_col'])){ //For Booking Report	
                                    //$resultSql = $db->query('SELECT '.$selectValues.' FROM pm_booking WHERE id_hotel = "'.$hotelId.'"');
                                    $hotelPartQuery = '';
                                    if($hotelId != 'All'){
                                        $hotelPartQuery = 'a.id_hotel = "'.$hotelId.'" AND';
                                    }
									$resultSql = $db->query('SELECT '.$selectValues.' FROM pm_booking a INNER JOIN pm_hotel b ON a.id_hotel = b.id WHERE '.$hotelPartQuery.' (a.from_date between "'.$fromDate.'" and "'.$toDate.'")');
                                    $rowResult = $resultSql->fetchAll(PDO::FETCH_ASSOC);
                                    //echo "<pre>"; print_r($rowResult);
                                    //Gereate Excel And Upload
                                        $spreadsheet = new Spreadsheet();
                                        $sheet = $spreadsheet->getActiveSheet();
                                        $col = 1;
                                        foreach($serialCol as $key=>$value) {
                                            $sheet->setCellValueByColumnAndRow($col, 1, $value);
                                            $col++;
                                        }
                                        //$coll = 1;
                                        $row = 2;
                                        foreach($rowResult as $key=>$rows) {							
                                            $coll = 1;
                                            $pair=0;
                                            $str="";
                                            foreach($rows as $k=>$cols){
                                                $string_val=$cols;
                                                if($k == "from_date"){
                                                    $string_val = date('Y-m-d', $cols);
                                                } else if($k == "to_date"){
                                                    $string_val = date('Y-m-d', $cols);
                                                }else if($k == "add_date"){
                                                    $string_val = date('Y-m-d', $cols);
                                                }else if($k == "firstname" || $k == "lastname"){
                                                    $str.=$cols." ";
                                                    $pair=1;
                                                    if($k == "lastname"){
                                                        $string_val =$str;
                                                        $pair=0;
                                                        $str = "";
                                                    }
                                                } else if($k == "status"){
                                                    if($cols == 1){
                                                        $string_val = "Pending";
                                                    } else if($cols == 2){
                                                        $string_val = "Cancelled";
                                                    } else if($cols == 3){
                                                        $string_val = "Payment Rejected";
                                                    } else if($cols == 4){
                                                        $string_val = "Completed";
                                                    }
                                                } else if($k == "checked_in" || $k == "checked_out"){
                                                    if($k == "checked_in" && $cols!=""){
                                                        $str.= "Checked ".$cols;
                                                    }else if($k == "checked_out" && $cols!=""){
                                                        $str.= "/".$cols;
                                                    } else if($k == "checked_in" && $cols==""){
														$str.= "Pending";
													}
                                                    $pair=1;
                                                    if($k == "checked_out"){
                                                        $string_val =$str;
                                                        $pair=0;
                                                        $str = "";
                                                    }
                                                }
                                                if($pair==0){
                                                    //echo $string_val."<br>";
                                                    $sheet->setCellValueByColumnAndRow($coll, $row, $string_val);
                                                    $coll++;
                                                }
                                            }
                                            $row++;
                                        }						
                                        $writer = new Xlsx($spreadsheet);
										if(!empty($custReportname)){
											$fileName = $custReportname."_".time().'.xlsx';
										} else {
											$fileName = $reportName."_".time().'.xlsx';
										}
                                        //$fileName = $reportName."_".time().'.xlsx';
                                        $dFile = $writer->save(SYSBASE.'medias/report/'.$fileName);
                                                        
                                    //Gereate Excel And Upload
                                
                                } else if(!empty($_POST['billing_col'])){ //For Billing Report							
                                    $resultSql = $db->query('SELECT '.$selectValues.' FROM pm_booking a LEFT JOIN pm_booking_payment b ON a.id = b.id_booking LEFT JOIN pm_booking_tax c on a.id = c.id_booking INNER JOIN pm_hotel d ON a.id_hotel = d.id WHERE a.id_hotel = "'.$hotelId.'" AND (a.from_date between "'.$fromDate.'" and "'.$toDate.'")');
                                    $rowResult = $resultSql->fetchAll(PDO::FETCH_ASSOC);
                                    //echo "<pre>"; print_r($rowResult);
                                    //Gereate Excel And Upload
                                        $spreadsheet = new Spreadsheet();
                                        $sheet = $spreadsheet->getActiveSheet();
                                        $col = 1;
                                        foreach($serialCol as $key=>$value) {
                                            $sheet->setCellValueByColumnAndRow($col, 1, $value);
                                            $col++;
                                        }
                                        
                                        //$coll = 1;
                                        $row = 2;
                                        foreach($rowResult as $key=>$rows) {							
                                            $coll = 1;
                                            $pair=0;
                                            foreach($rows as $k=>$cols){
                                                $string_val=$cols;
                                                
                                                if($k == "date"){
                                                    if ($cols!="") {
                                                      $string_val = date('Y-m-d', $cols);
                                                    }else{
                                                        $string_val = '';
                                                    }
                                                    
                                                } 								
                                                
                                                if($pair==0){
                                                    //echo $string_val."<br>";
                                                    $sheet->setCellValueByColumnAndRow($coll, $row, $string_val);
                                                    $coll++;
                                                }
                                                
                                            }
                                            
                                            $row++;
                                        }						
                                        
                                        $writer = new Xlsx($spreadsheet);
                                        if(!empty($custReportname)){
											$fileName = $custReportname."_".time().'.xlsx';
										} else {
											$fileName = $reportName."_".time().'.xlsx';
										}
                                        $dFile = $writer->save(SYSBASE.'medias/report/'.$fileName);
                                                        
                                    //Gereate Excel And Upload
                                    
                                } else if(!empty($_POST['payment_col'])){ //For Payment Report
                                    
                                    $resultSql = $db->query('SELECT '.$selectValues.' FROM pm_booking a LEFT JOIN pm_booking_payment b ON a.id = b.id_booking LEFT JOIN pm_booking_cancel c on a.id = c.id_booking INNER JOIN pm_hotel d ON a.id_hotel = d.id WHERE a.id_hotel = "'.$hotelId.'" AND (a.from_date between "'.$fromDate.'" and "'.$toDate.'")');
                                    $rowResult = $resultSql->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    //echo "<pre>"; print_r($rowResult); die;
                                    
                                    //Gereate Excel And Upload
                                
                                        $spreadsheet = new Spreadsheet();
                                        $sheet = $spreadsheet->getActiveSheet();
                                        $col = 1;
                                        foreach($serialCol as $key=>$value) {
                                            $sheet->setCellValueByColumnAndRow($col, 1, $value);
                                            $col++;
                                        }
                                        
                                        //$coll = 1;
                                        $row = 2;
                                        foreach($rowResult as $key=>$rows) {							
                                            $coll = 1;
                                            $pair=0;
                                            foreach($rows as $k=>$cols){
                                                $string_val=$cols;
                                                if($k == "payment_date"){
                                                    $string_val = date('Y-m-d', $cols);
                                                } else if($k == "status"){
                                                    if($cols == 1){
                                                        $string_val = "Pending";
                                                    } else if($cols == 2){
                                                        $string_val = "Cancelled";
                                                    } else if($cols == 3){
                                                        $string_val = "Payment Rejected";
                                                    } else if($cols == 4){
                                                        $string_val = "Completed";
                                                    }
                                                } else if($k == "refund_time"){
                                                    $convTime = gm_strtotime($cols);
                                                    $string_val = date('Y-m-d', $convTime);
                                                }
                                                if($pair==0){
                                                    //echo $string_val."<br>";
                                                    $sheet->setCellValueByColumnAndRow($coll, $row, $string_val);
                                                    $coll++;
                                                }
                                            }
                                            $row++;
                                        }
                                        $writer = new Xlsx($spreadsheet);
                                        if(!empty($custReportname)){
											$fileName = $custReportname."_".time().'.xlsx';
										} else {
											$fileName = $reportName."_".time().'.xlsx';
										}
                                        $dFile = $writer->save(SYSBASE.'medias/report/'.$fileName);
                                                        
                                    //Gereate Excel And Upload							
                                    
                                }
                                                    
                                //Insert Data
                                $data = array();
                                $data['hotel_id'] = $hotelId;
								if(!empty($custReportname)){
									$data['report_name'] = $custReportname;
								} else {
									$data['report_name'] = $reportName;
								}                                
                                $data['report_content'] = $fileName; //Excel Path
                                $data['created_by'] = $_SESSION['admin']['id'];
                                $data['created_on'] = date('Y-m-d h:i:s');
                                
                                $result_report = db_prepareInsert($db, 'pm_report', $data);
            
                                if($result_report->execute() !== false){							
                                    $_SESSION['msg_time'] = time();
                                    $_SESSION['msg_report'] = '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Report Successfully Listed.</div>';
                                    echo '<script>window.location.href = "report.php";</script>';
                                } else {
                                    $_SESSION['msg_time'] = time();
                                    $_SESSION['msg_report'] = '<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Report Not Listed. Try Again!</div>';
                                    echo '<script>window.location.href = "report.php";</script>';
                                }
                                
                            }
                        ?>
                        
                        <?php
                            if(!empty($_REQUEST['hotel_id'])){											
                        ?>					
                                
                                <div class="generate_report">
                                
                                    <div class="tab">
                                      <button class="tablinks active" id="booking_report" onclick="openReport(event, 'Booking')">Booking Report</button>
                                      <button class="tablinks" id="billing_report" onclick="openReport(event, 'Billing')">Billing Report</button>
                                      <button class="tablinks" id="payment_report" onclick="openReport(event, 'Payment')">Payment Report</button>
                                    </div>
                                    
                                    <form action="" method="post" id="final_report" name="final_report">
                                    
                                        <!-- Tab content -->
                                        <div id="Booking" class="tabcontent" style="display: block;">
                                          <h3>Booking Report</h3>
                                          <div class="row">
                                            <input type="hidden" name="report_name_booking" id="report_name" value="Booking Report">
                                            <input type="hidden" name="report_hotel_id" id="report_hotel_id" value="<?php echo $_REQUEST['hotel_id']; ?>">
                                            <input type="hidden" name="report_from_date" id="report_from_date" value="<?php echo $_REQUEST['from_range']; ?>">
                                            <input type="hidden" name="report_to_date" id="report_to_id" value="<?php echo $_REQUEST['to_range']; ?>">
                                            <input type="hidden" name="custom_report_name" class="custom_report_name" value="">                                            
                                            <div class="col-sm-3">
                                                <label>Booking Id</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.id" value="Booking Id">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                            <div class="booking_hidden" style="display:none;">
                                                <input type="hidden" name="booking_col[]" class="booking_col" id="booking_hotel" value="Hotel Name">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" id="booking_hidden_hotel" value="b.title">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Booking Date</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.add_date" value="Booking Date">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>CheckIn Date</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.from_date" value="CheckIn Date">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>CheckOut Date</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.to_date" value="CheckOut Date">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Booking Amount</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.total" value="Booking Amount">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Booking Payment Status</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.status" value="Booking Payment Status">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Booking Source</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.source" value="Booking Source">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Booking Status</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.checked_in, a.checked_out" value="Booking Status">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Guest Name</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.firstname, a.lastname" value="Guest Name">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Guest Email</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.email" value="Guest Email">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Guest Country</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.country" value="Guest Country">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">	
                                                <label>Room Nights</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.nights" value="Room Nights">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>No of Adults</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.adults" value="No of Adults">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>No of Children</label>
                                                <input type="checkbox" name="booking_col[]" class="booking_col" data-value="a.children" value="No of Children">
                                                <input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                          
                                          </div>
                                        </div>
                                        
                                        <div id="Billing" class="tabcontent">
                                          <h3>Billing Report</h3>
                                          <div class="row">
                                            <input type="hidden" name="report_name_billing" id="report_name" value="">
                                            <input type="hidden" name="report_hotel_id" id="report_hotel_id" value="<?php echo $_REQUEST['hotel_id']; ?>">
                                            <input type="hidden" name="report_from_date" id="report_from_date" value="<?php echo $_REQUEST['from_range']; ?>">
                                            <input type="hidden" name="report_to_date" id="report_to_id" value="<?php echo $_REQUEST['to_range']; ?>">
                                            <input type="hidden" name="custom_report_name" class="custom_report_name" value="">
                                            <div class="col-sm-3">
                                                <label>Booking Id</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="a.id as booking_id" value="Booking Id">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                            <div class="billing_hidden" style="display:none;">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Bill Amount</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="a.total as billing_amount" value="Bill Amount">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Bill Creation Date</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="b.date" value="Bill Creation Date">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Bill Id</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="b.id as bill_id" value="Bill Id">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Tax Type</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="c.name as tax_type" value="Tax Type">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Total Base Amount</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="a.amount as base_amount" value="Total Base Amount">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>                            
                                            <div class="col-sm-3">
                                                <label>Tax Amount</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="a.tax_amount" value="Total Tax Amount"> 
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Total Final Amount</label>
                                                <input type="checkbox" name="billing_col[]" class="booking_col" data-value="a.total as total_amount" value="Total Final Amount">
                                                <input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                          </div>
                                        </div>
                                        
                                        <div id="Payment" class="tabcontent">
                                          <h3>Payment Report</h3>
                                          <div class="row">
                                            <input type="hidden" name="report_name_payment" id="report_name" value="">
                                            <input type="hidden" name="report_hotel_id" id="report_hotel_id" value="<?php echo $_REQUEST['hotel_id']; ?>">
                                            <input type="hidden" name="report_from_date" id="report_from_date" value="<?php echo $_REQUEST['from_range']; ?>">
                                            <input type="hidden" name="report_to_date" id="report_to_id" value="<?php echo $_REQUEST['to_range']; ?>">
                                            <input type="hidden" name="custom_report_name" class="custom_report_name" value="">
                                            
                                            <div class="col-sm-3">
                                                <label>Booking ID</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.id" value="Booking ID">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                            <div class="payment_hidden" style="display:none;">
                                                
                                            </div>                                            
                                            
                                            <div class="Payment Amount col-sm-3">
                                                <label>Payment Amount</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.total as payment_amount" value="Payment Amount">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                            <!--<div class="payment_hidden" style="display:none;">
                                                
                                            </div>-->
                                            
                                            <div class="col-sm-3">
                                                <label>Transaction Created Date</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.payment_date as payment_date" value="Transaction Created Date">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <!--<div class="col-sm-3">
                                                <input type="checkbox" name="payment_col[]" class="booking_col" value="Payment Merchant">Payment Merchant
                                            </div>-->
                                            <div class="col-sm-3">
                                                <label>Payment Mode</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.payment_mode" value="Payment Mode">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Payment Status</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.status" value="Payment Status">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Payment Type</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="b.method as payment_type" value="Payment Type">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Refund Amount</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="c.refund_amount" value="Refund Amount">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Refund Create Time</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="c.added_date as refund_time" value="Refund Create Time">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Refund Guest Id</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="a.id_user as guest_id" value="Refund Guest Id">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Refund Id</label>
                                                <input type="checkbox" name="payment_col[]" class="booking_col" data-value="c.id as refund_id" value="Refund Id">
                                                <input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" value="">
                                            </div>
                                            
                                          </div>
                                        </div>
                                    
                                        <!--<input type="submit" name="report_submit" class="report_submit" value="Save">-->
                                        <!--<button class="report_submit">Save</button>-->
                                        <input type="hidden" name="report_submit" class="report_submit" value="Save">
                                        <a href="javascript:void(0);" class="report_submit">Save</a>
                                    
                                    </form>
                                    
                                </div>
                                
                                
                                <!-- Modal -->
                                <div id="customTitle" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close custom_name_close">&times;</button>
                                        <h4 class="modal-title">Generate Report</h4>
                                      </div>
                                      <div class="modal-body">
                                      	<label>Report Name</label>
                                        <input type="text" id="custom_name_value" value="">
                                        <div id="custom_name_value_error" style="color:red;"></div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" id="custom_name_save">Save</button>
                                      </div>
                                    </div>
                                
                                  </div>
                                </div>
                                
                                
                        <?php		
                            } else {						
                                // $result_report = $db->query('SELECT a.*, CONCAT(b.firstname," ",b.lastname) as name, c.title 
                                // FROM pm_report a INNER JOIN pm_user b ON a.created_by = b.id 
                                // INNER JOIN pm_hotel c ON a.hotel_id = c.id 
                                // WHERE a.created_by = "'.$_SESSION['admin']['id'].'" ORDER BY a.id DESC');
                                $result_report = $db->query('SELECT a.*, CONCAT(b.firstname," ",b.lastname) as name
                                FROM pm_report a INNER JOIN pm_user b ON a.created_by = b.id 
                                WHERE a.created_by = "'.$_SESSION['admin']['id'].'" ORDER BY a.id DESC');
                                $rowReport = $result_report->fetchAll();
                                
                                $resultHotel = $db->query('SELECT id, title FROM pm_hotel WHERE FIND_IN_SET('.$_SESSION['admin']['id'].', users)');
                                $rowHotels = $resultHotel->fetchAll();
                                
                                //echo "<pre>"; print_r($rowHotel);
								
								if (isset($_REQUEST['reportId'])){ //FOR REPORT DELETE
									$reportId = $_REQUEST['reportId'];
									
									$resultDelete = $db->query('DELETE FROM pm_report WHERE id = "'.$reportId.'"');
									
									if($resultDelete->execute() !== false){							
										$_SESSION['msg_time'] = time();
										$_SESSION['msg_report'] = '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Report Successfully Deleted.</div>';
										echo '<script>window.location.href = "report.php";</script>';
									} else {
										$_SESSION['msg_time'] = time();
										$_SESSION['msg_report'] = '<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Report Not Deleted. Try Again!</div>';
										echo '<script>window.location.href = "report.php";</script>';
									}
								}
                        ?>
                                <?php
                                    if (time() - $_SESSION['msg_time'] < 5) { // 300 seconds = 5 minutes
                                        echo $_SESSION['msg_report'];
                                    } else {
                                        // sorry, you're out of time
                                       unset($_SESSION['msg_report']); // and unset any other session vars for this task
                                       unset($_SESSION['msg_time']);
                                    }
                                    
                                ?>
                                
                                <div class="hotel_select">
                                	
                                    <form id="report_select_submit" method="post" action="">         
                                            <div class="form-group">
                                            	<label>Select Hotel Name</label>
                                                <select name="hotel_id" id="select_hotel">
                                                    <option value="">Select Hotel</option>
                                                    <option value="All">All Hotels</option>
                                                    <?php foreach($rowHotels as $rowHotel){ ?>
                                                        <option value="<?php echo $rowHotel['id']; ?>"><?php echo $rowHotel['title']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div id="select_hotel_error" style="color:red;"></div>
                                            </div>
                                            <div class="form-group">
                                                
                                                <div class="date input-group" id="fromdatepicker" data-date-format="yyyy-mm-dd">
                                                	<label>From Date</label>
                                                    <input type="text" id="from_range" class="input-sm form-control" name="from_range" placeholder="From Date" />                                            		<span class="input-group-addon dateicon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    <div id="from_range_error" style="color:red;"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            	<div class="date input-group" id="todatepicker" data-date-format="yyyy-mm-dd">
                                                	<label>To Date</label>
                                                    <input type="text" id="to_range" class="input-sm form-control" name="to_range" placeholder="To Date" /> 
                                                    <span class="input-group-addon dateicon2"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    <div id="to_range_error" style="color:red;"></div>
                                                </div> 
                                            </div>
                                            <div class="form-group">                                        	
                                                <div class="create_report_button">
                                                    <!--<a class="report_create" href="">+ Create New</a>-->
                                                    <input class="report_create" type="submit" value="Go">
                                                </div>
                                            </div>
                                    </form>
                                </div>
                                <div class="hotel_report">		
                                    <table class="table table-striped table-bordered" style="width:100%" id="report_base">
                                        <thead>
                                            <tr>
                                                <th>Sl No.</th>
                                                <!-- <th>Hotel</th> -->
                                                <th>Report Name</th>
                                                <th>Created On</th>
                                                <th>Created By</th>
                                                <th class="no-sort"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php if(!empty($rowReport)){ ?>
                                                <?php foreach($rowReport as $rowRep){ ?>
                                                	<?php if($i <= 10){ ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <!-- <td><?php echo $rowRep['title']; ?></td> -->
                                                            <td><?php echo $rowRep['report_name']; ?></td>
                                                            <td><?php echo $rowRep['created_on']; ?></td>
                                                            <td><?php echo $rowRep['name']; ?></td>
                                                            <td class="text-center action_btn">
                                                            	<a title="Download Report" class="tips dropdown-item" href="<?php echo DOCBASE.'medias/report/'.$rowRep['report_content']; ?>"><i class="fa fa-download"></i></a> 
                                                                <a title="Delete Report" class="tips dropdown-item" href="?reportId=<?php echo $rowRep['id']; ?>"><i class="fa fa-trash"></i></a>                                                               
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                    <tr>
                                                        <td><?php echo "Reports Not Found!"; ?></td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    
                                    </table>
                                </div>
                        <?php		
                            }
                        ?>
                    </div></div>
            </div>
            
        </div>
    </div>
   
    <script>
	
		$("#booking_report").click(function(e) {
            var getName = $("#booking_report").html();
			
			$("#Booking #report_name").val(getName);
			$("#Billing #report_name").val('');
			$("#Payment #report_name").val('');
			
			$('#Billing .booking_col, #Payment .booking_col').removeAttr('checked'); 
			//$('#Billing #report_select, #Payment #report_select').val('');
			
			$('.booking_hidden').append('<input type="hidden" name="booking_col[]" class="booking_col" id="booking_hotel" value="Hotel Name"><input type="hidden" name="booking_col_hidden[]" class="booking_col_hidden" id="booking_hidden_hotel" value="b.title">');
			$('#billing_hotel, #payment_hotel').remove();
			$('#billing_hidden_hotel, #payment_hidden_hotel').remove();
			
        });
		
		$("#billing_report").click(function(e) {
            var getName = $("#billing_report").html();
			
			$("#Booking #report_name").val('');
			$("#Billing #report_name").val(getName);
			$("#Payment #report_name").val('');
			
			$('#Booking .booking_col, #Payment .booking_col').removeAttr('checked');
			//$('#Booking #report_select, #Payment #report_select').val('');
			
			$('.billing_hidden').append('<input type="hidden" name="billing_col[]" class="booking_col" id="billing_hotel" value="Hotel Name"><input type="hidden" name="billing_col_hidden[]" class="booking_col_hidden" id="billing_hidden_hotel" value="d.title">');
			$('#booking_hotel, #payment_hotel').remove();
			$('#booking_hidden_hotel, #payment_hidden_hotel').remove();
			
        });
		
		$("#payment_report").click(function(e) {
            var getName = $("#payment_report").html();
			
			$("#Booking #report_name").val('');
			$("#Billing #report_name").val('');
			$("#Payment #report_name").val(getName);
			
			$('#Booking .booking_col, #Billing .booking_col').removeAttr('checked');
			//$('#Booking #report_select, #Billing #report_select').val('');
			
			$('.payment_hidden').append('<input type="hidden" name="payment_col[]" class="booking_col" id="payment_hotel" value="Hotel Name"><input type="hidden" name="payment_col_hidden[]" class="booking_col_hidden" id="payment_hidden_hotel" value="d.title">');
			$('#booking_hotel, #billing_hotel').remove();
			$('#booking_hidden_hotel, #billing_hidden_hotel').remove();
			
        });
		
		
		$("#Booking .booking_col").change(function() {
			if(this.checked) {
				var getVal = $(this).data('value');
				$(this).next('.booking_col_hidden').val(getVal);
			} else {
				$(this).next('.booking_col_hidden').val('');
			}
		});
		
		
		$("#Billing .booking_col").change(function() {
			if(this.checked) {
				var getVal = $(this).data('value');
				$(this).next('.booking_col_hidden').val(getVal);
			} else {
				$(this).next('.booking_col_hidden').val('');
			}
		});
		
		
		$("#Payment .booking_col").change(function() {
			if(this.checked) {
				var getVal = $(this).data('value');
				$(this).next('.booking_col_hidden').val(getVal);
			} else {
				$(this).next('.booking_col_hidden').val('');
			}
		});
		
		
		
		$("#report_select_submit").on("submit", function(e){
			
			var selectHotel    = $("#select_hotel");
			var fromRange    = $("#from_range");
			var toRange    = $("#to_range");
			
			var selectHotelVal = selectHotel.val();
			var fromRangeVal = fromRange.val();
			var toRangeVal = toRange.val();
			
			if(selectHotelVal == ''){
				$("#select_hotel_error").html('Please Select Hotel.');	
				$("#from_range_error").html('');	
				$("#to_range_error").html('');
				selectHotel.focus();	
				return false;	
			} else if(fromRangeVal == ''){
				$("#select_hotel_error").html('');	
				$("#from_range_error").html('Please Select From Date.');	
				$("#to_range_error").html('');
				fromRange.focus();	
				return false;	
			} else if(toRangeVal == ''){
				$("#select_hotel_error").html('');	
				$("#from_range_error").html('');	
				$("#to_range_error").html('Please Select To Date.');
				toRange.focus();	
				return false;	
			} else {
				$("#select_hotel_error").html('');	
				$("#from_range_error").html('');	
				$("#to_range_error").html('');
				
				return true;
			}		
			
			//e.preventDefault();
		});
		
		
		//$("#final_report").on("submit", function(e){
		$(".report_submit").click(function(e) {						
			$('#customTitle').modal('show');
			//e.preventDefault();
		});
		
		
		$("#custom_name_save").click(function(e) {
			
			var custName = $("#custom_name_value");
			var custNameVal = custName.val();
			
			if(custNameVal == ''){
				$("#custom_name_value_error").html('Please Provide Report Name.');	
				custName.focus();	
				return false;	
			} else {
				$("#custom_name_value_error").html('');
				$(".custom_report_name").val(custNameVal);
				$("#final_report").submit();
			}
			
            
        });
		
		
		//$(".custom_name_close").click(function(e) {
		$(document).on("click",".custom_name_close",function() {	
            $(".custom_report_name").val('');
			$("#custom_name_value").val('');
			$('#customTitle').modal('hide');
        });
		
	
	
		function openReport(evt, reportName) {
		  var i, tabcontent, tablinks;
		  tabcontent = document.getElementsByClassName("tabcontent");
		  for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		  }
		  tablinks = document.getElementsByClassName("tablinks");
		  for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		  }
		  document.getElementById(reportName).style.display = "block";
		  evt.currentTarget.className += " active";
		}
	</script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
     <?php include(SYSBASE.ADMIN_FOLDER.'/modules/default/nav_script.php');?>    
    <script>
		$(document).ready(function() {
			$('#report_base').DataTable({
				"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false
				}],
				"searching": false
			});
			
			$('#fromdatepicker').datepicker({	//Report From Date	
					autoclose: true,		
					//startDate: date,		
					todayHighlight: true,
			}).on('changeDate', function (ev) {		
				$('#from_range').change(function () {		
					var date = $("#from_range").val();	
					$("#todatepicker").datepicker({//Report To Date		
						autoclose: true,		
						startDate: date,		
						todayHighlight: true,		
						//endDate: '+0d',		
					});		
				});		
			});
	
		} );
	</script>
</body>
</html>
<?php
//$_SESSION['redirect'] = false;
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
