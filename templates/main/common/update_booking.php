<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '');

if(isset($db) && $db !== false){
        
    if(isset($_SESSION['book'])){
    
        if(isset($_SESSION['book']['amount_rooms'])){
            $_SESSION['book']['tax_services_amount'] = 0;
            $_SESSION['book']['amount_services'] = 0;
            $_SESSION['book']['duty_free_services'] = 0;
            
              // Start slab booking tax calculation   
              if(isset($_SESSION['book']['stax_id'])){
                     $tax_id = $_SESSION['book']['stax_id'];
                     $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$_SESSION['book']['bookamount'].' AND end >= '.$_SESSION['book']['bookamount'].' LIMIT 1');
                     if($result_tax_slab == false && $db->last_row_count()==0){
                             
                             foreach($_SESSION['book']['taxes'] as $tax_id => $taxes)
                                if(isset($taxes['services'])) unset($_SESSION['book']['taxes'][$tax_id]['services']); 
                       
                        }
                      }else{
                         foreach($_SESSION['book']['taxes'] as $tax_id => $taxes)
                                if(isset($taxes['services'])) unset($_SESSION['book']['taxes'][$tax_id]['services']);  
                      }
              // End slab booking tax calculation              
           
                
         
        }
        
        $people = $_SESSION['book']['adults']+$_SESSION['book']['children'];
        $adults = $_SESSION['book']['adults'];
        $children = $_SESSION['book']['children'];
        $nights = $_SESSION['book']['nights'];

        $extra_services = array();
        $total_services = 0;
        $duty_free_services = 0;
        $taxes = array();
        $rooms_ids = array_keys($_SESSION['book']['rooms']);
        
         if(isset($_POST['coupon_code']) && $_POST['coupon_code'] != ''){
            $coupon_code = htmlentities($_POST['coupon_code'], ENT_COMPAT, 'UTF-8');
            $result_coupon = $db->query('SELECT * FROM pm_coupon WHERE checked = 1 AND UPPER(code) = UPPER('.$db->quote($coupon_code).') AND discount > 0 AND (publish_date IS NULL || publish_date <= '.time().') AND (unpublish_date IS NULL || unpublish_date > '.time().') LIMIT 1');
            if($result_coupon !== false && $db->last_row_count() > 0){
                $row = $result_coupon->fetch();
                $_SESSION['book']['discount'] = $row['discount'];
                $_SESSION['book']['discount_type'] = $row['discount_type'];
                //$response['success'] .= $texts['COUPON_CODE_SUCCESS'];
                $_SESSION['book']['coupon'] = $_POST['coupon_code'];
            }else{
                
                //$response['error'] .= $texts['COUPON_CODE_ERROR'];
            }
        }else{
            $_SESSION['book']['coupon'] = '';
            $_SESSION['book']['discount']=0;
            $_SESSION['book']['discount_type']='';
        }
        
        if(isset($_POST['extra_services']) && count($_POST['extra_services']) > 0){

            $result_service = $db->query('SELECT * FROM pm_service WHERE id IN('.implode(',', $_POST['extra_services']).') AND checked = 1 AND lang = '.LANG_ID);
            if($result_service !== false){
                
                $tax_id = 0;
                $result_incl_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 GROUP BY id ORDER BY rank LIMIT 1');
                $result_incl_tax->bindParam(':tax_id', $tax_id);
                
                $taxes_id = '';
                $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE (FIND_IN_SET(id, :taxes_id) OR id = :tax_id) AND checked = 1 AND value > 0 GROUP BY id ORDER BY rank');
                $result_tax->bindParam(':taxes_id', $taxes_id);
                $result_tax->bindParam(':tax_id', $tax_id);
        
                foreach($result_service as $i => $row){
                    $id = $row['id'];
                    $type = $row['type'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $tax_id = $row['id_tax'];
                    $taxes_id = $row['taxes'];
                    $rooms = explode(',', $row['rooms']);
                    
                    $nb_rooms = count(array_intersect($rooms, $rooms_ids));
                    
                    $qty = 0;
                    $rate = 0;
                    if(strpos($type, 'qty') !== false && isset($_POST['qty_service_'.$id])){
                        $qty = $_POST['qty_service_'.$id];
                        $rate = $qty;
                        if($type == 'qty-night') $rate *= $nights;
                        if($type == 'qty-person-night') $rate *= $nights*$people;
                        if($type == 'qty-adult-night') $rate *= $nights*$adults;
                        if($type == 'qty-child-night') $rate *= $nights*$children;
                    }else{
                        if($type == 'person-night') $qty = $nights*$people;
                        if($type == 'adult-night') $qty = $nights*$adults;
                        if($type == 'child-night') $qty = $nights*$children;
                        if($type == 'person') $qty = $people;
                        if($type == 'adult') $qty = $adults;
                        if($type == 'child') $qty = $children;
                        if($type == 'night') $qty = $nights*$nb_rooms;
                        if($type == 'package') $qty = 1;
                        $rate = $qty;
                    }

                    if($qty > 0){
                        $price = $rate*$price;
                        $total_services += $price;
                        $extra_services[$id]['title'] = $title;
                        $extra_services[$id]['qty'] = $qty;
                        $extra_services[$id]['amount'] = $price;
                        
                        if(isset($_SESSION['book']['amount_rooms'])){
                            $duty_free = $price;
                            if($result_incl_tax->execute() !== false && $db->last_row_count() > 0){
                                $incl_tax = $result_incl_tax->fetch();
								$extra_services[$id]['tax_rate'] = number_format($incl_tax['value'], 2, '.', '');
                                $duty_free = $price/($incl_tax['value']/100+1);
								$extra_services[$id]['duty_free'] = $duty_free;
                            }
                    
                            $duty_free_services += $duty_free;
                            
                            if($result_tax->execute() !== false){
                                foreach($result_tax as $tax){
                                    if(!isset($taxes[$tax['id']])) $taxes[$tax['id']] = 0;
                                    $taxes[$tax['id']] += $duty_free*($tax['value']/100);
                                }
                            }
                        }
                    }
                }
            }
            if($total_services > 0){
            
                if(isset($_SESSION['book']['amount_rooms'])){
                        // Start slab booking tax calculation   
                          if(isset($_SESSION['book']['stax_id'])){
                                 $tax_id = $_SESSION['book']['stax_id'];
                                 $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$_SESSION['book']['bookamount'].' AND end >= '.$_SESSION['book']['bookamount'].' LIMIT 1');
                                 if($result_tax_slab == false && $db->last_row_count()==0){
                                         
                                        foreach($taxes as $tax_id => $tax_amount){
                                            $_SESSION['book']['tax_services_amount'] += $tax_amount;
                                            $_SESSION['book']['taxes'][$tax_id]['services'] = $tax_amount;
                                        }
                                                       
                                    }
                                  }else{
                                     foreach($taxes as $tax_id => $tax_amount){
                                            $_SESSION['book']['tax_services_amount'] += $tax_amount;
                                            $_SESSION['book']['taxes'][$tax_id]['services'] = $tax_amount;
                                        }  
                                  }
                          // End slab booking tax calculation 
                   
                }
            }
        }
        
        $_SESSION['book']['extra_services'] = $extra_services;
        
         if(isset($_SESSION['book']['amount_rooms'])){
                    $total_duty_free = round($_SESSION['book']['duty_free_rooms']) + round($duty_free_services);
                    $_SESSION['book']['roomtotal'] = round($total_duty_free) ;
                    //discount calculation
                       $_SESSION['book']['discount_amount']=0;
                          if(isset($_SESSION['book']['discount']) && $_SESSION['book']['discount'] > 0){
                            if($_SESSION['book']['discount_type'] == 'fixed') $_SESSION['book']['discount_amount'] = $_SESSION['book']['discount'];
                            elseif($_SESSION['book']['discount_type'] == 'rate') $_SESSION['book']['discount_amount'] = $total_duty_free*$_SESSION['book']['discount']/100;
                           $total_duty_free= round($total_duty_free) - round($_SESSION['book']['discount_amount']);
                        }
                    //discount calculation 
                  //$_SESSION['book']['payableamount']
                  $_SESSION['book']['bookamount'] = round($total_duty_free);
                  $_SESSION['book']['taxableamount'] = round($total_duty_free);
                  // Start slab booking tax calculation        
                  if(isset($_SESSION['book']['stax_id'])){
                     $tax_id = $_SESSION['book']['stax_id'];
                     $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$total_duty_free.' AND end >= '.$total_duty_free.' LIMIT 1');
                     if($result_tax_slab != false && $db->last_row_count()>0){
                         
                             $_SESSION['book']['taxslab'] ='active';
                             $row = $result_tax_slab->fetch();
                             $_SESSION['book']['duty_free_services'] = $duty_free_services;
                             
                             $_SESSION['book']['tax_rooms_amount']= $total_duty_free*($row['value']/100);
                             $_SESSION['book']['slab_tax_amount'] =$_SESSION['book']['tax_rooms_amount'];
                             $_SESSION['book']['amount_services'] = $total_services;
                             $_SESSION['book']['total'] = $total_duty_free+$_SESSION['book']['tax_rooms_amount'];
                                                        
                       
                        }else{
                             unset($_SESSION['book']['taxslab']);
                             unset($_SESSION['book']['slab_tax_amount']);
                             $_SESSION['book']['amount_services'] = $total_services;
                             $_SESSION['book']['duty_free_services'] = $duty_free_services;
                             $_SESSION['book']['total'] = $total_duty_free+$_SESSION['book']['tax_rooms_amount'];
                        }
                      }else{
                          $_SESSION['book']['amount_services'] = $total_services;
                          $_SESSION['book']['duty_free_services'] = $duty_free_services;
                          $_SESSION['book']['total'] = $total_duty_free+$_SESSION['book']['tax_rooms_amount'];
                      }
              // Start slab booking tax calculation  
              
              
              if(isset($_SESSION['book']['discount']) && $_SESSION['book']['discount'] > 0){

                $response['html'] .= '
                <div class="row disount">
                    <div class="col-xs-7 lead"><b>'.$_SESSION['book']['coupon'].'</b><span>applied</span><div class="applied_link"><a class="btn-link text-rihgt" data-toggle="modal" data-target="#modalCoupon">Change</a> <a href="javascript: void(0);" id="coupon_remove" onclick="remove_coupon();">Remove</a></div></div>
                    <div class="col-xs-5 lead text-right">- '.formatPrice($_SESSION['book']['discount_amount']*CURRENCY_RATE).'</div>
                </div>';
                  $response['html'] .= '';
            }else{
                  $response['html'] .=  $texts['DO_YOU_HAVE_A_COUPON'].'<a class="btn-link" data-toggle="modal" data-target="#modalCoupon">Apply Coupon</a>';
            }
            
            

             // Start slab booking tax calculation 
              if(isset($_SESSION['book']['stax_id'])){
                 $tax_id = $_SESSION['book']['stax_id'];
                 $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$total_duty_free.' AND end >= '.$total_duty_free.' LIMIT 1');
                if($result_tax_slab !== false && $db->last_row_count() > 0){
                    $row = $result_tax_slab->fetch();
                    //$_SESSION['book']['taxes'] = $row['value'];
                    $tax_amount = $_SESSION['book']['tax_rooms_amount'];
                    $response['html'] .= '
                        <div class="row tax">
                                    <div class="col-xs-6">'.db_getFieldValue($db, 'pm_tax', 'name', $tax_id, $lang = 0).'('.db_getFieldValue($db, 'pm_tax', 'value', $tax_id, $lang = 0).'%)</div>
                                    <div class="col-xs-6 text-right">'.formatPrice($tax_amount*CURRENCY_RATE).'</div>
                        </div>';
                }else{
                    $tax_id = 0;
                    $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
                    $result_tax->bindParam(':tax_id', $tax_id);
                    
                    foreach($_SESSION['book']['taxes'] as $tax_id => $taxes){
                        $tax_amount = 0;
                        foreach($taxes as $amount) $tax_amount += $amount;
                        if($tax_amount > 0){
                            if($result_tax->execute() !== false && $db->last_row_count() > 0){
                                $row = $result_tax->fetch();
                                $response['html'] .= '
                                <div class="row tax">
                                    <div class="col-xs-6">'.$row['name'].'('.db_getFieldValue($db, 'pm_tax', 'value', $tax_id, $lang = 0).'%)</div>
                                    <div class="col-xs-6 text-right">'.formatPrice($tax_amount*CURRENCY_RATE).'</div>
                                </div>';
                            }
                        }
                    }
                 }
               }
            //Start slab booking tax calculation 
            
            /*$tax_id = 0;
                $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
                $result_tax->bindParam(':tax_id', $tax_id);
                foreach($_SESSION['book']['taxes'] as $tax_id => $taxes){
                    $tax_amount = 0;
                    foreach($taxes as $amount){
                        $tax_amount += $amount;
                    }
                    if($tax_amount > 0){
                        if($result_tax->execute() !== false && $db->last_row_count() > 0){
                            $row = $result_tax->fetch();
                            $response['html'] .= '
                            <div class="row tax">
                                <div class="col-xs-6">'.$row['name'].' ('.$row['value'].'%)</div>
                                <div class="col-xs-6 text-right">'.formatPrice($tax_amount*CURRENCY_RATE).'</div>
                            </div>';
                        }
                    }
                }
            */
            
            
           
            
            
            
            $_SESSION['book']['down_payment'] = (ENABLE_DOWN_PAYMENT == 1 && DOWN_PAYMENT_RATE > 0 && $_SESSION['book']['total'] >= DOWN_PAYMENT_AMOUNT) ? $_SESSION['book']['total']*DOWN_PAYMENT_RATE/100 : 0;
            
           
            $response['html'] .= '<hr>';
            $response['html'] .= '
            <div class="row amount">
                <div class="col-xs-6">
                    <h3>'.$texts['TOTAL'].' <small>('.$texts['INCL_TAX'].')</small></h3>
                </div>
                <div class="col-xs-6 lead text-right">'.formatPrice($_SESSION['book']['total']*CURRENCY_RATE).'</div>
            </div>';
            
            
        }
        echo json_encode($response);
    }
}
