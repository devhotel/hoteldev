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

$rooms_ids = $_POST['rooms'];
                        $query_service = 'SELECT * FROM pm_service WHERE';
                        if(isset($rooms_ids)) $query_service .= ' rooms REGEXP \'[[:<:]]'.implode('|', $rooms_ids).'[[:>:]]\' AND';
                        $query_service .= ' lang = '.LANG_ID.' AND checked = 1 ORDER BY rank';
                        $result_service = $db->query($query_service);
                        if($result_service !== false && $db->last_row_count() > 0){ ?>
                            <div class="rooms boxed table-responsive">
                             <table class="table table-hover table-striped">
                                <tr><td><?php echo $texts['EXTRA_SERVICES']; ?></td></tr>
                                <?php
                                  foreach($result_service as $i => $row){
                                    $id_service = $row['id'];
                                    $service_title = $row['title'];
                                    $service_descr = $row['descr'];
                                    $service_long_descr = $row['long_descr'];
                                    $service_price = $row['price'];
                                    $service_type = $row['type'];
                                    $service_rooms = explode(',', $row['rooms']);
                                    $mandatory = $row['mandatory'];
                                    $nb_rooms = count(@array_intersect($service_rooms, $rooms_ids));
                                    if($nb_rooms == 0) $nb_rooms = 1;

                                    $service_qty = 1;
                                    if($service_type == 'person') $service_qty = intval($_POST['num_adults'])+intval($_POST['num_children']);
                                    if($service_type == 'adult') $service_qty = intval($_POST['num_adults']);
                                    if($service_type == 'child') $service_qty = intval($_POST['num_children']);
                                    if($service_type == 'person-night' || $service_type == 'qty-person-night') $service_qty = (intval($_POST['num_adults'])+intval(['num_children']))*intval($_POST['nights']);
                                    if($service_type == 'adult-night' || $service_type == 'qty-adult-night') $service_qty = intval($_POST['num_adults'])*intval($_POST['nights']);
                                    if($service_type == 'child-night' || $service_type == 'qty-child-night') $service_qty = intval($_POST['num_children'])*intval($_POST['nights']);
                                    if($service_type == 'qty-night' || $service_type == 'night') $service_qty = intval($_POST['nights']);
                                    if($service_type == 'night') $service_qty = $nb_rooms;
                                    
                                    $service_price *= $service_qty;

                                    $service_selected = @array_key_exists($id_service, $_POST['extra_services']);
                                    
                                    if($mandatory == 1 && !$service_selected) $service_selected = true;

                                    $checked = $service_selected ? ' checked="checked"' : ''; ?>
                                    <tr><td>
                                    <div class="row form-group">
                                        <label class="col-sm-<?php echo (strpos($service_type, 'qty') !== false) ? 7 : 8; ?> col-xs-8">
                                            <input type="checkbox" name="extra_services[]" value="<?php echo $id_service; ?>" class="sendAjaxForm"<?php if($mandatory) echo ' disabled="disabled" data-sendOnload="1"'; ?> data-action="update_booking.php" data-target="#total_booking"<?php echo $checked;?>>
                                            <input type="hidden" name="service_<?php echo $i; ?>" value="<?php echo $id_service; ?>" />
                                            <?php
                                            
                                            if($mandatory){ ?>
                                                <input type="hidden" name="extra_services[]" value="<?php echo $id_service; ?>">
                                                <?php
                                            }
                                            echo $service_title;
                                            if($service_descr != ''){ ?>
                                                <br><small><?php echo $service_descr; ?></small>
                                                <?php
                                            }
                                            if($service_long_descr != ''){ ?>
                                                <br><small><a data-target="#service_<?php echo $id_service; ?>"class="btn-info btn-sm" data-toggle="modal">Read More</a></small>
                                                   <div id="service_<?php echo $id_service; ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                           <div class="modal-content">
                                                               <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                   <h2><?php echo $service_title; ?></h2>
                                                                   <small><?php echo $service_descr; ?></small>
                                                                </div>
                                                                <div class="modal-body">
                                                                 <p><?php echo $service_long_descr; ?></p>
                                                              </div> 
                                                        </div>
                                                    </div>
                                                  </div>
                                                <?php
                                            } ?>
                                        </label>
                                        <?php
                                        if(strpos($service_type, 'qty') !== false){
                                            $qty =1 ?>
                                            <div class="col-sm-3 col-xs-9">
                                                <div class="input-group input-group-sm">
                                                   
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-number" data-field="qty_service_<?php echo $id_service; ?>" data-type="minus" disabled="disabled" type="button">
                                                            <i class="fas fa-fw fa-minus"></i>
                                                        </button>
                                                    </span>
                                                    <input class="form-control input-number sendAjaxForm" type="text" max="20" min="1" value="<?php echo $qty; ?>" name="qty_service_<?php echo $id_service; ?>" data-action="<?php echo getFromTemplate('common/update_booking.php'); ?>" data-target="#total_booking">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-number" data-field="qty_service_<?php echo $id_service; ?>" data-type="plus" type="button">
                                                            <i class="fas fa-fw fa-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php
                                        } ?>
                                        <div class="col-sm-4 col-xs-4 text-right">
                                            <input type="hidden" name="price_service_<?php echo $id_service; ?>" value="<?php echo $service_price; ?>" />
                                            <?php
                                            if(strpos($service_type, 'qty') !== false) echo 'x ';
                                            echo formatPrice($service_price*CURRENCY_RATE); ?>
                                        </div>
                                    </div>
                                    </td></tr>
                                    <?php
                                } ?>
                            </table>
                            </div>
                            <?php
                        }?>
