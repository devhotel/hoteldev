<?php
require_once('book.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '', 'extraHtml' => '');
if(isset($db) && $db !== false){
        
    if(isset($_POST['book'])){
       
        if(isset($_SESSION['bt']['amount_rooms'])){
            $_SESSION['bt']['tax_services_amount'] = 0;
            $_SESSION['bt']['amount_services'] = 0;
            $_SESSION['bt']['duty_free_services'] = 0;
            $_SESSION['bt']['wallet'] = 0;
            $_SESSION['bt']['payable_amount'] = 0;
            $_SESSION['bt']['gamount'] = 0;
            
            // Start slab booking tax calculation   
              if(isset($_SESSION['bt']['stax_id'])){
                     $tax_id = $_SESSION['bt']['stax_id'];
                     $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$_SESSION['bt']['bookamount'].' AND end >= '.$_SESSION['bt']['bookamount'].' LIMIT 1');
                     if($result_tax_slab == false && $db->last_row_count()==0){
                             
                             foreach($_SESSION['bt']['taxes'] as $tax_id => $taxes)
                                if(isset($taxes['services'])) unset($_SESSION['bt']['taxes'][$tax_id]['services']); 
                       
                        }
                      }
            // End slab booking tax calculation 
            

        }
       
        
        $people = $_SESSION['bt']['adults']+$_SESSION['bt']['children'];
        $adults = $_SESSION['bt']['adults'];
        $children = $_SESSION['bt']['children'];
        $nights = $_SESSION['bt']['nights'];

        $extra_services = array();
        $total_services = 0;
        $duty_free_services = 0;
        $taxes = array();
        $rooms_ids = array_keys($_SESSION['bt']['rooms']);
        
        
         // booking coupon varification and apply 
            if(isset($_SESSION['bt']['amount_rooms'])){                           
              if(isset($_POST['coupon_code']) && $_POST['coupon_code'] != ''){
                $coupon_code = htmlentities($_POST['coupon_code'], ENT_COMPAT, 'UTF-8');
                $result_coupon = $db->query('SELECT * FROM pm_coupon WHERE checked = 1 AND UPPER(code) = UPPER('.$db->quote($coupon_code).') AND discount > 0 AND (publish_date IS NULL || publish_date <= '.time().') AND (unpublish_date IS NULL || unpublish_date > '.time().') LIMIT 1');
                if($result_coupon !== false && $db->last_row_count() > 0){
                    $row = $result_coupon->fetch();
                    $_SESSION['bt']['discount'] = $row['discount'];
                    $_SESSION['bt']['discount_type'] = $row['discount_type'];
                    $response['success'] .= $texts['COUPON_CODE_SUCCESS'];
                }else{
                    $response['error'] .= 'No coupon available';
                }
              }
            }
            
    
     // booking room service list and price calculation 
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
                        if($type == 'qty-night') $rate *= intval($nights);
                        if($type == 'qty-person-night') $rate *= intval($nights)*intval($people);
                        if($type == 'qty-adult-night') $rate *= intval($nights)*intval($adults);
                        if($type == 'qty-child-night') $rate *= intval($nights)*intval($children);
                    }else{
                        if($type == 'person-night') $qty = intval($nights)*intval($people);
                        if($type == 'adult-night') $qty = intval($nights)*intval($adults);
                        if($type == 'child-night') $qty = intval($nights)*intval($children);
                        if($type == 'person') $qty = intval($people);
                        if($type == 'adult') $qty = intval($adults);
                        if($type == 'child') $qty = intval($children);
                        if($type == 'night') $qty = intval($nights*$nb_rooms);
                        if($type == 'package') $qty = 1;
                        $rate = $qty;
                    }

                    if($qty > 0){
                        $price = $rate*$price;
                        $total_services += $price;
                        $extra_services[$id]['title'] = $title;
                        $extra_services[$id]['qty'] = $qty;
                        $extra_services[$id]['amount'] = $price;
                        $_SESSION['bt']['total_services'] = $total_services;
                        if(isset($_SESSION['bt']['amount_rooms'])){
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
            if(isset($_SESSION['bt']['amount_rooms'])){
                 
                        // Start slab booking tax calculation   
                          if(isset($_SESSION['bt']['stax_id'])){
                                 $tax_id = $_SESSION['bt']['stax_id'];
                                 $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$_SESSION['bt']['bookamount'].' AND end >= '.$_SESSION['bt']['bookamount'].' LIMIT 1');
                            if($result_tax_slab == false && $db->last_row_count()==0){
                                         
                                foreach($taxes as $tax_id => $tax_amount){
                                 $_SESSION['bt']['tax_services_amount'] += $tax_amount;
                                 $_SESSION['bt']['taxes'][$tax_id]['services'] = $tax_amount;
                               }
                                                       
                           }
                        }
                    // End slab booking tax calculation 
               }
            }
          }
          
        $_SESSION['bt']['extra_services'] = $extra_services;
     
          if(isset($_SESSION['bt']['amount_rooms'])){
                $total_duty_free = $_SESSION['bt']['duty_free_rooms'] + $duty_free_services;
                $_SESSION['bt']['gamount']= round($total_duty_free) ;
                 //discount calculation
                       $_SESSION['bt']['discount_amount']=0;
                          if(isset($_SESSION['bt']['discount']) && $_SESSION['bt']['discount'] > 0){
                            if($_SESSION['bt']['discount_type'] == 'fixed') $_SESSION['bt']['discount_amount'] = $_SESSION['bt']['discount'];
                            elseif($_SESSION['bt']['discount_type'] == 'rate') $_SESSION['bt']['discount_amount'] = $total_duty_free*$_SESSION['bt']['discount']/100;
                           $total_duty_free= round($total_duty_free) - round($_SESSION['bt']['discount_amount']);
                        }
                    //discount calculation 
                $_SESSION['book']['bookamount'] = round($total_duty_free);
                $_SESSION['book']['taxableamount'] = round($total_duty_free);
               // 1. Start slab booking tax calculation        
                  if(isset($_SESSION['bt']['stax_id'])){
                     $tax_id = $_SESSION['bt']['stax_id'];
                     $result_tax_slab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax='.$tax_id.'  AND start <= '.$total_duty_free.' AND end >= '.$total_duty_free.' LIMIT 1');
                     if($result_tax_slab != false && $db->last_row_count()>0){
                             $row = $result_tax_slab->fetch();
                             $_SESSION['bt']['taxslab'] ='active';
                             $_SESSION['bt']['duty_free_services'] = $duty_free_services;
                             $_SESSION['bt']['tax_rooms_amount']= $total_duty_free*($row['value']/100);
                             $_SESSION['bt']['slab_tax_amount'] =$_SESSION['bt']['tax_rooms_amount'];
                             $_SESSION['bt']['amount_services'] = $total_services;
                             
                            
                             $_SESSION['bt']['total'] = $total_duty_free+$_SESSION['bt']['tax_rooms_amount'];
                       
                        }else{
                             unset($_SESSION['bt']['taxslab']);
                             unset($_SESSION['bt']['slab_tax_amount']);
                             $_SESSION['bt']['amount_services'] = $total_services;
                             $_SESSION['bt']['duty_free_services'] = $duty_free_services;
                             /*$_SESSION['bt']['total'] = $_SESSION['bt']['duty_free_rooms']+$_SESSION['bt']['tax_rooms_amount']/*+$_SESSION['book']['tourist_tax']*/
                                                       // + $_SESSION['bt']['duty_free_services']+$_SESSION['bt']['tax_services_amount']; 
                                                       
                             $_SESSION['bt']['total'] = $total_duty_free+$_SESSION['bt']['tax_rooms_amount'];
                        }
                      }else{
                          $_SESSION['bt']['amount_services'] = $total_services;
                          $_SESSION['bt']['duty_free_services'] = $duty_free_services;
                          $_SESSION['bt']['total'] = $total_duty_free+$_SESSION['bt']['tax_rooms_amount'];
                      }
              // Start slab booking tax calculation  
            
              $response['html'] .= '
                <div class="row amount">
                    <div class="col-xs-6">
                        '.$texts['TOTAL'].'
                    </div>
                    <div class="col-xs-6  text-right">'.formatPrice($_SESSION['bt']['gamount']*CURRENCY_RATE).'</div>
                </div>';

             // booking Coupon and other discount calculation
            if(isset($_SESSION['bt']['discount']) && $_SESSION['bt']['discount'] > 0){
                
                /*$response['html'] .= '
                <div class="row amount">
                    <div class="col-xs-6">
                        '.$texts['TOTAL'].' <small>('.$texts['INCL_TAX'].')</small>
                    </div>
                    <div class="col-xs-6  text-right">'.formatPrice($_SESSION['bt']['total']*CURRENCY_RATE).'</div>
                </div>'; */
                
                /*if($_SESSION['bt']['discount_type'] == 'fixed') $_SESSION['bt']['discount_amount'] = $_SESSION['bt']['discount'];
                elseif($_SESSION['bt']['discount_type'] == 'rate') $_SESSION['bt']['discount_amount'] = $_SESSION['bt']['total']*$_SESSION['bt']['discount']/100;
                $_SESSION['bt']['total'] -= $_SESSION['bt']['discount_amount'];
                */
                
                $response['html'] .= '
                <div class="row disount">
                    <div class="col-xs-6">'.$texts['DISCOUNT'].'</div>
                    <div class="col-xs-6 text-right">- '.formatPrice($_SESSION['bt']['discount_amount']*CURRENCY_RATE).'</div>
                </div>';
            }
            
            $_SESSION['bt']['down_payment'] = (ENABLE_DOWN_PAYMENT == 1 && DOWN_PAYMENT_RATE > 0 && $_SESSION['bt']['total'] >= DOWN_PAYMENT_AMOUNT) ? $_SESSION['bt']['total']*DOWN_PAYMENT_RATE/100 : 0;
            
            //2. Start slab booking tax calculation 
              if(isset($_SESSION['bt']['stax_id'])){
                $tax_id = $_SESSION['bt']['stax_id'];
                if($result_tax_slab !== false && $db->last_row_count() > 0){
                    $row = $result_tax_slab->fetch();
                    //$_SESSION['bt']['taxes'] = $row['value'];
                    $tax_amount = $_SESSION['bt']['tax_rooms_amount'];
                    $response['html'] .= '
                        <div class="row tax">
                                    <div class="col-xs-6">'.db_getFieldValue($db, 'pm_tax', 'name', $tax_id, $lang = 0).'</div>
                                    <div class="col-xs-6 text-right">'.formatPrice($tax_amount*CURRENCY_RATE).'</div>
                        </div>';
                }else{
                    $tax_id = 0;
                    $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.LANG_ID.' ORDER BY rank');
                    $result_tax->bindParam(':tax_id', $tax_id);
                    
                    foreach($_SESSION['bt']['taxes'] as $tax_id => $taxes){
                        $tax_amount = 0;
                        foreach($taxes as $amount) $tax_amount += $amount;
                        if($tax_amount > 0){
                            if($result_tax->execute() !== false && $db->last_row_count() > 0){
                                $row = $result_tax->fetch();
                                $response['html'] .= '
                                <div class="row tax">
                                    <div class="col-xs-6">'.$row['name'].'</div>
                                    <div class="col-xs-6 text-right">'.formatPrice($tax_amount*CURRENCY_RATE).'</div>
                                </div>';
                            }
                        }
                    }
                 }
               }
            //Start slab booking tax calculation 
           
            
            $check='';
            $exfield='';
            if($_SESSION['bt']['total'] > 0){  
                if(isset($_POST['id_user']) && $_POST['id_user'] != ''){
                    $id = (int)$_POST['id_user'];
                    $wallet_info = $db->query("SELECT * FROM pm_wallet WHERE user_id=".$db->quote($id));
                    $wallet_result = $wallet_info->fetch();
                 if($wallet_info->execute() !== false && $db->last_row_count() > 0){
                    if($wallet_result['amount'] > 0){
                       if($wallet_result['amount'] > $_SESSION['bt']['total']){
                            $_SESSION['bt']['wallet'] = $_SESSION['bt']['total'];
                            $_SESSION['bt']['payable_amount']= 0;
                            $check='<input type="hidden" name="wallet" id="wallet" value="wallet" >';
                            $exfield='';
                            $exprice='';
                       }else{
                            $_SESSION['bt']['wallet'] = $wallet_result['amount']; 
                            $_SESSION['bt']['payable_amount']=$_SESSION['bt']['total']-$wallet_result['amount'];
                            $check='<input type="checkbox" name="wallet" id="wallet" value="wallet" checked >';
                            $exfield='<span>On arrival </span>';
                            $exprice = $_SESSION['bt']['payable_amount'];
                       }
                       $response['html'] .= '<hr>';
                       $response['html'] .= '
                        <div class="row wallet">
                            <div class="col-xs-7">'.$check.'
                                <b>Wallet ('.formatPrice($wallet_result['amount']*CURRENCY_RATE).')<b><br>
                               '.$exfield.'
                            </div>
                            <div class="col-xs-5  text-right">'.formatPrice($_SESSION['bt']['wallet']*CURRENCY_RATE).'<br>
                            '.formatPrice($exprice*CURRENCY_RATE).'
                            </div>
                        </div>';
                    }else{
                         $_SESSION['bt']['payable_amount'] = $_SESSION['bt']['total'];
                    }
                 }else{
                      $_SESSION['bt']['payable_amount']=$_SESSION['bt']['total'];
                 }
                }
             }
             
           if($_SESSION['bt']['total'] > 0){ 
            $response['html'] .= '<hr>';
            $response['html'] .= '
            <div class="row amount">
                <div class="col-xs-7 lead">
                    '.$texts['TOTAL'].' <small>('.$texts['INCL_TAX'].')</small>
                </div>
                <div class="col-xs-5 lead text-right">'.formatPrice($_SESSION['bt']['total']*CURRENCY_RATE).'</div>
            </div>';
           }
 
        }
        
        if($_SESSION['bt']['total'] > 0 && isset($_POST['step'])){
            $response['html'] .= '<div class="row mb10">
               <div class="col-xs-5">';
                   if($_SESSION['bt']['payable_amount']==0){
                        $response['html'] .= '<input type="radio" name="payment_opt" id="payment_opt" value="wallet" checked > <b>Wallet </b>
                          <input type="hidden" name="payment_option" value="wallet" >';  
                   }else{
                        $response['html'] .= '<input type="radio" name="payment_opt" id="payment_opt" value="arrivel" checked > On Arrival  
                        <input type="hidden" name="payment_option" value="arrival" >';
                   }
               $response['html'] .= '</div>
               <div class="col-xs-7">';
                if($_SESSION['bt']['payable_amount']==0){
                         $response['html'] .= '<input type="hidden" name="payment_mode" value="wallet" >';  
                   }else{
                         $response['html'] .= '<select class="form-control" name="payment_mode" id="payment_mode">
                           <option value="cash_pay">Cash Pay</option>
                           <option value="credit_card">Credit card</option>
                           <option value="debit_card">Debit card</option>
                           <option value="net_banking">Net banking</option>
                        </select>';
                    }
                
              $response['html'] .= '</div>
              </div>
              <div class="row mb10">';
                $response['html'] .= '<div class="col-xs-12">';
                   if($_SESSION['bt']['payable_amount']==0){
                        $response['html'] .= '<input type="hidden" name="transactionid" value="'.randTransaction().'" >';  
                   }else{
                        $response['html'] .= '<lebel>Transaction id</lebel>
                           <input class="form-control" type="text" name="transactionid" value="" >';
                   }
                $response['html'] .= '</div>
              </div>
              <div class="row process">
               <div class="col-xs-5"></div>
               <div class="col-xs-7">
                <a class="btn btn-default form-control " onclick="create_booking();"> <i id="spin2" class="fa fa-spinner fa-spin" style="display:none;"></i><span>'.formatPrice($_SESSION['bt']['total']*CURRENCY_RATE).'</span> Book now</a>
               </div>
            </div>';
        }
        
        echo json_encode($response); 
     }
     
       
}
