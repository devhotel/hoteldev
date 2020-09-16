<?php
/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/royalslider/royalslider.css', 'media' => 'all');
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/royalslider/skins/minimal-white/rs-minimal-white.css', 'media' => 'all');
$javascripts[] = DOCBASE . 'js/plugins/royalslider/jquery.royalslider.min.js';
$javascripts[] = DOCBASE . 'js/plugins/live-search/jquery.liveSearch.js';
require(getFromTemplate('common/header.php', false));
$slide_id = 0;
$result_slide_file = $db->prepare('SELECT * FROM pm_slide_file WHERE id_item = :slide_id AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
$result_slide_file->bindParam('slide_id', $slide_id);
$result_slide = $db->query('SELECT * FROM pm_slide WHERE id_page = ' . $page_id . ' AND checked = 1 AND lang = ' . LANG_ID . ' ORDER BY rank', PDO::FETCH_ASSOC);
if ($result_slide !== false) {
    $nb_slides = $db->last_row_count();
    if ($nb_slides > 0) { ?>
        <div id="search-home-wrapper">
            <div id="search-home" class="container">
                <?php include(getFromTemplate('common/search-home.php', false)); ?>
            </div>
        </div>
        <section id="sliderContainer">
            <div class="royalSlider rsMinW fullSized clearfix">
                <?php
                foreach ($result_slide as $i => $row) {
                    $slide_id = $row['id'];
                    $slide_legend = $row['legend'];
                    $url_video = $row['url'];
                    $id_page = $row['id_page'];
                    $result_slide_file->execute();
                    if ($result_slide_file !== false && $db->last_row_count() == 1) {
                        $row = $result_slide_file->fetch();
                        $file_id = $row['id'];
                        $filename = $row['file'];
                        $label = $row['label'];
                        $realpath = SYSBASE . 'medias/slide/big/' . $file_id . '/' . $filename;
                        $thumbpath = DOCBASE . 'medias/slide/small/' . $file_id . '/' . $filename;
                        $zoompath = DOCBASE . 'medias/slide/big/' . $file_id . '/' . $filename;
                        if (is_file($realpath)) { ?>
                            <div class="rsContent">
                                <img class="rsImg" src="<?php echo $zoompath; ?>" alt="" <?php if ($url_video != '') echo ' data-rsVideo="' . $url_video . '"'; ?>>
                                <?php
                                if ($slide_legend != '') { ?>
                                    <div class="infoBlock" data-fade-effect="" data-move-offset="10" data-move-effect="bottom" data-speed="200">
                                        <?php echo $slide_legend; ?>
                                    </div>
                                <?php
                                } ?>
                            </div>
                <?php
                        }
                    }
                } ?>
            </div>
        </section>
<?php
    }
} ?>
<?php
$testimonial = $db->query('SELECT * FROM pm_testimonial where status = 1 ORDER BY id DESC ')->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="top_testimonial">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php for ($i = 0; $i < count($testimonial); $i++) { ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" <?php if ($i == 0) { ?> class="active" <?php } ?>></li>
            <?php } ?>
        </ol>
        <div class="carousel-inner" role="listbox">
            <?php for ($j = 0; $j < count($testimonial); $j++) { ?>
                <div class="item <?php if ($j == 0) {
                                        echo 'active';
                                    } ?>">
                    <h1>HMS , Luxury Hotels </h1>
                    <?= $testimonial[$j]['name'] ?>
                    <span>- <?= $testimonial[$j]['comment'] ?> -</span>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<section id="content" class="pt20 pb30">
    <div class="container">
        <div class="row mb10">
            <?php
            $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE lang = ' . LANG_ID . ' AND checked = 1 AND home = 1 ORDER BY rank');
            if ($result_hotel !== false) {
                $nb_hotels = $db->last_row_count();
                $hotel_id = 0;
                $result_hotel_file = $db->prepare('SELECT * FROM pm_hotel_file WHERE id_item = :hotel_id AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                $result_hotel_file->bindParam(':hotel_id', $hotel_id);
                //$result_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_rate WHERE id_hotel = :hotel_id');
                $result_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_room WHERE checked = 1 AND id_hotel = :hotel_id');
                $result_rate->bindParam(':hotel_id', $hotel_id);
                foreach ($result_hotel as $i => $row) {
                    $hotel_id = $row['id'];
                    $hotel_title = $row['title'];
                    $hotel_subtitle = $row['subtitle'];
                    $hotel_alias = DOCBASE . $pages[9]['alias'] . '/' . text_format($row['alias']);
                    $min_price = 0;


                    $newMinDiscPriceQ   = $db->query('SELECT MIN(new_disc_price) as new_disc_price FROM pm_room_new_stock_rate WHERE id_hotel = ' . $hotel_id . ' AND date = ' . date('Y-m-d'))->fetch(PDO::FETCH_ASSOC);
                    $newMinPriceQ       = $db->query('SELECT MIN(new_price) as new_price FROM pm_room_new_stock_rate WHERE id_hotel = ' . $hotel_id . ' AND date = ' . date('Y-m-d'))->fetch(PDO::FETCH_ASSOC);
                    $newMinPrice        = (!empty($newMinPriceQ['new_price'])) ? $newMinPriceQ['new_price'] : '0';
                    $newMinDiscPrice    = (!empty($newMinDiscPriceQ['new_price'])) ? $newMinDiscPriceQ['new_price'] : '0';
                    echo 'SELECT MIN(new_disc_price) as new_disc_price FROM pm_room_new_stock_rate WHERE id_hotel = ' . $hotel_id . ' AND date = ' . date('Y-m-d');
                    print_r($newMinPrice); 
                    print_r($newMinDiscPrice); 
                    die;



                    if ($result_rate->execute() !== false && $db->last_row_count() > 0) {
                        $row = $result_rate->fetch();
                        $price = $row['min_price'];
                        if ($price > 0):
                            $min_price = ($newMinPrice != 0 || $newMinDiscPrice != 0) ? (($newMinDiscPrice <= $newMinPrice) ? (($newMinDiscPrice < $price) ? $newMinDiscPrice : $price) : (($newMinPrice < $price) ? $newMinPrice : $price)) : $price;
                        endif;
                    } ?>
                    <article class="col-sm-4 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                        <a itemprop="url" href="<?php echo $hotel_alias; ?>" class="moreLink">
                            <?php
                            if ($result_hotel_file->execute() !== false && $db->last_row_count() == 1) {
                                $row = $result_hotel_file->fetch(PDO::FETCH_ASSOC);
                                $file_id = $row['id'];
                                $filename = $row['file'];
                                $label = $row['label'];
                                $realpath = SYSBASE . 'medias/hotel/small/' . $file_id . '/' . $filename;
                                $thumbpath = DOCBASE . 'medias/hotel/small/' . $file_id . '/' . $filename;
                                $zoompath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                if (is_file($realpath)) {
                                    $s = getimagesize($realpath); ?>
                                    <figure class="more-link">
                                        <div class="img-container lazyload md">
                                            <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                        </div>
                                        <div class="more-content">
                                            <?php
                                            if ($min_price > 0) : ?>
                                                <div class="more-descr">
                                                    <h3 itemprop="name"><?php echo $hotel_title; ?></h3>
                                                    <div class="price">
                                                        <?php echo $texts['FROM_PRICE']; ?>
                                                        <span itemprop="priceRange">
                                                            <?php echo formatPrice($min_price * CURRENCY_RATE); ?>
                                                        </span>
                                                    </div>
                                                    <small><?php echo $texts['PRICE'] . ' / ' . $texts['NIGHT']; ?></small>
                                                </div>
                                            <?php else: ?>
                                                <small>No Rooms Available</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="more-action">
                                            <div class="more-icon">
                                                <i class="fas fa-plus-circle"></i>
                                            </div>
                                        </div>
                                    </figure>
                            <?php
                                }
                            } ?>
                        </a>
                    </article>
            <?php
                }
            } ?>
        </div>
    </div>
    <?php
    $activity_id = 0;
    $result_activity = $db->query('SELECT * FROM pm_activity WHERE lang = ' . LANG_ID . ' AND checked = 1 AND home = 1 ORDER BY rank');
    if ($result_activity !== false) {
        $nb_activities = $db->last_row_count();
        if ($nb_activities > 0) { ?>
            <div class="hotBox mb30 mt5">
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center mt5 mb10"><?php echo $texts['FIND_ACTIVITIES_AND_TOURS']; ?></h2>
                        <?php
                        $activity_id = 0;
                        $result_activity_file = $db->prepare('SELECT * FROM pm_activity_file WHERE id_item = :activity_id AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                        $result_activity_file->bindParam(':activity_id', $activity_id);
                        foreach ($result_activity as $i => $row) {
                            $activity_id = $row['id'];
                            $activity_title = $row['title'];
                            $activity_alias = $row['title'];
                            $activity_subtitle = $row['subtitle'];
                            $min_price = $row['price'];
                            $activity_alias = DOCBASE . $sys_pages['activities']['alias'] . '/' . text_format($row['alias']); ?>
                            <article class="col-sm-3 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                                <a itemprop="url" href="<?php echo $activity_alias; ?>" class="moreLink">
                                    <?php
                                    if ($result_activity_file->execute() !== false && $db->last_row_count() > 0) {
                                        $row = $result_activity_file->fetch(PDO::FETCH_ASSOC);
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        $realpath = SYSBASE . 'medias/activity/small/' . $file_id . '/' . $filename;
                                        $thumbpath = DOCBASE . 'medias/activity/small/' . $file_id . '/' . $filename;
                                        $zoompath = DOCBASE . 'medias/activity/big/' . $file_id . '/' . $filename;
                                        if (is_file($realpath)) {
                                            $s = getimagesize($realpath); ?>
                                            <figure class="more-link">
                                                <div class="img-container lazyload md">
                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                </div>
                                                <div class="more-content">
                                                    <h3 itemprop="name"><?php echo $activity_title; ?></h3>
                                                </div>
                                                <div class="more-action">
                                                    <div class="more-icon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                </div>
                                            </figure>
                                    <?php
                                        }
                                    } ?>
                                </a>
                            </article>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    $result_destination = $db->query('SELECT * FROM pm_destination WHERE lang = ' . LANG_ID . ' AND checked = 1 AND home = 1 ORDER BY rank');
    if ($result_destination !== false) {
        $nb_destinations = $db->last_row_count();
        if ($nb_destinations > 0) { ?>
            <div class="dest_section">
                <div class="container">
                    <div class="row mb10">
                        <h2 class="text-center mt5 mb10"><?php echo $texts['TOP_DESTINATIONS']; ?></h2>
                        <?php
                        $destination_id = 0;
                        $result_destination_file = $db->prepare('SELECT * FROM pm_destination_file WHERE id_item = :destination_id AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                        $result_destination_file->bindParam(':destination_id', $destination_id);
                        $result_rate = $db->prepare('
                        SELECT MIN(ra.price) as min_price
                        FROM pm_rate as ra, pm_hotel as h, pm_destination as d
                        WHERE id_hotel = h.id
                            AND id_destination = d.id
                            AND id_destination = :destination_id');
                        $result_rate->bindParam(':destination_id', $destination_id);
                        foreach ($result_destination as $i => $row) {
                            $destination_id = $row['id'];
                            $destination_name = $row['name'];
                            $destination_subtitle = $row['subtitle'];
                            $destination_alias = DOCBASE . $sys_pages['booking']['alias'] . '/' . text_format($row['alias']);
                            $min_price = 0;
                            if ($result_rate->execute() !== false && $db->last_row_count() > 0) {
                                $row = $result_rate->fetch();
                                $price = $row['min_price'];
                                if ($price > 0) $min_price = $price;
                            } ?>
                            <article class="col-sm-4 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                                <a itemprop="url" href="<?php echo $destination_alias; ?>" class="moreLink">
                                    <?php
                                    if ($result_destination_file->execute() !== false && $db->last_row_count() == 1) {
                                        $row = $result_destination_file->fetch(PDO::FETCH_ASSOC);
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        $realpath = SYSBASE . 'medias/destination/small/' . $file_id . '/' . $filename;
                                        $thumbpath = DOCBASE . 'medias/destination/small/' . $file_id . '/' . $filename;
                                        $zoompath = DOCBASE . 'medias/destination/big/' . $file_id . '/' . $filename;
                                        if (is_file($realpath)) {
                                            $s = getimagesize($realpath); ?>
                                            <figure class="more-link">
                                                <div class="img-container lazyload md">
                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                </div>
                                                <div class="more-content">
                                                    <h3 itemprop="name"><?php echo $destination_name; ?></h3>
                                                    <?php
                                                    if ($min_price > 0) { ?>
                                                        <div class="more-descr">
                                                            <div class="price">
                                                                <?php echo $texts['FROM_PRICE']; ?>
                                                                <span itemprop="priceRange">
                                                                    <?php echo formatPrice($min_price * CURRENCY_RATE); ?>
                                                                </span>
                                                            </div>
                                                            <small><?php echo $texts['PRICE'] . ' / ' . $texts['NIGHT']; ?></small>
                                                        </div>
                                                    <?php
                                                    } ?>
                                                </div>
                                                <div class="more-action">
                                                    <div class="more-icon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                </div>
                                            </figure>
                                    <?php
                                        }
                                    } ?>
                                </a>
                            </article>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    $result_article = $db->query('SELECT * FROM pm_article WHERE (id_page = ' . $page_id . ' OR home = 1) AND checked = 1 AND (publish_date IS NULL || publish_date <= ' . time() . ') AND (unpublish_date IS NULL || unpublish_date > ' . time() . ') AND lang = ' . LANG_ID . ' ORDER BY rank');
    if ($result_article !== false) {
        $nb_articles = $db->last_row_count();
        if ($nb_articles > 0) { ?>
            <div class="container mt10">
                <div class="row">
                    <div class="clearfix">
                        <?php
                        $article_id = 0;
                        $result_article_file = $db->prepare('SELECT * FROM pm_article_file WHERE id_item = :article_id AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                        $result_article_file->bindParam(':article_id', $article_id);
                        foreach ($result_article as $i => $row) {
                            $article_id = $row['id'];
                            $article_title = $row['title'];
                            $article_alias = $row['alias'];
                            $char_limit = ($i == 0) ? 1200 : 500;
                            $article_text = strtrunc(strip_tags($row['text'], '<p><br>'), $char_limit, true, '');
                            $article_page = $row['id_page'];
                            if (isset($pages[$article_page])) {
                                $article_alias = DOCBASE . $pages[$article_page]['alias'] . '/' . text_format($article_alias); ?>
                                <article id="article-<?php echo $article_id; ?>" class="col-sm-<?php echo ($i == 0) ? 12 : 4; ?> " itemscope itemtype="http://schema.org/Article">
                                    <div class="<?php echo ($i == 0) ? 'row' : ''; ?> ">
                                        <div class="<?php echo ($i != 0) ? 'art_small' : ''; ?>">
                                            <a itemprop="url" href="<?php echo $article_alias; ?>" class="moreLink">
                                                <div class="col-sm-<?php echo ($i == 0) ? 8 : 12; ?> mb20">
                                                    <?php
                                                    if ($result_article_file->execute() !== false && $db->last_row_count() == 1) {
                                                        $row = $result_article_file->fetch(PDO::FETCH_ASSOC);
                                                        $file_id = $row['id'];
                                                        $filename = $row['file'];
                                                        $label = $row['label'];
                                                        $realpath = SYSBASE . 'medias/article/big/' . $file_id . '/' . $filename;
                                                        $thumbpath = DOCBASE . 'medias/article/big/' . $file_id . '/' . $filename;
                                                        $zoompath = DOCBASE . 'medias/article/big/' . $file_id . '/' . $filename;
                                                        if (is_file($realpath)) {
                                                            $s = getimagesize($realpath); ?>
                                                            <figure class="more-link">
                                                                <div class="img-container lazyload xl">
                                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                                </div>
                                                                <div class="more-action">
                                                                    <div class="more-icon">
                                                                        <i class="fa fa-link"></i>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                    <?php
                                                        }
                                                    } ?>
                                                </div>
                                                <div class="col-sm-<?php echo ($i == 0) ? 4 : 12; ?>">
                                                    <div class="text-overflow">
                                                        <h3 itemprop="name"><?php echo $article_title; ?></h3>
                                                        <?php echo $article_text; ?>
                                                        <div class="more-btn">
                                                            <span class="btn btn-primary"><?php echo $texts['READMORE']; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                        <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
    <?php
        }
    } ?>
</section>
</div>
<!-- Modal -->
<div id="covidModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close covidClose" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <h2>COVID-19 Safety Measures at HMS</h2>
                <ul>
                    <li>Thermal screening at the entry both for staff and guests</li>
                    <li>Sanitization at the entry and as & when required</li>
                    <li>Separate dustbin for all used masks and gloves, cleared in a sterile manner</li>
                    <li>Extra masks and gloves are available for those who need</li>
                    <li>Our staff wears appropriate safety kits</li>
                    <li>Each and every room gets sanitized after guests check out</li>
                    <li>Sanitization of entire property is being done at regular intervals</li>
                    <li>Our Staff keep safe distance while serving or providing any services to the guests</li>
                </ul>
                <p>Maintaining all safety and sanitization norms, we are ready to serve you again with same passion as before.</p>
                <p><b>Team HMS</b></p>
            </div>
        </div>
    </div>
</div>
<div id="homePopupModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <h2>We have launched our mobile application</h2>
                <a href="http://shorturl.at/jwS46" target="_blank">
                    <p>Here is the link to download <br><img src="/templates/main/images/google-play-icon.png"></p>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    /*setTimeout(function() {
        $('#covidModal').modal('show');
    }, 3000);
    $(".covidClose").click(function(){
        $('#homePopupModal').modal('show');
    });*/
</script>