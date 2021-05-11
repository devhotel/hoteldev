<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
$item_id = (int)$_POST['id'];

$javascripts[] = DOCBASE.'js/plugins/sharrre/jquery.sharrre.min.js';

        $javascripts[] = DOCBASE.'js/plugins/jquery.event.calendar/js/jquery.event.calendar.js';
        $javascripts[] = DOCBASE.'js/plugins/jquery.event.calendar/js/languages/jquery.event.calendar.'.LANG_TAG.'.js';
        $stylesheets[] = array('file' => DOCBASE.'js/plugins/jquery.event.calendar/css/jquery.event.calendar.css', 'media' => 'all');

        $stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css', 'media' => 'all');
        $stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css', 'media' => 'all');
        $javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js';

        $stylesheets[] = array('file' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css', 'media' => 'all');
        $javascripts[] = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js';

        $stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
        $javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js';
        $javascripts[] = DOCBASE.'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';

        $stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
        $javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

        $javascripts[] = DOCBASE.'js/plugins/live-search/jquery.liveSearch.js';
?>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css">
    
    <!-- Comments -->
    <div class="white-popup-block review_booking_popup" id="popup-review-<?php echo $item_id; ?>">
    <h3 class="mb10"></h3>

    <div class="row">
        <div class="col-sm-12">
               <?php
                    $nb_comments = 0;
                    $item_type = 'hotel';
                       $result_comment = $db->query('SELECT * FROM pm_comment WHERE id_item = '.$item_id.' AND item_type = \''.$item_type.'\' AND checked = 1 ORDER BY add_date DESC');
                        if($result_comment !== false){
                            $nb_comments = $db->last_row_count();
                            if($nb_comments>0){ ?>
                            <h2>Rating & Reviews </h2>
                            <?php foreach($result_comment as $i => $row){ ?>
                               
                              <div class="ratingbox">
                                <div class="row">
                                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"><div class="rating_pic"><img src="<?php echo getFromTemplate("images/user.png"); ?>" alt="" /><?php echo $row['name']; ?></div></div>
                                  <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                                      <div class="rating_info">
                                         <input type="hidden" class="rating" value="<?php echo $row['rating']; ?>" data-rtl="<?php echo (RTL_DIR) ? true : false; ?>" data-size="xs" readonly="true" data-show-clear="false" data-show-caption="false">
                                        <p><?php echo nl2br($row['msg']); ?></p>
                                    </div>
                                </div>
                            </div>
                           </div>
                           <?php } ?>
                        <?php } ?>
                        <?php  }
                      
                       ?>
                    
        </div> 
    </div>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js" type="text/javascript"></script>

<script type="text/javascript">
     $(document).ready(function() {
         $('.rating').rating({
           min: 0,
           max: 5, 
           step: 0.1, 
           stars: 5,
           hoverEnabled: false,
           starCaptions: function(val){
            return val ;
        },
        });
    });

</script>