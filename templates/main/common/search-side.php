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
$from_date = (isset($_SESSION['from_date'])) ? $_SESSION['from_date'] : '';
$to_date = (isset($_SESSION['to_date'])) ? $_SESSION['to_date'] : '';
/****** Searched destinatation *****/
if (isset($_REQUEST['destination_id']) && is_numeric($_REQUEST['destination_id'])) {
    $_SESSION['destination_id'] = $_REQUEST['destination_id'];
    $destination_name = db_getFieldValue($db, 'pm_destination', 'title', $_SESSION['destination_id']);
} elseif (isset($_REQUEST['destination_id']) && is_array($_REQUEST['destination_id'])) {
    $_SESSION['destination_id'] = $_REQUEST['destination_id'];
    $destination_name = db_getFieldValue($db, 'pm_destination', 'title', $_SESSION['destination_id']);
} elseif (!isset($_SESSION['destination_id'])) {
    $_SESSION['destination_id'] = 0;
    $destination_name = '';
}
/****** Searched accommodation *****/
if (isset($_REQUEST['accommodation_id']) && is_numeric($_REQUEST['accommodation_id'])) {
    $_SESSION['accommodation_id'] = $_REQUEST['accommodation_id'];
    //$accommodation_name = db_getFieldValue($db, 'pm_destination', 'name', $_SESSION['accommodation_id']);
    $accommodation_name = db_getFieldValue($db, 'pm_accommodation', 'name', $_SESSION['accommodation_id']);
} elseif (isset($_REQUEST['accommodation_id']) && is_array($_REQUEST['accommodation_id'])) {
    $_SESSION['accommodation_id'] = $_REQUEST['accommodation_id'];
    $accommodation_name = db_getFieldValue($db, 'pm_accommodation', 'name', $_SESSION['accommodation_id']);
} elseif (!isset($_SESSION['accommodation_id'])) {
    $_SESSION['accommodation_id'] = 0;
    $accommodation_name = '';
}
?>
<div class="side_search">
    <form id="form_search" action="<?php echo DOCBASE . $sys_pages['booking']['alias']; ?>" method="get" class="booking-search">
        <?php
        if (isset($hotel_id)) { ?>
            <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
        <?php
        } ?>
        <input type="hidden" name="destination_id" id="destination_id" value="<?=($_SESSION['destination_id']) ? $_SESSION['destination_id'] : 1?>">
        <input type="hidden" name="accommodation_id" id="accommodation_id" value="<?=($_SESSION['accommodation_id']) ? $_SESSION['accommodation_id'] : 0?>">
        <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
        <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
        <input type="hidden" name="num_adults" value="<?php echo $_SESSION['num_adults']; ?>">
        <input type="hidden" name="num_children" value="<?php echo $_SESSION['num_children']; ?>">
        <input type="hidden" name="class_range" value="0-10">
        <input type="hidden" name="price_range" value="0-999999">
        <?php
        if ($page['page_model'] == 'booking') { ?>
            <div class="row mb5 mt10">
                <?php if (isset($_SESSION['price_range'])) {
                    $price_range = explode('-', $_SESSION['price_range']);
                    $pmin = $price_range[0];
                    $pmax = $price_range[1];
                } else {
                    $pmin = '';
                    $pmax = '';
                }
                if (isset($_SESSION['class_range'])) {
                    $class_range = explode('-', $_SESSION['class_range']);
                    if (count($class_range) == 2) {
                        $class_min = number_format($class_range[0], 2, '.', '');
                        $class_max = number_format($class_range[1], 2, '.', '');
                    }
                } else {
                    $class_min = '';
                    $class_max = '';
                }
                $result_rate = $db->query('SELECT MAX(price) as max_price FROM pm_rate');
                if ($result_rate !== false && $db->last_row_count() > 0) {
                    $row = $result_rate->fetch();
                    $max_price = $row['max_price'] * (($_SESSION['num_adults'] == 0 ? 1 : $_SESSION['num_adults']) + ($_SESSION['num_children'] == 0 ? 0 : $_SESSION['num_children']));
                    if ($max_price > 0) {
                        if (!isset($price_min) || is_null($price_min)) $price_min = 0;
                        if (!isset($price_max) || is_null($price_max)) $price_max = $max_price; ?>
                        <div class="boxed" ">
                            <div class=" form-group gp-aside">
                                <label class="control-label" for="hotel_class"><?php echo $texts['YOUR_BUDGET']; ?></label>
                                <div class="col-sm-12">
                                    <!--<div class="nouislider-wrapper">
                                        <div class="nouislider" data-min="0" data-max="<?php echo number_format(ceil($max_price) * CURRENCY_RATE, 0, '.', ''); ?>" data-start="<?php echo '[' . number_format(floor($price_min) * CURRENCY_RATE, 0, '.', '') . ',' . number_format(ceil($price_max) * CURRENCY_RATE, 0, '.', '') . ']'; ?>" data-step="10" data-direction="<?php echo RTL_DIR; ?>" data-input="price_range"></div>
                                    <span> <?php echo $texts['PRICE'] . ' / ' . $texts['NIGHT']; ?> : <?php echo CURRENCY_SIGN; ?></span> <input type="text" name="price_range" class="slider-target" id="price_range" value="" readonly="readonly" size="15">
                                    </div> -->
                                    <?php
                                    $rang = array('0' => '1000', '1001' => '2000', '2001' => '4000', '4001' => '9999');
                                    foreach ($rang as $price => $mprice) {
                                        $selected = (isset($pmax) && $pmax == $mprice) ? ' checked="checked"' : ''; ?>
                                        <span class="checkbox"><input type="checkbox" class="check_price" name="price_range" value="<?php echo $price; ?>-<?php echo $mprice; ?>" <?php echo $selected ?>><?php echo formatPrice($price * CURRENCY_RATE); ?>-<?php echo formatPrice($mprice * CURRENCY_RATE); ?></span>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                if (!isset($class_min) || is_null($class_min)) $class_min = 0;
                if (!isset($class_max) || is_null($class_max)) $class_max = 5; ?>
        <div class="boxed">
            <div class="form-group gp-aside">
                <label class="control-label" for="hotel_class"><?php echo $texts['HOTEL_CLASS']; ?></label>
                <div class="col-sm-12">
                    <!-- <div class="nouislider-wrapper">
                                <div class="nouislider" data-min="0" data-max="5" data-start="<?php echo '[' . $class_min . ',' . $class_max . ']'; ?>" data-step="1" data-direction="<?php echo RTL_DIR; ?>" data-input="class_range"></div>
                                <span><?php echo $texts['STARS']; ?> :</span> <input type="text" name="class_range" class="slider-target" id="class_range" value="" readonly="readonly" size="5">
                            </div> -->
                    <?php
                    for ($str = 1; $str <= 5; $str++) {
                        $selected = (isset($class_max) && $class_max == $str) ? ' checked="checked"' : '';
                    ?>
                        <span class="checkbox"><input type="checkbox" class="check_star" name="class_range" value="<?php echo $str; ?>-<?php echo $str; ?>" <?php echo $selected ?>><input type="hidden" class="rating" value="<?php echo $str; ?>" data-rtl="<?php echo (RTL_DIR) ? true : false; ?>" data-size="xs" readonly="true" data-show-clear="false" data-show-caption="false"></span>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
        <?php
            $nb_search_destinations = 0;
            $result_search_destination = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND lang = ' . LANG_ID);
            if ($result_search_destination !== false) {
        ?>
        <div class="boxed">
            <div class="form-group gp-aside">
                <label class="control-label" for="hotel_class"><?php echo $texts['DESTINATION']; ?></label>
                <?php
                $result_search_destination = $result_search_destination->fetchAll(PDO::FETCH_ASSOC);
                if (count($result_search_destination) > 10) { ?>
                    <input type="text" name="destination_name" class="form-control liveSearch" data-wrapper="result-destinations" data-target="destination_id" data-url="<?php echo getFromTemplate('common/search_destinations.php'); ?>" value="<?php echo $destination_name; ?>" placeholder="<?php echo $texts['DESTINATION']; ?>">
                    <input type="hidden" name="destination_id" id="destination_id" value="<?php echo $_SESSION['destination_id']; ?>">
                <?php
                } else { ?>
                    <?php
                    foreach ($result_search_destination as $row) {
                        $selected = (isset($_SESSION['destination_id']) && $_SESSION['destination_id'] == $row['id']) ? ' checked="checked"' : '';
                        echo '<span class="checkbox"><input type="checkbox" class="check_desti" name="destination_id" value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</span>';
                    } ?>
                <?php
                } ?>
            </div>
        </div>
    <?php } ?>
    <?php
    $nb_search_accommodation = 0;
    $result_search_accommodation = $db->query('SELECT * FROM pm_accommodation WHERE checked = 1 AND lang = ' . LANG_ID);
    if ($result_search_accommodation !== false) {
    ?>
        <div class="boxed">
            <div class="form-group gp-aside">
                <label class="control-label" for="hotel_class"><?php echo $texts['ACCOMMODATION']; ?></label>
                <?php
                $result_search_accommodation = $result_search_accommodation->fetchAll(PDO::FETCH_ASSOC);
                if (count($result_search_accommodation) > 20) { ?>
                    <input type="text" name="destination_name" class="form-control liveSearch" data-wrapper="result-destinations" data-target="accommodation_id" data-url="<?php echo getFromTemplate('common/search_destinations.php'); ?>" value="<?php echo $destination_name; ?>" placeholder="<?php echo $texts['DESTINATION']; ?>">
                    <input type="hidden" name="accommodation_id" id="accommodation_id" value="<?php echo $_SESSION['destination_id']; ?>">
                <?php
                } else { ?>
                    <?php
                    foreach ($result_search_accommodation as $row) {
                        $selected = (isset($_SESSION['accommodation_id']) && $_SESSION['accommodation_id'] == $row['id']) ? ' checked="checked"' : '';
                        echo '<span class="checkbox"><input type="checkbox" class="check_accomo" name="accommodation_id" value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</span>';
                    } ?>
                <?php
                } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
        } ?>
<input type="hidden" name="check_availabilities" value="">
</form>
<form id="form_reset" action="<?php echo DOCBASE . $sys_pages['booking']['alias']; ?>" method="get" class="booking-search">
    <input type="hidden" name="destination_id" id="destination_id" value="0">
    <input type="hidden" name="accommodation_id" id="accommodation_id" value="0">
    <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
    <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
    <input type="hidden" name="class_range" value="0-10">
    <input type="hidden" name="price_range" value="0-999999">
    <input type="hidden" name="num_adults" value="1">
    <input type="hidden" name="num_children" value="0">
    <input type="hidden" name="check_availabilities" value="">
</form>
<div class="gp-search-btn">
    <button class="btn btn-block btn-primary" type="button" name="check_availabilities" onclick="booking_search()">GO</button>
    <button class="btn btn-block btn-primary" type="button" name="check_availabilities" onclick="booking_reset()">Reset</button>
</div>
</div>