<?php
if($article_alias == '') err404();

$result = $db->query('SELECT * FROM pm_accommodation WHERE checked = 1 AND lang = '.LANG_ID.' AND alias = '.$db->quote($article_alias));
if($result !== false && $db->last_row_count() > 0){
    
    $accommodation = $result->fetch(PDO::FETCH_ASSOC);
    
    $accommodation_id = $accommodation['id'];
    $article_id = $accommodation_id;
    $title_tag = $accommodation['name'].' - '.$title_tag;
    $page_title = $accommodation['name'];
    $page_subtitle = '';
    $page_alias = $pages[$page_id]['alias'].'/'.text_format($accommodation['alias']);
    
    $result_accommodation_file = $db->query('SELECT * FROM pm_accommodation_file WHERE id_item = '.$accommodation_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    if($result_accommodation_file !== false && $db->last_row_count() > 0){
        
        $row = $result_accommodation_file->fetch();
        
        $file_id = $row['id'];
        $filename = $row['file'];
        
        if(is_file(SYSBASE.'medias/accommodation/medium/'.$file_id.'/'.$filename))
            $page_img = getUrl(true).'/medias/accommodation/medium/'.$file_id.'/'.$filename;
    }
    
}else err404();

check_URI(DOCBASE.$page_alias);

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$javascripts[] = DOCBASE.'js/plugins/jquery.sharrre-1.3.4/jquery.sharrre-1.3.4.min.js';

$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css', 'media' => 'all');
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js';

$stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js';
$javascripts[] = DOCBASE.'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';

$stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/css/star-rating.min.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/js/star-rating.min.js';

$stylesheets[] = array('file' => DOCBASE.'js/plugins/simpleweather/css/simpleweather.css', 'media' => 'all');
$javascripts[] = '//cdn.rawgit.com/monkeecreate/jquery.simpleWeather/master/jquery.simpleWeather.min.js';

require(getFromTemplate('common/send_comment.php', false));

require(getFromTemplate('common/header.php', false)); ?>

<article id="page">
    <?php include(getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="pt30 pb30">
        <div class="container">
            
            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            
            <div class="row">
                <div class="col-md-8 mb20">
                    <div class="row mb10">
                        <div class="col-sm-12">
                            <h1 class="mb0">
                                <?php echo $accommodation['name']; ?>
                                <br><small><?php echo $accommodation['subtitle']; ?></small>
                            </h1>
                        </div>
                    </div>
                    <div class="row mb10">
                        <div class="col-md-12">
                            <div class="owl-carousel owlWrapper" data-items="1" data-autoplay="false" data-dots="true" data-nav="false" data-rtl="<?php echo (RTL_DIR) ? 'true' : 'false'; ?>">
                                <?php
                                if(!empty($accommodation['video'])){ ?>
                                    <div class="video-container">
                                        <iframe src="<?php echo $accommodation['video']; ?>" frameborder="0"></iframe>
                                    </div>
                                    <?php
                                }
                                $result_accommodation_file = $db->query('SELECT * FROM pm_accommodation_file WHERE id_item = '.$accommodation_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank');
                                if($result_accommodation_file !== false){
                                    
                                    foreach($result_accommodation_file as $i => $row){
                                    
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE.'medias/accommodation/big/'.$file_id.'/'.$filename;
                                        $thumbpath = DOCBASE.'medias/accommodation/big/'.$file_id.'/'.$filename;
                                        
                                        if(is_file($realpath)){ ?>
                                            <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>" class="img-responsive" style="max-height:600px;"/>
                                            <?php
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb10">
                        <div class="col-md-12" itemprop="description">
                            <?php echo $accommodation['text']; ?>
                        </div>
                    </div>
                </div>
                <aside class="col-md-4 mb20">
                    <div class="boxed">
                        <div itemscope itemtype="http://schema.org/Corporation">
                            <h3 itemprop="name"><?php echo $accommodation['name']; ?></h3>
                            <span class="simple-weather" data-location="<?php echo $accommodation['name']; ?>" data-unit="c"></span>
                        </div>
                        <script type="text/javascript">
                            var locations = [
                                ['<?php echo $accommodation['name']; ?>', '', '<?php echo $accommodation['lat']; ?>', '<?php echo $destination['lng']; ?>']
                            ];
                        </script>
                        <div id="mapWrapper" class="mb30" data-marker="<?php echo getFromTemplate('images/marker.png'); ?>" data-api_key="<?php echo GMAPS_API_KEY; ?>"></div>
                        
                        <?php
                        $id_accommodation = 0;
                        $result_accommodation_file = $db->prepare('SELECT * FROM pm_accommodation_file WHERE id_item = :id_accommodation AND checked = 1 AND lang = '.LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY rank');
                        $result_accommodation_file->bindParam(':id_destination', $id_destination, PDO::PARAM_STR);
                
                        $result_accommodation = $db->query('SELECT * FROM pm_accommodation WHERE id != '.$accommodation_id.' AND checked = 1 AND lang = '.LANG_ID.' ORDER BY rand() LIMIT 5', PDO::FETCH_ASSOC);
                        if($result_accommodation !== false && $db->last_row_count() > 0){
                            foreach($result_accommodation as $i => $row){
                                $id_accommodation = $row['id'];
                                $accommodation_name = $row['name'];
                                $accommodation_subtitle = $row['subtitle'];
                                $accommodation_alias = $row['alias']; ?>
                                
                                <a href="<?php echo DOCBASE.$page['alias'].'/'.text_format($accommodation_alias); ?>">
                                    <div class="row">
                                        <div class="col-xs-4 mb20">
                                            <?php
                                            $result_accommodation_file->execute();
                                            if($result_accommodation_file !== false && $db->last_row_count() > 0){
                                                $row = $result_accommodation_file->fetch(PDO::FETCH_ASSOC);
                                                
                                                $file_id = $row['id'];
                                                $filename = $row['file'];
                                                $label = $row['label'];
                                                
                                                $realpath = SYSBASE.'medias/accommodation/small/'.$file_id.'/'.$filename;
                                                $thumbpath = DOCBASE.'medias/accommodation/small/'.$file_id.'/'.$filename;
                                                    
                                                if(is_file($realpath)){ ?>
                                                    <div class="img-container sm">
                                                        <img alt="" src="<?php echo $thumbpath; ?>">
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                        <div class="col-xs-8">
                                            <h3 class="mb0"><?php echo $accommodation_name; ?></h3>
                                            <?php
                                            if($accommodation_subtitle != ''){ ?>
                                                <h4 class="mb0"><?php echo $accommodation_subtitle; ?></h4>
                                                <?php
                                            } ?>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            } ?>
                            <?php
                        } ?>
                    </div>
                </aside>
            </div>
            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
                $hotel_ids = array();
                $result = $db->query('SELECT id FROM pm_hotel WHERE checked = 1 AND lang = '.LANG_ID.' AND id_destination = '.$destination_id);
                if($result !== false){
                    $hotel_ids = $result->fetchAll(PDO::FETCH_COLUMN);
                    $num_records = count($hotel_ids);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                if($num_records > 0){ ?>
                    <div class="col-md-12">
                        <h2><?php echo $accommodation['name'].' - '.$num_records.' '.getAltText($texts['HOTEL'], $texts['HOTELS'], $num_records); ?></h2>
                    </div>
                    <div class="clearfix"></div>
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo getFromTemplate('common/get_hotels.php'); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="destination=<?php echo $destination_id; ?>">
                        <?php include(getFromTemplate('common/get_hotels.php', false)); ?>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
                $hotel_ids = implode('|', $hotel_ids);
                $result = $db->query('SELECT count(*) FROM pm_activity WHERE checked = 1 AND lang = '.LANG_ID.' AND hotels REGEXP \'[[:<:]]'.$hotel_ids.'[[:>:]]\'');
                if($result !== false){
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                if($num_records > 0){ ?>
                    <div class="col-md-12">
                        <h2><?php echo $accommodation['name'].' - '.$num_records.' '.getAltText($texts['ACTIVITY'], $texts['ACTIVITIES'], $num_records); ?></h2>
                    </div>
                    <div class="clearfix"></div>
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo getFromTemplate('common/get_activities.php'); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="page_id=<?php echo $page_id; ?>&page_alias=<?php echo $page['alias']; ?>&hotels=<?php echo $hotel_ids; ?>">
                        <?php include(getFromTemplate('common/get_activities.php', false)); ?>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</article>
