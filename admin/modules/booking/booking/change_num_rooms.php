<?php
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
require_once('functions.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '', 'extraHtml' => '');

$response['extraHtml'] = getBookingSummary();

if(isset($db) && $db !== false){
    
    if(isset($_POST['room']) && is_numeric($_POST['room'])){
        
        $room_id = $_POST['room'];
        
        if(isset($_POST['num_rooms'][$room_id])){
                    
            $result_room = $db->query('SELECT * FROM pm_room WHERE id = '.$room_id.' AND checked = 1 AND lang = '.LANG_ID);
            if($result_room !== false && $db->last_row_count() > 0){
                $row = $result_room->fetch();
                $max_adults = $row['max_adults'];
                $max_children = $row['max_children'];
                $hotel_id = $row['id_hotel'];
                $num_rooms = $_POST['num_rooms'][$room_id];
                $num_adults = intval($_POST['num_adults']);
                $num_children = intval($_POST['num_children']);
                for($i = 0; $i < $num_rooms; $i++){
                    $response['html'] .= '
                    <div class="mb5 mt5 bg-success">
                        <div class="col-md-2 pt10 text-center has_room"><b>'.$texts['ROOM'].' #'.($i+1).'</b></div>
                        <div class="col-md-3 col-lg-3 pt5 pb5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-addon"><i class="fas fa-fw fa-male"></i> '.$texts['ADULTS'].'</div>
                                <select  name="num_adults['.$room_id.']['.$i.']" class="form-control adlt_count sendAjaxForm selectpicker" data-extratarget="#booking-amount_'.$hotel_id.'" data-action="change_num_people.php?index='.$i.'&id_room='.$room_id.'&id_hotel='.$hotel_id.'" data-target="#room-result-'.$room_id.'-'.$i.'">';
                                    for($j = 0; $j <= $max_adults; $j++){
                                         $select = ($_POST['num_adults'] == $j) ? ' selected="selected"' : '';
                                         $response['html'] .= '<option value="'.$j.'" >'.$j.'</option>';
                                    }
                                    $response['html'] .= '
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 pt5 pb5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-addon"><i class="fas fa-fw fa-male"></i> '.$texts['CHILDREN'].'</div>
                                    <select name="num_children['.$room_id.']['.$i.']" class="form-control kids_select sendAjaxForm selectpicker" data-extratarget="#booking-amount_'.$hotel_id.'" data-action="change_num_people.php?index='.$i.'&id_room='.$room_id.'&id_hotel='.$hotel_id.'" data-target="#room-result-'.$room_id.'-'.$i.'">
                                        <option value="0">-</option>';
                                        for($j = 0; $j <= $max_children; $j++){
                                             $select = ($_POST['num_children'] == $j) ? ' selected="selected"' : '';
                                             $response['html'] .= '<option value="'.$j.'"   >'.$j.'</option>';
                                        }
                                        $response['html'] .= '
                                    </select>
                            </div>
                            <div id="children-options-'.$room_id.'-'.$i.'"></div>
                        </div>
                        <div class="col-md-3 col-lg-4 pt5 pb5 has_price" id="room-result-'.$room_id.'-'.$i.'"></div>
                        <di class="clearfix"></di>
                    </div>';
                }
            }
        }
    }
}
echo json_encode($response);
