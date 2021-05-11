<?php
debug_backtrace() || die('Direct access not permitted');
$result_adult = $db->prepare('SELECT MAX(`max_adults`) as max_adults_qty FROM pm_room');
$result_children = $db->prepare('SELECT MAX(`max_children`) as max_child_qty FROM pm_room');
$result_adult->execute();
$result_children->execute();
$max_adults_search = $result_adult->fetch()['max_adults_qty'];
$max_children_search = $result_children->fetch()['max_child_qty'];
if (!isset($_SESSION['destination_id'])) $_SESSION['destination_id'] = 0;
if (!isset($destination_name)) $destination_name = '';
if (!isset($_SESSION['num_adults']))
    $_SESSION['num_adults'] = (isset($_SESSION['book']['adults'])) ? $_SESSION['book']['adults'] : 1;
if (!isset($_SESSION['num_children']))
    $_SESSION['num_children'] = (isset($_SESSION['book']['children'])) ? $_SESSION['book']['children'] : 0;
if (!isset($_SESSION['num_room']))
    $_SESSION['num_room'] = (isset($_SESSION['book']['num_room'])) ? $_SESSION['book']['num_room'] : 1;
$from_date = (isset($_SESSION['from_date'])) ? $_SESSION['from_date'] : date('d/m/Y');
$to_date = (isset($_SESSION['to_date'])) ? $_SESSION['to_date'] : date('d/m/Y', strtotime(' +1 day'));
$_SESSION['from_date'] = $from_date;
$_SESSION['to_date'] = $to_date;
if (isset($_REQUEST['ab']) && is_array($_REQUEST['ab'])) $_SESSION['ab'] = $_REQUEST['ab'];
elseif (isset($_SESSION['book']['ab'])) $_SESSION['ab'] = $_SESSION['book']['ab'];
elseif (!isset($_SESSION['ab'])) {
    $_SESSION['ab']['adlts'][] = 1;
    $_SESSION['ab']['kids'][] = 0;
}
$adultsOpthtml = "";
for ($i = 1; $i <= $max_adults_search; $i++) {
    if ($i == 1) {
        $adultsOpthtml .= '<option value="' . $i . '">' . $i . ' Adult </option>';
    } else {
        $adultsOpthtml .= '<option value="' . $i . '">' . $i . ' Adults </option>';
    }
}
$KidsOpthtml = "";
$KidsOpthtml .= '<option value="0">Kid</option>';
for ($i = 1; $i <= $max_children_search; $i++) {
    if ($i == 1) {
        $KidsOpthtml .= '<option value="' . $i . '">' . $i . ' Kid </option>';
    } else {
        $KidsOpthtml .= '<option value="' . $i . '">' . $i . ' Kids </option>';
    }
}
$markup = '<div class="room-rows">';
$markup .= '<input type="hidden" id="norm" name="ab[norm][]" value="1" />';
$markup .= '<div class="room-cell"><span class="roomCount displayBlock">Room 1</span></div>';
$markup .= '<div class="room-cell"><span class="guestminus"><select name="ab[adlts][]" class="adlt" onchange="update_rmaudkik();">' . $adultsOpthtml . '</select></span> ';
$markup .= '<span class="guestplus"><select name="ab[kids][]" class="kid" onchange="update_rmaudkik();">' . $KidsOpthtml . '</select></span>';
$markup .= '<span class="remove" onclick="removeRow(this)">Remove</span></div>';
$markup .= '</div>';
?>
<script type="text/javascript">
    var markup = '<?php echo $markup; ?>';
