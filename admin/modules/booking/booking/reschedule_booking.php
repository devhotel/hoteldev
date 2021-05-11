<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

if(isset($_POST['book'])){
    $msg_error = '';
    $msg_success = '';
    $field_notice = array();

    $users = '';
    $result_owner = $db->query('SELECT users FROM pm_hotel WHERE id = '.$_SESSION['bt']['hotel_id']);
    if($result_owner !== false && $db->last_row_count() > 0){
    	$row = $result_owner->fetch();
    	$users = $row['users'];
    }
  $hotel_owners = array();
  $result_owner = $db->query('SELECT * FROM pm_user WHERE id IN ('.$users.')');
  if($result_owner !== false && $db->last_row_count() > 0)
	$hotel_owners = $result_owner->fetchAll();
	
      $total = $_SESSION['bt']['total'];
      $payment_type = $_POST['payment_type'];
      $adults=0;
      foreach($_SESSION['bt']['adults'] as $key=>$adlt){
          foreach($adlt as $adl){
             $adults +=$adl;
          }
      }
       $children=0;
      foreach($_SESSION['bt']['children'] as $key=>$child){
          foreach($child as $chd){
             $children +=$chd;
          }
      }
     
    if(!isset($_SESSION['bt']['id']) || is_null($_SESSION['bt']['id'])){
        $hotel_re = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND id='.$_SESSION['bt']['hotel_id'].' AND lang=2'); 
        $hotel = $hotel_re->fetch(PDO::FETCH_ASSOC);
        $c_user = $db->query('SELECT * FROM pm_user WHERE checked = 1 AND id='.$_POST["id_user"]); 
        $row = $c_user->fetch(PDO::FETCH_ASSOC);
        //var_dump($_SESSION['bt']['rooms']);
        //exit;
       
        $data = array();
        $data['id'] = null;
        $data['id_user'] = $_SESSION['bt']['id_user']=$_POST['id_user'];
        $data['firstname'] = $_SESSION['bt']['firstname']=$row['firstname'];
        $data['lastname'] = $_SESSION['bt']['lastname']=$row['lastname'];
        $data['email'] = $_SESSION['bt']['email']=$row['email'];
        $data['company'] = $_SESSION['bt']['company']=$row['company'];
        $data['address'] = $_SESSION['bt']['address']=$row['address'];
        $data['postcode'] = $_SESSION['bt']['postcode']=$row['postcode'];
        $data['city'] = $_SESSION['bt']['city']=$row['city'];
        $data['phone'] = $_SESSION['bt']['phone']=$row['phone'];
        $data['mobile'] = $_SESSION['bt']['mobile']=$row['mobile'];
        $data['country'] = $_SESSION['bt']['country']=$row['country'];
        $data['id_hotel'] = $_SESSION['bt']['hotel_id'];
        $data['from_date'] = $_SESSION['bt']['from_date'];
        $data['to_date'] = $_SESSION['bt']['to_date'];
        $data['nights'] = $_SESSION['bt']['nights'];
        $data['adults'] = $adults;
        $data['children'] = $children;
        $data['amount'] = number_format($_SESSION['bt']['total'], 2, '.', '');
        $data['total'] = number_format($total, 2, '.', '');
        if($payment_type != 'arrival') $data['down_payment'] = number_format($_SESSION['bt']['down_payment'], 2, '.', '');
        $data['add_date'] = time();
        $data['edit_date'] = null;
        $data['status'] = 4;
        $data['discount'] = number_format($_SESSION['bt']['discount_amount'], 2, ".", "");
		$data['payment_option'] = $payment_type;
        $data['users'] = $users;
	//	$tax_amount = $_SESSION['bt']['tax_rooms_amount']+$_SESSION['bt']['tax_activities_amount']+$_SESSION['bt']['tax_services_amount'];
	    $tax_amount= $_SESSION['bt']['tax_rooms_amount'];
        $data['tax_amount'] = number_format($tax_amount, 2, '.', '');
        $data['ex_tax'] = number_format($total-$tax_amount, 2, '.', '');
        
        $result_booking = db_prepareInsert($db, 'pm_booking', $data);
        if($result_booking->execute() !== false){
             $_SESSION['bt']['id'] = $db->lastInsertId();
             $rm=$_POST['rooms'][0];
            if(isset($_SESSION['bt']['rooms']) && count($_SESSION['bt']['rooms']) > 0){
                 /*for($i=0; $i<$_POST['num_rooms'][$rm]; $i++){
                   $pbr['id_booking'] = $_SESSION['bt']['id'];
                   $pbr['id_room'] =$_POST['rooms'][0];
                   $pbr['id_hotel'] =$_SESSION['bt']['hotel_id'].'-'.$_POST['room_'.$rm];
                   $pbr['title'] = $hotel['title']; 
                   //$rm=$_POST['rooms'][0];
                   $pbr['children'] = $_POST['num_children'][$rm][0]; 
                   $pbr['adults'] = $_POST['num_adults'][$rm][0]; 
                   $pbr['amount'] = $_POST['amount_rooms']; 
                   $pbr['ex_tax'] = $_POST['amount_rooms'] -$_POST['taxes'] ;
                   $result = db_prepareInsert($db, 'pm_booking_room', $pbr);
                   $result->execute();
                } */
                foreach($_SESSION['bt']['rooms'] as $id_room => $rooms){
                    foreach($rooms as $index => $room){
                        $data = array();
                        $data['id'] = null;
                        $data['id_booking'] = $_SESSION['bt']['id'];
                        $data['id_room'] = $id_room;
                        $data['id_hotel'] = $_SESSION['bt']['hotel_id'];
                        $data['title'] = $_SESSION['bt']['hotel'].' - '.$room['title'];
                        $data['adults'] = $room['adults'];
                        $data['children'] = $room['children'];
                        $data['amount'] = number_format($room['amount'], 2, '.', '');
                        if(isset($room['duty_free'])) $data['ex_tax'] = number_format($room['duty_free'], 2, '.', '');
                        if(isset($room['tax_rate'])) $data['tax_rate'] = $room['tax_rate'];
                        $result = db_prepareInsert($db, 'pm_booking_room', $data);
                        $result->execute();
                    }
                }
            }
            if(isset($_SESSION['bt']['activities']) && count($_SESSION['bt']['activities']) > 0){
                foreach($_SESSION['bt']['activities'] as $id_activity => $activity){
                    $data = array();
                    $data['id'] = null;
                    $data['id_booking'] = $_SESSION['bt']['id'];
                    $data['id_activity'] = $id_activity;
                    $data['title'] = $activity['title'];
                    $data['adults'] = $activity['adults'];
                    $data['children'] = $activity['children'];
                    $data['duration'] = $activity['duration'];
                    $data['amount'] = number_format($activity['amount'], 2, '.', '');
                    $data['date'] = $activity['session_date'];
					if(isset($activity['duty_free'])) $data['ex_tax'] = number_format($activity['duty_free'], 2, '.', '');
					if(isset($activity['tax_rate'])) $data['tax_rate'] = $activity['tax_rate'];
                    
                    $result = db_prepareInsert($db, 'pm_booking_activity', $data);
                    $result->execute();
                }
            }
            if(isset($_SESSION['bt']['extra_services']) && count($_SESSION['bt']['extra_services']) > 0){
                foreach($_SESSION['bt']['extra_services'] as $id_service => $service){
                    $data = array();
                    $data['id'] = null;
                    $data['id_booking'] = $_SESSION['bt']['id'];
                    $data['id_service'] = $id_service;
                    $data['title'] = $service['title'];
                    $data['qty'] = $service['qty'];
                    $data['amount'] = number_format($service['amount'], 2, '.', '');
					if(isset($service['duty_free'])) $data['ex_tax'] = number_format($service['duty_free'], 2, '.', '');
					if(isset($service['tax_rate'])) $data['tax_rate'] = $service['tax_rate'];
                    
                    $result = db_prepareInsert($db, 'pm_booking_service', $data);
                    $result->execute();
                }
            }
           if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
            if(isset($_SESSION['bt']['taxes']) && count($_SESSION['bt']['taxes']) > 0){
                $tax_id = 0;
                $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
                $result_tax->bindParam(':tax_id', $tax_id);
                foreach($_SESSION['bt']['taxes'] as $tax_id => $taxes){
                    $tax_amount = 0;
                    foreach($taxes as $amount) $tax_amount += $amount;
                    if($tax_amount > 0){
                        if($result_tax->execute() !== false && $db->last_row_count() > 0){
                            $row = $result_tax->fetch();
                            $data = array();
                            $data['id'] = null;
                            $data['id_booking'] = $_SESSION['bt']['id'];
                            $data['id_tax'] = $tax_id;
                            $data['name'] = $row['name'];
                            $data['amount'] = number_format($tax_amount, 2, '.', '');
                            $result = db_prepareInsert($db, 'pm_booking_tax', $data);
                            $result->execute();
                        }
                    }
                }
            }
           }
            $_SESSION['tmp_book'] = $_SESSION['bt'];
        }
    }
        
    if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
        $data = array();
        $data['id'] = $_SESSION['bt']['id'];
		$data['payment_option'] = $payment_type;
        $result_booking = db_prepareUpdate($db, 'pm_booking', $data);
        $result_booking->execute();
    }
       
    if($payment_type == 'check' || $payment_type == 'arrival'){
        $room_content = '';

        $service_content = '';
        if(isset($_SESSION['bt']['extra_services']) && count($_SESSION['bt']['extra_services']) > 0){
            foreach($_SESSION['bt']['extra_services'] as $id_service => $service)
                $service_content .= $service['title'].' x '.$service['qty'].' : '.formatPrice($service['amount']*CURRENCY_RATE).' '.$texts['INCL_VAT'].'<br>';
        }
        
        $activity_content = '';
        if(isset($_SESSION['bt']['activities']) && count($_SESSION['bt']['activities']) > 0){
            foreach($_SESSION['bt']['activities'] as $id_activity => $activity){
                $activity_content .= '<p><b>'.$activity['title'].'</b> - '.$activity['duration'].' - '.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $activity['session_date']).'<br>
                '.($activity['adults']+$activity['children']).' '.$texts['PERSONS'].' - 
                '.$texts['ADULTS'].': '.$activity['adults'].' / 
                '.$texts['CHILDREN'].': '.$activity['children'].'<br>
                '.$texts['PRICE'].' : '.formatPrice($activity['amount']*CURRENCY_RATE).'</p>';
            }
        }
        
        $tax_id = 0;
        $tax_content = '';
        $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
        $result_tax->bindParam(':tax_id', $tax_id);
        
        foreach($_SESSION['bt']['taxes'] as $tax_id => $taxes){
            $tax_amount = 0;
            foreach($taxes as $amount) $tax_amount += $amount;
            if($tax_amount > 0){
                if($result_tax->execute() !== false && $db->last_row_count() > 0){
                    $row = $result_tax->fetch();
                    $tax_content .= $row['name'].': '.formatPrice($tax_amount*CURRENCY_RATE).'<br>';
                }
            }
        }
        
        $payment_notice = '';
        if($payment_type == 'check') $payment_notice .= str_replace('{amount}', '<b>'.formatPrice($payed_amount*CURRENCY_RATE).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_CHECK_NOTICE']);
        if($payment_type == 'arrival') $payment_notice .= str_replace('{amount}', '<b>'.formatPrice($total).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_ARRIVAL_NOTICE']);
        
        $mail = @getMail($db, 'BOOKING_CONFIRMATION', array(
            '{firstname}' => $_SESSION['bt']['firstname'],
            '{lastname}' => $_SESSION['bt']['lastname'],
            '{company}' => $_SESSION['bt']['company'],
            '{address}' => $_SESSION['bt']['address'],
            '{postcode}' => $_SESSION['bt']['postcode'],
            '{city}' => $_SESSION['bt']['city'],
            '{country}' => $_SESSION['bt']['country'],
            '{phone}' => $_SESSION['bt']['phone'],
            '{mobile}' => $_SESSION['bt']['mobile'],
            '{email}' => $_SESSION['bt']['email'],
            '{Check_in}' => gmstrftime(DATE_FORMAT, $_SESSION['bt']['from_date']),
            '{Check_out}' => gmstrftime(DATE_FORMAT, $_SESSION['bt']['to_date']),
            '{num_nights}' => $_SESSION['bt']['nights'],
            '{num_guests}' => ($_SESSION['bt']['adults']+$_SESSION['bt']['children']),
            '{num_adults}' => $_SESSION['bt']['adults'],
            '{num_children}' => $_SESSION['bt']['children'],
            '{rooms}' => $room_content,
            '{extra_services}' => $service_content,
            '{activities}' => $activity_content,
            //'{tourist_tax}' => formatPrice($_SESSION['bt']['tourist_tax']*CURRENCY_RATE),
            '{discount}' => '- '.formatPrice($_SESSION['bt']['discount_amount']*CURRENCY_RATE),
            '{taxes}' => $tax_content,
            '{down_payment}' => formatPrice($_SESSION['bt']['down_payment']*CURRENCY_RATE),
            '{total}' => formatPrice($total*CURRENCY_RATE),
            '{payment_notice}' => $payment_notice
        ));
        
        if($mail !== false){
            foreach($hotel_owners as $owner){
                if($owner['email'] != EMAIL)
                    @sendMail($owner['email'], $owner['firstname'], $mail['subject'], $mail['content'], $_SESSION['bt']['email'], $_SESSION['bt']['firstname'].' '.$_SESSION['bt']['lastname']);
            }
            @sendMail(EMAIL, OWNER, $mail['subject'], $mail['content'], $_SESSION['bt']['email'], $_SESSION['book']['firstname'].' '.$_SESSION['bt']['lastname']);
            @sendMail($_SESSION['bt']['email'], $_SESSION['bt']['firstname'].' '.$_SESSION['bt']['lastname'], $mail['subject'], $mail['content']);
        }
        unset($_SESSION['bt']);
        echo 'Your booking is successfully completed!';
       
    }

 }
 exit();
 ?>
