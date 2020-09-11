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

    $my_page_alias = $sys_pages['booking']['alias'];

    $query_accommodation = 'SELECT * FROM pm_accommodation WHERE lang = ' . LANG_ID . ' AND checked = 1';
    $query_accommodation .= ' ORDER BY rank LIMIT ' . ($lz_offset - 1) * $lz_limit . ', ' . $lz_limit;
    $result_accommodation = $db->query($query_accommodation);

    $accommodation_id = 0;

    $result_accommodation_file = $db->prepare('SELECT * FROM pm_accommodation_file WHERE id_item = :accommodation_id AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    $result_accommodation_file->bindParam(':accommodation_id', $accommodation_id);

    $result_rate = $db->prepare('
        SELECT MIN(ra.price) as min_price
        FROM pm_rate as ra, pm_hotel as h, pm_accommodation as d
        WHERE id_hotel = h.id
            AND id_accommodation = d.id
            AND id_accommodation = :accommodation_id');
    $result_rate->bindParam(':accommodation_id', $accommodation_id);

    foreach ($result_accommodation as $i => $row) {

        $accommodation_id = $row['id'];
        $accommodation_name = $row['name'];
        $accommodation_title = $row['title'];
        $accommodation_alias = $row['alias'];

        $accommodation_alias = DOCBASE . $my_page_alias . '/' . text_format($accommodation_alias);
        //$accommodation_alias = $my_page_alias . '?accommodation_id=' . $row['id'];
        $min_price = 0;
        if ($result_rate->execute() !== false && $db->last_row_count() > 0) {
            $row = $result_rate->fetch();
            $price = $row['min_price'];
            if ($price > 0) {
                $min_price = $price;

                $html .= '
        <article class="col-sm-6 col-md-4 isotopeItem accommodation" itemscope itemtype="http://schema.org/Place">
            <div class="isotopeInner">
                <a itemprop="url" href="' . $accommodation_alias . '">';
                if ($result_accommodation_file->execute() !== false && $db->last_row_count() > 0) {
                    $row = $result_accommodation_file->fetch(PDO::FETCH_ASSOC);

                    $file_id = $row['id'];
                    $filename = $row['file'];
                    $label = $row['label'];

                    $realpath = SYSBASE . 'medias/accommodation/medium/' . $file_id . '/' . $filename;
                    $thumbpath = DOCBASE . 'medias/accommodation/medium/' . $file_id . '/' . $filename;
                    $zoompath = DOCBASE . 'medias/accommodation/big/' . $file_id . '/' . $filename;

                    if (is_file($realpath)) {
                        $html .= '
                            <figure class="more-link">
                                <img alt="' . $label . '" src="' . $thumbpath . '" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                    } else {
                        $html .= '
                            <figure class="more-link">
                                <img alt="' . $label . '" src="' . getFromTemplate("images/no-image.png") . '" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                    }
                } else {
                    $html .= '
                            <figure class="more-link">
                                <img alt="' . $label . '" src="' . getFromTemplate("images/no-image.png") . '" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                }
                $html .= '
                    <div class="isotopeContent">
                        <h3 itemprop="name">' . $accommodation_name . '</h3>
                        <h4>' . $accommodation_title . '</h4>';

                $html .= '
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="price text-primary">
                                    ' . $texts['FROM_PRICE'] . '
                                    <span itemprop="priceRange">
                                        ' . formatPrice($min_price * CURRENCY_RATE) . '
                                    </span>
                                </div>
                                <div class="text-muted">' . $texts['PRICE'] . ' / ' . $texts['NIGHT'] . '</div>
                            </div>
                            <div class="col-xs-6">
                                <span class="btn btn-primary mt5 pull-right">' . $texts['MORE_DETAILS'] . '</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </article>';
            }
        }
    }
    if (isset($_POST['ajax']) && $_POST['ajax'] == 1)
        echo json_encode(array('html' => $html));
    else
        echo $html;
}