</script>
<div id="destination_msg"></div>
<form id="bookingsearch" name="bookingsearch" action="<?php echo DOCBASE . $sys_pages['booking']['alias']; ?>" method="post" class="booking-search">
    <?php
    if (isset($hotel_id)) { ?>
        <input type="hidden" id="src_hotel_id" name="hotel_id" value="0">
    <?php } ?>
    <div class="page_search">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="input-wrapper form-inline" id="input_destination">
                    <!-- <i class="fas fa-fw fa-map-marker"></i>/--><i class="fa fa-bed"></i>
                    <div class="input-group">
                        <input type="text" name="destination_name" id="search_str" class="form-control" value="<?php echo $destination_name; ?>" placeholder="<?php echo 'Search Hotel / Loation'; //echo $texts['DESTINATION'] . ' / Location'; 
                                                                                                                                                                ?>" autocomplete="off">
                        <input type="hidden" name="destination_id" id="destination_id" value="0">
                    </div>
                </div>
                <ul class="suggest_list"></ul>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="input-wrapper datepicker-wrapper form-inline">
                    <i class="fas fa-fw fa-calendar hidden-xs"></i>
                    <div class="input-group from-date">
                        <input type="text" class="form-control text-right" id="from_picker" name="from_date" value="<?php echo $from_date; ?>" placeholder="<?php echo $texts['CHECK_IN']; ?>" autocomplete="off">
                    </div>
                    <i class="fas fa-fw fa-long-arrow-alt-right"></i>
                    <div class="input-group to-date">
                        <input type="text" class="form-control" id="to_picker" name="to_date" value="<?php echo $to_date; ?>" placeholder="<?php echo $texts['CHECK_OUT']; ?>" autocomplete="off">
                    </div>
                </div>
                <div class="field-notice" rel="dates"></div>
            </div>
            <div class="col-md-4 col-sm-3 col-xs-12">
                <input type="hidden" id="num_adults" name="num_adults" value="1" />
                <input type="hidden" id="num_children" name="num_children" value="0" />
                <?php if (isset($_SESSION['num_room'])) { ?>
                    <input type="hidden" id="num_rooms" name="num_room" value="<?php echo $_SESSION['num_room']; ?>" />
                <?php } else { ?>
                    <input type="hidden" id="num_rooms" name="num_room" value="1" />
                <?php } ?>
                <input type="hidden" id="num_guests" name="noguests" value="1" />
                <div class="form-group">
                    <div class="input-room-row" id="input_room_row">
                        <?php
                        $rm = 0;
                        $gest = 0;
                        if (isset($_SESSION['ab']['norm'])) {
                            foreach ($_SESSION['ab']['norm'] as $ka => $abk) {
                                $rm = $rm + $_SESSION['ab']['norm'][$ka];
                                $gest = $gest + (($_SESSION['ab']['adlts'][$ka] > 0 ? $_SESSION['ab']['adlts'][$ka] : 0) + ($_SESSION['ab']['kids'][$ka] > 0 ? $_SESSION['ab']['kids'][$ka] : 0));
                            }
                        } else {
                            $rm = 1;
                            $gest = 1;
                        } ?>
                        <span id="sRooms"><?php echo $rm . '' . ($rm > 1 ? ' Rooms' : ' Room'); ?></span>, <span id="sGuests"><?php echo $gest . '' . ($gest > 1 ? ' Guests' : ' Guest'); ?></span> <span id="caret" class="caret"></span>
                    </div>
                    <div class="room-container" id="room_container" style="display:none">
                        <div id="rooms_rows">
                            <?php if (isset($_SESSION['ab']['norm'])) {
                                foreach ($_SESSION['ab']['norm'] as $k => $ab) {
                                    $a = $k; ?>
                                    <div class="room-rows">
                                        <input type="hidden" id="norm" name="ab[norm][]" value="<?php echo $_SESSION['ab']['norm'][$k]; ?>" />
                                        <div class="room-cell"><span class="roomCount displayBlock">Room <?php echo ++$a; ?></span></div>
                                        <div class="room-cell">
                                            <span class="guestminus">
                                                <select name="ab[adlts][]" class="adlts" onchange="update_rmaudkik();">
                                                    <?php for ($i = 1; $i <= $max_adults_search; $i++) {
                                                        if ($i == 1) {
                                                            echo '<option value="' . $i . '" ' . ($_SESSION['ab']['adlts'][$k] == $i ? 'selected="selected"' : '') . ' >' . $i . ' Adult </option>';
                                                        } else {
                                                            echo '<option value="' . $i . '" ' . ($_SESSION['ab']['adlts'][$k] == $i ? 'selected="selected"' : '') . '>' . $i . ' Adults </option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </span>
                                            <span class="guestplus">
                                                <select name="ab[kids][]" class="kids" onchange="update_rmaudkik();">
                                                    <?php
                                                    echo '<option value="0">Kid</option>';
                                                    for ($i = 1; $i <= $max_children_search; $i++) {
                                                        if ($i == 1) {
                                                            echo '<option value="' . $i . '" ' . ($_SESSION['ab']['kids'][$k] == $i ? 'selected="selected"' : '') . ' >' . $i . ' Kid </option>';
                                                        } else {
                                                            echo '<option value="' . $i . '" ' . ($_SESSION['ab']['kids'][$k] == $i ? 'selected="selected"' : '') . ' >' . $i . ' Kids </option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </span>
                                            <?php echo ($a == 1 ? '<span class="kidage">(2-7 Years)</span>' : '<span class="remove" onclick="removeRow(this)">Remove</span>'); ?>
                                        </div>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="room-rows">
                                    <input type="hidden" id="norm" name="ab[norm][]" value="1" />
                                    <div class="room-cell"><span class="displayBlock">Room 1</span></div>
                                    <div class="room-cell">
                                        <span class="guestminus">
                                            <select name="ab[adlts][]" class="adlts" onchange="update_rmaudkik();">
                                                <?php echo $adultsOpthtml; ?>
                                            </select>
                                        </span>
                                        <span class="guestplus">
                                            <select name="ab[kids][]" class="kids" onchange="update_rmaudkik();">
                                                <?php echo $KidsOpthtml; ?>
                                            </select>
                                        </span>
                                        <span class="kidage">(2-7 Years)</span>
                                        <!--  <span class="remove_non"></span> -->
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="addRoomAlert"></div>
                        <div class="room-action"><a href="javascript:void(0);" class="add-row">ADD ROOM</a><a href="javascript:void(0);" class="confirm">CONFIRM</a></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="class_range" value="0-10">
            <input type="hidden" name="price_range" value="0-999999">
            <div class="col-md-1 col-sm-1 col-xs-12">
                <div class="form-group">
                    <button class="btn btn-block btn-primary" type="button" name="check_availabilities" id="check_availabilities">GO</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($page['page_model'] == 'book') { ?>
        <div class="row mb5 mt10">
            <?php
            $result_rate = $db->query('SELECT MAX(price) as max_price FROM pm_rate');
            if ($result_rate !== false && $db->last_row_count() > 0) {
                $row = $result_rate->fetch();
                $max_price = $row['max_price'] * ($_SESSION['num_children'] + $_SESSION['num_adults']);
                if ($max_price > 0) {
                    if (!isset($price_min) || is_null($price_min)) $price_min = 0;
                    if (!isset($price_max) || is_null($price_max)) $price_max = $max_price; ?>
                    <div class="col-sm-6">
                        <label class="col-sm-3 control-label" for="hotel_class"><?php echo $texts['YOUR_BUDGET']; ?></label>
                        <div class="col-sm-9">
                            <div class="nouislider-wrapper">
                                <div class="nouislider" data-min="0" data-max="<?php echo number_format(ceil($max_price) * CURRENCY_RATE, 0, '.', ''); ?>" data-start="<?php echo '[' . number_format(floor($price_min) * CURRENCY_RATE, 0, '.', '') . ',' . number_format(ceil($price_max) * CURRENCY_RATE, 0, '.', '') . ']'; ?>" data-step="10" data-direction="<?php echo RTL_DIR; ?>" data-input="price_range"></div>
                                <?php echo $texts['PRICE'] . ' / ' . $texts['NIGHT']; ?> : <?php echo CURRENCY_SIGN; ?> <input type="text" name="price_range" class="slider-target" id="price_range" value="" readonly="readonly" size="15">
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            if (!isset($class_min) || is_null($class_min)) $class_min = 0;
            if (!isset($class_max) || is_null($class_max)) $class_max = 5; ?>
            <div class="col-sm-6">
                <label class="col-sm-3 control-label" for="hotel_class"><?php echo $texts['HOTEL_CLASS']; ?></label>
                <div class="col-sm-9">
                    <div class="nouislider-wrapper">
                        <div class="nouislider" data-min="0" data-max="5" data-start="<?php echo '[' . $class_min . ',' . $class_max . ']'; ?>" data-step="1" data-direction="<?php echo RTL_DIR; ?>" data-input="class_range"></div>
                        <?php echo $texts['STARS']; ?> : <input type="text" name="class_range" class="slider-target" id="class_range" value="" readonly="readonly" size="5">
                    </div>
                </div>
            </div>
        </div>
    <?php
    } ?>
</form>