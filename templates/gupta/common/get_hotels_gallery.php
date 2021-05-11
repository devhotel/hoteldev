<?php

/**
 * Script called (Ajax) on scroll or click
 * loads more content with Lazy Loader
 */
$html = '';
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
}
if (isset($db) && $db !== false) {

    //$my_page_alias = $sys_pages['gallery']['alias'];
    if (isset($_POST['id_destination']) && is_numeric($_POST['id_destination'])) $id_destination = $_POST['id_destination'];
    if (isset($_POST['id_hotel']) && is_numeric($_POST['id_hotel'])) $id_hotel = $_POST['id_hotel'];
    $my_page_alias = 'gallery';

    $query_hotel = 'SELECT * FROM pm_hotel WHERE lang = ' . LANG_ID . ' AND checked = 1';
    if (isset($id_destination) && $id_destination > 0) $query_hotel .= ' AND id_destination = ' . $db->quote($id_destination);
    if (isset($id_hotel) && $id_hotel > 0) $query_hotel .= ' AND id = ' . $db->quote($id_hotel);
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
        <article class="col-sm-6 col-md-3 isotopeItem" itemscope itemtype="http://schema.org/LodgingBusiness">
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
                                    <span class="more-icon"><i class="fa fa-plus"></i></span>
                                </span>
                            </figure>';
            }
        }
        $html .= '
                    <div class="isotopeContent">
                        <h3 itemprop="name">' . $hotel_title . '</h3>';
        $html .= '
                </a>
            </div>
             
        </article>';
    }
    if (isset($_POST['ajax']) && $_POST['ajax'] == 1)
        echo json_encode(array('html' => $html));
    else
        echo $html;
}
