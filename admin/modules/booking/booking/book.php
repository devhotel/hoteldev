<?php
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
require_once('functions.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '', 'extraHtml' => '');
if(isset($_POST['book']) || (ENABLE_BOOKING_REQUESTS == 1 && isset($_POST['request']))){
    if(isset($_SESSION['bt'])) unset($_SESSION['bt']);
    $num_nights = $_POST['nights'];
    
    $_SESSION['bt']['hotel'] = db_getFieldValue($db, 'pm_hotel', 'title', $_POST['id_hotel'], $lang = 0);
    $_SESSION['bt']['hotel_id'] = $_POST['id_hotel'];
    $_SESSION['bt']['from_date'] = $_POST['from_time'];
    $_SESSION['bt']['to_date'] = $_POST['to_time'];
    $_SESSION['bt']['nights'] = $num_nights;
    $_SESSION['bt']['adults'] = $_POST['num_adults'];
    $_SESSION['bt']['children'] = $_POST['num_children'];
    $_SESSION['bt']['extra_services'] = array();
    $_SESSION['bt']['activities'] = array();
    $_SESSION['bt']['rooms'] = array();
    $_SESSION['bt']['total'] = 0;
    $_SESSION['book']['taxableamount']=0;
    $taxn_id=0;
    if(isset($_POST['book'])){
        
        $_SESSION['bt']['amount_rooms'] = 0;
        $_SESSION['bt']['amount_activities'] = 0;
        $_SESSION['bt']['amount_services'] = 0;
        $_SESSION['bt']['duty_free_rooms'] = 0;
        $_SESSION['bt']['duty_free_activities'] = 0;
        $_SESSION['bt']['duty_free_services'] = 0;
        $_SESSION['bt']['tax_rooms_amount'] = 0;
        $_SESSION['btbt']['tax_activities_amount'] = 0;
        $_SESSION['btbtbt']['tax_services_amount'] = 0;
        $_SESSION['bt']['discount'] = 0;
        $_SESSION['bt']['discount_type'] = '';
        $_SESSION['bt']['discount_amount'] = 0;
        $_SESSION['bt']['taxes'] = array();
        $_SESSION['bt']['bookamount'] = 0;
       
        
        $_SESSION['bt']['sessid'] = uniqid();
        
        $num_rooms = 0;
        $num_adults = 0;
        $num_children = 0;
        if(isset($_POST['amount']) && is_array($_POST['amount'])){
            foreach($_POST['amount'] as $id_room => $values){
                foreach($values as $i => $value){
                    
                    if(isset($_POST['num_adults'][$id_room][$i]) && isset($_POST['num_children'][$id_room][$i]) && isset($_POST['room_'.$id_room])){
                            
                        $room_title = $_POST['room_'.$id_room];
                        $adults = $_POST['num_adults'][$id_room][$i];
                        $children = $_POST['num_children'][$id_room][$i];
                        $duty_free = $_POST['duty_free'][$id_room][$i];
                        
                        if(is_numeric($adults) && is_numeric($children) && ($adults+$children) > 0 && $value > 0){
                            $num_adults += $adults;
                            $num_children += $children;
                            $num_rooms++;
                            /*
                            $data = array();
                            $data['id'] = null;
                            $data['id_room'] = $id_room;
                            $data['from_date'] = $_POST['from_time'];
                            $data['to_date'] = $_POST['to_time'];
                            $data['add_date'] = time();
                            $data['sessid'] = $_SESSION['bt']['sessid'];
                            
                            $result_room_lock = db_prepareInsert($db, 'pm_room_lock', $data);
                            $result_room_lock->execute();
                            */
                            $_SESSION['bt']['rooms'][$id_room][$i]['title'] = $room_title;
                            $_SESSION['bt']['rooms'][$id_room][$i]['adults'] = $adults;
                            $_SESSION['bt']['rooms'][$id_room][$i]['children'] = $children;
                            $_SESSION['bt']['rooms'][$id_room][$i]['amount'] = $value;
                            $_SESSION['bt']['rooms'][$id_room][$i]['duty_free'] = $duty_free;
                            
                            $_SESSION['bt']['taxes'] = array();
                            
                            if(isset($_POST['taxes'][$id_room][$i])){
                                $taxes = $_POST['taxes'][$id_room][$i];
                                if(is_array($taxes)){
                                    //var_dump($taxes);
                                    $taxamt=0;
                                      foreach($taxes as $tax_id => $tax_amount){
                                        if($tax_id!='tax_id'){
                                          $taxamt = $taxes[$tax_id]; 
                                          $_SESSION['bt']['tax_rooms_amount'] += $taxamt;
                                          $_SESSION['bt']['taxes'][$tax_id]['rooms'] = $_SESSION['bt']['tax_rooms_amount'];
                                        }
                                        if(!isset($_SESSION['bt']['stax_id'])){
                                               $_SESSION['bt']['stax_id']=round($taxes['tax_id']);
                                          }
                                      }
                                     $_SESSION['bt']['tax_rooms_amount'];
                                }
                            }
                            $_SESSION['bt']['amount_rooms'] += $value;
                            $_SESSION['bt']['duty_free_rooms'] += $duty_free;
                        }
                    }
                }
            }
            $_SESSION['bt']['num_rooms'] = $num_rooms;
        }
        
        $tourist_tax = (TOURIST_TAX_TYPE == 'fixed') ? floatval($_SESSION['bt']['adults'])*intval($num_nights)*intval(TOURIST_TAX) : floatval($_SESSION['bt']['amount_rooms'])*intval(TOURIST_TAX)/100;
        
        $_SESSION['bt']['tourist_tax'] = (ENABLE_TOURIST_TAX == 1) ? $tourist_tax : 0;
        
        $_SESSION['bt']['total'] = $_SESSION['bt']['duty_free_rooms']+$_SESSION['bt']['tax_rooms_amount']+$_SESSION['bt']['tourist_tax'];
        
        $_SESSION['bt']['down_payment'] = (ENABLE_DOWN_PAYMENT == 1 && DOWN_PAYMENT_RATE > 0 && $_SESSION['bt']['total'] >= DOWN_PAYMENT_AMOUNT) ? $_SESSION['bt']['total']*DOWN_PAYMENT_RATE/100 : 0;
    }
    
    if(isset($_SESSION['bt']['id'])) unset($_SESSION['bt']['id']);

    $result_activity = $db->query('SELECT * FROM pm_activity WHERE hotels REGEXP \'(^|,)'.$_SESSION['bt']['hotel_id'].'(,|$)\' AND checked = 1 AND lang = '.LANG_ID);
    if(isset($_SESSION['bt']['activities'])) unset($_SESSION['bt']['activities']);
   
}
