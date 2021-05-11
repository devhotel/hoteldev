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
      $gtotal=0;
      $payment_option = $_POST['payment_option'];
      $payment_mode = $_POST['payment_mode'];
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
        $_SESSION['bt']['added_date'] = time();
        $_SESSION['bt']['room_type']="";
        $hotel_re = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND id='.$_SESSION['bt']['hotel_id'].' AND lang=2'); 
        $hotel = $hotel_re->fetch(PDO::FETCH_ASSOC);
        $c_user = $db->query('SELECT * FROM pm_user WHERE checked = 1 AND id='.$_POST["id_user"]); 
        $row = $c_user->fetch(PDO::FETCH_ASSOC);
        //var_dump($_SESSION['bt']['rooms']);
        //exit;
        $data = array();
        $data['id'] = null;
        $data['id_user'] = $_SESSION['bt']['id_user']=$_POST['id_user'];
        $data['id_hotel'] = $_SESSION['bt']['hotel_id'];
        $data['id_destination'] =db_getFieldValue($db, 'pm_hotel', 'id_destination', $_SESSION['bt']['hotel_id'], $lang = 0);
        $data['firstname'] = $_SESSION['bt']['firstname']=$row['firstname'];
        $data['lastname'] = $_SESSION['bt']['lastname']=$row['lastname'];
        $data['email'] = $_SESSION['bt']['email']=$row['email'];
        $data['company'] = $_SESSION['bt']['company']=$row['company'];
        $data['govid_type'] = $_SESSION['bt']['govid_type']=$row['govid_type'];
        $data['govid'] = $_SESSION['bt']['govid']=$row['govid'];
        $data['address'] = $_SESSION['bt']['address']=$row['address'];
        $data['postcode'] = $_SESSION['bt']['postcode']=$row['postcode'];
        $data['city'] = $_SESSION['bt']['city']=$row['city'];
        $data['phone'] = $_SESSION['bt']['phone']=$row['phone'];
        $data['mobile'] = $_SESSION['bt']['mobile']=$row['mobile'];
        $data['country'] = $_SESSION['bt']['country']=$row['country'];
        $data['from_date'] = $_SESSION['bt']['from_date'];
        $data['to_date'] = $_SESSION['bt']['to_date'];
        $data['nights'] = $_SESSION['bt']['nights'];
        $data['adults'] = $adults;
        $data['children'] = $children;
        $data['amount'] = number_format($_SESSION['bt']['total'], 2, '.', '');
        $data['total'] = number_format($total, 2, '.', '');
        if($payment_option != 'arrival') $data['down_payment'] = number_format($_SESSION['bt']['down_payment'], 2, '.', '');
        $data['add_date'] = time();
        $data['edit_date'] = null;
        $data['status'] = 1;
        $data['discount'] = number_format($_SESSION['bt']['discount_amount'], 2, ".", "");
        $data['source'] = 'admin';
        $data['users'] = $users;
	//	$tax_amount = $_SESSION['bt']['tax_rooms_amount']+$_SESSION['bt']['tax_activities_amount']+$_SESSION['bt']['tax_services_amount'];
	     if(isset($_SESSION['bt']['taxslab'])){
            $tax_amount = $_SESSION['bt']['tax_rooms_amount'];
        }else{
            $tax_amount = $_SESSION['bt']['tax_rooms_amount']+$_SESSION['bt']['tax_services_amount'];  
        }
	    
        $data['tax_amount'] = number_format($tax_amount, 2, '.', '');
        $data['ex_tax'] = number_format($total-$tax_amount, 2, '.', '');
        $gtotal =(($_SESSION['bt']['total']-$tax_amount)+$_SESSION['bt']['discount_amount']);
        $result_booking = db_prepareInsert($db, 'pm_booking', $data);
        if($result_booking->execute() !== false){
             $_SESSION['bt']['id'] = $db->lastInsertId();
             $rm=$_POST['rooms'][0];
            if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){     
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
                            $_SESSION['bt']['room_type'] .= '<p>'.$_SESSION['bt']['hotel'].' - '.$room['title'].'</p>';
                            $data = array();
                            $data['id'] = null;
                            $data['id_booking'] = $_SESSION['bt']['id'];
                            $data['id_room'] = $id_room;
                            $data['id_hotel'] = $_SESSION['bt']['hotel_id'];
                            $data['title'] = $_SESSION['bt']['hotel'].' - '.$room['title'];
                            $data['adults'] = $room['adults'];
                            $data['children'] = $room['children'];
                            $data['amount'] = number_format($room['amount'], 2, '.', '');
                            $data['chk'] = 1;
                            if(isset($room['duty_free'])) $data['ex_tax'] = number_format($room['duty_free'], 2, '.', '');
                            if(isset($room['tax_rate'])) $data['tax_rate'] = $room['tax_rate'];
                            $result = db_prepareInsert($db, 'pm_booking_room', $data);
                            $result->execute();
                        }
                    }
                }
            }
            
            if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
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
            }
            
           if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
               
             if(isset($_SESSION['bt']['taxslab'])){
                   $data = array();
                                $data['id'] = null;
                                $data['id_booking'] = $_SESSION['bt']['id'];
                                $data['id_tax'] = $_SESSION['bt']['taxslab'];
                                $data['name'] = db_getFieldValue($db, 'pm_tax', 'name', $_SESSION['bt']['stax_id'], $lang = 0);
                                $data['amount'] = number_format($_SESSION['bt']['slab_tax_amount'], 2, '.', '');
                                $result = db_prepareInsert($db, 'pm_booking_tax', $data);
                                $result->execute();
               }else{
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
           }
           // $_SESSION['tmp_book'] = $_SESSION['bt'];
        }
    }
        
    if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
        if($payment_option == 'wallet' || $payment_option == 'arrival'){
            if(isset($_POST['wallet']) && $payment_option == 'arrival'){
                $payable=$_SESSION['bt']['payable_amount'];
                $walletvalue=$_SESSION['bt']['wallet'];
                $data = array();
                $data['id'] = $_SESSION['bt']['id'];
        		$data['payment_option'] = $payment_option;
        		$data['payment_mode'] = $payment_mode;
                if(isset($_POST['transactionid']) && $_POST['transactionid'] !=''){
                                $data['status'] = 4;
                                $data['trans'] = $_POST['transactionid'];
                                $data['payment_date'] = time();
                                $data_a = array();
                                $data_a['id'] = null;
                                $data_a['id_booking'] = $_SESSION['bt']['id'];
                                $data_a['date'] = time();
                                $data_a['descr'] = 'Payment has been success by '.$payment_mode;;
                                $data_a['trans'] = $_POST['transactionid'];
                                $data_a['method'] = $payment_mode;
                                $data_a['amount'] = number_format($payable, 2, '.', '');
                                $result_payment = db_prepareInsert($db, 'pm_booking_payment', $data_a);
                                $result_payment->execute();
                }
                
                $result_booking = db_prepareUpdate($db, 'pm_booking', $data);
                $result_booking->execute(); 
                if(isset($_POST['wallet']) && $_POST['wallet']=='wallet'){
                    $amount=$_SESSION['bt']['wallet'];
                           $data['status'] = 4;
                           $data['trans'] = $_POST['transactionid'];
                           $data['payment_date'] = time();
                           $data_a = array();
                           $data_a['id'] = null;
                           $data_a['id_booking'] = $_SESSION['bt']['id'];
                           $data_a['date'] = time();
                           $data_a['descr'] = 'Payment has been success by '.$payment_mode;;
                           $data_a['trans'] = randTransaction();
                           $data_a['method'] = 'wallet';
                           $data_a['amount'] = number_format($amount, 2, '.', '');
                           $result_payment = db_prepareInsert($db, 'pm_booking_payment', $data_a);
                           $result_payment->execute();
                    
                    $id_booking=$_SESSION['bt']['id'];
                    $id_user= $_SESSION['bt']['id_user'];
                    $type='debit';
                    $purpose ='booking';
                    walletUpdate($id_booking, $id_user, $amount, $type, $purpose);
                }
            }else{
                $payable=$_SESSION['bt']['payable_amount'];
                $data = array();
                $data['id'] = $_SESSION['bt']['id'];
        		$data['payment_option'] = $payment_option;
        		$data['payment_mode'] = $payment_mode;
                if(isset($_POST['transactionid']) && $_POST['transactionid'] !=''){
                                $data['status'] = 4;
                                $data['trans'] = $_POST['transactionid'];
                                $data['payment_date'] = time();
                                $data_a = array();
                                $data_a['id'] = null;
                                $data_a['id_booking'] = $_SESSION['bt']['id'];
                                $data_a['date'] = time();
                                $data_a['descr'] = 'Payment has been success by '.$payment_mode;;
                                $data_a['trans'] = $_POST['transactionid'];
                                $data_a['method'] = $payment_mode;
                                $data_a['amount'] = number_format($total, 2, '.', '');
                                $result_payment = db_prepareInsert($db, 'pm_booking_payment', $data_a);
                                $result_payment->execute();
                }
                $result_booking = db_prepareUpdate($db, 'pm_booking', $data);
                $result_booking->execute(); 
                
                if(isset($_POST['wallet']) && $_POST['wallet']=='wallet'){
                    $id_booking=$_SESSION['bt']['id'];
                    $id_user= $_SESSION['bt']['id_user'];
                    $amount=$_SESSION['bt']['wallet'];
                    $type='debit';
                    $purpose ='booking';
                    walletUpdate($id_booking, $id_user, $amount, $type, $purpose);
                }
             }
          }
      
      }
    
    if(isset($_SESSION['bt']['id']) && $_SESSION['bt']['id'] > 0){
        if($payment_option == 'wallet' || $payment_option == 'arrival'){
            
            
            $room_content = '';
            $room_content .= '<table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">';
    		$room_content .= '<tbody>';
    		
    			 $room_content .= '<tr>';
    				$room_content .= '<td style="padding: 20px;background: #fff;">';
    				   $room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Guests</h2>';
    				$room_content .= '</td>';
    				$room_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
    					$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Rooms</h2>';
    				$room_content .= '</td>';
    				$room_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
    					$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">Room Type</h2>';			
    				$room_content .= '</td>';
    			 $room_content .= '</tr>';
    			 
            if(isset($_SESSION['bt']['rooms']) && count($_SESSION['bt']['rooms']) > 0){
                foreach($_SESSION['bt']['rooms'] as $id_room => $rooms){
                     $room_content .= '<tr>';
                     $room_ids = array();
                     $adult=0;
                     $kid=0;
                     $key=0;
                      end($rooms);
                      $lastkey = key($rooms);
                      $row_content ='';
                     foreach($rooms as $index => $room){
                         
                           $_SESSION['bt']['room_type'] = '';
                           $adult = $adult+$room['adults'];
                           $kid = $kid+$room['children'];
    
            				     
            				  if($lastkey===$index){
            				         $room_content .= '<td style="padding: 20px;background: #fff;">';
            				         $room_content .= '<p style="font-size: 16px;color: #000;margin: 0;">'; 
            				          $room_content .=  $adult;
            				          $room_content .= ' Adult,';
            				    
            				           $room_content .= $kid;
            				           $room_content .= ' Kid';
            				          $room_content .= '</p>';
            				          $room_content .= '</td>';
                                 }
    
                              if (!in_array($id_room, $room_ids)){
                                        $room_ids[]=$id_room;
                                        
                        				$row_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';  
                        				   $row_content .= '<p style="font-size: 16px;color: #000;margin: 0;">'.count($rooms).'</p>';
                        				$row_content .= '</td>';
                        				$row_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
                        					$row_content .= '<p style="font-size: 16px;color: #000;margin: 0;">'.$_SESSION['bt']['hotel'].' - '.$room['title'].'</p>';
                        				$row_content .= '</td>';
                                 } 
                             if($lastkey===$index){
                                $room_content .=$row_content;
                             }
                               
                       /* $room_content .= '<p><b>'.$_SESSION['book']['hotel'].' - '.$room['title'].'</b><br>
                        '.($room['adults']+$room['children']).' '.$texts['PERSONS'].' - 
                        '.$texts['ADULTS'].': '.$room['adults'].' / 
                        '.$texts['CHILDREN'].': '.$room['children'].'<br>
                        '.$texts['PRICE'].' : '.formatPrice($room['amount']*CURRENCY_RATE).'</p>';
                        */
                      }
                    $room_content .= '</tr>'; 
                }
            }
            
            $room_content .= '</tbody>';
            $room_content .= '</table>';
    
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
            
            if(isset($_SESSION['bt']['taxslab'])){
                    $tax_content = '';
                    $tax_content .= db_getFieldValue($db, 'pm_tax', 'name', $_SESSION['bt']['stax_id'], $lang = 0).': '.formatPrice($_SESSION['bt']['slab_tax_amount']*CURRENCY_RATE).'<br>';
               }else{
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
               }
            
             $purpose = 'New Booking  #id ='.$_SESSION['bt']['id']. ' is Created' ;
                      // Add activity log
             add_activity_log($_SESSION['admin']['id'], $_SESSION['bt']['id'], 'booking' , 'add', $purpose);

             
        
            
            $payment_notice = '';
            if($payment_option == 'wallet') $payment_notice .= str_replace('{amount}', '<b>'.formatPrice($total*CURRENCY_RATE).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_CHECK_NOTICE']);
            if($payment_option == 'arrival') $payment_notice .= str_replace('{amount}', '<b>'.formatPrice($total).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_ARRIVAL_NOTICE']);
            //SMS Notification
              if( isset($_SESSION['bt']['mobile'])){
                 $mobile =  $_SESSION['bt']['mobile'];
                 $message = 'Hi '.$_SESSION['bt']['firstname'].' Your booking Confirmation  # '.$_SESSION['bt']['id'].' is successfully Confirmed';
                 httpPost($mobile,$message);  
               }
            $payment_notice='';
            $mail = @getMail($db, 'BOOKING_CONFIRMATION', array(
                '{booking_id}' => $_SESSION['bt']['id'],
                '{booking_date}' => gmstrftime(DATE_FORMAT, $_SESSION['bt']['added_date']),
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
                '{num_guests}' => ($adults+$children),
                '{num_adults}' => $adults,
                '{num_children}' => $children,
                '{rooms}' => $room_content,
                '{room_type}' => $_SESSION['bt']['room_type'],
                '{extra_services}' => $service_content,
                '{activities}' => $activity_content,
                '{comments}' => nl2br(''),
                '{total_cost}' => formatPrice($gtotal*CURRENCY_RATE),
                '{tourist_tax}' => formatPrice($_SESSION['book']['tourist_tax']*CURRENCY_RATE),
                '{discount}' => '- '.formatPrice($_SESSION['bt']['discount_amount']*CURRENCY_RATE),
                '{taxes}' => $tax_content,
                '{down_payment}' => formatPrice($_SESSION['bt']['down_payment']*CURRENCY_RATE),
                '{total}' => formatPrice($total*CURRENCY_RATE),
                '{payment_notice}' => $payment_notice
            ));
            //$from_email = 'bookings@guptahotels.com'; 
            $from_email = 'bookings@yopmail.com'; 
            $from_name = 'Gupta Hotels';
            $from_subject = 'New Booking Received';
            if($mail !== false){
                foreach($hotel_owners as $owner){
                    if($owner['email'] != EMAIL)
                        @sendMail($owner['email'], $owner['firstname'], $from_subject, $mail['content'], $_SESSION['bt']['email'], $_SESSION['bt']['firstname'].' '.$_SESSION['bt']['lastname'],$from_email,$from_name);
                }
                @sendMail(EMAIL, OWNER, $from_subject, $mail['content'], $_SESSION['bt']['email'], $_SESSION['book']['firstname'].' '.$_SESSION['bt']['lastname'],$from_email,$from_name);
                @sendMail($_SESSION['bt']['email'], $_SESSION['bt']['firstname'].' '.$_SESSION['bt']['lastname'], $mail['subject'], $mail['content'],'','',$from_email,$from_name);
            }
            unset($_SESSION['bt']);
            unset($_SESSION['rprice']);
            unset($_SESSION['rid']);
            echo 'Your booking is successfully completed!';
           
        }
    }
 }
 exit();
 ?>
