<?php global $res_room;
if ($article_alias == '') err404();
$result = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND lang = ' . LANG_ID . ' AND alias = ' . $db->quote($article_alias));
if ($result !== false && $db->last_row_count() == 1) {
    $hotel = $result->fetch(PDO::FETCH_ASSOC);
    $destination_name = $hotel['title'] . ', ' . $hotel['city'];
    $hotel_id = $hotel['id'];
    $article_id = $hotel_id;
    $title_tag = $hotel['title'] . ' - ' . $title_tag;
    $page_title = $hotel['title'];
    $page_subtitle = '';
    $page_alias = $pages[$page_id]['alias'] . '/' . text_format($hotel['alias']);
    $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $hotel_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    if ($result_hotel_file !== false && $db->last_row_count() > 0) {
        $row = $result_hotel_file->fetch();
        $file_id = $row['id'];
        $filename = $row['file'];
        if (is_file(SYSBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename))
            $page_img = getUrl(true) . DOCBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
    }
    $field_notice = array();
    $msg_error = '';
    $msg_success = '';
    $room_stock = 1;
    $max_people = 30;
    $search_limit = 8;
    /*********** Num adults ************/
    if (isset($_REQUEST['num_adults']) && is_numeric($_REQUEST['num_adults'])) $_SESSION['num_adults'] = $_REQUEST['num_adults'];
    elseif (isset($_SESSION['book']['adults'])) $_SESSION['num_adults'] = $_SESSION['book']['adults'];
    elseif (!isset($_SESSION['num_adults'])) $_SESSION['num_adults'] = 1;
    /********** Num children ***********/
    if (isset($_REQUEST['num_children']) && is_numeric($_REQUEST['num_children'])) $_SESSION['num_children'] = $_REQUEST['num_children'];
    elseif (isset($_SESSION['book']['children'])) $_SESSION['num_children'] = $_SESSION['book']['children'];
    elseif (!isset($_SESSION['num_children'])) $_SESSION['num_children'] = 0;
    /*********** Num adults ************/
    if (isset($_REQUEST['num_room']) && is_numeric($_REQUEST['num_room'])) $_SESSION['num_room'] = $_REQUEST['num_room'];
    elseif (isset($_SESSION['book']['num_room'])) $_SESSION['num_room'] = $_SESSION['book']['num_room'];
    elseif (!isset($_SESSION['num_room'])) $_SESSION['num_room'] = 1;
    /*********** Num room ************/
    if (isset($_REQUEST['ab']) && is_array($_REQUEST['ab'])) $_SESSION['ab'] = $_REQUEST['ab'];
    elseif (isset($_SESSION['book']['ab'])) $_SESSION['ab'] = $_SESSION['book']['ab'];
    elseif (!isset($_SESSION['ab'])) {
        $_SESSION['ab']['adlts'][] = 1;
        $_SESSION['ab']['kids'][] = 0;
    }
    /****** Check in / out date ********/
    if (isset($_SESSION['from_date'])) $from_time = $_SESSION['from_date'];
    else $from_time = gmtime();
    if (isset($_SESSION['to_date'])) $to_time = $_SESSION['to_date'];
    else $to_time = gmtime() + 86400;
    if (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date'])) $_SESSION['from_date'] = htmlentities($_REQUEST['from_date'], ENT_QUOTES, 'UTF-8');
    elseif (!isset($_SESSION['from_date'])) $_SESSION['from_date'] = gmdate('d/m/Y', $from_time);
    if (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) $_SESSION['to_date'] = htmlentities($_REQUEST['to_date'], ENT_QUOTES, 'UTF-8');
    elseif (!isset($_SESSION['to_date'])) $_SESSION['to_date'] = gmdate('d/m/Y', $to_time);
    $num_people = $_SESSION['num_adults'] + $_SESSION['num_children'];
    if (!is_numeric($_SESSION['num_adults'])) $field_notice['num_adults'] = $texts['REQUIRED_FIELD'];
    if (!is_numeric($_SESSION['num_children'])) $field_notice['num_children'] = $texts['REQUIRED_FIELD'];
    if ($_SESSION['from_date'] == '') $field_notice['dates'] = $texts['REQUIRED_FIELD'];
    else {
        $time = explode('/', $_SESSION['from_date']);
        if (count($time) == 3) $time = gm_strtotime($time[2] . '-' . $time[1] . '-' . $time[0] . ' 00:00:00');
        if (!is_numeric($time)) $field_notice['dates'] = $texts['REQUIRED_FIELD'];
        else $from_time = $time;
    }
    if ($_SESSION['to_date'] == '') $field_notice['dates'] = $texts['REQUIRED_FIELD'];
    else {
        $time = explode('/', $_SESSION['to_date']);
        if (count($time) == 3) $time = gm_strtotime($time[2] . '-' . $time[1] . '-' . $time[0] . ' 00:00:00');
        if (!is_numeric($time)) $field_notice['dates'] = $texts['REQUIRED_FIELD'];
        else $to_time = $time;
    }
    $today = gm_strtotime(gmdate('Y') . '-' . gmdate('n') . '-' . gmdate('j') . ' 00:00:00');
    if ($from_time < $today || $to_time < $today || $to_time <= $from_time) {
        $from_time = $today;
        $to_time = $today + 86400;
        $_SESSION['from_date'] = gmdate('d/m/Y', $from_time);
        $_SESSION['to_date'] = gmdate('d/m/Y', $to_time);
    }
    if (is_numeric($from_time) && is_numeric($to_time)) {
        $num_nights = ($to_time - $from_time) / 86400;
    } else
        $num_nights = 0;
    if (count($field_notice) == 0) {
        if ($num_nights <= 0) $msg_error .= $texts['NO_AVAILABILITY'];
        else {
            require_once(getFromTemplate('common/functions.php', false));
            //$res_hotel = getRoomsResult($from_time, $to_time, $_SESSION['num_adults'], $_SESSION['num_children']);
            $res_hotel = getRoomsResult($from_time, $to_time, max($_SESSION["ab"]["adlts"]), max($_SESSION["ab"]["kids"]));
            //$res_hotel = getRoomsResult($from_time, $to_time, 1, $_SESSION['num_children']);
            if (empty($res_hotel)) $msg_error .= $texts['NO_AVAILABILITY'];
            else $_SESSION['res_hotel'] = $res_hotel;
        }
    }
    $search_offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;
    $id_room = 0;
    $result_room_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_rate WHERE id_room = :id_room');
    $result_room_rate->bindParam(':id_room', $id_room);
    $id_hotel = 0;
    $result_budget_room = $db->prepare('SELECT * FROM pm_room WHERE id_hotel = :id_hotel AND checked = 1 AND lang = ' . LANG_ID);
    $result_budget_room->bindParam(':id_hotel', $hotel_id);
    $result_base_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_room WHERE id_hotel = :id_hotel');
    $result_base_rate->bindParam(':id_hotel', $id_hotel);
    $hotel_min_price = 0;
    $hidden_hotels = array();
    $hidden_rooms = array();
    $room_prices = array();
    $hotel_prices = array();
    $base_hotel_prices = array();
    $result_budget_hotel = $db->query('SELECT * FROM pm_hotel WHERE id=' . $hotel_id . ' AND checked = 1 AND lang = ' . LANG_ID);
    if ($result_budget_hotel !== false) {
        foreach ($result_budget_hotel as $i => $row) {
            $id_hotel = $row['id'];
            $old_hotel_min_price = 0;
            $hotel_max_price = 0;
            $result_budget_room->execute();
            if ($result_budget_room !== false) {
                foreach ($result_budget_room as $row) {
                    //         echo "<pre>";
                    // print_r($row );
                    // die;
                    if ($_SESSION['num_room'] <= $row['stock']) {
                        $id_room = $row['id'];
                        $room_price = $row['price'];
                        $max_people = $row['max_people'];
                        $min_people = $row['min_people'];
                        $max_adults = $row['max_adults'];
                        $max_children = $row['max_children'];
                        //$base_hotel_prices[$id_hotel] = $room_price;
                        $result_room_rate->execute();
                        if ($result_room_rate !== false && $db->last_row_count() > 0) {
                            $row = $result_room_rate->fetch();
                            if ($row['min_price'] > 0) $room_price = $row['min_price'];;
                        }
                        if (!isset($res_hotel[$id_hotel][$id_room]) || isset($res_hotel[$id_hotel][$id_room]['error'])) {
                            $amount = $room_price;
                            $full_price = 0;
                            $type = $texts['NIGHT'];
                            $price_night = $room_price;
                        } else {
                            $amount = $res_hotel[$id_hotel][$id_room]['amount'] + $res_hotel[$id_hotel][$id_room]['fixed_sup'];
                            $full_price = $res_hotel[$id_hotel][$id_room]['full_price'] + $res_hotel[$id_hotel][$id_room]['fixed_sup'];
                            $type = $num_nights . ' ' . $texts['NIGHTS'];
                            //
                            $price_night = $res_hotel[$id_hotel][$id_room]['price_per_night'];
                        }
                        if ((!empty($price_min) && $price_night < $price_min) || (!empty($price_max) && $price_night > $price_max)) $hidden_rooms[] = $id_room;
                        else {
                            //$room_prices[$id_room]['price_night'] = $price_night;
                            $room_prices[$id_room]['price_night'] = $amount;
                            $room_prices[$id_room]['base_price'] = $room_price;
                            $room_prices[$id_room]['amount'] = $amount;
                            $room_prices[$id_room]['full_price'] = $full_price;
                            $room_prices[$id_room]['type'] = $type;
                        }
                        if (empty($hotel_min_price) || $price_night < $hotel_min_price) {
                            $hotel_min_price = $amount;
                            $base_hotel_prices[$id_hotel] = $room_price;
                        }
                        if (empty($hotel_max_price) || $price_night > $hotel_max_price) {
                            $hotel_max_price = $price_night;
                        }
                        if ($old_hotel_min_price == 0) {
                            $old_hotel_min_price = $hotel_min_price;
                        } else {
                            if ($old_hotel_min_price > $hotel_min_price) {
                                $old_hotel_min_price = $hotel_min_price;
                            }
                        }
                    }
                }
            }
            if ((!empty($price_min) && $hotel_max_price < $price_min) || (!empty($price_max) && $hotel_min_price > $price_max)) $hidden_hotels[] = $id_hotel;
            //$hotel_prices[$id_hotel] = $hotel_min_price;
            $hotel_prices[$id_hotel] = $old_hotel_min_price;
        }
    }
    $result_rating = $db->prepare('SELECT AVG(rating) as avg_rating FROM pm_comment WHERE item_type = \'hotel\' AND id_item = :id_hotel AND checked = 1 AND rating > 0 AND rating <= 5');
    $result_rating->bindParam(':id_hotel', $hotel_id);
    $id_facility = 0;
    $result_facility_file = $db->prepare('SELECT * FROM pm_facility_file WHERE id_item = :id_facility AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    $result_facility_file->bindParam(':id_facility', $id_facility);
    $room_facilities = '0';
    $result_room_facilities = $db->prepare('SELECT * FROM pm_facility WHERE lang = ' . LANG_ID . ' AND FIND_IN_SET(id, :room_facilities) ORDER BY rank LIMIT 18');
    $result_room_facilities->bindParam(':room_facilities', $room_facilities);
    $query_room = 'SELECT * FROM pm_room WHERE id_hotel = :id_hotel AND checked = 1 AND lang = ' . LANG_ID;
    if (!empty($hidden_rooms)) $query_room .= ' AND id NOT IN(' . implode(',', $hidden_rooms) . ')';
    $query_room .= ' ORDER BY';
    if (!empty($room_ids)) $query_room .= ' CASE WHEN id IN(' . implode(',', $room_ids) . ') THEN 3 ELSE 4 END,';
    $query_room .= ' price';
    $result_room = $db->prepare($query_room);
    $result_room->bindParam(':id_hotel', $hotel_id);
    $result_room_file = $db->prepare('SELECT * FROM pm_room_file WHERE id_item = :id_room AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
    $result_room_file->bindParam(':id_room', $id_room);
    $query_hotel = 'SELECT * FROM pm_hotel WHERE WHERE id=' . $hotel_id . ' AND checked = 1 AND lang = ' . LANG_ID;
    if (!empty($hidden_hotels)) $query_hotel .= ' AND id NOT IN(' . implode(',', $hidden_hotels) . ')';
    $query_hotel .= ' ORDER BY';
    if ($hotel_id != 0) $query_hotel .= ' CASE WHEN id = ' . $hotel_id . ' THEN 1 ELSE 4 END,';
    if (!empty($hotel_ids)) $query_hotel .= ' CASE WHEN id IN(' . implode(',', $hotel_ids) . ') THEN 3 ELSE 4 END,';
    $query_hotel .= ' rank';
    $num_results = 0;
    $result_hotel = $db->query($query_hotel);
    if ($result_hotel !== false) {
        $num_results = $db->last_row_count();
        $visible_hotels = $result_hotel->fetchAll(PDO::FETCH_COLUMN, 0);
        if (!empty($visible_hotels)) {
            $visible_hotels = array_intersect_key($hotel_prices, array_flip($visible_hotels));
            $subtitle = str_replace('{min_price}', formatPrice(min($visible_hotels) * CURRENCY_RATE), $texts['BEST_RATES_SUBTITLE']);
            if ($article_id > 0) $page_subtitle = $subtitle;
            else $page['subtitle'] = $subtitle;
        }
    }
    $query_hotel .= ' LIMIT ' . $search_limit . ' OFFSET ' . $search_offset;
    $result_hotel = $db->query($query_hotel);
} else err404();
//echo check_URI(DOCBASE.$page_alias);
/* ==============================================
    * CSS AND JAVASCRIPT USED IN THIS MODEL
    * ==============================================
    */
$javascripts[] = DOCBASE . 'js/plugins/sharrre/jquery.sharrre.min.js';
$javascripts[] = DOCBASE . 'js/plugins/jquery.event.calendar/js/jquery.event.calendar.js';
$javascripts[] = DOCBASE . 'js/plugins/jquery.event.calendar/js/languages/jquery.event.calendar.' . LANG_TAG . '.js';
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/jquery.event.calendar/css/jquery.event.calendar.css', 'media' => 'all');
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css', 'media' => 'all');
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js';
$stylesheets[] = array('file' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css', 'media' => 'all');
$javascripts[] = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js';
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/isotope/css/style.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js';
$javascripts[] = DOCBASE . 'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$javascripts[] = DOCBASE . 'js/plugins/lazyloader/lazyloader.js';
$javascripts[] = DOCBASE . 'js/plugins/live-search/jquery.liveSearch.js';
//require(getFromTemplate('common/send_comment.php', false));
require(getFromTemplate('common/header.php', false));
?>
<section id="page">
    <?php include(getFromTemplate('common/page_header.php', false)); ?>
    <div id="content" class="pb30">
        <div id="search-page" class="mb20">
            <div class="container">
                <?php include(getFromTemplate('common/search.php', false)); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="container">
            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
        </div>
        <section class="hotel_details_new">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                        <div class="hotel_details_left">
                            <div id="sync1" class="owl-carousel">
                                <?php
                                $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $hotel_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
                                if ($result_hotel_file !== false) {
                                    foreach ($result_hotel_file as $i => $row) {
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        $realpath = SYSBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                        $thumbpath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                        if (is_file($realpath)) { ?>
                                            <div class="item">
                                                <img src="<?php echo $thumbpath; ?>" alt="<?php echo $label; ?>" />
                                            </div>
                                <?php
                                        }
                                    }
                                } ?>
                            </div>
                            <div id="sync2" class="owl-carousel">
                                <?php
                                $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $hotel_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
                                if ($result_hotel_file !== false) {
                                    foreach ($result_hotel_file as $i => $row) {
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        $realpath = SYSBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                        $thumbpath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                        if (is_file($realpath)) { ?>
                                            <div class="item">
                                                <img src="<?php echo $thumbpath; ?>" alt="<?php echo $label; ?>" />
                                            </div>
                                <?php
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    // echo "ok"; die;
                    // echo "<pre>";
                    // print_r($hotel);
                    // die;
                    ?>
                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                        <div class="hotel_details_right">
                            <div class="right_hotel_info">
                                <h2><?php echo $hotel['title']; ?></h2>
                                <h3><?= $hotel['address'] . ', ' . $hotel['address_1'] . ' ' . $hotel['address_2'] . ', ' . $hotel['city'] . ' - ' . $hotel['zipcode']; ?>, <?php echo $hotel['state']; ?></h3>
                                <h3><?= $hotel['phone'] ?></h3>
                                <?php
                                $acc_query_hotel = 'SELECT title FROM pm_accommodation WHERE id=' . $hotel['id_accommodation'] . ' AND checked = 1 AND lang = ' . LANG_ID;
                                $result_acc = $db->query($acc_query_hotel);
                                $accom = $result_acc->fetch()['title'];
                                ?>
                                <?php if ($accom != "") { ?>
                                    <div class="typebox1"><span><?php echo $accom; ?></span></div>
                                <?php } ?>
                                <?php
                                $result_facility = $db->query('SELECT * FROM pm_facility WHERE lang = ' . LANG_ID . ' AND id IN(' . $hotel['facilities'] . ') ORDER BY id', PDO::FETCH_ASSOC);
                                if ($result_facility !== false && $db->last_row_count() > 0) {
                                    echo '<h4>' . $texts['FEATURES_AMENITIES'] . '</h4>';
                                    echo '<ul class="facility_main">';
                                    foreach ($result_facility as $i => $row) {
                                        $facility_id     = $row['id'];
                                        $facility_name  = $row['name'];
                                        $result_facility_file = $db->query('SELECT * FROM pm_facility_file WHERE id_item = ' . $facility_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1', PDO::FETCH_ASSOC);
                                        if ($result_facility_file !== false && $db->last_row_count() == 1) {
                                            $row = $result_facility_file->fetch();
                                            $file_id     = $row['id'];
                                            $filename     = $row['file'];
                                            $label         = $row['label'];
                                            $realpath    = SYSBASE . 'medias/facility/big/' . $file_id . '/' . $filename;
                                            $thumbpath    = DOCBASE . 'medias/facility/big/' . $file_id . '/' . $filename;
                                            if (is_file($realpath)) { ?>
                                                <li class="facility-icon">
                                                    <img alt="<?php echo $facility_name; ?>" title="<?php echo $facility_name; ?>" src="<?php echo $thumbpath; ?>" class="tips"><?php echo $facility_name; ?>
                                                </li>
                                <?php
                                            }
                                        }
                                    }
                                    echo '</ul>';
                                } ?>
                                <div class="typebox3">
                                    <?php
                                    if (isset($hotel_prices[$id_hotel]) && $hotel_prices[$id_hotel] > 0) { ?>
                                        <?php
                                        @$hotel_regular_price = $base_hotel_prices[$id_hotel];
                                        $defaultMinPriceQ   = $db->query("SELECT MIN(price) as min_price FROM pm_room WHERE checked = 1 AND id_hotel = '" . $id_hotel . "'")->fetch(PDO::FETCH_ASSOC);
                                        $defaultMinPrice = $defaultMinPriceQ['min_price'];
                                        $newMinDiscPriceQ   = $db->query("SELECT MIN(new_disc_price) as new_disc_price FROM pm_room_new_stock_rate WHERE is_blocked = 0 AND id_hotel = '" . $id_hotel . "' AND date = '" . gmdate('Y-m-d H:i:s', $from_time)."'")->fetch(PDO::FETCH_ASSOC);
                                        $newMinPriceQ       = $db->query("SELECT MIN(new_price) as new_price FROM pm_room_new_stock_rate WHERE is_blocked = 0 AND id_hotel = '" . $id_hotel . "' AND date = '" . gmdate('Y-m-d H:i:s', $from_time) ."'")->fetch(PDO::FETCH_ASSOC);
                                        $newMinPrice        = (!empty($newMinPriceQ['new_price'])) ? $newMinPriceQ['new_price'] : $defaultMinPrice;
                                        $newMinDiscPrice    = (!empty($newMinDiscPriceQ['new_disc_price'])) ? $newMinDiscPriceQ['new_disc_price'] : $defaultMinPrice;
                                        //$newMinDiscPrice = ($newMinDiscPrice < $hotel_prices[$id_hotel]) ? $newMinDiscPrice : $hotel_prices[$id_hotel];
                                        $newMinDiscPrice = ($newMinDiscPrice < $defaultMinPrice) ? $newMinDiscPrice : $defaultMinPrice;
                                        if ($newMinDiscPrice < $newMinPrice) { ?>
                                            <span itemprop="priceRange"><del><?php echo formatPrice($newMinPrice * CURRENCY_RATE); ?></del></span>
                                        <?php } ?>                                                    
                                        <?php echo formatPrice($newMinDiscPrice * CURRENCY_RATE); ?>
                                        <span><?php echo $texts['PER'] . " " . $texts['NIGHT']; ?></span>
                                    <?php } ?><span>
                                        <p>Tax excl. price</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="roomtype">
                    <form action="<?php echo DOCBASE . $sys_pages['booking']['alias']; ?>" method="post" class="ajax-form">
                        <input type="hidden" name="from_time" value="<?php echo $from_time; ?>">
                        <input type="hidden" name="to_time" value="<?php echo $to_time; ?>">
                        <input type="hidden" name="nights" value="<?php echo $num_nights; ?>">
                        <input type="hidden" name="id_hotel" value="<?php echo $hotel_id; ?>">
                        <input type="hidden" name="hotel" value="<?php echo $page_title; ?>">
                        <div class="booking-result">
                            <div class="">
                                <div class="">
                                    <?php
                                    $result_room->execute();
                                    $msssg = '';
                                    if (array_key_exists($hotel_id, $res_hotel)) {
                                        if ($result_room !== false) {
                                            if (isset($_SESSION['num_room'])) {
                                                $num_room = $_SESSION['num_room'];
                                            }
                                            foreach ($result_room as $row) {
                                                $newStockQ  = $db->query("SELECT new_stock, is_blocked FROM pm_room_new_stock_rate WHERE id_room = '" . $row['id'] . "' AND date = '" . gmdate('Y-m-d H:i:s', $from_time)."'")->fetch(PDO::FETCH_ASSOC);
                                                $newStock = (!empty($newStockQ['new_stock'])) ? $newStockQ['new_stock'] : $row['stock'];
                                                if(empty($newStockQ['is_blocked']) || $newStockQ['is_blocked'] == 0){
                                                if (array_key_exists($row['id'], $room_prices) && $_SESSION['num_room'] <= $newStock) {
                                                    $id_room = $row['id'];
                                                    $room_title = $row['title'];
                                                    $room_alias = $row['alias'];
                                                    $room_subtitle = $row['title'];
                                                    $room_descr = $row['descr'];
                                                    $room_price = $row['price'];
                                                    $max_adults = $row['max_adults'];
                                                    $max_children = $row['max_children'];
                                                    $max_people = $row['max_people'];
                                                    $min_people = $row['min_people'];
                                                    $room_facilities = $row['facilities'];
                                                    $bed_type = $row['bed_type'];
                                                    $num_of_bed = $row['number_beds'];
                                                    $room_dimention = $row['room_dimention'];
                                                    $views = $row['views'];
                                                    //$room_stock = isset($res_room[$id_room]['room_stock']) ? $res_room[$id_room]['room_stock'] : $row['stock'];
                                                    $room_stock = $newStock;
                                                    $amount = $room_prices[$id_room]['amount'];
                                                    $full_price = $room_prices[$id_room]['full_price'];
                                                    $type = $room_prices[$id_room]['type'];
                                                    $price_night = $room_prices[$id_room]['price_night'];
                                                    $base_price = $room_prices[$id_room]['base_price'];
                                    ?>
                                                    <input type="hidden" name="rooms[]" value="<?php echo $id_room; ?>">
                                                    <input type="hidden" name="room_<?php echo $id_room; ?>" value="<?php echo $room_title; ?>">
                                                    <div class="row room-result">
                                                        <div class="col-lg-3">
                                                            <?php
                                                            $result_room_file->execute();
                                                            if ($result_room_file !== false && $db->last_row_count() > 0) {
                                                            ?>
                                                                <div id="sync_room1" class="sync_room1 owl-carousel">
                                                                    <?php foreach ($result_room_file as $key => $row) {
                                                                        $file_id = $row['id'];
                                                                        $filename = $row['file'];
                                                                        $label = $row['label'];
                                                                        $realpath = SYSBASE . 'medias/room/small/' . $file_id . '/' . $filename;
                                                                        $thumbpath = DOCBASE . 'medias/room/small/' . $file_id . '/' . $filename;
                                                                        $zoompath = DOCBASE . 'medias/room/big/' . $file_id . '/' . $filename;
                                                                        if (is_file($realpath)) {
                                                                            $s = getimagesize($realpath); ?>
                                                                            <div class="item">
                                                                                <div class="img-container lazyload md">
                                                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" style="height:150px">
                                                                                </div>
                                                                            </div>
                                                                    <?php
                                                                        }
                                                                    }  ?>
                                                                </div>
                                                                <div id="sync_room2" class="sync_room2 owl-carousel">
                                                                    <?php foreach ($result_room_file as $key => $row) {
                                                                        $file_id = $row['id'];
                                                                        $filename = $row['file'];
                                                                        $label = $row['label'];
                                                                        $realpath = SYSBASE . 'medias/room/small/' . $file_id . '/' . $filename;
                                                                        $thumbpath = DOCBASE . 'medias/room/small/' . $file_id . '/' . $filename;
                                                                        $zoompath = DOCBASE . 'medias/room/big/' . $file_id . '/' . $filename;
                                                                        if (is_file($realpath)) {
                                                                            $s = getimagesize($realpath); ?>
                                                                            <div class="item">
                                                                                <div class="img-container lazyload md">
                                                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" height="50">
                                                                                </div>
                                                                            </div>
                                                                    <?php
                                                                        }
                                                                    }  ?>
                                                                </div>
                                                            <?php  } ?>
                                                        </div>
                                                        <div class="col-sm-4 col-md-5 col-lg-4">
                                                            <h4><?php echo $room_title; ?></h4>
                                                            <p><?php echo $room_subtitle; ?></p>
                                                            <?php echo strtrunc(strip_tags($room_descr), 100); ?>
                                                            <!-- Trigger the modal with a button -->
                                                            <?php if ($room_descr != "") { ?>
                                                                <a class="more_btn btn-more " data-toggle="modal" data-target="#roomMod<?php echo $id_room; ?>">Read More</a>
                                                            <?php } ?>
                                                            <!-- Modal -->
                                                            <div class="modal fade room_content_modal" id="roomMod<?php echo $id_room; ?>" role="dialog">
                                                                <div class="modal-dialog">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content room_content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title"><?php echo $room_title; ?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <?php echo $room_descr; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix mt10 fac-room">
                                                                <?php
                                                                $result_room_facilities->execute();
                                                                if ($result_room_facilities !== false && $db->last_row_count() > 0) {
                                                                    $i = 0;
                                                                    foreach ($result_room_facilities as $row) {
                                                                        $i++;
                                                                        $id_itel = $row['id'];
                                                                        $facility_name = $row['name'];
                                                                        $result_room_facility_file = $db->query('SELECT * FROM pm_facility_file WHERE id_item = ' . $id_itel . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1', PDO::FETCH_ASSOC);
                                                                        if ($result_room_facility_file !== false && $db->last_row_count() == 1) {
                                                                            $row = $result_room_facility_file->fetch();
                                                                            $file_id     = $row['id'];
                                                                            $filename     = $row['file'];
                                                                            $label         = $row['label'];
                                                                            $realpath    = SYSBASE . 'medias/facility/big/' . $file_id . '/' . $filename;
                                                                            $thumbpath    = DOCBASE . 'medias/facility/big/' . $file_id . '/' . $filename;
                                                                            if (is_file($realpath)) { ?>
                                                                                <span class="facility-icon">
                                                                                    <img alt="<?php echo $facility_name; ?>" title="<?php echo $facility_name; ?>" src="<?php echo $thumbpath; ?>" class="tips">
                                                                                </span>
                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                } ?>
                                                            </div>
                                                            <?php
                                                            if ($bed_type != "") { ?>
                                                                <div class="clearfix mt10">
                                                                    <p><b>Bed Type : </b><?php echo $bed_type; ?></p>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            if ($num_of_bed != "") {
                                                            ?>
                                                                <div class="clearfix mt10">
                                                                    <p><b>Number of Beds : </b><?php echo $num_of_bed; ?></p>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            if ($room_dimention != "") {
                                                            ?>
                                                                <div class="clearfix mt10">
                                                                    <p><b>Room Dimension : </b><?php echo $room_dimention; ?></p>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            if ($views != "") {
                                                            ?>
                                                                <div class="clearfix mt10">
                                                                    <p><b>Room View : </b><?php echo $views; ?></p>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-lg-5 col-md-6 col-sm-7 text-center sep hotel_detail_right">
                                                            <div class="price">
                                                                <?php
                                                                $defaultMinPriceQ   = $db->query("SELECT MIN(price) as min_price FROM pm_room WHERE checked = 1 AND id = '" . $id_room . "'")->fetch(PDO::FETCH_ASSOC);
                                                                $defaultMinPrice = $defaultMinPriceQ['min_price'];
                                                                $newMinDiscPriceQ   = $db->query("SELECT MIN(new_disc_price) as new_disc_price FROM pm_room_new_stock_rate WHERE is_blocked = 0 AND id_room = '" . $id_room . "' AND date = '" . gmdate('Y-m-d H:i:s', $from_time)."'")->fetch(PDO::FETCH_ASSOC);
                                                                $newMinPriceQ       = $db->query("SELECT MIN(new_price) as new_price FROM pm_room_new_stock_rate WHERE is_blocked = 0 AND id_room = '" . $id_room . "' AND date = '" . gmdate('Y-m-d H:i:s', $from_time) ."'")->fetch(PDO::FETCH_ASSOC);
                                                                $newMinPrice        = (!empty($newMinPriceQ['new_price'])) ? $newMinPriceQ['new_price'] : $defaultMinPrice;
                                                                $newMinDiscPrice    = (!empty($newMinDiscPriceQ['new_disc_price'])) ? $newMinDiscPriceQ['new_disc_price'] : $defaultMinPrice;
                                                                $newMinDiscPrice = ($newMinDiscPrice < $defaultMinPrice) ? $newMinDiscPrice : $defaultMinPrice;
                                                                if ($newMinDiscPrice < $newMinPrice) { ?>
                                                                    <br><s class="text-warning"><?php echo formatPrice($newMinPrice * CURRENCY_RATE); ?></s>
                                                                <?php
                                                                } ?>
                                                                <span itemprop="priceRange"><?php echo formatPrice($newMinDiscPrice * CURRENCY_RATE); ?></span>
                                                            </div>
                                                            <div class="mb10 text-muted"><?php echo $texts['PER'] . " " . $texts['NIGHT']; ?></div>
                                                            <!--<?php echo $texts['CAPACITY']; ?> : <i class="fas fa-fw fa-male"></i>x<?php echo $max_people; ?>-->
                                                            <?php if ($room_stock > 0) { ?>
                                                                <div style="display:none;" id="div_rms_<?php echo $id_room; ?>" class="div_rms">
                                                                    <div class="pt10 form-inline">
                                                                        <div class="level"><i class="fas fa-fw fa-tags"></i> <?php echo $texts['NUM_ROOMS']; ?></div>
                                                                        <div class="nrom rooms_count" id="nrom_<?php echo $id_room; ?>">
                                                                            <div class="incrmnt">
                                                                                <span class="plus" data-p="<?php echo $id_room; ?>" id="plus_<?php echo $id_room; ?>">+</span>
                                                                                <input class="nrnvalue" id="nrmv_<?php echo $id_room; ?>" type="text" placeholder="1" value="<?php echo ($_SESSION['num_room'] ? $_SESSION['num_room'] : 0); ?>" />
                                                                                <span class="minus" data-m="<?php echo $id_room; ?>" id="minus_<?php echo $id_room; ?>">-</span>
                                                                            </div>
                                                                            <div style="display:none;">
                                                                                <select id="rms_<?php echo $id_room; ?>" name="num_rooms[<?php echo $id_room; ?>]" class="form-control room_select selectpicker btn-group-sm sendAjaxForm" data-target="#room-options-<?php echo $id_room; ?>" data-extratarget="#booking-amount_<?php echo $hotel_id; ?>" data-action="<?php echo getFromTemplate('common/change_num_rooms.php'); ?>?room=<?php echo $id_room; ?>">
                                                                                    <?php
                                                                                    for ($i = 0; $i <= $room_stock; $i++) { ?>
                                                                                        <?php $selected = '';
                                                                                        if (isset($_SESSION['num_rooms']) && $num_room <= $room_stock  && $num_room == $i) {
                                                                                            $selected = ' selected="selected"';
                                                                                        } ?>
                                                                                        <?php if ($i <= 6) { ?>
                                                                                            <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                                                                        <?php } ?>
                                                                                    <?php
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            } else { ?>
                                                                <div class="mt10 btn btn-danger btn-block" disabled="disabled"><?php echo $texts['NO_AVAILABILITY']; ?></div>
                                                            <?php
                                                            } ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div id="room-options-<?php echo $id_room; ?>" class="room-options"></div>
                                                        <div class="room_select_opt">
                                                            <a href="javascript:void(0);" id="rm_select_<?php echo $id_room; ?>" onclick="select_room('<?php echo $id_room; ?>','<?php echo ($_SESSION['num_room'] ? $_SESSION['num_room'] : 0); ?>');" class="rm_select btn btn-select total_button btn-lg btn-block mt5"> Select </a>
                                                            <a href="javascript:void(0);" id="rm_remove_<?php echo $id_room; ?>" onclick="unselect_room('<?php echo $id_room; ?>');" class="rm_remove btn btn-remove total_button btn-lg btn-block mt5" style="display:none;"> Remove </a>
                                                        </div>
                                                        <!-- <div class="mt10 booking-summary">
                                    <span id="booking-amount_<?php echo $id_room; ?>">
                                        <?php
                                                                    $room_stock = isset($res_hotel[$hotel_id][$id_room]['room_stock']) ? $res_hotel[$hotel_id][$id_room]['room_stock'] : $row['stock'];
                                                                    if ($num_nights <= 0 || (empty($res_hotel[$hotel_id]) && $room_stock > 0) || (!empty($res_hotel[$hotel_id]) && $room_stock <= 0)) {
                                                                        echo '
                                        <input type="hidden" name="adults" value="' . $_SESSION['num_adults'] . '">
                                        <input type="hidden" name="children" value="' . $_SESSION['num_children'] . '">';
                                                                    } ?>
                                </span> -->
                                                                    </div>
                                                </div>
                                                <hr>
                                                    <?php } else {
                                                                    if ($msssg == '') {
                                                                        $msssg = '<div class="mt10 btn btn-danger btn-block" disabled="disabled">' . $texts['NO_AVAILABILITY'] . '</div>';
                                                                        print $msssg;
                                                                    }
                                                                }
                                                            }
                                                            }
                                                        }else{
                                                            print '<div class="mt10 btn btn-danger btn-block" disabled="disabled">'.$texts['NO_AVAILABILITY'].'</div>';
                                                        }
                                                    } else { ?>
                                    <div class="alert alert-danger">
                                        <?php echo $texts['ROOM_NOT_AVAILABLE']; ?>.
                                    </div>
                                <?php  } ?>
                                <div class="mt10 booking-summary">
                                    <span id="booking-amount_<?php echo $hotel_id; ?>">
                                        <?php
                                        $room_stock = 0;
                                        $result_room->execute();
                                        if ($result_room !== false) {
                                            foreach ($result_room as $row) {
                                                $id_room = $row['id'];
                                                $room_stock = isset($res_hotel[$hotel_id][$id_room]['room_stock']) ? $res_hotel[$hotel_id][$id_room]['room_stock'] : $row['stock'];
                                            }
                                        }
                                        if ($num_nights <= 0 || (empty($res_hotel[$hotel_id]) && $room_stock > 0) || (!empty($res_hotel[$hotel_id]) && $room_stock <= 0)) {
                                            echo '<input type="hidden" name="adults" value="' . $_SESSION['num_adults'] . '">
                                                <input type="hidden" name="children" value="' . $_SESSION['num_children'] . '">';
                                        } ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container">
                <div class="hotel_about">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h2><?php echo $hotel['title']; ?></h2>
                        <h3>Address</h3>
                        <p><?php echo $hotel['address'] . ', ' . $hotel['address_1'] . ' ' . $hotel['address_2'] . ', ' . $hotel['city'] . ' - ' . $hotel['zipcode']; ?></p>
                        <script type="text/javascript">
                            var locations = [
                                ['<?php echo $hotel['title']; ?>', '<?php echo $hotel['address'] . ', ' . $hotel['address_1'] . ' ' . $hotel['address_2'] . ', ' . $hotel['city'] . ' - ' . $hotel['zipcode'] . '<br/>' . $hotel['phone']; ?>', '<?php echo $hotel['lat']; ?>', '<?php echo $hotel['lng']; ?>']
                            ];
                        </script>
                        <div id="mapWrapper" class="mb10" data-marker="<?php echo getFromTemplate('images/marker.png'); ?>" data-api_key="<?php echo GMAPS_API_KEY; ?>"></div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <?php echo $hotel['descr']; ?>
                    </div>
                    <div class="hotel_review_panel">
                        <h2>Hotel Policies</h2>
                        <?php if ($hotel['book_policy'] != "") { ?>
                            <h3>Booking Policy</h3>
                            <?php echo $hotel['book_policy']; ?>
                        <?php } ?>
                        <?php if ($hotel['general_policies'] != "") { ?>
                            <h3>Genarel Policies</h3>
                            <?php echo $hotel['general_policies']; ?>
                        <?php } ?>
                        <?php if ($hotel['cancel_policy'] != "") { ?>
                            <h3>Cancellation Policy</h3>
                            <?php echo $hotel['cancel_policy']; ?>
                        <?php } ?>
                    </div>
                    <div class="hotel_review_panel">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-5">
                                <div class="right_hotel_review">
                                    <?php
                                    $ratings = $db->query("SELECT * FROM `pm_comment` WHERE `item_type` = 'hotel' AND `id_item` = " . $hotel_id . " AND `checked` = 1")->fetchAll();
                                    if (!empty($ratings)) {
                                        get_hotel_rating($ratings);
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7 col-lg-7">
                                <?php
                                $nb_comments = 0;
                                $item_type = 'hotel';
                                $item_id = $hotel_id;
                                $allow_comment = ALLOW_COMMENTS;
                                $allow_rating = ALLOW_RATINGS;
                                if ($allow_comment == 1) {
                                    $result_comment = $db->query('SELECT * FROM pm_comment WHERE id_item = ' . $item_id . ' AND item_type = \'' . $item_type . '\' AND checked = 1 ORDER BY add_date DESC');
                                    if ($result_comment !== false) {
                                        $nb_comments = $db->last_row_count();
                                        if ($nb_comments > 0) { ?>
                                            <h2>Rating & Reviews </h2>
                                            <?php foreach ($result_comment as $i => $row) {
                                                if ($i <= 2) { ?>
                                                    <div class="ratingbox">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                <div class="rating_pic"><img src="<?php echo getFromTemplate("images/user.png"); ?>" alt="" /><?php echo $row['name']; ?></div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                                                                <div class="rating_info">
                                                                    <?php if ($allow_rating == 1 && $row['rating'] > 0 && $row['rating'] <= 5) { ?>
                                                                        <input type="hidden" class="rating" value="<?php echo $row['rating']; ?>" data-rtl="<?php echo (RTL_DIR) ? true : false; ?>" data-size="xs" readonly data-show-clear="false" data-show-caption="false">
                                                                    <?php  } ?>
                                                                    <p><?php echo nl2br($row['msg']); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php }
                                            } ?>
                                            <?php if ($nb_comments > 3) { ?>
                                                <a href="<?php echo getFromTemplate("common/more-review-popup.php"); ?>" data-params="id=<?php echo $item_id; ?>" class="ajax-popup-link">More review </a>
                                            <?php } ?>
                                        <?php } ?>
                                <?php  }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js" type="text/javascript"></script>
<script>
    function initMap() {
        var markerArray = [];
        // Instantiate a directions service.
        var directionsService = new google.maps.DirectionsService;
        // Create a map and center it on Manhattan.
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: {
                lat: 40.771,
                lng: -73.974
            }
        });
        // Create a renderer for directions and bind it to the map.
        var directionsDisplay = new google.maps.DirectionsRenderer({
            map: map
        });
        // Instantiate an info window to hold step text.
        var stepDisplay = new google.maps.InfoWindow;
        // Display the route between the initial start and end selections.
        calculateAndDisplayRoute(
            directionsDisplay, directionsService, markerArray, stepDisplay, map);
        // Listen to change events from the start and end lists.
        var onChangeHandler = function() {
            calculateAndDisplayRoute(
                directionsDisplay, directionsService, markerArray, stepDisplay, map);
        };
        document.getElementById('start').addEventListener('keyup', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
    }
    function calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map) {
        // First, remove any existing markers from the map.
        for (var i = 0; i < markerArray.length; i++) {
            markerArray[i].setMap(null);
        }
        // Retrieve the start and end locations and create a DirectionsRequest using
        // WALKING directions.
        directionsService.route({
            origin: document.getElementById('start').value,
            destination: document.getElementById('end').value,
            travelMode: 'WALKING'
        }, function(response, status) {
            // Route the directions and pass the response to a function to create
            // markers for each step.
            if (status === 'OK') {
                document.getElementById('warnings-panel').innerHTML =
                    '<b>' + response.routes[0].warnings + '</b>';
                directionsDisplay.setDirections(response);
                showSteps(response, markerArray, stepDisplay, map);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }
    function showSteps(directionResult, markerArray, stepDisplay, map) {
        // For each step, place a marker, and add the text to the marker's infowindow.
        // Also attach the marker to an array so we can keep track of it and remove it
        // when calculating new routes.
        var myRoute = directionResult.routes[0].legs[0];
        for (var i = 0; i < myRoute.steps.length; i++) {
            var marker = markerArray[i] = markerArray[i] || new google.maps.Marker;
            marker.setMap(map);
            marker.setPosition(myRoute.steps[i].start_location);
            attachInstructionText(
                stepDisplay, marker, myRoute.steps[i].instructions, map);
        }
    }
    function attachInstructionText(stepDisplay, marker, text, map) {
        google.maps.event.addListener(marker, 'click', function() {
            // Open an info window when the marker is clicked on, containing the text
            // of the step.
            stepDisplay.setContent(text);
            stepDisplay.open(map, marker);
        });
    }
    function place_initialize() {
        var input = document.getElementById('start');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            // place variable will have all the information you are looking for.
            console.log(place.geometry);
            console.log(place.geometry);
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('.rating-321').rating({
            min: 0,
            max: 5,
            step: 0.1,
            stars: 5,
            hoverEnabled: false,
            starCaptions: function(val) {
                return val;
            },
        });
        var sync1 = $("#sync1");
        var sync2 = $("#sync2");
        sync1.owlCarousel({
            singleItem: true,
            autoPlay: false,
            slideSpeed: 1000,
            navigation: true,
            pagination: false,
            afterAction: syncPosition,
            responsiveRefreshRate: 200,
        });
        sync2.owlCarousel({
            items: 5,
            itemsDesktop: [1199, 5],
            itemsDesktopSmall: [979, 4],
            itemsTablet: [768, 3],
            itemsMobile: [479, 2],
            pagination: false,
            autoPlay: false,
            responsiveRefreshRate: 100,
            afterInit: function(el) {
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });
        var syncRoom1 = $(".sync_room1");
        var syncRoom2 = $(".sync_room2");
        syncRoom1.owlCarousel({
            singleItem: true,
            autoPlay: false,
            slideSpeed: 1000,
            navigation: true,
            pagination: false,
            afterAction: syncPosition,
            responsiveRefreshRate: 200,
        });
        syncRoom2.owlCarousel({
            items: 5,
            itemsDesktop: [1199, 5],
            itemsDesktopSmall: [979, 4],
            itemsTablet: [768, 3],
            itemsMobile: [479, 2],
            pagination: false,
            autoPlay: false,
            responsiveRefreshRate: 100,
            afterInit: function(el) {
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });
        function syncPosition(el) {
            var current = this.currentItem;
            $("#sync2")
                .find(".owl-item")
                .removeClass("synced")
                .eq(current)
                .addClass("synced")
            if ($("#sync2").data("owlCarousel") !== undefined) {
                center(current)
            }
        }
        $("#sync2").on("click", ".owl-item", function(e) {
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo", number);
        });
        function center(number) {
            var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
            var num = number;
            var found = false;
            for (var i in sync2visible) {
                if (num === sync2visible[i]) {
                    var found = true;
                }
            }
            if (found === false) {
                if (num > sync2visible[sync2visible.length - 1]) {
                    sync2.trigger("owl.goTo", num - sync2visible.length + 2)
                } else {
                    if (num - 1 === -1) {
                        num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                }
            } else if (num === sync2visible[sync2visible.length - 1]) {
                sync2.trigger("owl.goTo", sync2visible[1])
            } else if (num === sync2visible[0]) {
                sync2.trigger("owl.goTo", num - 1)
            }
        }
    });
</script>