<?php
function getBookedRooms($from_time, $to_time, $room_id = 0)
{
    global $db;
    global $res_room;
    
    $days = array();
    $booked = array();
    
    $query_book = '
        SELECT stock, br.id_room as room_id, from_date, to_date
        FROM pm_booking as b, pm_booking_room as br, pm_room as r
        WHERE
            lang = '.DEFAULT_LANG.'
            AND
            br.id_room = r.id
            AND br.id_booking = b.id
            AND (status = 4 OR (status = 1 AND (b.add_date > '.(time()-900).' OR payment_option IN(\'arrival\',\'check\'))))
            AND from_date < '.$to_time.'
            AND to_date > '.$from_time.'
            AND br.chk = 1
            AND r.checked = 1';

			if(!empty($room_id)) $query_book .= '
			AND r.id = '.$room_id;
			$query_book .= '
		GROUP BY br.id
		
		UNION ALL
		
		SELECT stock, r.id as room_id, from_date, to_date
        FROM pm_room as r, pm_room_lock as rl
        WHERE
            lang = '.DEFAULT_LANG.'
            AND rl.id_room = r.id
            AND rl.add_date > '.(time()-900).'
            AND r.checked = 1';
			if(!empty($room_id)) $query_book .= '
			AND r.id = '.$room_id;
			if(isset($_SESSION['book']['sessid'])) $query_book .= '
			AND sessid != '.$db->quote($_SESSION['book']['sessid']);
			$query_book .= '
			
		UNION ALL
		
		SELECT (r.stock-rc.stock)+1 as stock, r.id as room_id, from_date, to_date
		FROM pm_room as r, pm_room_closing as rc
		WHERE
			lang = '.DEFAULT_LANG.'
			AND rc.id_room = r.id
			AND r.checked = 1
			AND from_date <= '.$to_time.'
			AND to_date >= '.$from_time;
			if(!empty($room_id)) $query_book .= '
			AND r.id = '.$room_id;
    
    $result_book = $db->query($query_book);
    if($result_book !== false){
        foreach($result_book as $i => $row){
            $start_date = $row['from_date'];
            $end_date = $row['to_date'];
            $id_room = $row['room_id'];
            $room_stock = $row['stock'];
			
            $start = ($start_date < $from_time) ? $from_time : $start_date;
            $end = ($end_date > $to_time) ? $to_time : $end_date;
            
            for($date = $start; $date < $end; $date += 86400){

                $days[$id_room][$date] = isset($days[$id_room][$date]) ? $days[$id_room][$date]+1 : 1;
			
                if($days[$id_room][$date]+1 > $room_stock && !in_array($date, $booked)) $booked[$id_room][] = $date;
            }
            $max = isset($days[$id_room]) ? max($days[$id_room]) : 0;
			
            $res_room[$id_room]['room_stock'] = max(0, $room_stock-$max);
        }
    }
    return $booked;
}


function getRoomsResult($from_time, $to_time, $num_adults, $children, $strict = false, $room_id = 0)
{

    global $db;
    global $texts;
    global $room_ids;
    global $hotel_ids;
    global $res_hotel;
    global $res_room;
    $res_hotel = array();
    
    if(is_null($room_ids)) $room_ids = array();
    if(is_null($hotel_ids)) $hotel_ids = array();
    
    $amount = 0;
    $total_nights = 0;
    $booked = getBookedRooms($from_time, $to_time, $room_id);
    //var_dump($res_room);
    
    $num_nights = ($to_time-$from_time)/86400;
    
    $num_children = is_array($children) ? count($children) : $children;
    $nu_adults = is_array($num_adults) ? count($num_adults) : $num_adults;
    $nu_clields = is_array($children) ? count($children) : $children;
    
    $tax_id = 0;
    $result_incl_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 GROUP BY id ORDER BY rank LIMIT 1');
    $result_incl_tax->bindParam(':tax_id', $tax_id);
    
    $rate_id = 0;
    $age = null;
    $result_child_rate = $db->prepare('SELECT * FROM pm_rate_child WHERE id_rate = :rate_id AND max_age >= :age AND min_age <= :age');
    $result_child_rate->bindParam(':rate_id', $rate_id);
    $result_child_rate->bindParam(':age', $age);
    
    $taxes_id = '';
    $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE (FIND_IN_SET(id, :taxes_id) OR id = :tax_id) AND checked = 1 AND value > 0 GROUP BY id ORDER BY rank');
    $result_tax->bindParam(':taxes_id', $taxes_id);
    $result_tax->bindParam(':tax_id', $tax_id);

    $query_rate = '
        SELECT DISTINCT name, ra.id as rate_id, min_nights, max_nights, days, max_adults, max_children, min_people, max_people, ro.id_hotel, id_room, start_date, end_date, ra.price, child_price, discount, discount_type, people, price_sup, fixed_sup, day_start, day_end, id_tax, taxes
        FROM pm_rate as ra, pm_room as ro, pm_package as p, pm_lang as l
        WHERE
            ro.lang = l.id
            AND l.checked = 1
            AND id_package = p.id
            AND id_room = ro.id
            AND ro.checked = 1
            AND stock > 0
            AND max_adults >= '.(isset($_SESSION["ab"]["adlts"])? max($_SESSION["ab"]["adlts"]):1).'
            AND max_children >= '.(isset($_SESSION["ab"]["kids"])? max($_SESSION["ab"]["kids"]):0).'
            AND (end_lock IS NULL OR end_lock < '.$from_time.' OR
                start_lock IS NULL OR start_lock > '.$to_time.')
            AND start_date <= '.$from_time.'
            AND end_date >= '.$to_time;
    if(!empty($booked)) $query_rate .= ' AND id_room NOT IN('.implode(',', array_keys($booked)).')';
    if(!empty($room_id)) $query_rate .= ' AND ro.id = '.$room_id;
    $query_rate .= '
        ORDER BY min_nights DESC, start_date DESC,  price ASC';
        $result_rate = $db->query($query_rate);
    /*print_r($result_rate);
    exit();*/
    if($result_rate !== false){
        foreach($result_rate as $i => $row){

            $rate_id = $row['rate_id'];
            $id_room = $row['id_room'];
            $id_hotel = $row['id_hotel'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $price = $row['price'];
            //price per night
            $per_night_price = $row['price'];
            
            $child_price = $row['child_price'];
            $discount = $row['discount'];
            $discount_type = $row['discount_type'];
            //$people = $row['people'];
            $people = 1;
            $price_sup = $row['price_sup'];
            $fixed_sup = $row['fixed_sup'];
            $day_start = $row['day_start'];
            $day_end = $row['day_end'];
            $days = explode(',', $row['days']);
            $tax_id = $row['id_tax'];
            $taxes_id = $row['taxes'];
            $min_stay = $row['min_nights'];
            $max_stay = $row['max_nights'];
            $min_people = $row['min_people'];
            $max_people = $row['max_people'];
            $max_adults = $row['max_adults'];
            $max_children = $row['max_children'];
             
             if($price_sup == '' ) $price_sup = 0;
             if($child_price == '') $child_price = 0;
             
             if(($price_sup == 0 && $child_price == 0) ||($child_price == 0 &&  $num_children>0) ||($price_sup == 0 && $num_adults>0) ){
                // $people = $row['people'];
             }
             
            $num_people = $num_adults+$num_children;
            
            if(!isset($res_hotel[$id_hotel][$id_room]['days'])) $res_hotel[$id_hotel][$id_room]['days'] = array();
            
            $from_n = gmdate('N', $from_time);
            $to_n = gmdate('N', $to_time);
            
            $error = false;
            if($num_nights < $min_stay){
                if(!isset($res_hotel[$id_hotel][$id_room]['min_stay'])) $res_hotel[$id_hotel][$id_room]['min_stay'] = $min_stay;
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MIN_NIGHTS'].' : '.$min_stay;
            }
            if($num_adults+$num_children > $max_people){
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MAX_PEOPLE'].' : '.$max_people;
                if(!$strict){
                    $num_adults = $max_adults;
                    $num_children = 0;
                }
            }
            if($num_adults+$num_children < $min_people){
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MIN_PEOPLE'].' : '.$min_people;
                if(!$strict){
                    $num_adults = $min_people;
                    $num_children = 0;
                }
            }
            if($num_adults > $max_adults){
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MAX_ADULTS'].' : '.$max_adults;
                if(!$strict)
                    $num_adults = $max_adults;
            }
            if($num_children > $max_children){
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MAX_CHILDREN'].' : '.$max_children;
                if(!$strict)
                    $num_children = $max_children;
            }
            
            /*if($num_people >= $max_people && $max_people > $people){
                $error = true;
                $res_hotel[$id_hotel][$id_room]['notice'] = $texts['MAX_PEOPLE'].' : '.$people;
                if(!$strict){
                    $num_adults = $max_adults;
                    $num_children = 0;
                }
            }*/

            if($error === false || !$strict){
                
                // package with default conditions
                if(($num_nights >= $min_stay || empty($min_stay))
                && ($num_nights <= $max_stay || empty($max_stay))
                && ($from_n == $day_start || empty($day_start))
                && ($to_n == $day_end || empty($day_end))){
					$extra_price = 0;
				     //Start Extra adult and extra child wise price 
                        if($num_people > $people){
                           
                            $extra_adults = ($num_adults > $people) ? $num_adults-$people : 0;
                            $extra_children = ($num_children > 0) ? ($num_people-$people)-$extra_adults : 0;
                            if($child_price == 0 && $price_sup > 0) $child_price = 0;
                            if($extra_children > 0 && $child_price >0){
    							 //$price += $child_price*$extra_children;
                                 $extra_price +=($price*$child_price/100)*$extra_children;
    						  }
                            if($price_sup > 0){
                                 //$extra_price += $price_sup*$extra_adults;
                                $extra_price += ($price*$price_sup/100)*$extra_adults;
                            }
                            
                            if($people == 0) $price = 0;
                            $price += $extra_price;
                         }
                    // End Extra adult and extra child wise price 
                        
					if($num_people >= $people || $extra_price >0){
                    
						// get common period between current rate and selected period
						$start = ($start_date < $from_time) ? $from_time : $start_date;
						$end = ($end_date > $to_time) ? $to_time : $end_date;
						
						$start = gm_strtotime(gmdate('Y', $start).'-'.gmdate('n', $start).'-'.gmdate('j', $start).' 00:00:00');
						$end = gm_strtotime(gmdate('Y', $end).'-'.gmdate('n', $end).'-'.gmdate('j', $end).' 00:00:00');
						
						// number of nights
						$nnights = 0;
						
						for($date = $start; $date < $end; $date += 86400){
							$d = gmdate('N', $date);
							if(!in_array($date, $res_hotel[$id_hotel][$id_room]['days']) && in_array($d, $days)){
								$res_hotel[$id_hotel][$id_room]['days'][] = $date;
								$nnights++;
							}
						}
						
						$price = $nnights*$price;
						$full_price = $price;
						if($discount > 0){
							if($discount_type == 'fixed'){
							    $price = $price-($nnights*$discount);
							    // Canculate price discount discount in fixed.
							    $per_night_price = $per_night_price-$discount;
							}
							elseif($discount_type == 'rate'){ 
							    $price = $price-($price*$discount/100);
							    // Canculate price discount discount in % rate.
							    $per_night_price = $per_night_price-($per_night_price*$discount/100);
							}
						}

						if(!isset($res_hotel[$id_hotel][$id_room]['total_nights']) || $res_hotel[$id_hotel][$id_room]['total_nights']+$nnights <= $num_nights){
							
							if(!isset($res_hotel[$id_hotel][$id_room]['amount'])) $res_hotel[$id_hotel][$id_room]['amount'] = 0;
							$res_hotel[$id_hotel][$id_room]['amount'] += $price;
							
							// assign price per night after discount if have discount.
							if(!isset($res_hotel[$id_hotel][$id_room]['price_per_night'])) $res_hotel[$id_hotel][$id_room]['price_per_night'] = 0;
							$res_hotel[$id_hotel][$id_room]['price_per_night'] += $per_night_price;
							
							if(!isset($res_hotel[$id_hotel][$id_room]['full_price'])) $res_hotel[$id_hotel][$id_room]['full_price'] = 0;
							$res_hotel[$id_hotel][$id_room]['full_price'] += $full_price;
							
							if(!isset($res_hotel[$id_hotel][$id_room]['total_nights'])) $res_hotel[$id_hotel][$id_room]['total_nights'] = 0;
							$res_hotel[$id_hotel][$id_room]['total_nights'] += $nnights;
							
							if(!isset($res_hotel[$id_hotel][$id_room]['fixed_sup'])) $res_hotel[$id_hotel][$id_room]['fixed_sup'] = 0;
							if($fixed_sup > $res_hotel[$id_hotel][$id_room]['fixed_sup'])
								$res_hotel[$id_hotel][$id_room]['fixed_sup'] = $fixed_sup;
							
							$duty_free = $price;
							$duty_free_sup = $fixed_sup;
							if($result_incl_tax->execute() !== false && $db->last_row_count() > 0){
								$incl_tax = $result_incl_tax->fetch();
								$duty_free = $price/($incl_tax['value']/100+1);
								$duty_free_sup = $res_hotel[$id_hotel][$id_room]['fixed_sup']/($incl_tax['value']/100+1);
							}
							
							if(!isset($res_hotel[$id_hotel][$id_room]['duty_free'])) $res_hotel[$id_hotel][$id_room]['duty_free'] = 0;
							$res_hotel[$id_hotel][$id_room]['duty_free'] += $duty_free;
							$res_hotel[$id_hotel][$id_room]['duty_free_sup'] = $duty_free_sup;
							
							if($result_tax->execute() !== false){
								foreach($result_tax as $tax){
									if(!isset($res_hotel[$id_hotel][$id_room]['taxes'][$tax['id']]['amount'])) $res_hotel[$id_hotel][$id_room]['taxes'][$tax['id']]['amount'] = 0;
									$res_hotel[$id_hotel][$id_room]['taxes']['tax_id'] = $tax['id'];
									$res_hotel[$id_hotel][$id_room]['taxes'][$tax['id']]['amount'] += $duty_free*($tax['value']/100);
									$res_hotel[$id_hotel][$id_room]['taxes'][$tax['id']]['fixed_sup'] = $duty_free_sup*($tax['value']/100);
								}
							}
							
							$res_hotel[$id_hotel][$id_room]['min_stay'] = ((isset($res_hotel[$id_hotel][$id_room]['min_stay']) && $min_stay > $res_hotel[$id_hotel][$id_room]['min_stay']) || !isset($res_hotel[$id_hotel][$id_room]['min_stay'])) ? $min_stay : 0;
							if($num_nights < $res_hotel[$id_hotel][$id_room]['min_stay']){
								$res_hotel[$id_hotel][$id_room]['error'] = true;
								$res_hotel[$id_hotel][$id_room]['notice'] = $texts['MIN_NIGHTS'].' : '.$res_hotel[$id_hotel][$id_room]['min_stay'];
							}
						}
					}
                }
            }else
                if($error) $res_hotel[$id_hotel][$id_room]['error'] = true;
        }
        
        foreach($res_hotel as $id_hotel => $hotel){
            foreach($hotel as $id_room => $result){
                if(!isset($result['amount']) || $result['amount'] == 0 || $result['total_nights'] != $num_nights) $res_hotel[$id_hotel][$id_room]['error'] = true;
                elseif(isset($res_hotel[$id_hotel][$id_room]['error'])) unset($res_hotel[$id_hotel][$id_room]['error']);
                
                if(empty($res_hotel[$id_hotel])) unset($res_hotel[$id_hotel]);
                if(isset($res_hotel[$id_hotel][$id_room]) && !isset($res_hotel[$id_hotel][$id_room]['error'])){
                    if(!in_array($id_hotel, $hotel_ids)) $hotel_ids[] = $id_hotel;
                    $room_ids[] = $id_room;
                }
            }
        }
    }
    
    return $res_hotel;
}

function getBookingSummary($room_id = null, $index = null, $amount = 0, $available = true)
{
    global $texts;

    $html = '';
    $total = 0;
    $num_rooms = 0;
    $num_adults = 0;
    $num_children = 0;
    if(isset($_REQUEST['amount']) && is_array($_REQUEST['amount'])){
        foreach($_REQUEST['amount'] as $id_room => $values){
           
            foreach($values as $i => $value){
                if(isset($_REQUEST['num_adults'][$id_room][$i]) && isset($_REQUEST['num_children'][$id_room][$i])){
                    $adults = $_REQUEST['num_adults'][$id_room][$i];
                    $children = $_REQUEST['num_children'][$id_room][$i];
                    if(($adults+$children) > 0){
                        $num_adults += $adults;
                        $num_children += $children;
                        $num_rooms++;
                        if($id_room != $room_id || $i != $index) $total += $value;

                         //$html .= '<div class="room-details-container"><div class="fc-room-cards-con"><div class="room-details-heading" title="Taj Club Room City View">Taj Club Room City View</div><div class="room-details-rate"><span class="fc-each-room-rate">13,000</span> | 1 night</div><button class="btn-only-focus icon-delete-icon selection-delete" aria-label="delete icon"></button></div></div>';
                    
                    }
                }
            }
        }
    }
 if(!is_null($room_id)){
        $total += $amount;
        if(isset($_REQUEST['num_adults'][$room_id][$index]) && isset($_REQUEST['num_children'][$room_id][$index])){
            $adults = $_REQUEST['num_adults'][$room_id][$index];
            $children = $_REQUEST['num_children'][$room_id][$index];
            if(($adults+$children) > 0 && $amount > 0){
                $num_adults += $adults;
                $num_children += $children;
                $num_rooms++;
                  //$html .= '<div class="room-details-container"><div class="fc-room-cards-con"><div class="room-details-heading" title="Taj Club Room City View">Taj Club Room City View</div><div class="room-details-rate"><span class="fc-each-room-rate">13,000</span> | 1 night</div><button class="btn-only-focus icon-delete-icon selection-delete" aria-label="delete icon"></button></div></div>';
                    
            }
        }
    }
    $persons = $num_adults+$num_children;
    
    if($total > 0){
        
        $hotel_id = isset($_REQUEST['id_hotel']) ? $_REQUEST['id_hotel'] : 0;
        
        
        $html .= '
        <input type="hidden" name="adults" value="'.$num_adults.'">
        <input type="hidden" name="children" value="'.$num_children.'">
        <button name="book" id="btn-book_'.$hotel_id.'" class="btn btn-success total_button btn-lg btn-block mt5"><i class="fas fa-fw fa-hand-point-right"></i> '.$texts['BOOK'].'</button>';
    }elseif(ENABLE_BOOKING_REQUESTS == 1 && (isset($_REQUEST['unavailable_rooms']) || !$available)){
        $html .= '
        <input type="hidden" name="adults" value="'.$_SESSION['num_adults'].'">
        <input type="hidden" name="children" value="'.$_SESSION['num_children'].'">
        <button name="request" class="btn btn-default total_button btn-lg btn-block mt5"><i class="fas fa-fw fa-comment"></i> '.$texts['MAKE_A_REQUEST'].'</small></button>';
    }
    
    return $html;
}
