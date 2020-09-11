<?php
if($article_alias == '') err404();

$result = $db->query('SELECT * FROM pm_offer WHERE checked = 1 AND lang = '.LANG_ID.' AND alias = '.$db->quote($article_alias));
if($result !== false && $db->last_row_count() > 0){
    
    $offer = $result->fetch(PDO::FETCH_ASSOC);
    
    $offer_id = $offer['id'];
    $article_id = $offer_id;
    $title_tag = $offer['name'];
    $page_title = '';
    $page_subtitle = '';
    $page_alias = $pages[$page_id]['alias'].'/'.text_format($offer['alias']);
    
    $result_offer_file = $db->query('SELECT * FROM pm_offer_file WHERE id_item = '.$offer_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    if($result_offer_file !== false && $db->last_row_count() > 0){
        
        $row = $result_offer_file->fetch();
        
        $file_id = $row['id'];
        $filename = $row['file'];
        
        if(is_file(SYSBASE.'medias/offer/medium/'.$file_id.'/'.$filename))
            $page_img = getUrl(true).'/medias/offer/medium/'.$file_id.'/'.$filename;
    }
    
}else err404();

check_URI(DOCBASE.$page_alias);
$my_page_alias ='package';
$offer_alias = DOCBASE.$my_page_alias.'/'.text_format($offer['alias']);

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
            
            
                <div class="mb20">
                    
                    <div class="row mb10">
                       
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            	<div class="owl-carousel owlWrapper" data-items="1" data-autoplay="false" data-dots="true" data-nav="false" data-rtl="<?php echo (RTL_DIR) ? 'true' : 'false'; ?>">
                                <?php
                                if(!empty($offer['video'])){ ?>
                                    <div class="video-container">
                                        <iframe src="<?php echo $offer['video']; ?>" frameborder="0"></iframe>
                                    </div>
                                    <?php
                                }
                                $result_offer_file = $db->query('SELECT * FROM pm_offer_file WHERE id_item = '.$offer_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank');
                                if($result_offer_file !== false){
                                    foreach($result_offer_file as $i => $row){
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE.'medias/offer/big/'.$file_id.'/'.$filename;
                                        $thumbpath = DOCBASE.'medias/offer/big/'.$file_id.'/'.$filename;
                                        
                                        if(is_file($realpath)){ ?>
                                            <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>" class="img-responsive" style="max-height:600px;"/>
                                            <?php
                                        }
                                    }
                                } ?>
                            </div>
                            </div>
                            <div class="package_header">
                            <div class="col-md-9" class="offer_title" itemprop="title">
                              <h1 class="mb0"><?php echo $offer['name']; ?></h1>
                                <div class="date_validity"> Validity From  <span class="from_date"><?php echo gmdate('d F Y', $offer['day_start']); ?></span> <span class="to_date"></span>To <?php echo gmdate('d F Y', $offer['day_end']); ?>  </div>
                                <div class="capacity">
                                     <?php
                                      $adults = $offer['max_adults'];
                                      $kids = $offer['max_children'];
                                      if($adults > 0){
                                        echo '<span>'.$adults.'</span>';
                                        echo '<span>'.($adults>1?' Adults ':' Adult ').'</span>';
                                      }
                                      if($kids > 0){
                                        echo '<span>'.$kids.'</span>';
                                        echo '<span>'.($kids>1?' Kids':' Kid').'</span>';
                                      }
                                    ?>
                              </div>
                              </div>
                              <div class="col-md-3" class="offer_price" itemprop="price">
                                  <div class="price"><span>Offer Price</span>
                                   <?php echo formatPrice($offer['offer_price']*CURRENCY_RATE); ?>
                                  </div>
                                  <div class="duration">
                                     <?php
                                      $nights = $offer['no_day_night'];
                                      $days=($nights+1);
                                      echo '<span>'.$days.'</span>';
                                      echo '<span>'.($days>1?' Days ':' Day ').'</span>';
                                      echo '<span>'.$nights.'</span>';
                                      echo '<span>'.($nights>1?'Nights':'Night').'</span>';
                                    ?>
                                </div>
                              </div>
                            </div>
                            
                       
                    </div>
                    <div class="row mb10">
                        <div class="col-md-9 description" itemprop="description">
                           <h2>Highlight</h2>
                            <?php echo $offer['text']; ?>
                        </div>
                         <div class="col-md-3" class="facilities" itemprop="facilities">
                           <?php
                            $result_facility = $db->query('SELECT * FROM pm_facility WHERE lang = '.LANG_ID.' AND id IN('.$offer['facilities'].') ORDER BY id',PDO::FETCH_ASSOC);
                            if($result_facility !== false && $db->last_row_count() > 0){
                                echo '<h2>'.$texts['FEATURES_AMENITIES'].'</h2>';
                                echo '<ul class="facilities_list">';
                                 foreach($result_facility as $i => $row){
                                    $facility_id 	= $row['id'];
                                    $facility_name  = $row['name'];
                                    
                                    $result_facility_file = $db->query('SELECT * FROM pm_facility_file WHERE id_item = '.$facility_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1',PDO::FETCH_ASSOC);
                                    if($result_facility_file !== false && $db->last_row_count() == 1){
                                        $row = $result_facility_file->fetch();
                                        
                                        $file_id 	= $row['id'];
                                        $filename 	= $row['file'];
                                        $label	 	= $row['label'];
                                        
                                        $realpath	= SYSBASE.'medias/facility/big/'.$file_id.'/'.$filename;
                                        $thumbpath	= DOCBASE.'medias/facility/big/'.$file_id.'/'.$filename;
                                            
                                        if(is_file($realpath)){ ?>
                                            <li class="facility-icon">
                                                <img alt="<?php echo $facility_name; ?>" title="<?php echo $facility_name; ?>" src="<?php echo $thumbpath; ?>" class="tips"><?php echo $facility_name; ?>
                                            </li>
                                            <?php
                                        }
                                    }
                                  }
                               echo '</ul>';
                            } ?>
                        </div>
                         <div class="col-md-12" class="booking_div" itemprop="description">
                             <?php
                                $srt_date = gmdate('d/M/Y', $offer['day_start']);
                             ?>
                             <script>
                                 localStorage.setItem("offernight", '<?php echo $offer['no_day_night']; ?>');
                             </script>
                               <?php  $today= time(); 
                               if($offer['day_start']<=$today && $today <=$offer['day_end']){ ?>
                                  <form action="<?php echo $offer_alias;?>" method="post">
                                    <input type="hidden" name="nights" value="<?php echo $offer['no_day_night']; ?>">
                                    <input type="hidden" name="from_date" value="<?php echo $srt_date; ?>">
                                    <input type="hidden" name="to_date" value="">
                                    <input type="hidden" id="norm" name="ab[norm][]" value="1" />
                                    <input type="hidden" id="adlts" name="ab[adlts][]" value="<?php echo $offer['max_adults']; ?>" />
                                    <input type="hidden" id="kids" name="ab[kids][]" value="<?php echo $offer['max_children']; ?>" />
                                    <button class="offer_book_btn" type="submit">Book Now</button> 
                                 </form>
                             <?php } ?>
                         </div>
                    </div>
                </div>
            
        </div>
    </div>
</article>
