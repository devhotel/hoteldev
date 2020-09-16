<?php

/**
 * Script called (Ajax) on scroll or click
 * loads more content with Lazy Loader
 */
$html = 'ok';
echo $html; die;


if (!isset($lz_offset)) $lz_offset = 1;
if (!isset($lz_limit)) $lz_limit = 30;
if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {

    require_once('../../../common/lib.php');
    require_once('../../../common/define.php');

    if (
        isset($_POST['offset']) && is_numeric($_POST['offset'])
        && isset($_POST['limit']) && is_numeric($_POST['limit'])
    ) {
        $lz_offset = $_POST['offset'];
        $lz_limit =    $_POST['limit'];
    }
    if (isset($_POST['destination']) && is_numeric($_POST['destination'])) $destination_id = $_POST['destination'];
}
if (isset($db) && $db !== false) {

    $my_page_alias = $sys_pages['hotels']['alias'];

    $query_hotel = 'SELECT * FROM pm_hotel WHERE lang = ' . LANG_ID . ' AND checked = 1';
    if (isset($destination_id)) $query_hotel .= ' AND id_destination = ' . $db->quote($destination_id);
    $query_hotel .= ' ORDER BY rank LIMIT ' . ($lz_offset - 1) * $lz_limit . ', ' . $lz_limit;
    $result_hotel = $db->query($query_hotel);

    $id_hotel = 0;

    $result_hotel_file = $db->prepare('SELECT * FROM pm_hotel_file WHERE id_item = :id_hotel AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    $result_hotel_file->bindParam(':id_hotel', $id_hotel);
    $now_time = strtotime("now");
    $result_rate = $db->prepare('SELECT * FROM pm_rate WHERE end_date >=' . $now_time . ' AND id_hotel = :id_hotel');
    $result_rate->bindParam(':id_hotel', $id_hotel);

    $result_base_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_room WHERE id_hotel = :id_hotel');
    $result_base_rate->bindParam(':id_hotel', $id_hotel);
    $item_type = 'hotel';
    $result_rating_count = $db->prepare('SELECT COUNT(*) as tot_rating FROM pm_comment WHERE id_item = :id_item AND item_type = :item_type AND checked = 1');
    $result_rating_count->bindParam(':id_item', $id_hotel);
    $result_rating_count->bindParam(':item_type', $item_type);

    $result_rating_avg = $db->prepare('SELECT AVG(rating) as avg_rating FROM pm_comment WHERE id_item = :id_item AND item_type = :item_type AND checked = 1');
    $result_rating_avg->bindParam(':id_item', $id_hotel);
    $result_rating_avg->bindParam(':item_type', $item_type);
?>
           
<?php
    foreach ($result_hotel as $i => $row) {

        $id_hotel = $row['id'];
        $hotel_title = $row['title'];
        $hotel_subtitle = $row['subtitle'];
        $address = $row['address'];
        $hotel_alias = $row['alias'];

        $hotel_alias = DOCBASE . $my_page_alias . '/' . text_format($hotel_alias);
        if (isset($_SESSION['user'])) {
            $data_link = DOCBASE . 'templates/' . TEMPLATE . '/common/wishlist.php';
            $href_attr = 'href="javscript:void(0);" class="sendAjaxForm wshlst_button" data-action="' . $data_link . '" data-refresh="true"';
            $user_id = $_SESSION['user']['id'];
            $get_query = $db->query("SELECT * FROM pm_wishlist WHERE hotel_id =" . $id_hotel . " AND user_id=" . $user_id);
            $result = $get_query->fetch();
            if (!empty($result)) {
                $style_attr = 'style="background:#ff0000"';
            } else {
                $style_attr = 'style="background:#000"';
            }
        } else {
            $data_link = '';
            $href_attr = 'class="popup-modal firstLevel wshlst_button" href="#user-popup"';
            $user_id = '';
            $style_attr = 'style="background:#000"';
        }
        $html .= '
        <article class="col-sm-6 col-md-4 isotopeItem" itemscope itemtype="http://schema.org/LodgingBusiness">
            <div class="isotopeInner">
                <a itemprop="url" href="' . $hotel_alias . '">';

        if ($result_hotel_file->execute() !== false && $db->last_row_count() > 0) {
            $row = $result_hotel_file->fetch(PDO::FETCH_ASSOC);
            $file_id = $row['id'];
            $filename = $row['file'];
            $label = $row['label'];

            $realpath = SYSBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
            $thumbpath = DOCBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
            $zoompath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;

            if (is_file($realpath)) {
                $html .= '
                            <figure class="more-link img-container md">
                                <img alt="' . $label . '" src="' . $thumbpath . '">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
            }
        }
        $html .= '
                    <div class="isotopeContent">
                        <h3 itemprop="name">' . $hotel_title . '</h3>
                        <h5 class="text-center">' . $address . '</h5>';
        $min_price = 0;
        $price_arry = array();
        if ($result_rate->execute() !== false && $db->last_row_count() > 0) {
            $row = $result_rate->fetchAll();
            foreach ($row as $r) {
                $price = $r['price'];
                $discount = $r['discount'];
                $discount_type = $r['discount_type'];
                if ($discount > 0 && $discount_type != "") {
                    if ($discount_type == 'rate') {
                        $min_price = (($discount * $price) / 100);
                        $min_price = $price - $min_price;
                        array_push($price_arry, $min_price);
                    } else {
                        $min_price = $price - $discount;
                        array_push($price_arry, $min_price);
                    }
                } else {
                    array_push($price_arry, $price);
                }
            }

            $price = min($price_arry);
            if ($price > 0) $min_price = $price;
        }
        if ($min_price > 0) {

            $html .= '
                        <div>
                            <div class="col-xs-6">
                                <div class="price text-primary">
                                    ' . $texts['FROM_PRICE'];
            if ($result_base_rate->execute() !== false && $db->last_row_count() > 0) {
                $brow = $result_base_rate->fetch();
                $base_price = $brow['min_price'];
                if ($base_price > 0 && $base_price > $min_price) {
                    $html .= '<del>' . formatPrice($base_price * CURRENCY_RATE) . '</del>';
                }
            }

            $html .= '<br/><span itemprop="priceRange">
                                        ' . formatPrice($min_price * CURRENCY_RATE) . '
                                    </span>
                                </div>
                                <div class="text-muted">' . $texts['PRICE'] . ' / ' . $texts['NIGHT'] . '</div>
                            </div>
                            <div class="col-xs-6">
                                <span class="btn btn-primary mt5 pull-right">' . $texts['MORE_DETAILS'] . '</span>
                            </div>
                           
                        </div>
                    </div>';
        } else {
            $html .= '
                        <div>
                            <div class="col-xs-6">
                                <div class="price text-primary">
                                    ' . $texts['FROM_PRICE'];
            if ($result_base_rate->execute() !== false && $db->last_row_count() > 0) {
                $brow = $result_base_rate->fetch();
                $base_price = $brow['min_price'];
            }

            $html .= '<br/><span itemprop="priceRange">
                                        ' . formatPrice($base_price * CURRENCY_RATE) . '
                                    </span>
                                </div>
                                <div class="text-muted">' . $texts['PRICE'] . ' / ' . $texts['NIGHT'] . '</div>
                            </div>
                            <div class="col-xs-6">
                                <span class="btn btn-primary mt5 pull-right">' . $texts['MORE_DETAILS'] . '</span>
                            </div>
                           
                        </div>
                    </div>';
        }
        $tot_rating = 0;
        if ($result_rating_count->execute() !== false && $db->last_row_count() > 0) {
            $count = $result_rating_count->fetchColumn();
            if ($count > 0) $tot_rating = $count;
        }
        $avg_rating = 0;
        if ($result_rating_avg->execute() !== false && $db->last_row_count() > 0) {
            $avg = $result_rating_avg->fetchColumn();
            if ($avg > 0) $avg_rating = $avg;
        }
        $avg_rating = floatval($avg_rating);
        //$avg_rating = number_format((float) $avg_rating, 1, '.', '');
        switch ($avg_rating) {
            case ($avg_rating < 2):
                $rating_txt = 'Bad';
                break;
            case ($avg_rating >= 2 && $avg_rating < 2.5):
                $rating_txt = 'Poor';
                break;
            case ($avg_rating >= 2.5 && $avg_rating < 3):
                $rating_txt = 'Average';
                break;
            case ($avg_rating >= 3 && $avg_rating < 3.5):
                $rating_txt = 'Fair';
                break;
            case ($avg_rating >= 3.5 && $avg_rating < 4):
                $rating_txt = 'Good';
                break;
            case ($avg_rating >= 4 && $avg_rating < 4.5):
                $rating_txt = 'Very Good';
                break;
            case ($avg_rating >= 4.5 && $avg_rating < 4.8):
                $rating_txt = 'Excelent';
                break;
            case ($avg_rating > 4.8):
                $rating_txt = 'Fabulous';
                break;
            default:
                $rating_txt = '&#128528;';
                break;
        }
        $html .= '<div class="hotelRating__wrapper" tabindex="-1">
                            <span class="is-fontBold hotelRating__rating">' . $avg_rating . ' 
                            <span class="hotelRating__star"><i class="fa fa-star"></i></span></span>
                            <span class="hotelRating__ratingSummary">(' . $tot_rating . ' Reviews)</span></div>
                </a>
            </div>
             <form method="post" action="#" class="ajax-form">
                <input type="hidden" name="hotel_id" value="' . $id_hotel . '" />
                <input type="hidden" name="user_id" value="' . $user_id . '" />
                <span><a ' . $href_attr . ' ' . $style_attr . '><i class="far fa-heart"></i></a></span>
            </form>
        </article>';
    }
    if (isset($_POST['ajax']) && $_POST['ajax'] == 1)
        echo json_encode(array('html' => $html));
    else
        echo $html;
}
