<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
global $res_room;
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$id_hotel =$_POST['id_hotel'];

$num_people = $_POST['num_adults']+$_POST['num_children'];

if(!is_numeric($_POST['num_adults'])) $field_notice['num_adults'] = '';
if(!is_numeric($_POST['num_children'])) $field_notice['num_children'] = '';

if($_POST['from_date'] == '') $field_notice['dates'] ='';
else{
    $time = explode('/', $_POST['from_date']);
    if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
    if(!is_numeric($time)) $field_notice['dates'] ='';
    else $from_time = $time;
}
if($_POST['to_date'] == '') $field_notice['dates'] = '';
else{
    $time = explode('/', $_POST['to_date']);
    if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
    if(!is_numeric($time)) $field_notice['dates'] = '';
    else $to_time = $time;
}

$today = gm_strtotime(gmdate('Y').'-'.gmdate('n').'-'.gmdate('j').' 00:00:00');

if($from_time < $today || $to_time < $today || $to_time <= $from_time){
    $from_time = $today;
    $to_time = $today+86400;
    $_POST['from_date'] = gmdate('d/m/Y', $from_time);
    $_POST['to_date'] = gmdate('d/m/Y', $to_time);
}

if(is_numeric($from_time) && is_numeric($to_time)){
    $num_nights = ($to_time-$from_time)/86400;
}else
    $num_nights = 0;

