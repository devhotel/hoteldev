
<?php
if(!isset($_SESSION['book']) || count($_SESSION['book']) == 0){
    header('Location: '.DOCBASE.$sys_pages['booking']['alias']);
    exit();
}else
    $_SESSION['book']['step'] = 'summary';

require(getFromTemplate('common/header.php', false)); ?>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700&display=swap" rel="stylesheet">

<style>
 .booking_list_new {width: 100%;padding: 50px 0;}
.booking_list_new h2 {color: #4a4a4a;font-weight: 400;font-size: 17px !important;margin: 0 0 20px;border-bottom: 1px solid #4a4a4a;}
.booking_box_top {width: 100%;border-bottom: 1px solid #ccc;padding: 0;margin: 0 0 20px;}
.booking_box_top .fa {color: #9b9b9b;font-size: 30px;}
.booking_box_top h3 {color: #4a4a4a;font-weight: 400;font-size: 15px;margin: 0 0 10px;}
.booking_box_top p {color: #9b9b9b;font-weight: 300;font-size: 13px;margin: 0;}
.booking_box_top h4 {color: #029d4b;font-size: 15px;margin: 0 0 10px;font-weight: 400;}
.booking_box_top a .fa {color:#085C9A;font-size: 13px;}
span.fontawsome {width: 100%;text-align: center;display: block;}
.booking_details_top {width: 100%;margin: 0 0 40px;}
.booking_details_top h2 {color: #2f9df2;font-weight: 600;font-size: 25px;margin: 0 0 9px;border:none;text-transform: uppercase;}
.booking_details_top h3 {color: #9b9b9b;font-weight: 400;font-size: 15px;margin: 0 0 3px;}
.toppart {width: 100%;margin: 0 0 40px;display: inline-block;}
span.tp1 {width: auto;float: left;}
span.tp2 {width: auto;float: right;}
span.tp2 a {display: inline-block;padding: 0 0 0 12px;font-weight: 400;color:#9b9b9b;font-size: 15px;text-decoration: none;}
span.tp2 a:hover {color: #000;}
span.tp2 a .fa {font-size: 15px;margin-right: 5px;color:#000;}
.bk_dtl_box .fa {font-size: 14px;}
.noborder {border:none;}
.newpage_paymnt {width: 100%;}
.newpage_paymnt p {color: #9b9b9b;font-weight: 300;font-size: 13px;margin: 0;display: inline-block;width: 100%;}
.newpage_paymnt p span {font-size: 16px !important;color: #000 !important;font-weight: 600 !important;width: auto;float: right;}
.service_area_summery p span {font-size: 13px!important;}
.service_area_summery p {line-height:20px !important;}
.service_area_summery {margin:0 0 20px;}
</style>
<section id="page">
    
    <?php include(getFromTemplate('common/page_header.php', false)); ?>

    <div id="content" class="pt30 pb20">
        <div class="container">
            
            <div class="row mb30" id="booking-breadcrumb">
                <div class="col-sm-2 col-sm-offset-<?php echo isset($_SESSION['book']['activities']) ? '1' : '2'; ?>">
                    <a href="<?php echo DOCBASE.$sys_pages['booking']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-check "></i>
                            <span><?php echo $sys_pages['booking']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <?php
                if(isset($_SESSION['book']['activities'])){ ?>
                    <div class="col-sm-2">
                        <a href="<?php echo DOCBASE.$sys_pages['booking-activities']['alias']; ?>">
                            <div class="breadcrumb-item done">
                                <i class="fas fa-fw fa-check "></i>
                                <span><?php echo $sys_pages['booking-activities']['name']; ?></span>
                            </div>
                        </a>
                    </div>
                    <?php
                } ?>
                <div class="col-sm-2">
                    <a href="<?php echo DOCBASE.$sys_pages['details']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-check"></i>
                            <span><?php echo $sys_pages['details']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <div class="breadcrumb-item active">
                        <i class="fas fa-fw fa-check"></i>
                        <span><?php echo $sys_pages['summary']['name']; ?></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="breadcrumb-item">
                        <i class="fas fa-fw fa-check"></i>
                        <span><?php echo $sys_pages['payment']['name']; ?></span>
                    </div>
                </div>
            </div>
            
            <?php
            if($page['text'] != ""){ ?>
                <div class="clearfix mb20"><?php echo $page['text']; ?></div>
                <?php
            } ?>
            <form method="post" action="<?php echo DOCBASE.$sys_pages['payment']['alias']; ?>">
                <div class="leftbox"> 
                    
                    <?php
                        // echo '<pre>';
                        // print_r($_SESSION);
                        // echo '</pre>';
                    ?>
                    <div class="booking_list_new">
                        <h2>Booking Details</h2>
                            <div class="booking_box_top bk_dtl_box noborder">
                                <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h3><?= $_SESSION['book']['hotel'];?></h3>
                                    <p><?= db_getFieldValue($db, 'pm_hotel', 'address', $_SESSION['book']['hotel_id'], $lang = 0);?></p>
                                    <p style="text-transform: capitalize;">
                                    <?php
                                     if(isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0){
                                          foreach($_SESSION['book']['rooms'] as $id_room => $rooms){
                                            foreach($rooms as $index => $room){
                                                 echo $room['title'].' - '.($room['adults']+$room['children']).' '.getAltText($texts['PERSON'], $texts['PERSONS'], ($room['adults']+$room['children'])).': ';
                                            if($room['adults'] > 0) echo $room['adults'].' '.getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']);
                                            if($room['children'] > 0) echo ' , ';
                                            if($room['children'] > 0) echo $room['children'].' '.getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']);
                                        
                                    ?>
                                    <br>
                                    <?php 
                                        }
                                      }
                                    } ?>
                                    </p>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="text-align: right;">
                                    <p><i class="fa fa-long-arrow-right" aria-hidden="true"></i> Check In</p>
                                    <h3><?php echo date("D, d-M-Y", $_SESSION['book']['from_date']);?></h3>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="text-align: right;">
                                    <p><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Check Out</p>
                                    <h3>
                                    <?php
                                    if(!empty($_SESSION['book']['offer_id']))
                                        {                                               
                                          $NewDate=Date('D, d-M-Y', strtotime("+".$_SESSION['book']['nights']." days"));
                                          echo $NewDate;                                                 
                                        }
                                        else
                                        {
                                            echo date("D, d-M-Y",$_SESSION['book']['to_date']);
                                        }
                                     ?>                                         
                                     </h3>
                                </div>
                            </div>
                            </div>
                            <h2>Billing Address</h2>
                            <div class="booking_box_top noborder">
                                <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h3><?php echo $_SESSION['book']['firstname'].' '.$_SESSION['book']['lastname']; ?>
                                        <span><?php if($_SESSION['book']['company'] != '') echo $texts['COMPANY'].' : '.$_SESSION['book']['company'];?></span>
                                    </h3>
                                    <p><?php  echo nl2br($_SESSION['book']['address']);?>, <?= $_SESSION['book']['postcode'];?> <?= $_SESSION['book']['city'];?></p>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: right;">
                                    <p>Mobile : <?= $_SESSION['book']['mobile'];?></p>
                                    <p>E-mail : <?= $_SESSION['book']['email'];?> </p>
                                </div>
                            </div>
                            </div>
                            
                                <?php
                                 $service_amount = 0;
                                    for($i=1; $i<=count($_SESSION['book']['extra_services']); $i++){
                                        @$service_amount += $_SESSION['book']['extra_services'][$i]['amount'];
                                    }
                                    if(isset($_SESSION['book']['extra_services']) && count($_SESSION['book']['extra_services']) > 0 && $service_amount > 0){
                                        echo '<h2>Services</h2>
                                            <div class="service_area_summery">
                                        ';
                                            foreach($_SESSION['book']['extra_services'] as $id_service => $service){
                                                if($service['amount'] > 0){
                                                echo
                                                '<p>
                                                    <span>'.$service['title'].'</span>
                                                    
                                                    <span style="text-align:right;float: right;width:auto;">'.formatPrice($service['amount']*CURRENCY_RATE).'</span>
                                                </p>';
                                                }
                                            }
                                            echo '
                                        </div>';
                                    }
                                ?>
                          
                            
                            <h2>Payment Information</h2>
                            <div class="booking_box_top noborder">
                                <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="newpage_paymnt">
                                        <!--<p>Total : <span>Rs: 6398.00</span></p> $_SESSION['book']['roomtotal']-->
                                        <?php if((isset($_SESSION['book']['discount_amount']) && $_SESSION['book']['discount_amount']>0 ) || (isset($_SESSION['book']['tax_rooms_amount']) && $_SESSION['book']['tax_rooms_amount']>0)){ ?>
                                            <?php if(isset($_SESSION['book']['roomtotal']) && $_SESSION['book']['roomtotal'] > 0){
                                                echo '
                                                <p>
                                                    '.$texts['TOTAL'].'
                                                    <span class="text-right"> '.formatPrice($_SESSION['book']['roomtotal']*CURRENCY_RATE).'</span>
                                                </p>';
                                             } ?>
                                        <?php } ?>

                                       <?php if(isset($_SESSION['book']['discount_amount']) && $_SESSION['book']['discount_amount'] > 0){
                                                echo '
                                                <p>
                                                    '.$texts['DISCOUNT'].'
                                                    <span class="text-right">- '.formatPrice($_SESSION['book']['discount_amount']*CURRENCY_RATE).'</span>
                                                </p>';
                                        } ?>
                                        
                                        <?php
                                        
                                             // Start slab booking tax calculation 
                               if(isset($_SESSION['book']['stax_id'])){
                                     $tax_id = $_SESSION['book']['stax_id'];
                                     $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$_SESSION['book']['bookamount'].' AND end >= '.$_SESSION['book']['bookamount'].' LIMIT 1');
                                    if($result_tax_slab !== false && $db->last_row_count() > 0){
                                        $row = $result_tax_slab->fetch();
                                        //$_SESSION['book']['taxes'] = $row['value'];
                                        $tax_amount = $_SESSION['book']['tax_rooms_amount'];
                                        echo '<p>'.db_getFieldValue($db, 'pm_tax', 'name', $tax_id, $lang = 0).' ('.$row['value'].'%) - <span>'.formatPrice($tax_amount*CURRENCY_RATE).'</span>
                                                    </p>';
                                         }else{
                                        $tax_id = 0;
                                        $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
                                        $result_tax->bindParam(':tax_id', $tax_id);
                                        foreach($_SESSION['book']['taxes'] as $tax_id => $taxes){
                                            $tax_amount = 0;
                                            foreach($taxes as $amount) $tax_amount += $amount;
                                            if($tax_amount > 0){
                                                if($result_tax->execute() !== false && $db->last_row_count() > 0){
                                                    $tax = $result_tax->fetch();
                                                    if(formatPrice($tax_amount*CURRENCY_RATE) > 0)
                                                    {
                                                    echo '<p>'.$tax['name'].' ('.$tax['value'].'%) - <span>'.formatPrice($tax_amount*CURRENCY_RATE).'</span>
                                                    </p>';
                                                    }
                                                }
                                            }
                                        }
                                     }
                                   }
                                        
                                 ?>
 
                                      <p><?= $texts['TOTAL'].' ('.$texts['INCL_TAX'].')';?> - <span><?= formatPrice($_SESSION['book']['total']*CURRENCY_RATE);?></span></p>
                                    </div>
                                </div>
                            </div>
                            </div>  
                    </div>
                </div>
                <a class="btn btn-default btn-lg pull-left" href="<?php echo DOCBASE.$sys_pages['details']['alias']; ?>"><i class="fas fa-fw fa-angle-left"></i> <?php echo $texts['PREVIOUS_STEP']; ?></a>
                <button type="submit" name="confirm_booking" class="btn btn-primary btn-lg pull-right"><?php echo $texts['NEXT_STEP']; ?> <i class="fas fa-fw fa-angle-right"></i></button>
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
      // Notice how this gets configured before we load Font Awesome
      window.FontAwesomeConfig = { autoReplaceSvg: false }
</script>