$hotel_ids = array();
$room_ids = array();
$available_rooms=array();

    if($num_nights <= 0) $msg_error .= '';
    else{
        require_once('functions.php');
        $res_hotel = getAdmRoomsResult($from_time, $to_time, $_POST['num_adults'], $_POST['num_children']);
        foreach($res_hotel as $key=>$aval){
           $available_rooms[]= $key;
        }
    }

     //echo '<pre>';
    // print_r($res_hotel);
     $query_room = 'SELECT * FROM pm_room WHERE id_hotel = :id_hotel AND checked = 1 AND lang = '.LANG_ID;
     if(!empty($hidden_rooms)) $query_room .= ' AND id NOT IN('.implode(',', $hidden_rooms).')';
     $query_room .= ' ORDER BY';
     if(!empty($room_ids)) $query_room .= ' CASE WHEN id IN('.implode(',', $room_ids).') THEN 3 ELSE 4 END,';
     $query_room .= ' rank';
     $result_room = $db->prepare($query_room);
     $result_room->bindParam(':id_hotel', $id_hotel);
     $result_room->execute();
     //echo '<pre>';
     //print_r($available_rooms);
     
     ?>
             <div class="rooms boxed table-responsive">
                 <input  type="hidden" name="from_time" value="<?php echo $from_time; ?>" />
                 <input type="hidden" name="to_time" value="<?php echo $to_time; ?>" />
                 <table class="table">
                    <?php
                        if (array_key_exists($id_hotel,$res_hotel)){
                            if($result_room !== false){
                                 foreach($result_room as $row){
                                     if (array_key_exists($row['id'],$res_hotel[$id_hotel])){
                                          
                                            $id_room = $row['id'];
                                            $room_title = $row['title'];
                                            $room_alias = $row['alias'];
                                            $room_subtitle = $row['subtitle'];
                                                        $room_descr = $row['descr'];
                                                        //$room_price = $row['price'];
                                                        if(isset($_SESSION['rid']) && isset($_SESSION['rprice'])){
                                                            if($_SESSION['rid']==$id_room){
                                                              $res_room[$id_room]['price'] = $_SESSION['rprice'];
                                                              $room_price = $_SESSION['rprice'];
                                                            }else{
                                                             $room_price = $row['price'];
                                                            }
                                                    	  }else{
                                                    	   $room_price = $row['price'];
                                                    	 }
                                                        $room_stock = $row['stock'];
                                                        $max_adults = $row['max_adults'];
                                                        $max_children = $row['max_children'];
                                                        $max_people = $row['max_people'];
                                                        $min_people = $row['min_people'];
                                                        $room_facilities = $row['facilities'];
                                                       if(isset($res_hotel[$id_hotel][$id_room]['room_stock'])){
                                                               //$currnt_room_stock = $res_hotel[$id_hotel][$id_room]['room_stock'];
                                                           }else{
                                                               //$currnt_room_stock  = $room_stock;
                                                           }
                                                           $currnt_room_stock = isset($res_room[$id_room]['room_stock']) ? $res_room[$id_room]['room_stock'] : $row['stock'];
                                                        if($currnt_room_stock > 0){

                                                        $sale_price = @$res_hotel[$id_hotel][$id_room]['price'];
                                                        $amount = @$res_hotel[$id_hotel][$id_room]['amount'];
                                                        $full_price = @$res_hotel[$id_hotel][$id_room]['full_price'];
                                                        $type = @$res_hotel[$id_hotel][$id_room]['type'];
                                                        $tax_id = @$res_hotel[$id_hotel][$id_room]['taxes']['tax_id'];
                                                        $taxes = @$res_hotel[$id_hotel][$id_room]['taxes'][$tax_id]['amount'];
                                                        $tax_fixed_sup= @$res_hotel[$id_hotel][$id_room]['taxes'][$tax_id]['fixed_sup'];
                                                        
                                                        $days = @$res_hotel[$id_hotel][$id_room]['days'];
                                                        if(count($days)== $_POST['nights']){
                                                        ?>
                                                        <tr>
                                                        <td>
                                                        <div class="row">
                                                            <div class="col-md-12 room_item">
                                                                <input id="chk_room_<?php echo $id_room; ?>"  type="checkbox" name="item[]" class="select_room sendAjaxForm" data-action="update_booking.php" data-target="#total_booking" value="<?php echo $id_room; ?>"  onclick="get_services(this.id);" >
                                                            
                                                                 <lebel data-labelfor="chk_room_<?php echo $id_room; ?>">
                                                                    <?php echo $room_title; ?> 
                                                                     <span>
                                                                        <div class="price">
                                                                            <span itemprop="priceRange"><?php echo formatPrice($amount*CURRENCY_RATE); ?> For <?php echo count($days);?><?php echo (count($days) > 1 ? ' Nights' : ' Night' ); ?></span>
                                                                            <?php
                                                                            if($full_price > 0 && $full_price > $amount){ ?>
                                                                                <?php //echo formatPrice($full_price*CURRENCY_RATE); ?><?php //echo count($days);?>
                                                                                <?php
                                                                            } ?>
                                                                            
                                                                        </div>
                                                                    </span> 
                                                                </lebel>
                                                            </div>
                                                        </div>    
                                                            
                                                        <div class="row" id="view_chk_room_<?php echo $id_room; ?>" style="display:none">
                                                              <div class="col-md-8">
                                                                  <input type="hidden" name="rooms[]" value="<?php echo $id_room; ?>">
                                                                  <input type="hidden" name="room_<?php echo $id_room; ?>" value="<?php echo $room_title; ?>">
                                                                  <input type="hidden" name="amount_rooms[]" value="<?php echo $amount; ?>">
                                                                   <!--<div class="price">
                                                                    <span itemprop="priceRange">
                                                                        <?php
                                                                         if(isset($sale_price)){
                                                                             //echo formatPrice($sale_price*CURRENCY_RATE);   
                                                                         }else{
                                                                             //echo formatPrice($room_price*CURRENCY_RATE);
                                                                         }
                            
                                                                        ?>
                                                                    </span>
                                                                    <span class="mb10 text-muted"><?php // echo 'Price/night'; ?></span>
                                                                </div> -->
                                                                 <?php echo strtrunc(strip_tags($room_descr), 100); ?>
                                                                 
                                                                 <div>Capacity: <i class="fas fa-fw fa-male"></i>x<?php echo $max_people; ?></div> 
                                                                
                                                              </div>
                                                              <div class="col-md-4">
                                                                  <div class="price">
                                                                    <span itemprop="priceRange"><?php echo formatPrice($amount*CURRENCY_RATE); ?></span>
                                                                    <?php
                                                                    if($full_price > 0 && $full_price > $amount){ ?>
                                                                        <br><s class="text-warning"><?php echo formatPrice($full_price*CURRENCY_RATE); ?></s>/<?php echo count($days);?>
                                                                        <?php
                                                                    } ?>
                                                                    <!--<span class="mb10 text-muted"><?php echo 'Price';?>/<?php echo count($days);?></span> -->
                                                                    <a class="mb10 change_xprice" id="cp_<?php echo $id_room; ?>" data-room="<?php echo $id_room; ?>" data-price="<?php echo $room_price ?>" onclick="get_modal_price(this.id);"  >Change Price</a>
                                                                </div>
                                                                <?php
                                                                if($room_stock > 0){ ?>
                                                                    <div class="pt10 form-inline">
                                                                     <div class="nrom rooms_count" id="nrom_<?php echo $id_room; ?>">
                                                                         <div class="incrmnt">
                                                                             <span class="plus"  data-p="<?php echo $id_room; ?>" id="plus_<?php echo $id_room; ?>">+</span>
                                                                               <input class="nrnvalue" id="nrmv_<?php echo $id_room; ?>" type="text" placeholder="1"  value="0" />
                                                                              <span class="minus" data-m="<?php echo $id_room; ?>" id="minus_<?php echo $id_room; ?>">-</span>
                                                                         </div> 
                                                                         <div style="display:none;">
                                                                            <select id="num_chk_room_<?php echo $id_room; ?>" name="num_rooms[<?php echo $id_room; ?>]" class="form-control num_room btn-group-sm sendAjaxForm selectpicker" data-target="#room-options-<?php echo $id_room; ?>" data-extratarget="#booking-amount" data-action="change_num_rooms.php?room=<?php echo $id_room; ?>" onchange="get_services(<?php echo $id_room; ?>);">
                                                                                <?php
                                                                                for($i = 0; $i <= $currnt_room_stock; $i++){ ?>
                                                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                                                    <?php
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                    <?php
                                                                }else{ ?>
                                                                    <?php
                                                                } ?>
                                                              </div>
                                                               <div class="col-md-12">
                                                                <div id="room-options-<?php echo $id_room; ?>" class="room-options"></div>
                                                                <div class="clearfix"></div>
                                                               </div>
                                                             </div>
                                                          </td>
                                                        </tr>
                                                        <?php }
                                                    }
                                                   }else{?>
                                                    <!--<tr><td>Room not available !!</td></tr>-->
                                                   <?php  
                                                   } 
                                                 }
                                                }else{ ?>
                                                    <tr><td>Room not available !!</td></tr>
                                                   <?php }
                                                }else{ ?>
                                                  <tr><td>Room not available !!</td></tr>
                                                   <?php }
                                                ?>
                                              </table>
                                            </div>